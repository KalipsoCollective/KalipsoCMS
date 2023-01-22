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
		
		/*
		if ($controller->module === 'home_contents') {

			$container = $controller->get();

			// title
			$output['arguments']['title'] = isset($output['arguments']['detail'][0]->title) 
				? str_replace(PHP_EOL, ' ', $output['arguments']['detail'][0]->title) 
				: Base::lang('base.welcome');

			// home title
			$output['arguments']['homeTitle'] = isset($output['arguments']['detail'][0]->title) 
				? $output['arguments']['detail'][0]->title
				: '';

			// home images
			if (isset($output['arguments']['detail'][0]->home_images_src) !== false) {
				$output['arguments']['homeSlides'] = $output['arguments']['detail'][0]->home_images_src;
			}

			// home subtitle
			$homeSubtitle = '';
			if (isset($output['arguments']['detail'][0]->subtitle) !== false) {
				$homeSubtitle = html_entity_decode($output['arguments']['detail'][0]->subtitle);
				if (strpos($homeSubtitle, '"') !== false) {
					$homeSubtitle = explode('"', $homeSubtitle, 3);

					$homeSubtitle = $homeSubtitle[0] . ' <b>"' . $homeSubtitle[1] . '"</b> ' . $homeSubtitle[2];
				}
			}
			$output['arguments']['homeSubtitle'] = $homeSubtitle;

			$homeAbout = '';
			if (isset($output['arguments']['detail'][0]->about) !== false) {
				$homeAbout = $output['arguments']['detail'][0]->about;
			}
			$output['arguments']['homeAbout'] = $homeAbout;

			$homeAboutSide = '';
			if (isset($output['arguments']['detail'][0]->about_side) !== false) {
				$homeAboutSide = $output['arguments']['detail'][0]->about_side;
			}
			$output['arguments']['homeAboutSide'] = $homeAboutSide;


			// home titles
			$homeProductsTitle = '';
			if (isset($output['arguments']['detail'][0]->products_title) !== false) {
				$homeProductsTitle = $output['arguments']['detail'][0]->products_title;
			}
			$output['arguments']['homeProductsTitle'] = $homeProductsTitle;

			
			// Home Slider
			$get = (new Contents());
			$get = $get->select('

					id, 
					IFNULL(JSON_UNQUOTE(JSON_EXTRACT(input, \'$.title.'.Base::lang('lang.code').'\')), "-") AS title,
					IFNULL(JSON_UNQUOTE(JSON_EXTRACT(input, \'$.content.'.Base::lang('lang.code').'\')), "-") AS content,
					IFNULL(JSON_UNQUOTE(JSON_EXTRACT(input, \'$.slide_content.'.Base::lang('lang.code').'\')), "-") AS slide_content,
					IFNULL(JSON_UNQUOTE(JSON_EXTRACT(input, \'$.link.'.Base::lang('lang.code').'\')), "-") AS link,
					IFNULL(JSON_UNQUOTE(JSON_EXTRACT(input, \'$.link_title.'.Base::lang('lang.code').'\')), "-") AS link_title,
					REPLACE(
						REPLACE(
							REPLACE(
								REPLACE(
									IFNULL(JSON_EXTRACT(input, \'$.images\'), ""),
									" ",
									""
								),
								"\"",
								""
							),
							"]",
							""
						),
						"[",
						""
					) AS images,
					(SELECT JSON_ARRAYAGG(files) AS files FROM files WHERE FIND_IN_SET(id, images)) AS image_src
			');
			$get = $get->where('module', 'home_gallery')->limit(2)->getAll();

			foreach ($get as $k => $v) {
				$v->image_src = json_decode($v->image_src);
				foreach ($v->image_src as $key => $imageData) {
					if (is_string($imageData)) {
						$imageData = json_decode($imageData);
						$v->image_src[$key] = (object)[];
					}
					foreach ($imageData as $dimension => $src) {
						$v->image_src[$key]->{$dimension} = Base::base('upload/' . $src);
					}
				}
				$get[$k] = $v;
			}
			$output['arguments']['homeGallery'] = $get;


			// Our Values
			$get = (new Contents());
			$get = $get->select('
					id, 
					IFNULL(JSON_UNQUOTE(JSON_EXTRACT(input, \'$.title.'.Base::lang('lang.code').'\')), "-") AS title,
					IFNULL(JSON_UNQUOTE(JSON_EXTRACT(input, \'$.content.'.Base::lang('lang.code').'\')), "-") AS content,
					REPLACE(
						REPLACE(
							REPLACE(
								REPLACE(
									IFNULL(JSON_EXTRACT(input, \'$.image\'), ""),
									" ",
									""
								),
								"\"",
								""
							),
							"]",
							""
						),
						"[",
						""
					) AS image,
					(SELECT JSON_ARRAYAGG(files) AS files FROM files WHERE FIND_IN_SET(id, image)) AS image_src
			');
			$get = $get->where('module', 'our_values')->orderBy('id', 'desc')->getAll();
			foreach ($get as $k => $v) {
				if (isset($v->image_src) !== false AND $v->image_src) {
					$v->image_src = json_decode($v->image_src);
					foreach ($v->image_src as $key => $imageData) {
						if (is_string($imageData)) {
							$imageData = json_decode($imageData);
							$v->image_src[$key] = (object)[];
						}
						foreach ($imageData as $dimension => $src) {
							$v->image_src[$key]->{$dimension} = Base::base('upload/' . $src);
						}
					}
					$get[$k] = $v;
				}
			}
			$output['arguments']['ourValues'] = $get;

			// Products
			$products = [];
			$model = (new Contents());
			$get = $model->select('
				id,
				IFNULL(JSON_UNQUOTE(JSON_EXTRACT(input, \'$.title.'.Base::lang('lang.code').'\')), "-") AS title
			');
			$get = $get->where('module', 'product_categories')->orderBy('created_at', 'desc')->limit(2)->getAll();
			foreach ($get as $cat) {
				$pro = $model->select('
					id,
					IFNULL(JSON_UNQUOTE(JSON_EXTRACT(input, \'$.title.'.Base::lang('lang.code').'\')), "-") AS title,
					REPLACE(
						REPLACE(
							REPLACE(
								REPLACE(
									IFNULL(JSON_EXTRACT(input, \'$.images\'), ""),
									" ",
									""
								),
								"\"",
								""
							),
							"]",
							""
						),
						"[",
						""
					) AS images,
					(SELECT JSON_ARRAYAGG(files) AS files FROM files WHERE FIND_IN_SET(id, images)) AS images_src
				');
				$pro = $pro
					->where('module', 'products')
					->where('IFNULL(JSON_UNQUOTE(JSON_EXTRACT(input, \'$.category\')), "-")', $cat->id)
					->orderBy('created_at', 'desc')
					->limit(1)
					->getAll();

				if (!empty($pro)) {

					foreach ($pro as $k => $v) {
						$v->images_src = json_decode($v->images_src);
						foreach ($v->images_src as $key => $imageData) {
							if (is_string($imageData)) {
								$imageData = json_decode($imageData);
								$v->images_src[$key] = (object)[];
							}
							foreach ($imageData as $dimension => $src) {
								$v->images_src[$key]->{$dimension} = Base::base('upload/' . $src);
							}
						}
						$pro[$k] = $v;
					}

					$products[$cat->id] = [
						'id' => $cat->id,
						'title' => $cat->title,
						'products' => $pro,
					];
				}

			}

			$output['arguments']['products'] = $products;

		} */

		return $output;
	}

?>