<?php

/**
 * Authority Check
 * @param $area
 * @param $key
 * @return bool
 */

function authority($area, $key): bool
{

    global $authorityPoints;

    $return = false;
    if ((isset($authorityPoints[$area][$key]['default']) !== false AND $authorityPoints[$area][$key]['default'])
        OR
        (isset($_SESSION['user']->permission->{$area}->{$key}) !== false
            AND $_SESSION['user']->permission->{$area}->{$key}
            )
    ) {

        $return = true;

    }
    return $return;
}

/**
 * User Data to Session
 * @param $data
 * @return void
 */
function userDataToSession($data) {

    $_SESSION['user'] = (object)[
        'id'		    => $data->id,
        'u_name'	    => $data->u_name,
        'f_name'		=> $data->f_name,
        'l_name'	    => $data->l_name,
        'email'         => $data->email,
        'auth_group_id' => $data->auth_group_id,
        'permission'    => $data->permission,
        'group_name'    => (isset($data->group_name) !== false ? $data->group_name : '')
    ];
}

/**
 * XHR response generator.
 * @param $return
 * @return void
 */
function generateResponse($return) {

    if (isset($return['title']) AND isset($return['text'])) {

        $return['message'] = '
			<div class="alert alert-' .$return['status']. ' alert-dismissible fade show" role="alert">
				<strong>' . $return['title'] .'</strong> ' .$return['text']. '
				<button type="button" class="close" data-dismiss="alert" aria-label="' .lang('close'). '">
					<span aria-hidden="true" class="mdi mdi-close-circle-outline"></span>
				</button>
			</div>';
        unset($return['title'], $return['text']);

    }

    if (isset($return['toast']) AND isset($return['toast'][1]) AND $return['toast'][1] != '') {

        switch ($return['toast'][3]) {
            case 'success':
                $return['toast'][1] = '<span class="mdi mdi-check-circle-outline"></span> ' .$return['toast'][1];
                $class = ' alert-' .$return['toast'][3];
                break;

            case 'danger':
                $return['toast'][1] = '<span class="mdi mdi-alert-octagram"></span> ' .$return['toast'][1];
                $class = ' alert-' .$return['toast'][3];
                break;

            case 'warning':
                $return['toast'][1] = '<span class="mdi mdi-alert"></span> ' .$return['toast'][1];
                $class = ' alert-' .$return['toast'][3];
                break;

            case 'primary':
            case 'secondary':
            case 'light':
            case 'dark':
            case 'info':
                $class = ' alert-' .$return['toast'][3];
                break;

            default:
                $class = '';
                break;
        }

        $return['toast'] = '
			<div class="toast ml-auto' .$class.' [ID]" role="alert" 
			' .($return['toast'][0] ? 'data-delay="'.$return['toast'][0].'000"' : 'data-autohide="false"'). '>
				<div class="toast-header">
					<strong class="mr-auto">' .$return['toast'][1]. '</strong>
					<button type="button" class="ml-2 mb-1 close text-muted" data-dismiss="toast" 
					aria-label="' .lang('close'). '">
						<span aria-hidden="true" class="mdi mdi-close-circle-outline"></span>
					</button>
				</div>
				<div class="toast-body">' .$return['toast'][2]. '</div>
			</div>';

    } else {
        unset($return['toast']);
    }

    return $return;
}