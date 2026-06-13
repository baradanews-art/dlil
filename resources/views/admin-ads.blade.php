@extends('layouts.admin')

@section('title', 'إدارة الإعلانات')
@section('page_heading', '📢 إدارة الإعلانات')
@section('page_subheading', 'إضافة وإدارة البنرات الإعلانية')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    {{-- Add Ad Form --}}
    <div class="lg:col-span-1">
        <div class="card sticky top-24">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="font-bold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-emerald-500"></i>
                    إضافة إعلان جديد
                </h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.ads.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="label">عنوان الإعلان *</label>
                        <input type="text" name="title" required placeholder="مثال: إعلان مفروشات النور" class="input">
                    </div>
                    <div>
                        <label class="label">رابط التوجيه</label>
                        <input type="url" name="link_url" placeholder="https://example.com" class="input">
                    </div>
                    <div>
                        <label class="label">مكان الظهور *</label>
                        <select name="position" class="input">
                            <option value="sidebar">📱 القائمة الجانبية (Sidebar)</option>
                            <option value="home_top">📺 أعلى الصفحة الرئيسية (Top Banner)</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">صورة الإعلان *</label>
                        <input type="file" name="image" required accept="image/*" class="input !p-2">
                        <p class="text-[10px] text-slate-400 mt-1">JPG, PNG, WEBP (max 2MB)</p>
                    </div>
                    <button type="submit" class="btn-primary w-full">
                        <i class="fas fa-upload ml-2"></i> رفع الإعلان
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    {{-- Ads List --}}
    <div class="lg:col-span-2">
        <div class="card">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="font-bold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-list text-emerald-500"></i>
                    الإعلانات ({{ count($ads) }})
                </h3>
            </div>
            
            <div class="divide-y divide-slate-100">
                @forelse($ads as $ad)
                <div class="p-4 flex flex-wrap items-center gap-4 hover:bg-slate-50 transition-all">
                    <div class="w-24 h-20 bg-slate-100 rounded-xl overflow-hidden flex-shrink-0">
                        <img src="{{ $ad->image_url }}" 
                             class="w-full h-full object-cover" 
                             loading="lazy"
                             onerror="this.src='https://placehold.co/300x200/1e293b/10b981?text=Ad'">
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-slate-800">{{ $ad->title }}</h4>
                        <div class="flex flex-wrap gap-3 mt-1">
                            <span class="text-xs text-slate-500">
                                <i class="fas fa-link ml-1"></i> {{ Str::limit($ad->link_url ?? 'بدون رابط', 40) }}
                            </span>
                            <span class="text-xs {{ $ad->position == 'home_top' ? 'text-emerald-600' : 'text-sky-600' }}">
                                <i class="fas {{ $ad->position == 'home_top' ? 'fa-tv' : 'fa-bars' }} ml-1"></i>
                                {{ $ad->position == 'home_top' ? 'أعلى الصفحة' : 'القائمة الجانبية' }}
                            </span>
                            <span class="text-xs text-slate-400">
                                <i class="far fa-calendar-alt ml-1"></i> {{ $ad->created_at->format('Y-m-d') }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <form action="{{ route('admin.ads.destroy', $ad->id) }}" 
                              method="POST" 
                              onsubmit="return confirm('هل أنت متأكد من حذف هذا الإعلان؟')" 
                              class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500/20 hover:bg-red-500 text-red-600 hover:text-white px-3 py-2 rounded-lg transition-all">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="p-12 text-center text-slate-500">
                    <i class="fas fa-ad text-5xl mb-3 block opacity-50"></i>
                    <p>لا توجد إعلانات مضافة حالياً</p>
                    <p class="text-xs mt-1">أضف إعلانك الأول من النموذج المجاور</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection