<?php

namespace App\Services;

use App\Core\App;
use App\Models\Setting;

/**
 * Hukuki metin saglayicisi.
 *
 * Oncelik sirasi: Admin panelinden girilen metin (settings) > buradaki tam varsayilan metin.
 * Boylece site "standart sablon" yerine markaya ozel, kapsamli metinlerle yayina cikar;
 * yonetici dilerse panelden gunceller.
 *
 * NOT: Metinler bilgilendirme amaçli olup sektorel gerçeklere gore hazirlanmistir.
 * Yayina almadan once bir hukuk danismanina son kontrol yaptirmaniz onerilir.
 */
class LegalText
{
    /** @var array<string,string> setting anahtari => baslik */
    public const PAGES = [
        'legal_preinfo'  => 'On Bilgilendirme Formu',
        'legal_sales'    => 'Mesafeli Satis Sozlesmesi',
        'legal_delivery' => 'Teslimat ve Iade Kosullari',
        'legal_kvkk'     => 'KVKK Aydinlatma Metni',
        'legal_privacy'  => 'Gizlilik Politikasi',
        'legal_cookie'   => 'Cerez Politikasi',
    ];

    private static function company(): array
    {
        $c = App::config('company');
        return [
            'name'  => $c['name'] ?: 'Punch Yazilim',
            'email' => setting('contact_email', $c['email'] ?: 'destek@punchyazilim.com'),
            'phone' => setting('contact_phone', $c['phone'] ?: ''),
            'addr'  => setting('contact_address', $c['address'] ?: 'Yenimahalle, Ankara'),
            'site'  => App::config('app.url'),
        ];
    }

    /** Panel metni varsa onu, yoksa tam varsayilan metni dondurur. */
    public static function get(string $key): string
    {
        $custom = (new Setting())->get($key);
        if ($custom !== null && trim($custom) !== '') {
            return $custom;
        }
        return self::default($key);
    }

    public static function title(string $key): string
    {
        return self::PAGES[$key] ?? 'Hukuki Metin';
    }

    public static function default(string $key): string
    {
        $c = self::company();
        return match ($key) {
            'legal_preinfo'  => self::preinfo($c),
            'legal_sales'    => self::sales($c),
            'legal_delivery' => self::delivery($c),
            'legal_kvkk'     => self::kvkk($c),
            'legal_privacy'  => self::privacy($c),
            'legal_cookie'   => self::cookie($c),
            default          => '<p>Icerik yakinda eklenecektir.</p>',
        };
    }

