		<div class="wrap">
			<div class="container">
				<div class="row">
					<div class="col-12 col-lg-3 col-md-4 col-sm-6">
						<h1><?php echo KN\Helpers\Base::lang('base.welcome'); ?></h1>
						<p><?php echo KN\Helpers\Base::lang('base.welcome_message'); ?></p>
					</div>
					<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#informationRequest">
						<?php echo KN\Helpers\Base::lang('base.information_request_form'); ?>
					</button>
					<div class="modal fade" id="informationRequest" tabindex="-1" aria-labelledby="informationRequestLabel" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered modal-lg">
							<div class="modal-content">
								<div class="modal-body">
									<h5 class="modal-title mb-3" id="informationRequestLabel"><?php echo KN\Helpers\Base::lang('base.information_request_form'); ?></h5>
									<?php echo $prepareForm; ?>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo \KN\Helpers\Base::lang('base.close'); ?></button>
									<button type="submit" form="formAdd" class="btn btn-success"><?php echo \KN\Helpers\Base::lang('base.add'); ?></button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		