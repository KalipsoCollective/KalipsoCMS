<?php

/**
 * @package KN
 * @subpackage Controller
 */

declare(strict_types=1);

namespace KN\Controllers;

use KN\Core\Controller;
use KN\Helpers\Base;
use KN\Helpers\KalipsoTable;
use KN\Core\Model;
use KN\Model\Menu;

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
			],
			'view' => ['admin.menus', 'admin']
		];

	}


	public function userList() {

		$container = $this->get();

		$tableOp = (new KalipsoTable())
			->db((new Users)->pdo)
			->from('(SELECT 
					x.id, 
					x.u_name, 
					x.f_name,
					x.l_name,
					x.email, 
					IFNULL(FROM_UNIXTIME(x.b_date, "%Y.%m.%d"), "-") AS birth_date,
					IFNULL((SELECT name FROM user_roles WHERE status = "active" AND id = x.role_id), "-") AS role,
					FROM_UNIXTIME(x.created_at, "%Y.%m.%d %H:%i") AS created,
					IFNULL(FROM_UNIXTIME(x.updated_at, "%Y.%m.%d"), "-") AS updated,
					x.status
				FROM `users` x WHERE status != "deleted") AS raw')
			->process([
				'id' => [
					'primary' => true,
				],
				'u_name' => [],
				'name' => [
					'exclude' => true,
					'formatter' => function($row) {

						$name = trim($row->f_name . ' ' . $row->l_name);
						return $name == '' ? '-' : $name;
					}
				],
				'email' => [],
				'birth_date' => [],
				'role' => [],
				'created' => [],
				'updated' => [],
				'status' => [
					'formatter' => function ($row) {

						switch ($row->status) {
							case 'deleted':
								$status = 'text-danger';
								break;

							case 'passive':
								$status = 'text-warning';
								break;
								
							default:
								$status = 'text-success';
								break;
						}

						return '<span class="' . $status . '">' . Base::lang('base.' . $row->status) . '</span>';

					}
				],
				'action' => [
					'exclude' => true,
					'formatter' => function($row) use ($container) {

						$buttons = '';
						if ($container->authority('management/users/:id')) {
							$buttons .= '
							<button type="button" class="btn btn-light" 
								data-kn-action="'.$this->get()->url('/management/users/' . $row->id ).'">
								' . Base::lang('base.view') . '
							</button>';
						}

						if ($container->authority('management/users/:id/delete')) {
							$buttons .= '
							<button type="button" class="btn btn-danger" 
								data-kn-again="'.Base::lang('base.are_you_sure').'" 
								data-kn-action="'.$this->get()->url('/management/users/' . $row->id . '/delete').'">
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


		//$arguments = (new KalipsoTable()->);

		return [
			'status' => true,
			'statusCode' => 200,
			'arguments' => $tableOp,
			'view' => null
		];

	}


	public function userAdd() {

		extract(Base::input([
			'email' => 'nulled_text',
			'u_name' => 'nulled_text',
			'f_name' => 'nulled_text',
			'l_name' => 'nulled_text',
			'role_id' => 'nulled_int',
			'password' => 'nulled_password'
		], $this->get('request')->params));

		$alerts = [];
		$arguments = [];

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


	public function userDelete() {

		$id = (int)$this->get('request')->attributes['id'];

		$alerts = [];
		$arguments = [];

		$model = new Users();
		
		$getUser = $model->select('id, u_name')->where('id', $id)->get();
		if (! empty($getUser)) {

			if ($id !== (int)Base::userData('id')) {

				$update = $model->where('id', $id)->update([
					'status' => 'deleted'
				]);

				if ($update) {

					(new Sessions())->where('user_id', $id)->delete();
					$alerts[] = [
						'status' => 'success',
						'message' => Base::lang('base.user_successfully_deleted')
					];
					$arguments['table_reset'] = 'usersTable';

				} else {

					$alerts[] = [
						'status' => 'error',
						'message' => Base::lang('base.user_delete_problem')
					];
				}

			} else {

				$alerts[] = [
					'status' => 'error',
					'message' => Base::lang('base.user_delete_problem_for_own_account')
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


	public function userDetail() {

		$id = (int)$this->get('request')->attributes['id'];

		$alerts = [];
		$arguments = [];

		$model = new Users();
		$getUser = $model->select('id, u_name, f_name, l_name, email, role_id')->where('id', $id)->get();
		if (! empty($getUser)) {

			$userRoles = (new UserRoles)->select('name, id')->orderBy('name', 'asc')->getAll();
			$options = '';

			foreach ($userRoles as $role) {
				$selected = $role->id == $getUser->role_id ? true : false;
				$options .= '
				<option value="' . $role->id. '"' . ($selected ? ' selected' : '') . '>
					' . $role->name . '
				</option>';
			}

			$arguments['modal_open'] = ['#editModal'];
			$arguments['manipulation'] = [
				'#userUpdate' => [
					'attribute' => ['action' => $this->get()->url('management/users/' . $id . '/update')],
				],
				'#theUserEmail' => [
					'attribute' => ['value' => $getUser->email],
				],
				'#theUserName' => [
					'attribute' => ['value' => $getUser->u_name],
				],
				'#thefName' => [
					'attribute' => $getUser->f_name ? ['value' => $getUser->f_name] : ['value' => ''],
				],
				'#thelName' => [
					'attribute' => $getUser->l_name ? ['value' => $getUser->l_name] : ['value' => ''],
				],
				'#theRoles' => [
					'html'	=> $options
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


	public function userUpdate() {

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