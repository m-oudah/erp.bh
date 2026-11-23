<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Damage\PricingCriteria;

class DamsPricingCriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $criteria = [
            // 1. Real Estate & Residential Buildings
            [
                'name_ar' => 'بناء باطون مسلح وسقف خرساني (سكني/تجاري)',
                'name_en' => 'Reinforced Concrete Structure (Residential/Commercial)',
                'sector' => 'real_estate',
                'category' => 'building_structure',
                'unit_of_measure' => 'sqm',
                'unit_price_usd' => 350.00,
                'unit_price_ils' => 1295.00,
                'damage_multipliers' => [
                    'total_destruction' => 1.00,
                    'severe_damage' => 0.65,
                    'partial_damage' => 0.35,
                    'minor_damage' => 0.15,
                ],
                'formula_type' => 'area_floors',
                'notes' => 'سعر المتر المربع التقديري لإعادة الإعمار شاملاً الهيكل الإنشائي والتشطيبات الأساسية',
                'is_active' => true,
            ],
            [
                'name_ar' => 'بناء جدران طوب وسقف أسبست / زنكو',
                'name_en' => 'Asbestos/Corrugated Metal Roof Building',
                'sector' => 'real_estate',
                'category' => 'building_structure',
                'unit_of_measure' => 'sqm',
                'unit_price_usd' => 180.00,
                'unit_price_ils' => 666.00,
                'damage_multipliers' => [
                    'total_destruction' => 1.00,
                    'severe_damage' => 0.60,
                    'partial_damage' => 0.30,
                    'minor_damage' => 0.10,
                ],
                'formula_type' => 'area_floors',
                'notes' => 'تقدير المساكن الشعبية ذات الأسقف الخفيفة',
                'is_active' => true,
            ],
            [
                'name_ar' => 'منشأة هنغار / هيكل معدني صناعي أو تجاري',
                'name_en' => 'Steel Structure / Hangar Facility',
                'sector' => 'economic_crafts',
                'category' => 'commercial_industrial',
                'unit_of_measure' => 'sqm',
                'unit_price_usd' => 220.00,
                'unit_price_ils' => 814.00,
                'damage_multipliers' => [
                    'total_destruction' => 1.00,
                    'severe_damage' => 0.65,
                    'partial_damage' => 0.35,
                    'minor_damage' => 0.15,
                ],
                'formula_type' => 'area_floors',
                'notes' => 'المستودعات والورش والمخازن الصناعية والحرفية',
                'is_active' => true,
            ],

            // 2. Roads & Infrastructure
            [
                'name_ar' => 'شارع مسفلت (طبقة أساس + إسفلت)',
                'name_en' => 'Asphalt Paved Road',
                'sector' => 'infrastructure',
                'category' => 'road_asphalt',
                'unit_of_measure' => 'sqm',
                'unit_price_usd' => 45.00,
                'unit_price_ils' => 166.50,
                'damage_multipliers' => [
                    'total_destruction' => 1.00,
                    'severe_damage' => 0.65,
                    'partial_damage' => 0.35,
                    'minor_damage' => 0.15,
                ],
                'formula_type' => 'road_dimension',
                'notes' => 'إعادة تجريف وتأسيس وتعبيد طبقة إسفلتية متكاملة',
                'is_active' => true,
            ],
            [
                'name_ar' => 'شارع مبلط بالإنترلوك (بلاط خرساني)',
                'name_en' => 'Interlock Paver Road',
                'sector' => 'infrastructure',
                'category' => 'road_interlock',
                'unit_of_measure' => 'sqm',
                'unit_price_usd' => 35.00,
                'unit_price_ils' => 129.50,
                'damage_multipliers' => [
                    'total_destruction' => 1.00,
                    'severe_damage' => 0.65,
                    'partial_damage' => 0.35,
                    'minor_damage' => 0.15,
                ],
                'formula_type' => 'road_dimension',
                'notes' => 'فرش الرمال وتوريد وتركيب بلاط إنترلوك مع الحواف (كربستون)',
                'is_active' => true,
            ],
            [
                'name_ar' => 'شارع ترابي / تسوية وتأهيل أولي',
                'name_en' => 'Dirt Road Grading and Compaction',
                'sector' => 'infrastructure',
                'category' => 'road_dirt',
                'unit_of_measure' => 'sqm',
                'unit_price_usd' => 12.00,
                'unit_price_ils' => 44.40,
                'damage_multipliers' => [
                    'total_destruction' => 1.00,
                    'severe_damage' => 0.50,
                    'partial_damage' => 0.25,
                    'minor_damage' => 0.10,
                ],
                'formula_type' => 'road_dimension',
                'notes' => 'تسوية ورصف بالكركار تمهيداً لفتح مسارات الإغاثة والطوارئ',
                'is_active' => true,
            ],

            // 3. Water & Sewage & Electricity Networks
            [
                'name_ar' => 'شبكة خطوط مياه شرب رئيسية وفرعية (متر طولي)',
                'name_en' => 'Potable Water Distribution Network',
                'sector' => 'infrastructure',
                'category' => 'water_network',
                'unit_of_measure' => 'linear_meter',
                'unit_price_usd' => 55.00,
                'unit_price_ils' => 203.50,
                'damage_multipliers' => [
                    'total_destruction' => 1.00,
                    'severe_damage' => 0.70,
                    'partial_damage' => 0.40,
                    'minor_damage' => 0.15,
                ],
                'formula_type' => 'network_length',
                'notes' => 'أنابيب بولي إيثيلين وملحقات الضخ والمحابس',
                'is_active' => true,
            ],
            [
                'name_ar' => 'شبكة صرف صحي ومناهل رئيسية (متر طولي)',
                'name_en' => 'Sewage Collection Network & Manholes',
                'sector' => 'infrastructure',
                'category' => 'sewage_network',
                'unit_of_measure' => 'linear_meter',
                'unit_price_usd' => 75.00,
                'unit_price_ils' => 277.50,
                'damage_multipliers' => [
                    'total_destruction' => 1.00,
                    'severe_damage' => 0.75,
                    'partial_damage' => 0.40,
                    'minor_damage' => 0.15,
                ],
                'formula_type' => 'network_length',
                'notes' => 'خطوط Upvc أقطار 250-400 ملم شاملة غرف التفتيش والمناهل',
                'is_active' => true,
            ],
            [
                'name_ar' => 'شبكة كهرباء وإنارة شوارع (متر طولي / أعمدة وكوابل)',
                'name_en' => 'Electricity Grid & Street Lighting Network',
                'sector' => 'infrastructure',
                'category' => 'electricity_network',
                'unit_of_measure' => 'linear_meter',
                'unit_price_usd' => 40.00,
                'unit_price_ils' => 148.00,
                'damage_multipliers' => [
                    'total_destruction' => 1.00,
                    'severe_damage' => 0.70,
                    'partial_damage' => 0.35,
                    'minor_damage' => 0.15,
                ],
                'formula_type' => 'network_length',
                'notes' => 'أعمدة حديد مع كوابل معزولة ومصابيح طاقة شمسية/إنارة',
                'is_active' => true,
            ],

            // 4. Public Facilities & Municipal Assets
            [
                'name_ar' => 'مباني المرافق العامة والمدارس والمستشفيات',
                'name_en' => 'Public Educational/Health Facilities',
                'sector' => 'public_facilities',
                'category' => 'facility',
                'unit_of_measure' => 'sqm',
                'unit_price_usd' => 450.00,
                'unit_price_ils' => 1665.00,
                'damage_multipliers' => [
                    'total_destruction' => 1.00,
                    'severe_damage' => 0.70,
                    'partial_damage' => 0.35,
                    'minor_damage' => 0.15,
                ],
                'formula_type' => 'area_floors',
                'notes' => 'شاملة التجهيزات الكهروميكانيكية والتشطيبات التخصصية',
                'is_active' => true,
            ],
            [
                'name_ar' => 'آبار ومحطات تحلية وضخ مياه البلدية',
                'name_en' => 'Municipal Water Wells & Pumping Stations',
                'sector' => 'municipal_assets',
                'category' => 'water_facility',
                'unit_of_measure' => 'item',
                'unit_price_usd' => 150000.00,
                'unit_price_ils' => 555000.00,
                'damage_multipliers' => [
                    'total_destruction' => 1.00,
                    'severe_damage' => 0.65,
                    'partial_damage' => 0.30,
                    'minor_damage' => 0.10,
                ],
                'formula_type' => 'fixed_asset',
                'notes' => 'إعادة حفر وتجهيز بئر كامل مع المحركات ومجموعات الطاقة والمولدات',
                'is_active' => true,
            ],
        ];

        foreach ($criteria as $item) {
            PricingCriteria::updateOrCreate(
                ['name_ar' => $item['name_ar'], 'sector' => $item['sector']],
                $item
            );
        }
    }
}
