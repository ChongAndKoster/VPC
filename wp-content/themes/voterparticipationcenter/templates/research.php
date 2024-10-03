<?php
/*
 * Template Name: Our Research
 */
?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php
        $bkgd_image        = get_field('banner_background_image');
        $mobile_bkgd_image = get_field('banner_mobile_background_image');
        ?>
        <div id="research">
            <div class="research-banner" role="banner">

                <div class="research-banner__bkgd" style="background-image: url(<?php echo $bkgd_image['sizes']['banner']; ?>);"></div>
                <div class="research-banner__bkgd research-banner__bkgd--mobile" style="background-image: url(<?php echo $mobile_bkgd_image['sizes']['banner'] ?? $bkgd_image['sizes']['banner']; ?>);"></div>

                <div class="container">
                    <span class="research-banner__kicker"><?php the_field('banner_kicker'); ?></span>
                    <h1 class="research-banner__headline"><?php the_field('banner_headline'); ?></h1>
                    <div class="research-banner__content">
                        <p><?php the_field('banner_content'); ?></p>
                    </div>
                </div> <!-- .container -->
            </div>

            <section class="research-charts">
                <div class="container container--tabs">
                    <ul class="nav nav-tabs nav-tabs--purple" role="tablist">

                        <?php
                        // Tabs
                        $index = 0;
                        while (have_rows('research_groups')) :
                            the_row();
                            $slug = sanitize_title(get_sub_field('name'));
                        ?>
                            <li>
                                <a href="#<?php echo $slug; ?>" id="<?php echo $slug; ?>-tab" class="<?php echo ($index == 0) ? 'active' : ''; ?> tab tab--<?php echo $slug; ?>" data-toggle="tab" role="tab" aria-controls="<?php echo $slug; ?>" aria-selected="true"><span><?php echo get_sub_field('name'); ?></span></a>
                            </li>
                        <?php $index++;
                        endwhile; ?>

                    </ul>
                </div> <!-- .container.container--tabs -->

                <div class="tab-content">

                    <?php
                    // Tab panels
                    $index = 0;
                    while (have_rows('research_groups')) :
                        the_row();
                        $slug = sanitize_title(get_sub_field('name'));
                    ?>
                        <div class="tabs__content tabs__content--<?php echo $slug; ?> tab-pane fade <?php echo ($index == 0) ? 'show active' : ''; ?>" id="<?php echo $slug; ?>" role="tabpanel" aria-labelledby="<?php echo $slug; ?>-tab">
                            <div class="container">
                                <h2 class="tabs__headline"><?php echo get_sub_field('chart_title'); ?></h2>
                                <p><?php echo nl2br(get_sub_field('chart_description')); ?></p>

                                <div class="research-chart-wrap">
                                    <div class="research-chart">
                                        <div class="research-chart__chart research-chart__chart--<?php echo $slug; ?>">
                                            <img src="<?php echo get_sub_field('chart_image')['url']; ?>" alt="">
                                        </div>

                                        <?php if ($breakdown_image = get_sub_field('breakdown_image')) : ?>
                                            <button type="button" class="btn btn-secondary btn-modal" @click="activateChartModal('<?php echo $slug; ?>')">
                                                <i class="ico-bar-chart"></i> See breakdown
                                            </button>
                                        <?php endif; ?>

                                        <div class="research-chart__legend">
                                            <?php if ($slug == 'all') : ?>
                                                <span class="legend__item"><?php echo get_sub_field('chart_title'); ?></span>
                                            <?php else : ?>
                                                <span class="legend__item legend__item--<?php echo $slug; ?>"><?php echo get_sub_field('chart_title'); ?></span>
                                                <span class="legend__item">Rising American Electorate</span>
                                            <?php endif; ?>
                                        </div>
                                    </div> <!-- .research-chart -->

                                    <div class="research-modal" tabindex="-1" role="dialog" v-show="activeChartModal == '<?php echo $slug; ?>'">
                                        <div class="modal__dialog" role="document">
                                            <button type="button" class="btn modal__close" aria-label="Close" @click="closeChartModals()">
                                                <i class="ico-times-circle" aria-hidden="true"></i>
                                            </button>

                                            <!-- <h2 class="modal__headline"><?php echo get_sub_field('chart_title'); ?></h2> -->
                                            <p class="mb-4"><?php echo get_sub_field('chart_title'); ?> in the Rising American Electorate are Also...</p>

                                            <img src="<?php echo get_sub_field('breakdown_image')['url']; ?>" alt="" class="d-none d-md-inline">
                                            <img src="<?php echo get_sub_field('mobile_breakdown_image')['url']; ?>" alt="" class="d-md-none">
                                        </div>
                                    </div> <!-- .research-modal -->
                                </div> <!-- .research-chart-wrap -->
                            </div> <!-- .container -->
                        </div> <!-- .tabs__content -->
                    <?php $index++;
                    endwhile; ?>

                </div> <!-- .tab-content -->

                <div class="container">
                    <div class="research-line-graph">
                        <div class="research-line-graph__content" v-show="activeElectorateLineGraph == 'presidential'">
                            <h2 class="research-line-graph__headline"><?php the_field('research_rae_presidential_headline'); ?></h2>
                            <p><?php echo nl2br(get_field('research_rae_presidential_content')); ?></p>
                        </div>

                        <div class="research-line-graph__content" v-show="activeElectorateLineGraph == 'midterms'">
                            <h2 class="research-line-graph__headline"><?php the_field('research_rae_midterms_headline'); ?></h2>
                            <p><?php echo nl2br(get_field('research_rae_midterms_content')); ?></p>
                        </div>

                        <div class="research-line-graph__switch">
                            <button type="button" v-bind:class="{ active: activeElectorateLineGraph == 'presidential' }" @click="setActiveElectorateLineGraph('presidential')">
                                Presidential
                            </button>
                            <button type="button" v-bind:class="{ active: activeElectorateLineGraph == 'midterms' }" @click="setActiveElectorateLineGraph('midterms')">
                                Midterms
                            </button>
                        </div>

                        <div class="research-line-graph__chart" v-show="activeElectorateLineGraph == 'presidential'">
                            <img src="<?php echo get_field('research_rae_presidential_chart')['url']; ?>" alt="" class="d-none d-md-inline">
                            <img src="<?php echo get_field('mobile_research_rae_presidential_chart')['url']; ?>" alt="" class="d-md-none">
                        </div>

                        <div class="research-line-graph__chart" v-show="activeElectorateLineGraph == 'midterms'">
                            <img src="<?php echo get_field('research_rae_midterms_chart')['url']; ?>" alt="" class="d-none d-md-inline">
                            <img src="<?php echo get_field('mobile_research_rae_midterms_chart')['url']; ?>" alt="" class="d-md-none">
                        </div>
                    </div> <!-- .research-line-graph -->
                </div> <!-- .container -->
            </section> <!-- .research-charts -->

            <section class="research-map mb-6">
                <div class="container">
                    <h2 class="research-map__headline"><?php the_field('voter_turnout_map_headline'); ?></h2>
                    <p class="font-weight-bold"><?php echo nl2br(get_field('voter_turnout_map_content')); ?></p>

                    <div class="research-map__map">
                        <?php the_field('voter_turnout_map_embed_code'); ?>
                    </div>
                </div> <!-- .container -->
            </section> <!-- .research-map -->

            <?php get_template_part('partials/stat-cards'); ?>

            <?php get_template_part('partials/info-blocks'); ?>

            <section class="research-blog">
                <div class="container">
                    <div class="research-blog__header">
                        <h2 class="research-blog__headline"><?php the_field('blog_posts_headline'); ?></h2>
                        <div class="rich-text">
                            <?php the_field('blog_posts_content'); ?>
                        </div>
                    </div>

                    <div class="research-blog__posts">
                        <?php $blog_category = 'reports';
                        include(locate_template('partials/blog-post-cards.php', false, false)); ?>
                    </div> <!-- .research-blog__posts -->

                    <div class="research-blog__footer rich-text">
                        <?php the_field('blog_posts_footer_content'); ?>
                    </div>
                </div> <!-- .container -->
            </section> <!-- .research-blog -->

            <?php get_template_part('partials/my-vote'); ?>
        </div> <!-- #research -->

<?php endwhile;
endif; ?>
<?php get_footer(); ?>