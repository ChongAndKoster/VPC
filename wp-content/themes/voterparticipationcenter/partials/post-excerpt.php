<?php
    $date = get_the_date();
    $categories = array_map(function($category) {
        return $category->name;
    }, get_the_category());
                
?><article class="blog-post-card">
    <a 
        href="<?php the_permalink(); ?>" 
        class="blog-post-card__image" 
        style="background-image: url(<?php echo get_the_post_thumbnail_url(); ?>);"
    ></a>
    <div class="blog-post-card__content">
        <div>
            <div class="blog-post-card__meta">
                <time class="blog-post-card__date" datetime="<?php echo date('Y-m-d', strtotime($date)); ?>"><?php echo date('M j, Y', strtotime($date)); ?></time> 
                <span class="blog-post-card__category"><?php echo implode(', ', $categories); ?></span>
            </div>
            <h3 class="blog-post-card__headline"><?php the_title(); ?></h3>
        </div>
        <div>
            <a href="<?php the_permalink(); ?>" class="btn btn-primary">Read</a>
        </div>
    </div>
</article>
