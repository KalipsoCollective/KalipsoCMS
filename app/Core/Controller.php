<?php

/**
 * @package KN
 * @subpackage Controller
 */

declare(strict_types=1);

namespace KN\Core;

use KN\Helpers\Base;

class Controller {


    public $module;
    public $modules;
    public $forms;
    public $form;

    /**
     *  @param object container  factory class   
     *  @return void
     **/

    public function __construct($container) {

        $this->container = $container;
        $this->module = isset($this->get('request')->attributes['module']) !== false ? $this->get('request')->attributes['module'] : 'general';
        $this->modules = file_exists($file = Base::path('app/Resources/modules.php')) ? require $file : [];
        $this->form = isset($this->get('request')->attributes['form']) !== false ? $this->get('request')->attributes['form'] : 'general';
        $this->forms = file_exists($file = Base::path('app/Resources/forms.php')) ? require $file : [];

    }

    public function get($key = null) {

        return is_null($key) ? $this->container : $this->container->{$key};

    }

}