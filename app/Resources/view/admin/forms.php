		<div class="container-fluid">
			<div class="row">
				<header class="col-12 dash-header">
					<div class="d-flex align-items-center">
						<h1><i class="<?php echo isset($icon) !== false ? $icon : null; ?>"></i> <?php echo $formName; ?></h1>
						<?php
						if ($this->authority('management/forms/:form/add')) {
						?>
							<button data-bs-toggle="modal" data-bs-target="#addModal" class="btn btn-dark ms-auto">
								<?php echo \KN\Helpers\Base::lang('base.add_new'); ?>
							</button>
						<?php
						}	?>
					</div>
					<p><?php echo $description; ?></p>
				</header>
				<div class="col-12 dash-content">
					<div class="bg-white p-2 mb-2 rounded shadow-sm" 
						id="contentsTable" 
						data-source="<?php echo $this->url('/management/forms/' . $form . '/list') ?>"
						data-columns='<?php echo json_encode($formDatas['columns']); ?>'
					></div>
				</div>
			</div>
		</div>
		<?php
		if ($this->authority('management/forms/:form/:id')) {
		?>
			<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-xl modal-xxl">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="editModalLabel"><?php echo \KN\Helpers\Base::lang('base.view'); ?></h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo \KN\Helpers\Base::lang('base.close'); ?>"></button>
						</div>
						<div class="modal-body">
							<form id="contentEdit"></form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo \KN\Helpers\Base::lang('base.close'); ?></button>
							<?php
							if ($this->authority('management/forms/:form/:id/update')) {
							?>
								<button type="submit" form="contentEdit" class="btn btn-primary"><?php echo \KN\Helpers\Base::lang('base.edit'); ?></button>
							<?php
							}	?>
						</div>
					</div>
				</div>
			</div>
		<?php
		}	?>