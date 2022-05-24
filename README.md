# KalipsoCMS - KalipsoNext-based, open source, powerful and experimental content management system.

## Requirements
- Apache >= 2.4.5 or Nginx >= 1.8
- PHP >= 7.1
- MySQL >= 5.6

## Documentation
This documentation has been created to give you a quick start.

### Installation
- `composer install`
- Visit http://localhost/sandbox
- Prepare DB
- Seed DB
- Start Development

### Tricks

#### Server Configurations (Apache .htaccess)
```htaccess
<IfModule mod_rewrite.c>
    RewriteEngine On

    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    RewriteRule ^index\.php$ - [L]
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule . index.php [L]
</IfModule>
```

#### Server Configurations (Nginx nginx.conf)
```nginx_conf
location / {
	if (!-e $request_filename){
		rewrite ^(.+)$ /index.php/$1 break;
	}
}
```