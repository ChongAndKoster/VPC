<?php
/*
 * Template Name: Contact Us
 */

// Collect tabs into an array
$tabs = [];
$current_tab = null;

while (have_rows('tabs')) {
    the_row();
    $slug = sanitize_title(get_sub_field('tab_label'));
    $tabs[$slug] = [
        'label'    => get_sub_field('tab_label'),
        'headline' => get_sub_field('headline'),
        'text'     => get_sub_field('text'),
        'button'   => get_sub_field('button'),
        'additional_button' => get_sub_field('additional_button'),
        'display_button_group' => get_sub_field('display_button_group'),
    ];
    
    if (! $current_tab) {
        $current_tab = $slug; // Set current tab to the first one
    }
    
    if ($form_id = get_sub_field('gravity_form_id')) {
        $tabs[$slug]['form_id'] = $form_id;
    }
}

get_header(); 

?>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    
    <section class="contact-tabs">
        <header class="contact-tabs__header">
            <div class="container">
                <h1 class="contact-tabs__headline"><?php the_field('page_headline'); ?></h1>
                <div class="contact-tabs__content rich-text">
                    <?php the_field('page_intro_text'); ?>
                </div>
            </div> <!-- .container -->
        </header>
            
        <div class="contact-tabs__tabs">
            <div class="container">
                <ul class="nav nav-tabs" role="tablist">
                    
                <?php 
                    // Tabs
                    foreach ($tabs as $slug => $tab): 
                ?>
                    <li>
                        <a 
                            href="#<?php echo $slug; ?>" 
                            id="<?php echo $slug; ?>-tab" 
                            class="<?php echo ($slug == $current_tab) ? 'active' : ''; ?>" 
                            data-toggle="tab" 
                            data-form-id="<?php echo $tab['form_id'] ?? ''; ?>"
                            role="tab" 
                            aria-controls="<?php echo $slug; ?>" 
                            aria-selected="<?php echo ($slug == $current_tab) ? 'true' : 'false'; ?>"
                        ><span><?php echo $tab['label']; ?></span></a>
                    </li>
                <?php endforeach; ?>
                
                </ul>
                
                <div class="tab-content">
                    
                <?php 
                    // Tab panels
                    foreach ($tabs as $slug => $tab): 
                ?>
                    <div 
                        class="tabs__content tab-pane fade <?php echo ($slug == $current_tab) ? 'show active' : ''; ?> <?php echo (! isset($tab['form_id'])) ? 'tabs__content--no-form' : ''; ?>" 
                        id="<?php echo $slug; ?>" 
                        role="tabpanel" 
                        aria-labelledby="<?php echo $slug; ?>-tab"
                    >
                        <div class="container mw-660px">
                            <h2 class="tabs__headline"><?php echo $tab['headline']; ?></h2>
                            <p><?php echo nl2br($tab['text']); ?></p>
                        
                        <?php if ($tab['button']): ?>
                            <div class="mt-4">
                                <a href="<?php echo $tab['button']['url']; ?>" target="<?php echo $tab['button']['target']; ?>" class="btn btn-outline-white">
                                    <?php echo $tab['button']['title']; ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if ($tab['additional_button']): ?>
                            <div class="mt-2">
                                <a href="<?php echo $tab['additional_button']['url']; ?>" target="<?php echo $tab['additional_button']['target']; ?>" class="btn btn-outline-white">
                                    <?php echo $tab['additional_button']['title']; ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        </div>
                        
                        <?php 
                            // Voter-related button group
                            if ($tab['display_button_group']): 
                        ?>
                            <div class="container mw-840px">
                                <div class="btn-group-main btn-group-main--darker-blue btn-group-main--3-btns">
                                    <a href="/register-to-vote" class="btn btn-main btn-main--arrow btn-main--darker-blue">
                                        <span class="btn__icon d-lg-none"><i class="ico-pencil"></i></span> 
                                        Register to Vote
                                    </a>
                                    <a href="/check-registration-status" class="btn btn-main btn-main--arrow btn-main--gradient">
                                        <span class="btn__icon d-lg-none"><i class="ico-magnifying-glass"></i></span> 
                                        Check My Registration
                                    </a>
                                    <a href="/my-voter-info" class="btn btn-main btn-main--arrow">
                                        <span class="btn__icon d-lg-none"><i class="ico-check"></i></span> 
                                        Voting in My State
                                    </a>
                                </div>
                                
                                <div class="btn-group-plain btn-group-plain--white">
                                    <a href="/got-mail/#unsubscribe" class="btn">
                                        Unsubscribe or Report an Error
                                    </a>
                                    <a href="/about-us" class="btn">Who is VPC?</a>
                                </div>
                            </div> <!-- .container -->
                        <?php endif; ?>
                    </div> <!-- .tabs__content -->
                <?php endforeach; ?>
                
                </div> <!-- .tab-content -->
            </div> <!-- .container -->
        </div> <!-- .contact-tabs__tabs -->
        
        <div class="contact-tabs__form" id="forms">
            <div class="container tab-content">
            <?php 
                foreach ($tabs as $slug => $tab): 
                    if (! empty($tab['form_id'])):
            ?>
                <div 
                    class="form__wrap form-vpc tab-pane fade <?php echo ($slug == $current_tab) ? 'show active' : ''; ?>" 
                    id="<?php echo $slug; ?>-form" 
                    role="tabpanel" 
                    aria-labelledby="<?php echo $slug; ?>-tab"
                >
                    <?php /*<h2 class="form__headline">Contact VPC</h2>*/ ?>
                    <?php gravity_form($tab['form_id'], false, true, false, '', false); ?>
                </div> <!-- .form__wrap -->
            <?php 
                    endif;
                endforeach;
            ?>
            </div> <!-- .container -->
        </div> <!-- .contact-tabs__form -->
    </section> <!-- .contact-tabs -->
    
    <?php get_template_part('partials/info-blocks'); ?>
    
    <?php endwhile; endif; ?>
<?php get_footer(); ?>

<script>

    // Activate a specific tab via URL hash
    if (window.location.hash) {
        var hash = window.location.hash;
        var formId = jQuery(hash + '-tab').data('form-id');
        
        if (jQuery(window.location.hash + '-tab').length) {
            jQuery(hash + '-tab').tab('show');
            setForm(hash, formId);
        }
    }
    
    // Display form for selected tab
    jQuery('.nav-tabs a').on('click', function(e) {        
        var hash   = jQuery(this).attr('href');
        var formId = jQuery(this).data('form-id');
        setForm(hash, formId);
    });
    
    // Set the form
    function setForm(hash, formId) {
        jQuery('#forms .form-vpc').removeClass('active show');
        
        // Reduce jumpiness due to the "Got Mail" tab not having a form.
        if (hash == '#got-mail') {
            jQuery('.contact-tabs__tabs .tab-content').css('min-height', '455px');
        } else {
            jQuery('.contact-tabs__tabs .tab-content').css('min-height', 'auto');
        }
        
        if (formId) {
            jQuery(hash + '-form').addClass('active show');
        }
    }
</script>
