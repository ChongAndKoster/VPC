<!doctype html>
<html lang="en">

<head>
    <meta charset="<?php bloginfo('charset'); ?>">

    <title><?php wp_title('|', true, 'right'); ?></title>
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">

    <?php wp_head(); ?>

    <?php if ($page_css = get_field('page_css')) : ?>
        <style type="text/css">
            <?php echo $page_css; ?>
        </style>
    <?php endif; ?>

    <?php if ($page_scripts = get_field('page_scripts')) : ?>
        <?php echo $page_scripts; ?>
    <?php endif; ?>
</head>

<body <?php body_class(); ?>>
    <div id="app-nav-mobile" style="display: none;">
        <button type="button" class="close-icon" aria-label="Close" aria-controls="app-nav-mobile">
            <span aria-hidden="true">&times;</span>
        </button>

        <a href="/" class="text-hide logo">Voter Participation Center</a>

        <nav>
            <?php wp_nav_menu(array(
                'theme_location' => 'mobile-nav',
                'container'      => false,
                'menu_class'     => 'nav',
                'walker'         => new app_MobileNavWalker(),
            )); ?>
        </nav>
    </div> <!-- .app-nav-mobile -->

    <div id="app-panel">
        <header class="app-header">
            <div class="container">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="text-hide logo">Voter Participation Center</a>

                <button class="nav-icon"><svg width="25px" height="20px" viewBox="0 0 25 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g transform="translate(-327.000000, -28.000000)">
                                <g transform="translate(328.000000, 29.000000)" stroke-linecap="round" stroke-width="2">
                                    <line x1="0" y1="9.25" x2="23" y2="9.25" id="Stroke-2" stroke="#9200FF"></line>
                                    <line x1="0" y1="0.5" x2="23" y2="0.5" id="Stroke-4" stroke="#FD2F6A"></line>
                                    <line x1="0" y1="17.5" x2="23" y2="17.5" id="Stroke-6" stroke="#2F84FD"></line>
                                </g>
                            </g>
                        </g>
                    </svg><span class="sr-only">Menu</span></button>

                <nav>
                    <?php wp_nav_menu(array(
                        'theme_location' => 'main-nav',
                        'container'      => false,
                        'menu_class'     => 'nav',
                    )); ?>
                </nav>
            </div> <!-- .container -->
        </header> <!-- .app-header -->

        <main class="app-main">