<?php
/*
 * Template Name: Embedded / My Voter Info
 */
?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <div id="voter-info">
        <section class="voter-info-banner content-row flex-column">
            <iframe onload="onLoadHandler(this)" id="voterToolIframe" src="/my-voter-info/"></iframe>
        </section>
    </div> <!-- #voter-info -->

<?php endwhile;
endif; ?>

<script>
    const onLoadHandler = (iframe) => {

        try {
            // surgically remove its' contents
            const mainAppElmnt = iframe.contentWindow.document.querySelector(".app-main");
            mainAppElmnt.style.padding = "0px";
            const headerElmnt = iframe.contentWindow.document.querySelector(".app-header");
            headerElmnt.style.display = "none";
            const footerElmnt = iframe.contentWindow.document.querySelector(".app-footer");
            footerElmnt.style.display = "none";
            const infoBlocksElmnt = iframe.contentWindow.document.querySelector(".info-blocks");
            infoBlocksElmnt.style.display = "none";
            const pageSrcsElmnt = iframe.contentWindow.document.querySelector(".page-sources");
            pageSrcsElmnt.style.display = "none";

            // change color of bottom section elements
            const militaryInfoDiv = iframe.contentWindow.document.querySelector(".secondary__west");
            militaryInfoDiv.style.backgroundColor = "#013088";
            const militaryInfoHeadline = iframe.contentWindow.document.querySelector(".secondary__headline");
            militaryInfoHeadline.style.color = "#41EDF7";
            const militaryInfoButton = iframe.contentWindow.document.querySelector('a[href="#MIL"]');

            // remove class to override !important styles for button
            militaryInfoButton.classList.remove("btn--razz");
            militaryInfoButton.style.backgroundColor = "#41EDF7";
            militaryInfoButton.style.color = "#013088";

            // add onclick callback to remove class whenever it's clicked
            militaryInfoButton.onclick = () => {
                setTimeout(() => {
                    militaryInfoButton.classList.remove("btn--razz");
                }, 1000);

            }

            militaryInfoButton.style.backgroundColor = "#41EDF7";


            const militaryInfoParagraph = militaryInfoDiv.getElementsByTagName('p')[0];
            militaryInfoParagraph.style.color = "#FFFFFF";

            const overseasInfoDiv = iframe.contentWindow.document.querySelector(".secondary__east");
            overseasInfoDiv.style.backgroundColor = "#04C7A8";
            const overseasInfoHeadline = overseasInfoDiv.querySelector(".secondary__headline");
            overseasInfoHeadline.style.color = "#FFFFFF";
            const overseasInfoButton = iframe.contentWindow.document.querySelector('a[href="#ABS"]');
            overseasInfoButton.style.backgroundColor = "#013088";
            const overseasInfoParagraph = overseasInfoDiv.getElementsByTagName('p')[0];
            overseasInfoParagraph.style.color = "#013088";

            // change target attributes for links to /register-to-vote and /check-registration-status 
            // so they still open in iframe even with the <base> element added below
            const anchors = iframe.contentWindow.document.getElementsByTagName('a');

            Array.from(anchors).forEach((anchor) => {
                if (anchor.href === "<?php echo get_site_url(); ?>/register-to-vote/" || anchor.href === "<?php echo get_site_url(); ?>/check-registration-status/") {
                    anchor.target = "_self";
                }
                if (anchor.href === "<?php echo get_site_url(); ?>/register-to-vote/") {
                    anchor.href = "<?php echo get_site_url(); ?>/register-to-vote-cvi/"
                }
                if (anchor.href === "<?php echo get_site_url(); ?>/check-registration-status/") {
                    anchor.href = "<?php echo get_site_url(); ?>/check-registration-status-cvi/"
                }
            });

        } finally {
            // append iframe head with base element to make links open in new tab
            const base = iframe.contentWindow.document.createElement('base');
            base.target = '_blank';

            iframe.contentWindow.document.getElementsByTagName('head')[0].appendChild(base);

            iframe.style.visibility = "visible";

            // add onVisibilityChange handler to iframe dom to hide iframe when contents change
            iframe.contentWindow.document.onvisibilitychange = () => {
                if (iframe.contentWindow.document.visibilityState === "hidden") {
                    iframe.style.visibility = "hidden";
                } else {
                    iframe.style.visibility = "visible";
                }
            };
        }
    }
</script>

<script>
    window.vpc = window.vpc || {};
    window.vpc.states = <?php echo json_encode(app_get_states()); ?>;
    window.vpc.statesForSearch = <?php echo json_encode(app_get_states_for_search()); ?>;
    <?php wp_reset_postdata(); ?>
</script>
<?php get_footer(); ?>
