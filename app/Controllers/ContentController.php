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
use \Verot\Upload\Upload;

final class ContentController extends Controller {

    public $module;
    public $modules;

    public function __construct($container) {

        parent::__construct($container);
        $this->module = isset($this->get('request')->attributes['module']) !== false ? $this->get('request')->attributes['module'] : 'general';
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
                    $moduleForm .= '
                    <div class="col-12 bg-light p-1 rounded shadow shadow-sm border border-1 border-gray">
                        <div class="row g-4 align-items-center">
                            <div class="col-4 col-md-2">
                                <div class="nav nav-pills flex-column" id="content_'.$name.'-tablist" role="tablist" aria-orientation="vertical">';
                                foreach ($languages as $i => $lang) {
                                    $moduleForm .= '
                                    <button class="nav-link'.($i===0 ? ' active' : '').'" id="content_'.$name.'-tab" data-bs-toggle="pill" data-bs-target="#content_'.$name.'-'.$lang.'" type="button" role="tab" aria-controls="content_'.$name.'-'.$lang.'" aria-selected="'.($i===0 ? 'true' : 'false').'">
                                        '.Base::lang('langs.'.$lang).'
                                    </button>';
                                }
                        $moduleForm .= '
                                </div>
                            </div>
                            <div class="col-8 col-md-10 ps-0">
                                <div class="tab-content" id="content_'.$name.'-tablist">';
                                
                }

                $attributes = '';
                if (isset($input['attributes']) !== false) {
                    foreach ($input['attributes'] as $attribute => $val) {
                        $attributes .= $attribute . '="'.$val.'" ';
                    }
                }

                foreach ($nameSubfix as $i => $lang) {
                    
                    $col = 'col-12 col-md-6';
                    $inputName = is_null($lang) ? $name : $name.'['.$lang.']';
                    // multilingual
                    if (! is_null($lang)) {
                        $col = 'col';
                        $moduleForm .= '
                        <div class="tab-pane fade'.($i === 0 ? ' show active' : '').'" id="content_'.$name.'-'.$lang.'" role="tabpanel" aria-labelledby="content_'.$name.'-tab">';
                    }

                    switch ($input['type']) {
                        case 'input':

                            $moduleForm .= '
                            <div class="'.$col.'">
                                <div class="form-floating">
                                    <input class="form-control" '.$attributes.'name="' . $inputName . '" id="content_' . $name . $lang . '" placeholder="' . Base::lang($input['label']) . '" />
                                    <label for="content_' . $name . $lang . '">' . Base::lang($input['label']) . '</label>
                                </div>
                            </div>';
                            break;

                        case 'file':

                            $moduleForm .= '
                            <div class="'.$col.'">
                                <div class="">
                                    <label for="content_' . $name . $lang . '" class="form-label">' . Base::lang($input['label']) . '</label>
                                    <input class="form-control" '.$attributes.'name="' . $inputName . '" id="content_' . $name . $lang . '" type="file">
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
                            <div class="'.$col.'">
                                <div class="form-floating">
                                    <select class="form-select" '.$attributes.'name="' . $inputName . '" id="content_' . $name . $lang . '" placeholder="' . Base::lang($widget['label']) . '">
                                        '.$options.'
                                    </select>
                                    <label for="content_' . $name . $lang . '">' . Base::lang($widget['label']) . '</label>
                                </div>
                            </div>';
                            break;

                        case 'textarea':
                            $moduleForm .= '
                            <div class="'.$col.'">
                                <div class="form-floating">
                                    <textarea class="form-control" '.$attributes.'name="' . $inputName . '" id="content_' . $name . $lang . '" placeholder="' . Base::lang($input['label']) . '" style="min-height: 200px"></textarea>
                                    <label for="content_' . $name . $lang . '">' . Base::lang($input['label']) . '</label>
                                </div>
                            </div>';
                            break;

                        case 'editor':
                            $moduleForm .= '
                            <div class="'.$col.'">
                                <div data-kn-toggle="editor" class="editor" data-options=\'' . json_encode(['placeholder'=>Base::lang($input['label'])])  . '\' data-name="' . $inputName . '" data-module="' . $this->module . '">
                                </div>
                            </div>';
                            break;
                    }

                    if (! is_null($lang)) {
                        $moduleForm .= '
                        </div>';
                    }

                }

                if (isset($input['multilanguage']) !== false AND $input['multilanguage']) {
                    $moduleForm .= '
                                </div>
                            </div>  
                        </div>
                    </div>';
                }
                    
            }
        }

        $moduleForm = '
        <form class="row g-2" data-kn-form id="contentAdd" method="post" action="'.$this->get()->url('management/' . $this->module . '/add').'">
            <div class="form-loader">
                <div class="spinner-border text-light" role="status">
                    <span class="visually-hidden">'.Base::lang('base.loading').'</span>
                </div>
            </div>
            <div class="col-12 form-info">
            </div>'
            . $moduleForm .
        '</form>';


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

    public function uploadAFile() {

        $files = $this->get()->request->files;

        $alerts = [];
        $arguments = [];

        if (is_array($files) AND count($files)) {

            if (! is_dir($path = Base::path('upload')))
                mkdir($path);

            if (! is_dir($path .= '/' . $this->module))
                mkdir($path);

            foreach ($files as $name => $file) {
                foreach ($file as $f) {
                    $handle = new Upload($f, Base::lang('lang.iso_code'));
                    if ($handle->uploaded) {
                        $handle->file_new_name_body   = Base::slugGenerator($handle->file_src_name_body);
                        $handle->file_max_size = Base::config('app.editor_upload_max_size');
                        $handle->allowed = array('image/*');
                        $handle->image_convert = 'webp';
                        if ($quality = Base::config('app.editor_upload_webp_quality')) {
                            $handle->webp_quality = $quality;
                        }
                        if ($quality = Base::config('app.editor_upload_png_quality')) {
                            $handle->webp_quality = $quality;
                        }
                        if ($quality = Base::config('app.editor_upload_jpeg_quality')) {
                            $handle->webp_quality = $quality;
                        }

                        if (Base::config('app.editor_upload_max_width')) {
                            $handle->image_resize         = true;
                            $handle->image_x              = Base::config('app.editor_upload_max_width');
                            $handle->image_ratio_y        = true;
                        }
                        
                        $handle->process($path);
                        if ($handle->processed) {
                           
                            $alerts[] = [
                                'status' => 'success',
                                'message' => Base::lang('base.file_successfully_uploaded')
                            ];

                            $arguments['editor_upload'][] = [
                                'file_dst_path' => str_replace($path, '', $handle->file_dst_path),
                                'file_dst_name_body' => str_replace($path, '', $handle->file_dst_name_body),
                                'file_dst_name_ext' => str_replace($path, '', $handle->file_dst_name_ext),
                                'file_dst_name' => str_replace($path, '', $handle->file_dst_name),
                                'file_dst_pathname' => str_replace($path, '', $handle->file_dst_pathname),
                            ];

                            $handle->clean();

                        } else {
                            echo 'error : ' . $handle->error;
                            $alerts[] = [
                                'status' => 'error',
                                'message' => Base::lang('base.file_upload_problem')
                            ];
                        }
                    }
                }
            }

        } else {
            $alerts[] = [
                'status' => 'warning',
                'message' => Base::lang('base.file_not_found')
            ];
        }

        return [
            'status' => true,
            'statusCode' => 200,
            'arguments' => $arguments,
            'alerts' => $alerts,
            'view' => null
        ];

    }

}