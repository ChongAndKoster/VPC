<?php

    // Get the "Our Research" page ID
    $our_research_post_id = null;
    $our_research_post = get_page_by_path('our-research', OBJECT, 'page');
    if ($our_research_post) {
        $our_research_post_id = $our_research_post->ID;
    }
?><section class="my-vote">
    <div class="container">
        <header class="my-vote__header wow fadeInUp">
            <h2 class="my-vote__headline"><?php the_field('my_vote_headline', $our_research_post_id); ?></h2>
            <div class="rich-text">
                <?php the_field('my_vote_content', $our_research_post_id); ?>
            </div>
            
        <?php if ($button = get_field('my_vote_button', $our_research_post_id)): ?>
            <div class="btn-wrap">
                <a href="<?php echo $button['url']; ?>" target="<?php echo $button['target']; ?>" class="btn btn-main btn-main--arrow btn-main--gradient"><?php echo $button['title']; ?></a>
            </div>
        <?php endif; ?>
        </header>
        
        <main class="my-vote__infographic">
        <?php
            $labels = get_field('my_vote_labels', $our_research_post_id);
        ?>
            <div class="infographic__captions">
                <div class="caption wow fadeInUp" data-wow-delay="1.3s">
                    <span class="caption__icon text-hide">My Vote</span>
                    <span class="caption__label"><?php echo $labels[0]['label']; ?></span>
                </div>
                <div class="caption wow fadeInUp" data-wow-delay="1.4s">
                    <span class="caption__icon text-hide">My Vote</span>
                    <span class="caption__label"><?php echo $labels[1]['label']; ?></span>
                </div>
                <div class="caption wow fadeInUp" data-wow-delay="1.5s">
                    <span class="caption__icon text-hide">My Vote</span>
                    <span class="caption__label"><?php echo $labels[2]['label']; ?></span>
                </div>
                <div class="caption wow fadeInUp" data-wow-delay="1.6s">
                    <span class="caption__icon text-hide">My Vote</span>
                    <span class="caption__label"><?php echo $labels[3]['label']; ?></span>
                </div>
                <div class="caption wow fadeInUp" data-wow-delay="1.7s">
                    <span class="caption__icon text-hide">My Vote</span>
                    <span class="caption__label"><?php echo $labels[4]['label']; ?></span>
                </div>
            </div> <!-- .infographic__captions -->

            <div class="infographic__people">
                <img src="<?php echo app_asset_url('img/my-vote/young-girl.png'); ?>" alt="" class="person wow fadeIn" data-wow-delay=".25s">
                <img src="<?php echo app_asset_url('img/my-vote/capitol-building.png'); ?>" alt="" class="person wow fadeIn" data-wow-delay="1.0s">
                <img src="<?php echo app_asset_url('img/my-vote/young-woman.png'); ?>" alt="" class="person wow fadeIn" data-wow-delay=".45s">
                <img src="<?php echo app_asset_url('img/my-vote/protester.png'); ?>" alt="" class="person wow fadeIn" data-wow-delay="1.1s">
                <img src="<?php echo app_asset_url('img/my-vote/young-man.png'); ?>" alt="" class="person wow fadeIn" data-wow-delay=".55s">
                <img src="<?php echo app_asset_url('img/my-vote/button.png'); ?>" alt="" class="person wow fadeIn" data-wow-delay="1.2s">
            </div> <!-- .infographic__people -->
        </main>
    </div> <!-- .container -->
</section> <!-- .my-vote -->
