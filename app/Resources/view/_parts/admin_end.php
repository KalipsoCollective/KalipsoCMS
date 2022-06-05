			<footer class="mt-auto bg-white text-dark shadow-sm rounded dash-footer">
				<div class="row justify-content-between align-items-center">
					<div class="col-12 col-md-6">
						<?php echo \KN\Helpers\Base::lang('base.copyright') . ' © ' . date('Y') . ' | ' . \KN\Helpers\Base::lang('base.all_rights_reserved'); ?> 
						<small class="badge bg-primary"><?php echo 'v' . KN_VERSION; ?></small>
					</div>
					<div class="col-12 col-md-6 d-flex">
						<?php 
						$languages = \KN\Helpers\Base::config('app.available_languages');
						if ($languages AND count($languages) > 1) {
							$currentLang = \KN\Helpers\Base::lang('lang.code');
						?>
							<div class="dropdown ms-auto">
								<a class="btn btn-sm btn-dark dropdown-toggle" href="#" role="button" id="languageChange" data-bs-toggle="dropdown" aria-expanded="false">
									<?php echo \KN\Helpers\Base::lang('base.language') . ': ' . strtoupper($currentLang); ?>
								</a>
								<ul class="dropdown-menu" aria-labelledby="languageChange">
									<?php
									foreach ($languages as $lang) {
										
										echo '
										<li>
											<a class="dropdown-item'.($currentLang == $lang ? ' disabled' : '').'" href="' . $this->request->uri . '?lang='.$lang.'">
												' . strtoupper($lang) . '
											</a>
										</li>';
									}	?>
								</ul>
							</div>
						<?php
						}	?>
					</div>
				</div>
			</footer>
		</div>