<?php

/**
 * @package KN
 * @subpackage Controller
 */

declare(strict_types=1);

namespace KN\Controllers;

use KN\Core\Controller;
use KN\Model\Contents;
use KN\Helpers\Base;
use KN\Helpers\HTML;
use KN\Helpers\KalipsoTable;
use \Verot\Upload\Upload;
use KN\Controllers\FileController;


final class ContentController extends Controller {

    public function __construct($container) {

        parent::__construct($container);

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
            $fileController = new FileController($this->get());
        }

        $moduleForm = '';
        $languages = Base::config('app.available_languages');
        $multilanguage = false;
        foreach ($module as $name => $input) {

            $col = isset($input['col']) !== false ? $input['col'] : 'col-12 col-md-6';

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

                            if (in_array($attribute, ['required', 'selected', 'checked']) !== false) 
                                $attributes .= $attribute . ' ';
                            else
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
                            <select class="form-select" '.$attributes.'name="' . $key . '" id="' . $idPrefix . '_' . $key . '">
                                '.(! $requiredWidget ?
                                 '<option value=""></option>' : 
                                 '<option value=""'.$allSelected.'>' . Base::lang('base.all') . '</option>').'
                                '.$options.'
                            </select>
                            <label for="' . $idPrefix . '_' . $key . '">' . Base::lang($widget['label']) . $requiredBadge . '</label>
                        </div>
                    </div>';

                }

            } else {

                if ($input['type'] === 'url_widget') { // URL Widget

                    $menuController = new MenuController($this->get());
                    $menuWidgetData = [
                        'menu_options' => $menuController->menuOptionsAsHTML(
                            (isset($fillDatas->{$name}->dynamic_link->module) !== false ? $fillDatas->{$name}->dynamic_link->module : null)
                        ),
                        'name' => $name,
                        'label' => Base::lang($input['label']),
                        'values' => (isset($fillDatas->{$name}) !== false ? $fillDatas->{$name} : [])
                    ];
                    if (isset($fillDatas->{$name}->dynamic_link->module) !== false AND isset($fillDatas->{$name}->dynamic_link->parameter) !== false) {
                        $menuWidgetData['module_parameters'] = $menuController->getMenuParameters(
                            $fillDatas->{$name}->dynamic_link->module, 
                            $fillDatas->{$name}->dynamic_link->parameter
                        );
                    }
                    
                    
                    $moduleForm .= HTML::menuModuleUrlWidget($menuWidgetData);

                } else {

                    $nameSubfix = [null];
                    if (isset($input['multilanguage']) !== false AND $input['multilanguage']) {

                        $nameSubfix = $languages;
                        $moduleForm .= '
                        <div class="' . $col . ' kn-multilang-content">
                            <div class="kn-multilang-content-switch">
                                <div class="nav nav-pills" id="' . $idPrefix . '_'.$name.'-tablist" role="tablist" aria-orientation="vertical">';
                                foreach ($languages as $i => $lang) {
                                    $moduleForm .= '
                                    <button class="nav-link'.($i===0 ? ' active' : '').'" id="' . $idPrefix . '_'.$name.'-tab-'.$lang.'" data-bs-toggle="pill" data-bs-target="#' . $idPrefix . '_'.$name.'-'.$lang.'" type="button" role="tab" aria-controls="' . $idPrefix . '_'.$name.'-'.$lang.'" aria-selected="'.($i===0 ? 'true' : 'false').'">
                                        '.Base::lang('langs.'.$lang).'
                                    </button>';
                                }
                        $moduleForm .= '
                                </div>
                            </div>
                            <div class="tab-content">';
                                    
                    }

                    $col = isset($input['col']) !== false ? $input['col'] : 'col-12 col-md-6';

                    foreach ($nameSubfix as $i => $lang) {
                        
                        $attributes = '';
                        if (isset($input['attributes']) !== false) {
                            foreach ($input['attributes'] as $attribute => $val) {
                                if (in_array($attribute, ['required', 'checked', 'selected']) !== false)
                                    $attributes .= $attribute . ' ';
                                else
                                    $attributes .= $attribute . '="'.$val.'" ';
                                
                            }
                        }

                        $currentVal = null;
                        $inputName = is_null($lang) ? $name : $name.'['.$lang.']';
                        // multilingual
                        if (! is_null($lang)) {
                            $col = 'col';
                            $moduleForm .= '
                            <div class="tab-pane fade'.($i === 0 ? ' show active' : '').'" id="' . $idPrefix . '_'.$name.'-'.$lang.'" role="tabpanel" aria-labelledby="' . $idPrefix . '_'.$name.'-tab-'.$lang.'">';

                            if (isset($fillDatas->{$name}->{$lang}) !== false) {
                                $currentVal = $fillDatas->{$name}->{$lang};
                            }

                        } else {
                            
                            if (isset($fillDatas->{$name}) !== false) {
                                $currentVal = $fillDatas->{$name};
                            }
                        }

                        $requiredBadge = '';
                        if (isset($input['attributes']['required']) !== false AND $input['attributes']['required']) {
                            $requiredBadge = ' <sup class="text-danger">*</sup>';
                        }

                        switch ($input['type']) {
                            case 'input':
                            case 'color':
                            case 'number':

                                if (! is_null($currentVal)) {
                                    $attributes .= 'value="'.$currentVal.'" ';
                                }
                                $attributes .= 'data-kn-lang="'.$lang.'" ';
                                if (isset($id) !== false) $attributes .= 'data-kn-id="'.$id.'" ';

                                $moduleForm .= '
                                <div class="'.$col.'">
                                    <div class="form-floating">
                                        <input type="' . ($input['type'] == 'input' ? 'text' : $input['type']) . '" class="form-control" '.$attributes.'name="' . $inputName . '" id="' . $idPrefix . '_' . $name . $lang . '" placeholder="' . Base::lang($input['label']) . '" />
                                        <label for="' . $idPrefix . '_' . $name . $lang . '">' . Base::lang($input['label']) . $requiredBadge . '</label>
                                    </div>
                                </div>';
                                break;

                            case 'file':

                                $externalBadge = '';
                                if (! is_null($currentVal) AND is_array($currentVal) AND count($currentVal)) {

                                    $getFiles = $fileController->getFilesInId($currentVal);
                                    
                                    if (! empty($getFiles)) {

                                        $externalBadge = '<div class="image-group">';
                                        foreach ($getFiles as $fileIndex => $getFile) {
                                            
                                            $getFile->files = json_decode($getFile->files);
                                            $src = Base::base('upload/' . (isset($getFile->files->sm) !== false ? $getFile->files->sm : $getFile->files->original));
                                            $href = Base::base('upload/' . $getFile->files->original);
                                            $externalBadge .= '<a href="' . $href . '" target="_blank" data-target="current_file_delete_' . $inputName . $fileIndex . '">
                                                <img class="table-image" src="' . $src . '" />
                                                <button href="javascript:;" 
                                                    data-kn-again="'.Base::lang('base.are_you_sure').'" 
                                                    data-kn-action="manipulation"
                                                    data-kn-manipulation=\'' . json_encode(
                                                        [   'manipulation' => [
                                                                '[data-target="current_file_delete_' . $inputName . $fileIndex . '"]' => [
                                                                    'remove_element' => true
                                                                ]
                                                            ]
                                                        ]) . '\'
                                                    >
                                                    ' . Base::lang('base.delete') . '
                                                </button>
                                                <input type="hidden" name="current_file_' . $inputName . '[]" value="' . $getFile->id . '" />
                                            </a>';

                                        }
                                        $externalBadge .= '</div>';
                                    }
                                }

                                $moduleForm .= '
                                <div class="'.$col.'">
                                    <div class="">
                                        <label for="' . $idPrefix . '_' . $name . $lang . '" class="form-label small text-muted m-0">' . $externalBadge . Base::lang($input['label']) . $requiredBadge . '</label>
                                        <input class="form-control" '.$attributes.'name="' . $inputName . (isset($input['attributes']['multiple']) !== false ? '[]' : '') . '" id="' . $idPrefix . '_' . $name . $lang . '" type="file">
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
                                        <select class="form-select" '.$attributes.'name="' . $inputName . '" id="' . $idPrefix . '_' . $name . $lang . '">
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
                                        <textarea class="form-control" '.$attributes.'name="' . $inputName . '" id="' . $idPrefix . '_' . $name . $lang . '" placeholder="' . Base::lang($input['label']) . '" style="min-height: 100px">' . $currentVal . '</textarea>
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
                        </div>';
                    }
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

            $fileController = new FileController($this->get());
            $upload = $fileController->directUpload($this->module, $files);

            if (count($upload)) {

                $url = array_shift($upload);
                $url = $url['original'];
                           
                $alerts[] = [
                    'status' => 'success',
                    'message' => Base::lang('base.file_successfully_uploaded')
                ];

                $arguments['editor_upload'][] = $this->get()->url('upload/' . $url);

            } else {
                
                $alerts[] = [
                    'status' => 'error',
                    'message' => Base::lang('base.file_upload_problem') 
                ];
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

                    if ($detail['type'] === 'input' OR $detail['type'] === 'url_widget' OR $detail['type'] === 'textarea' OR $detail['type'] === 'select') {

                        $inputAreas[$name] = 'nulled_text';

                    } elseif ($detail['type'] === 'number') {

                        $inputAreas[$name] = 'int';

                    } elseif ($detail['type'] === 'editor') {

                        $inputAreas[$name] = 'nulled_html';

                    } elseif ($detail['type'] === 'color') {

                        $inputAreas[$name] = 'color';

                    } elseif ($detail['type'] === 'file') {

                        $files[$name][] = $detail;

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

                    $fileController = new FileController($this->get());

                    foreach ($files as $fileName => $detail) {

                        foreach ($detail as $k => $fileDetails) {
                        
                            $requiredFile = false;
                            $multipleFile = false;

                            if (isset($fileDetails['attributes']['required']) !== false AND $fileDetails['attributes']['required']) {
                                $requiredFile = true;
                            }

                            if (isset($fileDetails['attributes']['multiple']) !== false AND $fileDetails['attributes']['multiple']) {
                                $multipleFile = true;
                            }

                            if (isset($this->get('request')->files[$fileName]) !== false) {

                                $uploadParameters = [];

                                if (isset($fileDetails['external_parameters']['max_size']) !== false AND $fileDetails['external_parameters']['max_size']) {
                                    $uploadParameters['max_size'] = $fileDetails['external_parameters']['max_size'];
                                }

                                if (isset($fileDetails['attributes']['accept']) !== false AND $fileDetails['attributes']['accept']) {
                                    $uploadParameters['accept_mime'] = $fileDetails['attributes']['accept'];
                                }

                                if (isset($fileDetails['external_parameters']['convert']) !== false AND $fileDetails['external_parameters']['convert']) {
                                    $uploadParameters['convert'] = $fileDetails['external_parameters']['convert'];
                                }

                                if (isset($fileDetails['external_parameters']['size']) !== false AND $fileDetails['external_parameters']['size']) {
                                    $uploadParameters['dimension'] = $fileDetails['external_parameters']['size'];
                                }

                                $upload = $fileController
                                    ->directUpload(
                                        $this->module, 
                                        $this->get('request')->files[$fileName], 
                                        $uploadParameters
                                    );

                                if (count($upload)) {

                                    foreach ($upload as $uploadId => $uploadDetails) {
                                        $insert[$fileName][] = $uploadId;
                                        $rollBack[] = $uploadId;
                                    }
                                    
                                    $alerts[] = [
                                        'status' => 'success',
                                        'message' => Base::lang('base.file_successfully_uploaded')
                                    ];

                                } else {

                                    $alerts[] = [
                                        'status' => 'error',
                                        'message' => Base::lang('base.file_upload_problem') 
                                        . ' (' . Base::lang($fileDetails['label']) . ')'
                                    ];
                                    $arguments['manipulation']['#contentAdd [name="' . $fileName . ($multipleFile ? '[]' : '') . '"]'] = [
                                        'class' => ['is-invalid'],
                                    ];
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

                                $insert[$fileName] = [];
                            }
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

        if (isset($rollBack) !== false AND count($rollBack)) {
            foreach ($rollBack as $fileId) {
                $fileController->removeFileWithId($fileId);
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

                    if ($detail['type'] === 'input' OR $detail['type'] === 'url_widget' OR $detail['type'] === 'textarea' OR $detail['type'] === 'select') {

                        $inputAreas[$name] = 'nulled_text';

                    } elseif ($detail['type'] === 'number') {

                        $inputAreas[$name] = 'int';

                    } elseif ($detail['type'] === 'editor') {

                        $inputAreas[$name] = 'nulled_html';

                    } elseif ($detail['type'] === 'color') {

                        $inputAreas[$name] = 'color';

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

                    $fileController = new FileController($this->get());

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

                            $uploadParameters = [];

                            if (isset($fileDetails['external_parameters']['max_size']) !== false AND $fileDetails['external_parameters']['max_size']) {
                                $uploadParameters['max_size'] = $fileDetails['external_parameters']['max_size'];
                            }

                            if (isset($fileDetails['attributes']['accept']) !== false AND $fileDetails['attributes']['accept']) {
                                $uploadParameters['accept_mime'] = $fileDetails['attributes']['accept'];
                            }

                            if (isset($fileDetails['external_parameters']['convert']) !== false AND $fileDetails['external_parameters']['convert']) {
                                $uploadParameters['convert'] = $fileDetails['external_parameters']['convert'];
                            }

                            if (isset($fileDetails['external_parameters']['size']) !== false AND $fileDetails['external_parameters']['size']) {
                                $uploadParameters['dimension'] = $fileDetails['external_parameters']['size'];
                            }

                            $upload = $fileController
                                ->directUpload(
                                    $this->module, 
                                    $this->get('request')->files[$fileName], 
                                    $uploadParameters
                                );

                            if (count($upload)) {

                                foreach ($upload as $uploadId => $uploadDetails) {
                                    $update[$fileName][] = $uploadId;
                                    $rollBack[] = $uploadId;
                                }
                                
                                $alerts[] = [
                                    'status' => 'success',
                                    'message' => Base::lang('base.file_successfully_uploaded')
                                ];

                            } else {

                                $alerts[] = [
                                    'status' => 'error',
                                    'message' => Base::lang('base.file_upload_problem') 
                                    . ' (' . Base::lang($fileDetails['label']) . ')'
                                ];
                                $arguments['manipulation']['#contentEdit [name="' . $fileName . ($multipleFile ? '[]' : '') . '"]'] = [
                                    'class' => ['is-invalid'],
                                ];
                            }

                            if ($multipleFile AND is_array(${'current_file_'.$fileName})) {
                                $update[$fileName] = array_merge(
                                    ${'current_file_'.$fileName}, 
                                    (isset($update[$fileName]) !== false ? $update[$fileName] : [])
                                );
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

                            $update[$fileName] = ${'current_file_'.$fileName} ? ${'current_file_'.$fileName} : [];
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

                if (isset($colAttributes['type']) !== false AND $colAttributes['type'] === 'file') {
                    $externalSelectColumns[] = '
                    REPLACE(
                        REPLACE(
                            REPLACE(
                                REPLACE(
                                    IFNULL(JSON_EXTRACT(input, \'$.'.$selectCol.($multilanguage ? '.'.Base::lang('lang.code') : '').'\'), ""),
                                    " ",
                                    ""
                                ),
                                "\"",
                                ""
                            ),
                            "]",
                            ""
                        ),
                        "[",
                        ""
                    ) AS ' . $selectCol;
                    $externalSelectColumns[] = '(SELECT JSON_ARRAYAGG(files) AS files FROM files WHERE FIND_IN_SET(id, '.$selectCol.')) AS ' . $selectCol . '_src';
                } else {
                    $externalSelectColumns[] = 'JSON_UNQUOTE(JSON_EXTRACT(input, \'$.'.$selectCol.($multilanguage ? '.'.Base::lang('lang.code') : '').'\')) AS ' 
                    . $selectCol;
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

            // Extract files as object
            $getData = $this->fillFileLinks($getData);
            
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
                            
                            if (isset($colAttributes['type']) !== false AND $colAttributes['type'] === 'file') {
                                $selectColumns[] = '
                                REPLACE(
                                    REPLACE(
                                        REPLACE(
                                            REPLACE(
                                                IFNULL(JSON_EXTRACT(input, \'$.'.$selectCol.($multilanguage ? '.'.Base::lang('lang.code') : '').'\'), ""),
                                                " ",
                                                ""
                                            ),
                                            "\"",
                                            ""
                                        ),
                                        "]",
                                        ""
                                    ),
                                    "[",
                                    ""
                                ) AS ' . $selectCol;
                                $selectColumns[] = '(SELECT JSON_ARRAYAGG(files) AS files FROM files WHERE FIND_IN_SET(id, '.$selectCol.')) AS ' . $selectCol . '_src';
                            } else {
                                $selectColumns[] = 'JSON_UNQUOTE(JSON_EXTRACT(input, \'$.'.$selectCol.($multilanguage ? '.'.Base::lang('lang.code') : '').'\')) AS ' 
                                . $selectCol;
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
                        $contentDetails = $this->fillFileLinks($contentDetails);
                        
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

    public function fillFileLinks($originalData = []) {

        foreach ($originalData as $index => $data) {

            foreach ($data as $col => $var) {

                if (strpos($col, '_src') !== false) {

                    $data->{$col} = @json_decode($data->{$col});
                    if (is_array($data->{$col})) {

                        foreach ($data->{$col} as $fileIndex => $fileDetail) {

                            foreach ($fileDetail as $fileSize => $fileLink) {
                                $data->{$col}[$fileIndex]->{$fileSize} = Base::base('upload/' . $fileLink);
                            }
                        }
                    }
                }
            }

            $originalData[$index] = $data;
        }
        return $originalData;

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