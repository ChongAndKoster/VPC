<?php

/*
|--------------------------------------------------------------------------
| Actions and Filters
|--------------------------------------------------------------------------
*/
add_filter('nav_menu_css_class', 'add_classes_on_li', 1, 3);
add_filter('wp_nav_menu', 'add_classes_on_a');

/*
|--------------------------------------------------------------------------
| Functions, etc.
|--------------------------------------------------------------------------
*/

/**
 * Add Bootstrap "nav-item" class on navigation <li> tags
 */
function add_classes_on_li($classes, $item, $args) {
    $classes[] = 'nav-item';
    return $classes;
}

/**
 * Add Bootstrap "nav-link" class on navigation <a> tags
 */
function add_classes_on_a($ulclass) {
    return preg_replace('/<a /', '<a class="nav-link"', $ulclass);
}

/**
 * Custom nav walker to add sub-menu toggle buttons to the
 * mobile navigation menu.
 */
class app_MobileNavWalker extends Walker_Nav_Menu
{
    /**
     * Starts the list before the elements are added.
     *
     * @since 3.0.0
     *
     * @see Walker::start_lvl()
     *
     * @param string   $output Used to append additional content (passed by reference).
     * @param int      $depth  Depth of menu item. Used for padding.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */
    public function start_lvl( &$output, $depth = 0, $args = null ) {
        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = str_repeat( $t, $depth );

        // Default class.
        $classes = array( 'sub-menu' );

        /**
         * Filters the CSS class(es) applied to a menu list element.
         *
         * @since 4.8.0
         *
         * @param string[] $classes Array of the CSS classes that are applied to the menu `<ul>` element.
         * @param stdClass $args    An object of `wp_nav_menu()` arguments.
         * @param int      $depth   Depth of menu item. Used for padding.
         */
        $class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
        $class_names = $class_names ? ' class="collapse ' . esc_attr( $class_names ) . '"' : '';
        
        /**
         * Add a button to toggle the visibility of the sub-menu.
         * Also applies an id to a menu list element.
         */
        $id = null;
        $id_str = '';
        preg_match_all('/menu-item-(\d+)/', $output, $matches);
        if ($matches) {
            $id = array_pop($matches[1]);
        
            $output .= '<button
                type="button" 
                aria-label="Sub-Menu"
                data-toggle="collapse" 
                data-target="#sub-menu-' . $id . '"
                aria-expanded="false" 
                aria-controls="sub-menu-' . $id . '"
            >
                <i class="ico-angle-down" aria-hidden="true"></i>
            </button>';

            $id_str = 'id="sub-menu-' . $id . '"';
        }
        
        $output .= "{$n}{$indent}<ul$class_names $id_str>{$n}";
    }
}

/**
 * Display navigation to next/previous *single post* when applicable.
 *
 * @return void
 */
function app_post_nav() {
    global $post;

    // Don't print empty markup if there's nowhere to navigate.
    $previous = (is_attachment()) ? get_post($post->post_parent) : get_adjacent_post(false, '', true);
    $next     = get_adjacent_post(false, '', false);

    if (! $next && ! $previous)
        return;
    ?>
    <nav class="navigation post-navigation section" role="navigation">
        <h1 class="screen-reader-text"><?php _e('Post navigation', 'app'); ?></h1>
        <div class="nav-links">

            <?php if(get_previous_post_link()): ?>
            <div class="nav-previous"><?php previous_post_link('%link', _x('<i class="fa fa-arrow-circle-left"></i> Previous Post', 'Previous post link', 'app')); ?></div>
            <?php endif; ?>

            <?php if(get_next_post_link()): ?>
            <div class="nav-next"><?php next_post_link('%link', _x('Next Post <i class="fa fa-arrow-circle-right"></i>', 'Next post link', 'app')); ?></div>
            <?php endif; ?>

        </div><!-- .nav-links -->
    </nav><!-- .navigation -->
    <?php
}

/**
 * Display navigation to next/previous *set of posts* when applicable.
 *
 * @return void
 */
function app_paging_nav() {
    global $wp_query;

    // Don't print empty markup if there's only one page.
    if($wp_query->max_num_pages < 2)
        return;
    ?>
    <nav class="navigation paging-navigation section" role="navigation">
        <h1 class="screen-reader-text"><?php _e('Posts navigation', 'app'); ?></h1>
        <div class="nav-links">
        
        <?php if(get_previous_posts_link()) : ?>
            <div class="nav-next"><?php previous_posts_link( __('Newer posts <i class="fa fa-arrow-circle-right"></i>', 'app')); ?></div>
        <?php endif; ?>

        <?php if(get_next_posts_link()) : ?>
            <div class="nav-previous"><?php next_posts_link( __('<i class="fa fa-arrow-circle-left"></i> Older posts', 'app')); ?></div>
        <?php endif; ?>

        </div><!-- .nav-links -->
    </nav><!-- .navigation -->
    <?php
}
