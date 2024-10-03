<?php
/*
 * Template Name: Home / My Voter Info
 */
?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <div id="voter-info">
            <section class="voter-info-banner content-row content-row--bright-green flex-column">
                <div class="container mw-760px">
                    <div class="btn-group-main btn-group-main--bright-green btn-group-main--2-btns wow fadeInUp mw-960px">
                        <a href="/register-to-vote/" class="btn btn-main btn-main--arrow btn-main--gradient">
                            <span class="btn__icon d-lg-none"><i class="ico-pencil"></i></span>
                            Register to Vote
                        </a>
                        <?php /*
                        <a href="/early-voting-locations/" class="btn btn-main btn-main--arrow btn-main--blue-green-gradient">
                            <span class="btn__icon d-lg-none"><i class="ico-pencil"></i></span>
                            How to Vote Early
                        </a>
                        */ ?>
                        <a href="/check-registration-status/" class="btn btn-main btn-main--arrow">
                            <span class="btn__icon d-lg-none"><i class="ico-magnifying-glass"></i></span>
                            Check My Registration
                        </a>
                    </div>
                </div>

                <div class="container">
                    <h1 class="voter-info-banner__headline wow fadeInUp"><?php the_field('page_headline'); ?></h1>

                    <div class="voter-info-banner__text rich-text wow fadeInUp">
                        <?php the_field('page_intro_text'); ?>
                    </div>

                    <form method="get" class="voter-info-banner__form wow fadeInUp" @submit.prevent="search()">
                        <div class="form__select-wrap d-lg-none">
                            <label for="search" class="sr-only">Enter your state or territory</label>
                            <select v-model="activeState" id="search" class="form-control">
                                <option value=""></option>
                                <option v-for="state in statesForSearch" v-bind:value="state.id">{{ state.name }}</option>
                            </select>
                            <label for="search" class="select-wrap__overlay" v-show="!activeState">Select your state</label>
                        </div>

                        <div class="form__input-wrap d-none d-lg-block">
                            <label for="search" class="sr-only">Enter your state or territory</label>
                            <?php /*
                            <input type="search" v-model="searchTerms" id="search" placeholder="Enter your state or territory" value=""> 
                            */ ?>
                            <autocomplete :source="statesForSearch" id="search" placeholder="Enter your state or territory" @selected="searchFromAutocomplete" @enter="searchFromAutocomplete" @keyup.enter.native="searchFromAutocompleteEnter">
                            </autocomplete>
                        </div>

                        <?php
                        /*
                         * The submit button doesn't actually submit anything because 
                         * of the autocomplete, and can be (probably should be) removed...
                         */
                        ?>
                        <button type="button" class="d-none d-lg-block">
                            <svg v-if="isLoading" width="32" height="32" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path fill="#00FF9D" d="M296 48c0 22.091-17.909 40-40 40s-40-17.909-40-40 17.909-40 40-40 40 17.909 40 40zm-40 376c-22.091 0-40 17.909-40 40s17.909 40 40 40 40-17.909 40-40-17.909-40-40-40zm248-168c0-22.091-17.909-40-40-40s-40 17.909-40 40 17.909 40 40 40 40-17.909 40-40zm-416 0c0-22.091-17.909-40-40-40S8 233.909 8 256s17.909 40 40 40 40-17.909 40-40zm20.922-187.078c-22.091 0-40 17.909-40 40s17.909 40 40 40 40-17.909 40-40c0-22.092-17.909-40-40-40zm294.156 294.156c-22.091 0-40 17.909-40 40s17.909 40 40 40c22.092 0 40-17.909 40-40s-17.908-40-40-40zm-294.156 0c-22.091 0-40 17.909-40 40s17.909 40 40 40 40-17.909 40-40-17.909-40-40-40z"></path>
                            </svg>
                            <span v-if="! isLoading">Go</span>
                        </button>
                    </form>
                </div> <!-- .container -->
            </section>

            <div class="voter-info-states-wrap">
                <transition name="fade" mode="out-in" v-on:enter="enter">
                    <section class="voter-info-states" v-show="! stateSelected" key="voterInfoStates" v-cloak>

                        <div class="container">
                            <div class="voter-info-states__primary">
                                <h2 class="primary__headline">Select Your State <span class="d-none d-lg-inline">from the Map or List Below</span></h2>

                                <?php if ($map_id = get_field('us_map_id')) : ?>
                                    <div class="d-none d-lg-block mb-lg-5">
                                        <?php echo do_shortcode('[display-map id="' . $map_id . '"]'); ?>
                                    </div>
                                <?php endif; ?>

                                <div class="primary__states">

                                    <a v-bind:href="'#' + state.abbrev" class="state" v-bind:class="{ 'state--loading': stateIsLoading(state.abbrev) }" v-for="(state, index) in states" @click.prevent="loadAndSetActiveState(state.abbrev)">
                                        <h3 class="state__name">{{ state.name }}</h3>
                                        <div class="state__icon">
                                            <img v-bind:src="state.logo" alt="">
                                        </div>
                                        <!-- <div class="state__info" v-if="state.next_election_date && getDaysDiff(state.next_election_date) > 0">
                                            Next statewide election is in {{ getDaysDiff(state.next_election_date) }} days:<br>
                                            <time datetime="2019-10-09">{{ moment(state.next_election_date).format('MMM D') }}</time>
                                            <span class="btn btn-sm btn-primary" v-if="state.next_election_deadline && getDaysDiff(state.next_election_deadline) > 0">
                                                Register by {{ moment(state.next_election_deadline).format('MMMM D') }}
                                            </span>
                                        </div> .state__info commented post-election for spacing -->

                                        <!-- <div class="state__lower-bar" v-if="stateHasVoteByMail(state)">
                                            Vote by Mail
                                            <span class="lower-bar__icon"></span>
                                        </div> commented post-election  -->

                                        <div class="state__loading">
                                            <svg width="64" height="64" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                <path fill="#FFFFFF" d="M296 48c0 22.091-17.909 40-40 40s-40-17.909-40-40 17.909-40 40-40 40 17.909 40 40zm-40 376c-22.091 0-40 17.909-40 40s17.909 40 40 40 40-17.909 40-40-17.909-40-40-40zm248-168c0-22.091-17.909-40-40-40s-40 17.909-40 40 17.909 40 40 40 40-17.909 40-40zm-416 0c0-22.091-17.909-40-40-40S8 233.909 8 256s17.909 40 40 40 40-17.909 40-40zm20.922-187.078c-22.091 0-40 17.909-40 40s17.909 40 40 40 40-17.909 40-40c0-22.092-17.909-40-40-40zm294.156 294.156c-22.091 0-40 17.909-40 40s17.909 40 40 40c22.092 0 40-17.909 40-40s-17.908-40-40-40zm-294.156 0c-22.091 0-40 17.909-40 40s17.909 40 40 40 40-17.909 40-40-17.909-40-40-40z"></path>
                                            </svg>
                                        </div>
                                    </a>

                                    <span class="state placeholder"></span>
                                </div> <!-- .primary__states -->
                            </div> <!-- .voter-info-states__primary -->

                        </div> <!-- .container -->
                    </section> <!-- .voter-info-states -->
                </transition>

                <transition name="fade" mode="out-in" v-on:enter="enter">
                    <section class="voter-info-state-detail" v-show="stateSelected" key="voterInfoStateDetail" v-cloak>
                        <div class="container">
                            <button class="btn-back text-hide" @click="clearActiveState()">Pick Another State</button>

                            <h2 class="state-detail__headline">{{ activeStateInfo.name }}</h2>

                            <div class="state-detail__countdown" v-if="getDaysDiff(activeStateInfo.next_election_date) > 0">
                                <strong>{{ getDaysDiff(activeStateInfo.next_election_date) }} Days</strong> {{ activeStateInfo.countdown_label }}
                            </div>
                        </div> <!-- .container -->

                        <div class="state-detail__vote-by-mail" v-if="stateHasVoteByMail(activeStateInfo)">
                            <div class="container">
                                <h2 class="vote-by-mail__headline">Vote by Mail</h2>

                                <div v-html="activeStateInfo.vote_by_mail.state_vote_by_mail_content"></div>

                                <a v-if="activeStateInfo.vote_by_mail.state_vote_by_mail_button_url && activeStateInfo.vote_by_mail.state_vote_by_mail_button_text" v-bind:href="activeStateInfo.vote_by_mail.state_vote_by_mail_button_url" v-bind:target="activeStateInfo.vote_by_mail.state_vote_by_mail_button_url_target" class="btn btn--dark-blue mt-3">{{ activeStateInfo.vote_by_mail.state_vote_by_mail_button_text }}</a>

                                <div class="vote-by-mail__info-blocks">
                                    <div v-for="info in activeStateInfo.vote_by_mail.state_vote_by_mail_additional_information" class="block rich-text">
                                        <span class="block__label">{{ info.label }}</span>
                                        <div class="rich-text" v-html="info.content"></div>
                                    </div>
                                </div> <!-- .vote-by-mail__info-blocks -->

                            </div> <!-- .container -->
                        </div> <!-- .state-detail__vote-by-mail -->

                        <div class="state-detail__info" v-if="activeStateInfo.additional_information">
                            <div class="container">
                                <div class="info__icon" v-if="activeStateInfo.logo">
                                    <img v-bind:src="activeStateInfo.logo" alt="">
                                </div>

                                <h2 class="info__headline">{{ activeStateInfo.additional_info_headline }}</h2>

                                <div class="info__sections">

                                    <div class="section" v-for="section in activeStateInfo.additional_information_sections">
                                        <h3 class="section__headline" v-if="section.headline">{{ section.headline }}</h3>

                                        <div class="rich-text" v-html="section.content"></div>

                                        <div class="info__blocks">
                                            <div v-for="block in section.blocks" class="block rich-text">
                                                <span class="block__label">{{ block.label }}</span>
                                                <div class="rich-text" v-html="block.content"></div>
                                            </div>
                                        </div> <!-- .state-detail-info__blocks -->
                                    </div> <!-- .section -->

                                </div> <!-- .info__sections -->
                            </div> <!-- .container -->
                        </div> <!-- .state-detail__info -->

                        <div class="state-detail__deadlines" v-if="activeStateInfo.deadlines">
                            <div class="container">
                                <h3 class="deadlines__headline">{{ activeStateInfo.name }} Deadlines</h3>
                                <ul class="deadlines__list" v-bind:class="{ 'deadlines__list--center': activeStateInfo.deadlines.length < 3 }">
                                    <li v-for="deadline in activeStateInfo.deadlines" v-if="getDaysDiff(deadline.date) > 0">
                                        <time v-bind:datetime="deadline.date">{{ moment(deadline.date).format('MMMM') }} <b>{{ moment(deadline.date).format('D') }}</b></time>
                                        {{ deadline.label }}
                                    </li>
                                    <li class="placeholder"> </li>
                                </ul>
                            </div> <!-- .container -->
                        </div> <!-- .state-detail__deadlines -->

                        <div class="state-detail__resources" v-if="activeStateInfo.additional_resources">
                            <div class="container">
                                <h2 class="resources__headline">{{ activeStateInfo.additional_resources_headline }}</h2>

                                <ul class="resources__list">
                                    <li v-for="resource in activeStateInfo.additional_resources">
                                        <a v-bind:href="getResourceUrl(resource)" v-bind:target="getResourceUrlTarget(resource)" class="resource" v-bind:class="'resource--' + resource.type">
                                            <div class="resource__icon">
                                                <i v-bind:class="'ico-' + resource.type"></i>
                                            </div>
                                            <div class="resource__info">
                                                {{ resource.label }}
                                            </div>
                                        </a>
                                    </li>
                                    <li class="placeholder"> </li>
                                    <li class="placeholder"> </li>
                                </ul>
                            </div> <!-- .container -->
                        </div> <!-- .state-detail__resources -->
                    </section> <!-- .voter-info-state-detail -->
                </transition>

                <div class="voter-info-states-wrap__secondary">
                    <div class="secondary__west">
                        <div class="container">
                            <?php if ($voter_info_left_block = get_field('voter_info_left_block')) : ?>
                                <h2 class="secondary__headline"><?php echo $voter_info_left_block['headline']; ?></h2>
                                <p><?php echo nl2br($voter_info_left_block['text']); ?></p>
                                <a href="#MIL" class="btn btn--razz" v-bind:class="{ 'btn--loading': stateIsLoading('MIL') }" @click.prevent="loadAndSetActiveState('MIL')"><?php echo $voter_info_left_block['button_label']; ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="secondary__east">
                        <div class="container">
                            <?php if ($voter_info_right_block = get_field('voter_info_right_block')) : ?>
                                <h2 class="secondary__headline"><?php echo $voter_info_right_block['headline']; ?></h2>
                                <p><?php echo nl2br($voter_info_right_block['text']); ?></p>
                                <a href="#ABS" class="btn btn--dark-blue" v-bind:class="{ 'btn--loading': stateIsLoading('ABS') }" @click.prevent="loadAndSetActiveState('ABS')"><?php echo $voter_info_right_block['button_label']; ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div> <!-- .voter-info-states-wrap__secondary -->

            </div> <!-- .voter-info-states-wrap -->

            <?php get_template_part('partials/info-blocks'); ?>

        </div> <!-- #voter-info -->
<?php endwhile;
endif; ?>

<script>
    window.vpc = window.vpc || {};
    window.vpc.states = <?php echo json_encode(app_get_states()); ?>;
    window.vpc.statesForSearch = <?php echo json_encode(app_get_states_for_search()); ?>;
    <?php wp_reset_postdata(); ?>
</script>
<?php get_footer(); ?>