<?php

/*
|--------------------------------------------------------------------------
| Actions and Filters
|--------------------------------------------------------------------------
*/
add_action('manage_states_posts_custom_column', 'app_manage_states_columns', 10, 2);
add_filter('manage_edit-states_columns', 'app_edit_states_columns');

/*
|--------------------------------------------------------------------------
| Functions, etc.
|--------------------------------------------------------------------------
*/

/** 
 * Add a "Abbrev." and "Listed" columns to the States list page in admin
 *
 * @param  array  $columns
 * @return array
 */
function app_edit_states_columns($columns){
    $columns = 
    [
        'cb'     => '<input type="checkbox">',
        'title'  => __('Title'),
        'abbrev' => __('Abbrev.'),
        'listed' => __('Listed'),
        'date'   => __('Date'),
    ];

    return $columns;
}

/** 
 * Add data to the "Abbrev." and "Listed" columns to the States list page in admin
 *
 * @param  string  $column
 * @param  int     $post_id
 * @return string
 */
function app_manage_states_columns($column, $post_id){
    global $post;

    switch($column) {
        case 'abbrev' :
            echo get_field('state_abbreviation', $post_id);
            break;
        case 'listed' :
            echo get_field('state_display_in_listing', $post_id) ? 'Yes' : 'No';
            break;
        default :
            break;
    }
}
