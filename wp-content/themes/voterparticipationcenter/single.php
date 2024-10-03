<?php
    get_header();

    $post_date = get_the_date();

    if (have_posts()) : while (have_posts()) : the_post(); ?>

        <article <?php post_class() ?> id="post-<?php the_ID(); ?>">
            <header class="single__header">
                <div class="container">
                    <time datetime="<?php echo date('Y-m-d', strtotime($post_date)); ?>" class="single__date">
                        <?php echo date('M', strtotime($post_date)); ?> 
                        <span><?php echo date('j', strtotime($post_date)); ?></span> 
                        <?php echo date('Y', strtotime($post_date)); ?>
                    </time>

                    <h1 class="single__headline"><?php the_title(); ?></h1>

                    <div class="single__sharing">
                        <?php get_template_part('partials/share-post-links'); ?>
                    </div>
                </div> <!-- .container -->
            </header>

            <div class="blocks">
                <?php the_content(); ?>
            </div> <!-- .blocks -->
        </article>

         <?php get_template_part('partials/share-row'); ?>

    <?php endwhile; endif; ?>

<?php get_footer(); ?>