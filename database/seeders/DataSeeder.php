<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Support\Str;

class DataSeeder extends Seeder
{
    public function run()
    {
        // --- 1. إدخال الأنشطة التجارية الأساسية ---
        $categories = [
            'مطاعم ومقاهي',
            'أطباء وعيادات',
            'صيدليات ومستلزمات طبية',
            'مواد غذائية وسوبرماركت',
            'صيانة سيارات وورشات',
            'ألبسة وأحذية',
            'صالونات حلاقة وتجميل',
            'صيانة ومبيعات موبايل وكمبيوتر',
            'مقاولات ومواد بناء',
            'مكتبات وقرطاسية'
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['name' => $cat],
                ['slug' => Str::slug($cat, '-'), 'type' => 'business']
            );
        }

        // --- 2. إدخال المحافظات والمناطق الشهيرة ---
        $syriaData = [
            'دمشق' => ['المزة', 'القصاع', 'أبو رمانة', 'باب توما', 'مشروع دمر', 'كفرسوسة', 'البرامكة'],
            'ريف دمشق' => ['ضاحية قدسيا', 'قدسيا', 'الهامة', 'جرمانا', 'صحنايا', 'التل', 'يعفور'],
            'حلب' => ['الجميلية', 'الفرقان', 'الشهباء', 'الموكامبو', 'العزيزية', 'صلاح الدين'],
            'حمص' => ['الإنشاءات', 'المحطة', 'الحمراء', 'الغوطة', 'الوعر', 'كرم الشامي'],
            'اللاذقية' => ['المشروع الأول', 'المشروع السابع', 'الكورنيش الغربي', 'الزراعة', 'الأمريكان'],
            'طرطوس' => ['الكورنيش البحري', 'الحمرات', 'المشروع الرابع', 'وسط المدينة'],
            'حماة' => ['الحاضر', 'العليليات', 'الشريعة', 'طريق حلب', 'القصور']
        ];

        foreach ($syriaData as $governorate => $areas) {
            // إضافة المحافظة الأم أولاً
            $parentLoc = Location::updateOrCreate(
                ['name' => $governorate, 'parent_id' => null],
                ['slug' => Str::slug($governorate, '-')]
            );

            // إضافة المناطق التابعة لها
            foreach ($areas as $area) {
                Location::updateOrCreate(
                    ['name' => $area, 'parent_id' => $parentLoc->id],
                    ['slug' => Str::slug($area, '-')]
                );
            }
        }
    }
}