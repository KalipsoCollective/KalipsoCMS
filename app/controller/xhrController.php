<?php

use app\modules\User;

$returnCode = 401;
$returnData = '<pre>The only thing wrong is not what your request.</pre>';

if (isset($this->request[0]) !== false) {

    switch ($this->request[0]) {
        case 'user':
            if (isset($this->request[1]) !== false) {

                switch ($this->request[1]) {
                    case 'login': {
                        $return = (new User())->login();
                    }
                }

            }
            break;

    }

}

if (isset($return) !== false AND is_array($return)) {

    http('content_type', json_encode(generateResponse($return)), 'json');

} else {

    http($returnCode, $returnData);
}