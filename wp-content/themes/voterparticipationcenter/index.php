<?php
    get_header();
    
    $categories = get_categories(array(
        'orderby' => 'name',
        'order'   => 'ASC'
    ));
    
    // Get the current category
    $current_category = get_category(get_query_var('cat'));
    if(isset($current_category->slug)){
        $current_category = $current_category->slug;
    } else {
        $current_category = null;
    }
?>
<header class="blog__header">
    <div class="container">
        <?php if(get_option('page_for_posts')): ?>
            <h1 class="header__headline"><?php echo apply_filters('the_title', get_page(get_option('page_for_posts'))->post_title); ?></h1>
        <?php endif; ?>
        
        <ul class="header__categories post-categories-filter">
            <li class="all-categories"><a href="/news-reports/" class="<?php echo ! $current_category ? 'active' : ''; ?>">All</a></li>
        <?php foreach ($categories as $c): if ($c->slug == 'uncategorized') continue; ?>
            <li class="<?php echo htmlentities($c->slug); ?>">
                <a 
                    href="<?php echo get_category_link($c); ?>"
                    data-category="<?php echo htmlentities($c->slug); ?>" 
                    data-id="<?php echo $c->cat_ID; ?>" 
                    class="<?php echo (($current_category && $current_category == $category->slug) ? 'active' : ''); ?>"
                ><?php echo htmlentities($c->name); ?></a>
            </li>
        <?php endforeach; ?>
        </ul>
    </div>
</header>
    
<div class="container">
    <?php if (have_posts()): ?>
        <div id="blog-posts-container">
            <?php get_template_part('partials/posts'); ?>
        </div>
    <?php endif; ?>
</div> <!-- .container -->
    
<?php get_footer(); ?>
