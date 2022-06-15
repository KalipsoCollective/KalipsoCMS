<?php

/**
 * @package KN
 * @subpackage Controller
 */

declare(strict_types=1);

namespace KN\Controllers;

use KN\Core\Controller;
use KN\Model\Forms;
use KN\Helpers\Base;
use KN\Helpers\HTML;
use KN\Helpers\KalipsoTable;
use \Verot\Upload\Upload;
use KN\Controllers\FileController;


final class FormController extends Controller {

    public function __construct($container) {

        parent::__construct($container);

    }

    public function forms() {

        $title = Base::lang('base.forms');
        $description = Base::lang('base.forms_message');

        if (isset($this->forms[$this->form]) !== false) {

            $form = $this->forms[$this->form];
            $formName = Base::lang($form['name']);
            $title = $formName . ' | ' . $title;
            $description = Base::lang($form['description']);
            $icon = isset($form['icon']) !== false ? $form['icon'] : 'ti ti-folders';

            $arguments = [
                'title' => $title,
                'formName' => $formName,
                'formDatas' => $form,
                'icon' => $icon,
                'description' => $description,
                'form' => $this->form,
                'forms' => $this->forms,
                'modules' => $this->modules,
                'languages' => Base::config('app.available_languages'),
                'prepareForm' => $this->prepareForm($form['inputs']),
            ];

            return [
                'status' => true,
                'statusCode' => 200,
                'arguments' => $arguments,
                'view' => ['admin.forms', 'admin']
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

    public function getFormDatas($form = null) {

        $model = (new Forms())->where('form', $form);

        return $model->getAll();

    }

    public function getModuleDatas($module = null) {

        return (new ContentController($this->get()))->getModuleDatas($module);
    }

    public function getForm($id = 0) {

        $return = null;
        if ($id) {
            $return = (new Forms())->where('id', $id)->get();
        }
        return $return;

    }

    public function prepareForm($formPart, $fill = null) {

        $idPrefix = 'form_add';
        if (! is_null($fill)) {
            $fillDatas = json_decode($fill->input);
            $id = $fill->id;
            $idPrefix = 'form_edit';
            $fileController = new FileController($this->get());
        }

        $form = '';
        foreach ($formPart as $name => $input) {

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

                    $form .= '
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

                $col = isset($input['col']) !== false ? $input['col'] : 'col-12 col-md-6';
                    
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
                $inputName = $name;
                // multilingual
                if (isset($fillDatas->{$name}) !== false) {
                    $currentVal = $fillDatas->{$name};
                }

                $requiredBadge = '';
                if (isset($input['attributes']['required']) !== false AND $input['attributes']['required']) {
                    $requiredBadge = ' <sup class="text-danger">*</sup>';
                }

                switch ($input['type']) {
                    case 'input':
                    case 'color':
                    case 'number':
                    case 'email':
                    case 'tel':

                        if (! is_null($currentVal)) {
                            $attributes .= 'value="'.$currentVal.'" ';
                        }
                        if (isset($id) !== false) $attributes .= 'data-kn-id="'.$id.'" ';

                        $form .= '
                        <div class="'.$col.'">
                            <div class="form-floating">
                                <input type="' . ($input['type'] == 'input' ? 'text' : $input['type']) . '" class="form-control" '.$attributes.'name="' . $inputName . '" id="' . $idPrefix . '_' . $name . '" placeholder="' . Base::lang($input['label']) . '" />
                                <label for="' . $idPrefix . '_' . $name . '">' . Base::lang($input['label']) . $requiredBadge . '</label>
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

                        $form .= '
                        <div class="'.$col.'">
                            <div class="">
                                <label for="' . $idPrefix . '_' . $name . '" class="form-label small text-muted m-0">' . $externalBadge . Base::lang($input['label']) . $requiredBadge . '</label>
                                <input class="form-control" '.$attributes.'name="' . $inputName . (isset($input['attributes']['multiple']) !== false ? '[]' : '') . '" id="' . $idPrefix . '_' . $name . '" type="file">
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

                        $form .= '
                        <div class="'.$col.'">
                            <div class="form-floating">
                                <select class="form-select" '.$attributes.'name="' . $inputName . '" id="' . $idPrefix . '_' . $name . '">
                                    '.$options.'
                                </select>
                                <label for="' . $idPrefix . '_' . $name . '">' . Base::lang($widget['label']) . $requiredBadge . '</label>
                            </div>
                        </div>';
                        break;

                    case 'textarea':
                        $form .= '
                        <div class="'.$col.'">
                            <div class="form-floating">
                                <textarea class="form-control" '.$attributes.'name="' . $inputName . '" id="' . $idPrefix . '_' . $name . '" placeholder="' . Base::lang($input['label']) . '" style="min-height: 100px">' . $currentVal . '</textarea>
                                <label for="' . $idPrefix . '_' . $name . '">' . Base::lang($input['label']) . $requiredBadge . '</label>
                            </div>
                        </div>';
                        break;

                    case 'editor':
                        $form .= '
                        <div class="'.$col.'">
                            <div data-kn-toggle="editor" class="editor" data-options=\'' . json_encode(['placeholder'=>Base::lang($input['label'])])  . '\' data-name="' . $inputName . '" data-module="general">' . htmlspecialchars_decode((string) $currentVal) . '</div>
                        </div>';
                        break;
                }
            }
        }

        if (! is_null($fill)) {
            $statusOptions = '';
            foreach (['pending', 'in_action', 'completed'] as $status) {
                $statusOptions .= '<option value="' . $status . '"'.($fill->status === $status ? ' selected' : '').'>' . Base::lang('base.' . $status) . '</option>';
            }

            $form .= '
            <div class="col-12 col-md-3 ms-auto">
                <div class="form-floating">
                    <select class="form-select" required name="status" id="' . $idPrefix . '_status">
                        ' . $statusOptions . '
                    </select>
                    <label for="' . $idPrefix . '_status">' . Base::lang('base.status') . '</label>
                </div>
            </div>';
        }

        $form = '
        <form class="row g-2" data-kn-form id="' . (isset($id) !== false ? 'formEdit' : 'formAdd') . '" method="post" action="'.$this->get()->url((isset($id) !== false ? 'management/forms/' : 'form/') . $this->form . '/' . (isset($id) !== false ? $id . '/update' : 'add')).'">
            <div class="form-loader">
                <div class="spinner-border text-light" role="status">
                    <span class="visually-hidden">'.Base::lang('base.loading').'</span>
                </div>
            </div>
            <div class="col-12 form-info">
            </div>'
            . $form .
        '</form>';


        return $form;

    }

    public function formList() {

        $container = $this->get();
        $formName = $this->form;
        if (isset($this->forms[$formName]) !== false) {

            $form = $this->forms[$formName];

            $tables = $form['table'];
            $tables['action'] = [
                'exclude' => true,
                'formatter' => function($row) use ($container, $formName) {

                    $buttons = '';
                    if ($container->authority('management/forms/:form/:id')) {
                        $buttons .= '
                        <button type="button" class="btn btn-light" 
                            data-kn-action="'.$this->get()->url('/management/forms/' . $formName . '/' . $row->id ).'">
                            ' . Base::lang('base.view') . '
                        </button>';
                    }

                    if ($container->authority('management/forms/:form/:id/delete')) {
                        $buttons .= '
                        <button type="button" class="btn btn-danger" 
                            data-kn-again="'.Base::lang('base.are_you_sure').'" 
                            data-kn-action="'.$this->get()->url('/management/forms/' . $formName . '/' . $row->id . '/delete').'">
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
                ->db((new Forms)->pdo)
                ->from($form['from'])
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

    public function formAdd() {

        $alerts = [];
        $arguments = [];

        $rollBack = [];
        $inputAreas = [];
        $files = [];
        $requiredAreas = [
            'areas' => [], 
            'files' => []
        ];

        if (isset($this->forms[$this->form]) !== false) {

            $form = $this->forms[$this->form];

            // Input area check
            foreach ($form['inputs'] as $name => $detail) {

                if ($name === 'widget') {

                    foreach ($detail as $name => $widgetDetail) {
                        
                        $inputAreas[$name] = 'int';
                        if (isset($detail['attributes']['required']) !== false AND $detail['attributes']['required'] === 'true') {
                            $requiredAreas['areas'][$name] = true;
                        }

                    }

                } else {

                    if ($detail['type'] === 'input' OR $detail['type'] === 'url_widget' OR $detail['type'] === 'email' OR $detail['type'] === 'tel' OR $detail['type'] === 'textarea' OR $detail['type'] === 'select') {

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
                
                if (isset($requiredAreas['areas'][$inputName]) === false OR ! empty($$inputName)) {

                    $insert[$inputName] = $$inputName;

                } else {

                    if ($inputType === 'nulled_html') {
                        $arguments['manipulation']['#formAdd [data-name="' . $inputName . '"]'] = [
                            'class' => ['border', 'border-1', 'border-danger'],
                        ];
                    } else {
                        $arguments['manipulation']['#formAdd [name="' . $inputName . '"]'] = [
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
                                        $this->form, 
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
                                    $arguments['manipulation']['#formAdd [name="' . $fileName . ($multipleFile ? '[]' : '') . '"]'] = [
                                        'class' => ['is-invalid'],
                                    ];
                                }

                            } elseif ($requiredFile) {

                                $alerts[] = [
                                    'status' => 'warning',
                                    'message' => Base::lang('base.file_not_found') . ' (' . Base::lang($fileDetails['label']) . ')'
                                ];
                                $arguments['manipulation']['#formAdd [name="' . $fileName . ($multipleFile ? '[]' : '') . '"]'] = [
                                    'class' => ['is-invalid'],
                                ];

                            } else {

                                $insert[$fileName] = [];
                            }
                        }
                    }
                }

                if (! count($files) OR isset($arguments['manipulation']) === false) {

                    $model = new Forms;
                    $insert = $model->insert([
                        'form' => $this->form,
                        'input' => json_encode($insert),
                    ]);

                    if ($insert) {

                        $alerts[] = [
                            'status' => 'success',
                            'message' => Base::lang('base.form_successfully_added')
                        ];
                        $arguments['form_reset'] = true;
                        $arguments['modal_close'] = '#addModal';
                        // $arguments['table_reset'] = 'contentsTable';
                        $rollBack = [];

                    } else {

                        $alerts[] = [
                            'status' => 'error',
                            'message' => Base::lang('base.form_add_problem')
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

    public function formDetail() {

        $id = (int)$this->get('request')->attributes['id'];


        $alerts = [];
        $arguments = [];

        if (isset($this->forms[$this->form]) !== false) {

            $model = new Forms();
            $getContent = $model->select('id, form, input, status')->where('id', $id)->where('form', $this->form)->get();
            if (! empty($getContent)) {

                $form = $this->prepareForm($this->forms[$this->form]['inputs'], $getContent);
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

    public function formUpdate() {

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
        if (isset($this->forms[$this->form]) !== false) {

            $form = $this->forms[$this->form];

            // Input area check
            foreach ($form['inputs'] as $name => $detail) {

                if ($name === 'widget') {

                    foreach ($detail as $name => $widgetDetail) {
                        
                        $inputAreas[$name] = 'int';
                        if (isset($detail['attributes']['required']) !== false AND $detail['attributes']['required'] === 'true') {
                            $requiredAreas['areas'][$name] = true;
                        }

                    }

                } else {

                    if ($detail['type'] === 'input' OR $detail['type'] === 'url_widget' OR $detail['type'] === 'email' OR $detail['type'] === 'tel' OR $detail['type'] === 'textarea' OR $detail['type'] === 'select') {

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

            $inputAreas['status'] = 'nulled_text';
            extract(Base::input($inputAreas, $this->get('request')->params));

            $update = [];
            // Filter all inputs
            foreach ($inputAreas as $inputName => $inputType) {
                
                if (isset($requiredAreas['areas'][$inputName]) === false OR ! empty($$inputName)) {

                    $update[$inputName] = $$inputName;

                } else {

                    if ($inputType === 'nulled_html') {
                        $arguments['manipulation']['#formEdit [data-name="' . $inputName . '"]'] = [
                            'class' => ['border', 'border-1', 'border-danger'],
                        ];
                    } else {
                        $arguments['manipulation']['#formEdit [name="' . $inputName . '"]'] = [
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
                                $arguments['manipulation']['#formEdit [name="' . $fileName . ($multipleFile ? '[]' : '') . '"]'] = [
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
                            $arguments['manipulation']['#formEdit [name="' . $fileName . ($multipleFile ? '[]' : '') . '"]'] = [
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

                    $model = new Forms;
                    $update = $model->where('id', $id)->update([
                        'input' => json_encode($update),
                        'status' => $status,
                    ]);

                    if ($update) {

                        $alerts[] = [
                            'status' => 'success',
                            'message' => Base::lang('base.form_successfully_updated')
                        ];
                        $arguments['modal_close'] = '#editModal';
                        $arguments['table_reset'] = 'formsTable';
                        $rollBack = [];

                    } else {

                        $alerts[] = [
                            'status' => 'error',
                            'message' => Base::lang('base.form_update_problem')
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

    public function formDelete() {

        $alerts = [];
        $arguments = [];

        $id = (int)$this->get('request')->attributes['id'];
        if (isset($this->forms[$this->form]) !== false) {

            $model = new forms();
            $getContent = $model->select('id')->where('id', $id)->where('form', $this->form)->get();
            if (! empty($getContent)) {

                $delete = $model->where('id', $id)->update(['status' => 'deleted']);

                if ($delete) {

                    $alerts[] = [
                        'status' => 'success',
                        'message' => Base::lang('base.form_successfully_deleted')
                    ];
                    $arguments['table_reset'] = 'formsTable';

                } else {

                    $alerts[] = [
                        'status' => 'error',
                        'message' => Base::lang('base.form_delete_problem')
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

        $return = [];

        foreach ($this->forms as $formKey => $formDetail) {

            foreach ($formDetail['routes'] as $extractType => $routeDetails) {
                
                if (is_array($routeDetails) AND isset($routeDetails[$this->get()->lang]) !== false) {

                    $details = $routeDetails[$this->get()->lang];
                    $route = trim($details[1], '/');
                    if ($route === $this->get()->endpoint) {

                        $return['extract_type'] = $extractType;
                        $return['form_detail'] = $formDetail;
                        $return['form_key'] = $formKey;
                        $this->form = $formKey;

                        if ($extractType === 'listing') { 

                            /* future available for form listing
                            -> $return['page_details'] = [];
                            $selectColumns = [];
                            $externalColumns = [];
                            foreach ($formDetail['inputs'] as $selectCol => $colAttributes) {

                                if ($selectCol === 'widget') {

                                    foreach ($colAttributes as $moduleName => $formDetails) {
                                        $externalColumns[] = $moduleName;
                                        $selectColumns[] = '(JSON_UNQUOTE(JSON_EXTRACT(form, \'$.'.$moduleName.'\'))) AS ' . $moduleName;
                                    }

                                } else {

                                    
                                    if (isset($colAttributes['type']) !== false AND $colAttributes['type'] === 'file') {
                                        $selectColumns[] = '
                                        REPLACE(
                                            REPLACE(
                                                REPLACE(
                                                    REPLACE(
                                                        IFNULL(JSON_EXTRACT(form, \'$.'.$selectCol.'\'), ""),
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
                                        $selectColumns[] = 'JSON_UNQUOTE(JSON_EXTRACT(form, \'$.'.$selectCol.($multilanguage ? '.'.Base::lang('lang.code') : '').'\')) AS ' 
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
                                if (isset($formDetail['inputs'][$column]['multilanguage']) !== false AND $formDetail['inputs'][$column]['multilanguage']) {
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
                                    'form_details' => $contentDetails,
                                    'form_detail' => $formDetail,
                                    'form_key' => $formKey,
                                ];
                            }
                            */

                        }
                        break;

                    }

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

    public function formPage() {

        $extract = $this->extractContentData();

        if ($extract) {

            $arguments = [];
            $arguments['title'] = Base::lang($extract['form_detail']['name']);
            if (isset($extract['page_details']) !== false) $arguments['detail'] = $extract['page_details'];
            $arguments['form_detail'] = $extract['form_detail'];

            if (isset($extract['form_detail']['routes']['description'][$extract['extract_type']]) !== false) {
                $arguments['description'] = Base::lang($extract['form_detail']['routes']['description'][$extract['extract_type']]);
            }

            if ($extract['extract_type'] === 'detail') {
                $arguments['form'] = $this->prepareForm($extract['form_detail']['inputs']);
            }

            return [
                'status' => true,
                'statusCode' => 200,
                'arguments' => $arguments,
                'view' => $extract['form_detail']['routes']['view'][$extract['extract_type']]
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