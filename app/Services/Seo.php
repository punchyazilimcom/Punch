<?php

namespace App\Services;

use App\Core\App;

/**
 * SEO meta + JSON-LD yapilandirilmis veri uretici.
 */
class Seo
{
    /**
     * Sayfa icin SEO veri paketi olusturur (layout head tarafindan kullanilir).
     */
    public static function build(array $overrides = []): array
    {
        $defaults = [
            'title'       => App::config('app.name'),
            'description' => 'Ankara merkezli dijital yazilim ajansi.',
            'canonical'   => self::currentUrl(),
            'og_image'    => App::config('app.url') . '/assets/img/og-default.svg',
            'og_type'     => 'website',
            'robots'      => 'index, follow',
            'jsonld'      => [],
        ];
        return array_merge($defaults, $overrides);
    }

    public static function currentUrl(): string
    {
        $base = rtrim((string) App::config('app.url'), '/');
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($path, PHP_URL_PATH) ?: '/';
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }
        return $base . $path;
    }

    // --- JSON-LD ureticileri ---
    public static function organization(): array
    {
        $company = App::config('company');
        return [
            '@context' => 'https://schema.org',
            '@type'    => 'Organization',
            'name'     => $company['name'],
            'url'      => App::config('app.url'),
            'logo'     => App::config('app.url') . '/assets/img/logo.svg',
            'email'    => $company['email'],
            'address'  => [
                '@type'           => 'PostalAddress',
                'addressLocality' => $company['city'],
                'addressCountry'  => 'TR',
                'streetAddress'   => $company['address'],
            ],
        ];
    }

    public static function localBusiness(): array
    {
        $company = App::config('company');
        $data = [
            '@context' => 'https://schema.org',
            '@type'    => 'ProfessionalService',
            'name'     => $company['name'],
            'image'    => App::config('app.url') . '/assets/img/og-default.svg',
            'url'      => App::config('app.url'),
            'email'    => $company['email'],
            'address'  => [
                '@type'           => 'PostalAddress',
                'addressLocality' => 'Yenimahalle',
                'addressRegion'   => 'Ankara',
                'addressCountry'  => 'TR',
                'streetAddress'   => $company['address'],
            ],
            'areaServed' => 'TR',
            'priceRange' => '₺₺',
        ];
        if (!empty($company['phone'])) {
            $data['telephone'] = $company['phone'];
        }
        return $data;
    }

    public static function website(): array
    {
        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'WebSite',
            'name'            => App::config('app.name'),
            'url'             => App::config('app.url'),
            'potentialAction' => [
                '@type'       => 'SearchAction',
                'target'      => App::config('app.url') . '/blog?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    public static function breadcrumb(array $items): array
    {
        $list = [];
        $pos = 1;
        foreach ($items as $name => $url) {
            $list[] = [
                '@type'    => 'ListItem',
                'position' => $pos++,
                'name'     => $name,
                'item'     => $url,
            ];
        }
        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $list,
        ];
    }

    public static function faqPage(array $faqs): array
    {
        $entities = [];
        foreach ($faqs as $faq) {
            $entities[] = [
                '@type'          => 'Question',
                'name'           => $faq['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['a']],
            ];
        }
        return [
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => $entities,
        ];
    }

    public static function product(array $package): array
    {
        $data = [
            '@context'    => 'https://schema.org',
            '@type'       => 'Product',
            'name'        => $package['title'],
            'description' => $package['short_desc'] ?? '',
            'brand'       => ['@type' => 'Brand', 'name' => App::config('app.name')],
        ];
        if (empty($package['is_quote_only']) && (float) $package['price'] > 0) {
            $data['offers'] = [
                '@type'         => 'Offer',
                'price'         => (string) $package['price'],
                'priceCurrency' => $package['currency'] ?? 'TRY',
                'availability'  => 'https://schema.org/InStock',
                'url'           => App::config('app.url') . '/paketler/' . $package['slug'],
            ];
        }
        return $data;
    }

    public static function blogPosting(array $post): array
    {
        return [
            '@context'      => 'https://schema.org',
            '@type'         => 'BlogPosting',
            'headline'      => $post['title'],
            'description'   => $post['excerpt'] ?? '',
            'datePublished' => $post['published_at'] ? date('c', strtotime($post['published_at'])) : null,
            'dateModified'  => $post['updated_at'] ? date('c', strtotime($post['updated_at'])) : null,
            'author'        => ['@type' => 'Organization', 'name' => App::config('app.name')],
            'publisher'     => [
                '@type' => 'Organization',
                'name'  => App::config('app.name'),
                'logo'  => ['@type' => 'ImageObject', 'url' => App::config('app.url') . '/assets/img/logo.svg'],
            ],
            'mainEntityOfPage' => App::config('app.url') . '/blog/' . $post['slug'],
        ];
    }

    public static function renderJsonLd(array $blocks): string
    {
        $out = '';
        foreach ($blocks as $block) {
            if (empty($block)) {
                continue;
            }
            $json = json_encode($block, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            $out .= "<script type=\"application/ld+json\">{$json}</script>\n";
        }
        return $out;
    }
}
