jQuery(function() {

    /**
     * Filter blog posts by category
     */
    jQuery('body.blog .post-categories-filter a').on('click', function(e) {
        e.preventDefault();

        // Temporarily disable all links
        jQuery('.post-categories-filter a').addClass('disabled');
        
        var category = jQuery(this).data('category');
        
        filterPosts(category);
    });

    /**
     * Pagination
     */
    jQuery(document).on('click', 'body.blog .pagination a', function(e) {
        e.preventDefault();

        var url_parts = jQuery(this).attr('href').split('/');
        var page = url_parts[url_parts.length - 2];
        if (! jQuery.isNumeric(page)) {
            page = 1;
        }
        var category = getActiveCategory();

        jQuery.ajax({
            url: window.ajaxurl,
            type: 'post',
            data: {
                action: 'load_posts',
                post_category: category,
                query_vars: window.query_vars,
                page: page
            },
            beforeSend: function() {
                jQuery('#blog-posts-container').empty();
                jQuery('html, body').animate({ scrollTop: 0 }, "slow");
                jQuery('#blog-posts-container').append('<div class="loader"><div class="spinner-border" role="status"></div> Loading posts...</div>');
            },
            success: function(response) {
                jQuery('.loader').remove();
                jQuery('#blog-posts-container').append(response);
                jQuery('.post-categories-filter a').removeClass('disabled');
            }
        });
    });
});

/**
 * Filter blog posts by category
 */
function filterPosts(category) {
    // Update filters nav
    jQuery('.post-categories-filter a').removeClass('active');
    if (category) {
        jQuery('.post-categories-filter li.' + category + ' a').addClass('active');
    } else {
        jQuery('.post-categories-filter li.all-categories a').addClass('active');
    }
    
    // Load posts in active categories
    jQuery.ajax({
        url: window.ajaxurl,
        type: 'post',
        data: {
            action: 'load_posts',
            post_category: category
        },
        beforeSend: function() {
            jQuery('#blog-posts-container').empty();
            jQuery('html, body').animate({ scrollTop: 0 }, "slow");
            jQuery('#blog-posts-container').append('<div class="loader"><div class="spinner-border" role="status"></div> Loading posts...</div>');
        },
        success: function(response) {
            jQuery('.loader').remove();
            jQuery('#blog-posts-container').append(response);
            jQuery('.post-categories-filter a').removeClass('disabled');
        }
    });
}

/**
 * Get the active post category slug
 */
function getActiveCategory() {
    return jQuery('.post-categories-filter a.active').data('category');
}
