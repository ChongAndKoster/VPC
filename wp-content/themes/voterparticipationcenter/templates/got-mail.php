<?php
/*
 * Template Name: Got Mail
 */
?>
<?php get_header(); ?>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    
    <section class="hero-image-banner" role="banner">
        <div class="container">
            <div class="hero-image-banner__content">
                <h1 class="hero-image-banner__headline"><?php the_field('page_headline'); ?></h1>
                
                <?php the_field('page_intro_text'); ?>
                
                <div class="btn-group-main btn-group-main--darker-blue btn-group-main--3-btns wow fadeInUp">
                    <a href="/register-to-vote" class="btn btn-main btn-main--arrow btn-main--dark-blue">
                        <span class="btn__icon d-lg-none"><i class="ico-pencil"></i></span> 
                        Register to Vote
                    </a>
                    <a href="/check-registration-status" class="btn btn-main btn-main--arrow btn-main--blue-green-gradient">
                        <span class="btn__icon d-lg-none"><i class="ico-magnifying-glass"></i></span> 
                        Check My Registration
                    </a>
                    <a href="/my-voter-info" class="btn btn-main btn-main--arrow">
                        <span class="btn__icon d-lg-none"><i class="ico-check"></i></span> 
                        Voting in My State
                    </a>
                </div>
                
                <div class="btn-group-plain btn-group-plain--white wow fadeInUp">
                    <a 
                        href="#unsubscribe-wrap" 
                        class="btn" 
                        role="button" 
                        data-scrollto="#unsubscribe"
                        aria-expanded="false" 
                        aria-controls="unsubscribe-wrap" 
                        onclick="jQuery('.collapse').collapse('show')"
                    >
                        Unsubscribe or Report an Error
                    </a>
                    <a href="/about-us" class="btn">Who is VPC?</a>
                </div>
            </div>
        </div> <!-- .container -->
        <img src="<?php echo app_asset_url('img/_opening-mailbox.png'); ?>" alt="" class="hero-image-banner__image">
    </section> <!-- .banner -->
    
    <section id="unsubscribe">
        <div id="unsubscribe-wrap" class="unsubscribe-form collapse">
            <div class="container">
                <iframe 
                    src="https://docs.google.com/forms/d/e/1FAIpQLSczy5VmjK-hDYRA-c_l3BVUFl9bUVzxP1p6JpQaGAmS_RJyOQ/viewform?embedded=true" 
                    width="100%" 
                    height="1899" 
                    frameborder="0" 
                    marginheight="0" 
                    marginwidth="0"
                >
                    Loading...
                </iframe>
            </div> <!-- .container -->
        </div> <!-- .unsubscribe-form -->
    </section>
    
    <?php get_template_part('partials/info-blocks'); ?>
    
    <?php endwhile; endif; ?>
<?php get_footer(); ?>

<script>
    // Open the unsubscribe pane via URL hash
    if (window.location.hash == '#unsubscribe') {
        document.getElementById('unsubscribe-wrap').classList.add('show');
    }
</script>
