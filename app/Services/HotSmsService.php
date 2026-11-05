<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HotSmsService
{
    protected string $baseUrl;
    protected ?string $username;
    protected ?string $password;
    protected string $defaultSender;
    protected ?string $configuredToken;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.hotsms.base_url', 'https://hotsms.ps/api/rest/v2'), '/');
        $this->username = config('services.hotsms.username');
        $this->password = config('services.hotsms.password');
        $this->defaultSender = config('services.hotsms.sender', 'BeitHanon');
        $this->configuredToken = config('services.hotsms.token');
    }

    /**
     * Get the active API Token from configuration or persistent cache.
     */
    public function getToken(): ?string
    {
        if (!empty($this->configuredToken)) {
            return $this->configuredToken;
        }

        return Cache::get('hotsms_api_token');
    }

    /**
     * Store active API Token into cache.
     */
    public function setToken(string $token, int $days = 30): void
    {
        Cache::put('hotsms_api_token', $token, now()->addDays($days));
    }

    /**
     * Clear cached API Token.
     */
    public function clearToken(): void
    {
        Cache::forget('hotsms_api_token');
    }

    /**
     * Check if credentials or token are configured.
     */
    public function isConfigured(): bool
    {
        return !empty($this->getToken()) || (!empty($this->username) && !empty($this->password));
    }

    /**
     * Step 1: Send credentials -> sends SMS OTP to account owner mobile.
     * Endpoint: POST /auth/login
     */
    public function loginStepOne(?string $username = null, ?string $password = null): array
    {
        $user = $username ?: $this->username;
        $pass = $password ?: $this->password;

        if (!$user || !$pass) {
            return [
                'status' => false,
                'code' => 1004,
                'message' => 'اسم المستخدم وكلمة المرور غير محددين في الإعدادات (.env).',
            ];
        }

        try {
            $response = Http::timeout(15)
                ->acceptJson()
                ->post("{$this->baseUrl}/auth/login", [
                    'user_name' => $user,
                    'user_pass' => $pass,
                ]);

            $json = $response->json();

            Log::info('HotSMS Login Step 1 Response:', $json ?? ['raw' => $response->body()]);

            return $json ?: [
                'status' => false,
                'code' => $response->status(),
                'message' => 'تعذر الاتصال بخدمة HotSMS: ' . $response->body(),
            ];
        } catch (\Exception $e) {
            Log::error('HotSMS Login Step 1 Exception: ' . $e->getMessage());
            return [
                'status' => false,
                'code' => 500,
                'message' => 'خطأ في الاتصال بالخادم: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Step 2: Send credentials WITH the pin_code received via SMS -> returns api_token.
     * Endpoint: POST /auth/login
     */
    public function loginStepTwo(string $pinCode, ?string $username = null, ?string $password = null): array
    {
        $user = $username ?: $this->username;
        $pass = $password ?: $this->password;

        if (!$user || !$pass) {
            return [
                'status' => false,
                'code' => 1004,
                'message' => 'اسم المستخدم وكلمة المرور غير محددين في الإعدادات.',
            ];
        }

        try {
            $response = Http::timeout(15)
                ->acceptJson()
                ->post("{$this->baseUrl}/auth/login", [
                    'user_name' => $user,
                    'user_pass' => $pass,
                    'pin_code' => trim($pinCode),
                ]);

            $json = $response->json();

            Log::info('HotSMS Login Step 2 Response:', $json ?? ['raw' => $response->body()]);

            if ($response->successful() && !empty($json['status']) && !empty($json['data']['api_token'])) {
                $token = $json['data']['api_token'];
                $this->setToken($token);
                return [
                    'status' => true,
                    'code' => 1001,
                    'message' => 'تم تسجيل الدخول بنجاح وحفظ التوكن في النظام.',
                    'token' => $token,
                    'data' => $json['data'],
                ];
            }

            return $json ?: [
                'status' => false,
                'code' => $response->status(),
                'message' => 'فشل التحقق من رمز PIN: ' . $response->body(),
            ];
        } catch (\Exception $e) {
            Log::error('HotSMS Login Step 2 Exception: ' . $e->getMessage());
            return [
                'status' => false,
                'code' => 500,
                'message' => 'خطأ أثناء تأكيد الرمز: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send SMS Message.
     * Endpoint: POST /messages/send
     */
    public function sendMessage(string|array $mobile, string $message, ?string $sender = null): array
    {
        $senderName = $sender ?: $this->defaultSender;
        $formattedMobiles = $this->formatMobileNumbers($mobile);

        // If not configured, log to file and return mock success so application never breaks
        if (!$this->isConfigured()) {
            Log::info("[HotSMS Mock Mode] SMS to {$formattedMobiles} [Sender: {$senderName}]: {$message}");
            return [
                'status' => true,
                'code' => 1001,
                'message' => 'تم الإرسال (وضع المحاكاة المحلي - بانتظار تزويد بيانات الحساب)',
                'data' => [
                    'mock' => true,
                    'mobile' => $formattedMobiles,
                    'message' => $message,
                    'sender' => $senderName,
                ],
            ];
        }

        $token = $this->getToken();

        if (!$token) {
            Log::warning("[HotSMS] No token available. Please run 2-step login or provide HOTSMS_API_TOKEN.");
            return [
                'status' => false,
                'code' => 1004,
                'message' => 'الحساب غير مسجل الدخول، يرجى تفعيل توكن HotSMS أولاً.',
            ];
        }

        try {
            $response = Http::timeout(20)
                ->withToken($token)
                ->withHeaders(['X-API-TOKEN' => $token])
                ->acceptJson()
                ->post("{$this->baseUrl}/messages/send", [
                    'mobile' => $formattedMobiles,
                    'message' => $message,
                    'sender' => $senderName,
                ]);

            $json = $response->json();

            // Handle invalid token / expired token
            if ($response->status() === 401 || (isset($json['code']) && in_array($json['code'], [1004, 1401, 1402]))) {
                Log::warning('[HotSMS] API Token rejected or expired. Clearing cached token.');
                $this->clearToken();
            }

            if ($response->successful() && !empty($json['status']) && ($json['code'] ?? null) == 1001) {
                return [
                    'status' => true,
                    'code' => 1001,
                    'message' => 'تم إرسال الرسالة بنجاح عبر مزود HotSMS.',
                    'data' => $json['data'] ?? [],
                ];
            }

            Log::error('HotSMS Send Failed:', $json ?? ['status' => $response->status(), 'body' => $response->body()]);

            return [
                'status' => false,
                'code' => $json['code'] ?? $response->status(),
                'message' => $json['message'] ?? 'فشل إرسال الرسالة عبر المزود.',
                'errors' => $json['errors'] ?? [],
            ];
        } catch (\Exception $e) {
            Log::error('HotSMS Send Exception: ' . $e->getMessage());
            return [
                'status' => false,
                'code' => 500,
                'message' => 'حدث خطأ أثناء الاتصال بمزود HotSMS: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get remaining credits / balance.
     * Endpoint: GET /credits
     */
    public function getCredits(): array
    {
        $token = $this->getToken();
        if (!$token) {
            return [
                'status' => false,
                'message' => 'لا يوجد توكن دخول مسجل.',
                'credits' => null,
            ];
        }

        try {
            $response = Http::timeout(10)
                ->withToken($token)
                ->withHeaders(['X-API-TOKEN' => $token])
                ->acceptJson()
                ->get("{$this->baseUrl}/credits");

            $json = $response->json();
            if ($response->successful() && !empty($json['status'])) {
                return [
                    'status' => true,
                    'credits' => $json['data']['credits'] ?? 0,
                    'message' => 'الرصيد الحالي: ' . ($json['data']['credits'] ?? 0) . ' نقطة.',
                ];
            }

            return [
                'status' => false,
                'credits' => null,
                'message' => $json['message'] ?? 'تعذر جلب الرصيد.',
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'credits' => null,
                'message' => 'خطأ في الاتصال: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get approved sender names for this account.
     * Endpoint: GET /senders
     */
    public function getSenders(): array
    {
        $token = $this->getToken();
        if (!$token) {
            return [
                'status' => false,
                'message' => 'لا يوجد توكن دخول مسجل.',
                'senders' => [],
            ];
        }

        try {
            $response = Http::timeout(10)
                ->withToken($token)
                ->withHeaders(['X-API-TOKEN' => $token])
                ->acceptJson()
                ->get("{$this->baseUrl}/senders");

            $json = $response->json();
            if ($response->successful() && !empty($json['status'])) {
                return [
                    'status' => true,
                    'senders' => $json['data']['senders'] ?? [],
                    'message' => 'تم جلب أسماء المرسل المعتمدة بنجاح.',
                ];
            }

            return [
                'status' => false,
                'senders' => [],
                'message' => $json['message'] ?? 'تعذر جلب أسماء المرسل.',
            ];
        } catch (\Exception $e) {
            return [
                'status' => false,
                'senders' => [],
                'message' => 'خطأ في الاتصال: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Format mobile number(s) to clean Palestinian / International standards.
     * E.g. "0599123456" -> "970599123456" or "0599123456"
     */
    public function formatMobileNumbers(string|array $mobiles): string
    {
        $list = is_array($mobiles) ? $mobiles : explode(',', $mobileString = str_replace([';', '|', ' '], ',', $mobiles));
        
        $cleaned = [];
        foreach ($list as $m) {
            $m = trim($m);
            if (empty($m)) continue;
            
            // Clean non-digits
            $digits = preg_replace('/[^\d]/', '', $m);
            
            // Normalize Palestinian local mobiles: 059XXXXXXX -> 97059XXXXXXX
            if (preg_match('/^0?(5[69]\d{7})$/', $digits, $matches)) {
                $cleaned[] = '970' . $matches[1];
            } elseif (preg_match('/^972(5[69]\d{7})$/', $digits, $matches)) {
                $cleaned[] = '970' . $matches[1];
            } else {
                $cleaned[] = $digits;
            }
        }

        return implode(',', $cleaned);
    }
}

