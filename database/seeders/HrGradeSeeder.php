<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HrGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = [
            // الفئة العليا
            [
                'category' => 'العليا',
                'grade_code' => 'A1',
                'grade_name' => 'الدرجة الخاصة (A1)',
                'base_salary' => 3500.00,
                'annual_raise' => 50.00,
                'max_steps' => 20,
                'notes' => 'وكيل أول / الفئة العليا A1',
                'order' => 1,
            ],
            [
                'category' => 'العليا',
                'grade_code' => 'A2',
                'grade_name' => 'وكيل مساعد (A2)',
                'base_salary' => 3100.00,
                'annual_raise' => 45.00,
                'max_steps' => 20,
                'notes' => 'وكيل مساعد / الفئة العليا A2',
                'order' => 2,
            ],
            [
                'category' => 'العليا',
                'grade_code' => 'A3',
                'grade_name' => 'مدير عام (A3)',
                'base_salary' => 2700.00,
                'annual_raise' => 40.00,
                'max_steps' => 20,
                'notes' => 'مدير عام / الفئة العليا A3',
                'order' => 3,
            ],

            // الفئة الأولى
            [
                'category' => 'الأولى',
                'grade_code' => '1',
                'grade_name' => 'الدرجة الأولى (1)',
                'base_salary' => 2400.00,
                'annual_raise' => 35.00,
                'max_steps' => 20,
                'notes' => 'المؤهل المطلوب: ماجستير أو بكالوريوس + خبرة إشرافية',
                'order' => 4,
            ],
            [
                'category' => 'الأولى',
                'grade_code' => '2',
                'grade_name' => 'الدرجة الثانية (2)',
                'base_salary' => 2150.00,
                'annual_raise' => 30.00,
                'max_steps' => 20,
                'notes' => 'المؤهل المطلوب: بكالوريوس + خبرة موثقة',
                'order' => 5,
            ],

            // الفئة الثانية
            [
                'category' => 'الثانية',
                'grade_code' => '3',
                'grade_name' => 'الدرجة الثالثة (3)',
                'base_salary' => 1950.00,
                'annual_raise' => 25.00,
                'max_steps' => 20,
                'notes' => 'المؤهل المطلوب: بكالوريوس أو ما يعادله',
                'order' => 6,
            ],
            [
                'category' => 'الثانية',
                'grade_code' => '4',
                'grade_name' => 'الدرجة الرابعة (4)',
                'base_salary' => 1800.00,
                'annual_raise' => 25.00,
                'max_steps' => 20,
                'notes' => 'المؤهل المطلوب: بكالوريوس',
                'order' => 7,
            ],
            [
                'category' => 'الثانية',
                'grade_code' => '5',
                'grade_name' => 'الدرجة الخامسة (5)',
                'base_salary' => 1650.00,
                'annual_raise' => 20.00,
                'max_steps' => 20,
                'notes' => 'بداية تعيين حملة شهادة البكالوريوس في الخدمة المدنية',
                'order' => 8,
            ],

            // الفئة الثالثة
            [
                'category' => 'الثالثة',
                'grade_code' => '6',
                'grade_name' => 'الدرجة السادسة (6)',
                'base_salary' => 1520.00,
                'annual_raise' => 20.00,
                'max_steps' => 20,
                'notes' => 'حملة الدبلوم المتوسط سنتين بعد الثانوية مع ترقية',
                'order' => 9,
            ],
            [
                'category' => 'الثالثة',
                'grade_code' => '7',
                'grade_name' => 'الدرجة السابعة (7)',
                'base_salary' => 1420.00,
                'annual_raise' => 15.00,
                'max_steps' => 20,
                'notes' => 'بداية تعيين حملة شهادة الدبلوم المتوسط سنتين',
                'order' => 10,
            ],
            [
                'category' => 'الثالثة',
                'grade_code' => '8',
                'grade_name' => 'الدرجة الثامنة (8)',
                'base_salary' => 1340.00,
                'annual_raise' => 15.00,
                'max_steps' => 20,
                'notes' => 'حملة الدبلوم المهني والفني',
                'order' => 11,
            ],

            // الفئة الرابعة
            [
                'category' => 'الرابعة',
                'grade_code' => '9',
                'grade_name' => 'الدرجة التاسعة (9)',
                'base_salary' => 1260.00,
                'annual_raise' => 15.00,
                'max_steps' => 20,
                'notes' => 'حملة شهادة الثانوية العامة / توجيهي مع ترقية',
                'order' => 12,
            ],
            [
                'category' => 'الرابعة',
                'grade_code' => '10',
                'grade_name' => 'الدرجة العاشرة (10)',
                'base_salary' => 1200.00,
                'annual_raise' => 15.00,
                'max_steps' => 20,
                'notes' => 'بداية تعيين حملة الثانوية العامة / كتاب وفنيين',
                'order' => 13,
            ],

            // الفئة الخامسة
            [
                'category' => 'الخامسة',
                'grade_code' => 'عمال',
                'grade_name' => 'فئة الخدمات والعمال المعاونة',
                'base_salary' => 1150.00,
                'annual_raise' => 10.00,
                'max_steps' => 20,
                'notes' => 'العمال والحرفيون والخدمات المعاونة',
                'order' => 14,
            ],
        ];

        foreach ($grades as $grade) {
            DB::table('hr_grades')->updateOrInsert(
                ['grade_code' => $grade['grade_code']],
                array_merge($grade, [
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}

