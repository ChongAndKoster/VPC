<?php get_header(); ?>

<main id="main">
    <div class="container">
        <?php if (have_posts()) : ?>
            
            <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
            
            <?php /* If this is a category archive */ if (is_category()) { ?>
                <h1 class="mb-3">Archive for the &ldquo;<?php echo single_cat_title(); ?>&rdquo; Category</h1>
            
            <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
                <h1 class="mb-3">Posts Tagged &ldquo;<?php single_tag_title(); ?>&rdquo;</h1>
            
            <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
                <h1 class="mb-3">Archive for <?php the_time('F jS, Y'); ?></h1>
            
            <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
                <h1 class="mb-3">Archive for <?php the_time('F, Y'); ?></h1>
            
            <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
                <h1 class="mb-3">Archive for <?php the_time('Y'); ?></h1>
            
            <?php /* If this is an author archive */ } elseif (is_author()) { ?>
                <h1 class="mb-3">Author Archive</h1>
            
            <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && ! empty($_GET['paged'])) { ?>
                <h1 class="mb-3">Blog Archives</h1>
            
            <?php } ?>
            
            <?php while (have_posts()) : the_post(); ?>

                <article <?php post_class() ?> id="post-<?php the_ID(); ?>">
                    
                    <?php if(has_post_thumbnail()): ?>
                        <a href="<?php the_permalink() ?>">
                            <?php the_post_thumbnail('blog_header'); ?>
                        </a>
                    <?php endif; ?>
                    
                    <div class="content">
                        <h2 class="hl-2"><?php the_title(); ?></h2>
                        <div class="rich-text">
                            <?php the_excerpt(); ?>
                        </div>
                        <a href="<?php the_permalink() ?>" class="more">Read More <i class="fa fa-angle-right"></i></a>
                    </div> <!-- .content -->
                </article>
                
            <?php endwhile; ?>

                <div class="pagination">
                    <div class="next-posts"><?php next_posts_link('<i class="fa fa-angle-left"></i> Older Entries') ?></div>
                    <div class="prev-posts"><?php previous_posts_link('Newer Entries <i class="fa fa-angle-right"></i>') ?></div>
                </div>

        <?php else : ?>

            <p>Sorry, there were no results found. Please refine your search and try again.</p>

        <?php endif; ?>
        
        </div> <!-- .container -->
    </main>

<?php get_footer(); ?>