        <nav class="navbar-side navbar navbar-expand navbar-dark bg-black">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?php echo $this->url('/management'); ?>">
                    <?php echo KN\Helpers\Base::config('app.name'); ?>
                    <small class="h6">_admin</small>    
                </a>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <?php
                        if ($this->authority('/management')) 
                        {   ?>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $this->currentLink('/management'); ?>" href="<?php echo $this->url('/management'); ?>">
                                    <i class="ti ti-layout-dashboard nav-icon"></i> <?php echo KN\Helpers\Base::lang('base.dashboard'); ?>
                                </a>
                            </li>
                        <?php
                        }
                        if ($this->authority('/management/users') OR
                            $this->authority('/management/roles') OR
                            $this->authority('/management/sessions')) {
                            echo '<li class="nav-item nav-group">' . KN\Helpers\Base::lang('base.users') . '</li>';
                        }
                        if ($this->authority('/management/users')) {
                        ?>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $this->currentLink('/management/users'); ?>" href="<?php echo $this->url('/management/users'); ?>">
                                    <i class="ti ti-users nav-icon"></i> <?php echo KN\Helpers\Base::lang('base.users'); ?>
                                </a>
                            </li>
                        <?php
                        } 
                        if ($this->authority('/management/roles')) {
                        ?>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $this->currentLink('/management/roles'); ?>" href="<?php echo $this->url('/management/roles'); ?>">
                                    <i class="ti ti-chart-dots-3 nav-icon"></i> <?php echo KN\Helpers\Base::lang('base.user_roles'); ?>
                                </a>
                            </li>
                        <?php
                        } 
                        if ($this->authority('/management/sessions')) {
                        ?>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $this->currentLink('/management/sessions'); ?>" href="<?php echo $this->url('/management/sessions'); ?>">
                                    <i class="ti ti-affiliate nav-icon"></i> <?php echo KN\Helpers\Base::lang('base.sessions'); ?>
                                </a>
                            </li>
                        <?php
                        }

                        if ($this->authority('/management/:module')) {

                            echo '<li class="nav-item nav-group">' . KN\Helpers\Base::lang('base.modules') . '</li>';
                            foreach ($modules as $name => $details) {
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link<?php echo $this->currentLink('/management/' . $name); ?>" href="<?php echo $this->url('/management/' . $name); ?>">
                                        <i class="<?php echo $details['icon']; ?> nav-icon"></i> <?php echo KN\Helpers\Base::lang($details['name']); ?>
                                    </a>
                                </li>
                                <?php
                            }
                        }

                        if ($this->authority('/management/:module')) {

                            echo '<li class="nav-item nav-group">' . KN\Helpers\Base::lang('base.modules') . '</li>';
                            foreach ($modules as $name => $details) {
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link<?php echo $this->currentLink('/management/' . $name); ?>" href="<?php echo $this->url('/management/' . $name); ?>">
                                        <i class="<?php echo $details['icon']; ?> nav-icon"></i> <?php echo KN\Helpers\Base::lang($details['name']); ?>
                                    </a>
                                </li>
                                <?php
                            }
                        }

                        if ($this->authority('/management/:module')) {

                            echo '<li class="nav-item nav-group">' . KN\Helpers\Base::lang('base.modules') . '</li>';
                            foreach ($modules as $name => $details) {
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link<?php echo $this->currentLink('/management/' . $name); ?>" href="<?php echo $this->url('/management/' . $name); ?>">
                                        <i class="<?php echo $details['icon']; ?> nav-icon"></i> <?php echo KN\Helpers\Base::lang($details['name']); ?>
                                    </a>
                                </li>
                                <?php
                            }
                        }

                        if ($this->authority('/management/forms/:form')) {

                            echo '<li class="nav-item nav-group">' . KN\Helpers\Base::lang('base.forms') . '</li>';
                            foreach ($forms as $name => $details) {
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link<?php echo $this->currentLink('/management/forms/' . $name); ?>" href="<?php echo $this->url('/management/forms/' . $name); ?>">
                                        <i class="<?php echo $details['icon']; ?> nav-icon"></i> <?php echo KN\Helpers\Base::lang($details['name']); ?>
                                    </a>
                                </li>
                                <?php
                            }
                        } 

                        if ($this->authority('/management/media')) {
                            echo '<li class="nav-item nav-group">' . KN\Helpers\Base::lang('base.media') . '</li>';
                        }
                        if ($this->authority('/management/media')) {
                        ?>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $this->currentLink('/management/media'); ?>" href="<?php echo $this->url('/management/media'); ?>">
                                    <i class="ti ti-cloud nav-icon"></i> <?php echo KN\Helpers\Base::lang('base.media'); ?>
                                </a>
                            </li>
                        <?php
                        }

                        if ($this->authority('/management/logs') OR
                            $this->authority('/management/settings')) {
                            echo '<li class="nav-item nav-group">' . KN\Helpers\Base::lang('base.system') . '</li>';
                        }

                        if ($this->authority('/management/logs')) {
                        ?>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $this->currentLink('/management/logs'); ?>" href="<?php echo $this->url('/management/logs'); ?>">
                                    <i class="ti ti-virus-search nav-icon"></i> <?php echo KN\Helpers\Base::lang('base.logs'); ?>
                                </a>
                            </li>
                        <?php
                        } 
                        if ($this->authority('/management/settings')) {
                        ?>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $this->currentLink('/management/settings'); ?>" href="<?php echo $this->url('/management/settings'); ?>">
                                    <i class="ti ti-settings nav-icon"></i> <?php echo KN\Helpers\Base::lang('base.settings'); ?>
                                </a>
                            </li>
                        <?php
                        }   ?>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="wrap-content">
            <nav class="navbar admin-nav navbar-dark navbar-expand bg-primary shadow">
                <div class="container-fluid">
                    <div class="collapse navbar-collapse">
                        <button class="navbar-side-toggler" onclick="navOpen()" type="button" aria-label="<?php echo KN\Helpers\Base::lang('base.toggle_navigation'); ?>">
                            <span class="menu-btn"><span></span></span>
                        </button>
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a title="<?php echo KN\Helpers\Base::lang('base.home'); ?>" data-bs-toggle="tooltip" data-bs-placement="bottom" class="nav-link" href="<?php echo $this->url('/'); ?>">
                                    <i class="ti ti-arrow-back-up"></i>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a title="<?php echo KN\Helpers\Base::lang('base.account'); ?>" data-bs-toggle="tooltip" data-bs-placement="bottom" class="nav-link<?php echo $this->currentLink('/auth', 'active', false); ?>" href="<?php echo $this->url('/auth'); ?>">
                                    <i class="ti ti-user"></i>
                                </a>
                            </li>
                            <div class="vr"></div>
                            <li class="nav-item danger">
                                <a title="<?php echo KN\Helpers\Base::lang('base.logout'); ?>" data-bs-toggle="tooltip" data-bs-placement="left" class="nav-link" href="<?php echo $this->url('/auth/logout'); ?>">
                                    <i class="ti ti-power"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        