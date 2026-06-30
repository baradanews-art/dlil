<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BusinessController extends Controller
{
    // ============================================================
    // عرض قائمة المنشآت مع فلترة وبحث
    // ============================================================
    public function index(Request $request)
    {
        $query = Business::with(['category', 'governorate', 'region']);

        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->approved();
            } elseif ($request->status === 'pending') {
                $query->pending();
            }
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('governorate_id')) {
            $query->where('governorate_id', $request->governorate_id);
        }

        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $businesses = $query->latest()->paginate(20);
        $categories = Category::ordered()->get();
        $governorates = Location::governorates()->ordered()->get();
        $regions = collect();

        if ($request->filled('governorate_id')) {
            $regions = Location::where('parent_id', $request->governorate_id)->ordered()->get();
        }

        return view('admin-businesses', compact('businesses', 'categories', 'governorates', 'regions'));
    }

    // ============================================================
    // عرض نموذج إضافة منشأة جديدة
    // ============================================================
    public function create()
    {
        $categories = Category::ordered()->get();
        $governorates = Location::governorates()->ordered()->get();
        
        return view('admin-business-create', compact('categories', 'governorates'));
    }

    // ============================================================
    // حفظ منشأة جديدة من لوحة التحكم
    // ============================================================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'phone' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'governorate_id' => 'required|exists:locations,id',
            'region_id' => 'required|exists:locations,id',
            'description' => 'required|string|min:10',
            'address_detail' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'delivery_available' => 'nullable|boolean',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required|in:approved,pending',
            'verification_type' => 'required|in:none,verified,official',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'google_maps_url' => 'nullable|url|max:255',
        ]);

        DB::beginTransaction();

        try {
            $business = new Business();
            $business->title = $validated['title'];
            $business->slug = $this->generateUniqueSlug($validated['title']);
            $business->phone = $validated['phone'];
            $business->category_id = $validated['category_id'];
            $business->governorate_id = $validated['governorate_id'];
            $business->region_id = $validated['region_id'];
            $business->address_detail = $validated['address_detail'] ?? null;
            $business->description = $validated['description'];
            $business->delivery_available = $request->has('delivery_available');
            $business->latitude = $validated['latitude'] ?? null;
            $business->longitude = $validated['longitude'] ?? null;
            $business->is_approved = $validated['status'] === 'approved';
            $business->verification_type = $validated['verification_type'];
            $business->facebook_url = $validated['facebook_url'] ?? null;
            $business->instagram_url = $validated['instagram_url'] ?? null;
            $business->google_maps_url = $validated['google_maps_url'] ?? null;
            $business->price_list = [];

            // رفع اللوجو
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                $destination = 'uploads/logos/';
                $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/dlil/public/' . $destination;
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $file->move($uploadPath, $filename);
                $business->logo = $destination . $filename;
            }

            // رفع الغلاف
            if ($request->hasFile('cover')) {
                $file = $request->file('cover');
                $filename = time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                $destination = 'uploads/covers/';
                $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/dlil/public/' . $destination;
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $file->move($uploadPath, $filename);
                $business->cover = $destination . $filename;
            }

            $business->save();
            DB::commit();

            return redirect()->route('admin.businesses.index')
                ->with('success', '✅ تم إضافة المنشأة بنجاح!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', '❌ حدث خطأ: ' . $e->getMessage());
        }
    }

    // ============================================================
    // عرض نموذج تعديل منشأة
    // ============================================================
    public function edit($id)
    {
        $business = Business::with(['category', 'governorate', 'region'])->findOrFail($id);
        $categories = Category::ordered()->get();
        $governorates = Location::governorates()->ordered()->get();
        $regions = collect();

        if ($business->governorate_id) {
            $regions = Location::where('parent_id', $business->governorate_id)->ordered()->get();
        }

        return view('admin-business-edit', compact('business', 'categories', 'governorates', 'regions'));
    }

    // ============================================================
    // تحديث منشأة
    // ============================================================
    public function update(Request $request, $id)
    {
        $business = Business::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'phone' => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'governorate_id' => 'required|exists:locations,id',
            'region_id' => 'nullable|exists:locations,id',
            'description' => 'required|string',
            'address_detail' => 'nullable|string|max:255',
            'status' => 'required|in:approved,pending',
            'verification_type' => 'required|in:none,verified,official',
            'delivery_available' => 'nullable|boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'google_maps_url' => 'nullable|url|max:255',
        ]);

        DB::beginTransaction();

        try {
            $business->title = $validated['title'];
            $business->phone = $validated['phone'];
            $business->category_id = $validated['category_id'];
            $business->governorate_id = $validated['governorate_id'];
            $business->region_id = $validated['region_id'] ?? null;
            $business->description = $validated['description'];
            $business->address_detail = $validated['address_detail'] ?? null;
            $business->is_approved = $validated['status'] === 'approved';
            $business->verification_type = $validated['verification_type'];
            $business->delivery_available = $request->has('delivery_available');
            $business->latitude = $validated['latitude'] ?? null;
            $business->longitude = $validated['longitude'] ?? null;
            $business->facebook_url = $validated['facebook_url'] ?? null;
            $business->instagram_url = $validated['instagram_url'] ?? null;
            $business->google_maps_url = $validated['google_maps_url'] ?? null;

            // تحديث اللوجو
            if ($request->hasFile('logo')) {
                if ($business->logo) {
                    $oldPath = $_SERVER['DOCUMENT_ROOT'] . '/dlil/public/' . $business->logo;
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $file = $request->file('logo');
                $filename = time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                $destination = 'uploads/logos/';
                $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/dlil/public/' . $destination;
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $file->move($uploadPath, $filename);
                $business->logo = $destination . $filename;
            }

            // تحديث الغلاف
            if ($request->hasFile('cover')) {
                if ($business->cover) {
                    $oldPath = $_SERVER['DOCUMENT_ROOT'] . '/dlil/public/' . $business->cover;
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $file = $request->file('cover');
                $filename = time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
                $destination = 'uploads/covers/';
                $uploadPath = $_SERVER['DOCUMENT_ROOT'] . '/dlil/public/' . $destination;
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                $file->move($uploadPath, $filename);
                $business->cover = $destination . $filename;
            }

            // تحديث قائمة الأسعار
            if ($request->has('price_list') && is_array($request->price_list)) {
                $business->price_list = array_values(array_filter($request->price_list, function ($item) {
                    return !empty($item['name']);
                }));
            }

            $business->save();
            DB::commit();

            return redirect()->route('admin.businesses.index')
                ->with('success', '✅ تم تحديث المنشأة بنجاح!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', '❌ حدث خطأ: ' . $e->getMessage());
        }
    }

    // ============================================================
    // حذف منشأة
    // ============================================================
    public function destroy($id)
    {
        $business = Business::findOrFail($id);

        if ($business->logo) {
            $logoPath = $_SERVER['DOCUMENT_ROOT'] . '/dlil/public/' . $business->logo;
            if (file_exists($logoPath)) {
                unlink($logoPath);
            }
        }

        if ($business->cover) {
            $coverPath = $_SERVER['DOCUMENT_ROOT'] . '/dlil/public/' . $business->cover;
            if (file_exists($coverPath)) {
                unlink($coverPath);
            }
        }

        $business->delete();

        return redirect()->route('admin.businesses.index')
            ->with('success', '✅ تم حذف المنشأة بنجاح.');
    }

    // ============================================================
    // تصدير البيانات إلى CSV
    // ============================================================
    public function export(Request $request)
    {
        $query = Business::with(['category', 'governorate', 'region']);

        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->approved();
            } elseif ($request->status === 'pending') {
                $query->pending();
            }
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('governorate_id')) {
            $query->where('governorate_id', $request->governorate_id);
        }

        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $businesses = $query->orderBy('id')->get();

        $filename = 'businesses_' . date('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://temp', 'w+');
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($handle, [
            'ID', 'الاسم', 'الوصف', 'الهاتف', 'التصنيف (ID)', 'المحافظة (ID)', 'المنطقة (ID)',
            'العنوان التفصيلي', 'الحالة (approved/pending)', 'نوع التوثيق (none/verified/official)',
            'التوصيل (1/0)', 'رابط فيسبوك', 'رابط انستغرام', 'رابط خرائط جوجل',
            'خط العرض', 'خط الطول', 'عدد المشاهدات', 'متوسط التقييم', 'تاريخ الإضافة'
        ]);

        foreach ($businesses as $bus) {
            fputcsv($handle, [
                $bus->id,
                $bus->title,
                $bus->description,
                $bus->phone,
                $bus->category_id,
                $bus->governorate_id,
                $bus->region_id,
                $bus->address_detail,
                $bus->is_approved ? 'approved' : 'pending',
                $bus->verification_type,
                $bus->delivery_available ? 1 : 0,
                $bus->facebook_url,
                $bus->instagram_url,
                $bus->google_maps_url,
                $bus->latitude,
                $bus->longitude,
                $bus->views_count,
                $bus->rating_avg,
                $bus->created_at?->format('Y-m-d H:i:s')
            ]);
        }

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        return response($csvContent, 200)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', "attachment; filename={$filename}");
    }

    // ============================================================
    // استيراد البيانات من CSV
    // ============================================================
    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('import_file');
        $handle = fopen($file->getPathname(), 'r');

        $bom = fgets($handle, 4);
        if (strpos($bom, chr(0xEF).chr(0xBB).chr(0xBF)) === 0) {
            rewind($handle);
            fseek($handle, 3);
        } else {
            rewind($handle);
        }

        $headers = fgetcsv($handle);

        $importedCount = 0;
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
                $business = Business::find($data['ID']);

                if (!$business) {
                    continue;
                }

                $updateData = [
                    'title' => $data['الاسم'] ?? $business->title,
                    'description' => $data['الوصف'] ?? $business->description,
                    'phone' => $data['الهاتف'] ?? $business->phone,
                    'category_id' => !empty($data['التصنيف (ID)']) ? (int)$data['التصنيف (ID)'] : $business->category_id,
                    'governorate_id' => !empty($data['المحافظة (ID)']) ? (int)$data['المحافظة (ID)'] : $business->governorate_id,
                    'region_id' => !empty($data['المنطقة (ID)']) ? (int)$data['المنطقة (ID)'] : $business->region_id,
                    'address_detail' => $data['العنوان التفصيلي'] ?? $business->address_detail,
                    'is_approved' => ($data['الحالة (approved/pending)'] ?? 'pending') === 'approved',
                    'verification_type' => $data['نوع التوثيق (none/verified/official)'] ?? $business->verification_type,
                    'delivery_available' => !empty($data['التوصيل (1/0)']) && (int)$data['التوصيل (1/0)'] === 1,
                    'facebook_url' => $data['رابط فيسبوك'] ?? $business->facebook_url,
                    'instagram_url' => $data['رابط انستغرام'] ?? $business->instagram_url,
                    'google_maps_url' => $data['رابط خرائط جوجل'] ?? $business->google_maps_url,
                    'latitude' => !empty($data['خط العرض']) ? (float)$data['خط العرض'] : $business->latitude,
                    'longitude' => !empty($data['خط الطول']) ? (float)$data['خط الطول'] : $business->longitude,
                ];

                $business->update($updateData);
                $importedCount++;
            }

            fclose($handle);
            DB::commit();

            return redirect()->back()
                ->with('success', "✅ تم تحديث {$importedCount} منشأة بنجاح!");

        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);

            return redirect()->back()
                ->with('error', '❌ حدث خطأ أثناء الاستيراد: ' . $e->getMessage());
        }
    }

    // ============================================================
    // دوال مساعدة
    // ============================================================
    private function generateUniqueSlug(string $title): string
    {
        $baseSlug = Str::slug($title, '-', 'ar');
        $slug = $baseSlug ?: 'business';
        $count = Business::where('slug', 'LIKE', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }
}