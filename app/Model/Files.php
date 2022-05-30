<?php

/**
 * @package KN
 * @subpackage Model
 */

declare(strict_types=1);

namespace KN\Model;

use KN\Core\Model;
use KN\Helpers\Base;

final class Files extends Model {

    function __construct () {

        $this->table = 'files';
        $this->created = true;
        $this->updated = true;

        parent::__construct();

    }
}