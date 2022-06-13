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
				<div class="modal-dialog modal-dialog-centered modal-xl">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="addModalLabel"><?php echo \KN\Helpers\Base::lang('base.add_new'); ?></h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo \KN\Helpers\Base::lang('base.close'); ?>"></button>
						</div>
						<div class="modal-body">
							<form class="row g-1" data-kn-form id="menuAdd" method="post" action="<?php echo $this->url('management/menus/add'); ?>">
								<div class="form-loader">
									<div class="spinner-border text-light" role="status">
										<span class="visually-hidden"><?php echo \KN\Helpers\Base::lang('base.loading'); ?></span>
									</div>
								</div>
								<div class="col-12 form-info">
								</div>
								<div class="col-12 col-md-11">
									<div class="form-floating">
										<input type="text" class="form-control" required name="key" id="menuKey" placeholder="<?php echo \KN\Helpers\Base::lang('base.key'); ?>">
										<label for="menuKey"><?php echo \KN\Helpers\Base::lang('base.key'); ?></label>
									</div>
								</div>
								<div class="col-12 col-md-1">
									<div class="d-grid">
										<button class="btn btn-primary btn-lg" type="button" data-kn-action="manipulation" data-kn-manipulation='<?php

										$languages = \KN\Helpers\Base::config('app.available_languages');
										$tabContents = '';
										$nameArea = '
										<div class="col-12 kn-multilang-content">
											<div class="kn-multilang-content-switch">
												<div class="nav nav-pills" id="menuName-tablist" role="tablist" aria-orientation="vertical">';
												foreach ($languages as $i => $lang) {
													$nameArea .= '
													<button class="nav-link'.($i===0 ? ' active' : '').'" id="name-tab-'.$lang.'(DYNAMIC_ID)" data-bs-toggle="pill" data-bs-target="#name-'.$lang.'(DYNAMIC_ID)" type="button" role="tab" aria-controls="name-tab-'.$lang.'(DYNAMIC_ID)" aria-selected="'.($i===0 ? 'true' : 'false').'">
														' . \KN\Helpers\Base::lang('langs.' . $lang ) . '
													</button>';
													$tabContents .= '
													<div class="tab-pane fade'.($i === 0 ? ' show active' : '').'" id="name-'.$lang.'(DYNAMIC_ID)" role="tabpanel" aria-labelledby="name-tab-'.$lang.'(DYNAMIC_ID)">
														<div class="form-floating">
															<input type="text" class="form-control" name="links[name][' . $lang . ']" id="menuName' . $lang . '(DYNAMIC_ID)" placeholder="'.\KN\Helpers\Base::lang('base.name').'">
															<label for="menuName' . $lang . '">'.\KN\Helpers\Base::lang('base.name').'</label>
														</div>
													</div>';
												}
										$nameArea .= '
												</div>
											</div>
											<div class="tab-content">
												' . $tabContents . '
											</div>
										</div>';

										echo json_encode([
											'dragger' => true,
											'manipulation' => [
												'#addModal .kn-menu-drag' => [
													'html_append' => '
														<div class="kn-menu-drag kn-menu-item">
															<div class="row g-1">
																<div class="col-12 col-md-11">
																	<div class="row g-1">
																		<div class="col-12">
																			' . $nameArea . '
																		</div>
																		<div class="col-12">
																			<div class="form-floating">
																				<input type="url" class="form-control form-control-sm" name="links[direct_link]" placeholder="' . \KN\Helpers\Base::lang('base.direct_link').'">
																				<label>' . \KN\Helpers\Base::lang('base.direct_link').'</label>
																			</div>
																		</div>
																		<div class="col-12">
																			<div class="row g-1">
																				<div class="col-sm-8">
																					<div class="form-floating">
																						<select data-kn-change="'.$this->url('management/menus/get-params').'" class="form-select form-select-sm" name="links[dynamic_link][module]" aria-label="' . \KN\Helpers\Base::lang('base.module').'">
																							'.$menuOptions.'
																						</select>
																						<label>' . \KN\Helpers\Base::lang('base.module').'</label>
																					</div>
																				</div>
																				<div class="col-sm-4">
																					<div class="form-floating">
																						<select class="form-select form-select-sm" name="links[dynamic_link][parameter]" aria-label="' . \KN\Helpers\Base::lang('base.parameter').'">
																						</select>
																						<label>' . \KN\Helpers\Base::lang('base.parameter').'</label>
																					</div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="col-12 col-md-1">
																	<div class="d-grid gap-2">
																		<button class="btn btn-danger btn-sm" type="button" data-kn-action="remove" data-kn-parent=".kn-menu-item">
																			<i class="ti ti-circle-minus"></i>
																		</button>
																		<button class="btn btn-dark btn-sm kn-menu-item-dragger" type="button">
																			<i class="ti ti-drag-drop"></i>
																		</button>
																		<input type="checkbox" name="links[blank]" class="btn-check" id="targetBlank(DYNAMIC_ID)" autocomplete="off">
																		<label class="btn btn-outline-primary btn-sm" for="targetBlank(DYNAMIC_ID)">
																			<i class="ti ti-external-link"></i>
																		</label><br>
																	</div>
																</div>
															</div>
														</div>
													',
													'html_append_dynamic' => true
												]
											]
										]); ?>'>
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
							<button type="submit" form="menuAdd" class="btn btn-success"><?php echo \KN\Helpers\Base::lang('base.add'); ?></button>
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