<?php 

/**
 * @package KN
 * @author halillusion <halillusion@gmail.com>
 **/

declare(strict_types=1);

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/app/bootstrap.php';

try {

    $app = (new KN\Core\Factory);

    // Root-bound route group
    $app->routeGroup(['GET,POST', '/auth', 'UserController@account', ['Auth@with']], function () {
        return [
            ['GET,POST', '/login', 'UserController@login', ['Auth@withOut', 'CSRF@validate']],
            ['GET,POST', '/register', 'UserController@register', ['Auth@withOut', 'CSRF@validate']],
            ['GET,POST', '/recovery', 'UserController@recovery', ['Auth@withOut', 'CSRF@validate']],
            ['GET,POST', '/logout', 'UserController@logout', ['Auth@with']],
            ['GET,POST', '/:action', 'UserController@account', ['Auth@with', 'CSRF@validate']],
        ];
    });

    $app->routeGroup(['GET,POST', '/management', 'AdminController@dashboard', ['Auth@with']], function () {
        return [

            // Users
            ['GET,POST', '/users', 'AdminController@users', ['Auth@with']],
            ['GET,POST', '/users/list', 'AdminController@userList', ['Auth@with']],
            ['GET,POST', '/users/add', 'AdminController@userAdd', ['Auth@with']],
            ['GET,POST', '/users/:id', 'AdminController@userDetail', ['Auth@with']],
            ['GET,POST', '/users/:id/delete', 'AdminController@userDelete', ['Auth@with']],
            ['GET,POST', '/users/:id/update', 'AdminController@userUpdate', ['Auth@with']],

            // Roles
            ['GET,POST', '/roles', 'AdminController@roles', ['Auth@with']],
            ['GET,POST', '/roles/list', 'AdminController@roleList', ['Auth@with']],
            ['GET,POST', '/roles/add', 'AdminController@roleAdd', ['Auth@with']],
            ['GET,POST', '/roles/:id', 'AdminController@roleDetail', ['Auth@with']],
            ['GET,POST', '/roles/:id/delete', 'AdminController@roleDelete', ['Auth@with']],
            ['GET,POST', '/roles/:id/update', 'AdminController@roleUpdate', ['Auth@with']],

            // Files
            ['GET,POST', '/media', 'FileController@medias', ['Auth@with']],
            ['GET,POST', '/media/list', 'FileController@mediaList', ['Auth@with']],
            ['GET,POST', '/media/add', 'FileController@mediaAdd', ['Auth@with']],
            ['GET,POST', '/media/:id', 'FileController@mediaDetail', ['Auth@with']],
            ['GET,POST', '/media/:id/delete', 'FileController@mediaDelete', ['Auth@with']],
            ['GET,POST', '/media/:id/update', 'FileController@mediaUpdate', ['Auth@with']],

            // Sessions
            ['GET,POST', '/sessions', 'AdminController@sessions', ['Auth@with']],
            ['GET,POST', '/sessions/list', 'AdminController@sessionList', ['Auth@with']],
            ['GET,POST', '/sessions/:id/delete', 'AdminController@sessionDelete', ['Auth@with']],

            // Logs & Security
            ['GET,POST', '/logs', 'AdminController@logs', ['Auth@with']],
            ['GET,POST', '/logs/list', 'AdminController@logList', ['Auth@with']],
            ['GET,POST', '/logs/:ip/block', 'AdminController@logIpBlock', ['Auth@with']],

            // Contents
            ['GET,POST', '/icon-picker', 'ContentController@iconPicker', ['Auth@with']],
            ['GET,POST', '/:module', 'ContentController@contents', ['Auth@with']],
            ['GET,POST', '/:module/list', 'ContentController@contentList', ['Auth@with']],
            ['GET,POST', '/:module/add', 'ContentController@contentAdd', ['Auth@with']],
            ['GET,POST', '/:module/:id', 'ContentController@contentDetail', ['Auth@with']],
            ['GET,POST', '/:module/:id/delete', 'ContentController@contentDelete', ['Auth@with']],
            ['GET,POST', '/:module/:id/update', 'ContentController@contentUpdate', ['Auth@with']],
            
            // Content Autocomplete Field
            ['GET,POST', '/:module/autocomplete', 'ContentController@contentAutoCompleteInquiry', ['Auth@with']],

            // Content Image Upload in Editor(quill)
            ['POST', '/content/:module/upload-file', 'ContentController@uploadAFile', ['Auth@with']],

            // Content Slug Check
            ['GET,POST', '/:module/slug', 'ContentController@contentSlugInquiry', ['Auth@with']],

            // Forms
            ['GET,POST', '/forms/:form', 'FormController@forms', ['Auth@with']],
            ['GET,POST', '/forms/:form/list', 'FormController@formList', ['Auth@with']],
            ['GET,POST', '/forms/:form/:id', 'FormController@formDetail', ['Auth@with']],
            ['GET,POST', '/forms/:form/:id/delete', 'FormController@formDelete', ['Auth@with']],
            ['GET,POST', '/forms/:form/:id/update', 'FormController@formUpdate', ['Auth@with']],

            // Menus
            ['GET,POST', '/menus', 'MenuController@menus', ['Auth@with']],
            ['GET,POST', '/menus/list', 'MenuController@menuList', ['Auth@with']],
            ['GET,POST', '/menus/add', 'MenuController@menuAdd', ['Auth@with']],
            ['GET,POST', '/menus/:id', 'MenuController@menuDetail', ['Auth@with']],
            ['GET,POST', '/menus/:id/delete', 'MenuController@menuDelete', ['Auth@with']],
            ['GET,POST', '/menus/:id/update', 'MenuController@menuUpdate', ['Auth@with']],
            ['GET,POST', '/menus/get-menu-params', 'MenuController@getMenuParameters', ['Auth@with']],

            // Settings
            ['GET,POST', '/settings', 'AdminController@settings', ['Auth@with']],
            ['GET,POST', '/settings/update', 'AdminController@settingsUpdate', ['Auth@with']],
        ];
    });

    // Single route
    $app->route('GET', '/', 'AppController@index', ['Auth@verifyAccount']);
    $app->route('GET', '/app/cookie-consent', 'AppController@cookieConsent', []);

    // Please do not remove for KN to work properly.
    $app->route('GET,POST', '/cron', 'AppController@cronJobs');

    // Multi route group
    $app->routes([
        ['GET,POST', '/sandbox', 'AppController@sandbox'],
        ['GET,POST', '/sandbox/:action', 'AppController@sandbox']
    ]);

    $app->excludeWhileInMaintenance([
        'auth/login'
    ]);

    $app->run();

} catch (Exception $e) {

    KN\Core\Exception::exceptionHandler($e);

}