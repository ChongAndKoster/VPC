<?php
/*
 * Template Name: Check Registration CVI
 */
?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <div class="color-banner" role="banner">
            <div class="container">
                <h1 class="color-banner__headline"><?php the_field('page_headline'); ?></h1>

                <div class="color-banner__content rich-text">
                    <?php the_field('page_intro_text'); ?>
                </div>
            </div> <!-- .container -->
        </div> <!-- .color-banner -->

        <section class="check-registration-form">
            <div class="container">
                <div class="check-registration-form__form">
                    <div id="spinner" class="spinner spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <script onload="document.getElementById('spinner').style.display='none';" type="text/javascript" src="https://register.rockthevote.com/assets/rtv-iframe.js"></script>
                    <script type="text/javascript">
                        RtvIframe.initLookup({
                            partner: 36794
                        })
                    </script>
                    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/iframe-resizer/3.5.3/iframeResizer.min.js"></script>
                    <script type="text/javascript">
                        iFrameResize({
                            log: true,
                            checkOrigin: false
                        });
                    </script>
                </div>
            </div> <!-- .container -->
        </section> <!-- .check-registration-form -->

        <?php get_template_part('partials/info-blocks'); ?>

<?php endwhile;
endif; ?>
<?php get_footer(); ?>