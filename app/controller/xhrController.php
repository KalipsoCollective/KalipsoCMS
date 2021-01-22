<?php

http('content_type', 'json');

use app\modules\User;

$returnCode = 401;
$returnData = '<pre>The only thing wrong is not what your request.</pre>';

if (isset($this->request[0]) !== false) {

    switch ($this->request[0]) {
        case 'user':
            if (isset($this->request[1]) !== false) {

                switch ($this->request[1]) {
                    case 'login': {
                        $return = (new User(false))->login();
                    }
                }

            }
            break;

    }

}

if (isset($return) !== false AND is_array($return)) {

    echo 'response';

} else {

    http($returnCode, $returnData);
}