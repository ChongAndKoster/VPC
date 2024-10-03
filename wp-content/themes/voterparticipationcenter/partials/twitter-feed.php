<?php

// {@see https://codex.wordpress.org/HTTP_API}
$response = wp_remote_get('https://api.twitter.com/2/users/357674108/tweets?tweet.fields=created_at,attachments,entities&expansions=author_id,attachments.media_keys&user.fields=created_at,username,url,entities&max_results=15&exclude=retweets,replies&media.fields=duration_ms,height,media_key,preview_image_url,public_metrics,type,url,width', array(
    'headers' => array(
        'Authorization' => 'Bearer AAAAAAAAAAAAAAAAAAAAAHGRRgEAAAAASTmF4a2MumMzqXbgymfdlikobyE%3DEQ1BfD0DA7yhjc0aBxvfpW0Y3rrYPq4gwAj32fumVRJ7cvwVGG',
        'Cookie' => 'personalization_id="v1_kdP/jrv0LkYsM21Xwr8JJA=="; guest_id=v1%3A162620137239436469',
    ),
));

if (!is_wp_error($response)) {
    // The request went through successfully, check the response code against
    // what we're expecting
    if (200 == wp_remote_retrieve_response_code($response)) {
        // Do something with the response
        $body = wp_remote_retrieve_body($response);
        $headers = wp_remote_retrieve_headers($response);
    } else {
        // The response code was not what we were expecting, record the message
        $error_message = wp_remote_retrieve_response_message($response);
    }
} else {
    // There was an error making the request
    $error_message = $response->get_error_message();
}

?>
<div>
    <?php
    if (isset($body)) :
        $response = json_decode($body);
        // echo json_encode($response);
        $data = $response->data;
        $user = $response->includes->users[0];
        $media = $response->includes->media;
    // echo $media;
    // echo json_encode($media);
    // echo json_encode($user);
    // echo json_encode($media);
    // echo $data;
    // echo $body->data;
    // echo $body["data"];
    endif;
    ?>
    <section class="twitter-row">
        <h2><?php the_field('twitter_feed_headline'); ?></h2>
        <!-- Slider main container -->
        <div class="swiper-container">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
                <!-- Slides -->
                <?php
                foreach ($data as $tweet) {
                    $date = date_create($tweet->created_at);
                    $formattedDate = date_format($date, "M j");
                    if (isset($tweet->attachments)) {
                        $mediaKey = $tweet->attachments->media_keys[0];

                        // commented out because it was causing an error
                        // function filterSocialMedia($var)
                        // {
                        //     return $var->media_key == $mediaKey;
                        // }

                        $mediaLocation = array_filter($media, function ($v) use ($mediaKey) {
                            return $v->media_key == $mediaKey;
                        });

                        $mediaLocationValue = array_values($mediaLocation);
                        $mediaURL = $mediaLocationValue[0]->url;

                        if ($mediaURL == null) {
                            $mediaURL = $mediaLocationValue[0]->preview_image_url;
                        }

                        // const filteredArray = media.filter((element) => {
                        //     return element.media_key === media_key;
                        // });

                        echo "<div class='swiper-slide'>
                        <a href='http://twitter.com/$tweet->author_id/status/$tweet->id' class='card__link' target='_blank' rel='noreferer noopener'>
                        <div class='card'>
                        <img class='card__image' src='$mediaURL' />
                            <div class='card__text'>
                            <p class='card__headline'><span class='account'>@VoterCenter</span> | <span class='time'>$formattedDate</span></p>
                            <p class='card__description'>$tweet->text</p>
                            </div>
                        </div>
                        </a>
                        </div>";
                    } else {
                        echo "<div class='swiper-slide'>
                        <a href='http://twitter.com/$tweet->author_id/status/$tweet->id' class='card__link' target='_blank' rel='noreferer noopener'>
                        <div class='card'>
                          <div class='card__text--no-image'>
                            <p class='card__headline'><span class='account'>@VoterCenter</span> | <span class='time'>$formattedDate</span></p>
                            <p class='card__description'>$tweet->text</p>
                          </div>
                        </div>
                        </a>
                      </div>";
                    }
                }
                ?>
            </div>
            <!-- If we need navigation buttons -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </section>
</div>

<script type="module">
    import Swiper from 'https://unpkg.com/swiper/swiper-bundle.esm.browser.min.js';

    const swiper = new Swiper('.swiper-container', {
        // Default parameters
        slidesPerView: 1,
        preventClicks: false,
        allowTouchMove: false,
        // Responsive breakpoints
        breakpoints: {
            // when window width is >= 480px
            768: {
                slidesPerView: 2,
            },
            1080: {
                slidesPerView: 3,
            },
            // when window width is >= 640px
            1440: {
                slidesPerView: 4,
            }
        },
        // Navigation arrows
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
</script>