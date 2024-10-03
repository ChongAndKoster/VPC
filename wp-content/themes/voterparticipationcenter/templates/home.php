<?php
/*
 * Template Name: Home
 */
?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <section class="hero-banner" role="banner">
            <div class="container">
                <div class="hero-banner__content">
                    <div class="hero-banner__headline wow fadeInUp">
                        <h1><?php the_field('page_headline'); ?></h1>
                        <h2><?php the_field('page_subheadline'); ?></h2>
                    </div>

                    <?php if ($text = get_field('page_intro_text')) : ?>
                        <div class="hero-banner__text wow fadeInUp">
                            <?php echo $text; ?>
                        </div>
                    <?php endif; ?>

                    <div class="hero-banner__buttons wow fadeInUp">
                        <a href="/register-to-vote" class="btn btn-main btn-main--arrow btn-main--gradient">
                            <span class="btn__icon d-lg-none"><i class="ico-pencil"></i></span>
                            Register to Vote
                        </a>
                        <a href="/check-registration-status" class="btn btn-main btn-main--arrow">
                            <span class="btn__icon d-lg-none"><i class="ico-magnifying-glass"></i></span>
                            Check My Registration
                        </a>
                        <a href="/stay-informed" class="btn btn-main btn-main--arrow">
                            <span class="btn__icon d-lg-none"><i class="ico-check"></i></span>
                            Stay Informed
                        </a>
                    </div>

                </div>
            </div> <!-- .container -->

            <div class="banner__people">
                <div class="person person--older-lady wow fadeIn" data-wow-delay=".21s">
                    <img src="<?php echo app_asset_url('img/home-hero-older-lady-nov2020.png'); ?>" alt="">
                </div>
                <div class="person person--older-man wow fadeIn" data-wow-delay=".4s">
                    <img src="<?php echo app_asset_url('img/home-hero-older-man-nov2020.png'); ?>" alt="">
                </div>
                <div class="person person--young-woman wow fadeIn" data-wow-delay=".6s">
                    <img src="<?php echo app_asset_url('img/home-hero-young-woman-nov2020.png'); ?>" alt="">
                </div>
                <div class="person person--student wow fadeIn" data-wow-delay=".8s">
                    <img src="<?php echo app_asset_url('img/home-hero-student-nov2020.png'); ?>" alt="">
                </div>
                <div class="person person--young-professional wow fadeIn" data-wow-delay="1s">
                    <img src="<?php echo app_asset_url('img/home-hero-young-professional-nov2020.png'); ?>" alt="">
                </div>
            </div>
        </section> <!-- .banner -->

        <?php get_template_part('partials/info-blocks'); ?>

        <section class="image-blocks">
            <div class="container">
                <header class="image-blocks__header">
                    <span class="header__kicker"><?php the_field('image_blocks_headline_kicker'); ?></span>
                    <h2 class="header__headline"><?php the_field('image_blocks_headline'); ?></h2>
                </header>

                <?php get_template_part('partials/image-blocks'); ?>

                <footer class="image-blocks__footer">
                    <span>Did you get a piece of mail from us?</span>
                    <a href="/got-mail/" class="btn btn-secondary">Learn More</a>
                </footer>
            </div> <!-- .container -->
        </section> <!-- .image-blocks -->

        <?php get_template_part('partials/image-carousel'); ?>
        <?php get_template_part('partials/my-vote'); ?>
        <?php get_template_part('partials/twitter-feed'); ?>

<?php endwhile;
endif; ?>
<?php get_footer(); ?>