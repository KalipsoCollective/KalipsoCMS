<?php

	/*
		If you want to take action as a result of module-specific developments while the content controller is running, you can use hooks.
		$type: Indicates in which method the function works. "detail" or "listing" values are displayed.
		$container: An instance of the core class.
		$controller: The instance of the content controller.
		$extract: Output of the module content.
		$output: Output from controller method. You can make changes to it.
	*/

	return function($type, $container, $controller, $extract, $output) {
			
		return $output;
	}

?>