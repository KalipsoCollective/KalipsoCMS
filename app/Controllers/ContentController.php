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
use KN\Model\Files;
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
        $multilanguage = false;
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

                    if (isset($input['col']) !== false) {
                        $col = $input['col'];
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
                                    <label for="content_' . $name . $lang . '" class="form-label small text-muted m-0">' . Base::lang($input['label']) . '</label>
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
                    $multilanguage = true;
                    $moduleForm .= '
                                </div>
                            </div>  
                        </div>
                    </div>';
                }
                    
            }
        }

        $moduleForm = '
        <form class="row g-2" data-kn-form id="contentAdd"'.($multilanguage ? ' novalidate' : '').' method="post" action="'.$this->get()->url('management/' . $this->module . '/add').'">
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
                        $handle->file_new_name_body   = Base::stringShortener(Base::slugGenerator($handle->file_src_name_body), 200, false);
                        $handle->file_max_size = Base::config('app.upload_max_size');
                        $handle->allowed = array('image/*');
                        $handle->image_convert = 'webp';
                        if ($quality = Base::config('app.upload_webp_quality')) {
                            $handle->webp_quality = $quality;
                        }
                        if ($quality = Base::config('app.upload_png_quality')) {
                            $handle->webp_quality = $quality;
                        }
                        if ($quality = Base::config('app.upload_jpeg_quality')) {
                            $handle->webp_quality = $quality;
                        }

                        if (Base::config('app.upload_max_width')) {
                            $handle->image_resize         = true;
                            $handle->image_x              = Base::config('app.upload_max_width');
                            $handle->image_ratio_y        = true;
                        }
                        
                        $handle->process($path);
                        if ($handle->processed) {
                           
                            $alerts[] = [
                                'status' => 'success',
                                'message' => Base::lang('base.file_successfully_uploaded')
                            ];
                            $url = $this->module . '/' . $handle->file_dst_name_body . '.' . $handle->file_dst_name_ext;
                            (new Files)->insert([
                                'module' => $this->module,
                                'name' => $handle->file_dst_name_body,
                                'files' => json_encode([
                                    'original' => $url
                                ])
                            ]);

                            $arguments['editor_upload'][] = $this->get()->url('upload/' . $url);
                            $handle->clean();

                        } else {
                            echo 'error : ' . $handle->error;
                            $alerts[] = [
                                'status' => 'error',
                                'message' => Base::lang('base.file_upload_problem') 
                                . (isset($handle->error) !== false ? '(' . $handle->error . ')' : '')
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

    public function contentAdd() {

        $alerts = [];
        $arguments = [];

        $inputAreas = [];
        $files = [];
        $requiredAreas = [
            'areas' => [], 
            'files' => []
        ];
        if (isset($this->modules[$this->module]) !== false) {

            $module = $this->modules[$this->module];

            foreach ($module['inputs'] as $name => $detail) {

                if ($name === 'widgets') {

                    foreach ($detail as $name => $widgetDetail) {
                        
                        $inputAreas[$name] = 'int';
                        if (isset($detail['attributes']['required']) !== false AND $detail['attributes']['required'] === 'true') {
                            $requiredAreas['areas'][$name] = true;
                        }

                    }

                } else {

                    if ($detail['type'] === 'input' OR $detail['type'] === 'textarea' OR $detail['type'] === 'select') {

                        $inputAreas[$name] = 'nulled_text';

                    } elseif ($detail['type'] === 'number') {

                        $inputAreas[$name] = 'int';

                    } elseif ($detail['type'] === 'editor') {

                        $inputAreas[$name] = 'nulled_html';

                    } elseif ($detail['type'] === 'file') {

                        $files[$name] = [
                            'size' => $detail['size'],
                            'attributes' => isset($detail['attributes']) !== false ? $detail['attributes'] : []
                        ];

                    }

                    if (isset($detail['attributes']['required']) !== false AND $detail['attributes']['required'] === 'true') {
                        $requiredAreas[($detail['type'] === 'file' ? 'files' : 'areas')][$name] = true;
                    }
                }
            }

            extract(Base::input($inputAreas, $this->get('request')->params));

            $insert = [];
            foreach ($inputAreas as $inputName => $inputType) {
                
                if (is_array($$inputName)) { // multilingual
                    foreach ($$inputName as $lang => $inputVar) {
                        if (
                            isset($requiredAreas['areas'][$inputName]) === false OR ! empty($inputVar)
                        ) {
                            $insert[$inputName][$lang] = $inputVar;
                        } else {
                            if ($inputType === 'nulled_html') {
                                $arguments['manipulation']['#contentAdd [data-name="' . $inputName . '[' . $lang . ']"]'] = [
                                    'class' => ['border', 'border-1', 'border-danger'],
                                ];
                            } else {
                                $arguments['manipulation']['#contentAdd [name="' . $inputName . '[' . $lang . ']"]'] = [
                                    'class' => ['is-invalid'],
                                ];
                            }
                            
                            
                        }
                    }
                } elseif (isset($requiredAreas['areas'][$inputName]) === false OR ! empty($inputVar)) {
                    $insert[$inputName] = $$inputName;
                } else {
                    if ($inputType === 'nulled_html') {
                        $arguments['manipulation']['#contentAdd [data-name="' . $inputName . '"]'] = [
                            'class' => ['border', 'border-1', 'border-danger'],
                        ];
                    } else {
                        $arguments['manipulation']['#contentAdd [name="' . $inputName . '"]'] = [
                            'class' => ['is-invalid'],
                        ];
                    }
                    
                }

            }

            if (isset($arguments['manipulation']) === false) {


            } else {

                $alerts[] = [
                    'status' => 'warning',
                    'message' => Base::lang('base.form_cannot_empty')
                ];
            }

            return [
                'status' => true,
                'statusCode' => 200,
                'arguments' => $arguments,
                'alerts' => $alerts,
                'view' => null
            ];
            /*
            Base::dump($insert);
            Base::dump($arguments);
            exit;*/



            /*
            foreach ($file as $f) {

                $handle = new Upload($f, Base::lang('lang.iso_code'));
                if ($handle->uploaded) {
                    $handle->file_new_name_body   = Base::stringShortener(Base::slugGenerator($handle->file_src_name_body), 200, false);
                    $handle->file_max_size = Base::config('app.upload_max_size');
                    $handle->allowed = array('image/*');
                    $handle->image_convert = 'webp';
                    if ($quality = Base::config('app.upload_webp_quality')) {
                        $handle->webp_quality = $quality;
                    }
                    if ($quality = Base::config('app.upload_png_quality')) {
                        $handle->webp_quality = $quality;
                    }
                    if ($quality = Base::config('app.upload_jpeg_quality')) {
                        $handle->webp_quality = $quality;
                    }

                    if (Base::config('app.upload_max_width')) {
                        $handle->image_resize         = true;
                        $handle->image_x              = Base::config('app.upload_max_width');
                        $handle->image_ratio_y        = true;
                    }
                    
                    $handle->process($path);
                    if ($handle->processed) {
                       
                        $alerts[] = [
                            'status' => 'success',
                            'message' => Base::lang('base.file_successfully_uploaded')
                        ];
                        $url = $this->module . '/' . $handle->file_dst_name_body . '.' . $handle->file_dst_name_ext;
                        (new Files)->insert([
                            'module' => $this->module,
                            'name' => $handle->file_dst_name_body,
                            'files' => json_encode([
                                'original' => $url
                            ])
                        ]);

                        $arguments['editor_upload'][] = $this->get()->url('upload/' . $url);
                        $handle->clean();

                    } else {
                        echo 'error : ' . $handle->error;
                        $alerts[] = [
                            'status' => 'error',
                            'message' => Base::lang('base.file_upload_problem') 
                            . (isset($handle->error) !== false ? '(' . $handle->error . ')' : '')
                        ];
                    }
                }
            }*/

            Base::dump($this->get('request')->files);
            Base::dump($requiredAreas);
            Base::dump($inputAreas, true);

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

        extract(Base::input([
            'email' => 'nulled_text',
            'u_name' => 'nulled_text',
            'f_name' => 'nulled_text',
            'l_name' => 'nulled_text',
            'role_id' => 'nulled_int',
            'password' => 'nulled_password'
        ], $this->get('request')->params));

        

        $model = new Users();
        
        if ($email AND $u_name AND $role_id AND $password) {

            $userNameCheck = $model->count('id', 'total')->where('u_name', $u_name)->get();
            if ((int)$userNameCheck->total === 0) {

                $userEmailCheck = $model->count('id', 'total')->where('email', $email)->get();
                if ((int)$userEmailCheck->total === 0) {

                    $insert = [
                        'email' => $email,
                        'u_name' => $u_name,
                        'f_name' => $f_name,
                        'l_name' => $l_name,
                        'role_id' => $role_id,
                        'password' => $password,
                        'token' => Base::tokenGenerator(80),
                        'status' => 'active'
                    ];

                    $insert = $model->insert($insert);

                    if ($insert) {

                        $alerts[] = [
                            'status' => 'success',
                            'message' => Base::lang('base.user_successfully_added')
                        ];
                        $arguments['form_reset'] = true;
                        $arguments['modal_close'] = '#addModal';
                        $arguments['table_reset'] = 'usersTable';

                    } else {

                        $alerts[] = [
                            'status' => 'error',
                            'message' => Base::lang('base.user_add_problem')
                        ];
                    }

                } else {

                    $alerts[] = [
                        'status' => 'warning',
                        'message' => Base::lang('base.email_is_already_used')
                    ];
                    $arguments['manipulation'] = [
                        '#userAdd [name="email"]' => [
                            'class' => ['is-invalid'],
                        ]
                    ];

                }

            } else {

                $alerts[] = [
                    'status' => 'warning',
                    'message' => Base::lang('base.username_is_already_used')
                ];
                $arguments['manipulation'] = [
                    '#userAdd [name="u_name"]' => [
                        'class' => ['is-invalid'],
                    ]
                ];
            }

        } else {

            $alerts[] = [
                'status' => 'warning',
                'message' => Base::lang('base.form_cannot_empty')
            ];

            $arguments['manipulation'] = [];

            if ($email) {
                $arguments['manipulation']['#userAdd [name="email"]'] = [
                    'class' => ['is-invalid'],
                ];
            }

            if ($u_name) {
                $arguments['manipulation']['#userAdd [name="u_name"]'] = [
                    'class' => ['is-invalid'],
                ];
            }

            if ($role_id) {
                $arguments['manipulation']['#userAdd [name="role_id"]'] = [
                    'class' => ['is-invalid'],
                ];
            }

            if ($password) {
                $arguments['manipulation']['#userAdd [name="password"]'] = [
                    'class' => ['is-invalid'],
                ];
            }

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