    // ====================================================================
    //  ON BILGILENDIRME FORMU
    // ====================================================================
    private static function preinfo(array $c): string
    {
        $phone = $c['phone'] ? "<li><strong>Telefon:</strong> {$c['phone']}</li>" : '';
        return <<<HTML
<p>İşbu Ön Bilgilendirme Formu, 6502 sayılı Tüketicinin Korunması Hakkında Kanun ve Mesafeli Sözleşmeler Yönetmeliği uyarınca, hizmeti satın almadan <strong>önce</strong> sizi bilgilendirmek amacıyla hazırlanmıştır.</p>

<h2>1. Hizmet Sağlayıcı (Satıcı) Bilgileri</h2>
<ul>
  <li><strong>Unvan:</strong> {$c['name']}</li>
  <li><strong>Adres:</strong> {$c['addr']}</li>
  <li><strong>E-posta:</strong> {$c['email']}</li>
  {$phone}
  <li><strong>Web:</strong> {$c['site']}</li>
</ul>

<h2>2. Sözleşme Konusu Hizmetin Temel Nitelikleri</h2>
<p>Satın aldığınız paketin adı, kapsamı (özellik listesi), teslim biçimi ve fiyatı; ödeme ekranındaki sipariş özetinde ve ilgili paket sayfasında açıkça belirtilir. Hizmetlerimiz dijital nitelikte olup; web sitesi tasarımı/geliştirilmesi, e‑ticaret kurulumu, sosyal medya yönetimi ve özel yazılım gibi kalemleri kapsar.</p>

<h2>3. Fiyat ve Ödeme</h2>
<ul>
  <li>Tüm fiyatlar Türk Lirası (₺) cinsinden olup <strong>KDV dahildir</strong>. Sipariş özetinde toplam tutar net olarak gösterilir.</li>
  <li>Ödeme, <strong>PayTR</strong> altyapısı üzerinden 3D Secure güvenli ödeme ile kredi/banka kartı kullanılarak tek seferde alınır. Kart bilgileriniz Satıcı tarafından saklanmaz.</li>
  <li>Sipariş onayı ve ödeme sonrası fatura tarafınıza e‑posta ile iletilir ve panelinizden indirilebilir.</li>
</ul>

<h2>4. Teslimat</h2>
<p>Hizmet, dijital ortamda ve paket kapsamında belirtilen süreçle ifa edilir. Tahmini teslim/başlangıç süreleri "Teslimat ve İade Koşulları" sayfasında açıklanmıştır. Süreç, müşteri tarafından gerekli içerik/erişimlerin sağlanmasıyla başlar.</p>

<h2>5. Cayma Hakkı ve İstisnası</h2>
<p>Mesafeli Sözleşmeler Yönetmeliği m.15 uyarınca; <strong>elektronik ortamda anında ifa edilen hizmetler ile tüketiciye anında teslim edilen gayrimaddi mallara</strong> ve <strong>tüketicinin onayı ile ifasına başlanan hizmetlere</strong> ilişkin sözleşmelerde cayma hakkı kullanılamaz. Buna göre, işin/üretimin fiilen başlamasından sonra cayma hakkı sona erer. İşe başlanmadan önceki taleplerde iade koşulları "Teslimat ve İade Koşulları" sayfasında düzenlenmiştir.</p>

<h2>6. Şikâyet ve İtiraz</h2>
<p>Her türlü talep ve şikâyetiniz için {$c['email']} adresinden bize ulaşabilirsiniz. Uyuşmazlıklarda, ilgili parasal sınırlar dâhilinde Tüketici Hakem Heyetleri ve Tüketici Mahkemeleri yetkilidir.</p>

<p>Ödeme adımındaki onay kutusunu işaretleyerek, işbu Ön Bilgilendirme Formu'nu okuyup anladığınızı ve onayladığınızı kabul etmiş olursunuz.</p>
HTML;
    }

