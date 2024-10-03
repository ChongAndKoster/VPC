<?php get_header(); ?>
    
    <div class="container">
        <h1 class="text-hide">I 404'd Today!</h1>
        
        <div class="content">
            <p>Oops! This page is missing. Sorry about that. But while you have you here, let’s make sure you’re not missing from the voters rolls.</p>
        </div>
        
        <h2 class="text-hide">Now GO Vote!</h2>
        
        <div class="buttons">
            <div class="btn-group-main btn-group-main--light-pink btn-group-main--2-btns wow fadeInUp">
                <a href="/register-to-vote" class="btn btn-main btn-main--arrow btn-main--gradient">
                    <span class="btn__icon d-lg-none"><i class="ico-pencil"></i></span> 
                    Register to Vote
                </a>
                <a href="/check-registration-status" class="btn btn-main btn-main--arrow">
                    <span class="btn__icon d-lg-none"><i class="ico-magnifying-glass"></i></span> 
                    Check My Registration
                </a>
            </div>
        </div>
    </div> <!-- .container -->
    
<?php get_footer(); ?>