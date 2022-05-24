        <nav class="navbar-vertical navbar">
            <div class="nav-scroller">
                <a class="navbar-brand" href="<?php echo $this->url('/management'); ?>">
                    <?php echo KN\Helpers\Base::config('app.name'); ?>
                </a>
                <!-- Navbar nav -->
                <ul class="navbar-nav flex-column" id="sideNavbar">
                    <?php
                    if ($this->authority('/management')) {
                        ?>
                        <li class="nav-item">
                            <a class="nav-link has-arrow <?php echo $this->currentLink('/management'); ?>" href="<?php echo $this->url('/management'); ?>">
                                <i class="ti ti-layout-dashboard nav-icon icon-xs me-2"></i> <?php echo KN\Helpers\Base::lang('base.management'); ?>
                            </a>
                        </li>
                    <?php
                    } 

                    if ($this->authority('/management/users')) { ?>
                        <li class="nav-item">
                            <div class="navbar-heading"><?php echo KN\Helpers\Base::lang('base.users'); ?></div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link has-arrow collapsed" href="#!" data-bs-toggle="collapse" data-bs-target="#navUser" aria-expanded="false" aria-controls="navUser">
                                <i class="ti ti-layout-dashboard nav-icon icon-xs me-2"></i> <?php echo KN\Helpers\Base::lang('base.users'); ?>
                            </a>
                            <div id="navUser" class="collapse" data-bs-parent="#sideNavbar">
                                <ul class="nav flex-column">
                                    <?php
                                    if ($this->authority('/management/users')) 
                                    {   ?>
                                        <li class="nav-item">
                                            <a class="nav-link has-arrow <?php echo $this->currentLink('/management/users'); ?>" href="<?php echo $this->url('/management/users'); ?>">
                                                <i class="ti ti-layout-dashboard nav-icon icon-xs me-2"></i> <?php echo KN\Helpers\Base::lang('base.users'); ?>
                                            </a>
                                        </li>
                                    <?php
                                    }   ?>
                                    <li class="nav-item">
                                        <a class="nav-link " href="./pages/profile.html">
                                        Profile
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link has-arrow   "  href="./pages/settings.html" >
                                        Settings
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " href="./pages/billing.html">
                                        Billing
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " href="./pages/pricing.html">
                                        Pricing
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " href="./pages/404-error.html">
                                        404 Error
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    <?php
                    }   ?>
                   
                    <!-- Nav item -->
                    <li class="nav-item">
                        <a class="nav-link has-arrow  collapsed " href="#!" data-bs-toggle="collapse" data-bs-target="#navAuthentication" aria-expanded="false" aria-controls="navAuthentication">
                        <i data-feather="lock" class="nav-icon icon-xs me-2">
                        </i> Authentication
                        </a>
                        <div id="navAuthentication" class="collapse " data-bs-parent="#sideNavbar">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link " href="./pages/sign-in.html"> Sign In</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link  " href="./pages/sign-up.html"> Sign Up</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " href="./pages/forget-password.html">
                                    Forget Password
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " href="./pages/layout.html">
                        <i
                            data-feather="sidebar"
                            class="nav-icon icon-xs me-2"
                            >
                        </i
                            >
                        Layouts
                        </a>
                    </li>
                    <!-- Nav item -->
                    <li class="nav-item">
                        <div class="navbar-heading">UI Components</div>
                    </li>
                    <!-- Nav item -->
                    <li class="nav-item">
                        <a class="nav-link has-arrow " href="./docs/accordions.html" >
                        <i data-feather="package" class="nav-icon icon-xs me-2" >
                        </i>  Components
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link has-arrow  collapsed " href="#!" data-bs-toggle="collapse" data-bs-target="#navMenuLevel" aria-expanded="false" aria-controls="navMenuLevel">
                        <i
                            data-feather="corner-left-down"
                            class="nav-icon icon-xs me-2"
                            >
                        </i
                            > Menu Level
                        </a>
                        <div id="navMenuLevel" class="collapse " data-bs-parent="#sideNavbar">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link has-arrow " href="#!" data-bs-toggle="collapse" data-bs-target="#navMenuLevelSecond" aria-expanded="false" aria-controls="navMenuLevelSecond">
                                    Two Level
                                    </a>
                                    <div id="navMenuLevelSecond" class="collapse" data-bs-parent="#navMenuLevel">
                                        <ul class="nav flex-column">
                                            <li class="nav-item">
                                                <a class="nav-link " href="#!">  NavItem 1</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link " href="#!">  NavItem 2</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link has-arrow  collapsed  " href="#!" data-bs-toggle="collapse" data-bs-target="#navMenuLevelThree" aria-expanded="false" aria-controls="navMenuLevelThree">
                                    Three Level
                                    </a>
                                    <div id="navMenuLevelThree" class="collapse " data-bs-parent="#navMenuLevel">
                                        <ul class="nav flex-column">
                                            <li class="nav-item">
                                                <a class="nav-link  collapsed " href="#!" data-bs-toggle="collapse" data-bs-target="#navMenuLevelThreeOne" aria-expanded="false" aria-controls="navMenuLevelThreeOne">
                                                NavItem 1
                                                </a>
                                                <div id="navMenuLevelThreeOne" class="collapse collapse " data-bs-parent="#navMenuLevelThree">
                                                    <ul class="nav flex-column">
                                                        <li class="nav-item">
                                                            <a class="nav-link " href="#!">
                                                            NavChild Item 1
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link " href="#!">  Nav Item 2</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <!-- Nav item -->
                    <li class="nav-item">
                        <div class="navbar-heading">Documentation</div>
                    </li>
                    <!-- Nav item -->
                    <li class="nav-item">
                        <a class="nav-link has-arrow " href="./docs/index.html" >
                        <i data-feather="clipboard" class="nav-icon icon-xs me-2" >
                        </i>  Docs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link has-arrow " href="./docs/changelog.html" >
                        <i data-feather="git-pull-request" class="nav-icon icon-xs me-2" >
                        </i>  Changelog
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <div id="page-content">
                <div class="header @@classList">
                    <!-- navbar -->
                    <nav class="navbar-classic navbar navbar-expand-lg">
                        <a id="nav-toggle" href="#"><i
                            data-feather="menu"
                            class="nav-icon me-2 icon-xs"></i></a>
                        <div class="ms-lg-3 d-none d-md-none d-lg-block">
                            <!-- Form -->
                            <form class="d-flex align-items-center">
                                <input type="search" class="form-control" placeholder="Search" />
                            </form>
                        </div>
                        <!--Navbar nav -->
                        <ul class="navbar-nav navbar-right-wrap ms-auto d-flex nav-top-wrap">
                            <li class="dropdown stopevent">
                                <a class="btn btn-light btn-icon rounded-circle indicator
                                    indicator-primary text-muted" href="#" role="button"
                                    id="dropdownNotification" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                <i class="icon-xs" data-feather="bell"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end"
                                    aria-labelledby="dropdownNotification">
                                    <div>
                                        <div class="border-bottom px-3 pt-2 pb-3 d-flex
                                            justify-content-between align-items-center">
                                            <p class="mb-0 text-dark fw-medium fs-4">Notifications</p>
                                            <a href="#" class="text-muted">
                                            <span>
                                            <i class="me-1 icon-xxs" data-feather="settings"></i>
                                            </span>
                                            </a>
                                        </div>
                                        <!-- List group -->
                                        <ul class="list-group list-group-flush notification-list-scroll">
                                            <!-- List group item -->
                                            <li class="list-group-item bg-light">
                                                <a href="#" class="text-muted">
                                                    <h5 class=" mb-1">Rishi Chopra</h5>
                                                    <p class="mb-0">
                                                        Mauris blandit erat id nunc blandit, ac eleifend dolor pretium.
                                                    </p>
                                                </a>
                                            </li>
                                            <!-- List group item -->
                                            <li class="list-group-item">
                                                <a href="#" class="text-muted">
                                                    <h5 class=" mb-1">Neha Kannned</h5>
                                                    <p class="mb-0">
                                                        Proin at elit vel est condimentum elementum id in ante. Maecenas et sapien metus.
                                                    </p>
                                                </a>
                                            </li>
                                            <!-- List group item -->
                                            <li class="list-group-item">
                                                <a href="#" class="text-muted">
                                                    <h5 class=" mb-1">Nirmala Chauhan</h5>
                                                    <p class="mb-0">
                                                        Morbi maximus urna lobortis elit sollicitudin sollicitudieget elit vel pretium.
                                                    </p>
                                                </a>
                                            </li>
                                            <!-- List group item -->
                                            <li class="list-group-item">
                                                <a href="#" class="text-muted">
                                                    <h5 class=" mb-1">Sina Ray</h5>
                                                    <p class="mb-0">
                                                        Sed aliquam augue sit amet mauris volutpat hendrerit sed nunc eu diam.
                                                    </p>
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="border-top px-3 py-2 text-center">
                                            <a href="#" class="text-inherit fw-semi-bold">
                                            View all Notifications
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <!-- List -->
                            <li class="dropdown ms-2">
                                <a class="rounded-circle" href="#" role="button" id="dropdownUser"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="avatar avatar-md avatar-indicators avatar-online">
                                        <img alt="avatar" src="./assets/images/avatar/avatar-1.jpg"
                                            class="rounded-circle" />
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end"
                                    aria-labelledby="dropdownUser">
                                    <div class="px-4 pb-0 pt-2">
                                        <div class="lh-1 ">
                                            <h5 class="mb-1"> John E. Grainger</h5>
                                            <a href="#" class="text-inherit fs-6">View my profile</a>
                                        </div>
                                        <div class=" dropdown-divider mt-3 mb-2"></div>
                                    </div>
                                    <ul class="list-unstyled">
                                        <li>
                                            <a class="dropdown-item" href="#">
                                            <i class="me-2 icon-xxs dropdown-item-icon" data-feather="user"></i>Edit
                                            Profile
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                                href="#">
                                            <i class="me-2 icon-xxs dropdown-item-icon"
                                                data-feather="activity"></i>Activity Log
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-primary" href="#">
                                            <i class="me-2 icon-xxs text-primary dropdown-item-icon"
                                                data-feather="star"></i>Go Pro
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#">
                                            <i class="me-2 icon-xxs dropdown-item-icon"
                                                data-feather="settings"></i>Account Settings
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="./index.html">
                                            <i class="me-2 icon-xxs dropdown-item-icon"
                                                data-feather="power"></i>Sign Out
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </nav>
                </div>
                <!-- Container fluid -->
                <div class="bg-primary pt-10 pb-21"></div>
        <?php /*
        <nav class="navbar navbar-expand-xl navbar-dark bg-black fixed-top shadow">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?php echo $this->url('/'); ?>">
                    <?php echo KN\Helpers\Base::config('app.name'); ?>
                    <small class="h6">_admin</small>    
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNav" aria-controls="navbarNav" 
                aria-expanded="false" aria-label="<?php echo KN\Helpers\Base::lang('base.toggle_navigation'); ?>">
                    <span class="menu-btn"><span></span></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <?php
                        if ($this->authority('/management')) 
                        {   ?>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $this->currentLink('/management'); ?>" href="<?php echo $this->url('/management'); ?>">
                                    <?php echo KN\Helpers\Base::lang('base.dashboard'); ?>
                                </a>
                            </li>
                        <?php
                        } 
                        if ($this->authority('/management/users')) {
                        ?>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $this->currentLink('/management/users'); ?>" href="<?php echo $this->url('/management/users'); ?>">
                                    <?php echo KN\Helpers\Base::lang('base.users'); ?>
                                </a>
                            </li>
                        <?php
                        } 
                        if ($this->authority('/management/roles')) {
                        ?>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $this->currentLink('/management/roles'); ?>" href="<?php echo $this->url('/management/roles'); ?>">
                                    <?php echo KN\Helpers\Base::lang('base.user_roles'); ?>
                                </a>
                            </li>
                        <?php
                        } 
                        if ($this->authority('/management/sessions')) {
                        ?>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $this->currentLink('/management/sessions'); ?>" href="<?php echo $this->url('/management/sessions'); ?>">
                                    <?php echo KN\Helpers\Base::lang('base.sessions'); ?>
                                </a>
                            </li>
                        <?php
                        } 
                        if ($this->authority('/management/logs')) {
                        ?>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $this->currentLink('/management/logs'); ?>" href="<?php echo $this->url('/management/logs'); ?>">
                                    <?php echo KN\Helpers\Base::lang('base.logs'); ?>
                                </a>
                            </li>
                        <?php
                        } 
                        if ($this->authority('/management/settings')) {
                        ?>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $this->currentLink('/management/settings'); ?>" href="<?php echo $this->url('/management/settings'); ?>">
                                    <?php echo KN\Helpers\Base::lang('base.settings'); ?>
                                </a>
                            </li>
                        <?php
                        }   ?>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $this->url('/'); ?>">
                                <?php echo KN\Helpers\Base::lang('base.home'); ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link<?php echo $this->currentLink('/auth', 'active', false); ?>" href="<?php echo $this->url('/auth'); ?>">
                                <?php echo KN\Helpers\Base::lang('base.account'); ?>
                            </a>
                        </li>
                        <div class="vr"></div>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $this->url('/auth/logout'); ?>">
                                <i class="mdi mdi-power"></i> <?php echo KN\Helpers\Base::lang('base.logout'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        */ ?>