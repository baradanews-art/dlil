<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - دليل سوريا التجاري</title>
    <!-- تضمين بوتستراب النسخة الخامسة لدعم التنسيق السريع والعربي -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #212529; color: white; padding-top: 20px; }
        .sidebar a { color: #adb5bd; text-decoration: none; padding: 12px 20px; display: block; font-size: 16px; border-bottom: 1px solid #2c3034; }
        .sidebar a:hover, .sidebar a.active { color: white; background-color: #0d6efd; }
        .card-counter { border: none; border-radius: 10px; color: white; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        
        <!-- القائمة الجانبية (Sidebar) -->
        <div class="col-md-3 col-lg-2 sidebar px-0">
            <div class="text-center mb-4">
                <h4>لوحة الإدارة 🛠️</h4>
                <small class="text-muted">دليل سوريا الرقمي</small>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="active">📊 الرئيسية (الإحصائيات)</a>
            <a href="#">📁 إدارة التصنيفات (قريباً)</a>
            <a href="#">📍 إدارة المناطق (قريباً)</a>
            <a href="#">🏢 إدارة الشركات (قريباً)</a>
            <a href="#">📢 نظام الإعلانات (قريباً)</a>
            <a href="#">📥 استيراد وتصدير Excel (قريباً)</a>
            <a href="{{ url('/') }}" target="_blank" class="text-info">🌐 عرض الموقع الرئيسي</a>
        </div>

        <!-- المحتوى الرئيسي (Main Content) -->
        <div class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>نظرة عامة على النظام</h2>
                <span class="badge bg-secondary p-2">مرحلة البناء والتأسيس v1.0</span>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- كروت الإحصائيات السريعة -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="p-4 bg-primary card-counter">
                        <h6>إجمالي النشاطات</h6>
                        <h2>{{ $totalBusinesses }}</h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-4 bg-warning text-dark card-counter">
                        <h6>نشاطات قيد الانتظار</h6>
                        <h2>{{ $pendingBusinesses }}</h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-4 bg-success card-counter">
                        <h6>أقسام النشاطات</h6>
                        <h2>{{ $totalCategories }}</h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-4 bg-info card-counter">
                        <h6>المحافظات والمناطق</h6>
                        <h2>{{ $totalLocations }}</h2>
                    </div>
                </div>
            </div>

            <!-- جدول التحكم في الشركات المضافة حديثاً -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-dark fw-bold">آخر النشاطات التجارية المضافة وتحكم الحالات</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>اسم النشاط</th>
                                    <th>التصنيف</th>
                                    <th>المنطقة</th>
                                    <th>الحالة الحالية</th>
                                    <th>إجراءات سريعة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($businesses as $biz)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $biz->title }}</div>
                                        <small class="text-muted">{{ $biz->phone ?? 'لا يوجد هاتف' }}</small>
                                    </td>
                                    <td><span class="badge bg-light text-dark">{{ $biz->category->name ?? 'غير محدد' }}</span></td>
                                    <td>📍 {{ $biz->location->name ?? 'غير محدد' }}</td>
                                    <td>
                                        @if($biz->status == 'approved')
                                            <span class="badge bg-success-subtle text-success p-2">✔️ نشط ومقبول</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning p-2">⏳ قيد المراجعة</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <!-- زر تفعيل/تعطيل الحالة -->
                                            <form action="{{ route('admin.business.toggle', $biz->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $biz->status == 'approved' ? 'btn-outline-warning' : 'btn-success' }}">
                                                    {{ $biz->status == 'approved' ? 'تعطيل القبول' : '⚠️ تفعيل ونشر' }}
                                                </button>
                                            </form>
                                            
                                            <!-- زر الحذف -->
                                            <form action="{{ route('admin.business.delete', $biz->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا النشاط نهائياً؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center p-4 text-muted">لا يوجد أي نشاط تجاري مضاف في قاعدة البيانات حتى الآن.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>