<?php

namespace App\Helpers;

class SeoHelper
{
    private array $meta = [
        'title' => 'دليل سوريا التجاري | دليلك الشامل للأعمال في سوريا',
        'description' => 'دليلك الشامل للأعمال والخدمات في سوريا. اكتشف آلاف المنشآت التجارية والخدمية مع تقييمات حقيقية ومواقع تفاعلية.',
        'keywords' => 'دليل سوريا, أعمال سوريا, منشآت تجارية سورية, دليل شركات سوريا, مطاعم دمشق, صيدليات حلب, محلات سوريا',
        'image' => '',
        'url' => '',
        'type' => 'website',
        'robots' => 'index, follow',
    ];
    
    public function __construct()
    {
        $this->meta['url'] = url()->current();
    }
    
    public function setTitle(?string $title, bool $appendSiteName = true): self
    {
        $siteName = \App\Models\Setting::get('site_name', 'دليل سوريا التجاري');
        $title = $title ?: $siteName;
        $this->meta['title'] = $appendSiteName ? "{$title} | {$siteName}" : $title;
        return $this;
    }
    
    public function setDescription(?string $description): self
    {
        $this->meta['description'] = mb_substr(strip_tags($description ?? ''), 0, 160);
        return $this;
    }
    
    public function setKeywords(?string $keywords): self
    {
        $this->meta['keywords'] = $keywords ?? $this->meta['keywords'];
        return $this;
    }
    
    public function setImage(?string $imageUrl): self
    {
        $this->meta['image'] = $imageUrl ?? '';
        return $this;
    }
    
    public function setType(string $type): self
    {
        $this->meta['type'] = $type;
        return $this;
    }
    
    public function noIndex(): self
    {
        $this->meta['robots'] = 'noindex, nofollow';
        return $this;
    }
    
    public function render(): string
    {
        $html = [];
        
        // Basic Meta
        $html[] = "<title>" . $this->escape($this->meta['title']) . "</title>";
        $html[] = "<meta name=\"description\" content=\"" . $this->escape($this->meta['description']) . "\">";
        $html[] = "<meta name=\"keywords\" content=\"" . $this->escape($this->meta['keywords']) . "\">";
        $html[] = "<meta name=\"robots\" content=\"" . $this->escape($this->meta['robots']) . "\">";
        
        // Open Graph
        $html[] = "<meta property=\"og:title\" content=\"" . $this->escape($this->meta['title']) . "\">";
        $html[] = "<meta property=\"og:description\" content=\"" . $this->escape($this->meta['description']) . "\">";
        $html[] = "<meta property=\"og:url\" content=\"" . $this->escape($this->meta['url']) . "\">";
        $html[] = "<meta property=\"og:type\" content=\"" . $this->escape($this->meta['type']) . "\">";
        if (!empty($this->meta['image'])) {
            $html[] = "<meta property=\"og:image\" content=\"" . $this->escape($this->meta['image']) . "\">";
        }
        $siteName = \App\Models\Setting::get('site_name', 'دليل سوريا التجاري');
        $html[] = "<meta property=\"og:site_name\" content=\"" . $this->escape($siteName) . "\">";
        
        // Twitter Card
        $html[] = "<meta name=\"twitter:card\" content=\"summary_large_image\">";
        $html[] = "<meta name=\"twitter:title\" content=\"" . $this->escape($this->meta['title']) . "\">";
        $html[] = "<meta name=\"twitter:description\" content=\"" . $this->escape($this->meta['description']) . "\">";
        if (!empty($this->meta['image'])) {
            $html[] = "<meta name=\"twitter:image\" content=\"" . $this->escape($this->meta['image']) . "\">";
        }
        
        // Canonical
        $html[] = "<link rel=\"canonical\" href=\"" . $this->escape($this->meta['url']) . "\">";
        
        return implode("\n    ", $html);
    }
    
    /**
     * الهروب من النص مع التعامل مع القيم الفارغة (null)
     */
    private function escape(?string $string): string
    {
        return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
    }
}