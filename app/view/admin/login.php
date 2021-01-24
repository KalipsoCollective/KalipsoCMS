        <div class="container login-screen" id="wrap">
            <div class="row justify-content-center">
                <div class="col-4 mt-5">
                    <a href="<?php echo $this->url(); ?>">
                        <img class="mb-2 d-flex mx-auto"
                             src="<?php echo assets('admin/img/logo.svg'); ?>"
                             alt="logo"
                        / >
                    </a>
                    <div class="card mt-5 shadow-lg bg-white
                    rounded border-bottom-0 border-left-0 border-right-0 border-primary border-top">
                        <div class="card-body">
                            <form class="section-loader" action="user/login" method="post" id="loginForm"
                                  onsubmit="return false">
                                <h1 class="h3 mb-3 text-center">
                                    <?php echo lang('welcome'); ?>
                                </h1>
                                <div class="form-info"></div>
                                <div class="form-group">
                                    <label for="username">
                                        <?php echo lang('email_or_username'); ?>
                                    </label>
                                    <input type="text" name="username" class="form-control" id="username"
                                           autofocus required>
                                </div>
                                <div class="form-group">
                                    <label for="password"><?php echo lang('password'); ?></label>
                                    <input type="password" name="password" class="form-control" id="password" required>
                                </div>
                                <button class="btn btn-lg btn-primary btn-block" type="submit">
                                    <?php echo lang('login'); ?>
                                </button>
                                <p class="mt-5 mb-3 text-muted text-center small">
                                    <?php
                                    foreach (config('app.available_langs') as $lang) {
                                        if ($lang == $this->currentLang) continue;

                                        echo '
                                        <a data-toggle="tooltip" title="'.lang('other_languages_' . $lang).'" 
                                        href="'.$this->generateLinkForOtherLanguages($lang, $this->route).'" 
                                        class="badge badge-dark">
                                            '.mb_strtoupper($lang).'
                                        </a>';
                                    }   ?>
                                </p>
                                <hr class="my-2" />
                                <p class="text-muted text-center small">
                                    <?php echo config('app.name') . ' &copy; ' . date('Y'); ?>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>