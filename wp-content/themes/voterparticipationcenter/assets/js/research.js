import Vue from 'vue';

if (document.getElementById('research')) {
  
  // Scroll document to top of tabs on tab click
  jQuery(function() {
    jQuery('.nav-tabs a.tab').on('click', function() {
      var extraPadding = 45;
      jQuery('html, body').animate({ 
        scrollTop: jQuery('.nav-tabs').position().top - jQuery('.app-header').height() - extraPadding
      }, 'slow');
    });
  });
  
  // Vue
  var app = new Vue({
    el: '#research',
    data: {
      activeChartModal: null,
      activeElectorateLineGraph: 'presidential'
    },
    methods: {
      activateChartModal: function(key) {
        this.activeChartModal = key;

        jQuery('html, body').animate({ 
          scrollTop: jQuery('.tab-content').position().top + jQuery('.app-header').height()
        }, 'slow');
      },
      closeChartModals: function() {
        this.activeChartModal = null;
      },
      setActiveElectorateLineGraph: function(key) {
        this.activeElectorateLineGraph = key;
      }
    }
  })
}
