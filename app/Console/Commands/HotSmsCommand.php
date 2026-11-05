<?php

namespace App\Console\Commands;

use App\Services\HotSmsService;
use Illuminate\Console\Command;

class HotSmsCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'hotsms 
                            {action=balance : Action to perform: login|verify|balance|senders|send|set-token}
                            {--username= : HotSMS account username}
                            {--password= : HotSMS account password}
                            {--pin= : OTP pin code received via SMS during 2-step login}
                            {--mobile= : Recipient mobile number}
                            {--message= : Message text}
                            {--sender= : Sender name (optional)}
                            {--token= : Direct API Token to store}';

    /**
     * The console command description.
     */
    protected $description = 'HotSMS REST API v2 CLI utility (Login, Verify PIN, Check Balance, List Senders, Send Test SMS)';

    public function handle(HotSmsService $hotSms)
    {
        $action = strtolower($this->argument('action'));

        switch ($action) {
            case 'login':
                $this->info('بدء تسجيل الدخول إلى مزود HotSMS (المرحلة الأولى)...');
                $username = $this->option('username');
                $password = $this->option('password');
                $res = $hotSms->loginStepOne($username, $password);

                if (!empty($res['status']) && ($res['code'] ?? null) == 1002) {
                    $this->info('✓ ' . ($res['message'] ?? 'تم إرسال رمز التحقق إلى جوال الحساب بنجاح.'));
                    $this->comment('يرجى التحقق من جوالك وإدخال الرمز عبر الأمر:');
                    $this->line('php artisan hotsms verify --pin=XXXXXX');
                } else {
                    $this->error('فشل تسجيل الدخول: ' . ($res['message'] ?? json_encode($res)));
                }
                break;

            case 'verify':
                $pin = $this->option('pin') ?: $this->ask('أدخل رمز الـ PIN المستلم في رسالة SMS');
                if (!$pin) {
                    $this->error('رمز PIN مطلوب.');
                    return 1;
                }
                $this->info("جارِ التحقق من رمز PIN ({$pin}) وحفظ التوكن...");
                $res = $hotSms->loginStepTwo($pin, $this->option('username'), $this->option('password'));

                if (!empty($res['status']) && ($res['code'] ?? null) == 1001) {
                    $this->info('✓ ' . $res['message']);
                    $this->line('Token: ' . ($res['token'] ?? ''));
                } else {
                    $this->error('فشل التحقق: ' . ($res['message'] ?? json_encode($res)));
                }
                break;

            case 'balance':
                $this->info('جارِ فحص رصيد الحساب لدى HotSMS...');
                $res = $hotSms->getCredits();
                if ($res['status']) {
                    $this->info('✓ ' . $res['message']);
                } else {
                    $this->error('تعذر جلب الرصيد: ' . $res['message']);
                    $this->comment('تأكد من تسجيل الدخول أو إدخال التوكن في .env أولاً.');
                }
                break;

            case 'senders':
                $this->info('جارِ جلب أسماء المرسل المعتمدة للحساب...');
                $res = $hotSms->getSenders();
                if ($res['status']) {
                    $this->info('✓ أسماء المرسل المتاحة:');
                    foreach ($res['senders'] as $s) {
                        $this->line(" - {$s}");
                    }
                } else {
                    $this->error('تعذر جلب أسماء المرسل: ' . $res['message']);
                }
                break;

            case 'send':
                $mobile = $this->option('mobile') ?: $this->ask('أدخل رقم جوال المستلم');
                $message = $this->option('message') ?: $this->ask('أدخل نص الرسالة');
                $sender = $this->option('sender');

                $this->info("جارِ إرسال الرسالة إلى ({$mobile})...");
                $res = $hotSms->sendMessage($mobile, $message, $sender);

                if (!empty($res['status']) && ($res['code'] ?? null) == 1001) {
                    $this->info('✓ ' . $res['message']);
                } else {
                    $this->error('فشل الإرسال: ' . ($res['message'] ?? json_encode($res)));
                }
                break;

            case 'set-token':
                $token = $this->option('token') ?: $this->ask('أدخل قيمة الـ API Token');
                if (!$token) {
                    $this->error('التوكن مطلوب.');
                    return 1;
                }
                $hotSms->setToken($token);
                $this->info('✓ تم حفظ التوكن في ذاكرة النظام المؤقتة بنجاح.');
                break;

            default:
                $this->warn("إجراء غير معروف: {$action}");
                $this->line('الأوامر المتاحة: login | verify | balance | senders | send | set-token');
                break;
        }

        return 0;
    }
}

