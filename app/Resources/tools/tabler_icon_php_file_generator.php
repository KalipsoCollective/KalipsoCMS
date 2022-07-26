<?php 

$tags = file_get_contents('app/tags.json');

$tags = json_decode($tags, true);

$icons = [];

echo '<pre>' . PHP_EOL;
echo 'return [' . PHP_EOL;
foreach ($tags as $name => $tag) {
	$icons[$name] = implode(', ', $tag['tags']);
	echo '	\'ti ti-'.$name.'\' => \''.implode(', ', $tag['tags']).'\',' . PHP_EOL;
}

echo '];';
