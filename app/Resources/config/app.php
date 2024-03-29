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
	'title_format' => '[TITLE] [SEPERATOR] [APP]',
	'available_languages' => ['tr', 'en', 'ar', 'mk'],
	'upload_accept' => 'image/*',
	'upload_convert' => 'webp', // jpg, png, webp, jpeg, gif, bmp
	'upload_max_width' => 1920,
	'upload_max_dimension' => [4096, 2160], // 4K
	'upload_max_size' => 5242880 * 3, // 5 MB * 3 = 15 MB
	'upload_png_quality' => 8, // without lossless = 0 or (1 - 9)
	'upload_webp_quality' => 85, // without lossless = 0 or (1 - 100)
	'upload_jpeg_quality' => 85, // without lossless = 0 or (1 - 100)
];