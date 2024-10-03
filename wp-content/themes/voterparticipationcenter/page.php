<?php 
    get_header();

    $page_banner_type      = get_field('page_banner_type');
    $page_background_color = get_field('page_background_color');
    
    if (have_posts()) : while (have_posts()) : the_post(); ?>

    <?php if ($page_banner_type == 'gradient'): ?>
        <div class="gradient-banner" role="banner">
            <div class="container mw-840px">
                <h1 class="gradient-banner__headline"><?php the_title(); ?></h1>
            </div> <!-- .container -->
        </div> <!-- .gradient-banner -->
    <?php endif; ?>

    <?php if ($page_banner_type == 'image'): ?>
        <div class="image-banner" role="banner" style="background-image: url(<?php echo app_asset_url('img/_image-banner.png'); ?>">
            <div class="container mw-840px">
                <h1 class="image-banner__headline"><?php the_title(); ?></h1>
            </div> <!-- .container -->
        </div> <!-- .image-banner -->
    <?php endif; ?>

    <?php if ($page_banner_type == 'none' || is_null($page_banner_type)): ?>
        <div class="page__header container">
            <h1 class="page__headline"><?php the_title(); ?></h1>
        </div>
    <?php endif; ?>
        
    <div class="
        blocks 
        <?php echo $page_banner_type != 'none' && ! is_null($page_banner_type) ? 'py-6' : ''; ?> 
        <?php echo 'bg-' . $page_background_color; ?> 
    ">
        <?php the_content(); ?>
    </div>
    
    <?php endwhile; endif; ?>
<?php get_footer(); ?>
