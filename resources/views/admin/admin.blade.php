<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم - دليل سوريا التجاري')</title>
    
    <!-- خطوط جوجل لمحرك البحث والواجهات -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS v4.0 التحديث المتوافق -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <style>
        body { font-family: 'Cairo', sans-serif; }
    </style>
    
    <!-- مساحة مخصصة لحقن تنسيقات إضافية مثل مكتبة الخرائط أو غيرها في صفحات معينة فقط -->
    @stack('styles')
</head>
<body class="h-full antialiased text-slate-800">

    <div class="flex min-h-screen">
        <!-- استدعاء الشريط الجانبي الذكي -->
        @include('components.admin.sidebar')

        <!-- محتوى الصفحة المتغير -->
        <main class="flex-1 p-6 md:p-8 lg:p-10 mr-64 overflow-y-auto">
            <!-- الهيدر العلوى الداخلي للوحة التحكم (يمكن تخصيصه لاحقاً) -->
            <div class="flex justify-between items-center mb-8 border-b border-slate-200 pb-4">
                <h1 class="text-2xl font-bold text-slate-900">@yield('page_heading')</h1>
                <div class="flex items-center gap-4">
                    <span class="text-sm text-slate-500">مرحباً، المدير العام</span>
                    <a href="/" class="text-sm text-blue-600 hover:underline">زيارة الموقع ↩</a>
                </div>
            </div>

            <!-- حقن محتوى الصفحات الفرعية هنا -->
            @yield('content')
        </main>
    </div>

    <!-- مساحة مخصصة لحقن سكربتات جافا سكريبت خاصة بكل صفحة بشكل منفصل -->
    @stack('scripts')
</body>
</html>