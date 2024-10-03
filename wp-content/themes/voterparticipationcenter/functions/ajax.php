<?php

/*
|--------------------------------------------------------------------------
| Actions and Filters
|--------------------------------------------------------------------------
*/
add_action('wp_ajax_loadStateDetail', 'app_loadStateDetail');
add_action('wp_ajax_nopriv_loadStateDetail', 'app_loadStateDetail');

add_action('wp_ajax_searchStates', 'app_searchStates');
add_action('wp_ajax_nopriv_searchStates', 'app_searchStates');

/*
|--------------------------------------------------------------------------
| Functions, etc.
|--------------------------------------------------------------------------
*/

/**
 * Load a state's information as JSON
 *
 * @return void
 */
function app_loadStateDetail() {
    $abbrev = substr(preg_replace('/[^a-z]/i', '', $_REQUEST['state']), 0, 3);
    
    $state = app_get_state($abbrev);
    if (! $state) {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        exit;
    }
    
    echo json_encode($state);
    exit;
}

/**
 * Seach for, and load, a state's information as JSON
 *
 * @return void
 */
function app_searchStates() {
    $query = preg_replace('/[^a-z ]/i', '', $_REQUEST['q']);
    
    $state =  app_search_for_state($query);
    if (! $state) {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
        exit;
    }
    
    echo json_encode($state);
    exit;
}

/*
|--------------------------------------------------------------------------
| Load blog/news posts
|--------------------------------------------------------------------------
*/

add_action('wp_ajax_nopriv_load_posts', 'app_load_posts');
add_action('wp_ajax_load_posts', 'app_load_posts');

function app_load_posts() {
    $query_vars = json_decode(stripslashes($_POST['query_vars']), true);
    $post_category = $_POST['post_category'];

    $args = [
        'post_status'    => 'publish',
        'post_type'      => 'post',
        'posts_per_page' => get_option('posts_per_page'),
        'orderby'        => 'date',
        'order'          => 'DESC',
        'category_name'  => $post_category,
    ];

    // Pagination
    if (isset($_POST['page'])) {
        $args['paged'] = $_POST['page'];
    }

    $posts = new WP_Query($args);
    $GLOBALS['wp_query'] = $posts;
    
    get_template_part('partials/posts');
    exit;
}
