<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Services\Seo;

class PageController extends Controller
{
    public function about(Request $request): never
    {
        $stats = setting_json('stats_json', []);
        $seo = Seo::build([
            'title'       => 'Hakkimizda — Punch Yazilim | Ankara Dijital Ajans',
            'description' => 'Punch Yazilim; Ankara merkezli, deneyim odakli dijital cozumler ureten bir yazilim ajansidir. Hikayemiz, degerlerimiz ve ekibimiz.',
        ]);
        $jsonld = [Seo::organization(), Seo::breadcrumb(['Ana Sayfa' => base_url(), 'Hakkimizda' => base_url('hakkimizda')])];
        $this->render('pages/about', compact('stats', 'seo', 'jsonld'));
    }

    /**
     * Hukuki sayfalar tek controller'da; yol uzerinden icerik secilir.
     */
    public function legal(Request $request): never
    {
        $map = [
            '/kvkk' => ['key' => 'legal_kvkk', 'title' => 'KVKK Aydinlatma Metni'],
            '/gizlilik-politikasi' => ['key' => 'legal_privacy', 'title' => 'Gizlilik Politikasi'],
            '/mesafeli-satis-sozlesmesi' => ['key' => 'legal_sales', 'title' => 'Mesafeli Satis Sozlesmesi'],
            '/cerez-politikasi' => ['key' => 'legal_cookie', 'title' => 'Cerez Politikasi'],
            '/teslimat-ve-iade' => ['key' => 'legal_delivery', 'title' => 'Teslimat ve Iade Kosullari'],
        ];
        $entry = $map[$request->path] ?? null;
        if (!$entry) {
            $this->abort(404);
        }

        $body = setting($entry['key'], $this->defaultLegal($entry['key']));
        $seo = Seo::build([
            'title'       => $entry['title'] . ' | Punch Yazilim',
            'description' => $entry['title'] . ' — Punch Yazilim.',
            'robots'      => 'index, follow',
        ]);
        $jsonld = [Seo::breadcrumb(['Ana Sayfa' => base_url(), $entry['title'] => Seo::currentUrl()])];
        $this->render('pages/legal', [
            'pageTitle' => $entry['title'],
            'body'      => $body,
            'seo'       => $seo,
            'jsonld'    => $jsonld,
        ]);
    }

    /** Panelden duzenlenmemisse gosterilecek varsayilan hukuki metinler. */
    private function defaultLegal(string $key): string
    {
        $company = config('company');
        $name = e($company['name']);
        $email = e($company['email']);
        $addr = e($company['address']);
        return match ($key) {
            'legal_kvkk' => "<p>{$name} olarak 6698 sayili Kisisel Verilerin Korunmasi Kanunu (KVKK) kapsaminda veri sorumlusu sifatiyla, kisisel verilerinizi asagida aciklanan amaclar dogrultusunda isliyoruz.</p><h2>Islenen Veriler</h2><p>Ad-soyad, e-posta, telefon, fatura bilgileri ve site kullanim verileri.</p><h2>Isleme Amaclari</h2><p>Hizmet sunumu, sozlesme yukumlulukleri, iletisim ve yasal yukumluluklerin yerine getirilmesi.</p><h2>Haklariniz</h2><p>KVKK m.11 kapsamindaki haklariniz icin <a href='mailto:{$email}'>{$email}</a> adresine basvurabilirsiniz.</p><p><em>Bu metin bilgilendirme amaclidir; nihai hukuki metin icin lutfen panelden guncelleyin.</em></p>",
            'legal_privacy' => "<p>{$name} gizliliginize onem verir. Bu politika, kisisel verilerinizin nasil toplandigini, kullanildigini ve korundugunu aciklar.</p><h2>Toplanan Bilgiler</h2><p>Iletisim formlari, uyelik ve odeme islemleri sirasinda paylastiginiz bilgiler.</p><h2>Cerezler</h2><p>Deneyiminizi iyilestirmek icin cerezler kullaniyoruz. Detay icin Cerez Politikasi.</p><h2>Iletisim</h2><p><a href='mailto:{$email}'>{$email}</a></p>",
            'legal_sales' => "<p>Isbu Mesafeli Satis Sozlesmesi, {$name} ({$addr}) ile alici arasinda, elektronik ortamda kurulan satislar icin gecerlidir.</p><h2>Konu</h2><p>Alicinin elektronik ortamda siparis verdigi hizmetlerin satisi ve teslimi.</p><h2>Cayma Hakki</h2><p>Niteligi geregi dijital hizmetlerde, ifaya baslanmasi halinde cayma hakki kullanilamayabilir (Mesafeli Sozlesmeler Yonetmeligi).</p><h2>Odeme</h2><p>Odemeler guvenli PayTR altyapisi ile alinir.</p><p><em>Bu metin ornek niteligindedir; panelden guncelleyiniz.</em></p>",
            'legal_cookie' => "<p>{$name} olarak web sitemizde cerezler kullaniyoruz.</p><h2>Cerez Turleri</h2><ul><li>Zorunlu cerezler: Sitenin calismasi icin gereklidir.</li><li>Analitik cerezler: Trafigi anlamak icin (onayiniza baglidir).</li></ul><h2>Yonetim</h2><p>Tarayici ayarlarinizdan cerezleri yonetebilirsiniz.</p>",
            'legal_delivery' => "<p>Dijital hizmetlerimizde teslimat, proje kapsamina gore belirlenen surede dijital ortamda yapilir.</p><h2>Teslim Suresi</h2><p>Kurumsal web siteleri ortalama 2-4 hafta icinde teslim edilir.</p><h2>Iade</h2><p>Ifaya baslanmamis hizmetlerde iade talepleri <a href='mailto:{$email}'>{$email}</a> uzerinden degerlendirilir.</p>",
            default => '<p>Icerik yakinda eklenecektir.</p>',
        };
    }
}
