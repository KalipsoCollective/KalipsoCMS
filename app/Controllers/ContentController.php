<?php

/**
 * @package KN
 * @subpackage Controller
 */

declare(strict_types=1);

namespace KN\Controllers;

use KN\Core\Controller;
use KN\Helpers\Base;
use KN\Model\Contents;
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

            $arguments = [
                'title' => $title,
                'moduleName' => $moduleName,
                'moduleDatas' => $module,
                'icon' => $icon,
                'description' => $description,
                'module' => $this->module,
                'modules' => $this->modules,
                'languages' => Base::config('app.available_languages'),
                'moduleForm' => $this->prepareModuleForm($module['inputs']),
            ];

            return [
                'status' => true,
                'statusCode' => 200,
                'arguments' => $arguments,
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

    public function getModuleDatas($module = null) {

        $model = (new Contents())->where('module', $module);

        return $model->getAll();

    }

    public function prepareModuleForm($module, $fill = null) {

        $moduleForm = '';
        $languages = Base::config('app.available_languages');
        foreach ($module as $name => $input) {

            if ($name === 'widget') { // Relational content
                foreach ($input as $key => $widget) {
                    // from db or array
                    $data = call_user_func([$this, $widget['source'][0]], ...$widget['source'][1]);

                    $options = '';
                    foreach ($data as $widgetData) {

                        $widgetInputs = json_decode($widgetData->input);
                        $text = isset($widgetInputs->{$widget['use_for_view']}->{Base::lang('lang.code')}) !== false
                            ? $widgetInputs->{$widget['use_for_view']}->{Base::lang('lang.code')} 
                            : $widgetInputs->{$widget['use_for_view']};

                        $options .= '<option value="'.$widgetData->id.'">' . $text . '</option>';
                    }
                    $moduleForm .= '
                    <div class="col-12 col-md-6">
                        <div class="form-floating">
                            <select class="form-select" required name="' . $key . '" id="content_' . $key . '" placeholder="' . Base::lang($widget['label']) . '">
                                <option value="0">' . Base::lang('base.all') . '</option>
                                '.$options.'
                            </select>
                            <label for="content_' . $key . '">' . Base::lang($widget['label']) . '</label>
                        </div>
                    </div>';

                }
            } else {

                $nameSubfix = [null];
                if (isset($input['multilanguage']) !== false AND $input['multilanguage']) {
                    $nameSubfix = $languages;
                }

                $attributes = '';
                if (isset($input['attributes']) !== false) {
                    foreach ($input['attributes'] as $attribute => $val) {
                        $attributes .= $attribute . '="'.$val.'" ';
                    }
                }

                foreach ($nameSubfix as $lang) {
                    
                    $inputName = is_null($lang) ? $name : $name.'['.$lang.']';

                    switch ($input['type']) {
                        case 'input':

                            $moduleForm .= '
                            <div class="col-12 col-md-6">
                                <div class="form-floating">
                                    <input class="form-select" '.$attributes.'name="' . $inputName . '" id="content_' . $name . $lang . '" placeholder="' . Base::lang($input['label']) . '" />
                                    <label for="content_' . $name . $lang . '">' . Base::lang($input['label']) . '</label>
                                </div>
                            </div>';
                            break;
                        
                        case 'select':
                            $options = '';
                            if (isset($input['data']) !== false) {
                                foreach ($input['data'] as $val => $text) {
                                    $options .= '<option value="'.$val.'">' . $text . '</option>';
                                }
                            }

                            $moduleForm .= '
                            <div class="col-12 col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" '.$attributes.'name="' . $inputName . '" id="content_' . $name . $lang . '" placeholder="' . Base::lang($widget['label']) . '">
                                        '.$options.'
                                    </select>
                                    <label for="content_' . $name . $lang . '">' . Base::lang($widget['label']) . '</label>
                                </div>
                            </div>';
                            break;

                        case 'textarea':
                            // code...
                            break;

                        case 'editor':
                            // code...
                            break;
                    }

                }
                    
            }
        }


        return $moduleForm;
        /*
            <form class="row g-2" data-kn-form id="schemaAdd" method="post" action="<?php echo $this->url('management/' . $module . '/add'); ?>">
                <div class="form-loader">
                    <div class="spinner-border text-light" role="status">
                        <span class="visually-hidden"><?php echo \KN\Helpers\Base::lang('base.loading'); ?></span>
                    </div>
                </div>
                <div class="col-12 form-info">
                </div>
                <div class="col-12">
                    <div class="d-flex align-items-start">
                        <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">Home</button>
                            <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">Profile</button>
                            <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">Messages</button>
                            <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">Settings</button>
                        </div>
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">...</div>
                            <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">...</div>
                            <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">...</div>
                            <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">...</div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                        <select class="form-select" id="roleRoutes" required multiple style="height: 300px" name="routes[]" aria-label="<?php echo \KN\Helpers\Base::lang('base.routes'); ?>">
                            <?php
                            /*
                            foreach ($roles as $route => $detail) {
                                echo '
                                <option value="' . $route . '"' . ($detail['default'] ? ' selected' : '') . '>
                                    ' . \KN\Helpers\Base::lang($detail['name']) . '
                                </option>';
                            }*//* ?>
                        </select>
                        <label for="roleRoutes"><?php echo \KN\Helpers\Base::lang('base.routes'); ?></label>
                    </div>
                </div>
            </form> */

    }

}