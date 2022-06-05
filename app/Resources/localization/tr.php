<?php

/**
 * 	KalipsoNext - Localization File
 * 	Turkish(tr)
 **/

return [
    'lang' => [
        'code' => 'tr',
        'iso_code' => 'tr_TR',
        'dir' => 'ltr',
        'timezone' => 'Europe/Istanbul',
        'currency' => 'try',
        'plural_suffix' => '',
    ],
    'langs' => [
        'tr' => 'Türkçe',
        'en' => 'İngilizce',
    ],
    'err' => 'Hata',
    'error' => [
        'page_not_found' => 'Sayfa bulunamadı!',
        'method_not_allowed' => 'Methoda izin verilmez!',
        'controller_not_defined' => 'Kontrolcü tanımlanmamış!',
        'unauthorized' => 'Yetkiniz yok.',
        'view_definition_not_found' => 'Kontrolcü, görüntüleme parametresi göndermedi!',
        'csrf_token_mismatch' => 'CSRF anahtarı uyuşmuyor.',
        'csrf_token_incorrect' => 'CSRF anahtarı geçersiz.',
        'username_is_already_used' => 'Kullanıcı adı zaten kullanımda.',
        'notification_hook_file_not_found' => 'Bildirim kanca dosyası bulunamadı!',
        'a_problem_occurred' => 'Bir sorun oluştu!',
        'endpoint_file_is_not_found' => 'Yetki kontrol noktası dosyası bulunamadı!',
        'ip_blocked' => 'IP adresiniz engellenmiştir!',
        'module_not_found' => 'Modül bulunamadı!',
        'missing_or_incorrect_parameter' => 'Eksik ya da hatalı parametre!',
    ],
    'notification' => [
        'registration_email_title' => 'Hesabınız Oluşturuldu!',
        'registration_email_body' => 'Selam [USER], <br>Hesabınız oluşturuldu. Aşağıdaki bağlantı ile eposta adresinizi doğrulayabilirsiniz. <br>[VERIFY_LINK]',
        'recovery_request_email_title' => 'Hesap Kurtarma',
        'recovery_request_email_body' => 'Selam [USER], <br>Hesap kurtarma talebinizi aldık. Aşağıdaki bağlantı ile yeni şifrenizi ayarlayabilirsiniz. <br>[RECOVERY_LINK]',
        'account_recovered_email_title' => 'Hesabınız Kurtarıldı!',
        'account_recovered_email_body' => 'Selam [USER], <br>Hesabınız kurtarıldı. Bu işlemi siz yapmadıysanız lütfen bizimle iletişime geçin.',
        'email_change_email_title' => 'Eposta Adresiniz Güncellendi!',
        'email_change_email_body' => 'Selam [USER], <br>Eposta adresiniz güncellendi. Aşağıdaki bağlantı ile doğrulama yapabilirsiniz. <br>[VERIFY_LINK] <br>[CHANGES]',
    ],
    'auth' => [
        'auth' => 'Profil',
        'auth_action' => 'Profil - Alt Sayfalar',
        'auth_logout' => 'Çıkış Yap',
        'management' => 'Yönetim',
        'management_users' => 'Yönetim - Kullanıcılar',
        'management_users_list' => 'Yönetim - Kullanıcılar - Liste',
        'management_users_add' => 'Yönetim - Kullanıcılar - Ekle',
        'management_users_detail' => 'Yönetim - Kullanıcılar - Detay',
        'management_users_update' => 'Yönetim - Kullanıcılar - Düzenle',
        'management_users_delete' => 'Yönetim - Kullanıcılar - Sil',
        'management_roles' => 'Yönetim - Roller',
        'management_roles_list' => 'Yönetim - Roller - Liste',
        'management_roles_add' => 'Yönetim - Roller - Ekle',
        'management_roles_detail' => 'Yönetim - Roller - Detay',
        'management_roles_update' => 'Yönetim - Roller - Düzenle',
        'management_roles_delete' => 'Yönetim - Roller - Sil',
        'management_sessions' => 'Yönetim - Oturumlar',
        'management_sessions_list' => 'Yönetim - Oturumlar - Liste',
        'management_logs' => 'Yönetim - Kayıtlar',
        'management_logs_list' => 'Yönetim - Kayıtlar - Liste',
        'management_logs_ip_block' => 'Yönetim - Kayıtlar - IP Bloklama',
        'management_settings' => 'Yönetim - Ayarlar',
        'management_settings_update' => 'Yönetim - Ayarlar - Düzenle',
        'management_contents' => 'Yönetim - İçerikler',
        'management_contents_list' => 'Yönetim - İçerikler - Liste',
        'management_contents_add' => 'Yönetim - İçerikler - Ekle',
        'management_contents_detail' => 'Yönetim - İçerikler - Detay',
        'management_contents_update' => 'Yönetim - İçerikler - Düzenle',
        'management_contents_delete' => 'Yönetim - İçerikler - Sil',
        'management_contents_slug' => 'Yönetim - İçerikler - Kısa Ad Sorgulama',
        'management_content_upload_file' => 'Yönetim - İçerikler - Editör Dosya Yükleme',
        'management_forms' => 'Yönetim - Formlar',
        'management_forms_list' => 'Yönetim - Formlar - Liste',
    ],
    'settings' => [
        'basic_settings' => 'Temel Ayarlar',
        'secure_settings' => 'Güvenlik Ayarları',
        'email_settings' => 'Eposta Ayarları',
        'optimization_settings' => 'Optimizasyon Ayarları',
        'name' => 'Site Adı',
        'name_info' => 'Bu değer sayfa üst bilgilerinde ve başlıklarda görünür.',
        'description' => 'Site Açıklaması',
        'description_info' => 'Bu değer sayfa üst bilgilerinde görünür, meta tanımı olmadığı zamanlarda baz alınır.',
        'contact_email' => 'İletişim Epostası',
        'contact_email_info' => 'Arayüz tarafında kullanılır, eposta gönderimde gönderen olarak bu bilgi gösterilir.',
        'separator' => 'Ayraç',
        'separator_info' => 'Sayfa başlığında ilgili sayfa başlığından sonra site adı gösterimi yapılırken araya eklenen karakteri temsil eder.',
        'language' => 'Varsayılan Dil',
        'language_info' => 'İlk ziyaretlerde baz alınan varsayılan dil tanımıdır.',
        'default_user_role' => 'Varsayılan Kullanıcı Rolü',
        'default_user_role_info' => 'Yeni kayıtlarda kullanıcı rolü ataması yapılırken bu değer kullanılır.',
        'ssl' => 'SSL Modu',
        'ssl_info' => 'Web sayfasında kurulmuş bir SSL sertifikası var ise bu ayar açılarak adreslemelerin bu doğrultuda yapılması sağlanır. <strong class="text-danger">Bilmiyorsanız değiştirmeyin!</strong>',
        'log' => 'Log Kayıt',
        'log_info' => 'Aktif olduğunda, tüm işlemlerde log kaydı tutar. Yoğun ziyaretli sitelerde kapalı tutulması önerilir. Kapalı durumdayken sadece başarısız erişimleri kaydeder.',
        'mail_send_type' => 'Eposta Gönderim Türü',
        'mail_send_type_info' => 'Sunucuda mail() fonksiyonu aktif ise sunucu ayarıyla direkt eposta gönderimi sağlanabilir. SMTP gönderim yavaş ama güvenli gönderim sağlar.',
        'smtp_address' => 'SMTP Adresi',
        'smtp_address_info' => 'SMTP gönderimde SMTP sunucu adresini ifade eder.',
        'smtp_port' => 'SMTP Portu',
        'smtp_port_info' => 'SMTP gönderimde SMTP sunucusuna bağlantı için kullanılacak port numarasını ifade eder.',
        'smtp_email_address' => 'SMTP Eposta Adresi',
        'smtp_email_address_info' => 'SMTP gönderimde gönderimin sağlanacağı hesabın eposta adresini ifade eder.',
        'smtp_email_pass' => 'SMTP Eposta Şifresi',
        'smtp_email_pass_info' => 'SMTP gönderimde gönderimin sağlanacağı hesabın şifresini ifade eder.',
        'smtp_secure' => 'SMTP Güvenlik',
        'smtp_secure_info' => 'SMTP gönderimde SMTP sunucusuna bağlanırken kullanıcak protokolü temsil eder.',
        'mail_queue' => 'Eposta Kuyruğu',
        'mail_queue_info' => 'SMTP gönderimde olduğu gibi yavaş bir eposta gönderim durumu söz konusu olduğunda kullanıcıları bekletmemek için gönderimi kuyruğa alır. Cron işleri aktif olmalıdır. Aksi halde gönderim yapılmaz.',
        'view_cache' => 'Arayüz Önbellekleme',
        'view_cache_info' => 'Statik sayfaların çok olduğu sitelerde yanıt sürelerini düşürmek için kullanılabilir, dinamik sayfaların yoğun olduğu projelerde önerilmez.',
        'db_cache' => 'Veri Tabanı Önbellekleme',
        'db_cache_info' => 'Veri tabanı sorgularında çalıştırılan sorguların daha hızlı yanıt vermesini sağlayarak performansı artırır.',
        'route_cache' => 'Rota Önbellekleme',
        'route_cache_info' => 'Gelen isteklere göre işlem gerçekleştiren rota mekanizmasında önbellekleme yaparak tekrar tekrar aynı kontrollerin yapılmasının önüne geçer.',
        'maintenance_mode' => 'Bakım Modu',
        'maintenance_mode_info' => 'Siteyi ziyaretlere kapatmanızı sağlar. Oturumu açık olan yöneticiler hariç herkes bu ekranla karşılanır.',
        'maintenance_mode_desc' => 'Bakım Modu Mesajı',
        'maintenance_mode_desc_info' => 'Bakım modu karşılama sayfasında ziyaretçilere gösterilecek mesajdır.',
    ],
    'slugs' => [
        'contact' => 'iletisim',
    ],
    'base' => [
        'sandbox' => 'Geliştirici Araçları',
        'sandbox_message' => 'Geliştirme sürecinde size yardımcı olacak tüm araçlara bu ekrandan ulaşabilirsiniz.',
        'clear_storage' => 'Klasörleri Temizle',
        'clear_storage_message' => 'Depolama klasörü içindeki dosyaları silmenizi sağlar.',
        'session' => 'Oturum',
        'session_message' => 'Oturum içindeki verileri gösterir.',
        'php_info' => 'PHP Bilgileri',
        'php_info_message' => 'Sunucu PHP bilgilerini gösterir.',
        'db_init' => 'Veri Tabanını Hazırla',
        'db_init_message' => 'Şemaya göre veri tabanı tablolarını hazırlar.',
        'db_init_success' => 'Veri tabanı başarıyla hazırlandı.',
        'db_init_problem' => 'Veritabanı hazırlanırken bir sorun oluştu. -> [ERROR]',
        'db_seed' => 'Veri Tabanını Doldur',
        'db_seed_message' => 'Şema içeriğinde verileri tablolara ekler.',
        'column' => 'Sütun',
        'data' => 'Veri',
        'table' => 'Tablo',
        'type' => 'Tip',
        'auto_inc' => 'Otomatik Artan',
        'attribute' => 'Özellik',
        'default' => 'Varsayılan',
        'index' => 'İndis',
        'yes' => 'evet',
        'no' => 'yes',
        'charset' => 'Karakter Seti',
        'collate' => 'Karşılaştırma Seti',
        'engine' => 'Motor',
        'db_name' => 'Veri Tabanı İsmi',
        'db_charset' => 'Veri Tabanı Karakter Seti',
        'db_collate' => 'Veri Tabanı Karşılaştırma Seti',
        'db_engine' => 'Veri Tabanı Motoru',
        'db_init_alert' => '[DB_NAME] adında bir veri tabanı yoksa, [COLLATION] karşılaştırma seti ayarıyla ekleyin.',
        'db_init_start' => 'Harika, Hazırla!',
        'db_seed_success' => 'Veri tabanı başarıyla içe aktarıldı.',
        'db_seed_problem' => 'Veritabanı içe aktarılırken bir sorun oluştu. -> [ERROR]',
        'db_seed_start' => 'Harika, İçe Aktar!',
        'clear_storage_success' => 'Depolama klasörü temizlendi.',
        'folder' => 'Klasör',
        'delete' => 'Sil',
        'folder_not_found' => 'Klasör bulunamadı!',
        'change_language' => 'Dili Değiştir',
        'seeding' => 'İçe aktarılıyor...',
        'go_to_home' => 'Ana Sayfaya Dön',
        'home' => 'Ana Sayfa',
        'welcome' => 'Hoş geldiniz!',
        'welcome_message' => 'KalipsoNext\'in başlangıç sayfasıdır.',
        'login' => 'Giriş Yap',
        'login_message' => 'Örnek giriş sayfasıdır.',
        'register' => 'Kayıt Ol',
        'register_message' => 'Örnek kayıt sayfasıdır.',
        'logout' => 'Çıkış Yap',
        'account' => 'Hesap',
        'account_message' => 'Örnek hesap sayfasıdır.',
        'email_or_username' => 'Eposta ya da Kullanıcı Adı',
        'password' => 'Şifre',
        'recovery_account' => 'Hesabımı Kurtar',
        'recovery_account_message' => 'Bu sayfadan eposta adresinizi girerek şifre sıfırlama bağlantısı alabilirsiniz.',
        'email' => 'Eposta Adresi',
        'username' => 'Kullanıcı Adı', 
        'name' => 'Ad',
        'surname' => 'Soyad',
        'form_cannot_empty' => 'Form boş olamaz!',
        'email_is_already_used' => 'Eposta adresi zaten kullanılıyor.',
        'username_is_already_used' => 'Kullanıcı adı zaten kullanılıyor.',
        'registration_problem' => 'Kayıt esnasında bir sorun oluştu.',
        'registration_successful' => 'Kayıt başarılı!',
        'verify_email' => 'Eposta Adresini Doğrula',
        'verify_email_not_found' => 'Eposta doğrulama bağlantısı geçersiz!',
        'verify_email_problem' => 'Eposta doğrulaması yapılırken bir sorun oluştu!',
        'verify_email_success' => 'Eposta doğrulama başarılı.',
        'your_account_has_been_blocked' => 'Hesabınız silinmiş, lütfen iletişime geçin.',
        'account_not_found' => 'Hesap bulunamadı!',
        'your_login_info_incorrect' => 'Giriş bilgileriniz hatalı!',
        'welcome_back' => 'Tekrar hoş geldiniz!',
        'login_problem' => 'Oturum başlatılırken bir sorun oluştu.',
        'profile' => 'Profil',
        'profile_message' => 'Profilinizi bu sayfadan düzenleyebilirsiniz.',
        'sessions' => 'Oturumlar',
        'sessions_message' => 'Aktif oturumları bu sayfadan görüntüleyebilirsiniz.',
        'device' => 'Cihaz',
        'ip' => 'IP',
        'last_action_point' => 'Son İşlem Noktası',
        'last_action_date' => 'Son İşlem Tarihi',
        'action' => 'İşlem',
        'terminate' => 'Sonlandır',
        'session_terminated' => 'Oturum sonlandırıldı.',
        'session_not_terminated' => 'Oturum sonlandırılamadı!',
        'signed_out' => 'Çıkış yapıldı.',
        'login_information_updated' => 'Your login information has been updated.',
        'birth_date' => 'Doğum Tarihi',
        'update' => 'Güncelle',
        'save_problem' => 'Kaydedilirken bir sorun oluştu.',
        'save_success' => 'Başarıyla kaydedildi.',
        'recovery_request_successful' => 'Hesap kurtarma bağlantısını gönderdik, eposta kutunuzu kontrol etmeyi unutmayın.',
        'recovery_request_problem' => 'Hesap kurtarma bağlantısını gönderirken bir sorun oluştu.',
        'new_password' => 'Yeni Şifre',
        'change_password' => 'Şifreyi Değiştir',
        'account_recovered' => 'Hesap kurtarıldı, yeni şifrenizle giriş yapabilirsiniz.',
        'account_not_recovered' => 'Hesap kurtarılırken bir sorun oluştu.',
        'account_not_verified' => 'Hesap doğrulaması yapılmamış.',
        'management' => 'Yönetim',
        'toggle_navigation' => 'Navigasyonu Aç',
        'dashboard' => 'Kontrol Paneli',
        'dashboard_message' => 'Kontrol paneli neler olup bittiğini özet olarak görmenin en kısa yoludur.',
        'users' => 'Kullanıcılar',
        'users_message' => 'Kullanıcıları yönetebileceğiniz sayfadır.',
        'user_roles' => 'Kullanıcı Rolleri',
        'user_roles_message' => 'Kullanıcı rollerini yönetebileceğiniz sayfadır.',
        'logs' => 'Kayıtlar',
        'logs_message' => 'Tüm işlem kayıtlarını inceleyebileceğiniz sayfadır.',
        'settings' => 'Ayarlar',
        'settings_message' => 'Tüm ayarları bu ekrandan güncelleyebilirsiniz.',
        'view' => 'Görüntüle',
        'status' => 'Durum',
        'all' => 'Tümü',
        'active' => 'Aktif',
        'passive' => 'Pasif',
        'deleted' => 'Silinmiş',
        'role' => 'Rol',
        'created_at' => 'Eklenme',
        'updated_at' => 'Güncellenme',
        'edit' => 'Düzenle',
        'routes' => 'Rotalar',
        'add_new' => 'Yeni Ekle',
        'close' => 'Kapat',
        'add' => 'Ekle',
        'user_role_successfully_added' => 'Kullanıcı rolü başarıyla eklendi.',
        'user_role_add_problem' => 'Kullanıcı rolü eklenirken bir sorun oluştu.',
        'user_role_successfully_deleted' => 'Kullanıcı rolü başarıyla silindi.',
        'user_role_delete_problem' => 'Kullanıcı rolü silinirken bir sorun oluştu.',
        'user_role_successfully_updated' => 'Kullanıcı rolü başarıyla güncellendi.',
        'user_role_update_problem' => 'Kullanıcı rolü güncellenirken bir sorun oluştu.',
        'same_name_alert' => 'Zaten aynı adı taşıyan başka bir kayıt var.',
        'loading' => 'Yükleniyor...',
        'are_you_sure' => 'Emin misiniz?',
        'record_not_found' => 'Kayıt bulunamadı!',
        'delete_role' => 'Rolü Sil',
        'role_to_transfer_users' => 'Kullanıcıların Transfer Edileceği Rol',
        'user_role_delete_required_transfer' => 'Bu rolü silebilmek için ilgili üyeleri transfer etmelisiniz!',
        'role_to_delete' => 'Silinecek Rol',
        'affected_user_count' => 'Etkilenecek Kullanıcı Sayısı',
        'user_role_transfer_problem' => 'Kullanıcılar yeni role transfer edilirken sorun oluştu!',
        'no_change' => 'Değişiklik yok!',
        'copyright' => 'Telif Hakkı',
        'all_rights_reserved' => 'Tüm hakkı saklıdır.',
        'language' => 'Dil',
        'user_successfully_added' => 'Kullanıcı başarıyla eklendi.',
        'user_add_problem' => 'Kullanıcı eklenirken bir sorun oluştu.',
        'user_successfully_deleted' => 'Kullanıcı başarıyla silindi.',
        'user_delete_problem' => 'Kullanıcı silinirken bir sorun oluştu.',
        'user_successfully_updated' => 'Kullanıcı başarıyla güncellendi.',
        'user_update_problem' => 'Kullanıcı güncellenirken bir sorun oluştu.',
        'user_delete_problem_for_own_account' => 'Kendi hesabınızı silemezsiniz!',
        'middleware' => 'İlk Katman',
        'controller' => 'İkinci Katman',
        'request' => 'İstek',
        'endpoint' => 'Hedef',
        'user' => 'Kullanıcı',
        'execute_time' => 'Yanıt Süresi',
        'block_ip' => 'IP Engelle',
        'remove_ip_block' => 'IP Engeli Kaldır',
        'ip_block_list_not_updated' => 'IP blok listesi güncellenemedi!',
        'ip_block_list_updated' => 'IP blok listesi güncellendi.',
        'auth_code' => 'Oturum Kodu',
        'ssl' => 'SSL',
        'tls' => 'TLS',
        'server' => 'Sunucu',
        'smtp' => 'SMTP',
        'settings_not_updated' => 'Ayarlar güncellenemedi!',
        'settings_updated' => 'Ayarlar güncellendi.',
        'maintenance_mode' => 'Bakım Modu',
        'maintenance_mode_desc' => 'Maalesef bakımda olduğumuz için şu an hizmet veremiyoruz, daha sonra tekrar deneyebilirsiniz.',
        'system' => 'Sistem',
        'contents' => 'İçerikler',
        'content' => 'İçerik',
        'contents_message' => 'İçerikler, içerik yapılarınıza göre yeniden modelleyebileceğiniz esnek yapılar sunar.',
        'services' => 'Hizmetler',
        'services_message' => 'Hizmetleri yönetebileceğiniz sayfadır.',
        'other_services' => 'Diğer Hizmetler',
        'other_services_message' => 'Diğer hizmetleri buradan ekleyebilirsiniz.',
        'countries' => 'Ülkeler',
        'countries_message' => 'Ülkeleri buradan düzenleyebilirsiniz.',
        'all' => 'Tümü',
        'description' => 'Açıklama',
        'title' => 'Başlık',
        'icon' => 'Simge',
        'slug' => 'Kısa Ad',
        'flag' => 'Bayrak',
        'image' => 'Görsel',
        'file_successfully_uploaded' => 'Dosya başarıyla yüklendi!',
        'file_upload_problem' => 'Dosya yüklenirken bir sorun oluştu!',
        'file_not_found' => 'Dosya bulunamadı!',
        'file_not_uploaded' => 'Dosya yüklenemedi!',
        'header_image' => 'Başlık Görseli',
        'content_successfully_added' => 'İçerik başarıyla eklendi.',
        'content_add_problem' => 'İçerik eklenirken bir sorun oluştu.',
        'modules' => 'Modüller',
        'content_successfully_updated' => 'İçerik başarıyla düzenlendi.',
        'content_update_problem' => 'İçerik düzenlenirken bir sorun oluştu.',
        'content_successfully_deleted' => 'İçerik başarıyla silindi.',
        'content_delete_problem' => 'İçerik silinirken bir sorun oluştu.',
        'service_list' => 'Servislerimizi bu sayfadan görüntüleyin.',
        'service_detail' => 'İlgili hizmeti görüntüleyin.',
        'contact' => 'İletişim',
        'contact_message' => 'İletişim sayfasından bize ulaşabilirsiniz.',
        'forms' => 'formlar',
        'information_request_form' => 'Bilgi Talep Formu',
    ],
    'app' => [
        
    ]
];