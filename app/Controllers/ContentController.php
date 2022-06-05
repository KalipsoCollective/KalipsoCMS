<?php

/**
 * @package KN
 * @subpackage Controller
 */

declare(strict_types=1);

namespace KN\Controllers;

use KN\Core\Controller;
use KN\Model\Contents;
use KN\Model\Files;
use KN\Helpers\Base;
use KN\Helpers\KalipsoTable;
use \Verot\Upload\Upload;


final class ContentController extends Controller {

    public $module;
    public $modules;
    public $forms;

    public function __construct($container) {

        parent::__construct($container);
        $this->module = isset($this->get('request')->attributes['module']) !== false ? $this->get('request')->attributes['module'] : 'general';
        $this->modules = file_exists($file = Base::path('app/Resources/modules.php')) ? require $file : [];
        $this->forms = file_exists($file = Base::path('app/Resources/forms.php')) ? require $file : [];

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
                'forms' => $this->forms,
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

        $idPrefix = 'content_add';
        if (! is_null($fill)) {
            $fillDatas = json_decode($fill->input);
            $id = $fill->id;
            $idPrefix = 'content_edit';
        }

        $moduleForm = '';
        $languages = Base::config('app.available_languages');
        $multilanguage = false;
        foreach ($module as $name => $input) {

            if ($name === 'widget') { // Relational content

                foreach ($input as $key => $widget) {
                    // from db or array
                    $data = call_user_func([$this, $widget['source'][0]], ...$widget['source'][1]);

                    $options = '';

                    $allSelected = '';
                    if (isset($fillDatas->{$key}) !== false AND $fillDatas->{$key} == 0) {
                        $allSelected = ' selected';
                    }
                    foreach ($data as $widgetData) {

                        $widgetInputs = json_decode($widgetData->input);
                        $text = isset($widgetInputs->{$widget['use_for_view']}->{Base::lang('lang.code')}) !== false
                            ? $widgetInputs->{$widget['use_for_view']}->{Base::lang('lang.code')} 
                            : $widgetInputs->{$widget['use_for_view']};

                        $selected = '';
                        if (isset($fillDatas->{$key}) !== false AND $fillDatas->{$key} == $widgetData->id) {
                            $selected = ' selected';
                        }

                        $options .= '<option value="'.$widgetData->id.'"' . $selected . '>' . $text . '</option>';
                    }

                    $attributes = '';
                    $requiredWidget = false;
                    if (isset($widget['attributes']) !== false) {
                        foreach ($widget['attributes'] as $attribute => $val) {
                            $attributes .= $attribute . '="'.$val.'" ';
                            if ($attribute === 'required') {
                                $requiredWidget = true;
                            }
                        }
                    }

                    $requiredBadge = '';
                    if (isset($widget['attributes']['required']) !== false AND $widget['attributes']['required']) {
                        $requiredBadge = ' <sup class="text-danger">*</sup>';
                    }

                    $col = isset($widget['col']) !== false ? $widget['col'] : 'col-12 col-md-6';

                    $moduleForm .= '
                    <div class="' . $col . '">
                        <div class="form-floating">
                            <select class="form-select" '.$attributes.'name="' . $key . '" id="' . $idPrefix . '_' . $key . '" placeholder="' . Base::lang($widget['label']) . '">
                                '.(! $requiredWidget ? '<option value=""></option>' : '').'
                                <option value="0"'.$allSelected.'>' . Base::lang('base.all') . '</option>
                                '.$options.'
                            </select>
                            <label for="' . $idPrefix . '_' . $key . '">' . Base::lang($widget['label']) . $requiredBadge . '</label>
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
                                <div class="nav nav-pills flex-column" id="' . $idPrefix . '_'.$name.'-tablist" role="tablist" aria-orientation="vertical">';
                                foreach ($languages as $i => $lang) {
                                    $moduleForm .= '
                                    <button class="nav-link'.($i===0 ? ' active' : '').'" id="' . $idPrefix . '_'.$name.'-tab" data-bs-toggle="pill" data-bs-target="#' . $idPrefix . '_'.$name.'-'.$lang.'" type="button" role="tab" aria-controls="' . $idPrefix . '_'.$name.'-'.$lang.'" aria-selected="'.($i===0 ? 'true' : 'false').'">
                                        '.Base::lang('langs.'.$lang).'
                                    </button>';
                                }
                        $moduleForm .= '
                                </div>
                            </div>
                            <div class="col-8 col-md-10 ps-0">
                                <div class="tab-content" id="' . $idPrefix . '_'.$name.'-tablist">';
                                
                }

                foreach ($nameSubfix as $i => $lang) {
                    
                    $attributes = '';
                    if (isset($input['attributes']) !== false) {
                        foreach ($input['attributes'] as $attribute => $val) {
                            $attributes .= $attribute . '="'.$val.'" ';
                        }
                    }

                    $currentVal = null;
                    $col = 'col-12 col-md-6';
                    $inputName = is_null($lang) ? $name : $name.'['.$lang.']';
                    // multilingual
                    if (! is_null($lang)) {
                        $col = 'col';
                        $moduleForm .= '
                        <div class="tab-pane fade'.($i === 0 ? ' show active' : '').'" id="' . $idPrefix . '_'.$name.'-'.$lang.'" role="tabpanel" aria-labelledby="' . $idPrefix . '_'.$name.'-tab">';

                        if (isset($fillDatas->{$name}->{$lang}) !== false) {
                            $currentVal = $fillDatas->{$name}->{$lang};
                        }

                    } else {
                        
                        if (isset($fillDatas->{$name}) !== false) {
                            $currentVal = $fillDatas->{$name};
                        }
                    }

                    if (isset($input['col']) !== false) {
                        $col = $input['col'];
                    }

                    $requiredBadge = '';
                    if (isset($input['attributes']['required']) !== false AND $input['attributes']['required']) {
                        $requiredBadge = ' <sup class="text-danger">*</sup>';
                    }

                    switch ($input['type']) {
                        case 'input':

                            if (! is_null($currentVal)) {
                                $attributes .= 'value="'.$currentVal.'" ';
                            }
                            $attributes .= 'data-kn-lang="'.$lang.'" ';
                            if (isset($id) !== false) $attributes .= 'data-kn-id="'.$id.'" ';

                            $moduleForm .= '
                            <div class="'.$col.'">
                                <div class="form-floating">
                                    <input class="form-control" '.$attributes.'name="' . $inputName . '" id="' . $idPrefix . '_' . $name . $lang . '" placeholder="' . Base::lang($input['label']) . '" />
                                    <label for="' . $idPrefix . '_' . $name . $lang . '">' . Base::lang($input['label']) . $requiredBadge . '</label>
                                </div>
                            </div>';
                            break;

                        case 'file':

                            $externalBadge = '';
                            if (! is_null($currentVal)) {

                                $getFile = (new Files)->select('id, name, files')->where('id', $currentVal)->get();
                                
                                if (! empty($getFile)) {
                                    $getFile->files = json_decode($getFile->files);
                                    $url = Base::base('upload/' . $getFile->files->original);
                                    $externalBadge = ' 
                                    <span class="ms-2" data-target="current_file_buttons_' . $inputName . '">
                                        <a class="small text-muted" href="' . $url . '" 
                                            target="_blank"
                                            data-target="current_file_view_' . $inputName . '"
                                            >
                                            ' . Base::lang('base.view') . '
                                        </a> &middot; 
                                        <a class="small text-danger" href="javascript:;" 
                                            data-target="current_file_delete_' . $inputName . '"
                                            data-kn-again="'.Base::lang('base.are_you_sure').'" 
                                            data-kn-action="manipulation"
                                            data-kn-manipulation=\'' . json_encode(
                                                [
                                                    '[data-target="current_file_buttons_' . $inputName . '"]' => [
                                                        'remove_element' => true
                                                    ]
                                                ]) . '\'
                                            >
                                            ' . Base::lang('base.delete') . '
                                        </a>
                                        <input type="hidden" name="current_file_' . $inputName . '" value="' . $getFile->id . '" />
                                    </span>';
                                }
                            }

                            $moduleForm .= '
                            <div class="'.$col.'">
                                <div class="">
                                    <label for="content_' . $name . $lang . '" class="form-label small text-muted m-0">' . Base::lang($input['label']) . $requiredBadge . $externalBadge . '</label>
                                    <input class="form-control" '.$attributes.'name="' . $inputName . '" id="' . $idPrefix . '_' . $name . $lang . '" type="file">
                                </div>
                            </div>';
                            break;
                        
                        case 'select':
                            $options = '';
                            if (isset($input['data']) !== false) {
                                foreach ($input['data'] as $val => $text) {
                                    $selected = '';
                                    if (! is_null($currentVal) AND $currentVal == $val) {
                                        $selected .= ' selected';
                                    }
                                    $options .= '<option value="'.$val.'"' . $selected . '>' . $text . '</option>';
                                }
                            }

                            $moduleForm .= '
                            <div class="'.$col.'">
                                <div class="form-floating">
                                    <select class="form-select" '.$attributes.'name="' . $inputName . '" id="' . $idPrefix . '_' . $name . $lang . '" placeholder="' . Base::lang($widget['label']) . '">
                                        '.$options.'
                                    </select>
                                    <label for="' . $idPrefix . '_' . $name . $lang . '">' . Base::lang($widget['label']) . $requiredBadge . '</label>
                                </div>
                            </div>';
                            break;

                        case 'textarea':
                            $moduleForm .= '
                            <div class="'.$col.'">
                                <div class="form-floating">
                                    <textarea class="form-control" '.$attributes.'name="' . $inputName . '" id="' . $idPrefix . '_' . $name . $lang . '" placeholder="' . Base::lang($input['label']) . '" style="min-height: 200px">' . $currentVal . '</textarea>
                                    <label for="' . $idPrefix . '_' . $name . $lang . '">' . Base::lang($input['label']) . $requiredBadge . '</label>
                                </div>
                            </div>';
                            break;

                        case 'editor':
                            $moduleForm .= '
                            <div class="'.$col.'">
                                <div data-kn-toggle="editor" class="editor" data-options=\'' . json_encode(['placeholder'=>Base::lang($input['label'])])  . '\' data-name="' . $inputName . '" data-module="' . $this->module . '">' . htmlspecialchars_decode((string) $currentVal) . '</div>
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
        <form class="row g-2" data-kn-form id="' . (isset($id) !== false ? 'contentEdit' : 'contentAdd') . '"'.($multilanguage ? ' novalidate' : '').' method="post" action="'.$this->get()->url('management/' . $this->module . '/' . (isset($id) !== false ? $id . '/update' : 'add')).'">
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
                            
                            $alerts[] = [
                                'status' => 'error',
                                'message' => Base::lang('base.file_upload_problem') 
                                . (isset($handle->error) !== false ? ' (' . $handle->error . ')' : '')
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

    public function contentList() {

        $container = $this->get();
        $moduleName = $this->module;
        if (isset($this->modules[$moduleName]) !== false) {

            $module = $this->modules[$moduleName];

            $tables = $module['table'];
            $tables['action'] = [
                'exclude' => true,
                'formatter' => function($row) use ($container, $moduleName) {

                    $buttons = '';
                    if ($container->authority('management/:module/:id')) {
                        $buttons .= '
                        <button type="button" class="btn btn-light" 
                            data-kn-action="'.$this->get()->url('/management/' . $moduleName . '/' . $row->id ).'">
                            ' . Base::lang('base.view') . '
                        </button>';
                    }

                    if ($container->authority('management/:module/:id/delete')) {
                        $buttons .= '
                        <button type="button" class="btn btn-danger" 
                            data-kn-again="'.Base::lang('base.are_you_sure').'" 
                            data-kn-action="'.$this->get()->url('/management/' . $moduleName . '/' . $row->id . '/delete').'">
                            ' . Base::lang('base.delete') . '
                        </button>';
                    }



                    return '
                    <div class="btn-group btn-group-sm" role="group" aria-label="'.Base::lang('base.action').'">
                        '.$buttons.'
                    </div>';
                }
            ];
            $tableOp = (new KalipsoTable())
                ->db((new Contents)->pdo)
                ->from($module['from'])
                ->process($tables)
                ->output();

            return [
                'status' => true,
                'statusCode' => 200,
                'arguments' => $tableOp,
                'view' => null
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

    public function contentAdd() {

        $alerts = [];
        $arguments = [];

        $rollBack = [];
        $inputAreas = [];
        $files = [];
        $requiredAreas = [
            'areas' => [], 
            'files' => []
        ];
        if (isset($this->modules[$this->module]) !== false) {

            $module = $this->modules[$this->module];

            // Input area check
            foreach ($module['inputs'] as $name => $detail) {

                if ($name === 'widget') {

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

                        $files[$name] = $detail;

                    }

                    if (isset($detail['attributes']['required']) !== false AND $detail['attributes']['required'] === 'true') {
                        $requiredAreas[($detail['type'] === 'file' ? 'files' : 'areas')][$name] = true;
                    }
                }
            }

            extract(Base::input($inputAreas, $this->get('request')->params));

            $insert = [];
            // Filter all inputs
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

                // Files
                if (count($files)) {

                    if (! is_dir($path = Base::path('upload')))
                        mkdir($path);

                    if (! is_dir($path .= '/' . $this->module))
                        mkdir($path);

                    foreach ($files as $fileName => $fileDetails) {

                        $requiredFile = false;
                        $multipleFile = false;

                        if (isset($fileDetails['attributes']['required']) !== false AND $fileDetails['attributes']['required']) {
                            $requiredFile = true;
                        }

                        if (isset($fileDetails['attributes']['multiple']) !== false AND $fileDetails['attributes']['multiple']) {
                            $multipleFile = true;
                        }

                        if (isset($this->get('request')->files[$fileName]) !== false) {

                            foreach ($this->get('request')->files[$fileName] as $fileKey => $fileUp) {

                                $maxSize = Base::config('app.upload_max_size');
                                if (isset($fileDetails['external_parameters']['max_size']) !== false AND $fileDetails['external_parameters']['max_size']) {
                                    $maxSize = $fileDetails['external_parameters']['max_size'];
                                }

                                $acceptMime = Base::config('app.upload_accept');
                                if (isset($fileDetails['attributes']['accept']) !== false AND $fileDetails['attributes']['accept']) {
                                    $acceptMime = $fileDetails['attributes']['accept'];
                                }

                                $convertFile = Base::config('app.upload_convert');
                                if (isset($fileDetails['external_parameters']['convert']) !== false AND $fileDetails['external_parameters']['convert']) {
                                    $convertFile = $fileDetails['external_parameters']['convert'];
                                }

                                $fileDimension = ['original' => [0, 0]];
                                if (isset($fileDetails['external_parameters']['size']) !== false AND $fileDetails['external_parameters']['size']) {
                                    $fileDimension = $fileDetails['external_parameters']['size'];
                                }

                                $handle = new Upload($fileUp, Base::lang('lang.iso_code'));
                                if ($handle->uploaded) {

                                    $insertData = [];
                                    $errorOnUpload = false;

                                    $originalFileName = Base::stringShortener(Base::slugGenerator($handle->file_src_name_body), 190, false);

                                    foreach ($fileDimension as $dimensionTag => $dimensionVar) {

                                        $newFileName = $originalFileName . '_' . $dimensionTag;
                                        $handle->file_new_name_body   = $newFileName;

                                        if ($maxSize) $handle->file_max_size = $maxSize;
                                        if ($acceptMime) $handle->allowed = $acceptMime;
                                        if ($convertFile) $handle->image_convert = $convertFile;

                                        if ($quality = Base::config('app.upload_webp_quality')) {
                                            $handle->webp_quality = $quality;
                                        }

                                        if ($quality = Base::config('app.upload_png_quality')) {
                                            $handle->webp_quality = $quality;
                                        }

                                        if ($quality = Base::config('app.upload_jpeg_quality')) {
                                            $handle->webp_quality = $quality;
                                        }

                                        $handle->image_resize           = true;
                                        $handle->image_ratio            = true;
                                        $handle->image_ratio_crop       = true;

                                        if ($dimensionVar[0]) 
                                            $handle->image_x      = $dimensionVar[0];

                                        if ($dimensionVar[1]) 
                                            $handle->image_y      = $dimensionVar[1];
                                        
                                        $handle->process($path);
                                        if ($handle->processed) {
                                           
                                            $url = $this->module . '/' . $handle->file_dst_name_body . '.' . $handle->file_dst_name_ext;
                                            $insertData[$dimensionTag] = $url;

                                        } else {
                                            
                                            $errorOnUpload = $handle->error;
                                            break;
                                            
                                        }
                                    }

                                    $handle->clean();
                                    if ($errorOnUpload === false) {

                                        $alerts[] = [
                                            'status' => 'success',
                                            'message' => Base::lang('base.file_successfully_uploaded') . ' (' . $originalFileName . ')'
                                        ];

                                        $id = (new Files)->insert([
                                            'module' => $this->module,
                                            'name' => $originalFileName,
                                            'files' => json_encode($insertData)
                                        ]);

                                        $rollBack[] = $id;
                                        if ($multipleFile) $insert[$fileName][$fileKey] = $id;
                                        else $insert[$fileName] = $id;

                                    } else {
                                        $alerts[] = [
                                            'status' => 'error',
                                            'message' => Base::lang('base.file_upload_problem') 
                                            . (isset($handle->error) !== false ? ' (' . Base::lang($fileDetails['label']) . ' -> ' . $errorOnUpload . ')' : '')
                                        ];
                                        $arguments['manipulation']['#contentAdd [name="' . $fileName . ($multipleFile ? '[]' : '') . '"]'] = [
                                            'class' => ['is-invalid'],
                                        ];
                                    }

                                } else {

                                    $alerts[] = [
                                        'status' => 'warning',
                                        'message' => Base::lang('base.file_not_uploaded') . ' (' . Base::lang($fileDetails['label']) . ')'
                                    ];
                                    $arguments['manipulation']['#contentAdd [name="' . $fileName . ($multipleFile ? '[]' : '') . '"]'] = [
                                        'class' => ['is-invalid'],
                                    ];

                                }

                            }

                        } elseif ($requiredFile) {

                            $alerts[] = [
                                'status' => 'warning',
                                'message' => Base::lang('base.file_not_found') . ' (' . Base::lang($fileDetails['label']) . ')'
                            ];
                            $arguments['manipulation']['#contentAdd [name="' . $fileName . ($multipleFile ? '[]' : '') . '"]'] = [
                                'class' => ['is-invalid'],
                            ];

                        } else {

                            $insert[$fileName] = null;
                        }
                    }
                }

                if (! count($files) OR isset($arguments['manipulation']) === false) {

                    $model = new Contents;
                    $insert = $model->insert([
                        'module' => $this->module,
                        'input' => json_encode($insert),
                    ]);

                    if ($insert) {

                        $alerts[] = [
                            'status' => 'success',
                            'message' => Base::lang('base.content_successfully_added')
                        ];
                        $arguments['form_reset'] = true;
                        $arguments['modal_close'] = '#addModal';
                        $arguments['table_reset'] = 'contentsTable';
                        $rollBack = [];

                    } else {

                        $alerts[] = [
                            'status' => 'error',
                            'message' => Base::lang('base.content_add_problem')
                        ];
                    }

                }

            } else {

                $alerts[] = [
                    'status' => 'warning',
                    'message' => Base::lang('base.form_cannot_empty')
                ];
            }

        } else {

            $alerts[] = [
                'status' => 'error',
                'message' => Base::lang('error.module_not_found')
            ];
        }

        if (count($rollBack)) {
            $controller = new FileController($this->get());
            foreach ($rollBack as $fileId) {
                $controller->removeFileWithId($fileId);
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

    public function contentDetail() {

        $id = (int)$this->get('request')->attributes['id'];


        $alerts = [];
        $arguments = [];

        if (isset($this->modules[$this->module]) !== false) {

            $model = new Contents();
            $getContent = $model->select('id, module, input')->where('id', $id)->where('module', $this->module)->get();
            if (! empty($getContent)) {

                $form = $this->prepareModuleForm($this->modules[$this->module]['inputs'], $getContent);
                $arguments['modal_open'] = ['#editModal'];
                $arguments['init'] = '#editModal';
                $arguments['manipulation'] = [
                    '#editModal .modal-body' => [
                        'html'  => $form
                    ]
                ];

            } else {

                $alerts[] = [
                    'status' => 'warning',
                    'message' => Base::lang('base.record_not_found')
                ];
            }

        } else {

            $alerts[] = [
                'status' => 'error',
                'message' => Base::lang('error.module_not_found')
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

    public function contentUpdate() {

        $alerts = [];
        $arguments = [];

        $id = (int)$this->get('request')->attributes['id'];

        $rollBack = [];
        $inputAreas = [];
        $files = [];
        $requiredAreas = [
            'areas' => [], 
            'files' => []
        ];
        if (isset($this->modules[$this->module]) !== false) {

            $module = $this->modules[$this->module];

            // Input area check
            foreach ($module['inputs'] as $name => $detail) {

                if ($name === 'widget') {

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

                        $files[$name] = $detail;
                        extract(Base::input([
                            'current_file_' . $name => 'int'
                        ], $this->get('request')->params));

                    }

                    if (isset($detail['attributes']['required']) !== false AND $detail['attributes']['required'] === 'true') {
                        $requiredAreas[($detail['type'] === 'file' ? 'files' : 'areas')][$name] = true;
                    }
                }
            }

            extract(Base::input($inputAreas, $this->get('request')->params));

            $update = [];
            // Filter all inputs
            foreach ($inputAreas as $inputName => $inputType) {
                
                if (is_array($$inputName)) { // multilingual

                    foreach ($$inputName as $lang => $inputVar) {

                        if (
                            isset($requiredAreas['areas'][$inputName]) === false OR ! empty($inputVar)
                        ) {

                            $update[$inputName][$lang] = $inputVar;

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

                    $update[$inputName] = $$inputName;

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

                // Files
                if (count($files)) {

                    if (! is_dir($path = Base::path('upload')))
                        mkdir($path);

                    if (! is_dir($path .= '/' . $this->module))
                        mkdir($path);

                    foreach ($files as $fileName => $fileDetails) {

                        $requiredFile = false;
                        $multipleFile = false;

                        if (isset($fileDetails['attributes']['required']) !== false AND $fileDetails['attributes']['required']) {
                            $requiredFile = true;
                        }

                        if (isset($fileDetails['attributes']['multiple']) !== false AND $fileDetails['attributes']['multiple']) {
                            $multipleFile = true;
                        }

                        if (isset($this->get('request')->files[$fileName]) !== false) {

                            foreach ($this->get('request')->files[$fileName] as $fileKey => $fileUp) {

                                $maxSize = Base::config('app.upload_max_size');
                                if (isset($fileDetails['external_parameters']['max_size']) !== false AND $fileDetails['external_parameters']['max_size']) {
                                    $maxSize = $fileDetails['external_parameters']['max_size'];
                                }

                                $acceptMime = Base::config('app.upload_accept');
                                if (isset($fileDetails['attributes']['accept']) !== false AND $fileDetails['attributes']['accept']) {
                                    $acceptMime = $fileDetails['attributes']['accept'];
                                }

                                $convertFile = Base::config('app.upload_convert');
                                if (isset($fileDetails['external_parameters']['convert']) !== false AND $fileDetails['external_parameters']['convert']) {
                                    $convertFile = $fileDetails['external_parameters']['convert'];
                                }

                                $fileDimension = ['original' => [0, 0]];
                                if (isset($fileDetails['external_parameters']['size']) !== false AND $fileDetails['external_parameters']['size']) {
                                    $fileDimension = $fileDetails['external_parameters']['size'];
                                }

                                $handle = new Upload($fileUp, Base::lang('lang.iso_code'));
                                if ($handle->uploaded) {

                                    $updateData = [];
                                    $errorOnUpload = false;

                                    $originalFileName = Base::stringShortener(Base::slugGenerator($handle->file_src_name_body), 190, false);

                                    foreach ($fileDimension as $dimensionTag => $dimensionVar) {

                                        $newFileName = $originalFileName . '_' . $dimensionTag;
                                        $handle->file_new_name_body   = $newFileName;

                                        if ($maxSize) $handle->file_max_size = $maxSize;
                                        if ($acceptMime) $handle->allowed = $acceptMime;
                                        if ($convertFile) $handle->image_convert = $convertFile;

                                        if ($quality = Base::config('app.upload_webp_quality')) {
                                            $handle->webp_quality = $quality;
                                        }

                                        if ($quality = Base::config('app.upload_png_quality')) {
                                            $handle->webp_quality = $quality;
                                        }

                                        if ($quality = Base::config('app.upload_jpeg_quality')) {
                                            $handle->webp_quality = $quality;
                                        }

                                        $handle->image_resize           = true;
                                        $handle->image_ratio            = true;
                                        $handle->image_ratio_crop       = true;

                                        if ($dimensionVar[0]) 
                                            $handle->image_x      = $dimensionVar[0];

                                        if ($dimensionVar[1]) 
                                            $handle->image_y      = $dimensionVar[1];
                                        
                                        $handle->process($path);
                                        if ($handle->processed) {
                                           
                                            $url = $this->module . '/' . $handle->file_dst_name_body . '.' . $handle->file_dst_name_ext;
                                            $updateData[$dimensionTag] = $url;

                                        } else {
                                            
                                            $errorOnUpload = $handle->error;
                                            break;
                                            
                                        }
                                    }

                                    $handle->clean();
                                    if ($errorOnUpload === false) {

                                        $alerts[] = [
                                            'status' => 'success',
                                            'message' => Base::lang('base.file_successfully_uploaded') . ' (' . $originalFileName . ')'
                                        ];

                                        $fileId = (new Files)->insert([
                                            'module' => $this->module,
                                            'name' => $originalFileName,
                                            'files' => json_encode($updateData)
                                        ]);

                                        $rollBack[] = $fileId;
                                        if ($multipleFile) $update[$fileName][$fileKey] = $fileId;
                                        else $update[$fileName] = $fileId;

                                    } else {
                                        $alerts[] = [
                                            'status' => 'error',
                                            'message' => Base::lang('base.file_upload_problem') 
                                            . (isset($handle->error) !== false ? ' (' . Base::lang($fileDetails['label']) . ' -> ' . $errorOnUpload . ')' : '')
                                        ];
                                        $arguments['manipulation']['#contentEdit [name="' . $fileName . ($multipleFile ? '[]' : '') . '"]'] = [
                                            'class' => ['is-invalid'],
                                        ];
                                    }

                                } else {

                                    $alerts[] = [
                                        'status' => 'warning',
                                        'message' => Base::lang('base.file_not_uploaded') . ' (' . Base::lang($fileDetails['label']) . ')'
                                    ];
                                    $arguments['manipulation']['#contentEdit [name="' . $fileName . ($multipleFile ? '[]' : '') . '"]'] = [
                                        'class' => ['is-invalid'],
                                    ];

                                }

                            }

                        } elseif ($requiredFile AND !${'current_file_'.$fileName}) {

                            $alerts[] = [
                                'status' => 'warning',
                                'message' => Base::lang('base.file_not_found') . ' (' . Base::lang($fileDetails['label']) . ')'
                            ];
                            $arguments['manipulation']['#contentEdit [name="' . $fileName . ($multipleFile ? '[]' : '') . '"]'] = [
                                'class' => ['is-invalid'],
                            ];

                        } else {

                            $update[$fileName] = ${'current_file_'.$fileName} ? ${'current_file_'.$fileName} : null;
                        }
                    }
                }

                // reassign old ID.
                if (isset($arguments['manipulation']) !== false AND count($arguments['manipulation']) AND isset($requiredAreas['files']) !== false) {

                    
                    foreach ($requiredAreas['files'] as $fileNameKey => $val) {

                        foreach ($arguments['manipulation'] as $manipulationSelector => $manipulationDetails) {
                            
                            if (strpos($manipulationSelector, $fileNameKey) !== false) {

                                if (isset(${'current_file_'.$fileNameKey}) !== false AND ${'current_file_'.$fileNameKey}) {
                                    unset($arguments['manipulation'][$manipulationSelector]);
                                    $update[$fileNameKey] = ${'current_file_'.$fileNameKey};
                                }
                            }
                        }
                    }

                    if (! count($arguments['manipulation']))
                        unset($arguments['manipulation']);

                }

                if (! count($files) OR isset($arguments['manipulation']) === false) {

                    $model = new Contents;
                    $update = $model->where('id', $id)->update([
                        'input' => json_encode($update),
                    ]);

                    if ($update) {

                        $alerts[] = [
                            'status' => 'success',
                            'message' => Base::lang('base.content_successfully_updated')
                        ];
                        $arguments['modal_close'] = '#editModal';
                        $arguments['table_reset'] = 'contentsTable';
                        $rollBack = [];

                    } else {

                        $alerts[] = [
                            'status' => 'error',
                            'message' => Base::lang('base.content_update_problem')
                        ];
                    }

                }

            } else {

                $alerts[] = [
                    'status' => 'warning',
                    'message' => Base::lang('base.form_cannot_empty')
                ];
            }

        } else {

            $alerts[] = [
                'status' => 'error',
                'message' => Base::lang('error.module_not_found')
            ];
        }

        if (count($rollBack)) {
            $controller = new FileController($this->get());
            foreach ($rollBack as $fileId) {
                $controller->removeFileWithId($fileId);
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

    public function contentDelete() {

        $alerts = [];
        $arguments = [];

        $id = (int)$this->get('request')->attributes['id'];
        if (isset($this->modules[$this->module]) !== false) {

            $model = new Contents();
            $getContent = $model->select('id')->where('id', $id)->where('module', $this->module)->get();
            if (! empty($getContent)) {

                $delete = $model->where('id', $id)->delete();

                if ($delete) {

                    $alerts[] = [
                        'status' => 'success',
                        'message' => Base::lang('base.content_successfully_deleted')
                    ];
                    $arguments['table_reset'] = 'contentsTable';

                } else {

                    $alerts[] = [
                        'status' => 'error',
                        'message' => Base::lang('base.content_delete_problem')
                    ];
                }

            } else {

                $alerts[] = [
                    'status' => 'warning',
                    'message' => Base::lang('base.record_not_found')
                ];
            }

        } else {

            $alerts[] = [
                'status' => 'error',
                'message' => Base::lang('error.module_not_found')
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

    public function contentSlugInquiry() {

        extract(Base::input([
            'id'    => 'int',
            'slug'  => 'slug',
            'lang'  => 'nulled_text',
        ], $this->get('request')->params));

        
        $alerts = [];
        $arguments = [];

        if (isset($this->modules[$this->module]) !== false) {

            if (! is_null($slug)) {

                $multilanguage = $lang ? $lang : false;
                $whereQuery = 'JSON_UNQUOTE(JSON_EXTRACT(input, \'$.slug'.($lang ? '.'.$lang : '').'\'))';

                $model = new Contents();

                $checkNum = 1;
                while (1) {
                    
                    $slugCheck = $slug . ($checkNum > 1 ? '-' . $checkNum : '');

                    $recordCheck = $model->select('id')
                        ->where('module', $this->module)
                        ->where($whereQuery, $slugCheck);

                    if ($id > 0) {
                        $recordCheck->notWhere('id', $id);
                    }

                    $recordCheck = $recordCheck->get();

                    if (empty($recordCheck)) {
                        break;
                    }
                    $checkNum++;

                }

                $arguments['manipulation'] = [
                    '#content'.($id ? 'Edit' : 'Add').' #content_'.($id ? 'edit' : 'add').'_slug' . $lang => [
                        'attribute'  => [
                            'value' => $slugCheck
                        ]
                    ]
                ];
                

            } else {

                $alerts[] = [
                    'status' => 'error',
                    'message' => Base::lang('error.missing_or_incorrect_parameter')
                ];
            }

        } else {

            $alerts[] = [
                'status' => 'error',
                'message' => Base::lang('error.module_not_found')
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

    public function extractWidgetData($moduleName, $moduleInputs, $ids) {

        $externalSelectColumns = [];
        $moduleContents = [];

        $model = new Contents;

        foreach ($moduleInputs as $selectCol => $colAttributes) {

            if ($selectCol === 'widget') {

                foreach ($colAttributes as $moduleName => $moduleDetails) {
                    $externalSelectColumns[] = '(JSON_UNQUOTE(JSON_EXTRACT(input, \'$.'.$moduleName.'\'))) AS ' . $moduleName;
                }

            } else {

                $multilanguage = false;
                if (isset($colAttributes['multilanguage']) !== false AND $colAttributes['multilanguage']) {
                    $multilanguage = true;
                }
                $externalSelectColumns[] = 'JSON_UNQUOTE(JSON_EXTRACT(input, \'$.'.$selectCol.($multilanguage ? '.'.Base::lang('lang.code') : '').'\')) AS ' 
                . $selectCol;
                if (isset($colAttributes['type']) !== false AND $colAttributes['type'] === 'file') {
                    $externalSelectColumns[] = '(SELECT files FROM files WHERE id = '.$selectCol.') AS ' . $selectCol . '_src';
                }

            }
        }
        $externalSelectColumns = implode(', ', $externalSelectColumns);
        
        $ids = strpos($ids, ',') !== false ? explode(',', $ids) : [$ids];

        foreach ($ids as $id) {

            $getData = $model->select($externalSelectColumns)
                ->where('module', $moduleName);

            if ($id != 0)
                $getData->where('id', $id);

            $getData = $getData->getAll();

            foreach ($getData as $index => $getDataDetail) {
                // Extract files as object
                foreach ($getDataDetail as $key => $value) {
                    if (strpos($key, '_src') !== false) {
                        try {
                            $getData[$index]->{$key} = json_decode($getData[$index]->{$key});
                            foreach ($getData[$index]->{$key} as $fileParam => $fileUrl) {
                                $getData[$index]->{$key}->$fileParam = Base::base('upload/' . $fileUrl);
                            }
                        } catch (Exception $e) {
                            $getData[$index]->{$key} = $getData[$index]->{$key};
                        }
                    }
                }
            }
            
            $moduleContents = array_merge($moduleContents, $getData);
        }

        return $moduleContents;

    }

    public function extractContentData() {

        $return = null;

        foreach ($this->modules as $moduleKey => $moduleDetail) {

            $extractType = isset($this->get('request')->attributes) !== false ? 'detail' : 'listing';

            if (
                isset($moduleDetail['routes'][$extractType][$this->get()->lang]) !== false
            ) {
                $attributes = isset($this->get('request')->attributes) !== false ? $this->get('request')->attributes : [];
                $details = $moduleDetail['routes'][$extractType][$this->get()->lang];
                $route = trim($details[1], '/');

                if ($route === $this->get()->endpoint) {

                    $this->module = $moduleKey;
                    $selectColumns = [];
                    $externalColumns = [];
                    foreach ($moduleDetail['inputs'] as $selectCol => $colAttributes) {

                        if ($selectCol === 'widget') {

                            foreach ($colAttributes as $moduleName => $moduleDetails) {
                                $externalColumns[] = $moduleName;
                                $selectColumns[] = '(JSON_UNQUOTE(JSON_EXTRACT(input, \'$.'.$moduleName.'\'))) AS ' . $moduleName;
                            }

                        } else {

                            $multilanguage = false;
                            if (isset($colAttributes['multilanguage']) !== false AND $colAttributes['multilanguage']) {
                                $multilanguage = true;
                            }
                            $selectColumns[] = 'JSON_UNQUOTE(JSON_EXTRACT(input, \'$.'.$selectCol.($multilanguage ? '.'.Base::lang('lang.code') : '').'\')) AS ' 
                            . $selectCol;
                            if (isset($colAttributes['type']) !== false AND $colAttributes['type'] === 'file') {
                                $selectColumns[] = '(SELECT files FROM files WHERE id = '.$selectCol.') AS ' . $selectCol . '_src';
                            }
                        }
                    }

                    $selectColumns = implode(', ', $selectColumns);

                    $model = new Contents;
                    $contentDetails = $model->select($selectColumns)
                        ->where('module', $this->module);

                    foreach ($attributes as $column => $columnVal) {

                        $multilanguage = false;
                        if (isset($moduleDetail['inputs'][$column]['multilanguage']) !== false AND $moduleDetail['inputs'][$column]['multilanguage']) {
                            $multilanguage = true;
                        }
                        $contentDetails
                            ->where('JSON_UNQUOTE(JSON_EXTRACT(input, \'$.'.$column.($multilanguage ? '.'.Base::lang('lang.code') : '').'\'))', $columnVal);
                    }

                    $contentDetails = $contentDetails->getAll();

                    if (! empty($contentDetails)) {

                        // Extract files as object
                        foreach ($contentDetails as $index => $val) {
                            foreach ($val as $key => $value) {
                                if (strpos($key, '_src') !== false) {
                                    try {
                                        $val->{$key} = json_decode($val->{$key});
                                        foreach ($contentDetails[$index]->{$key} as $fileParam => $fileUrl) {
                                            $val->{$key}->$fileParam = Base::base('upload/' . $fileUrl);
                                        }
                                    } catch (Exception $e) {
                                        $val->{$key} = $val->{$key};
                                    }
                                }
                            }
                            $contentDetails[$index] = $val;
                        }
                        
                        if (count($externalColumns)) {

                            foreach ($externalColumns as $module) {
                                
                                foreach ($contentDetails as $index => $val) {

                                    $moduleContents = [];
                                    
                                    if (isset($this->modules[$module]['inputs']) !== false) {

                                        $widget = $this->extractWidgetData(
                                            $module,
                                            $this->modules[$module]['inputs'],
                                            $val->{$module}
                                        );

                                        $moduleContents = array_merge($moduleContents, $widget);
                                    }
                                    
                                    $contentDetails[$index]->{$module} = $moduleContents;
                                }
                            }
                        }

                        $return = [
                            'content_details' => $contentDetails,
                            'module_detail' => $moduleDetail,
                            'module_key' => $moduleKey,
                        ];
                    }
                    break;
                }
            }
        }

        return $return;

    }

    public function contentListPage() {

        $extract = $this->extractContentData();

        if ($extract) {

            $arguments = [];
            $title = Base::lang($extract['module_detail']['name']);
            $arguments['title'] = $title;
            $arguments['detail'] = $extract['content_details'];
            $arguments['moduleDetail'] = $extract['module_detail'];

            if (isset($extract['module_detail']['routes']['description']['listing']) !== false) {
                $arguments['description'] = Base::lang($extract['module_detail']['routes']['description']['listing']);
            }

            return [
                'status' => true,
                'statusCode' => 200,
                'arguments' => $arguments,
                'view' => $extract['module_detail']['routes']['view']['listing']
            ];

        } else {

            return [
                'status' => false,
                'statusCode' => 404,
                'arguments' => [
                    'error' => 404,
                    'output' => Base::lang('error.page_not_found')
                ],
                'view' => ['error', 'error']
            ];
        }

    }

    public function contentDetailPage() {

        $extract = $this->extractContentData();

        if ($extract) {

            $arguments = [];
            $title = Base::lang($extract['module_detail']['name']);
            
            $arguments['detail'] = $extract['content_details'][0];

            if (isset($contentDetails->{'description'}) !== false) {
                $arguments['description'] = $contentDetails->{'description'};
            } elseif (isset($contentDetails->{'content'}) !== false) {
                $arguments['description'] = trim(strip_tags(htmlspecialchars_decode($contentDetails->{'content'})));
            } elseif (isset($extract['module_detail']['routes']['description']['listing']) !== false) {
                $arguments['description'] = Base::lang($extract['module_detail']['routes']['description']['listing']);
            }

            if (isset($arguments['detail']->{'title'}) !== false) {
                $title = $arguments['detail']->{'title'} . ' | ' . $title;
            }

            $arguments['title'] = $title;
            $arguments['moduleDetail'] = $extract['module_detail'];

            return [
                'status' => true,
                'statusCode' => 200,
                'arguments' => $arguments,
                'view' => $extract['module_detail']['routes']['view']['detail']
            ];

        } else {

            return [
                'status' => false,
                'statusCode' => 404,
                'arguments' => [
                    'error' => 404,
                    'output' => Base::lang('error.page_not_found')
                ],
                'view' => ['error', 'error']
            ];
        }
    }

}