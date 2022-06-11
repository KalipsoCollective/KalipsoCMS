		<div class="container-fluid">
			<div class="row">
				<header class="col-12 dash-header">
					<div class="d-flex align-items-center">
						<h1><i class="ti ti-chart-dots-3"></i> <?php echo \KN\Helpers\Base::lang('base.menus'); ?></h1>
						<?php
						if ($this->authority('management/menus/add')) {
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
					<div class="bg-white p-2 mb-2 rounded shadow-sm" id="menusTable"></div>
				</div>
			</div>
		</div>
		<?php
		if ($this->authority('management/menus/add')) {
		?>
			<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="addModalLabel"><?php echo \KN\Helpers\Base::lang('base.add_new'); ?></h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo \KN\Helpers\Base::lang('base.close'); ?>"></button>
						</div>
						<div class="modal-body">
							<form class="row g-1" data-kn-form id="roleAdd" method="post" action="<?php echo $this->url('management/menus/add'); ?>">
								<div class="form-loader">
									<div class="spinner-border text-light" role="status">
										<span class="visually-hidden"><?php echo \KN\Helpers\Base::lang('base.loading'); ?></span>
									</div>
								</div>
								<div class="col-12 form-info">
								</div>
								<div class="col-12 col-md-11">
									<div class="form-floating">
										<input type="text" class="form-control" required name="name" id="roleName" placeholder="<?php echo \KN\Helpers\Base::lang('base.name'); ?>">
										<label for="roleName"><?php echo \KN\Helpers\Base::lang('base.name'); ?></label>
									</div>
								</div>
								<div class="col-12 col-md-1">
									<div class="d-grid">
										<button class="btn btn-primary btn-lg" type="button" data-kn-action="manipulation" data-kn-manipulation='<?php
										echo json_encode([
											'manipulation' => [
												'#addModal .kn-menu-drag' => [
													'html_append' => '
														<div class="kn-menu-item">
															<div class="row g-1">
																<div class="col-12 col-md-11">
																	<div class="row g-1">
																		<div class="col-12">
																			<div class="form-floating">
																				<input type="url" class="form-control form-control-sm" name="links[0][direct_link]" id="directLink" placeholder="' . \KN\Helpers\Base::lang('base.direct_link').'">
																				<label for="directLink">' . \KN\Helpers\Base::lang('base.direct_link').'</label>
																			</div>
																		</div>
																		<div class="col-12">
																			<div class="row g-1">
																				<div class="col-sm-8">
																					<div class="form-floating">
																						<select class="form-select form-select-sm" id="theRoleRoutes" name="links[0][dynamic_link][module]" aria-label="' . \KN\Helpers\Base::lang('base.module').'">
																						</select>
																						<label for="theRoleRoutes">' . \KN\Helpers\Base::lang('base.module').'</label>
																					</div>
																				</div>
																				<div class="col-sm-4">
																					<div class="form-floating">
																						<select class="form-select form-select-sm" id="theRoleRoutes" name="links[0][dynamic_link][parameter]" aria-label="' . \KN\Helpers\Base::lang('base.parameter').'">
																						</select>
																						<label for="theRoleRoutes">' . \KN\Helpers\Base::lang('base.parameter').'</label>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="col-12 col-md-1">
																	<div class="d-grid gap-2">
																		<button class="btn btn-danger" type="button">
																			<i class="ti ti-circle-minus"></i>
																		</button>
																		<button class="btn btn-dark kn-menu-item-dragger" type="button">
																			<i class="ti ti-drag-drop"></i>
																		</button>
																	</div>
																</div>
																<div class="kn-menu-drag">
																</div>
																</div>
															</div>
														</div>
													'
												]
											]

										]);

										?>'>
											<i class="ti ti-circle-plus"></i>
										</button>
									</div>
								</div>
								<div class="kn-menu-drag">
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo \KN\Helpers\Base::lang('base.close'); ?></button>
							<button type="submit" form="roleAdd" class="btn btn-success"><?php echo \KN\Helpers\Base::lang('base.add'); ?></button>
						</div>
					</div>
				</div>
			</div>
		<?php
		}
		if ($this->authority('management/menus/:id')) {
		?>
			<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-lg">
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
							if ($this->authority('management/menus/:id/update')) {
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
		if ($this->authority('management/menus/:id/delete')) {
		?>
			<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered modal-lg">
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