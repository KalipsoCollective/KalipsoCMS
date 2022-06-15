		<div class="wrap">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-12 col-md-8">
						<h1><?php echo KN\Helpers\Base::lang('base.contact'); ?></h1>
						<p><?php echo KN\Helpers\Base::lang('base.contact_detail'); ?></p>
					</div>
					<div class="col-12 col-md-8">
						<?php
						if (isset($form) !== false) {
							echo $form;
							echo '<button class="btn btn-primary btn-lg mt-2" type="submit" form="formAdd">' . KN\Helpers\Base::lang('base.submit') . '</button>';
						}	?>
					</div>
				</div>
			</div>
		</div>
		