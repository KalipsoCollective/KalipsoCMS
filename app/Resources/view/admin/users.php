		<div class="container-fluid">
			<div class="row">
				<header class="col-12 dash-header">
					<div class="d-flex align-items-center">
						<h1><i class="ti ti-users"></i> <?php echo \KN\Helpers\Base::lang('base.users'); ?></h1>
						<?php
						if ($this->authority('management/users/add')) {
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
					<div class="bg-white p-2 mb-2 rounded shadow-sm" id="usersTable"></div>
				</div>
			</div>
		</div>
		<?php
		if ($this->authority('management/users/add')) {
		?>
			<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="addModalLabel"><?php echo \KN\Helpers\Base::lang('base.add_new'); ?></h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo \KN\Helpers\Base::lang('base.close'); ?>"></button>
						</div>
						<div class="modal-body">
							<form class="row g-2" data-kn-form id="userAdd" method="post" action="<?php echo $this->url('management/users/add'); ?>">
								<div class="form-loader">
									<div class="spinner-border text-light" role="status">
										<span class="visually-hidden"><?php echo \KN\Helpers\Base::lang('base.loading'); ?></span>
									</div>
								</div>
								<div class="col-12 form-info">
								</div>
								<div class="col-12">
									<div class="form-floating">
										<input type="email" class="form-control" required name="email" id="userEmail" placeholder="<?php echo \KN\Helpers\Base::lang('base.email'); ?>">
										<label for="userEmail"><?php echo \KN\Helpers\Base::lang('base.email'); ?></label>
									</div>
								</div>
								<div class="col-12">
									<div class="form-floating">
										<input type="text" class="form-control" required name="u_name" id="userName" placeholder="<?php echo \KN\Helpers\Base::lang('base.username'); ?>">
										<label for="userName"><?php echo \KN\Helpers\Base::lang('base.username'); ?></label>
									</div>
								</div>
								<div class="col-12">
									<div class="form-floating">
										<input type="text" class="form-control" name="f_name" id="fName" placeholder="<?php echo \KN\Helpers\Base::lang('base.name'); ?>">
										<label for="fName"><?php echo \KN\Helpers\Base::lang('base.name'); ?></label>
									</div>
								</div>
								<div class="col-12">
									<div class="form-floating">
										<input type="text" class="form-control" name="l_name" id="lName" placeholder="<?php echo \KN\Helpers\Base::lang('base.surname'); ?>">
										<label for="lName"><?php echo \KN\Helpers\Base::lang('base.surname'); ?></label>
									</div>
								</div>
								<div class="col-12">
									<div class="form-floating">
										<select class="form-select" id="roles" required name="role_id" aria-label="<?php echo \KN\Helpers\Base::lang('base.role'); ?>">
											<?php
											foreach ($userRoles as $role) {
												echo '
												<option value="' . $role->id . '">
													' . $role->name . '
												</option>';
											}	?>
										</select>
										<label for="roles"><?php echo \KN\Helpers\Base::lang('base.role'); ?></label>
									</div>
								</div>
								<div class="col-12">
									<div class="form-floating">
										<input type="password" class="form-control" name="password" id="userPassword" placeholder="<?php echo \KN\Helpers\Base::lang('base.password'); ?>">
										<label for="userPassword"><?php echo \KN\Helpers\Base::lang('base.password'); ?></label>
									</div>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo \KN\Helpers\Base::lang('base.close'); ?></button>
							<button type="submit" form="userAdd" class="btn btn-success"><?php echo \KN\Helpers\Base::lang('base.add'); ?></button>
						</div>
					</div>
				</div>
			</div>
		<?php
		}
		if ($this->authority('management/users/:id')) {
		?>
			<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="editModalLabel"><?php echo \KN\Helpers\Base::lang('base.view'); ?></h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo \KN\Helpers\Base::lang('base.close'); ?>"></button>
						</div>
						<div class="modal-body">
							<form class="row g-2" data-kn-form id="userUpdate" method="post" action="">
								<div class="form-loader">
									<div class="spinner-border text-light" role="status">
										<span class="visually-hidden"><?php echo \KN\Helpers\Base::lang('base.loading'); ?></span>
									</div>
								</div>
								<div class="col-12 form-info">
								</div>
								<div class="col-12">
									<div class="form-floating">
										<input type="email" class="form-control" required name="email" id="theUserEmail" placeholder="<?php echo \KN\Helpers\Base::lang('base.email'); ?>">
										<label for="theUserEmail"><?php echo \KN\Helpers\Base::lang('base.email'); ?></label>
									</div>
								</div>
								<div class="col-12">
									<div class="form-floating">
										<input type="text" class="form-control" required name="u_name" id="theUserName" placeholder="<?php echo \KN\Helpers\Base::lang('base.username'); ?>">
										<label for="theUserName"><?php echo \KN\Helpers\Base::lang('base.username'); ?></label>
									</div>
								</div>
								<div class="col-12">
									<div class="form-floating">
										<input type="text" class="form-control" name="f_name" id="thefName" placeholder="<?php echo \KN\Helpers\Base::lang('base.name'); ?>">
										<label for="thefName"><?php echo \KN\Helpers\Base::lang('base.name'); ?></label>
									</div>
								</div>
								<div class="col-12">
									<div class="form-floating">
										<input type="text" class="form-control" name="l_name" id="thelName" placeholder="<?php echo \KN\Helpers\Base::lang('base.surname'); ?>">
										<label for="thelName"><?php echo \KN\Helpers\Base::lang('base.surname'); ?></label>
									</div>
								</div>
								<div class="col-12">
									<div class="form-floating">
										<select class="form-select" id="theRoles" required name="role_id" aria-label="<?php echo \KN\Helpers\Base::lang('base.role'); ?>">
										</select>
										<label for="theRoles"><?php echo \KN\Helpers\Base::lang('base.role'); ?></label>
									</div>
								</div>
								<div class="col-12">
									<div class="form-floating">
										<input type="password" class="form-control" name="password" id="theUserPassword" placeholder="<?php echo \KN\Helpers\Base::lang('base.password'); ?>">
										<label for="theUserPassword"><?php echo \KN\Helpers\Base::lang('base.password'); ?></label>
									</div>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo \KN\Helpers\Base::lang('base.close'); ?></button>
							<?php
							if ($this->authority('management/users/:id/update')) {
							?>
								<button type="submit" form="userUpdate" class="btn btn-primary"><?php echo \KN\Helpers\Base::lang('base.update'); ?></button>
							<?php
							}	?>
						</div>
					</div>
				</div>
			</div>
		<?php
		}	?>