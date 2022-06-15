        <nav class="navbar navbar-expand-xl navbar-dark bg-black fixed-top shadow">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?php echo $this->url('/'); ?>"><?php echo KN\Helpers\Base::config('app.name'); ?></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNav" aria-controls="navbarNav" 
                aria-expanded="false" aria-label="<?php echo KN\Helpers\Base::lang('base.toggle_navigation'); ?>">
                    <span class="menu-btn"><span></span></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <?php
                    /*
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link<?php echo $this->currentLink('/'); ?>" href="<?php echo $this->url('/'); ?>">
                                <?php echo KN\Helpers\Base::lang('base.home'); ?>
                            </a>
                        </li>
                    </ul>
                    */
                    echo KN\Helpers\HTML::generateMenu('top', [
                        'ul_class' => 'navbar-nav',
                        'ul_dropdown_class' => 'dropdown-menu',
                        'li_class' => 'nav-item',
                        'li_dropdown_class' => 'nav-item dropdown',
                        'dropdown_li_class' => 'nav-item dropdown',
                        'a_class' => 'nav-link',
                        'a_dropdown_class' => 'nav-link dropdown-toggle',
                        'a_dropdown_attributes' => 'role="button" data-bs-toggle="dropdown" aria-expanded="false"',
                        'dropdown_a_class' => 'dropdown-item',
                    ], 1, $this);
                    ?>
                    <ul class="navbar-nav ms-auto">
                        <?php
                        if (! KN\Helpers\Base::isAuth()) {
                        ?>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $this->currentLink('/auth/login'); ?>" href="<?php echo $this->url('/auth/login'); ?>">
                                    <?php echo KN\Helpers\Base::lang('base.login'); ?>
                                </a>
                            </li>
                            <div class="vr"></div>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $this->currentLink('/auth/register'); ?>" href="<?php echo $this->url('/auth/register'); ?>">
                                    <?php echo KN\Helpers\Base::lang('base.register'); ?>
                                </a>
                            </li>
                        <?php 
                        } else {
                            if ($this->authority('/management')) {
                                ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo $this->url('/management'); ?>">
                                        <?php echo KN\Helpers\Base::lang('base.management'); ?>
                                    </a>
                                </li>
                            <?php
                            }   ?>
                            <li class="nav-item">
                                <a class="nav-link<?php echo $this->currentLink('/auth', 'active', false); ?>" href="<?php echo $this->url('/auth'); ?>">
                                    <?php echo KN\Helpers\Base::lang('base.account'); ?>
                                </a>
                            </li>
                            <?php
                            if ($this->authority('auth/logout')) { ?>
                                <li class="vr"></li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo $this->url('/auth/logout'); ?>">
                                        <?php echo KN\Helpers\Base::lang('base.logout'); ?>
                                    </a>
                                </li>
                        <?php
                            }
                        }   ?>
                    </ul>
                </div>
            </div>
        </nav>