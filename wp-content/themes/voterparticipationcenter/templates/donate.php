<?php
/*
 * Template Name: Donate
 */
?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <div class="hero-donate" role="banner" style="background-image: url(<?php echo get_field('hero_banner_image')['sizes']['banner']; ?>);">
            <div class="container">
                <div class="hero-donate__content">
                    <h1 class="hero-donate__headline"><?php the_field('page_headline'); ?></h1>

                    <div class="rich-text">
                        <?php the_field('page_intro_text'); ?>
                    </div>

                    <a href="#more-ways-to-give" class="btn-main btn-main--arrow btn-main--gradient" data-scrollto="#more-ways-to-give">Giving Options</a>
                </div>
            </div> <!-- .container -->
        </div> <!-- .banner -->

        <?php if ($more_ways_to_give = get_field('more_ways_to_give')) : ?>
            <section id="more-ways-to-give" class="more-ways-to-give">
                <div class="container">
                    <h2 class="more-ways-to-give__headline"><?php echo $more_ways_to_give['headline']; ?></h2>

                    <div class="ways-to-give">

                        <?php foreach ($more_ways_to_give['ways_to_give'] as $way) : ?>
                            <div class="ways-to-give__way">
                                <div class="way__inner">
                                    <div class="inner__image">
                                        <?php echo wp_get_attachment_image($way['image']['ID'], 'medium_large'); ?>
                                    </div>
                                    <div class="inner__info">
                                        <h3 class="info__headline"><?php echo $way['headline']; ?></h3>

                                        <p><?php echo nl2br($way['description']); ?></p>
                                    </div>
                                </div>

                                <?php if (!empty($way['button']['url'])) : ?>
                                    <a href="<?php echo app_obfuscate(esc_url($way['button']['url'])); ?>" class="way__btn" <?php if (substr($way['button']['url'], 0, 1) == '#') : ?> data-scrollto="<?php echo esc_attr($way['button']['url']); ?>" <?php endif; ?>><?php echo $way['button']['title']; ?></a>
                                <?php endif; ?>

                            </div>
                        <?php endforeach; ?>

                    </div> <!-- .ways-to-give -->

                    <div class="more-ways-to-give__lower">
                        <span><?php echo $more_ways_to_give['lower_headline']; ?></span>

                        <?php if (!empty($more_ways_to_give['lower_button']['url'])) : ?>
                            <a href="<?php echo app_obfuscate(esc_url($more_ways_to_give['lower_button']['url'])); ?>" class="btn"><?php echo $more_ways_to_give['lower_button']['title']; ?></a>
                        <?php endif; ?>

                    </div>
                </div> <!-- .container -->
            </section>
        <?php endif; ?>

        <section class="image-blocks">
            <div class="container">
                <header class="image-blocks__header">
                    <span class="header__kicker"><?php the_field('image_blocks_headline_kicker'); ?></span>
                    <h2 class="header__headline"><?php the_field('image_blocks_headline'); ?></h2>
                </header>

                <?php get_template_part('partials/image-blocks'); ?>

                <!-- red box -->
                <a class="image-blocks__red-box" href="/red-box">
                    An example
                </a>
            </div> <!-- .container -->
        </section> <!-- .image-blocks -->

        <section class="donate-form" id="donate-form">
            <div class="container">
                <div class="donate-form__wrap">
                    <div class="donate-form__header">
                        <a href="/our-research">Our Research</a>
                        <a href="/contact-us">Contact Us</a>
                    </div>

                    <div class="donate-form__content">
                        <h2 class="donate-form__headline">Give Online</h2>

                        <div class="donate-form__info rich-text">
                            <p>The Voter Participation Center is a 501(c)(3) tax-exempt organization. No goods or services will be provided in exchange for your generous contribution.</p>
                        </div>

                        <script type='text/javascript' src='https://d1aqhv4sn5kxtx.cloudfront.net/actiontag/at.js' crossorigin='anonymous'></script>
                        <div class="ngp-form" data-form-url="https://actions.everyaction.com/v1/Forms/bbvz6JaIsUSKBNvwccLQSA2" data-fastaction-endpoint="https://fastaction.ngpvan.com" data-inline-errors="true" data-fastaction-nologin="true" data-databag-endpoint="https://profile.ngpvan.com" data-databag="everybody"></div>
                    </div>

                    <div class="donate-form__footer rich-text">
                        <p>Your contribution to the Voter Participation Center is tax-deductible to the fullest extent allowable under the law.</p>
                    </div>
                </div> <!-- .donate-form__wrap -->
            </div> <!-- .container -->
        </section> <!-- .donate-form -->
<?php
    endwhile;
endif;
?>
<?php get_footer(); ?>