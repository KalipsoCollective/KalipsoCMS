<?php

/**
 *  KalipsoNext - Localization File
 *  Macedonian(mk)
 **/

return [
    'lang' => [
        'code' => 'mk',
        'iso_code' => 'mk_MK',
        'dir' => 'ltr',
        'timezone' => 'Europe/Skopje',
        'currency' => 'mkd',
        'plural_suffix' => 'лар',
    ],
    'langs' => [
        'tr' => 'турски',
        'en' => 'Англиски',
        'mk' => 'македонски',
        'ar' => 'арапски',
    ],
    'err' => 'Грешка',
    'error' => [
        'page_not_found' => 'Страната не е пронајдена!',
        'method_not_allowed' => 'Методот не е дозволен!',
        'controller_not_defined' => 'Контролорот не е дефиниран!',
        'unauthorized' => 'Вие не сте овластени.',
        'view_definition_not_found' => 'Контролорот не испрати параметар за преглед!',
        'csrf_token_mismatch' => 'Несовпаѓање на клучот CSRF.',
        'csrf_token_incorrect' => 'Клучот CSRF е неважечки.',
        'username_is_already_used' => 'Корисничкото име веќе се користи.',
        'notification_hook_file_not_found' => 'Датотеката со кука за известување не е пронајдена!',
        'a_problem_occurred' => 'Се појави проблем!',
        'endpoint_file_is_not_found' => 'Датотеката за крајна точка на авторитетот не е пронајдена!',
        'ip_blocked' => 'Вашата IP адреса е блокирана!',
        'module_not_found' => 'Модулот не е пронајден!',
        'missing_or_incorrect_parameter' => 'Недостасува или неточен параметар',
    ],
    'notification' => [
        'registration_email_title' => 'Вашата сметка е создадена!',
        'registration_email_body' => 'Здраво [USER], <br>Вашата сметка е создадена. Можете да ја потврдите вашата е-адреса со врската подолу. <br>[VERIFY_LINK]',
        'recovery_request_email_title' => 'Обнова на сметка',
        'recovery_request_email_body' => 'Здраво [USER], <br>Го добивме вашето барање за враќање на сметката. Можете да ја поставите вашата нова лозинка со врската подолу. <br>[RECOVERY_LINK]',
        'account_recovered_email_title' => 'Вашата сметка е обновена!',
        'account_recovered_email_body' => 'Здраво [USER], <br>Вашата сметка е обновена. Ако не сте го направиле ова, ве молиме контактирајте не.',
        'email_change_email_title' => 'Вашата адреса за е-пошта е ажурирана!',
        'email_change_email_body' => 'Здраво [USER], <br>Вашата адреса на е-пошта е ажурирана. Можете да потврдите со врската подолу. <br>[VERIFY_LINK] <br>[CHANGES]',
    ],
    'auth' => [
        'auth' => 'Профил',
        'auth_action' => 'Профил - Подстраници',
        'auth_logout' => 'Одјавување',
        'management' => 'Управување',
        'management_users' => 'Управување - Корисници',
        'management_users_list' => 'Управување - Корисници - Список',
        'management_users_add' => 'Управување - Корисници - Додај',
        'management_users_detail' => 'Управување - Корисници - Детали',
        'management_users_update' => 'Управување - Корисници - Уреди',
        'management_users_delete' => 'Управување - Корисници - Избриши',
        'management_roles' => 'Менаџмент - улоги',
        'management_roles_list' => 'Менаџмент - улоги - Листа',
        'management_roles_add' => 'Менаџмент - улоги - Додај',
        'management_roles_detail' => 'Менаџмент - улоги - Детали',
        'management_roles_update' => 'Менаџмент - улоги - Уредување',
        'management_roles_delete' => 'Менаџмент - улоги - Избриши',
        'management_sessions' => 'Менаџмент - сесии',
        'management_sessions_list' => 'Управување - Сесии - Список',
        'management_sessions_delete' => 'Управување - Сесии - Избриши',
        'management_logs' => 'Управување - Дневници',
        'management_logs_list' => 'Управување - Дневници - Список',
        'management_logs_ip_block' => 'Управување - Дневници - Блок на IP',
        'management_settings' => 'Управување - Поставки',
        'management_settings_update' => 'Управување - Поставки - Уреди',
        'management_icon_picker' => 'Управување - Избирач на икони',
        'management_contents' => 'Управување - Содржини',
        'management_contents_list' => 'Управување - Содржини - Список',
        'management_contents_add' => 'Управување - Содржини - Додај',
        'management_contents_detail' => 'Управување - Содржина - Детали',
        'management_contents_update' => 'Управување - Содржини - Уреди',
        'management_contents_delete' => 'Управување - Содржини - Избриши',
        'management_contents_slug' => 'Управување - Содржина - Прашање за голтка',
        'management_contents_autocomplete' => 'Управување - Содржина - Автоматско комплетно пребарување',
        'management_content_upload_file' => 'Управување - Содржини - Уредувач Поставување датотека',
        'management_forms' => 'Управување - Формулари',
        'management_forms_list' => 'Управување - Формулари - Список',
        'management_forms_detail' => 'Управување - Формулари - Детал',
        'management_forms_update' => 'Управување - Формулари - Ажурирање',
        'management_forms_delete' => 'Управување - Формулари - Избриши',
        'management_media' =>  'Менаџмент - медиуми',
        'management_media_list' =>  'Менаџмент - медиуми - Список',
        'management_media_add' =>  'Менаџмент - медиуми - Додадете',
        'management_media_detail' =>  'Менаџмент - медиуми - Детал',
        'management_media_update' =>  'Менаџмент - медиуми - Уредување',
        'management_media_delete' =>  'Менаџмент - медиуми - Избриши',
        'management_menu' =>  'Управување - Менија',
        'management_menu_list' =>  'Управување - Менија - List',
        'management_menu_add' =>  'Управување - Менија - Додадете',
        'management_menu_detail' =>  'Управување - Менија - Детал',
        'management_menu_update' =>  'Управување - Менија - Уредување',
        'management_menu_delete' =>  'Управување - Менија - Избриши',
        'management_menu_get_params' => 'Управување - Менија - Добијте детали за менито',
    ],
    'settings' => [
        'byte' => 'Byte',
        'basic_settings' => 'Основни поставки',
        'secure_settings' => 'Поставки за безбедност',
        'email_settings' => 'Поставки за е-пошта',
        'optimization_settings' => 'Поставки за оптимизација',
        'name' => 'Име на страницата',
        'name_info' => 'Оваа вредност се појавува во заглавијата на страниците и мета.',
        'description' => 'Опис на локацијата',
        'description_info' => 'Оваа вредност се појавува во заглавијата на страницата, се заснова на тоа кога нема мета опис.',
        'contact_email' => 'Контакт е-пошта',
        'contact_email_info' => 'Се користи на страната на интерфејсот, оваа информација се прикажува како испраќач при испраќање е-пошта.',
        'separator' => 'Сепаратор',
        'separator_info' => 'Го претставува знакот вметнат во насловот на страницата кога името на страницата се прикажува по соодветниот наслов на страницата.',
        'language' => 'Стандарден јазик',
        'language_info' => 'Стандардна дефиниција на јазикот врз основа на првичните посети.',
        'default_user_role' => 'Стандардна корисничка улога',
        'default_user_role_info' => 'Оваа вредност се користи кога се доделува корисничка улога на нови записи.',
        'ssl' => 'SSL режим',
        'ssl_info' => 'Ако има инсталирано SSL сертификат на веб-страницата, оваа поставка е овозможена и адресите се направени соодветно. <strong class="text-danger">Ако не знаете, не менувајте!</strong>',
        'log' => 'Запис за евиденција',
        'log_info' => 'Кога е активен, ги евидентира сите трансакции. Се препорачува да се чува затворен на многу посетени локации. Во исклучена состојба ги евидентира само неуспешните пристапи.',
        'mail_send_type' => 'Тип на испраќање на е-пошта',
        'mail_send_type_info' => 'Ако функцијата mail() е активна на серверот, може да се испрати директна е-пошта со поставката за серверот. Испраќањето SMTP обезбедува бавен, но безбеден пренос.',
        'smtp_address' => 'SMTP адреса',
        'smtp_address_info' => 'Во SMTP испраќањето, тоа се однесува на адресата на серверот SMTP.',
        'smtp_port' => 'SMTP порта',
        'smtp_port_info' => 'Го означува бројот на портата што ќе се користи за поврзување со серверот SMTP при SMTP испраќање.',
        'smtp_email_address' => 'SMTP адреса за е-пошта',
        'smtp_email_address_info' => 'Во SMTP испраќањето, тоа се однесува на адресата на е-пошта на сметката на која ќе биде обезбеден преносот.',
        'smtp_email_pass' => 'Лозинка за е-пошта SMTP',
        'smtp_email_pass_info' => 'Во SMTP испраќањето, тоа се однесува на лозинката на сметката на која ќе се обезбеди преносот.',
        'smtp_secure' => 'SMTP безбедност',
        'smtp_secure_info' => 'Го претставува протоколот што ќе се користи при поврзување со серверот SMTP при SMTP испраќање.',
        'mail_queue' => 'Ред за е-пошта',
        'mail_queue_info' => 'Како и кај SMTP испраќањето, кога има бавно испраќање на е-пошта, го поставува испраќањето во редици за да ги спречи корисниците да чекаат. Cron работните места мора да бидат активни. Во спротивно нема да се врши достава.',
        'view_cache' => 'Прикажи кеширање',
        'view_cache_info' => 'Може да се користи за намалување на времето на одговор на сајтови со многу статични страници, не се препорачува во проекти со тешки динамични страници.',
        'db_cache' => 'Кеширање на бази на податоци',
        'db_cache_info' => 'Ги подобрува перформансите со тоа што прашањата се извршуваат на барањата на базата на податоци побрзо да одговараат.',
        'route_cache' => 'Кеширање на маршрутата',
        'route_cache_info' => 'Кеширањето во механизмот за маршрута, кој ги извршува операциите според дојдовните барања, спречува исти проверки да се прават одново и одново.',
        'maintenance_mode' => 'Режим на одржување',
        'maintenance_mode_info' => 'Тоа ви овозможува да ја затворите страницата за посети. Сите се поздравени со овој екран, освен најавените администратори.',
        'maintenance_mode_desc' => 'Порака за режим на одржување',
        'maintenance_mode_desc_info' => 'Тоа е пораката што ќе им се прикаже на посетителите на страницата за добредојде на режимот на одржување.',
        'map_embed_url' => 'Код за вградување на карта',
        'map_embed_url_info' => 'Кодот за вградување на картата мора да ја има содржината src внатре.',
        'map_url' => 'Врска со карта',
        'map_url_info' => 'Тоа е врската со мапата што ќе се отвори кога ќе кликнете на страницата за контакт.',
        'clarification_text' => 'Текстуална страница за појаснување',
        'clarification_text_info' => 'Ова е страницата каде што се наоѓа полето за избор за појаснување на украсот во формулари.',
        'address' => 'Адреса',
        'address_info' => 'Тоа е делот за адреса што ќе се појави во деловите за контакт.',
        'phone' => 'Телефон',
        'phone_info' => 'Тоа е делот за телефон што ќе се појави во деловите за контакти.',
        'facebook' => 'URL на Фејсбук',
        'facebook_info' => 'Тоа е адресата на Фејсбук што ќе се појави во деловите за контакти.',
        'twitter' => 'URL на Твитер',
        'twitter_info' => 'Тоа е адресата на Твитер што ќе се појави во делот за контакт.',
        'linkedin' => 'URL на LinkedIn',
        'linkedin_info' => 'Тоа е адресата на LinkedIn што ќе се појави во деловите за контакти.',
        'instagram' => 'URL на Инстаграм',
        'instagram_info' => 'Тоа е адресата на Инстаграм што ќе се појави во делот за контакт.',
    ],
    'base' => [
        'sandbox' => 'Песочник',
        'sandbox_message' => 'Можете да пристапите до сите алатки кои ќе ви помогнат во процесот на развој од овој екран.',
        'clear_storage' => 'Исчистете го складиштето',
        'clear_storage_message' => 'Ви овозможува да бришете датотеки во папката за складирање.',
        'session' => 'Сесија',
        'session_message' => 'Ги прикажува податоците во рамките на сесијата.',
        'php_info' => 'Информации за PHP',
        'php_info_message' => 'Покажува информации за PHP на серверот.',
        'db_init' => 'Подгответе ДБ',
        'db_init_message' => 'Подготвува табели со бази на податоци според шемата.',
        'db_init_success' => 'Базата на податоци е успешно подготвена.',
        'db_init_problem' => 'Имаше проблем при подготовката на базата на податоци. -> [ERROR]',
        'db_seed' => 'Семе ДБ',
        'db_seed_message' => 'Вметнува податоци во табели во рамките на шемата.',
        'column' => 'Колона',
        'table' => 'Табела',
        'data' => 'Податоци',
        'type' => 'Тип',
        'auto_inc' => 'Автоматско зголемување',
        'attribute' => 'Атрибут',
        'default' => 'Стандардно',
        'index' => 'Индекс',
        'yes' => 'да',
        'no' => 'бр',
        'charset' => 'Збир на шари',
        'collate' => 'Состави',
        'engine' => 'Мотор',
        'db_name' => 'Име на база на податоци',
        'db_charset' => 'Збир на бази на податоци',
        'db_collate' => 'Собира база на податоци',
        'db_engine' => 'Мотор на база на податоци',
        'db_init_alert' => 'Ако не постои база на податоци со име [DB_NAME], додајте ја со споредувањето [COLLATION].',
        'db_init_start' => 'Добро, подгответе се!',
        'db_seed_success' => 'Базата на податоци е успешно поставена.',
        'db_seed_problem' => 'Настана проблем при поставувањето на базата на податоци. -> [ERROR]',
        'db_seed_start' => 'Добро, Семе!',
        'clear_storage_success' => 'Папката за складирање е исчистена.',
        'folder' => 'Папка',
        'delete' => 'Избриши',
        'folder_not_found' => 'Папката не е пронајдена!',
        'change_language' => 'Промени го јазикот',
        'seeding' => 'Сеење...',
        'go_to_home' => 'Оди дома',
        'home' => 'Дома',
        'welcome' => 'Добредојдовте!',
        'welcome_message' => 'Тоа е почетната страница на KalipsoNext.', 
        'login' => 'Логирај Се',
        'login_message' => 'Тоа е примерок за најавување страница.',
        'register' => 'Регистрирајте се',
        'register_message' => 'Тоа е страницата за регистрација на примероци.',
        'logout' => 'Одјавување',
        'account' => 'Сметка',
        'account_message' => 'Тоа е примерок за најавување страница.',
        'email_or_username' => 'Е-пошта или корисничко име',
        'password' => 'Лозинка',
        'recovery_account' => 'Сметка за обновување',
        'recovery_account_message' => 'Од оваа страница, можете да добиете врска за ресетирање лозинка со внесување на вашата e-mail адреса.',
        'email' => 'И-мејл адреса',
        'username' => 'Корисничко име', 
        'name' => 'Име',
        'surname' => 'Презиме',
        'form_cannot_empty' => 'Формуларот не може да биде празен!',
        'email_is_already_used' => 'Адресата на е-пошта веќе се користи.',
        'username_is_already_used' => 'Корисничкото име е веќе во употреба.',
        'registration_problem' => 'Имаше проблем при регистрацијата.',
        'registration_successful' => 'Регистрацијата е успешна!',
        'verify_email' => 'потврди ја Емаил адресата',
        'verify_email_not_found' => 'Врската за потврда на е-пошта е неважечка!',
        'verify_email_problem' => 'Имаше проблем при потврдувањето на е-поштата!',
        'verify_email_success' => 'Потврдата на е-пошта е успешна.',
        'your_account_has_been_blocked' => 'Вашата сметка е избришана, ве молиме контактирајте со нас.',
        'account_not_found' => 'Сметката не е пронајдена!',
        'your_login_info_incorrect' => 'Вашите информации за најавување се неточни!',
        'welcome_back' => 'Добредојде назад!',
        'login_problem' => 'Имаше проблем со започнување на сесијата.',
        'profile' => 'Профил',
        'profile_message' => 'Можете да го уредувате вашиот профил од оваа страница.',
        'sessions' => 'Сесии',
        'sessions_message' => 'Можете да гледате активни сесии од оваа страница.',
        'device' => 'Уред',
        'ip' => 'IP',
        'last_action_point' => 'Последна акциона точка',
        'last_action_date' => 'Датум на последно дејство',
        'action' => 'Акција',
        'terminate' => 'Прекини',
        'session_terminated' => 'Сесијата е прекината.',
        'session_not_terminated' => 'Седницата не можеше да се прекине!',
        'signed_out' => 'Одјавен.',
        'login_information_updated' => 'Вашите информации за најавување се ажурирани.',
        'birth_date' => 'Датум на раѓање',
        'update' => 'Ажурирање',
        'save_problem' => 'Имаше проблем со зачувувањето.',
        'save_success' => 'Успешно зачувано.',
        'recovery_request_successful' => 'Ви ја испративме врската за враќање на сметката, не заборавајте да ја проверите вашата е-пошта.',
        'recovery_request_problem' => 'Имаше проблем при испраќањето на врската за враќање на сметката.',
        'new_password' => 'нова лозинка',
        'change_password' => 'Промени го пасвордот',
        'account_recovered' => 'Сметката е обновена, можете да се најавите со вашата нова лозинка.',
        'account_not_recovered' => 'Имаше проблем со враќањето на сметката.',
        'account_not_verified' => 'Потврдата на сметката не е завршена.',
        'management' => 'Управување',
        'toggle_navigation' => 'Вклучи навигација',
        'dashboard' => 'Контролна табла',
        'dashboard_message' => 'Контролната табла е најкраткиот начин да се види резиме на она што се случува.',
        'users' => 'Корисници',
        'users_message' => 'Ова е страницата каде што можете да управувате со корисници.',
        'user_roles' => 'Улоги на корисници',
        'user_roles_message' => 'Ова е страницата каде што можете да управувате со корисничките улоги.',
        'logs' => 'Дневници',
        'logs_message' => 'Ова е страницата каде што можете да ги прегледате сите записи за дневници.',
        'settings' => 'Поставки',
        'settings_message' => 'Можете да ги ажурирате сите поставки од овој екран.',
        'view' => 'Погледни',
        'status' => 'Статус',
        'all' => 'Сите',
        'active' => 'Активен',
        'passive' => 'Пасивно',
        'deleted' => 'Избришано',
        'role' => 'Улога',
        'created_at' => 'Создаден',
        'updated_at' => 'Ажурирано',
        'edit' => 'Уредување',
        'routes' => 'Рути',
        'add_new' => 'Додади ново',
        'close' => 'Затвори',
        'add' => 'Додадете',
        'user_role_successfully_added' => 'Улогата на корисникот е успешно додадена.',
        'user_role_add_problem' => 'Имаше проблем со додавање на корисничката улога.',
        'user_role_successfully_deleted' => 'Улогата на корисникот е успешно избришана.',
        'user_role_delete_problem' => 'Имаше проблем со бришење на корисничката улога.',
        'user_role_successfully_updated' => 'Улогата на корисникот е успешно ажурирана.',
        'user_role_update_problem' => 'Имаше проблем при ажурирањето на корисничката улога.',
        'same_name_alert' => 'Веќе има уште една плоча со истото име.',
        'loading' => 'Се вчитува...',
        'are_you_sure' => 'Дали си сигурен?',
        'record_not_found' => 'Записот не е пронајден!',
        'delete_role' => 'Избриши улога',
        'role_to_transfer_users' => 'Улога за пренос на корисници',
        'user_role_delete_required_transfer' => 'За да можете да ја избришете оваа улога, мора да ги префрлите соодветните членови!',
        'role_to_delete' => 'Улога за бришење',
        'affected_user_count' => 'Број на корисници кои ќе бидат засегнати',
        'user_role_transfer_problem' => 'Се појави проблем при префрлањето на корисниците на нова улога!',
        'no_change' => 'Нема промена!',
        'copyright' => 'Авторски права',
        'all_rights_reserved' => 'Сите права се задржани.',
        'language' => 'Јазик',
        'user_successfully_added' => 'Корисникот е успешно додаден.',
        'user_add_problem' => 'Имаше проблем со додавање на корисникот.',
        'user_successfully_deleted' => 'Корисникот е успешно избришан.',
        'user_delete_problem' => 'Имаше проблем со бришењето на корисникот.',
        'user_successfully_updated' => 'Корисникот е успешно ажуриран.',
        'user_update_problem' => 'Имаше проблем при ажурирањето на корисникот.',
        'user_delete_problem_for_own_account' => 'Не можете да ја избришете вашата сопствена сметка!',
        'middleware' => 'Middleware',
        'controller' => 'Управувач',
        'request' => 'Барање',
        'endpoint' => 'Крајна точка',
        'user' => 'Корисник',
        'execute_time' => 'Време на извршување',
        'block_ip' => 'Блокирај IP адреса',
        'remove_ip_block' => 'Отстранете го блокот IP',
        'ip_block_list_not_updated' => 'Не успеа да се ажурира списокот со IP блокови!',
        'ip_block_list_updated' => 'Ажурирана е листата на IP блокови.',
        'auth_code' => 'Код за авторизација',
        'ssl' => 'SSL',
        'tls' => 'TLS',
        'server' => 'Сервер',
        'smtp' => 'SMTP',
        'settings_not_updated' => 'Не успеа да се ажурираат поставките!',
        'settings_updated' => 'Поставките се ажурирани.',
        'maintenance_mode' => 'Режим на одржување',
        'maintenance_mode_desc' => 'За жал, во моментов сме под одржување, па не можеме да обезбедиме услуга, може да се обидете повторно подоцна.',
        'system' => 'Систем',
        'contents' => 'Содржини',
        'content' => 'содржина',
        'contents_message' => 'Содржините се флексибилни структури кои можете да ги реконструирате врз основа на структурите на вашата содржина.',
        'services' => 'Услуги',
        'services_message' => 'Ова е страницата каде што можете да управувате со услугите.',
        'other_services' => 'Други услуги',
        'other_services_message' => 'Овде можете да управувате со други услуги.',
        'countries' => 'Земји',
        'countries_message' => 'Овде можете да ги уредувате земјите.',
        'all' => 'Сите',
        'description' => 'Опис',
        'title' => 'Наслов',
        'icon' => 'Икона',
        'slug' => 'Слаг',
        'flag' => 'Знаме',
        'image' => 'Слика',
        'file_successfully_uploaded' => 'Датотеката е успешно поставена!',
        'file_upload_problem' => 'Имаше проблем при поставувањето на датотеката!',
        'file_not_found' => 'Документот не е пронајден!',
        'file_not_uploaded' => 'Датотеката не можеше да се вчита!',
        'header_image' => 'Заглавие слика',
        'content_successfully_added' => 'Содржината е успешно додадена.',
        'content_add_problem' => 'Имаше проблем со додавање содржина.',
        'modules' => 'Модули',
        'content_successfully_updated' => 'Содржината е успешно уредена.',
        'content_update_problem' => 'Имаше проблем при уредувањето на содржината.',
        'content_successfully_deleted' => 'Содржината е успешно избришана.',
        'content_delete_problem' => 'Имаше проблем при бришењето на содржината.',
        'service_list' => 'Погледнете ги нашите услуги на оваа страница.',
        'service_detail' => 'Погледнете ја соодветната услуга.',
        'contact' => 'Контакт',
        'contact_message' => 'Можете да најдете формулари за контакт на оваа страница.',
        'forms' => 'Форми',
        'information_request_form' => 'Формулар за барање информации',
        'gallery' => 'Галерија',
        'gallery_message' => 'Ова е страницата каде што можете да управувате со галериите.',
        'images' => 'Слики',
        'blog' => 'Блог',
        'blog_message' => 'Ова е страницата каде што можете да управувате со објавите на блогот.',
        'blog_list' => 'Можете да ги најдете сите статии на оваа страница.',
        'blog_detail' => 'Погледнете ја поврзаната статија.',
        'categories' => 'Категории',
        'categories_message' => 'Ова е страницата каде што можете да управувате со категории.',
        'categories_detail' => 'Погледнете ја објавата за категоријата.',
        'color' => 'Боја',
        'category' => 'Категорија',
        'pages' => 'Страници',
        'pages_message' => 'Тоа е страницата каде што можете да управувате со страниците.',
        'page_detail' => 'Погледнете ја соодветната страница.',
        'media' => 'Медиуми',
        'media_message' => 'Можете да управувате со медиумските содржини овде.',
        'module' => 'Модул',
        'preview' => 'Преглед',
        'extension' => 'Продолжување',
        'size' => 'Големина',
        'general' => 'Општо',
        'file_successfully_deleted' => 'Датотеката е успешно избришана.',
        'file_delete_problem' => 'Имаше проблем со бришење на датотеката.',
        'menus' => 'Менија',
        'menus_message' => 'Ова е страницата каде што можете да управувате со менијата.',
        'other' => 'Друго',
        'direct_link' => 'Директна врска',
        'parameter' => 'Параметар',
        'key' => 'Клуч',
        'basic' => 'Основни',
        'list' => 'Список',
        'list_as_dropdown' => 'Листа како паѓачко',
        'key_is_already_used' => 'Клучот веќе се користи.',
        'menu_successfully_added' => 'Менито е успешно додадено.',
        'menu_add_problem' => 'Имаше проблем со додавање на менито.',
        'menu_successfully_deleted' => 'Менито е успешно избришано.',
        'menu_delete_problem' => 'Имаше проблем со бришење на менито.',
        'menu_successfully_updated' => 'Менито е успешно ажурирано.',
        'menu_update_problem' => 'Имаше проблем при ажурирањето на менито.',
        'menu_integrity_problem' => 'Се чини дека има проблем со интегритетот на податоците од менито, треба да проверите за да бидете сигурни дека нема полиња што недостасуваат.',
        'sliders' => 'Лизгачи',
        'sliders_message' => 'Можете да управувате со содржината на лизгачот овде.',
        'link' => 'Врска',
        'order' => 'Со цел',
        'links' => 'Врски',
        'phone' => 'Телефон',
        'subject' => 'Предмет',
        'message' => 'Порака',
        'contact_detail' => 'Можете да контактирате со нас со пополнување на формуларот за контакт на оваа страница или со користење на нашите информации за контакт.',
        'first_name_last_name' => 'Име презиме',
        'telephone_number' => 'Телефонски број',
        'submit' => 'Поднесете',
        'form_successfully_added' => 'Формуларот е успешно поднесен.',
        'form_add_problem' => 'Имаше проблем при поднесувањето на формуларот.',
        'form_successfully_deleted' => 'Формуларот е успешно избришан.',
        'form_delete_problem' => 'Имаше проблем со бришење на формуларот.',
        'form_successfully_updated' => 'Формуларот е успешно ажуриран.',
        'form_update_problem' => 'Имаше проблем при ажурирањето на формуларот.',
        'detail' => 'Детал',
        'pending' => 'Во очекување',
        'in_action' => 'Во акција',
        'completed' => 'Завршено',
        'form_received' => 'Примен формулар',
        'information_request_form_message' => 'Ова е страницата каде што можете да управувате со формулари за барање информации.',
        'last_studied_program' => 'Последно студирано/дипломирано училиште и програма',
        'service' => 'Сервис',
        'country' => 'Земја',
        'note' => 'Забелешка',
        'related_service_to_be_informed' => 'Образовната служба за која сакате да бидете информирани',
        'interested_country' => 'Земја од интерес за образование',
        'remove' => 'Отстрани',
        'subhead' => 'Подглава',
        'home_contents' => 'Содржина на почетната страница',
        'home_contents_message' => 'Можете да управувате со содржината на почетната страница од оваа страница.',
        'target_blank' => 'Отворете во нова картичка',
        'move' => 'Премести',
        'our_services' => 'Нашите услуги',
        'explore_our_services' => 'Истражете ги нашите услуги',
        'header_images' => 'Заглавие слики',
        'header_images_message' => 'Можете да управувате со слики од заглавието од оваа страница.',
        'file' => 'Датотека',
        'year' => 'година',
        'group' => 'Група',
        'sub_title' => 'Поднаслов',
        'birth_year' => 'година на раѓање',
        'death_year' => 'Година на смртта',
        'filter' => 'Филтер',
        'filter_option' => 'Опција за филтер',
        'filter_options' => 'Опции за филтрирање',
        'scroll_to_top' => 'Скролувајте до врвот',
        'career_form' => 'Кариера',
        'career_form_message' => 'Овде можете да управувате со формулари за кариера.',
        'position' => 'Позиција',
        'subtitle' => 'Поднаслов',
        'about' => 'За',
        'home_images' => 'Домашни слики',
        'products_title' => 'Наслов на производи',
        'news_title' => 'Наслов на вести',
        'home_banner_image' => 'Слика на банерот на домот',
        'home_banner_text' => 'Текст за банер за почеток',
        'home_gallery' => 'Домашна галерија',
        'news' => 'Вести',
        'news_message' => 'Можете да управувате со вести на оваа страница.',
        'slide_content' => 'Содржина на слајд',
        'product_categories' => 'Категории на производи',
        'product_categories_message' => 'Можете да управувате со категориите на производи овде.',
        'products' => 'Производи',
        'products_message' => 'Можете да управувате со производи овде.',
        'bid_form' => 'Понуда',
        'bid_form_message' => 'Можете да управувате со формуларите за понуди овде.',
        'positions' => 'Позиции',
        'positions_message' => 'Можете да управувате со позиции овде.',
        'career' => 'Кариера',
        'career_gallery' => 'Галерија за кариера',
        'link_title' => "Наслов на врската",
        'banners' => 'Банери',
        'banners_message' => 'Тоа е екранот каде што можете да управувате со банери.',
        'our_products' => 'Нашите производи',
        'about_us' => 'За нас',
        'read_more' => 'Прочитај повеќе',
        'all_news' => 'Сите вести',
        'go_to_category' => 'Одете во Категорија',
        'product_detail' => 'Детали за производот',
        'contact_info' => 'Контакт Инфо',
        'contact_info_message' => 'Ова е страницата каде што можете да ги ажурирате информациите на страницата за контакт.',
        'address' => 'Адреса',
        'fax' => 'Факс',
        'map_link' => 'Врска со карта',
        'main' => 'Главна',
        'departments' => 'Одделенија',
        'customer_service' => 'Услуги на клиентите',
        'show_on_map' => 'Прикажи на карта',
        'write_us' => 'Пишете ни',
        'clarification_text_check' => 'Го прочитав и го прифатив <a href="[LINK]">Текстот за појаснување</a>.',
        'follow_us' => 'Следете нè',
        'product_list' => 'Можете да ги видите нашите производи, да ги испитате нивните детали и да креирате формулар за понуда.',
        'go_back' => 'Врати се назад',
        'get_offer' => 'Добијте понуда',
        'bid_form' => 'Формулар за понуда',
        'other_products' => 'Други производи',
        'about_message' => 'За нас е страницата каде што можете да управувате со содржината на страницата.',
        'header_contents' => 'Содржина на заглавието',
        'header_contents_message' => 'Тоа е областа каде што можете да управувате со содржината внесена на врвот на страниците на страниците под Корпоративното.',
        'corporate' => 'Корпоративни',
        'catalogue' => 'Каталог',
        'product_catalogue' => 'Каталог на производи',
        'about_side' => 'За - Страна',
        'vision' => 'Визија',
        'mission' => 'Мисија',
        'slogan' => 'Слоган',
        'widget_title' => 'Наслов на графичка контрола',
        'widget_content' => 'Содржина на графичка контрола',
        'our_vision' => 'Нашата визија',
        'our_mission' => 'Нашата мисија',
        'view_job_detail' => 'Погледнете ги деталите за работата',
        'close_the_window' => 'Затворете го прозорецот',
        'career_form_title' => 'Придружете се на нашето семејство',
        'career_form_desc' => 'Одредете ја поделбата на работата што сакате да бидете со нас.',
        'quality_policy' => 'Политика за квалитет',
        'quality_policy_message' => 'Ова е страницата каде што можете да управувате со содржината на политиката за квалитет.',
        'quality_policy_head' => 'Се грижиме за квалитетот!',
        'quality_policy_desc' => 'Нашите политики за квалитет можете да ги најдете на оваа страница.',
        'about_us_desc' => 'Можете да не запознаете од оваа страница.',
        'news_list_desc' => 'Вестите можете да ги прелистувате на оваа страница за да се информирате за нас.',
        'news_detail_desc' => 'Деталите за поврзаните вести можете да ги најдете на оваа страница.',
        'career_detail_desc' => 'Доколку сакате да ни се придружите, можете да го користите формуларот за кариера на оваа страница.',
        'certificates_desc' => 'Сертификатите што ги добивме можете да ги видите на оваа страница.',
        'fairs_desc' => 'Саемите на кои присуствувавме можете да ги погледнете на оваа страница.',
        'quality_policy_foot' => 'Ние придонесуваме за заштита на <span>природниот живот.</span>',
        'quality_certificates' => 'Нашите сертификати за квалитет',
        'certificates' => 'Сертификати',
        'certificates_message' => 'Можете да управувате со сертификатите од оваа страница.',
        'fairs' => 'Саеми',
        'fairs_message' => 'Ова е страницата каде што можете да управувате со саемските содржини.',
        'load_more' => 'Товари повеќе',
    ],
    'app' => [
    ]
];