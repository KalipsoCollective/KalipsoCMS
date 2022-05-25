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