<?php 
    /*
    * Template Name: Custom Tool - State Search
    */
    get_header();

    $page_banner_type      = get_field('page_banner_type');
    $page_background_color = get_field('page_background_color');
    
    if (have_posts()) : while (have_posts()) : the_post(); ?>

    <div class="gradient-banner" role="banner">
        <div class="container mw-840px">
            <h1 class="gradient-banner__headline"><?php the_title(); ?></h1>
            <p>&nbsp;</p>

            <form method="GET" action="#" id="state-search-form" class="wow fadeInDown">
                <div class="fields">
                    <div class="field"><input id="state-search-text" name="state-search-text" type="text" placeholder="Search for your state" /></div>
                    <div class="field-action"><input type="submit" value="Go" /></div>
                </div>
            </form>

        </div>
    </div>
        
    <div class="
        blocks 
        <?php echo $page_banner_type != 'none' && ! is_null($page_banner_type) ? 'py-0' : ''; ?> 
        <?php echo 'bg-' . $page_background_color; ?> 
    ">
        <div class="wow fadeIn"><?php the_content(); ?></div>
    </div>
    
    <?php endwhile; endif; ?>

    <script>
        document.getElementById('state-search-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const searchText = document.getElementById('state-search-text').value.toLowerCase();
            const stateGroups = document.querySelectorAll('.stateGroup');
            
            stateGroups.forEach(function(group) {
                if (group.textContent.toLowerCase().includes(searchText)) {
                    group.style.display = 'block';
                } else {
                    group.style.display = 'none';
                }
            });
        });
    </script>

<?php get_footer(); ?>
