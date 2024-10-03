<?php
/*
 * Template Name: About Us
 */
?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <section class="three-cards mt-5 mt-lg-9">
            <div class="container">
                <header class="three-cards__header">
                    <h1 class="three-cards__headline"><?php the_field('page_headline'); ?></h1>

                    <div class="rich-text">
                        <?php the_field('page_intro_text'); ?>
                    </div>
                </header>

                <?php if (have_rows('page_cards')) : ?>
                    <div class="three-cards__cards">

                        <?php while (have_rows('page_cards')) : the_row(); ?>
                            <div class="card">
                                <div class="card__image">
                                    <?php if ($icon_code = get_sub_field('icon_code')) : ?>
                                        <?php echo $icon_code; ?>
                                    <?php else : ?>
                                        <img src="<?php echo get_sub_field('icon_image')['sizes']['card-icon']; ?>" alt="">
                                    <?php endif; ?>
                                </div>
                                <h2 class="card__headline"><?php echo get_sub_field('headline'); ?></h2>
                                <div class="card__content">
                                    <?php echo get_sub_field('text'); ?>
                                </div>
                            </div> <!-- .card -->
                        <?php endwhile; ?>

                    </div> <!-- .three-cards__cards -->
                <?php endif; ?>

            </div> <!-- .container -->
        </section> <!-- .three-cards -->

        <section class="leadership mt-10 pt-3">

            <h2 class="leadership__headline"><?php the_field('leadership_headline'); ?></h1>

                <div class="leadership__content">
                    <div class="container">

                        <?php if (have_rows('leadership_team_members')) : ?>
                            <div class="leadership__leaders">

                                <?php while (have_rows('leadership_team_members')) : the_row(); ?>
                                    <div class="leader">
                                        <div class="leader__image">
                                            <img src="<?php echo get_sub_field('image')['sizes']['headshot']; ?>" alt="">
                                        </div>
                                        <h3 class="leader__name"><?php echo get_sub_field('name'); ?></h3>
                                        <span class="leader__title"><?php echo get_sub_field('title'); ?></span>
                                        <div class="leader__bio">
                                            <p><?php echo nl2br(get_sub_field('description')); ?></p>
                                        </div>
                                    </div> <!-- .leader -->
                                <?php endwhile; ?>

                            </div> <!-- .leadership__leaders -->
                        <?php endif; ?>

                        <div class="leadership__info">
                            <h3 class="info__headline"><?php the_field('leadership_callout_headline'); ?></h3>

                            <p><?php the_field('leadership_callout_text'); ?></p>
                        </div> <!-- .leadership__info -->
                    </div> <!-- .container -->
                </div> <!-- .leadership__content -->
        </section> <!-- .leadership -->

        <?php
        // Partners area
        $partners = app_get_partners();
        if ($partners->have_posts()) :
        ?>
            <section class="partners">
                <div class="container">
                    <h2 class="partners__headline"><?php the_field('partners_headline'); ?></h1>

                        <div class="partners__logos partner-logos">
                            <div class="slides">

                                <?php
                                while ($partners->have_posts()) :
                                    $partners->the_post();
                                ?>
                                    <div class="slide">
                                        <?php if ($url = get_field('url')) : ?>
                                            <a href="<?php echo $url; ?>" target="_blank">
                                            <?php endif; ?>
                                            <img src="<?php echo get_field('logo')['sizes']['partner-logo']; ?>" alt="<?php echo htmlentities(get_the_title()); ?>">
                                            <?php if ($url) : ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endwhile;
                                wp_reset_query(); ?>

                            </div>
                        </div> <!-- .partners__logos -->

                        <small class="partners__note"><?php echo nl2br(get_field('partners_note')); ?></small>
                </div> <!-- .container -->
            </section> <!-- .partners -->
        <?php endif; ?>

<?php endwhile;
endif; ?>
<?php get_footer(); ?>