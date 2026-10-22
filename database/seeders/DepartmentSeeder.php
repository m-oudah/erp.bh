<?php

namespace Database\Seeders;

use App\Models\Correspondence\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'مكتب رئيس البلدية والمجلس البلدي',
                'code' => 'MAYOR',
                'description' => 'المكتب التنفيذي وقرارات المجلس البلدي والتوجيهات العليا',
                'order' => 1,
            ],
            [
                'name' => 'دائرة الهندسة والتنظيم',
                'code' => 'ENG',
                'description' => 'إدارة التخطيط الحضري، تراخيص البناء، المشاريع، والمخططات التفصيلية',
                'order' => 2,
            ],
            [
                'name' => 'الدائرة المالية والمحاسبة',
                'code' => 'FIN',
                'description' => 'الشؤون المالية، الموازنات، النفقات، والتدقيق المالي',
                'order' => 3,
            ],
            [
                'name' => 'قسم الحرف والصناعات',
                'code' => 'CRAFTS',
                'description' => 'تراخيص المهن، المحلات، الأنشطة الاقتصادية والرقابة الحرفية',
                'order' => 4,
            ],
            [
                'name' => 'قسم الجباية والتحصيل',
                'code' => 'REV',
                'description' => 'تحصيل الرسوم البلدية، مستحقات الخدمات، وضريبة المعارف',
                'order' => 5,
            ],
            [
                'name' => 'الدائرة القانونية',
                'code' => 'LEGAL',
                'description' => 'الاستشارات القانونية، العقود، الدعاوى القضائية، ومتابعة المخالفات',
                'order' => 6,
            ],
            [
                'name' => 'قسم العلاقات العامة والإعلام',
                'code' => 'PR',
                'description' => 'التواصل المؤسسي، الإعلام، الفعاليات المجتمعية، والشكاوى العامة',
                'order' => 7,
            ],
            [
                'name' => 'سكرتاريا البلدية وخدمات الجمهور',
                'code' => 'SEC',
                'description' => 'استقبال المعاملات، الأرشيف العام، والبريد الوارد والصادر الخارجي',
                'order' => 8,
            ],
            [
                'name' => 'دائرة الصحة والبيئة',
                'code' => 'HEALTH',
                'description' => 'النظافة العامة، الرقابة الصحية، الحدائق، والمكافحة البيئية',
                'order' => 9,
            ],
            [
                'name' => 'قسم تكنولوجيا المعلومات والأنظمة',
                'code' => 'IT',
                'description' => 'إدارة النظام الموحد، البنية التحتية التقنية، والدعم الفني',
                'order' => 10,
            ],
        ];

        foreach ($departments as $deptData) {
            Department::updateOrCreate(
                ['code' => $deptData['code']],
                $deptData
            );
        }

        // Link users if they don't have department yet
        $engDept = Department::where('code', 'ENG')->first();
        $itDept = Department::where('code', 'IT')->first();

        // Assign users reasonably if null
        User::whereNull('department_id')->each(function ($user) use ($engDept, $itDept) {
            if (in_array(strtolower($user->email), ['admin@bhn.ps', 'oudah@bhn.ps', 'it@bhn.ps'])) {
                $user->update([
                    'department_id' => $itDept?->id,
                    'job_title' => 'مسؤول النظم وتكنولوجيا المعلومات',
                ]);
            } else {
                $user->update([
                    'department_id' => $engDept?->id,
                    'job_title' => 'مهندس / موظف بلدي',
                ]);
            }
        });
    }
}
