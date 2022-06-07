<?php

/**
 * @package KN
 * @subpackage Controller
 */

declare(strict_types=1);

namespace KN\Controllers;

use KN\Core\Controller;
use KN\Helpers\Base;
use KN\Model\Files;

final class FileController extends Controller {

    public function medias() {


        return [
            'status' => true,
            'statusCode' => 200,
            'arguments' => [
                'title' => Base::lang('base.users') . ' | ' . Base::lang('base.media'),
                'description' => Base::lang('base.media_message'),
                'modules' => $this->modules,
                'forms' => $this->forms,
            ],
            'view' => ['admin.medias', 'admin']
        ];

    }

    public function removeFileWithId($id) {

        $model = new Files;
        $getFile = $model->select('files')->where('id', $id)->get();
        if ($getFile AND isset($getFile->files)) {

            $files = json_decode($getFile->files);
            foreach ($files as $file) {
                if (file_exists($path = Base::path('upload/' . $file))) {
                    unlink($path);
                }
            }
            $model->where('id', $id)->delete();
        }

    }

}