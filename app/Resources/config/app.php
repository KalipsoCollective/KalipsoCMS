<?php

/**
	* This section contains settings that are used for the core structure of
	* the system and require only one-time correction. You can add settings 
	* that will not require re-intervention after the first adjustment during development.
	* 
	**/


return [
	'name'		=> 'KalipsoCMS',
	'dev_mode'	=> true,
	'session'	=> 'kalipso',
	'charset'	=> 'utf-8',
	'title_format' => '[TITLE] — [APP]',
	'available_languages' => ['tr'],
	'upload_accept' => 'image/*',
	'upload_convert' => 'webp', // jpg, png, webp, jpeg, gif, bmp
	'upload_max_width' => 1920,
	'upload_max_size' => 5242880, // 5 MB
	'upload_png_quality' => 8, // without lossless = 0 or (1 - 9)
	'upload_webp_quality' => 85, // without lossless = 0 or (1 - 100)
	'upload_jpeg_quality' => 85, // without lossless = 0 or (1 - 100)
];