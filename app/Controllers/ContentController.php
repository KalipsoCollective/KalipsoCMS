<?php

/**
 * @package KN
 * @subpackage Controller
 */

declare(strict_types=1);

namespace KN\Controllers;

use KN\Core\Controller;
use KN\Helpers\Base;
use KN\Model\Sessions;
use KN\Core\Notification;

final class ContentController extends Controller {

    public $module;
    public $modules;

    public function __construct($container) {

        parent::__construct($container);
        $this->module = $this->get('request')->attributes['module'];
        $this->modules = file_exists($file = Base::path('app/Resources/modules.php')) ? require $file : [];

    }

    public function contents() {

        $title = Base::lang('base.contents');
        $description = Base::lang('base.contents_message');

        if (isset($this->modules[$this->module]) !== false) {

            $module = $this->modules[$this->module];
            $moduleName = Base::lang($module['name']);
            $title = $moduleName . ' | ' . $title;
            $description = Base::lang($module['description']);
            $icon = isset($module['icon']) !== false ? $module['icon'] : 'ti ti-folders';

            return [
                'status' => true,
                'statusCode' => 200,
                'arguments' => [
                    'title' => $title,
                    'moduleName' => $moduleName,
                    'icon' => $icon,
                    'description' => $description,
                    'modules' => $this->modules,
                ],
                'view' => ['admin.contents', 'admin']
            ];

        } else {

            return [
                'status' => false,
                'statusCode' => 404,
                'redirect' => '/management',
                'alerts' => [
                    [
                        'status' => 'error',
                        'message' => Base::lang('error.module_not_found')
                    ]
                ],
                'view' => null
            ];
        }
    }

}