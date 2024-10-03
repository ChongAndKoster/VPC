<?php
// Testimonials
$testimonial_pages = get_field('g_testimonials_pages', 'options');
if ($testimonial_pages && in_array(get_the_ID(), $testimonial_pages)) :
?>
    <?php get_template_part('partials/testimonials'); ?>
<?php endif; ?>


<?php
// Page Sources
if ($page_sources = get_field('page_sources')) :
?>
    <div class="page-sources">
        <div class="container rich-text">
            <?php echo $page_sources; ?>
        </div>
    </div>
<?php endif; ?>

</main> <!-- .app-main -->

<footer class="app-footer">
    <div class="app-footer__upper">
        <div class="container">
            <a href="/" class="upper__logo text-hide">Voter Participation Center</a>

            <nav class="upper__nav">
                <?php wp_nav_menu(array(
                    'theme_location' => 'footer-nav',
                    'container'      => false,
                    'menu_class'     => 'nav',
                )); ?>
            </nav>

            <a href="/register-to-vote" class="btn btn-primary upper__btn-register">Register to Vote</a>

            <ul class="upper__social">
                <?php if ($twitter_url = get_field('g_twitter_url', 'options')) : ?>
                    <li><a href="<?php echo $twitter_url; ?>" target="_blank" title="Twitter"><i class="ico-twitter" aria-hidden="true"></i></a></li>
                <?php endif; ?>
                <?php if ($facebook_url = get_field('g_facebook_url', 'options')) : ?>
                    <li><a href="<?php echo $facebook_url; ?>" target="_blank" title="Facebook"><i class="ico-facebook" aria-hidden="true"></i></a></li>
                <?php endif; ?>
                <?php if ($youtube_url = get_field('g_youtube_url', 'options')) : ?>
                    <li><a href="<?php echo $youtube_url; ?>" target="_blank" title="YouTube"><i class="ico-youtube" aria-hidden="true"></i></a></li>
                <?php endif; ?>
                <?php if ($instagram_url = get_field('g_instagram_url', 'options')) : ?>
                    <li><a href="<?php echo $instagram_url; ?>" target="_blank" title="Instagram"><i class="ico-instagram" aria-hidden="true"></i></a></li>
                <?php endif; ?>
            </ul>
        </div> <!-- .container -->
    </div> <!-- .app-footer__upper -->

    <div class="app-footer__lower">
        <div class="container">
            <div class="lower__info">
                <div class="info__west">
                    &copy; 2019-<?php echo date('y'); ?> Voter Participation Center <span class="mx-2">|</span>

                    <ul class="nav">
                        <li class="nav-item"><a href="/privacy-policy/" class="nav-link">Privacy Policy</a></li>
                        <li class="nav-item"><a href="terms-of-use/" class="nav-link">Terms of Use</a></li>
                    </ul>
                </div>
                <address class="info__east">
                    <?php //echo get_field('g_address', 'options'); 
                    ?>
                </address>
            </div> <!-- .lower__info -->
        </div> <!-- .container -->
    </div> <!-- .app-footer__lower -->
</footer> <!-- .app-footer -->
</div> <!-- #app-panel -->

<?php wp_footer(); ?>

</body>

</html>