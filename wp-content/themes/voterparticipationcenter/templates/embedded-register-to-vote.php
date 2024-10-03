<?php
/*
 * Template Name: Embedded / Register to Vote
 */
?>
<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

  <section class="vote-registration">
   <div class="container">
    <h1 class="vote-registration__headline"><?php the_field('page_headline'); ?></h1>

    <div class="vote-registration__content">
     <div class="content__header rich-text">
      <?php the_field('page_intro_text'); ?>
     </div>

     <div class="content__form">
      <div id="spinner"><img src="<?php echo app_asset_url('img/spinner.gif'); ?>" alt=""></div>
      <iframe src="https://register.rockthevote.com/registrants/new?partner=101<?php echo !empty($_GET['source']) ? '&amp;source=' . htmlentities($_GET['source']) : ''; ?>" width="100%" height="100%" frameborder="0" onload="document.getElementById('spinner').style.display='none';"></iframe>
     </div>
    </div>
   </div> <!-- .container -->
  </section> <!-- .vote-registration -->

  <?php get_template_part('partials/info-blocks'); ?>

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


<?php get_footer(); ?>