    // ====================================================================
    //  MESAFELI SATIS SOZLESMESI
    // ====================================================================
    private static function sales(array $c): string
    {
        $phone = $c['phone'] ? "<li><strong>Telefon:</strong> {$c['phone']}</li>" : '';
        return <<<HTML
<p>İşbu Mesafeli Satış Sözleşmesi ("Sözleşme"), 6502 sayılı Tüketicinin Korunması Hakkında Kanun ve Mesafeli Sözleşmeler Yönetmeliği'ne uygun olarak, aşağıda bilgileri yer alan taraflar arasında elektronik ortamda kurulmuştur.</p>

<h2>1. Taraflar</h2>
<p><strong>SATICI</strong></p>
<ul>
  <li><strong>Unvan:</strong> {$c['name']}</li>
  <li><strong>Adres:</strong> {$c['addr']}</li>
  <li><strong>E-posta:</strong> {$c['email']}</li>
  {$phone}
</ul>
<p><strong>ALICI:</strong> Web sitesi üzerinden üyelik ve sipariş bilgilerini sağlayan müşteri. Alıcının sipariş esnasında belirttiği ad‑soyad, adres, e‑posta ve fatura bilgileri esas alınır.</p>

<h2>2. Sözleşmenin Konusu</h2>
<p>İşbu Sözleşme'nin konusu; Alıcı'nın, Satıcı'ya ait web sitesinden elektronik ortamda siparişini verdiği, nitelikleri ve satış fiyatı sipariş özetinde belirtilen dijital hizmetin satışı ve ifası ile tarafların hak ve yükümlülüklerinin belirlenmesidir.</p>

<h2>3. Hizmet Bilgileri ve Bedel</h2>
<ul>
  <li>Hizmetin adı, kapsamı ve adedi: Sipariş özetinde belirtilen pakettir.</li>
  <li>Toplam bedel (KDV dahil): Sipariş özetinde gösterilen tutardır.</li>
  <li>Ödeme şekli: PayTR güvenli ödeme altyapısı üzerinden kredi/banka kartı ile peşin.</li>
</ul>

<h2>4. Genel Hükümler</h2>
<p>4.1. Alıcı, Sözleşme konusu hizmetin temel nitelikleri, satış fiyatı ve ödeme şekli ile ifaya ilişkin Ön Bilgilendirme Formu'nu okuyup bilgi sahibi olduğunu ve elektronik ortamda gerekli teyidi verdiğini kabul eder.</p>
<p>4.2. Hizmetin ifası, ödemenin tamamlanması ve Alıcı'nın gerekli içerik, görsel, metin ve erişim bilgilerini Satıcı'ya sağlaması ile başlar. Alıcı'nın gecikmesinden kaynaklanan süre uzamalarından Satıcı sorumlu değildir.</p>
<p>4.3. Satıcı, hizmeti özen ve profesyonellik içinde, paket kapsamına uygun olarak ifa etmeyi taahhüt eder.</p>

<h2>5. Cayma Hakkı</h2>
<p>Mesafeli Sözleşmeler Yönetmeliği m.15/1 uyarınca; elektronik ortamda anında ifa edilen hizmetler ve tüketiciye anında teslim edilen gayrimaddi mallar ile <strong>Alıcı'nın onayı ile ifasına başlanan hizmetlerde cayma hakkı kullanılamaz</strong>. Alıcı, satın alma sırasında işin ifasına derhal başlanmasını onayladığını ve bu kapsamda cayma hakkının bulunmadığını kabul eder. İşe fiilen başlanmadan önce yapılan iptal taleplerinde, "Teslimat ve İade Koşulları"ndaki iade esasları uygulanır.</p>

<h2>6. Fatura</h2>
<p>Ödeme onaylandığında fatura düzenlenir; Alıcı'nın e‑posta adresine gönderilir ve müşteri panelinden PDF olarak indirilebilir.</p>

<h2>7. Gizlilik ve Kişisel Veriler</h2>
<p>Alıcı'ya ait kişisel veriler, KVKK Aydınlatma Metni ve Gizlilik Politikası çerçevesinde işlenir ve korunur.</p>

<h2>8. Mücbir Sebepler</h2>
<p>Doğal afet, yangın, salgın, altyapı/iletişim kesintileri gibi mücbir sebep hâllerinde ifa süresi makul ölçüde uzayabilir; bu durum taraflara temerrüt sorumluluğu yüklemez.</p>

<h2>9. Yetkili Mahkeme</h2>
<p>İşbu Sözleşme'den doğabilecek uyuşmazlıklarda, Ticaret Bakanlığı'nca ilan edilen parasal sınırlar dâhilinde Alıcı'nın veya Satıcı'nın yerleşim yerindeki Tüketici Hakem Heyetleri ile Tüketici Mahkemeleri yetkilidir.</p>

<h2>10. Yürürlük</h2>
<p>Alıcı, ödeme adımında işbu Sözleşme'nin tüm koşullarını okuyup kabul ettiğini beyan eder. Sözleşme, siparişin onaylanması ile yürürlüğe girer.</p>
HTML;
    }

