<div id="wrap">
    <nav class="navbar navbar-vertical navbar-expand-lg bg-dark navbar-dark fixed-left">
        <a class="navbar-brand" href="<?php echo $this->url(); ?>">
            <img src="<?php echo assets('admin/img/logo.png'); ?>" class="navbar-brand-img mx-auto" alt="Logo">
        </a>
        <div class="d-flex align-items-center">
            <div class="d-block d-lg-none">
                <div class="dropdown">
                    <a href="#" role="button" data-toggle="dropdown" class="avatar avatar-sm" aria-haspopup="true"
                       aria-expanded="false">
                        <span class="avatar-title rounded-circle">NS</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#" class="dropdown-item">Account</a>
                        <a href="#" class="dropdown-item">Log Out</a>
                    </div>
                </div>
            </div>
            <button class="navbar-toggler ml-2" type="button" data-toggle="collapse" data-target="#sidebarMenu"
                    aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                <span class="mdi mdi-menu"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse flex-column" id="sidebarMenu">
            <ul class="navbar-nav d-lg-block">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#usersSub"
                       aria-controls="usersSub">Users</a>
                    <div id="usersSub" class="collapse">
                        <ul class="nav nav-small flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="#">Users</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Add User</a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>

        <div class="d-none w-100 d-lg-block">
            <div class="dropup">
                <a href="#" class="avatar avatar-sm" role="button" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false">
                    <span class="avatar-title rounded-circle">NS</span>
                </a>
                <div class="dropdown-menu">
                    <a href="#" class="dropdown-item">Account</a>
                    <a href="#" class="dropdown-item">Log Out</a>
                </div>
            </div>
        </div>

    </nav>
    <div class="main-content">