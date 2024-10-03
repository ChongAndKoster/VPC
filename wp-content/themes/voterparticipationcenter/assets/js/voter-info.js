import Vue from "vue";
var moment = require("moment");
var axios = require("axios");
var swal = require("sweetalert");
import Autocomplete from "vuejs-auto-complete";

if (
  document.getElementById("voter-info") ||
  document.getElementById("vote-by-mail")
) {
  var app = new Vue({
    el: document.getElementById("voter-info") ? "#voter-info" : "#vote-by-mail",
    data: {
      isLoading: false,
      loadingState: null,
      searchTerms: null,
      activeState: null,
      activeStateInfo: {},
      states: window.vpc.states || {},
      statesForSearch: window.vpc.statesForSearch || {},
    },
    computed: {
      stateSelected: function () {
        return null != this.activeState;
      },
    },
    components: {
      Autocomplete,
    },
    mounted: function () {
      var self = this;

      // Load state by URL hash
      if (window.location.hash) {
        var hash = window.location.hash;
        this.loadAndSetActiveState(hash.substring(1));
      }

      // Listen for the hash to change
      window.addEventListener(
        "hashchange",
        function () {
          var hash = window.location.hash;
          self.loadAndSetActiveState(hash.substring(1));
        },
        false
      );
    },
    watch: {
      activeState: function (newState, oldState) {
        this.selectState(newState);
      },
    },
    methods: {
      moment: function (date) {
        return moment(date);
      },
      loadAndSetActiveState: function (abbrev) {
        var self = this;
        self.isLoading = true;
        self.loadingState = abbrev;
        axios
          .get(ajaxurl + "?action=loadStateDetail&state=" + abbrev)
          .then(function (response) {
            if (response.data == 0) {
              swal("", "The requested state was not found.", "error");
              return;
            }
            self.setActiveState(response.data.abbrev, response.data);
          })
          .catch(function (error) {
            console.log(error);
            swal("", "An unknown error occurred.", "error");
          })
          .finally(function () {
            self.isLoading = false;
            self.loadingState = null;
          });
      },
      setActiveState(state, stateInfo) {
        this.activeState = state;
        this.activeStateInfo = stateInfo;
        window.history.pushState(null, null, "#" + state);
      },
      clearActiveState: function () {
        this.activeState = null;
        window.history.pushState(null, null, "#");
      },
      selectState: function (state) {
        if (state) {
          this.loadAndSetActiveState(state);
        } else {
          this.clearActiveState();
        }
      },
      getDaysDiff: function (date) {
        return moment(date)
          .startOf("day")
          .diff(moment().startOf("day"), "days");
      },
      getResourceUrl: function (resource) {
        if (resource.type == "file") {
          return resource.file.url;
        }
        return resource.link;
      },
      getResourceUrlTarget: function (resource) {
        return "_blank";
      },
      searchFromAutocomplete: function (input) {
        this.searchTerms = input.value;
        return this.search();
      },
      searchFromAutocompleteEnter: function (event) {
        this.searchTerms = event.target.value;
        return this.search();
      },
      search: function () {
        if (!this.searchTerms) {
          return false;
        }
        var self = this;
        self.isLoading = true;
        axios
          .get(ajaxurl + "?action=searchStates&q=" + this.searchTerms)
          .then(function (response) {
            if (response.data == 0) {
              swal("", "No results were found from your search.", "error");
              return;
            }
            self.setActiveState(response.data.abbrev, response.data);
          })
          .catch(function (error) {
            if (error.response.status == 404) {
              swal(
                "",
                "We couldn't find any results for \"" + self.searchTerms + '".',
                "error"
              );
            } else {
              swal("", "An unknown error occurred.", "error");
            }
            self.searchTerms = null;
          })
          .finally(function () {
            self.isLoading = false;
          });
      },
      stateIsLoading: function (abbrev) {
        return abbrev == this.loadingState;
      },
      stateHasVoteByMail: function (state) {
        return state.vote_by_mail && state.vote_by_mail.state_show_vote_by_mail;
      },
      /**
       * Vue.js transition hook
       */
      enter: function () {
        var top = 0;
        if (this.stateSelected) {
          top =
            jQuery(".voter-info-banner").offset().top +
            jQuery(".voter-info-banner").outerHeight() -
            115; // extra padding
        }

        jQuery("html, body").animate(
          {
            scrollTop: top,
          },
          "slow"
        );
      },
    },
  });
}
