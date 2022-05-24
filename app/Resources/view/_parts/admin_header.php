<!doctype html>
<html lang="<?php echo KN\Helpers\Base::lang('lang.iso_code'); ?>" dir="<?php echo KN\Helpers\Base::lang('lang.dir'); ?>">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $title; ?></title>
		<?php /*<link rel="stylesheet" type="text/css" href="<?php echo KN\Helpers\Base::assets('libs/bootstrap/bootstrap.min.css'); ?>"> */ ?>
		<link rel="stylesheet" type="text/css" href="<?php echo KN\Helpers\Base::assets('libs/kalipsotable/kalipso.table.css'); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo KN\Helpers\Base::assets('libs/nprogress/nprogress.css'); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo KN\Helpers\Base::assets('css/kalipso.libs.css'); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo KN\Helpers\Base::assets('css/theme.min.css'); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo KN\Helpers\Base::assets('css/kalipso.next.css'); ?>">
		<link rel="canonical" href="<?php echo $this->url($this->request->uri); ?>" />
		<meta name="copyright" content="<?php echo KN\Helpers\Base::config('settings.name'); ?>">
		<meta name="generator" content="<?php echo KN\Helpers\Base::config('app.name') . ' v' . KN_VERSION; ?>" />
		<meta name="author" content="<?php echo KN\Helpers\Base::config('app.name'); ?>">
		<meta name="title" content="<?php echo $title; ?>">
		<meta name="description" content="<?php echo $description; ?>">
	</head>
	<body id="wrap" class="bg-light">
		<?php echo \KN\Helpers\Base::sessionStoredAlert(); ?>
		<div id="db-wrapper">