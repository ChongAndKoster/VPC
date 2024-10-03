<?php get_header(); ?>
<h1>Search Results</h1>
    <?php if (have_posts()) : ?>

        <?php while (have_posts()) : the_post(); ?>

            <article <?php post_class() ?> id="post-<?php the_ID(); ?>">
            
                <h1><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>

                <footer class="meta">
                    <time datetime="<?php echo date(DATE_W3C); ?>" pubdate class="updated"><?php the_time('F jS, Y') ?></time>
                    <?php //comments_popup_link('No Comments', '1 Comment', '% Comments', 'comments-link', ''); ?>
                </footer>

                <div class="entry">
                    <?php //custom_excerpt('custom_excerpt_length', 'custom_excerpt_more'); ?>
                </div>

            </article>

        <?php endwhile; ?>

        <div class="navigation">
            <div class="next-posts"><?php next_posts_link('&laquo; Older Entries') ?></div>
            <div class="prev-posts"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
        </div>

    <?php else : ?>

        <p>Sorry, there were no results found. Please refine your search and try again.</p>

    <?php endif; ?>

<?php get_footer(); ?>
