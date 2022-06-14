<?php

/**
 * @package KN
 * @subpackage Controller
 */

declare(strict_types=1);

namespace KN\Controllers;

use KN\Core\Controller;
use KN\Helpers\Base;
use KN\Helpers\HTML;
use KN\Helpers\KalipsoTable;
use KN\Core\Model;
use KN\Model\Menus;
use KN\Controllers\ContentController;

final class MenuController extends Controller {

	public function __construct($container) {

		parent::__construct($container);

	}

	public function menus() {

		return [
			'status' => true,
			'statusCode' => 200,
			'arguments' => [
				'title' => Base::lang('base.menus') . ' | ' . Base::lang('base.management'),
				'description' => Base::lang('base.menus_message'),
				'modules' => $this->modules,
				'forms' => $this->forms,
				'menuJson' => json_encode([
					'dragger' => true,
					'manipulation' => [
						'#addModal .kn-menu-drag' => [
							'html_append' => HTML::menuModuleUrlWidget([
								'menu_options' => $this->menuOptionsAsHTML(),
								'kn_drag' => true
							]),
							'html_append_dynamic' => true
						]
					]
				])
			],
			'view' => ['admin.menus', 'admin']
		];

	}

	public function menuOptions() {

		$options = [
			'basic' => [
				'home' => Base::lang('base.home'),
				'login' => Base::lang('base.login'),
				'register' => Base::lang('base.register'),
				'recovery' => Base::lang('base.recovery_account'),
			],
			'modules' => [],
			'forms' => [],
		];
		foreach ($this->modules as $key => $data) {
			if ($data['routes']['listing'] OR $data['routes']['detail']) 
				$options['modules'][$key] = Base::lang($data['name']);
		}

		foreach ($this->forms as $key => $data) {
			$options['forms'][$key] = Base::lang($data['name']);
		}

		return $options;

	}

	public function menuOptionsAsHTML($currentValue = null) {

		$options = '<option value=""></option>';
		foreach ($this->menuOptions() as $section => $links) {
			$options .= '<optgroup label="' . Base::lang('base.' . $section) . '">';
			foreach ($links as $value => $name) {
				$optValue = $section . '_' . $value;
				$options .= '<option value="' . $optValue . '"'.($currentValue == $optValue ? ' selected' : '').'>' . $name . '</option>';
			}
			$options .= '</optgroup>';
		}
		return $options;
	}

	public function getMenuParameters($module = null, $parameter = null) {

		
		if (is_null($module)) {
			extract(Base::input([
				'module'  => 'nulled_text', // related module
				'target'  => 'nulled_text', // target
				'widget'  => 'check_as_boolean', // direct link parameter for other modules
			], $this->get('request')->params));
		} else {
			$returnDirect = true;
			$widget = false;
		}
		
		$arguments = [];
		$html = ' ';

		if (! is_null($module) AND strpos($module, '_') !== false) {

			$module = explode('_', $module, 2);
			if ($module[0] === 'modules') {
				

				if (isset($this->modules[$module[1]]) !== false) {

					$module = $module[1];
					$moduleDetail = $this->modules[$module];
					if ($moduleDetail['routes']['listing']) {
						$html .= '<option value="list"'.($parameter == 'list' ? ' selected' : '').'>' . Base::lang('base.list') . '</option>';
					}

					if ($moduleDetail['routes']['detail']) {

						if (! $widget) {
							$html .= '<option value="list_as_dropdown"'.($parameter == 'list_as_dropdown' ? ' selected' : '').'>' . Base::lang('base.list_as_dropdown') . '</option>';
						}

						$contents = (new ContentController($this->get()))->getModuleDatas($module);

						if (count($contents)) {
							$html .= '<optgroup label="' . Base::lang('base.contents') . '">';
							foreach ($contents as $content) {
								$contentDetails = json_decode($content->input);
								$val = $content->id;
								if (isset($contentDetails->title) AND 
									(
										is_string($contentDetails->title) OR 
										isset($contentDetails->title->{Base::lang('lang.code')}) !== false
									)
								) {

									$text = is_string($contentDetails->title) ? $contentDetails->title : $contentDetails->title->{Base::lang('lang.code')};

								} elseif (isset($contentDetails->name) AND 
									(
										is_string($contentDetails->name) OR 
										isset($contentDetails->name->{Base::lang('lang.code')}) !== false
									)
								) {

									$text = is_string($contentDetails->name) ? $contentDetails->name : $contentDetails->name->{Base::lang('lang.code')};

								} else {
									$text = $val;
								}
								$html .= '<option value="' . $val . '"'.($parameter==$val ? ' selected' : '').'>' . $text . '</option>';
							}
							$html .= '</optgroup>';
						}
					}

				}

			} elseif ($module[0] === 'forms') {
	


			}

		}

		

		if (isset($returnDirect) !== false) {
			$return = $html;
		} else {
			$arguments['manipulation'] = [
				$target => [
					'html'  => $html
				]
			];
			$return = [
				'status' => true,
				'statusCode' => 200,
				'arguments' => $arguments,
				'view' => null
			];
		}

		return $return;

	}

