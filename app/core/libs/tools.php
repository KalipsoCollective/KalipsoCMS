<?php

/**
 * Output Writer with Styled
 * @param $output: Printable value
 * @param bool $exit: System shutdown process after writing
 */

function varFuck($output, $exit = false)
{
    echo '<pre>';
    var_dump($output);
    echo '</pre>';

    if ($exit) {
        exit;
    }
}