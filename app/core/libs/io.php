<?php

function filter($data=null, $parameter='text')
{
/*	parameters
- text - strip_tags - trim
- html- htmlspecialchars - trim
- check - strip_tags - trim ? on : off
- pass - password_hash - trim
*/
if (is_array($data))
{
$_value = [];
foreach ($data as $key => $value)
{
if (is_array($value))
{
$_value[$key] = filter($value, $parameter);
}
else
{
switch ($parameter)
{
case 'html': $_value[$key] = htmlspecialchars(trim($value)); break;
case 'check': $_value[$key] = !is_null($value) ? 'on' : 'off'; break;
case 'check_as_boolean': $_value[$key] = !is_null($value); break;
case 'int': $_value[$key] = (integer)$value; break;
case 'float': $_value[$key] = floatval($value); break;
case 'pass': $_value[$key] = password_hash(trim($value), PASSWORD_DEFAULT); break;
case 'nulled_pass': $_value[$key] = trim($value) != '' ? password_hash(trim($value), PASSWORD_DEFAULT) : null; break;
case 'date': $_value[$key] = strtotime($value. ' 12:00'); break;
case 'nulled_text': $_value[$key] = strip_tags(trim($value)) == '' ? null : strip_tags(trim($value)); break;
default: $_value[$key] = strip_tags(trim($value)); break;
}
}
}
$data = $_value;
}
else
{
switch ($parameter)
{
case 'html': $data = htmlspecialchars(trim($data)); break;
case 'check': $data = !is_null($data) ? 'on' : 'off'; break;
case 'check_as_boolean': $data = !is_null($data); break;
case 'int': $data = (integer)$data; break;
case 'float': $data = (float)$data; break;
case 'pass': $data = password_hash(trim($data), PASSWORD_DEFAULT); break;
case 'nulled_pass': $data = trim($data) != '' ? password_hash(trim($data), PASSWORD_DEFAULT) : null; break;
case 'date': $data = strtotime($data. ' 12:00'); break;
case 'nulled_text': $data = strip_tags(trim($data)) == '' ? null : strip_tags(trim($data)); break;
default: $data = strip_tags(trim($data)); break;
}

}
return $data;

}

function in($extract, $var): array
{
$return = [];
if (is_array($extract) AND is_array($var))
{
foreach ($extract as $key => $value)
{
if (isset($var[$key])) $return[$key] = filter($var[$key], $value);
else $return[$key] = filter(null, $value);
}
}
return $return;
}


function out($type, $val) {

switch ($type) {
case 'float_with_zero':
$val = str_replace(['.', ','], ['', '.'], $val);

if (strpos($val, '.') === false) {
$val .= '.00';
}
break;

case 'with_comma':
$val = str_replace(['.', ','], ['', '.'], $val);

if (strpos($val, '.') === false) {
$val .= '.00';
}
$val = str_replace('.', ',', $val);
break;

case 'row_count':
$val = count(preg_split('/[\n\r]/', $val)) - 1;

if ($val <= 0) $val = 1;
break;

default:
# code...
break;
}

return $val;

}

function price($price): float
{

$price = str_replace(',', '.', $price);
$price = preg_replace("/[^0-9.]/", "", $price);
$price = str_replace('.', '',substr($price, 0, -3)) . substr($price, -3);

return (float) $price;
}

function price2SQL($price) {

$price = str_replace('.', '', (string)$price);
$price = str_replace(',', '.', $price);
return $price;
}