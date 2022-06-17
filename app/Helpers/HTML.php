<?php

/**
 * @package KN
 * @subpackage KN Helper
 */

declare(strict_types=1);

namespace KN\Helpers;

class HTML {

    /**
     * Menu Module Url Widget
     * @param array $parameters;
     * kn_drag -> boolean: for multi menu support 
     * name -> string: specific input name( default: links )
     * label -> string: title for direct using
     * menu_options -> string: menu module list as string option
     * multi_ready -> boolean: multi dimension layout
     * values -> object: current values 
     * @return string
     */
    public static function menuModuleUrlWidget($parameters = []) {

        $values = null;

        $drag = false;
        if (isset($parameters['kn_drag']) !== false AND $parameters['kn_drag']) 
            $drag = true;

        $name = 'links';
        if (isset($parameters['name']) !== false) 
            $name = $parameters['name'];

        if (isset($parameters['values']) !== false)
            $values = $parameters['values'];

        $currentLang = Base::lang('lang.code');
        $currentName = '';
        $languages = Base::config('app.available_languages');
        $tabContents = '';

        $nameArea = '
        <div class="col-12 kn-multilang-content">
            <div class="kn-multilang-content-switch">
                <div class="nav nav-pills" id="menuName-tablist" role="tablist" aria-orientation="vertical">';
                foreach ($languages as $i => $lang) {
                    $nameArea .= '
                    <button class="nav-link'.($i===0 ? ' active' : '').'" id="name-tab-'.$lang.'(DYNAMIC_ID)" data-bs-toggle="pill" data-bs-target="#name-'.$lang.'(DYNAMIC_ID)" type="button" role="tab" aria-controls="name-tab-'.$lang.'(DYNAMIC_ID)" aria-selected="'.($i===0 ? 'true' : 'false').'">
                        ' . Base::lang('langs.' . $lang ) . '
                    </button>';
                    $tabContents .= '
                    <div class="tab-pane fade'.($i === 0 ? ' show active' : '').'" id="name-'.$lang.'(DYNAMIC_ID)" role="tabpanel" aria-labelledby="name-tab-'.$lang.'(DYNAMIC_ID)">
                        <div class="form-floating">
                            <input'.($currentLang === $lang ? ' data-kn-input="link_name"' : '').' type="text" class="form-control" name="' . $name . '[name][' . $lang . ']" id="menuName' . $lang . '(DYNAMIC_ID)" placeholder="'.Base::lang('base.name').'"'.(isset($values->name->{$lang}) !== false ? ' value="'.$values->name->{$lang}.'"' : '').'>
                            <label for="menuName' . $lang . '">'.Base::lang('base.name').'</label>
                        </div>
                    </div>';

                    if ($currentLang === $lang AND isset($values->name->{$lang}) !== false) {
                        $currentName = $values->name->{$lang};
                    }
                }
        $nameArea .= '
                </div>
            </div>
            <div class="tab-content">
                ' . $tabContents . '
            </div>
        </div>';

        $return = '
        <div class="card px-0'.($drag ? ' kn-menu-drag kn-menu-item' : '').'">
            <div class="card-header d-flex px-2">
                <button class="btn btn-light btn-sm shadow-none text-nowrap text-start overflow-hidden w-100 me-2" type="button" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="menuCollapseBody(DYNAMIC_ID)" data-bs-target="#menuCollapseBody(DYNAMIC_ID)">
                    ' . (isset($parameters['label']) !== false ? $parameters['label'] : Base::lang('base.link')) . ': 
                    <span class="link_name">
                        ' . $currentName . '
                    </span>
                </button>
                <input type="checkbox" name="' . $name . '[blank]" class="btn-check" id="targetBlank(DYNAMIC_ID)" autocomplete="off"'.(isset($values->blank) !== false ? ($values->blank ? ' checked' : '') : '').'>
                <label title="'.Base::lang('base.target_blank').'" class="ms-auto btn btn-outline-primary btn-sm" for="targetBlank(DYNAMIC_ID)">
                    <i class="ti ti-external-link"></i>
                </label>
                '.($drag ? '
                <button title="'.Base::lang('base.move').'" class="ms-1 btn btn-dark btn-sm kn-menu-item-dragger" type="button">
                    <i class="ti ti-drag-drop"></i>
                </button>
                <button title="'.Base::lang('base.remove').'" class="ms-1 btn btn-danger btn-sm" type="button" data-kn-action="remove" data-kn-parent=".kn-menu-item">
                    <i class="ti ti-circle-minus"></i>
                </button>
                ' : '').'
            </div>
            <div class="collapse" id="menuCollapseBody(DYNAMIC_ID)">
                <div class="card-body">
                    <div class="row g-1">
                        <div class="col-12 col-md-6">
                            ' . $nameArea . '
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-floating mt-4">
                                <input type="url" class="form-control form-control-sm" name="' . $name . '[direct_link]" placeholder="' . Base::lang('base.direct_link').'"'.(isset($values->direct_link) !== false ? ' value="'.$values->direct_link.'"' : '').'>
                                <label>' . Base::lang('base.direct_link').'</label>
                            </div>
                        </div>
                        <div class="col-12 col-md-12">
                            <div class="row g-1">
                                <div class="col-sm-8">
                                    <div class="form-floating">
                                        <select data-kn-change="'.Base::base('management/menus/get-menu-params').($drag ? '' : '?widget=on').'" data-kn-target="#menuParameter(DYNAMIC_ID)" class="form-select form-select-sm" name="' . $name . '[dynamic_link][module]" aria-label="' . Base::lang('base.module').'">
                                            '.$parameters['menu_options'].'
                                        </select>
                                        <label>' . Base::lang('base.module').'</label>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-floating">
                                        <select class="form-select form-select-sm" id="menuParameter(DYNAMIC_ID)" name="' . $name . '[dynamic_link][parameter]" aria-label="' . Base::lang('base.parameter').'">
                                            ' . (isset($parameters['module_parameters']) !== false ? $parameters['module_parameters'] : '') . '
                                        </select>
                                        <label>' . Base::lang('base.parameter').'</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>';

        if (! $drag) {

            $return = str_replace('(DYNAMIC_ID)', Base::tokenGenerator(5), $return);

        }

        return $return;

    }

