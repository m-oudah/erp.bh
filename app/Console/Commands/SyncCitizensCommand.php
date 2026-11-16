<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Buildings\BhmCustomer;

class SyncCitizensCommand extends Command
{
    protected $signature = 'citizens:sync {--backup-name= : Custom name for the backup table}';
    protected $description = 'Create a backup of bhm_customers and sync missing citizens from building owners, economical activities, drafts and clients.';

    public function handle()
    {
        $this->info('🚀 بدء عملية إنشاء النسخة الاحتياطية ومزامنة سجل المواطنين الموحد...');

        // 1. Create Backup Table
        $backupTableName = $this->option('backup-name') ?: 'bhm_customers_backup_' . date('Ymd_His');
        $this->info("📦 إنشاء جدول النسخة الاحتياطية: {$backupTableName}");

        DB::statement("CREATE TABLE IF NOT EXISTS `{$backupTableName}` LIKE `bhm_customers`");
        DB::statement("TRUNCATE TABLE `{$backupTableName}`");
        DB::statement("INSERT INTO `{$backupTableName}` SELECT * FROM `bhm_customers`");

        $originalCount = DB::table('bhm_customers')->count();
        $backupCount = DB::table($backupTableName)->count();

        if ($backupCount !== $originalCount) {
            $this->error("❌ فشل التحقق من النسخة الاحتياطية! العدد الأصلي: {$originalCount}، المحفوظ: {$backupCount}");
            return 1;
        }

        $this->info("✅ تم إنشاء النسخة الاحتياطية بنجاح! تم نسخ {$backupCount} مواطناً إلى الجدول {$backupTableName}.");

        // Also ensure a standard table `bhm_customers_backup` is updated for easy reference
        DB::statement("CREATE TABLE IF NOT EXISTS `bhm_customers_backup` LIKE `bhm_customers`");
        DB::statement("TRUNCATE TABLE `bhm_customers_backup`");
        DB::statement("INSERT INTO `bhm_customers_backup` SELECT * FROM `bhm_customers`");

        // 2. Fetch Existing ID Numbers in bhm_customers
        $existingMap = DB::table('bhm_customers')
            ->whereNotNull('id_no')
            ->where('id_no', '!=', '')
            ->pluck('id_no')
            ->map(fn($v) => trim($v))
            ->flip()
            ->toArray();

        $this->info("ℹ️ عدد المواطنين الحاليين برقم هوية في bhm_customers: " . count($existingMap));

        $candidates = [];

        // 3. Process Building Owners (bhm_building_owners)
        $this->info("🔍 فحص بيانات ملاك المباني (bhm_building_owners)...");
        $owners = DB::table('bhm_building_owners')
            ->whereNotNull('id_card')
            ->where('id_card', '!=', '')
            ->orderBy('id', 'desc')
            ->get(['id', 'id_card', 'first_name', 'second_name', 'third_name', 'sur_name', 'phone_number', 'created_at']);

        $fromOwners = 0;
        foreach ($owners as $o) {
            $id = trim($o->id_card);
            if (!is_numeric($id) || strlen($id) < 5) continue;
            if (isset($existingMap[$id])) continue;

            $name = trim(implode(' ', array_filter([$o->first_name, $o->second_name, $o->third_name, $o->sur_name])));
            if ($name === '') $name = 'مالك عقار ' . $id;
            $phone = trim($o->phone_number ?? '');

            if (!isset($candidates[$id])) {
                $candidates[$id] = [
                    'name' => $name,
                    'id_no' => $id,
                    'mobile' => $phone,
                    'notes' => 'تمت المزامنة تلقائياً من بيانات ملاك المباني',
                    'created_at' => $o->created_at ?? now(),
                    'updated_at' => now(),
                ];
                $fromOwners++;
            } else {
                if (mb_strlen($name) > mb_strlen($candidates[$id]['name'])) {
                    $candidates[$id]['name'] = $name;
                }
                if (empty($candidates[$id]['mobile']) && !empty($phone)) {
                    $candidates[$id]['mobile'] = $phone;
                }
            }
        }
        $this->info("  ↳ تم استخراج {$fromOwners} مواطن فريد من ملاك المباني.");

        // 4. Process Economical Activities (bhm_economical)
        $fromEcon = 0;
        if (Schema::hasTable('bhm_economical')) {
            $this->info("🔍 فحص بيانات الأنشطة الاقتصادية والحرف (bhm_economical)...");
            $econs = DB::table('bhm_economical')
                ->whereNotNull('id_card')
                ->where('id_card', '!=', '')
                ->orderBy('id', 'desc')
                ->get(['id_card', 'name', 'trade_name', 'job_mobile', 'job_phone', 'created_at']);

            foreach ($econs as $e) {
                $id = trim($e->id_card);
                if (!is_numeric($id) || strlen($id) < 5) continue;
                if (isset($existingMap[$id])) continue;

                $name = trim($e->name ?: $e->trade_name ?: '');
                if ($name === '') $name = 'صاحب نشاط اقتصادي ' . $id;
                $phone = trim($e->job_mobile ?: $e->job_phone ?: '');

                if (!isset($candidates[$id])) {
                    $candidates[$id] = [
                        'name' => $name,
                        'id_no' => $id,
                        'mobile' => $phone,
                        'notes' => 'تمت المزامنة تلقائياً من بيانات الأنشطة الاقتصادية',
                        'created_at' => $e->created_at ?? now(),
                        'updated_at' => now(),
                    ];
                    $fromEcon++;
                } else {
                    if (empty($candidates[$id]['mobile']) && !empty($phone)) {
                        $candidates[$id]['mobile'] = $phone;
                    }
                }
            }
            $this->info("  ↳ تم استخراج {$fromEcon} مواطن فريد إضافي من الأنشطة الاقتصادية.");
        }

        // 5. Process Draft Transactions (bhm_draft_transactions)
        $fromDraft = 0;
        if (Schema::hasTable('bhm_draft_transactions')) {
            $this->info("🔍 فحص طلبات البوابة الإلكترونية (bhm_draft_transactions)...");
            $drafts = DB::table('bhm_draft_transactions')
                ->whereNotNull('national_id')
                ->where('national_id', '!=', '')
                ->orderBy('id', 'desc')
                ->get(['national_id', 'full_name', 'phone', 'created_at']);

            foreach ($drafts as $d) {
                $id = trim($d->national_id);
                if (!is_numeric($id) || strlen($id) < 5) continue;
                if (isset($existingMap[$id])) continue;

                $name = trim($d->full_name);
                if ($name === '') $name = 'مقدم طلب بوابة ' . $id;
                $phone = trim($d->phone ?? '');

                if (!isset($candidates[$id])) {
                    $candidates[$id] = [
                        'name' => $name,
                        'id_no' => $id,
                        'mobile' => $phone,
                        'notes' => 'تمت المزامنة تلقائياً من طلبات البوابة الإلكترونية',
                        'created_at' => $d->created_at ?? now(),
                        'updated_at' => now(),
                    ];
                    $fromDraft++;
                }
            }
            $this->info("  ↳ تم استخراج {$fromDraft} مواطن فريد إضافي من طلبات البوابة.");
        }

        // 6. Process Previous Clients Table (bhm_clients)
        $fromClients = 0;
        if (Schema::hasTable('bhm_clients')) {
            $this->info("🔍 فحص سجل المشتركين السابق (bhm_clients)...");
            $clients = DB::table('bhm_clients')
                ->whereNotNull('id_number')
                ->where('id_number', '!=', '')
                ->orderBy('id', 'desc')
                ->get(['id_number', 'name', 'mobile', 'created_at']);

            foreach ($clients as $c) {
                $id = trim($c->id_number);
                if (!is_numeric($id) || strlen($id) < 5) continue;
                if (isset($existingMap[$id])) continue;

                $name = trim($c->name);
                if ($name === '') $name = 'مشترك سابق ' . $id;
                $phone = trim($c->mobile ?? '');

                if (!isset($candidates[$id])) {
                    $candidates[$id] = [
                        'name' => $name,
                        'id_no' => $id,
                        'mobile' => $phone,
                        'notes' => 'تمت المزامنة تلقائياً من سجل المشتركين السابق',
                        'created_at' => $c->created_at ?? now(),
                        'updated_at' => now(),
                    ];
                    $fromClients++;
                }
            }
            $this->info("  ↳ تم استخراج {$fromClients} مواطن فريد إضافي من المشتركين السابقين.");
        }

        $totalNew = count($candidates);
        $this->info("📊 إجمالي المواطنين الجدد الجاهزين للإضافة: {$totalNew}");

        if ($totalNew === 0) {
            $this->info("✅ جميع المواطنين متطابقون وموجودون مسبقاً في سجل المواطنين. لا توجد سجلات جديدة لإضافتها.");
            return 0;
        }

        // 7. Insert Candidates in Chunks within Transaction
        $this->info("💾 جاري إضافة المواطنين إلى جدول bhm_customers...");
        $chunks = array_chunk(array_values($candidates), 250);

        DB::beginTransaction();
        try {
            $bar = $this->output->createProgressBar(count($chunks));
            $bar->start();

            foreach ($chunks as $chunk) {
                DB::table('bhm_customers')->insert($chunk);
                $bar->advance();
            }

            $bar->finish();
            $this->newLine();

            DB::commit();
            $this->info("✅ تمت إضافة كافة السجلات بنجاح!");
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error("❌ حدث خطأ أثناء الحفظ: " . $e->getMessage());
            return 1;
        }

        $finalCount = DB::table('bhm_customers')->count();
        $this->info("🎉 إجمالي عدد المواطنين في bhm_customers الآن: {$finalCount} (سابقاً: {$originalCount} + {$totalNew} جديد)");

        // 8. Verification of target citizen 410690341
        $target = BhmCustomer::where('id_no', '410690341')->first();
        if ($target) {
            $this->info("✨ التحقق من مالك المبنى 7226:");
            $this->info("   - المعرف: #{$target->id}");
            $this->info("   - الاسم: {$target->name}");
            $this->info("   - رقم الهوية: {$target->id_no}");
            $this->info("   - الهاتف: {$target->mobile}");
            $this->info("   - الملاحظات: {$target->notes}");
        }

        return 0;
    }
}

