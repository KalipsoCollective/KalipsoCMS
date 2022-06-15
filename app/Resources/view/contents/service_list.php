		<div class="wrap">
			<div class="container">
				<div class="row"> 
					<?php
					if (is_array($detail)) {
						foreach ($detail as $service) {
							
							echo '
							<div class="col-12 col-md-4">
								<div class="card" style="width: 100%;">
									'.
									(isset($service->header_image_src[0]->original) !== false ? '<img src="' . $service->header_image_src[0]->original . '" class="card-img-top" alt="' . $service->title . '">' : '') 
									.'
									<div class="card-body">
										<h5 class="card-title">' . $service->title . '</h5>
										<p class="card-text">' . $service->description . '</p>
										<a href="' . $this->dynamicUrl(
											$moduleDetail['routes']['detail'][$this->lang][1], 
											['slug' => $service->slug]
										) . '" class="btn btn-primary">-></a>
									</div>
								</div>
							</div>';
						}
					} else {
						echo \KN\Helpers\Base::lang('base.record_not_found');
					}	?>
				</div>
			</div>
		</div>
		