    /**
     * Menu URL Widget List
     * @param object $items
     * @return string
     **/
    public static function menuUrlWidgetList($items) {

        $return = '';
        $menuController = (new \KN\Controllers\MenuController((object)['request'=>'']));
        if (count((array) $items)) {
            foreach ($items as $item) {
                
                $dynamicId = Base::tokenGenerator(8);
                $moduleName = isset($item->dynamic_link->module) !== false? $item->dynamic_link->module : null;
                $widget = self::menuModuleUrlWidget([
                    'menu_options' => $menuController->menuOptionsAsHTML($moduleName),
                    'values' => $items,
                    'kn_drag' => true,
                    'module_parameters' => $menuController->getMenuParameters($item->dynamic_link->module, $item->dynamic_link->parameter),
                    'values' => $item
                ]);
                $widget = str_replace('(DYNAMIC_ID)', $dynamicId, $widget);

                if (isset($item->sub) !== false) {
                    $sub = self::menuUrlWidgetList($item->sub);
                    $widget = rtrim($widget, '</div>') . $sub . '</div>';
                }

                $return .= $widget;

            }
        }
        return $return;

    }

    /**
     * Menu Generator
     * @param string $menuKey
     * @param array $parameters
     * @param int $level
     * @return string
     **/
    public static function generateMenu($menuKey, $parameters = [], $level = 1, $container = null) {

        $return = '';
        $urls = is_string($menuKey) 
            ? (new \KN\Controllers\MenuController((object)['request' => '']))->getMenuDetails($menuKey)
            : $menuKey;
        if (count($urls)) {

            $ulClass = '';
            if (isset($parameters['ul_class']) !== false) {
                $ulClass = $parameters['ul_class'];
            }

            if ($level > 1 AND isset($parameters['ul_dropdown_class']) !== false) {
                $ulClass = $parameters['ul_dropdown_class'];
            }

            $return .= '
            <ul'.($ulClass != '' ? ' class="' . $ulClass . '"' : '').'>';
            foreach ($urls as $url) {

                $liClass = '';
                if (isset($parameters['li_class']) !== false) {
                    $liClass = $parameters['li_class'];
                }
                if (isset($url['sub']) !== false AND isset($parameters['dropdown_li_class']) !== false) {
                    $liClass = $parameters['dropdown_li_class'];
                }

                $aClass = '';
                if (isset($parameters['a_class']) !== false) {
                    $aClass = $parameters['a_class'];
                }
                if ($level > 1 AND isset($parameters['dropdown_a_class']) !== false) {
                    $aClass = $parameters['dropdown_a_class'];
                }
                $aAttr = '';
                if (isset($url['sub']) !== false) {
                    if (isset($parameters['a_dropdown_class']) !== false) {
                        $aClass = $parameters['a_dropdown_class'];
                    }
                    if (isset($parameters['li_dropdown_class']) !== false) {
                        $liClass = $parameters['li_dropdown_class'];
                    }
                    if (isset($parameters['a_dropdown_attributes']) !== false) {
                        $aAttr = $parameters['a_dropdown_attributes'];
                    }
                }

                if (! is_null($container)) {
                    $aClass .= $container->currentLink($url['link']);
                }

                $return .= '
                <li'.($liClass != '' ? ' class="' . $liClass . '"' : '').'>
                    <a'.($aClass != '' ? ' class="' . $aClass . '"' : '').' 
                        href="' . $url['link'] . '"
                        '.($url['blank'] ? ' target="_blank"' : '')
                        . ($aAttr != '' ? ' ' . $aAttr : '') . '>
                        ' . $url['name'] . '
                    </a>';

                if (isset($url['sub']) !== false) {
                    $return .= self::generateMenu($url['sub'], $parameters, ($level+1), $container);
                }
                $return .= '
                </li>';
            }
            $return .= '
            </ul>';

        }
        return $return;

    }

}