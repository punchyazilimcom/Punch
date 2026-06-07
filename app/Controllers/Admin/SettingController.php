<?php

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\App;
use App\Models\Setting;

class SettingController extends AdminController
{
    public function index(Request $request): never
    {
        $s = new Setting();
        $this->view('admin/settings', [
            'pageTitle' => 'Ayarlar',
            's'         => $s,
            // .env'den okunan (salt-okunur) durum bilgisi
            'envStatus' => [
                'mail'  => (bool) App::config('mail.host') && (bool) App::config('mail.password'),
                'paytr' => (bool) App::config('paytr.merchant_id') && (bool) App::config('paytr.merchant_key'),
                'test_mode' => (int) App::config('paytr.test_mode'),
            ],
        ]);
    }

    public function update(Request $request): never
    {
        $keys = [
            'site_title', 'site_tagline',
            'contact_phone', 'contact_whatsapp', 'contact_email', 'contact_address', 'contact_maps_embed',
            'social_instagram', 'social_linkedin', 'social_x', 'social_youtube',
            'seo_default_title', 'seo_default_description',
            'ga4_id', 'google_site_verification',
        ];
        $pairs = [];
        foreach ($keys as $k) {
            $pairs[$k] = (string) $request->input($k, '');
        }
        (new Setting())->setMany($pairs);
        $this->audit('settings.update');
        $this->withSuccess('Ayarlar kaydedildi.');
        $this->redirect('/admin/ayarlar');
    }
}
