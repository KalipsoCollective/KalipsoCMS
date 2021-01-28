<?php

namespace app\modules;

use app\core\db;

class User
{
    protected ?string $authCode = '';
    protected ?int $userId = 0;

    public function __construct()
    {
        global $authorityPoints;

        if (isset( $_COOKIE[config('app.session')] ) === false) {
            header("Refresh:0");
            exit;
        }

        $authorityPoints['action']['User/login'] = [
            'name'      => 'login',
            'default'   => true,
            'view'      => false,
        ];

        $this->authCode = $_COOKIE[config('app.session')];
    }

    /**
     * User Status
     * @param $status
     * @return bool
     */
    public function userStatus($status): bool
    {
        return $status != 'deleted';
    }

    /**
     * Login Method
     * @return array
     */
    public function login (): array
    {
        $route = routeFormatter(__CLASS__, __FUNCTION__);
        $externalData = null;

        if (authority('action', $route)) { // authority control

            if (isset($_POST) !== false AND count($_POST)) {
                $username = '';
                $password = '';
                extract(in(['username' => 'text', 'password' => 'text'], $_POST));
                $userData = (new db())->table('users')
                    ->select('*')
                    ->orWhere(['u_name' => $username, 'email' => $username])
                    ->limit(1)
                    ->getAll();

                if (is_array($userData) AND count($userData)>0) {

                    $userData = $userData[0];
                    $this->userId = $userData->id;

                    if (password_verify($password, $userData->password)) {

                        if ($this->userStatus($userData->status)) {

                            $sessionControl = (new db())->table('sessions')
                                ->select('auth_code')
                                ->where('auth_code', $_COOKIE[config('app.session')])
                                ->limit(1)
                                ->getAll();

                            if ($sessionControl) {

                                $return = [
                                    'status'	=> 'warning',
                                    'refresh'	=> [3, null, true],
                                    'title'		=> lang('warning'),
                                    'text'		=> lang('you_have_a_session')
                                ];
                                $externalData = 'you_have_a_session';

                            } else {

                                $insert = [
                                    'auth_code'			=> $_COOKIE[config('app.session')],
                                    'user_id'			=> $this->userId,
                                    'header'			=> getHeader(),
                                    'ip'				=> getIP(),
                                    'auth_group_id'		=> $userData->auth_group_id,
                                    'last_action_date'	=> time(),
                                    'last_action_point'	=> $route
                                ];

                                $session = (new db())->table('sessions')
                                    ->insert($insert);

                                if ($session) {

                                    $permission = $this->getUserGroup($userData->auth_group_id);
                                    if (isset($permission->id) !== false) {

                                        $userData->group_name = $permission->name;

                                        $view = [];
                                        $action = [];

                                        if ($permission->view_points != '') {

                                            $permission->view_points = strpos(
                                                $permission->view_points, ','
                                            ) !== false ? explode(',', $permission->view_points)
                                                : [$permission->view_points];

                                            $view = $permission->view_points;

                                        }

                                        if ($permission->action_points != '') {

                                            $permission->action_points = strpos(
                                                $permission->action_points, ','
                                            ) !== false ? explode(',', $permission->action_points)
                                                : [$permission->action_points];

                                            $action = $permission->action_points;

                                        }

                                        $permission = (object)[
                                            'view'      => (object) $view,
                                            'action'    => (object) $action
                                        ];

                                        $userData->permission = $permission;

                                    }
                                    userDataToSession($userData);

                                    $return = [
                                        'status'	=> 'success',
                                        'title'		=> lang('success'),
                                        'text'		=> lang('login_successful'),
                                        'refresh'	=> [3, '', true]
                                    ];
                                    $externalData = 'login_successful';

                                } else {
                                    $return = [
                                        'status'	=> 'danger',
                                        'title'		=> lang('error'),
                                        'text'		=> lang('session_register_problem')
                                    ];
                                    $externalData = 'session_register_problem';
                                }
                            }

                        } else {

                            $return = [
                                'status'		=> 'danger',
                                'form_reset'	=> true,
                                'title'			=> lang('error'),
                                'text'			=> lang('disabled_account_problem')
                            ];
                            $externalData = 'disabled_account_problem';
                        }

                    } else {
                        $return = [
                            'status'	=> 'warning',
                            'title'		=> lang('warning'),
                            'text'		=> lang('password_is_incorrect')
                        ];
                        $externalData = 'password_is_incorrect';
                    }

                } else {

                    $return = [
                        'status'	=> 'warning',
                        'title'		=> lang('warning'),
                        'text'		=> lang('no_such_account')
                    ];
                    $externalData = 'no_such_account';
                }

            } else {

                $return = [
                    'status'	=> 'danger',
                    'title'		=> lang('warning'),
                    'text'		=> lang('empty_form_alert')
                ];
                $externalData = 'empty_form_alert';

            }

        } else {

            $return = [
                'status'	=> 'danger',
                'title'		=> lang('error'),
                'text'		=> lang('permission_denied')
            ];
            $externalData = 'permission_denied';

        }

        return $return;
    }

    /**
     * Get Auth Code
     * @return string
     */
    public function getAuthCode(): ?string
    {
        return $this->authCode;
    }

    /**
     * Get User ID
     * @return int
     */
    public function getUserId(): ?int
    {
        $return = 0;

        $user = (new db())->table('sessions')
            ->select('user_id')
            ->where('auth_code', $this->authCode)
            ->get();


        if (isset($user->user_id) !== false) {

            $this->userId = $user->user_id;
            $return = $this->userId;
        }
        return $return;
    }

    /**
     * Get User Group
     * @param $id
     * @return array|false|mixed|string
     */
    public function getUserGroup($id) {

        return (new db())->table('auth_groups')
            ->select('*')
            ->where('id', $id)
            ->get();
    }
}