    // ====================================================================
    //  TESLIMAT VE IADE
    // ====================================================================
    private static function delivery(array $c): string
    {
        return <<<HTML
<p>Bu sayfada dijital hizmetlerimizin teslim süreçleri ve iade esasları açıklanmaktadır.</p>

<h2>1. Teslim Süreci</h2>
<ul>
  <li><strong>Kurumsal Web Sitesi:</strong> Brief ve içeriklerin tam teslimini takiben ortalama <strong>2–4 hafta</strong> içinde yayına alınır.</li>
  <li><strong>E‑Ticaret Sitesi:</strong> Kapsam ve ürün sayısına bağlı olarak ortalama <strong>3–6 hafta</strong>.</li>
  <li><strong>Sosyal Medya Yönetimi:</strong> Sözleşme başlangıcını izleyen ilk hafta strateji ve içerik takvimiyle başlar; aylık periyotlarla devam eder.</li>
  <li><strong>Özel Yazılım:</strong> Kapsam dokümanında belirlenen takvime göre aşamalı teslim edilir.</li>
</ul>
<p>Süreler tahminîdir ve müşterinin içerik/görsel/erişim sağlama hızına göre değişebilir. Süreç, gerekli bilgilerin Satıcı'ya iletilmesiyle başlar.</p>

<h2>2. Teslim Şekli</h2>
<p>Hizmetler dijital ortamda ifa edilir; çıktı, ilgili alan adı/sunucu üzerinde yayınlama, kaynak/erişim paylaşımı veya panel teslimi şeklinde sağlanır. Teslim, e‑posta ile bilgilendirme yapılarak gerçekleştirilir.</p>

<h2>3. İade Koşulları</h2>
<ul>
  <li>İşin/üretimin fiilen <strong>başlamasından önce</strong> yapılan iptal taleplerinde, varsa yapılan ön hazırlık ve üçüncü taraf maliyetleri (alan adı, lisans, eklenti vb.) düşülerek kalan tutar iade edilir.</li>
  <li>İşin ifasına başlanmış olması hâlinde, tamamlanan iş oranı ve harcanan emek dikkate alınarak hakkaniyet çerçevesinde kısmi iade değerlendirilir.</li>
  <li>Tamamlanıp teslim edilmiş dijital hizmetlerde, niteliği gereği iade yapılamaz (bkz. Mesafeli Satış Sözleşmesi m.5).</li>
</ul>

<h2>4. İade Süreci</h2>
<p>Onaylanan iadeler, ödemenin yapıldığı karta <strong>PayTR</strong> üzerinden, bankanıza bağlı olarak genellikle <strong>3–10 iş günü</strong> içinde yansıtılır. İade talepleri {$c['email']} adresine, sipariş numarası belirtilerek iletilmelidir.</p>

<h2>5. Revizyon Politikası</h2>
<p>Her paket, kapsamında belirtilen sayıda ücretsiz revizyon içerir. Kapsam dışı veya ek talepler ayrıca fiyatlandırılır ve onayınızla uygulanır.</p>

<h2>6. İletişim</h2>
<p>Teslimat ve iade ile ilgili tüm sorularınız için: {$c['email']}</p>
HTML;
    }

    // ====================================================================
    //  KVKK AYDINLATMA METNI
    // ====================================================================
    private static function kvkk(array $c): string
    {
        return <<<HTML
<p>{$c['name']} olarak, 6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") kapsamında <strong>veri sorumlusu</strong> sıfatıyla, kişisel verilerinizi aşağıda açıklanan amaç ve esaslarla işliyoruz.</p>

<h2>1. İşlenen Kişisel Veriler</h2>
<ul>
  <li><strong>Kimlik:</strong> ad‑soyad; fatura için talep edildiğinde TC/Vergi no.</li>
  <li><strong>İletişim:</strong> e‑posta, telefon, adres.</li>
  <li><strong>Müşteri işlem:</strong> sipariş, fatura, ödeme durumu, destek talepleri.</li>
  <li><strong>İşlem güvenliği:</strong> IP adresi, oturum ve log kayıtları.</li>
</ul>

<h2>2. İşleme Amaçları</h2>
<ul>
  <li>Hizmetlerin sunulması ve sözleşmesel yükümlülüklerin yerine getirilmesi,</li>
  <li>Sipariş, ödeme ve faturalandırma süreçlerinin yürütülmesi,</li>
  <li>Destek ve iletişim taleplerinin karşılanması,</li>
  <li>Hukuki yükümlülüklerin (vergi, ticaret mevzuatı vb.) yerine getirilmesi,</li>
  <li>Bilgi güvenliğinin ve dolandırıcılık önleminin sağlanması.</li>
</ul>

<h2>3. Hukuki Sebepler</h2>
<p>Verileriniz; sözleşmenin kurulması/ifası, hukuki yükümlülük, meşru menfaat ve açık rıza (gerektiğinde) hukuki sebeplerine dayanılarak işlenir.</p>

<h2>4. Aktarım</h2>
<p>Verileriniz; ödeme altyapısı (PayTR), e‑posta/sunucu hizmet sağlayıcıları (ör. Hostinger) ve yasal olarak yetkili kamu kurumları ile sınırlı ve amaçla bağlı olarak paylaşılabilir. Yurt dışı aktarım yalnızca mevzuata uygun şekilde ve gerekli güvenceler sağlanarak yapılır.</p>

<h2>5. Saklama Süresi</h2>
<p>Kişisel veriler, ilgili mevzuatta öngörülen veya işleme amacının gerektirdiği süre boyunca saklanır; sürenin sonunda silinir, yok edilir veya anonim hâle getirilir.</p>

<h2>6. Haklarınız (KVKK m.11)</h2>
<p>Verilerinizin işlenip işlenmediğini öğrenme, bilgi talep etme, düzeltme/silme isteme, işlemeye itiraz etme ve zararın giderilmesini talep etme haklarına sahipsiniz. Başvurularınızı {$c['email']} adresine iletebilirsiniz.</p>
HTML;
    }

