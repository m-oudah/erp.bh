<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Portal\CitizenAccessLog;
use App\Models\Portal\CitizenOtpVerification;
use App\Services\CitizenDataService;
use App\Services\SmsGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class PortalController extends Controller
{
    public function __construct(
        protected CitizenDataService $citizenService,
        protected SmsGatewayService $smsService
    ) {}

    /**
     * Show Citizen Login Page.
     */
    public function showLogin()
    {
        if (session()->has('portal_citizen_id')) {
            return redirect()->route('portal.dashboard');
        }

        return view('portal.auth.login');
    }

    /**
     * Send OTP SMS to Citizen.
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'id_card' => 'required|string|min:7|max:15',
            'mobile' => 'required|string|min:9|max:20',
        ], [
            'id_card.required' => 'يرجى إدخال رقم الهوية الوطنية.',
            'id_card.min' => 'رقم الهوية الوطنية يجب ألا يقل عن 7 أرقام.',
            'mobile.required' => 'يرجى إدخال رقم الجوال المسجل لدى البلدية.',
        ]);

        $idCard = trim($request->id_card);
        $mobile = trim($request->mobile);
        $normMobile = SmsGatewayService::normalizeMobile($mobile);

        // IP / ID Rate Limiting (Anti-Spam)
        $throttleKey = 'portal_otp:' . $request->ip() . ':' . $idCard;
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withInput()->with('error', "لقد تجاوزت الحد الأقصى للمحاولات. يرجى الانتظار {$seconds} ثانية والمحاولة مجدداً.");
        }

        // Verify citizen records in municipal database
        $citizen = $this->citizenService->findAndMatchCitizen($idCard, $mobile);

        if (!$citizen) {
            RateLimiter::hit($throttleKey, 300);
            CitizenAccessLog::logAction($idCard, $mobile, null, 'login_failed_not_found', 'رقم الهوية غير مسجل في النظام');
            return back()->withInput()->with('error', 'رقم الهوية المدخل غير مسجل في قيود وأرشيف بلدية بيت حانون. يرجى مراجعة قسم خدمات الجمهور.');
        }

        if (empty($citizen['phone_matched'])) {
            RateLimiter::hit($throttleKey, 300);
            CitizenAccessLog::logAction($idCard, $mobile, $citizen['name'], 'login_failed_phone_mismatch', 'رقم الهاتف غير مطابق للسجلات');
            return back()->withInput()->with('error', 'رقم الجوال المدخل غير مطابق لرقم الجوال المسجل لدى البلدية لصاحب هذه الهوية. يرجى مراجعة البلدية لتحديث بيانات الاتصال.');
        }

        // Check if citizen already has a verified login code (Subsequent Login)
        $existingVerifiedOtp = CitizenOtpVerification::where('id_card', $idCard)
            ->where('is_verified', true)
            ->latest('id')
            ->first();

        if ($existingVerifiedOtp) {
            // Returning citizen: Do NOT send an SMS! Use the established code
            session([
                'portal_pending_id' => $idCard,
                'portal_pending_mobile' => $normMobile,
                'portal_pending_name' => $citizen['name'],
                'portal_is_first_login' => false,
                'portal_otp_sent_at' => now()->timestamp,
            ]);

            CitizenAccessLog::logAction($idCard, $normMobile, $citizen['name'], 'login_prompt_existing_code', 'طلب إدخال رمز الدخول المعتمد مسبقاً دون إرسال SMS');

            return redirect()->route('portal.verify-otp')
                ->with('success', 'أهلاً بك مجدداً! أدخل رمز الدخول المعتمد الخاص بك (الذي وصلك سابقاً عبر رسالة SMS).');
        }

        // Check if there is an active OTP sent within the last 60 seconds for new registrations
        $recentOtp = CitizenOtpVerification::where('id_card', $idCard)
            ->where('created_at', '>=', now()->subSeconds(60))
            ->first();

        if ($recentOtp) {
            return back()->withInput()->with('error', 'تم إرسال رمز تحقق مسبقاً، يرجى الانتظار دقيقة واحدة قبل طلب رمز جديد.');
        }

        // First-Time Login: Generate a real random 6-digit code (never static 123456)
        $otpCode = sprintf('%06d', random_int(100000, 999999));

        // Save OTP with long validity as permanent code
        CitizenOtpVerification::create([
            'id_card' => $idCard,
            'mobile' => $normMobile,
            'otp_code' => $otpCode,
            'expires_at' => now()->addYears(5),
            'is_verified' => false,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Dispatch SMS via HotSMS
        $smsResult = $this->smsService->sendOtp($normMobile, $otpCode);

        // Store pending session
        session([
            'portal_pending_id' => $idCard,
            'portal_pending_mobile' => $normMobile,
            'portal_pending_name' => $citizen['name'],
            'portal_is_first_login' => true,
            'portal_otp_sent_at' => now()->timestamp,
        ]);

        CitizenAccessLog::logAction($idCard, $normMobile, $citizen['name'], 'otp_sent_first_login', 'تم إرسال رمز التحقق لأول تسجيل دخول');

        return redirect()->route('portal.verify-otp')->with('success', 'تم إرسال رمز التحقق لأول مرة إلى هاتفك المحمول عبر SMS. احتفظ بهذا الرمز لدخولك الدائم للنظام.');
    }

    /**
     * Direct Login with National ID and existing permanent code.
     */
    public function directLogin(Request $request)
    {
        $request->validate([
            'id_card' => 'required|string|min:7|max:20',
            'otp_code' => 'required|string|size:6',
        ], [
            'id_card.required' => 'يرجى إدخال رقم الهوية الوطنية.',
            'id_card.min' => 'رقم الهوية الوطنية يجب ألا يقل عن 7 أرقام.',
            'otp_code.required' => 'يرجى إدخال رمز الدخول المكون من 6 أرقام.',
            'otp_code.size' => 'رمز الدخول يجب أن يتكون من 6 أرقام تماماً.',
        ]);

        $idCard = trim($request->id_card);
        $otpCode = trim($request->otp_code);

        // IP / ID Rate Limiting
        $throttleKey = 'portal_direct_login:' . $request->ip() . ':' . $idCard;
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withInput()->with('error', "لقد تجاوزت الحد الأقصى للمحاولات الخاطئة. يرجى الانتظار {$seconds} ثانية.");
        }

        // Verify citizen identity in municipal database
        $citizen = $this->citizenService->findCitizenByIdCard($idCard);
        if (!$citizen) {
            RateLimiter::hit($throttleKey, 300);
            return back()->withInput()->with('error', 'رقم الهوية المدخل غير مسجل في قيود وأرشيف بلدية بيت حانون.');
        }

        // Look up verified permanent OTP
        $verification = CitizenOtpVerification::where('id_card', $idCard)
            ->where('is_verified', true)
            ->latest('id')
            ->first();

        if (!$verification) {
            return back()->withInput()->with('error', 'لم يتم تفعيل رمز دخول لهذا الحساب بعد. يرجى استخدام خيار "أول تسجيل دخول" لاستلام رمزك عبر SMS لأول مرة.');
        }

        if ($verification->attempts >= 10) {
            return back()->withInput()->with('error', 'تم تجاوز عدد محاولات إدخال الرمز الخاطئة. يرجى الانتقال إلى خيار "استلام رمز جديد عبر SMS".');
        }

        if ($verification->otp_code !== $otpCode) {
            RateLimiter::hit($throttleKey, 300);
            $verification->increment('attempts');
            $remaining = 10 - $verification->attempts;
            return back()->withInput()->with('error', "رمز الدخول غير صحيح. تبقى لك {$remaining} محاولات قبل الحظر.");
        }

        // Reset attempts
        $verification->update(['attempts' => 0]);

        // Login citizen in session
        session([
            'portal_citizen_id' => $idCard,
            'portal_citizen_mobile' => $verification->mobile,
            'portal_citizen_name' => $citizen['name'],
            'portal_logged_in_at' => now()->toDateTimeString(),
        ]);

        CitizenAccessLog::logAction($idCard, $verification->mobile, $citizen['name'], 'direct_login_success', 'تسجيل دخول مباشر ناجح برمز الدخول المعتمد');

        return redirect()->route('portal.dashboard')
            ->with('success', "مرحباً بك {$citizen['name']} في بوابة الخدمات الإلكترونية لبلدية بيت حانون.");
    }

    /**
     * Show Verify OTP Page.
     */
    public function showVerifyOtp()
    {
        if (!session()->has('portal_pending_id')) {
            return redirect()->route('portal.login');
        }

        $idCard = session('portal_pending_id');
        $mobile = session('portal_pending_mobile');
        $name = session('portal_pending_name');
        $isFirstLogin = session('portal_is_first_login', false);
        $sentAt = session('portal_otp_sent_at', now()->timestamp);
        $resendAvailableIn = max(0, 60 - (now()->timestamp - $sentAt));

        // Masked mobile for privacy (e.g. 059****123)
        $maskedMobile = (strlen($mobile) >= 7) 
            ? substr($mobile, 0, 3) . '****' . substr($mobile, -3) 
            : $mobile;

        return view('portal.auth.verify-otp', compact(
            'idCard', 'mobile', 'maskedMobile', 'name', 'isFirstLogin', 'resendAvailableIn'
        ));
    }

    /**
     * Verify OTP and Login Citizen.
     */
    public function verifyOtp(Request $request)
    {
        if (!session()->has('portal_pending_id')) {
            return redirect()->route('portal.login')->with('error', 'انتهت الجلسة، يرجى المحاولة من جديد.');
        }

        $request->validate([
            'otp_code' => 'required|string|size:6',
        ], [
            'otp_code.required' => 'يرجى إدخال رمز التحقق المكون من 6 أرقام.',
            'otp_code.size' => 'رمز التحقق يجب أن يتكون من 6 أرقام تماماً.',
        ]);

        $idCard = session('portal_pending_id');
        $mobile = session('portal_pending_mobile');
        $name = session('portal_pending_name');
        $inputOtp = trim($request->otp_code);

        // Find the latest verification record for this citizen
        $verification = CitizenOtpVerification::where('id_card', $idCard)
            ->latest('id')
            ->first();

        if (!$verification) {
            return redirect()->route('portal.login')->with('error', 'لا يوجد رمز دخول نشط، يرجى طلب رمز جديد.');
        }

        if ($verification->attempts >= 10) {
            return redirect()->route('portal.login')->with('error', 'تم تجاوز عدد محاولات إدخال الرمز الخاطئة. يرجى طلب رمز جديد عبر SMS.');
        }

        if ($verification->otp_code !== $inputOtp) {
            $verification->increment('attempts');
            $remaining = 10 - $verification->attempts;
            return back()->with('error', "رمز الدخول غير صحيح. يرجى التأكد والمحاولة مجدداً (تبقى {$remaining} محاولات).");
        }

        // Success! Mark verified and keep active permanently
        $verification->update([
            'is_verified' => true,
            'expires_at' => now()->addYears(5),
            'attempts' => 0,
        ]);

        // Login citizen in session
        session([
            'portal_citizen_id' => $idCard,
            'portal_citizen_mobile' => $mobile,
            'portal_citizen_name' => $name,
            'portal_logged_in_at' => now()->toDateTimeString(),
        ]);

        // Clean up pending session
        session()->forget(['portal_pending_id', 'portal_pending_mobile', 'portal_pending_name', 'portal_otp_sent_at', 'portal_is_first_login']);

        CitizenAccessLog::logAction($idCard, $mobile, $name, 'login_success', 'تسجيل دخول ناجح للمواطن وتثبيت رمز الدخول');

        return redirect()->route('portal.dashboard')
            ->with('success', "مرحباً بك {$name} في بوابة الخدمات الإلكترونية لبلدية بيت حانون.");
    }

    /**
     * Resend / Reset OTP.
     */
    public function resendOtp(Request $request)
    {
        if (!session()->has('portal_pending_id')) {
            return redirect()->route('portal.login');
        }

        $idCard = session('portal_pending_id');
        $mobile = session('portal_pending_mobile');
        $name = session('portal_pending_name');

        // Check if 60 seconds have passed
        $sentAt = session('portal_otp_sent_at', 0);
        if (now()->timestamp - $sentAt < 60) {
            $diff = 60 - (now()->timestamp - $sentAt);
            return back()->with('error', "يرجى الانتظار {$diff} ثانية قبل إعادة إرسال الرمز.");
        }

        // Generate a new random 6-digit code
        $otpCode = sprintf('%06d', random_int(100000, 999999));

        CitizenOtpVerification::create([
            'id_card' => $idCard,
            'mobile' => $mobile,
            'otp_code' => $otpCode,
            'expires_at' => now()->addYears(5),
            'is_verified' => false,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $smsResult = $this->smsService->sendOtp($mobile, $otpCode);

        session([
            'portal_otp_sent_at' => now()->timestamp,
            'portal_is_first_login' => false,
        ]);

        CitizenAccessLog::logAction($idCard, $mobile, $name, 'otp_reset_resend', 'تم توليد وإرسال رمز دخول جديد عبر SMS');

        $flashMsg = 'تم توليد وإرسال رمز دخول جديد إلى هاتفك المحمول عبر SMS بنجاح.';
        if (!empty($smsResult['debug_code'])) {
            $flashMsg .= " (كود التجربة: {$smsResult['debug_code']})";
        }

        return back()->with('success', $flashMsg);
    }

    /**
     * Logout Citizen.
     */
    public function logout()
    {
        if ($id = session('portal_citizen_id')) {
            CitizenAccessLog::logAction($id, session('portal_citizen_mobile'), session('portal_citizen_name'), 'logout', 'تسجيل خروج');
        }

        session()->forget([
            'portal_citizen_id',
            'portal_citizen_mobile',
            'portal_citizen_name',
            'portal_logged_in_at',
            'portal_pending_id',
            'portal_pending_mobile',
            'portal_pending_name',
            'portal_otp_sent_at'
        ]);

        return redirect()->route('portal.login')->with('success', 'تم تسجيل الخروج بنجاح. نتمنى لك يوماً سعيداً.');
    }

    /**
     * Citizen Overview Dashboard.
     */
    public function dashboard()
    {
        $idCard = session('portal_citizen_id');
        $summary = $this->citizenService->getDashboardSummary($idCard);
        $citizenName = session('portal_citizen_name');
        $citizenMobile = session('portal_citizen_mobile');

        CitizenAccessLog::logAction($idCard, $citizenMobile, $citizenName, 'view_dashboard');

        return view('portal.dashboard', compact('summary', 'citizenName', 'citizenMobile', 'idCard'));
    }

    /**
     * Citizen Buildings Page.
     */
    public function buildings()
    {
        $idCard = session('portal_citizen_id');
        $buildings = $this->citizenService->getCitizenBuildings($idCard);
        $citizenName = session('portal_citizen_name');

        CitizenAccessLog::logAction($idCard, session('portal_citizen_mobile'), $citizenName, 'view_buildings');

        return view('portal.buildings.index', compact('buildings', 'citizenName', 'idCard'));
    }

    /**
     * Citizen Units Page (Residential & Commercial).
     */
    public function units()
    {
        $idCard = session('portal_citizen_id');
        $units = $this->citizenService->getCitizenUnits($idCard);
        $citizenName = session('portal_citizen_name');

        CitizenAccessLog::logAction($idCard, session('portal_citizen_mobile'), $citizenName, 'view_units');

        return view('portal.units.index', compact('units', 'citizenName', 'idCard'));
    }

    /**
     * Citizen Crafts and Industries Page.
     */
    public function crafts()
    {
        $idCard = session('portal_citizen_id');
        $crafts = $this->citizenService->getCitizenCrafts($idCard);
        $citizenName = session('portal_citizen_name');

        CitizenAccessLog::logAction($idCard, session('portal_citizen_mobile'), $citizenName, 'view_crafts');

        return view('portal.crafts.index', compact('crafts', 'citizenName', 'idCard'));
    }
}

