<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OfficialEntity;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfficialEntityController extends Controller
{
    /**
     * ✅ عرض المؤسسات حسب النوع مع دعم البحث والفلترة
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'government');
        
        $query = OfficialEntity::where('type', $type)
            ->with(['city', 'region']);
        
        // ✅ البحث بالاسم أو العنوان أو الهاتف
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('address', 'LIKE', "%{$request->search}%")
                  ->orWhere('phone', 'LIKE', "%{$request->search}%")
                  ->orWhere('description', 'LIKE', "%{$request->search}%");
            });
        }
        
        // ✅ فلترة حسب المحافظة
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }
        
        // ✅ فلترة حسب المنطقة
        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }
        
        // ✅ فلترة حسب النوع الفرعي
        if ($request->filled('sub_type')) {
            $query->where('sub_type', $request->sub_type);
        }
        
        // ✅ ترتيب وتصفح (12 عنصر لكل صفحة)
        $entities = $query->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(12);
        
        // ✅ بيانات الفلاتر
        $cities = Location::governorates()->ordered()->get();
        $regions = collect();
        
        if ($request->filled('city_id')) {
            $regions = Location::where('parent_id', $request->city_id)->ordered()->get();
        }
        
        // ✅ أنواع فرعية حسب النوع الرئيسي
        $subTypes = match ($type) {
            'security' => [
                'police_station' => 'مركز شرطة',
                'criminal_investigation' => 'مباحث',
                'drug_enforcement' => 'مكافحة مخدرات',
                'traffic' => 'مرور',
                'passports' => 'جوازات',
                'civil_defense' => 'دفاع مدني',
                'emergency' => 'طوارئ',
            ],
            'government' => [
                'ministry' => 'وزارة',
                'directorate' => 'مديرية',
                'municipality' => 'بلدية',
                'government_office' => 'مكتب حكومي',
            ],
            default => [
                'hospital' => 'مستشفى',
                'clinic' => 'مركز صحي',
                'charity' => 'جمعية خيرية',
                'social_care' => 'رعاية اجتماعية',
                'orphanage' => 'دور أيتام',
                'shelter' => 'مركز إيواء',
            ],
        };
        
        return view('admin.official.index', compact('entities', 'type', 'cities', 'regions', 'subTypes'));
    }
    
    /**
     * ✅ تصدير بيانات المؤسسات (Excel/CSV)
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'government');
        
        $query = OfficialEntity::where('type', $type)
            ->with(['city', 'region']);
        
        // تطبيق نفس شروط البحث والفلترة الموجودة في index
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('address', 'LIKE', "%{$request->search}%")
                  ->orWhere('phone', 'LIKE', "%{$request->search}%");
            });
        }
        
        if ($request->filled('city_id')) {
            $query->where('city_id', $request->city_id);
        }
        
        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }
        
        if ($request->filled('sub_type')) {
            $query->where('sub_type', $request->sub_type);
        }
        
        $entities = $query->orderBy('sort_order')->orderBy('name')->get();
        
        // إنشاء ملف CSV
        $filename = 'official_entities_' . date('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://temp', 'w+');
        
        // إضافة BOM ليدعم اللغة العربية في Excel
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // رأس الأعمدة
        fputcsv($handle, [
            'ID', 'الاسم', 'النوع', 'النوع الفرعي', 'الوصف', 
            'الهاتف', 'طوارئ', 'البريد الإلكتروني', 'الموقع الإلكتروني',
            'العنوان', 'المحافظة (ID)', 'المنطقة (ID)', 'ساعات العمل',
            'ترتيب الظهور', 'خط العرض', 'خط الطول', 'فيسبوك', 'تويتر',
            'انستغرام', 'يوتيوب', 'لينكد إن', 'حالة النشر (is_active)'
        ]);
        
        foreach ($entities as $entity) {
            fputcsv($handle, [
                $entity->id,
                $entity->name,
                $entity->type,
                $entity->sub_type,
                $entity->description,
                $entity->phone,
                $entity->hotline,
                $entity->email,
                $entity->website,
                $entity->address,
                $entity->city_id,
                $entity->region_id,
                $entity->working_hours,
                $entity->sort_order,
                $entity->latitude,
                $entity->longitude,
                $entity->facebook_url,
                $entity->twitter_url,
                $entity->instagram_url,
                $entity->youtube_url,
                $entity->linkedin_url,
                $entity->is_active ? 'نشط' : 'غير نشط'
            ]);
        }
        
        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);
        
        return response($csvContent, 200)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', "attachment; filename={$filename}");
    }
    
    /**
     * ✅ استيراد وتحديث البيانات من ملف CSV/Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);
        
        $file = $request->file('import_file');
        $handle = fopen($file->getPathname(), 'r');
        
        // قراءة BOM إذا وجد
        $bom = fgets($handle, 4);
        if (strpos($bom, chr(0xEF).chr(0xBB).chr(0xBF)) === 0) {
            // تخطي الـ BOM
            rewind($handle);
            fseek($handle, 3);
        } else {
            rewind($handle);
        }
        
        // قراءة رأس الأعمدة
        $headers = fgetcsv($handle);
        
        $updatedCount = 0;
        $errors = [];
        
        DB::beginTransaction();
        
        try {
            while (($row = fgetcsv($handle)) !== false) {
                if (count($headers) != count($row)) {
                    continue;
                }
                $data = array_combine($headers, $row);
                
                if (!$data || empty($data['ID'])) {
                    continue;
                }
                
                // البحث عن السجل حسب ID
                $entity = OfficialEntity::find($data['ID']);
                
                if (!$entity) {
                    continue;
                }
                
                $updateData = [
                    'name' => $data['الاسم'] ?? $entity->name,
                    'type' => $data['النوع'] ?? $entity->type,
                    'sub_type' => $data['النوع الفرعي'] ?? $entity->sub_type,
                    'description' => $data['الوصف'] ?? $entity->description,
                    'phone' => $data['الهاتف'] ?? $entity->phone,
                    'hotline' => $data['طوارئ'] ?? $entity->hotline,
                    'email' => $data['البريد الإلكتروني'] ?? $entity->email,
                    'website' => $data['الموقع الإلكتروني'] ?? $entity->website,
                    'address' => $data['العنوان'] ?? $entity->address,
                    'city_id' => !empty($data['المحافظة (ID)']) ? (int)$data['المحافظة (ID)'] : $entity->city_id,
                    'region_id' => !empty($data['المنطقة (ID)']) ? (int)$data['المنطقة (ID)'] : $entity->region_id,
                    'working_hours' => $data['ساعات العمل'] ?? $entity->working_hours,
                    'sort_order' => !empty($data['ترتيب الظهور']) ? (int)$data['ترتيب الظهور'] : $entity->sort_order,
                    'latitude' => !empty($data['خط العرض']) ? (float)$data['خط العرض'] : $entity->latitude,
                    'longitude' => !empty($data['خط الطول']) ? (float)$data['خط الطول'] : $entity->longitude,
                    'facebook_url' => $data['فيسبوك'] ?? $entity->facebook_url,
                    'twitter_url' => $data['تويتر'] ?? $entity->twitter_url,
                    'instagram_url' => $data['انستغرام'] ?? $entity->instagram_url,
                    'youtube_url' => $data['يوتيوب'] ?? $entity->youtube_url,
                    'linkedin_url' => $data['لينكد إن'] ?? $entity->linkedin_url,
                    'is_active' => ($data['حالة النشر (is_active)'] ?? 'نشط') == 'نشط',
                ];
                
                $entity->update($updateData);
                $updatedCount++;
            }
            
            fclose($handle);
            DB::commit();
            
            return redirect()->back()
                ->with('success', "✅ تم تحديث {$updatedCount} مؤسسة بنجاح!");
                
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            
            return redirect()->back()
                ->with('error', '❌ حدث خطأ أثناء الاستيراد: ' . $e->getMessage());
        }
    }
    
    /**
     * ✅ عرض صفحة إضافة مؤسسة جديدة
     */
    public function create()
    {
        $type = request()->get('type', 'government');
        $cities = Location::governorates()->ordered()->get();
        
        return view('admin.official.create', compact('type', 'cities'));
    }
    
    /**
     * ✅ حفظ مؤسسة جديدة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:government,security,help',
            'sub_type' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'hotline' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'city_id' => 'nullable|exists:locations,id',
            'region_id' => 'nullable|exists:locations,id',
            'working_hours' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
        
        DB::beginTransaction();
        
        try {
            // ✅ رفع اللوجو
            if ($request->hasFile('logo')) {
                $logoName = time() . '_logo_' . rand(1000, 9999) . '.' . $request->file('logo')->getClientOriginalExtension();
                $request->file('logo')->move(public_path('uploads/official'), $logoName);
                $validated['logo'] = 'uploads/official/' . $logoName;
            }
            
            $validated['sort_order'] = $validated['sort_order'] ?? 0;
            
            OfficialEntity::create($validated);
            
            DB::commit();
            
            return redirect()->route('admin.official.index', ['type' => $validated['type']])
                ->with('success', '✅ تم إضافة المؤسسة بنجاح!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ حدث خطأ: ' . $e->getMessage());
        }
    }
    
    /**
     * ✅ عرض صفحة تعديل مؤسسة
     */
    public function edit($id)
    {
        $entity = OfficialEntity::findOrFail($id);
        $cities = Location::governorates()->ordered()->get();
        
        // ✅ جلب المناطق التابعة للمحافظة الحالية
        $regions = collect();
        if ($entity->city_id) {
            $regions = Location::where('parent_id', $entity->city_id)->ordered()->get();
        }
        
        return view('admin.official.edit', compact('entity', 'cities', 'regions'));
    }
    
    /**
     * ✅ تحديث مؤسسة
     */
    public function update(Request $request, $id)
    {
        $entity = OfficialEntity::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:government,security,help',
            'sub_type' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'hotline' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'youtube_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'city_id' => 'nullable|exists:locations,id',
            'region_id' => 'nullable|exists:locations,id',
            'working_hours' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);
        
        DB::beginTransaction();
        
        try {
            // ✅ تحديث اللوجو
            if ($request->hasFile('logo')) {
                if ($entity->logo && file_exists(public_path($entity->logo))) {
                    unlink(public_path($entity->logo));
                }
                
                $logoName = time() . '_logo_' . rand(1000, 9999) . '.' . $request->file('logo')->getClientOriginalExtension();
                $request->file('logo')->move(public_path('uploads/official'), $logoName);
                $validated['logo'] = 'uploads/official/' . $logoName;
            }
            
            $validated['sort_order'] = $validated['sort_order'] ?? 0;
            
            $entity->update($validated);
            
            DB::commit();
            
            return redirect()->route('admin.official.index', ['type' => $entity->type])
                ->with('success', '✅ تم تحديث المؤسسة بنجاح!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ حدث خطأ: ' . $e->getMessage());
        }
    }
    
    /**
     * ✅ حذف مؤسسة
     */
    public function destroy($id)
    {
        $entity = OfficialEntity::findOrFail($id);
        $type = $entity->type;
        
        // ✅ حذف اللوجو
        if ($entity->logo && file_exists(public_path($entity->logo))) {
            unlink(public_path($entity->logo));
        }
        
        $entity->delete();
        
        return redirect()->route('admin.official.index', ['type' => $type])
            ->with('success', '✅ تم حذف المؤسسة بنجاح!');
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
}