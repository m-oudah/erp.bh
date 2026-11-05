<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class SmsGatewayService
{
    public function __construct(
        protected ?HotSmsService $hotSmsService = null
    ) {
        $this->hotSmsService = $hotSmsService ?? app(HotSmsService::class);
    }

    /**
     * Send an OTP SMS to a citizen mobile.
     * Guaranteed single SMS part (<= 70 characters in Arabic).
     */
    public function sendOtp(string $mobile, string $otpCode): array
    {
        $message = "بلدية بيت حانون: رمز دخولك لبوابة المواطنين هو {$otpCode}، احتفظ به.";
        
        $driver = config('services.sms.driver', env('SMS_DRIVER', 'hotsms'));

        // Always store debug OTP in local/testing session for seamless testing
        if (app()->environment('local', 'testing')) {
            session()->flash('dev_otp_code', $otpCode);
        }

        switch ($driver) {
            case 'hotsms':
                $result = $this->hotSmsService->sendMessage($mobile, $message);
                return [
                    'success' => $result['status'] ?? false,
                    'driver' => 'hotsms',
                    'message' => $result['message'] ?? 'تم إرسال رمز التحقق.',
                    'debug_code' => app()->environment('local', 'testing') ? $otpCode : null,
                    'data' => $result['data'] ?? [],
                ];

            case 'log':
            default:
                Log::info("SMS OTP dispatched to {$mobile}: {$message}");
                return [
                    'success' => true,
                    'driver' => 'log',
                    'message' => 'تم إرسال رمز التحقق بنجاح (وضع المحاكاة/التطوير)',
                    'debug_code' => app()->environment('local', 'testing') ? $otpCode : null,
                ];
        }
    }

    /**
     * Send general municipal SMS notice.
     */
    public function sendNotice(string|array $mobile, string $message, ?string $sender = null): array
    {
        return $this->hotSmsService->sendMessage($mobile, $message, $sender);
    }

    /**
     * Normalize mobile numbers to Palestinian format (e.g. 059XXXXXXX).
     */
    public static function normalizeMobile(string $mobile): string
    {
        $digits = preg_replace('/[^\d]/', '', $mobile);

        // Match 59XXXXXXX or 56XXXXXXX at the end
        if (preg_match('/(5[69]\d{7})$/', $digits, $matches)) {
            return '0' . $matches[1];
        }

        return $digits;
    }
}
