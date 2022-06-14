<?php

/**
 * @package KN
 * @subpackage KN Helper
 */

declare(strict_types=1);

namespace KN\Helpers;

class HTML {

    /**
     * Url Widget
     * @param any $value
     * @param boolean $exit
     * @return void
     */
    public static function urlWidget($parameters = []) {

        $languages = Base::config('app.available_languages');
        $tabContents = '';
        $nameArea = '
        <div class="col-12 kn-multilang-content">
            <div class="kn-multilang-content-switch">
                <div class="nav nav-pills" id="menuName-tablist" role="tablist" aria-orientation="vertical">';
                foreach ($languages as $i => $lang) {
                    $nameArea .= '
                    <button class="nav-link'.($i===0 ? ' active' : '').'" id="name-tab-'.$lang.'(DYNAMIC_ID)" data-bs-toggle="pill" data-bs-target="#name-'.$lang.'(DYNAMIC_ID)" type="button" role="tab" aria-controls="name-tab-'.$lang.'(DYNAMIC_ID)" aria-selected="'.($i===0 ? 'true' : 'false').'">
                        ' . \KN\Helpers\Base::lang('langs.' . $lang ) . '
                    </button>';
                    $tabContents .= '
                    <div class="tab-pane fade'.($i === 0 ? ' show active' : '').'" id="name-'.$lang.'(DYNAMIC_ID)" role="tabpanel" aria-labelledby="name-tab-'.$lang.'(DYNAMIC_ID)">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="links[name][' . $lang . ']" id="menuName' . $lang . '(DYNAMIC_ID)" placeholder="'.\KN\Helpers\Base::lang('base.name').'">
                            <label for="menuName' . $lang . '">'.\KN\Helpers\Base::lang('base.name').'</label>
                        </div>
                    </div>';
                }
        $nameArea .= '
                </div>
            </div>
            <div class="tab-content">
                ' . $tabContents . '
            </div>
        </div>';

        $return = '
        <div class="kn-menu-drag kn-menu-item">
            <div class="row g-1">
                <div class="col-12 col-md-11">
                    <div class="row g-1">
                        <div class="col-12">
                            ' . $nameArea . '
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="url" class="form-control form-control-sm" name="links[direct_link]" placeholder="' . \KN\Helpers\Base::lang('base.direct_link').'">
                                <label>' . \KN\Helpers\Base::lang('base.direct_link').'</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row g-1">
                                <div class="col-sm-8">
                                    <div class="form-floating">
                                        <select data-kn-change="'.Base::base('management/menus/get-menu-params').'" data-kn-target="#menuParameter(DYNAMIC_ID)" class="form-select form-select-sm" name="links[dynamic_link][module]" aria-label="' . \KN\Helpers\Base::lang('base.module').'">
                                            '.$parameters['menu_options'].'
                                        </select>
                                        <label>' . \KN\Helpers\Base::lang('base.module').'</label>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-floating">
                                        <select class="form-select form-select-sm" id="menuParameter(DYNAMIC_ID)" name="links[dynamic_link][parameter]" aria-label="' . \KN\Helpers\Base::lang('base.parameter').'">
                                        </select>
                                        <label>' . \KN\Helpers\Base::lang('base.parameter').'</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-1">
                    <div class="d-grid gap-2">
                        <button class="btn btn-danger btn-sm" type="button" data-kn-action="remove" data-kn-parent=".kn-menu-item">
                            <i class="ti ti-circle-minus"></i>
                        </button>
                        <button class="btn btn-dark btn-sm kn-menu-item-dragger" type="button">
                            <i class="ti ti-drag-drop"></i>
                        </button>
                        <input type="checkbox" name="links[blank]" class="btn-check" id="targetBlank(DYNAMIC_ID)" autocomplete="off">
                        <label class="btn btn-outline-primary btn-sm" for="targetBlank(DYNAMIC_ID)">
                            <i class="ti ti-external-link"></i>
                        </label><br>
                    </div>
                </div>
            </div>
        </div>';

        return $return;

    }

}