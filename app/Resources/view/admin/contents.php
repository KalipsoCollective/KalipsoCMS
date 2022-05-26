		<div class="container-fluid">
			<div class="row">
				<header class="col-12 dash-header">
					<div class="d-flex align-items-center">
						<h1><i class="<?php echo isset($icon) !== false ? $icon : null; ?>"></i> <?php echo $moduleName; ?></h1>
						<?php
						if ($this->authority('management/:module/add')) {
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
					<div class="bg-white p-2 mb-2 rounded shadow-sm" id="schemasTable"></div>
				</div>
			</div>
		</div>
		<?php
		if ($this->authority('management/:module/add')) {
			$languages = \KN\Helpers\Base::config('app.available_languages');
			?>
			<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-xl">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="addModalLabel"><?php echo \KN\Helpers\Base::lang('base.add_new'); ?></h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo \KN\Helpers\Base::lang('base.close'); ?>"></button>
						</div>
						<div class="modal-body">
							<form class="row g-2" data-kn-form id="schemaAdd" method="post" action="<?php echo $this->url('management/roles/add'); ?>">
								<div class="form-loader">
									<div class="spinner-border text-light" role="status">
										<span class="visually-hidden"><?php echo \KN\Helpers\Base::lang('base.loading'); ?></span>
									</div>
								</div>
								<div class="col-12 form-info">
								</div>
								<?php 
								$li = '';
								$content = '';
								foreach ($languages as $index => $language) {
									$li .= '
									<button id="schemaName'.$language.'Tab" class="nav-link'.($index === 0 ? ' active' : '').'" data-bs-toggle="pill" data-bs-target="#schemaName'.$language.'" type="button" role="tab" aria-selected="'.($index === 0 ? 'true' : 'false').'" aria-controls="schemaName'.$language.'">
										' . \KN\Helpers\Base::lang('langs.' . $language) . '
									</button>';

									$content .= '
									<div class="tab-pane fade'.($index === 0 ? ' show active' : '').'" id="#schemaName'.$language.'" role="tabpanel" aria-labelledby="schemaName'.$language.'Tab">
										<div class="form-floating">
											<input type="text" class="form-control" required name="name['.$language.']" id="name_'.$language.'" 
											placeholder="'.\KN\Helpers\Base::lang('base.name').'">
											<label for="name_'.$language.'">'.\KN\Helpers\Base::lang('base.name').' ' . \KN\Helpers\Base::lang('langs.' . $language) . '</label>
										</div>
									</div>';
								}	?>
								<div class="col-12">
									<div class="d-flex align-items-start">
										<div class="nav flex-column nav-pills me-3" id="nameTab" role="tablist" aria-orientation="vertical">
											<?php echo $li; ?>
										</ul>
									</div>
									<div class="tab-content" id="nameTabContent">
										<?php echo $content; ?>
									</div>
									<?php /*
									<div class="form-floating">
										<input type="text" class="form-control" required name="icon" id="schemaIcon" 
										placeholder="<?php echo \KN\Helpers\Base::lang('base.icon'); ?>">
										<label for="schemaIcon"><?php echo \KN\Helpers\Base::lang('base.icon'); ?></label>
									</div>*/ ?>
								</div>
								<div class="col-12">
									<div class="form-floating">
										<select class="form-select" id="roleRoutes" required multiple style="height: 300px" name="routes[]" aria-label="<?php echo \KN\Helpers\Base::lang('base.routes'); ?>">
											<?php
											/*
											foreach ($roles as $route => $detail) {
												echo '
												<option value="' . $route . '"' . ($detail['default'] ? ' selected' : '') . '>
													' . \KN\Helpers\Base::lang($detail['name']) . '
												</option>';
											}*/	?>
										</select>
										<label for="roleRoutes"><?php echo \KN\Helpers\Base::lang('base.routes'); ?></label>
									</div>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo \KN\Helpers\Base::lang('base.close'); ?></button>
							<button type="submit" form="schemaAdd" class="btn btn-success"><?php echo \KN\Helpers\Base::lang('base.add'); ?></button>
						</div>
					</div>
				</div>
			</div>
		<?php
		}
		if ($this->authority('management/schemas/:id')) {
		?>
			<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="editModalLabel"><?php echo \KN\Helpers\Base::lang('base.view'); ?></h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo \KN\Helpers\Base::lang('base.close'); ?>"></button>
						</div>
						<div class="modal-body">
							<form class="row g-2" data-kn-form id="roleUpdate" method="post" action="">
								<div class="form-loader">
									<div class="spinner-border text-light" role="status">
										<span class="visually-hidden"><?php echo \KN\Helpers\Base::lang('base.loading'); ?></span>
									</div>
								</div>
								<div class="col-12 form-info">
								</div>
								<div class="col-12">
									<div class="form-floating">
										<input type="text" class="form-control" required name="name" id="theRoleName" placeholder="<?php echo \KN\Helpers\Base::lang('base.name'); ?>">
										<label for="theRoleName"><?php echo \KN\Helpers\Base::lang('base.name'); ?></label>
									</div>
								</div>
								<div class="col-12">
									<div class="form-floating">
										<select class="form-select" id="theRoleRoutes" required multiple style="height: 300px" name="routes[]" aria-label="<?php echo \KN\Helpers\Base::lang('base.routes'); ?>">
										</select>
										<label for="theRoleRoutes"><?php echo \KN\Helpers\Base::lang('base.routes'); ?></label>
									</div>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo \KN\Helpers\Base::lang('base.close'); ?></button>
							<?php
							if ($this->authority('management/roles/:id/update')) {
							?>
								<button type="submit" form="roleUpdate" class="btn btn-primary"><?php echo \KN\Helpers\Base::lang('base.update'); ?></button>
							<?php
							}	?>
						</div>
					</div>
				</div>
			</div>
		<?php
		}
		if ($this->authority('management/schemas/:id/delete')) {
		?>
			<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="deleteModalLabel"><?php echo \KN\Helpers\Base::lang('base.delete_role'); ?></h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo \KN\Helpers\Base::lang('base.close'); ?>"></button>
						</div>
						<div class="modal-body">
							<form class="row g-2" data-kn-form id="roleDelete" method="post" action="">
								<div class="form-loader">
									<div class="spinner-border text-light" role="status">
										<span class="visually-hidden"><?php echo \KN\Helpers\Base::lang('base.loading'); ?></span>
									</div>
								</div>
								<div class="col-12 form-info">
								</div>
								<div class="col-12">
									<div class="form-floating">
										<select class="form-select" name="transfer_role" id="availableRoles" required 
										aria-label="<?php echo \KN\Helpers\Base::lang('base.routes'); ?>">
										</select>
										<label for="availableRoles"><?php echo \KN\Helpers\Base::lang('base.role_to_transfer_users'); ?></label>
									</div>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo \KN\Helpers\Base::lang('base.close'); ?></button>
							<button type="submit" form="roleDelete" class="btn btn-danger"><?php echo \KN\Helpers\Base::lang('base.delete'); ?></button>
						</div>
					</div>
				</div>
			</div>
		<?php
		}	?>