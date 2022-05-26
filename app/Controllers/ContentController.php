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

    public function schemas() {

        return [
            'status' => true,
            'statusCode' => 200,
            'arguments' => [
                'title' => Base::lang('base.schemas'),
                'description' => Base::lang('base.schemas_message')
            ],
            'view' => ['admin.schemas', 'admin']
        ];

    }

}