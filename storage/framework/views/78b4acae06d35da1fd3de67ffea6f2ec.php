<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <title><?php echo $__env->yieldContent('title', 'لوحة التحكم - دليل سوريا التجاري'); ?></title>
    
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    
    <style>
        * { font-family: 'Cairo', sans-serif; }
        
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #1e293b; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #10b981; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #059669; }
        
        .sidebar-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        
        @media (max-width: 768px) {
            .sidebar-mobile-hidden { transform: translateX(100%); }
            .sidebar-mobile-visible { transform: translateX(0); }
        }
        
        .stat-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .stat-card:hover { transform: translateY(-3px); }
        
        .table-row { transition: background-color 0.2s ease; }
        .table-row:hover { background-color: rgba(16, 185, 129, 0.05); }
        
        .btn-primary {
            @apply bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-xl transition-all duration-300 cursor-pointer;
        }
        
        .btn-secondary {
            @apply bg-slate-600 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded-xl transition-all duration-300 cursor-pointer;
        }
        
        .btn-danger {
            @apply bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-xl transition-all duration-300 cursor-pointer;
        }
        
        .card {
            @apply bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden;
        }
        
        .input {
            @apply w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all;
        }
        
        .label {
            @apply block text-xs font-bold text-slate-700 mb-1.5;
        }
    </style>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-slate-100 font-sans antialiased">

    
    <button id="mobileMenuBtn" class="lg:hidden fixed top-4 right-4 z-50 bg-emerald-600 text-white p-3 rounded-xl shadow-lg">
        <i class="fas fa-bars text-xl"></i>
    </button>

    <div class="flex min-h-screen">
        
        
        <?php echo $__env->make('components.admin.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <main class="flex-1 overflow-x-hidden">
            
            <div class="bg-white border-b border-slate-200 px-6 py-4 sticky top-0 z-30 shadow-sm">
                <div class="flex justify-between items-center flex-wrap gap-3">
                    <div>
                        <h1 class="text-xl font-black text-slate-900"><?php echo $__env->yieldContent('page_heading'); ?></h1>
                        <p class="text-xs text-slate-500 mt-0.5"><?php echo $__env->yieldContent('page_subheading', 'لوحة تحكم دليل سوريا التجاري'); ?></p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-slate-600 hidden md:block">
                            <i class="far fa-calendar-alt ml-1"></i>
                            <?php echo e(now()->format('Y/m/d')); ?>

                        </span>
                        <div class="relative group">
                            <button class="flex items-center gap-2 bg-slate-100 hover:bg-slate-200 rounded-xl px-3 py-2 transition-all">
                                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                    <?php echo e(substr(Auth::user()->name ?? 'مدير', 0, 1)); ?>

                                </div>
                                <span class="text-sm font-bold text-slate-700 hidden md:block"><?php echo e(Auth::user()->name ?? 'المدير'); ?></span>
                                <i class="fas fa-chevron-down text-slate-500 text-xs"></i>
                            </button>
                            <div class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-200 hidden group-hover:block z-50">
                                <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 transition-colors">
                                    <i class="fas fa-globe"></i> عرض الموقع
                                </a>
                                <form action="<?php echo e(route('logout')); ?>" method="POST" class="block">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="w-full text-right flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="px-6 pt-4">
                <?php if(session('success')): ?>
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl text-sm mb-4 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-emerald-500"></i>
                            <?php echo e(session('success')); ?>

                        </div>
                        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                <?php endif; ?>
                
                <?php if(session('error')): ?>
                    <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl text-sm mb-4 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-exclamation-triangle text-red-500"></i>
                            <?php echo e(session('error')); ?>

                        </div>
                        <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            
            <div class="p-6">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </main>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const closeSidebar = document.getElementById('closeSidebar');
            
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', () => {
                    sidebar.classList.remove('sidebar-mobile-hidden');
                    sidebar.classList.add('sidebar-mobile-visible');
                });
            }
            
            if (closeSidebar) {
                closeSidebar.addEventListener('click', () => {
                    sidebar.classList.add('sidebar-mobile-hidden');
                    sidebar.classList.remove('sidebar-mobile-visible');
                });
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth < 1024) {
                    if (!sidebar.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                        sidebar.classList.add('sidebar-mobile-hidden');
                        sidebar.classList.remove('sidebar-mobile-visible');
                    }
                }
            });
        });
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH /home/u316371041/domains/aza-international.com/public_html/dlil/resources/views/layouts/admin.blade.php ENDPATH**/ ?>