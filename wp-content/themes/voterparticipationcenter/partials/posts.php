<div class="blog-posts">
    
    <?php while (have_posts()): the_post(); ?>
       
       <?php get_template_part('partials/post-excerpt'); ?>
       
    <?php endwhile; ?>

    <div class="blog-post-card placholder"></div>
</div> <!-- .blog-post-cards -->

<?php get_template_part('partials/posts-pagination'); ?>
