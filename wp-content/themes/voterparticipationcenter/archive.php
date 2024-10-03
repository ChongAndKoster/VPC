<?php get_header(); ?>

<header class="blog__header">
    <div class="container">
    <?php if (have_posts()): ?>
        <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
        
        <?php /* If this is a category archive */ if (is_category()) { ?>
            <h1 class="header__headline"><?php echo single_cat_title(); ?></h1>
        
        <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
            <h1 class="header__headline"><?php single_tag_title(); ?></h1>
        
        <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
            <h1 class="header__headline">Archive for <?php the_time('F jS, Y'); ?></h1>
        
        <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
            <h1 class="header__headline">Archive for <?php the_time('F, Y'); ?></h1>
        
        <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
            <h1 class="header__headline">Archive for <?php the_time('Y'); ?></h1>
        
        <?php /* If this is an author archive */ } elseif (is_author()) { ?>
            <h1 class="header__headline">Author Archive</h1>
        
        <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && ! empty($_GET['paged'])) { ?>
            <h1 class="header__headline">Blog Archives</h1>
        <?php } ?>
    <?php endif; ?>
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