    // ====================================================================
    //  GIZLILIK POLITIKASI
    // ====================================================================
    private static function privacy(array $c): string
    {
        return <<<HTML
<p>{$c['name']} olarak gizliliğinize önem veriyoruz. Bu politika, web sitemizi ve hizmetlerimizi kullanırken bilgilerinizin nasıl toplandığını, kullanıldığını ve korunduğunu açıklar.</p>

<h2>1. Topladığımız Bilgiler</h2>
<ul>
  <li>Üyelik, iletişim ve sipariş formlarında paylaştığınız bilgiler (ad, e‑posta, telefon, adres, fatura bilgileri).</li>
  <li>Ödeme işlemlerinde PayTR aracılığıyla işlenen ödeme bilgileri (kart bilgileri sunucularımızda saklanmaz).</li>
  <li>Site kullanımına ilişkin teknik veriler (IP, tarayıcı, çerez verileri).</li>
</ul>

<h2>2. Bilgilerin Kullanımı</h2>
<p>Bilgileriniz; hizmetlerin sunulması, sipariş ve destek süreçleri, yasal yükümlülükler ve site güvenliği amaçlarıyla kullanılır. Onayınız olmadan pazarlama amacıyla üçüncü taraflarla paylaşılmaz.</p>

<h2>3. Çerezler</h2>
<p>Deneyiminizi iyileştirmek ve trafiği analiz etmek için çerezler kullanırız. Analitik çerezler yalnızca onayınıza bağlı olarak çalışır. Ayrıntılar için Çerez Politikası'na bakınız.</p>

<h2>4. Veri Güvenliği</h2>
<p>Verileriniz; SSL şifreleme, güçlü parola saklama (Argon2id), erişim kontrolleri ve düzenli güvenlik önlemleriyle korunur.</p>

<h2>5. Üçüncü Taraf Hizmetler</h2>
<p>Ödeme (PayTR), barındırma (Hostinger) ve analitik (ör. Google Analytics) gibi hizmet sağlayıcılar, yalnızca hizmetin gerektirdiği ölçüde devreye girer ve kendi gizlilik politikalarına tabidir.</p>

<h2>6. İletişim</h2>
<p>Gizlilikle ilgili sorularınız için: {$c['email']}</p>
HTML;
    }

    // ====================================================================
    //  CEREZ POLITIKASI
    // ====================================================================
    private static function cookie(array $c): string
    {
        return <<<HTML
<p>{$c['name']} web sitesinde, hizmet kalitesini artırmak ve kullanımı analiz etmek için çerezlerden yararlanırız.</p>

<h2>1. Çerez Nedir?</h2>
<p>Çerezler, ziyaret ettiğiniz sitelerin tarayıcınıza kaydettiği küçük metin dosyalarıdır. Tercihlerinizi hatırlamaya ve siteyi geliştirmeye yardımcı olur.</p>

<h2>2. Kullandığımız Çerez Türleri</h2>
<ul>
  <li><strong>Zorunlu çerezler:</strong> Oturum, güvenlik ve temel işlevler için gereklidir; devre dışı bırakılamaz.</li>
  <li><strong>Analitik çerezler:</strong> Ziyaret istatistiklerini anlamak için kullanılır ve yalnızca <strong>onayınızla</strong> çalışır (Google Analytics – Consent Mode).</li>
  <li><strong>İşlevsel çerezler:</strong> Tercihlerinizi (ör. çerez onayı) hatırlar.</li>
</ul>

<h2>3. Çerez Yönetimi</h2>
<p>Site açıldığında görüntülenen çerez bildirimi üzerinden analitik çerezleri kabul edebilir veya yalnızca zorunlu çerezlerle devam edebilirsiniz. Ayrıca tarayıcı ayarlarınızdan çerezleri her zaman yönetebilir veya silebilirsiniz.</p>

<h2>4. İletişim</h2>
<p>Çerez politikamızla ilgili sorularınız için: {$c['email']}</p>
HTML;
    }
}
