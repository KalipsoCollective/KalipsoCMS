<div id="wrap">
    <nav class="navbar navbar-vertical fixed-left navbar-expand-md">
        <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#sidebarCollapse"
                aria-controls="sidebarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="<?php echo $this->url(); ?>">
            <img src="<?php echo assets('admin/img/logo.png'); ?>" class="navbar-brand-img mx-auto" alt="Logo">
        </a>
        <a class="nav-link mb-4 account-button" href="#accountMenu" data-toggle="collapse" role="button" aria-expanded="true"
           aria-controls="accountMenu">
            <?php echo trim($_SESSION['user']->f_name . ' ' . $_SESSION['user']->l_name); ?>
        </a>
        <div class="collapse" id="accountMenu">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="mdi mdi-account"></i> Account
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="mdi mdi-logout-variant text-danger"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
        <div class="navbar-collapse collapse" id="sidebarCollapse">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="javascript:;">
                        <i class="mdi mdi-dots-hexagon"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#usersMenu" data-toggle="collapse" role="button" aria-expanded="true"
                       aria-controls="usersMenu">
                        <i class="mdi mdi-account-group"></i> Users
                    </a>
                    <div class="collapse" id="usersMenu">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    Users
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    Add User
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    Groups
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    Add Group
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="mdi mdi-cog"></i> Settings
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="main-content">