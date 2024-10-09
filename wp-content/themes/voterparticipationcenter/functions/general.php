<?php

/*
|--------------------------------------------------------------------------
| Actions and Filters
|--------------------------------------------------------------------------
*/
add_action('after_setup_theme', 'custom_theme_setup');
add_action('admin_menu', 'remove_menus');
add_action('init', 'app_register_post_types');
add_action('wp_enqueue_scripts', 'app_scripts_styles');
add_action('wp_head', 'app_add_head_items');
add_action('login_enqueue_scripts', 'app_login_logo');
add_action('enqueue_block_editor_assets', 'app_block_editor_styles');

add_filter('login_headerurl', 'app_login_logo_url');
add_filter('login_headertext', 'app_login_logo_url_title');
add_filter('mce_buttons', 'app_tinymce_buttons');
add_filter('tiny_mce_before_init', 'app_tinymce_before_init_insert_formats');
add_filter('wp_title', 'app_wp_title', 10, 2);
add_filter('block_categories', 'app_block_categories');

/*
|--------------------------------------------------------------------------
| Functions, etc.
|--------------------------------------------------------------------------
*/

/**
 * Content Width
 *
 * Set the default width for large-sized images.
 * This tells WordPress the largest size image that the website supports.
 */
if (!isset($content_width)) {
    $content_width = 1600;
}

/**
 * Theme Setup
 *
 * Sets up the theme.
 *
 * @return void
 */
