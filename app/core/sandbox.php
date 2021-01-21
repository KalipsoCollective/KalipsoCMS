<?php

$preActive = false;

$output = '
    <section class="hero">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    Welcome to Kalipso Sandbox!
                </h1>
                <h2 class="subtitle">
                    From here, you can use basic setup or developer options.
                </h2>
            </div>
        </div>
    </section>';

if (isset($_GET['action']) !== false) {

    switch ($_GET['action']) {
        case 'db_init':
            $dbSchema = require path('app/core/defs/db_schema.php');
            $output = 'Preparing...<br>';
            $init = $this->db->dbInit($dbSchema);
            if ($init) {
                $output .= 'Database has been prepared successfully.';
            } else {
                $output .= 'There was a problem while preparing the database. -> ' . $init;
            }
            // $preActive = true;
            break;
        case 'db_seed':
            $dbSchema = require path('app/core/defs/db_schema.php');
            $output = 'Seeding...<br>';
            $init = $this->db->dbSeed($dbSchema);
            if ($init) {
                $output .= 'Database has been seeded successfully.';
            } else {
                $output .= 'There was a problem while seeding the database. -> ' . $init;
            }
            break;
        case 'db_commit':
            $output = '
            <div class="columns is-mobile is-centered">
                <div class="column is-half">
                    <p class="my-6">Maybe one day...</p>
                </div>
            </div>';
            break;
        case 'dump':
            $output = '
            <div class="columns is-mobile is-centered">
                <div class="column is-half">
                    <pre class="my-6"></pre>
                </div>
            </div>';
            break;
    }
}

?>
<!doctype html>
<html lang="tr">
    <head>
        <title>KalipsoCMS Sandbox</title>
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Anonymous+Pro:wght@400;700&family=Inter:wght@200;300;400&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.1/css/bulma.min.css">
        <style>
            body * {
                font-family: 'Inter', sans-serif !important;
                color: #fff;
            }
            html {
                background: #222222;
            }
            pre {
                font-family: 'Anonymous Pro', monospace !important;
                background: #292929;
                border-radius: 5px;
                box-shadow: 0 6px 15px rgb(0 0 0 / 20%);
                color: #828282;
                font-size: 15px;
            }

            nav.navbar {
                background: #181818;
                margin-bottom: 2rem;
            }

            .navbar-link.is-active,
            .navbar-link:focus,
            .navbar-link:focus-within,
            .navbar-link:hover,
            .navbar-link:hover,
            a.navbar-item.is-active,
            a.navbar-item:focus,
            a.navbar-item:focus-within,
            a.navbar-item:hover {
                background-color: initial;
                color: #00d1b2;
            }

            .navbar-item.has-dropdown.is-active .navbar-link,
            .navbar-item.has-dropdown:focus .navbar-link,
            .navbar-item.has-dropdown:hover .navbar-link {
                background-color: initial;
                color: #00d1b2
            }

            .navbar-link:not(.is-arrowless)::after {
                border-color: #2f2f2f;
                margin-top: -.375em;
                right: 1.125em;
            }

            .navbar-dropdown {
                background-color: #2a2a2a;
                border-top: 2px solid #00d1b2;
            }

            .navbar-item, .navbar-link {
                color: #878787;
            }

            .navbar-dropdown a.navbar-item:focus,
            .navbar-dropdown a.navbar-item:hover {
                background-color: #181818;
                color: #ffffff;
            }

            .title {
                color: #959595;
            }

            .subtitle {
                color: #797979;
            }
        </style>
    </head>
    <body>
        <nav class="navbar" role="navigation" aria-label="main navigation">
            <div class="navbar-brand">
                <a class="navbar-item" href="<?php echo base('sandbox') ?>">
                    <img src="<?php echo assets('admin/img/logo.svg'); ?>">
                </a>

                <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>

            <div id="navbarBasicExample" class="navbar-menu">
                <div class="navbar-start">
                    <a class="navbar-item" href="<?php echo base('sandbox') ?>">
                        Home
                    </a>
                    <div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link">
                            DB Setup
                        </a>
                        <div class="navbar-dropdown">
                            <a class="navbar-item" href="<?php echo base('sandbox?action=db_init') ?>">
                                Prepare DB
                            </a>
                            <a class="navbar-item" href="<?php echo base('sandbox?action=db_seed') ?>">
                                Seed
                            </a>
                            <a class="navbar-item" href="<?php echo base('sandbox?action=db_commit') ?>">
                                Commit
                            </a>
                        </div>
                    </div>
                    <a class="navbar-item" href="<?php echo base('sandbox?action=dump') ?>">
                        Dump
                    </a>
                </div>

                <div class="navbar-end">
                    <div class="navbar-item">
                        <div class="buttons">
                            <a class="button is-primary" href="<?php echo base(); ?>">
                                <strong>Back to Home</strong>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <div class="container is-fluid">
            <?php
            if ($preActive) {
                varFuck($output);
            } else {
                echo $output;
            }
            ?>
        </div>
    </body>
</html>