	public function menuIntegrityCheck($items) {

		$return = true;
		$availableLangs = Base::config('app.available_languages');
		if (count((array)$items)) {
			foreach ($items as $detail) {
				
				if ($detail->direct_link === '' AND $detail->dynamic_link->module === '') {
					$return = false;
					break;
				}

				foreach ($availableLangs as $lang) {
					if (isset($detail->name->{$lang}) === false OR $detail->name->{$lang} === '') {
						$return = false;
						break;
					}
				}

				if (isset($detail->sub) !== false) {
					$return = $this->menuIntegrityCheck($detail->sub);
					if (! $return)
						break;
				}
			}
		} else {
			$return = false;
		}
		return $return;

	}

	public function menuList() {

		$container = $this->get();

		$tableOp = (new KalipsoTable())
			->db((new Menus)->pdo)
			->from('(SELECT 
					x.id, 
					x.menu_key, 
					x.items,
					FROM_UNIXTIME(x.created_at, "%Y.%m.%d %H:%i") AS created,
					IFNULL(FROM_UNIXTIME(x.updated_at, "%Y.%m.%d"), "-") AS updated
				FROM `menus` x) AS raw')
			->process([
				'id' => [
					'primary' => true,
				],
				'menu_key' => [],
				'item_count' => [
					'exclude' => true,
					'formatter' => function($row) {

						$total = 0;
						$arr = json_decode($row->items, true);
						array_walk_recursive($arr, function($val, $key) use (&$total) {
							if ($key == 'direct_link') {
								$total++;
							}
						});
						return $total;
					}
				],
				'created' => [],
				'updated' => [],
				'action' => [
					'exclude' => true,
					'formatter' => function($row) use ($container) {

						$buttons = '';
						if ($container->authority('management/menus/:id')) {
							$buttons .= '
							<button type="button" class="btn btn-light" 
								data-kn-action="'.$this->get()->url('/management/menus/' . $row->id ).'">
								' . Base::lang('base.view') . '
							</button>';
						}

						if ($container->authority('management/menus/:id/delete')) {
							$buttons .= '
							<button type="button" class="btn btn-danger" 
								data-kn-again="'.Base::lang('base.are_you_sure').'" 
								data-kn-action="'.$this->get()->url('/management/menus/' . $row->id . '/delete').'">
								' . Base::lang('base.delete') . '
							</button>';
						}



						return '
						<div class="btn-group btn-group-sm" role="group" aria-label="'.Base::lang('base.action').'">
							'.$buttons.'
						</div>';
					}
				],
			])
			->output();

		return [
			'status' => true,
			'statusCode' => 200,
			'arguments' => $tableOp,
			'view' => null
		];

	}