function custom_theme_setup()
{

    // Set the supported post formats.
    add_theme_support('post-formats', [
        'aside', 'link', 'image', 'quote', 'audio', 'video'
    ]);

    // Set the custom image size for featured images.
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(752, 440, true);

    add_image_size('state-logo', 388, 222, false);
    add_image_size('banner', 1600, 9999, false);
    add_image_size('card-icon', 400, 250, false);
    add_image_size('headshot', 400, 400, true);
    add_image_size('partner-logo', 500, 150, false);

    // Register Navigation menu(s)
    register_nav_menus([
        'main-nav'   => 'Main Navigation',
        'mobile-nav' => 'Mobile Navigation',
        'footer-nav' => 'Footer Navigation',
    ]);

    // Register sidebar
    register_sidebar([
        'id' => 1,
        'name' => 'Sidebar',
    ]);

    // Site Options page
    if (function_exists('acf_add_options_sub_page')) {
        acf_add_options_sub_page('Site Options');
    }

    // Editor Stylesheet
    add_theme_support('editor-styles');
    add_editor_style('editor-style.css');

    // Move the "VPC Blocks" block category to the first position
    function app_block_categories($categories)
    {
        usort($categories, function ($a, $b) {
            if ($a['slug'] == 'vpcblocks') return -1;
            if ($b['slug'] == 'vpcblocks') return 1;
            return 0;
        });
        return $categories;
    }

    // Gutenburg block editor colors
    add_theme_support('editor-color-palette', [
        [
            'name'  => __('Dark Blue'),
            'slug'  => 'dark-blue',
            'color' => '#002F88',
        ],
        [
            'name'  => __('Dark Green'),
            'slug'  => 'dark-green',
            'color' => '#00E8BC',
        ],
        [
            'name'  => __('Grey'),
            'slug'  => 'gray-500',
            'color' => '#A2B1B0',
        ],
        [
            'name'  => __('Purple'),
            'slug'  => 'purple',
            'color' => '#9013FE',
        ],
        [
            'name'  => __('Razz'),
            'slug'  => 'razz',
            'color' => '#FD2F6A',
        ],
        [
            'name'  => __('Royal'),
            'slug'  => 'royal',
            'color' => '#2F84FD',
        ],
        [
            'name'  => __('Darker Blue'),
            'slug'  => 'darker-blue',
            'color' => '#001E57',
        ],
    ]);

    // Clean up the <head>
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'rel_canonical');
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

    // Add custom logo to login page
    function app_login_logo()
    { ?>
        <style type="text/css">
            body.login div#login h1 {
                padding: 0 12px;
            }

            body.login div#login h1 a {
                background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/img/logo-vpc.png);
                max-width: 320px;
                width: 100%;
                height: 33px;
                background-size: contain;
                padding-bottom: 10px;
            }
        </style> <?php
                }

                function app_login_logo_url()
                {
                    return home_url();
                }

                function app_login_logo_url_title()
                {
                    return 'Voter Participation Center';
                }
            }

            /** 
             * Remove unneeded admin menu items
             */
            function remove_menus()
            {
                remove_menu_page('edit-comments.php'); // Comments
            }

            /**
             * Register custom post types
             * 
             * @return void
             */
            function app_register_post_types()
            {

                // States
                register_post_type(
                    'states',
                    [
                        'labels' => [
                            'name' => __('States'),
                            'singular_name' => __('State')
                        ],
                        'public' => true,
                        'has_archive' => false,
                        'pages' => false,
                        'menu_icon' => 'dashicons-location-alt',
                        'supports' => ['title'],
                    ]
                );

                // FAQs
                register_post_type(
                    'faqs',
                    [
                        'labels' => [
                            'name' => __('FAQs'),
                            'singular_name' => __('FAQ')
                        ],
                        'public' => true,
                        'has_archive' => false,
                        'pages' => false,
                        'menu_icon' => 'dashicons-editor-help',
                        'supports' => ['title', 'editor'],
                    ]
                );

                // Partners
                register_post_type(
                    'partners',
                    [
                        'labels' => [
                            'name' => __('Partners'),
                            'singular_name' => __('Partner')
                        ],
                        'public' => true,
                        'has_archive' => false,
                        'pages' => false,
                        'menu_icon' => 'dashicons-groups',
                        'supports' => ['title'],
                    ]
                );

                // Testimonials
                register_post_type(
                    'testimonials',
                    [
                        'labels' => [
                            'name' => __('Testimonials'),
                            'singular_name' => __('Testimonial')
                        ],
                        'public' => true,
                        'has_archive' => false,
                        'pages' => false,
                        'menu_icon' => 'dashicons-format-chat',
                        'supports' => ['title', 'editor'],
                    ]
                );

                // flush_rewrite_rules( false );
            }

            /**
             * Enqueue scripts and styles for the front end.
             * 
             * @return void
             */
            function app_scripts_styles()
            {

                // Pull the version/cache-busting file names generated by Laravel Mix
                $manifests = json_decode(file_get_contents(get_template_directory() . '/mix-manifest.json'), true);
                $app_js = $manifests['/app.js'];
                $app_css = str_replace('/style.css', $manifests['/style.css'], get_stylesheet_uri());

                // Load JavaScript files with functionality specific to this theme.
                wp_enqueue_script('app-script', get_template_directory_uri() . $app_js, ['jquery'], null, true);

                // Load theme's main stylesheet.
                wp_enqueue_style('app-style', $app_css, [], null);

                // We enqueue certain libraries here that require jQuery, as importing/requiring them 
                // via JavaScript would cause a conflict with the version that WordPress automatically includes.
                // The CSS is imported in assets/sass/style.scss
                wp_enqueue_script('bootstrap-js', get_template_directory_uri() . '/assets/js/vendor/bootstrap/js/bootstrap.bundle.min.js', ['jquery']);
                wp_enqueue_script('slick-slider-js', get_stylesheet_directory_uri() . '/assets/js/vendor/slick/slick.min.js', ['jquery'], '', true);
                wp_enqueue_script('utm-keeper-js', get_stylesheet_directory_uri() . '/assets/js/utmkeeper.js', ['jquery'], '1.1', true);
            }

            /**
             * Enqueue block editor style and scripts
             * 
             * @return void
             */
            function app_block_editor_styles()
            {
                wp_enqueue_style('app-editor-styles', get_theme_file_uri('/editor-style.css'), false, '1.0', 'all');
                wp_enqueue_script('app-editor-scripts', get_theme_file_uri('/editor.js'), ['wp-blocks', 'wp-dom'], filemtime(get_stylesheet_directory() . '/editor.js'), true);
            }

            /**
             * Add styleselect to TinyMCE editor
             * 
             * @return array
             */
            function app_tinymce_buttons($buttons)
            {
                array_unshift($buttons, 'styleselect');
                return $buttons;
            }

            /**
             * Add styles to TinyMCE editor
             * 
             * @return array
             */
            function app_tinymce_before_init_insert_formats($init_array)
            {

                // Define the style_formats array
                // Each array child is a format with its own settings
                $style_formats = [
                    [
                        'title' => 'Red',
                        'inline' => 'span',
                        'classes' => 'text-primary',
                    ],
                    [
                        'title' => 'Blue',
                        'inline' => 'span',
                        'classes' => 'text-secondary',
                    ],
                    [
                        'title' => 'Purple Button',
                        'selector' => 'a',
                        'classes' => 'btn btn-primary',
                    ],
                    [
                        'title' => 'Royal Button',
                        'selector' => 'a',
                        'classes' => 'btn btn--royal',
                    ],
                    [
                        'title' => 'Razz Button',
                        'selector' => 'a',
                        'classes' => 'btn btn--razz',
                    ],
                    [
                        'title' => 'Dark Blue Button',
                        'selector' => 'a',
                        'classes' => 'btn btn--dark-blue',
                    ],
                    [
                        'title' => 'Darker Blue Button',
                        'selector' => 'a',
                        'classes' => 'btn btn--darker-blue',
                    ],
                    [
                        'title' => 'White Outline Button',
                        'selector' => 'a',
                        'classes' => 'btn btn-outline-white',
                    ],
                ];

                // Insert the array, JSON ENCODED, into 'style_formats'
                $init_array['style_formats'] = json_encode($style_formats);

                return $init_array;
            }

            /** 
             * Add other items to the page head.
             *
             * @return void
             */
            function app_add_head_items()
            {
                $site_url         = get_site_url();
                $site_title       = is_single() ? get_the_title() : htmlentities(get_bloginfo('title'), ENT_QUOTES);
                $site_description = is_single() ? get_the_excerpt() : htmlentities(get_bloginfo('description'), ENT_QUOTES);
                $template_url     = get_template_directory_uri();
                $ajaxurl          = admin_url('admin-ajax.php');

                echo <<<HTML

    <script type="text/javascript">
        var ajaxurl = '{$ajaxurl}';
    </script>
    
HTML;
            }

            /**
             * Filter the page title.
             *
             * Creates a nicely formatted and more specific title element text for output
             * in head of document, based on current view.
             *
             * @param  string  $title  Default title text for current view.
             * @param  string  $sep  Optional separator.
             * @return string  The filtered title.
             */
            function app_wp_title($title, $sep)
            {
                global $paged, $page;

                if (is_feed()) {
                    return $title;
                }

                // Add the site name.
                $title .= get_bloginfo('name');

                // Add the site description for the home/front page.
                $site_description = get_bloginfo('description', 'display');
                if ($site_description && (is_home() || is_front_page())) {
                    $title = "$title $sep $site_description";
                }

                return $title;
            }

            /**
             * Get the URL of an asset.
             *
             * @param  string  $filename
             * @return string
             */
            function app_asset_url($filename)
            {
                return get_template_directory_uri() . '/assets/' . $filename;
            }

            /**
             * Return the post URL.
             *
             * @uses get_url_in_content() to get the URL in the post meta (if it exists) or
             * the first link found in the post content.
             *
             * Falls back to the post permalink if no URL is found in the post.
             *
             * @return string The Link format URL.
             */
            function app_get_link_url()
            {
                $content = get_the_content();
                $has_url = get_url_in_content($content);

                return ($has_url) ? $has_url : apply_filters('the_permalink', get_permalink());
            }

            /**
             * Strip phone number down to just numerals
             * 
             * @param  string  $phone  A phone number
             * @return string
             */
            function app_clean_phone($phone)
            {
                // Remove leading 0 or 1
                if (in_array(substr($phone, 0, 1), array(0, 1))) {
                    $phone = substr($phone, 1);
                }

                return preg_replace("/[^0-9]/", "", $phone);
            }

            /**
             * Obfuscate a string (such as an email address).
             * 
             * @param  string|array  $string
             * @return string
             */
            function app_obfuscate($string)
            {

                // The function is used with preg_replace_callback() 
                // so it needs to be able to accept an array, but only
                // consider the first item.
                if (is_array($string)) {
                    $string = $string[0];
                }

                $chars = str_split($string);
                $encodedChars = array_map('ord', $chars);
                $formatted = array_map(function ($char) {
                    return '&#' . $char . ';';
                }, $encodedChars);

                return implode('', $formatted);
            }

            /**
             * Obfuscate email addresses in the "content".
             */

            add_filter('the_content', 'app_obfuscate_emails_in_content', 10);

            function app_obfuscate_emails_in_content($content)
            {

                // Check if we're inside the main loop in a single post.
                if (is_singular() && in_the_loop() && is_main_query()) {

                    // 1. First replace mailto:[email] strings.
                    $pattern = '/(mailto:)[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i';
                    $content = preg_replace_callback($pattern, 'app_obfuscate', $content);

                    // 2. Then replace just email strings.
                    $pattern = '/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i';
                    $content = preg_replace_callback($pattern, 'app_obfuscate', $content);

                    return $content;
                }

                return $content;
            }

            /**
             * Obfuscate email addresses in the ACF field values.
             */

            add_filter('acf/format_value', 'app_obfuscate_emails_in_acf', 10, 3);

            function app_obfuscate_emails_in_acf($value, $post_id, $field)
            {

                if (!in_array($field['type'], ['text', 'textarea', 'wysiwyg'])) {
                    return $value;
                }

                // 1. First replace mailto:[email] strings.
                $pattern = '/(mailto:)[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i';
                $value = preg_replace_callback($pattern, 'app_obfuscate', $value);

                // 2. Then replace just email strings.
                $pattern = '/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i';
                $value = preg_replace_callback($pattern, 'app_obfuscate', $value);

                return $value;
            }
