<?php

namespace App\Http\Controllers;

use App\Models\OfficialEntity;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Traits\ImageUploadTrait;

class OfficialEntityController extends Controller
{
    use ImageUploadTrait;

    /**
     * ✅ عرض المؤسسات الحكومية
     */
    public function government(Request $request)
    {
        return $this->renderIndex($request, 'government', [
            'title' => 'المؤسسات الحكومية',
            'description' => 'دليل الوزارات والمديريات والمكاتب الحكومية في سوريا',
            'icon' => 'fa-landmark',
            'bgColor' => 'from-green-700 to-green-600',
            'subTypes' => [
                'ministry' => 'وزارة',
                'directorate' => 'مديرية',
                'municipality' => 'بلدية',
                'government_office' => 'مكتب حكومي',
            ],
        ]);
    }
    
    /**
     * ✅ عرض مراكز الأمن والنجدة
     */
    public function security(Request $request)
    {
        return $this->renderIndex($request, 'security', [
            'title' => 'مراكز الأمن والنجدة',
            'description' => 'دليل مراكز الشرطة والدفاع المدني والإسعاف في سوريا',
            'icon' => 'fa-shield-alt',
            'bgColor' => 'from-red-700 to-red-600',
            'subTypes' => [
                'police_station' => 'مركز شرطة',
                'criminal_investigation' => 'مباحث',
                'drug_enforcement' => 'مكافحة مخدرات',
                'traffic' => 'مرور',
                'passports' => 'جوازات',
                'civil_defense' => 'دفاع مدني',
                'emergency' => 'طوارئ',
            ],
        ]);
    }
    
    /**
     * ✅ عرض مراكز المساعدة
     */
    public function help(Request $request)
    {
        return $this->renderIndex($request, 'help', [
            'title' => 'مراكز المساعدة',
            'description' => 'دليل المستشفيات والجمعيات الخيرية ومراكز الدعم الاجتماعي',
            'icon' => 'fa-hand-holding-heart',
            'bgColor' => 'from-blue-700 to-blue-600',
            'subTypes' => [
                'hospital' => 'مستشفى',
                'clinic' => 'مركز صحي',
                'charity' => 'جمعية خيرية',
                'social_care' => 'رعاية اجتماعية',
                'orphanage' => 'دور أيتام',
                'shelter' => 'مركز إيواء',
            ],
        ]);
    }
    
    /**
     * ✅ عرض صفحة مفصلة لمؤسسة رسمية
     */
    public function show($slug)
    {
        $entity = OfficialEntity::where('slug', $slug)
            ->with(['city', 'region'])
            ->firstOrFail();
        
        $data = [
            'entity' => $entity,
            'color' => $entity->color,
            'icon' => $entity->icon,
            'bgColor' => $entity->bg_gradient,
        ];
        
        return view('official.show', $data);
    }
    
    /**
     * ✅ API: جلب المناطق حسب المدينة
     */
    public function getRegions($cityId)
    {
        $regions = Location::where('parent_id', $cityId)
            ->ordered()
            ->get(['id', 'name']);
        
        return response()->json($regions);
    }
    
    /**
     * ✅ طريقة مساعدة لعرض القوائم مع Pagination وفلاتر صحيحة
     */
    private function renderIndex(Request $request, string $type, array $pageData)
    {
        $query = OfficialEntity::where('type', $type)->active()->ordered();
        
        // ✅ فلترة حسب المحافظة (city_id)
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }
        
        // ✅ فلترة حسب المنطقة (region_id)
        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }
        
        // ✅ فلترة حسب النوع الفرعي
        if ($request->filled('sub_type')) {
            $query->where('sub_type', $request->sub_type);
        }
        
        // ✅ فلترة حسب البحث النصي (يبحث في الاسم، الوصف، العنوان، واسم المحافظة والمنطقة)
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', $searchTerm)
                  ->orWhere('description', 'LIKE', $searchTerm)
                  ->orWhere('address', 'LIKE', $searchTerm)
                  // البحث في اسم المحافظة المرتبطة
                  ->orWhereHas('city', function($cityQ) use ($searchTerm) {
                      $cityQ->where('name', 'LIKE', $searchTerm);
                  })
                  // البحث في اسم المنطقة المرتبطة
                  ->orWhereHas('region', function($regionQ) use ($searchTerm) {
                      $regionQ->where('name', 'LIKE', $searchTerm);
                  });
            });
        }
        
        // ✅ Paginate (12 عنصر لكل صفحة)
        $entities = $query->paginate(12);
        
        // ✅ جلب المدن للمرشحات
        $cities = Location::governorates()->ordered()->get();
        
        // ✅ جلب المناطق إذا تم اختيار مدينة
        $regions = collect();
        if ($request->filled('city_id')) {
            $regions = Location::where('parent_id', $request->city_id)->ordered()->get();
        }
        
        return view('official.index', array_merge($pageData, [
            'entities' => $entities,
            'cities' => $cities,
            'regions' => $regions,
            'type' => $type,
        ]));
    }
}