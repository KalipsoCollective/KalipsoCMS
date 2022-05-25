	
		<div class="container-fluid">
			<div class="row">
                <header class="col-12 dash-header">
                    <h1><?php echo \KN\Helpers\Base::lang('base.dashboard'); ?></h1>
                    <p><?php echo $description; ?></p>
                </header>
                <div class="col-12 dash-content">
                    <div class="row">
        				<?php
        				if ($this->authority('/management/users')) {
        				?>
        					<div class="col-12 col-md-3">
        						<div class="card mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h2 class="h4"><?php echo \KN\Helpers\Base::lang('base.users'); ?></h2>
                                            </div>
                                            <div class="dash-icon">
                                                <i class="ti ti-user"></i>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h3 class="fw-bold h1 mb-0"><?php echo $count['users']; ?></h3>
                                            <a href="<?php echo $this->url('/management/users'); ?>" class="btn btn-dark btn-sm ms-auto">
                                                <?php echo \KN\Helpers\Base::lang('base.view'); ?> <i class="ti ti-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
        						</div>
        					</div>
        				<?php 
        				}
        				if ($this->authority('/management/roles')) {
        				?>
                            <div class="col-12 col-md-3">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h2 class="h4"><?php echo \KN\Helpers\Base::lang('base.user_roles'); ?></h2>
                                            </div>
                                            <div class="dash-icon">
                                                <i class="ti ti-users"></i>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h3 class="fw-bold h1 mb-0"><?php echo $count['user_roles']; ?></h3>
                                            <a href="<?php echo $this->url('/management/roles'); ?>" class="btn btn-dark btn-sm ms-auto">
                                                <?php echo \KN\Helpers\Base::lang('base.view'); ?> <i class="ti ti-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
        				<?php 
        				}

        				if ($this->authority('/management/sessions')) {
        				?>
                            <div class="col-12 col-md-3">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h2 class="h4"><?php echo \KN\Helpers\Base::lang('base.sessions'); ?></h2>
                                            </div>
                                            <div class="dash-icon">
                                                <i class="ti ti-affiliate"></i>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h3 class="fw-bold h1 mb-0"><?php echo $count['sessions']; ?></h3>
                                            <a href="<?php echo $this->url('/management/sessions'); ?>" class="btn btn-dark btn-sm ms-auto">
                                                <?php echo \KN\Helpers\Base::lang('base.view'); ?> <i class="ti ti-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
        				<?php 
        				}

        				if ($this->authority('/management/logs')) {
        				?>  
                            <div class="col-12 col-md-3">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h2 class="h4"><?php echo \KN\Helpers\Base::lang('base.logs'); ?></h2>
                                            </div>
                                            <div class="dash-icon">
                                                <i class="ti ti-virus-search"></i>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <h3 class="fw-bold h1 mb-0"><?php echo $count['logs']; ?></h3>
                                            <a href="<?php echo $this->url('/management/logs'); ?>" class="btn btn-dark btn-sm ms-auto">
                                                <?php echo \KN\Helpers\Base::lang('base.view'); ?> <i class="ti ti-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
        				<?php 
        				}   ?>
                    </div>
                </div>
			</div>
		</div>