	public function menuAdd() {

		extract(Base::input([
			'menu_key' => 'nulled_text',
			'items' => 'nulled_text'
		], $this->get('request')->params));

		$alerts = [];
		$arguments = [];

		$model = new Menus();
		
		if ($menu_key AND $items) {

			$keyCheck = $model->count('id', 'total')->where('menu_key', $menu_key)->get();
			if ((int)$keyCheck->total === 0) {

				$items = htmlspecialchars_decode($items);
				$itemsObj = @json_decode($items);
				$insert = $this->menuIntegrityCheck($itemsObj);

				if ($insert) {

					$insert = [
						'menu_key' => $menu_key,
						'items' => $items
					];

					$insert = $model->insert($insert);
					if ($insert) {

						$alerts[] = [
							'status' => 'success',
							'message' => Base::lang('base.menu_successfully_added')
						];
						$arguments['form_reset'] = true;
						$arguments['modal_close'] = '#addModal';
						$arguments['table_reset'] = 'menusTable';

					} else {

						$alerts[] = [
							'status' => 'error',
							'message' => Base::lang('base.menu_add_problem')
						];
					}

				} else {

					$alerts[] = [
						'status' => 'warning',
						'message' => Base::lang('base.menu_integrity_problem')
					];
				}

			} else {

				$alerts[] = [
					'status' => 'warning',
					'message' => Base::lang('base.key_is_already_used')
				];
				$arguments['manipulation'] = [
					'#addModal [name="key"]' => [
						'class' => ['is-invalid'],
					]
				];
			}

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

	}


	public function menuDelete() {

		$id = (int)$this->get('request')->attributes['id'];

		$alerts = [];
		$arguments = [];

		$model = new Menus();
		
		$getMenu = $model->select('id')->where('id', $id)->get();
		if (! empty($getMenu)) {

			$delete = $model->where('id', $id)->delete();

			if ($delete) {

				$alerts[] = [
					'status' => 'success',
					'message' => Base::lang('base.menu_successfully_deleted')
				];
				$arguments['table_reset'] = 'menusTable';

			} else {

				$alerts[] = [
					'status' => 'error',
					'message' => Base::lang('base.menu_delete_problem')
				];
			}

		} else {

			$alerts[] = [
				'status' => 'warning',
				'message' => Base::lang('base.record_not_found')
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


	public function menuDetail() {

		$id = (int)$this->get('request')->attributes['id'];

		$alerts = [];
		$arguments = [];

		$model = new Menus();
		$getMenu = $model->select('id, menu_key, items')->where('id', $id)->get();
		if (! empty($getMenu)) {

			$getMenu->items = json_decode($getMenu->items);
			$menuContent = HTML::menuUrlWidgetList(
				$getMenu->items
			);

			$arguments['modal_open'] = ['#editModal'];
			$arguments['manipulation'] = [
				'#menuUpdate' => [
					'attribute' => ['action' => $this->get()->url('management/menus/' . $id . '/update')],
				],
				'#editModal [name="menu_key"]' => [
					'attribute' => ['value' => $getMenu->menu_key],
				],
				'#editModal #menuItems' => [
					'html' => $menuContent,
				],
			];

		} else {

			$alerts[] = [
				'status' => 'warning',
				'message' => Base::lang('base.record_not_found')
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


	public function menuUpdate() {

		$id = (int)$this->get('request')->attributes['id'];

		extract(Base::input([
			'menu_key' => 'nulled_text',
			'items' => 'nulled_text'
		], $this->get('request')->params));

		$alerts = [];
		$arguments = [];

		$model = new Menus();
		$getMenu = $model->select('id, menu_key, items')->where('id', $id)->get();
		if (! empty($getMenu)) {

			if ($menu_key AND $items) {

				$keyCheck = $model->count('id', 'total')->where('menu_key', $menu_key)->notWhere('id', $id)->get();
				if ((int)$keyCheck->total === 0) {

					$items = htmlspecialchars_decode($items);
					$itemsObj = @json_decode($items);
					$update = $this->menuIntegrityCheck($itemsObj);

					if ($update) {

						$update = [
							'menu_key' => $menu_key,
							'items' => $items
						];

						$update = $model->where('id', $id)->update($update);
						if ($update) {

							$alerts[] = [
								'status' => 'success',
								'message' => Base::lang('base.menu_successfully_updated')
							];
							$arguments['modal_close'] = '#editModal';
							$arguments['table_reset'] = 'menusTable';

						} else {

							$alerts[] = [
								'status' => 'error',
								'message' => Base::lang('base.menu_update_problem')
							];
						}

					} else {

						$alerts[] = [
							'status' => 'warning',
							'message' => Base::lang('base.menu_integrity_problem')
						];
					}

				} else {

					$alerts[] = [
						'status' => 'warning',
						'message' => Base::lang('base.key_is_already_used')
					];
					$arguments['manipulation'] = [
						'#editModal [name="key"]' => [
							'class' => ['is-invalid'],
						]
					];
				}

			} else {

				$alerts[] = [
					'status' => 'warning',
					'message' => Base::lang('base.form_cannot_empty')
				];

			}
		} else {
			$alerts[] = [
				'status' => 'warning',
				'message' => Base::lang('base.record_not_found')
			];
		}

		return [
			'status' => true,
			'statusCode' => 200,
			'arguments' => $arguments,
			'alerts' => $alerts,
			'view' => null
		];


		extract(Base::input([
			'email' => 'nulled_text',
			'u_name' => 'nulled_text',
			'f_name' => 'nulled_text',
			'l_name' => 'nulled_text',
			'role_id' => 'nulled_int',
			'password' => 'nulled_password'
		], $this->get('request')->params));

		$id = (int)$this->get('request')->attributes['id'];

		$alerts = [];
		$arguments = [];

		$model = new Users();
		$getUser = $model->select('id, u_name, f_name, l_name, email, role_id')->where('id', $id)->get();
		if (! empty($getUser)) {
		
			if ($email AND $u_name AND $role_id) {

				$userNameCheck = $model->count('id', 'total')->where('u_name', $u_name)->notWhere('id', $id)->get();
				if ((int)$userNameCheck->total === 0) {

					$userEmailCheck = $model->count('id', 'total')->where('email', $email)->notWhere('id', $id)->get();
					if ((int)$userEmailCheck->total === 0) {

						$update = [
							'email' => $email,
							'u_name' => $u_name,
							'f_name' => $f_name,
							'l_name' => $l_name,
							'role_id' => $role_id,
						];

						if ($password) {
							$update['password'] = $password;
						}

						$update = $model->where('id', $id)->update($update);

						if ($update) {

							if ($getUser->role_id !== $role_id) {
								(new Sessions)->where('user_id', $id)->update([
									'role_id' => $role_id,
									'update_session' => 'true'
								]);
							}

							$alerts[] = [
								'status' => 'success',
								'message' => Base::lang('base.user_successfully_updated')
							];
							$arguments['table_reset'] = 'usersTable';

						} else {

							$alerts[] = [
								'status' => 'error',
								'message' => Base::lang('base.user_update_problem')
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
					$arguments['manipulation']['#userUpdate [name="email"]'] = [
						'class' => ['is-invalid'],
					];
				}

				if ($u_name) {
					$arguments['manipulation']['#userUpdate [name="u_name"]'] = [
						'class' => ['is-invalid'],
					];
				}

				if ($role_id) {
					$arguments['manipulation']['#userUpdate [name="role_id"]'] = [
						'class' => ['is-invalid'],
					];
				}

			}

		} else {

			$alerts[] = [
				'status' => 'warning',
				'message' => Base::lang('base.record_not_found')
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