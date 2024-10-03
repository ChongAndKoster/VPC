<?php
/*
 * Template Name: Embedded / My Voter Info
 */
?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

        <div id="voter-info">
            <section class="voter-info-banner content-row flex-column">
                <iframe onload="onLoadHandler(this)" id="voterToolIframe"></iframe>
            </section>
        </div> <!-- #voter-info -->

        <script>
            window.addEventListener('message', function(event) {
                if (
                    event.origin.startsWith('https://www.voterparticipation.org') ||
                    event.origin.startsWith('http://voterparticipation.test')
                ) {
                    if (event.data.selectedState) {
                        window.location.hash = event.data.selectedState;
                    } else {
                        window.history.pushState(null, null, "#");
                        parent.postMessage({
                            selectedState: ''
                        }, 'https://www.centerforvoterinformation.org');
                    }
                }
            });

            // On load handler for the iframe.
            const onLoadHandler = (iframe) => {

                // Set the URL hash to the state abbreviation on change.
                iframe.contentWindow.addEventListener('hashchange', function() {
                    window.location.hash = iframe.contentWindow.location.hash;

                    // Send the selected state to the parent.
                    parent.postMessage({
                        selectedState: window.location.hash.replace('#', '')
                    }, 'https://www.centerforvoterinformation.org');
                });

                try {
                    // surgically remove its' contents
                    const mainAppElmnt = iframe.contentWindow.document.querySelector(".app-main");
                    if (mainAppElmnt) mainAppElmnt.style.padding = "0px";
                    const headerElmnt = iframe.contentWindow.document.querySelector(".app-header");
                    if (headerElmnt) headerElmnt.style.display = "none";
                    const footerElmnt = iframe.contentWindow.document.querySelector(".app-footer");
                    if (footerElmnt) footerElmnt.style.display = "none";
                    const infoBlocksElmnt = iframe.contentWindow.document.querySelector(".info-blocks");
                    if (infoBlocksElmnt) infoBlocksElmnt.style.display = "none";
                    const pageSrcsElmnt = iframe.contentWindow.document.querySelector(".page-sources");
                    if (pageSrcsElmnt) pageSrcsElmnt.style.display = "none";
                    const gotMailModal = iframe.contentWindow.document.querySelector('.modal.modal--got-mail');
                    if (gotMailModal) {
                        gotMailModal.style.display = 'none';
                        iframe.contentWindow.document.querySelector('.modal-backdrop').style.display = 'none';
                    }

                    // change color of bottom section elements
                    const militaryInfoDiv = iframe.contentWindow.document.querySelector(".secondary__west");
                    if (militaryInfoDiv) militaryInfoDiv.style.backgroundColor = "#013088";
                    const militaryInfoHeadline = iframe.contentWindow.document.querySelector(".secondary__headline");
                    if (militaryInfoHeadline) militaryInfoHeadline.style.color = "#41EDF7";
                    const militaryInfoButton = iframe.contentWindow.document.querySelector('a[href="#MIL"]');

                    // remove class to override !important styles for button
                    if (militaryInfoButton) {
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
                    }

                    if (militaryInfoDiv) {
                        let militaryInfoParagraph = militaryInfoDiv.getElementsByTagName('p');
                        if (militaryInfoParagraph) {
                            militaryInfoParagraph = militaryInfoParagraph[0];
                            militaryInfoParagraph.style.color = "#FFFFFF";
                        }
                    }

                    const overseasInfoDiv = iframe.contentWindow.document.querySelector(".secondary__east");
                    if (overseasInfoDiv) {
                        overseasInfoDiv.style.backgroundColor = "#04C7A8";

                        const overseasInfoHeadline = overseasInfoDiv.querySelector(".secondary__headline");
                        if (overseasInfoHeadline) overseasInfoHeadline.style.color = "#FFFFFF";

                        const overseasInfoParagraph = overseasInfoDiv.getElementsByTagName('p')[0];
                        if (overseasInfoParagraph) overseasInfoParagraph.style.color = "#013088";
                    }

                    const overseasInfoButton = iframe.contentWindow.document.querySelector('a[href="#ABS"]');
                    if (overseasInfoButton) overseasInfoButton.style.backgroundColor = "#013088";

                    // change target attributes for links to /register-to-vote and /check-registration-status 
                    // so they still open in iframe even with the <base> element added below
                    const anchors = iframe.contentWindow.document.getElementsByTagName('a');
                    if (anchors) {
                        Array.from(anchors).forEach((anchor) => {
                            if (anchor.href === "<?php echo get_site_url(); ?>/register-to-vote/" || anchor.href === "<?php echo get_site_url(); ?>/check-registration-status/") {
                                anchor.target = "_self";
                            }
                        });
                    }

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

<?php endwhile;
endif; ?>

<script>
    const iframeTag = document.getElementById('voterToolIframe');

    // Set the iframe source on load, appending the state 
    // abbreviation hash if it exists.
    const iframeSrc = '/my-voter-info/';

    const stateAbbr = location.hash;
    if (stateAbbr) {
        iframeSrc += stateAbbr;
    }
    iframeTag.setAttribute('src', iframeSrc);


    window.vpc = window.vpc || {};
    window.vpc.states = <?php echo json_encode(app_get_states()); ?>;
    window.vpc.statesForSearch = <?php echo json_encode(app_get_states_for_search()); ?>;
    <?php wp_reset_postdata(); ?>
</script>

<?php get_footer(); ?>