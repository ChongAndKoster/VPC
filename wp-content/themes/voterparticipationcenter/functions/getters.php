<?php

/*
|--------------------------------------------------------------------------
| Functions, etc.
|--------------------------------------------------------------------------
*/

/**
 * Get Recent Blog Posts
 *
 * @param  string|null  $category    Category slug
 * @param  int          $limit       The maximum number of posts to return
 * @param  string|null  $start_date  Only posts on and after this date will be returned
 * @param  int|null     $exclude_id
 * @return array
 */
function app_get_recent_posts($category = null, $limit = 4, $start_date = null, $exclude_id = null) {
    $args = [
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => 4,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];
    
    if ($category) {
        $args['category_name'] = $category;
    }
    
    if ($start_date) {
        $args['date_query'] = [
            [
                'after' => $start_date,
                'inclusive' => true,
            ],
        ];
    }
    
    if ($exclude_id) {
        $args['post__not_in'] = [$exclude_id];
    }
    
    return new WP_Query($args);
}

/**
 * Collect an array of states
 *
 * @return array
 */
function app_get_states($for_listing = true) {
    $args = [
        'post_type'      => 'states',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
    ];
    
    if ($for_listing) {
        $args['meta_key']   = 'state_display_in_listing';
        $args['meta_value'] = 1;
    }
    
    $states = new WP_Query($args);
    $return = [];
    
    while ($states->have_posts()) {
        $states->the_post();
        $fields = get_fields();
        
        $return[] = [
            'abbrev'        => $fields['state_abbreviation'] ?? '',
            'name'          => get_the_title(),
            'logo'          => $fields['state_logo']['sizes']['state-logo'] ?? '',
            'next_election_date'     => $fields['state_next_election_date'] ?? '',
            'next_election_deadline' => $fields['state_next_election_deadline'] ?? '',
            'vote_by_mail'  => $fields['state_vote_by_mail'] ?? [],
        ];
    }
    
    // Move "In the Military" and "Living Overseas" to the bottom
    $bottom = array_filter($return, function($state) {
        if ($state['name'] == 'In the Military' || $state['name'] == 'Living Overseas') {
            return true;
        }
        return false;
    });
    
    $return = array_filter($return, function($state) {
        if ($state['name'] == 'In the Military' || $state['name'] == 'Living Overseas') {
            $bottom[] = $state;
            return false;
        }
        return true;
    });
        
    $return += $bottom;
    
    return $return;
}

/**
 * Collect an array of states for the search
 *
 * @return array
 */
function app_get_states_for_search() {
    $states = app_get_states(false);
    $return = [];
    
    foreach ($states as $state) {
        $return[] = [
            'id' => $state['abbrev'],
            'name' => $state['name']
        ];
    }
    
    return $return;
}

/**
 * Get a state by abbreviation
 *
 * @param  string  $abbrev
 * @return array
 */
function app_get_state($abbrev) {
    $args = [
        'post_type'      => 'states',
        'post_status'    => 'publish',
        'meta_key'       => 'state_abbreviation',
        'meta_value'     => $abbrev,
        'posts_per_page' => 1,
    ];  
    
    $states = new WP_Query($args);
    $return = [];
    
    while ($states->have_posts()) {
        $states->the_post();
        $fields = get_fields();
        
        $return = [
            'abbrev' => $fields['state_abbreviation'],
            'name'   => get_the_title(),
            'logo'   => $fields['state_logo']['sizes']['state-logo'] ?? null,
        ];
        
        foreach ($fields as $key => $field) {
            
            // Trim "state_" from beginning of key
            if (substr($key, 0, 6) == 'state_') {
                $key = substr($key, 6);
            }
            if ($key == 'logo') continue;
            
            $return[$key] = $field;
        }
    }
    
    return $return;
}

/**
 * Search for a state by abbreviation or name
 *
 * @param  string  $query
 * @return array
 */
function app_search_for_state($query) {
    
    // First check if we're searching by abbreviation
    if (strlen($query) <= 3) {
        $state = app_get_state($query);
        if ($state) {
            return $state;
        }
    }
    
    // Search by name
    global $wpdb;
    $prepared = $wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE post_title LIKE %s", $wpdb->esc_like($query) . '%');
    $state_ids = $wpdb->get_col($prepared);
    
    if (! $state_ids) {
        return [];
    }
    
    $args = [
        'post_type'      => 'states',
        'post_status'    => 'publish',
        'post__in'       => $state_ids,
        'posts_per_page' => 1,
    ];
    
    $states = new WP_Query($args);
    $return = [];
    
    while ($states->have_posts()) {
        $states->the_post();
        $fields = get_fields();
        
        $return = [
            'abbrev' => $fields['state_abbreviation'],
            'name'   => get_the_title(),
            'logo'   => $fields['state_logo']['sizes']['state-logo'],
        ];
        
        foreach ($fields as $key => $field) {
            
            // Trim "state_" from beginning of key
            if (substr($key, 0, 6) == 'state_') {
                $key = substr($key, 6);
            }
            if ($key == 'logo') continue;
            
            $return[$key] = $field;
        }
    }
    
    return $return;
}

/**
 * FAQs
 *
 * @return array
 */
function app_get_faqs() {
    $args = [
        'post_type'      => 'faqs',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC'
    ];  
    
    return new WP_Query($args);
}

/**
 * Partners
 *
 * @return array
 */
function app_get_partners() {
    $args = [
        'post_type'      => 'partners',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC'
    ];  
    
    return new WP_Query($args);
}

/**
 * Testmonials
 *
 * @return array
 */
function app_get_testimonials() {
    $args = [
        'post_type'      => 'testimonials',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC'
    ];  
    
    return new WP_Query($args);
}
