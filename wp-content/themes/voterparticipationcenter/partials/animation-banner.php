<div 
    class="animation-banner" 
    role="banner" 
    style="background-color: <?php echo esc_attr(get_field('animation_banner_background_color')); ?>"
>
    <div class="container">
        <h1 
            class="animation-banner__headline" 
            style="color: <?php echo esc_attr(get_field('animation_banner_headline_color')); ?>"
        ><?php echo get_field('animation_banner_headline'); ?></h1>
        
    <?php if ($subheadline = get_field('animation_banner_subheadline')): ?>
        <h2 
            class="animation-banner__subheadline" 
            style="color: <?php echo esc_attr(get_field('animation_banner_subheadline_color')); ?>"
        ><?php echo $subheadline; ?></h2>
    <?php endif; ?>
    </div>
    
    <div class="d-lg-none">
        <?php echo get_field('animation_banner_mobile_animation_code'); ?>
    </div>
    
    <div class="container d-none d-lg-block">
        <?php echo get_field('animation_banner_animation_code'); ?>
    </div> <!-- .container -->
</div> <!-- .animation-banner -->
