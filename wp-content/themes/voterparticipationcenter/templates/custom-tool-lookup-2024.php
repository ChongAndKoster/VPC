<?php
/*
 * Template Name: Custom Tool - Lookup (2024)
 */
// Early Voting
?>
<?php 
    get_header();
    $page_banner_type      = get_field('page_banner_type');
    $page_background_color = get_field('page_background_color');
    $google_api_key        = 'AIzaSyDnk517m-q27EbCoG01RukXKq8RDA_a5mM';
    $ballotpedia_api_key   = 'p91FW0J23Y44lQwxfwnuFT50geO1hdZ7bBtZlx16';
    $election_id           = 9000; // https://www.votinginfoproject.org/election-coverage
    
    if (have_posts()) : while (have_posts()) : the_post(); ?>

        <style>
            .map {
                width: 100%;
                height: 800px;
                background-color: grey;
            }
            .map-label {
                color: #fff;
                font-weight: bold;
                width: 60px;
            }
            #ev-lookup-tool {
                text-align: center;
            }
            .btn-small {
                margin: 10px 0;
                font-size: 12px;
                display: inline-block;
                padding: 5px 15px;
                font-weight: bold;
                border-radius: 20px;
                background: #9013fe;
                color: #FFFFFF;
            }
            .btn-small:hover {
                color: #FFF;
                background: #6801c4;
            }
            #address {
                float: left;
                margin: 0;
                border-radius: 33px;
                border: 2px solid #FFF;
                background: none;
                width: calc(100% - 130px);
                color: #FFF;    
                padding: .85rem 2rem;
                text-align: left;
                opacity: 1;
                transition: opacity 200ms ease;
            }
            #address::placeholder,
            #address::-webkit-input-placeholder {
                color: #FFF;
            }
            .loading #address {
                opacity: .5;
            }
            .address-submit {
                float: left;
                margin-left: 10px;
                border-radius: 33px;
                border: 2px solid #001e57;
                background: #001e57;
                width: 120px;
                color: #00ff9d;    
                padding: .85rem 0;
                text-align: center;
            }
            .loading .address-submit {
                color: #001e57;
                text-indent: -999em;
                background: url('/wp-content/themes/voterparticipationcenter/assets/img/button-spinner.gif') center center no-repeat #001e57;
            }


            /* Cobranding Footer Logo Override */
            .app-footer .app-footer__upper .upper__logo {
                height: 96px;
                background-image: url('/wp-content/themes/voterparticipationcenter/assets/img/vpc-cvi-cobrand.png');
            }

            @media (max-width: 993px) {
                #address {
                    float: none;
                    width: 100%;
                }
                .address-submit {
                    float: none;
                    width: 140px;
                    margin-left: 0;
                    margin-top: 15px;
                }                
            }

            table {
                margin-bottom: 2rem;
                font-size: .8em;
                width: 100%;
            }
            table th {
                border-bottom: 2px solid #001e57;
                padding: 10px;
                width: 33%;
                line-height: normal;
            }
            table tr:nth-child(even){
                background: #FAFAFA;
            }
            table td {
                padding: 10px;
                width: 33%;
                line-height: normal;
                vertical-align: top;
                border-bottom: 1px solid #a2b1b0;
            }
            table th.counter-cell,
            table td.counter-cell {
                padding: 10px 0 10px 5px;
                width: 10px;
            }

            strong.counter {
                display: inline-block;
                background: #FFF;
                color: #000000;
                width: 24px;
                height: 24px;
                line-height: 24px;
                text-align: center;
                font-size: 10px;
                border-radius: 50%;
                background: #FD2F6A;
            }
        </style>

        <script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=<?php echo $google_api_key; ?>"></script>
        <script src="https://unpkg.com/@googlemaps/markerclustererplus/dist/index.min.js"></script>
        
        <script>
            var state_election_ids = {
                'AK': 5043,
                'AL': 6166,
                'AZ': 4653,
                'AR': 4439,
                'CA': 4216,
                'CO': 4887,
                'CT': 4391,
                'DE': 4962,
                'DC': 5802,
                'FL': 4534,
                'GA': 4367,
                'HI': 5664,
                'ID': 4817,
                'IL': 5366,
                'IN': 5263,
                'IA': 5234,
                'KS': 4559,
                'KY': 5298,
                'LA': 4718,
                'ME': 5502,
                'MD': 5396,
                'MA': 4562,
                'MI': 4393,
                'MN': 4311,
                'MS': 4225,
                'MO': 4870,
                'MT': 4228,
                'NE': 5243,
                'NV': 5215,
                'NH': 5182,
                'NJ': 4249,
                'NM': 4343,
                'NY': 4407,
                'NC': 4210,
                'ND': 5335,
                'OH': 4306,
                'OK': 4273,
                'OR': 6998,
                'PA': 4259,
                'RI': 5017,
                'SC': 4693,
                'SD': 4472,
                'TN': 4879,
                'TX': 4764,
                'UT': 6923,
                'VT': 4449,
                'VA': 4333,
                'WA': 4282,
                'WV': 5861,
                'WI': 4706,
                'WY': 4802
            }

            // Map
            var map,
                loc,
                rightNow = new Date(),
                geoDelay = 500,
                marker = [],
                markers = [],
                uniqueMarkers = [],
                searchedCounty = '';
            var bounds = null;
            var markerCluster = [];            

            // Ballot Tracking URLS
            var ballotTrack = [];            
            ballotTrack['AL'] = 'We do not have any information for this location at this time. Please visit the <a href="https://myinfo.alabamavotes.gov/voterview" target="_blank">Secretary of State website</a> to find information on how to vote.';
            ballotTrack['AK'] = 'We do not have information for this location at this time. Please try again later, or visit the <a href="https://myvoterinformation.alaska.gov/" target="_blank">Secretary of State website</a> for information on how to vote.';
            ballotTrack['AZ'] = 'We do not have any information for this location at this time. Please try again later, or visit your <a href="https://azsos.gov/elections/about-elections/county-election-officials-contact-information" target="_blank">county website</a> to find your early voting location.';
            ballotTrack['AR'] = 'We do not have any information for this location at this time. Please visit the <a href="https://www.sos.arkansas.gov/elections" target="_blank">Secretary of State website</a> for information on how to vote.';
            ballotTrack['CA'] = 'We do not have any information for this location at this time. Please visit the <a href="https://www.sos.ca.gov/elections/voting-info/ways-vote" target="_blank">Secretary of State website</a> to find information on how to vote.';
            ballotTrack['CO'] = 'We do not have any information for this location at this time. Please visit the <a href="https://www.sos.state.co.us/pubs/elections/main.html?menuheaders=5" target="_blank">Secretary of State website</a> for more information on how to vote.';
            ballotTrack['CT'] = 'We do not have any information for this location at this time. Please visit the <a href="https://portal.ct.gov/SOTS/Common-Elements/V5-Template---Redesign/Elections--Voting--Home-Page" target="_blank">Secretary of State website</a> to find information on how to vote.';
            ballotTrack['DE'] = 'We do not have any information for this location at this time. Please visit the <a href="https://elections.delaware.gov/" target="_blank">Secretary of State website</a> for information on how to vote.';
            ballotTrack['DC'] = 'We do not have any information for this location at this time. Please try again later, or visit the <a href="https://dcboe.org/voters/find-out-where-to-vote/vote-center-locator-tool" target="_blank">Board of Elections website</a> for information on where and how to vote.';
            ballotTrack['FL'] = 'We do not have any information for this location at this time. Please try again later, or visit the <a href="https://dos.fl.gov/elections/for-voters/voting/early-voting-and-secure-ballot-intake-stations/" target="_blank">Secretary of State website</a> to find information on where and how to vote.';
            ballotTrack['GA'] = 'We do not have any information for this location at this time. Please try again later, or visit the <a href="https://mvp.sos.ga.gov/s/" target="_blank">Georgia My Voter Page</a> for information on how to vote.';
            ballotTrack['HI'] = 'We do not have any information for this location at this time. Please visit the <a href="https://elections.hawaii.gov/voter-service-centers-and-places-of-deposit/" target="_blank">Secretary of State website</a> for information on where and how to vote.';
            ballotTrack['ID'] = 'We do not have any information for this location at this time. Please visit the <a href="https://voteidaho.gov/idaho-general-election/" target="_blank">Secretary of State website</a> for information on where and how to vote.';
            ballotTrack['IL'] = 'We do not have any information for this location at this time. Please visit the <a href="https://elections.il.gov/votingandregistrationsystems/EarlyVotingLocationsSearch.aspx" target="_blank">Secretary of State website</a> for information on where and how to vote.';
            ballotTrack['IN'] = 'We do not have any information for this location at this time. Please visit the <a href="https://www.in.gov/sos/elections/voter-information/ways-to-vote/" target="_blank">Secretary of State website</a> for information on how to vote.';
            ballotTrack['IA'] = 'We do not have any information for this location at this time. Please visit the <a href="https://sos.iowa.gov/elections/voterinformation/index.html" target="_blank">Secretary of State website</a> for information on how to vote.';
            ballotTrack['KS'] = 'We do not have any information for this location at this time. Please try again later, or visit the <a href="https://sos.ks.gov/elections/important-election-information.html" target="_blank">Secretary of State website</a> for information on how to vote.';
            ballotTrack['KY'] = 'We do not have any information for this location at this time. Please visit the <a href="https://elect.ky.gov/Pages/default.aspx" target="_blank">Secretary of State website</a> for information on how to vote.';
            ballotTrack['LA'] = 'We do not have any information for this location at this time. Please visit the <a href="https://www.sos.la.gov/ElectionsAndVoting/Vote/VoteEarly/Pages/default.aspx" target="_blank">Secretary of State website</a> for information on how to vote.';
            ballotTrack['ME'] = 'We do not have any information for this location at this time. Please try again later, or visit the <a href="https://www.maine.gov/portal/government/edemocracy/voter_lookup.php" target="_blank">Secretary of State website</a> for information on how to vote.';
            ballotTrack['MD'] = 'We do not have any information for this location at this time. Please try again later, or visit the <a href="https://elections.maryland.gov/elections/2024/2024%20Early%20Voting%20Centers%20v1.pdf" target="_blank">State Board of Elections website</a> for information on where and how to vote.';
            ballotTrack['MA'] = 'We do not have any information for this location at this time. Please visit the <a href="https://www.sec.state.ma.us/ele/" target="_blank">Secretary of State website</a> for information on how to vote.';
            ballotTrack['MI'] = 'We do not have any information for this location at this time. Please try again later, or visit the <a href="https://mvic.sos.state.mi.us/Voter/Index#early-voting-search-section" target="_blank">Michigan Voter Information Center</a> for information on where and how to vote.';
            ballotTrack['MN'] = 'We do not have any information for this location at this time. Please try again later, or visit the <a href="https://www.sos.state.mn.us/elections-voting/other-ways-to-vote/voting-locations-before-election-day/" target="_blank">Secretary of State website</a> to find information on where and how to vote.';
            ballotTrack['MS'] = 'We do not have any information for this location at this time. Please visit the <a href="https://www.sos.ms.gov/elections-voting" target="_blank">Secretary of State website</a> to find information on how to vote.';
            ballotTrack['MO'] = 'We do not have any information for this location at this time. Please visit the <a href="https://www.sos.mo.gov/elections" target="_blank">Secretary of State website</a> for information on how to vote.';
            ballotTrack['MT'] = 'We do not have any information for this location at this time. Please visit the <a href="https://prodvoterportal.mt.gov/WhereToVote.aspx" target="_blank">Secretary of State website</a> for information on how to vote.';
            ballotTrack['NE'] = 'We do not have any information for this location at this time. Please try again later, or visit the <a href="https://www.votercheck.necvr.ne.gov/VoterView/" target="_blank">Secretary of State website</a> to find information on where and how to vote.';
            ballotTrack['NV'] = 'We do not have any information for this location at this time. Please try again later, or visit the <a href="https://www.nvsos.gov/votersearch/index.aspx" target="_blank">Secretary of State website</a> to find information on where and how to vote.';
            ballotTrack['NH'] = 'We do not have any information for this location at this time. Please visit the <a href="https://www.sos.nh.gov/elections/voters" target="_blank">Secretary of State website</a> to find information on how to vote.';
            ballotTrack['NJ'] = 'We do not have any information for this location at this time. Please try again later or, visit the <a href="https://www.state.nj.us/state/elections/vote.shtml" target="_blank">Secretary of State website</a> for information on where and how to vote.';
            ballotTrack['NM'] = 'We do not have any information for this location at this time. Please try again later, or visit the <a href="https://voterportal.servis.sos.state.nm.us/WhereToVoteAddress.aspx" target="_blank">Voter Information Portal</a> for information on where and how to vote.';
            ballotTrack['NY'] = 'We do not have any information for this location at this time. Please try again later, or visit the <a href="https://www.elections.ny.gov/" target="_blank">Board of Elections website</a> for information on how to vote.';
            ballotTrack['NC'] = 'We do not have information for this location at this time. Please try again later, or visit the <a href="https://vt.ncsbe.gov/evsite/" target="_blank">Secretary of State website</a> for information on where and how to vote.';
            ballotTrack['ND'] = 'We do not have information for this location at this time. Please visit the <a href="https://vip.sos.nd.gov/WhereToVote.aspx?tab=AddressandVotingTimes" target="_blank">Secretary of State website</a> for more information on how to vote.';
            ballotTrack['OH'] = 'We do not have information for this location at this time. Please try again later, or visit the <a href="https://www.ohiosos.gov/elections/voters/toolkit/early-voting/" target="_blank">Secretary of State website</a> for information on where and how to vote.';
            ballotTrack['OK'] = 'We do not have any information for this location at this time. Please visit the <a href="https://okvoterportal.okelections.gov/" target="_blank">Oklahoma Voter Portal</a> for information on where and how to vote.';
            ballotTrack['OR'] = 'We do not have any information for this location at this time. Please visit the <a href="https://sos.oregon.gov/voting-elections/Pages/default.aspx" target="_blank">Secretary of State website</a> for more information on how to vote.';
            ballotTrack['PA'] = 'We do not have any information for this location at this time. Please try again later, or visit the <a href="https://www.pavoterservices.pa.gov/Pages/SurePortalHome.aspx" target="_blank">Secretary of State website</a> for information on how to vote.';
            ballotTrack['RI'] = 'We do not have any information for this location at this time. Please visit the <a href="https://vote.sos.ri.gov/Home/PollingPlaces?ActiveFlag=2" target="_blank">Secretary of State website</a> for information on where and how to vote.';
            ballotTrack['SC'] = 'We do not have any information for this location at this time. Please visit the <a href="https://scvotes.gov/voters/early-voting/" target="_blank">Secretary of State website</a> for information on how to vote.';
            ballotTrack['SD'] = 'We do not have any information for this location at this time. Please visit the <a href="https://vip.sdsos.gov/viplogin.aspx" target="_blank">Secretary of State website</a> for information on where and how to vote.';
            ballotTrack['TN'] = 'We do not have information for this location at this time. Please try again later, or visit the <a href="https://web.go-vote-tn.elections.tn.gov/" target="_blank">Secretary of State website</a> for information on how to vote.';
            ballotTrack['TX'] = 'We do not have any information for this location at this time. Please visit the <a href="https://www.sos.state.tx.us/elections/" target="_blank">Secretary of State website</a> for information on how to vote.';
            ballotTrack['UT'] = 'We do not have any information for this location at this time. Please visit the <a href="https://votesearch.utah.gov/voter-search/search/search-by-address/how-and-where-can-i-vote" target="_blank">Secretary of State website</a> for more information on how to vote.';
            ballotTrack['VT'] = 'We do not have any information for this location at this time. Please visit the <a href="https://mvp.vermont.gov/" target="_blank">Secretary of State website</a> for information on where and how to vote.';
            ballotTrack['VA'] = 'We do not have information for this location at this time. Please try again later, or visit the <a href="https://www.elections.virginia.gov/casting-a-ballot/early-voting-office-locations/" target="_blank">Secretary of State website</a> for more information on how to vote.';
            ballotTrack['WA'] = 'We do not have any information for this location at this time. Please visit the <a href="https://voter.votewa.gov/portal2023/login.aspx" target="_blank">Secretary of State website</a> for more information on how to vote.';
            ballotTrack['WV'] = 'We do not have any information for this location at this time. Please visit the <a href="https://sos.wv.gov/elections/Pages/GoVoteWV.aspx" target="_blank">Secretary of State website</a> for information on where and how to vote.';
            ballotTrack['WI'] = 'We do not have information for this location at this time. Please try again later, or visit the <a href="https://myvote.wi.gov/en-us/Vote-Absentee-In-Person" target="_blank">Secretary of State website</a> for information on where and how to vote.';
            ballotTrack['WY'] = 'We do not have any information for this location at this time. Please visit the <a href="https://myelectionday.sos.wyo.gov/WYVOTES/Pages/VOSearch.aspx" target="_blank">Secretary of State website</a> for information on where and how to vote.';

            // No early voting
            var noEarlyVoting = [];
            // ballotTrack['CO'] = 'Colorado does not offer in-person early voting. Eligible voters will be mailed ballots. Please visit the <a href="https://www.sos.state.co.us/pubs/elections/main.html?menuheaders=5" target="_blank">Secretary of State website</a> for more information.';
            // ballotTrack['AL'] = 'Alabama does not offer early voting. Please visit the <a href="https://myinfo.alabamavotes.gov/voterview" target="_blank">Secretary of State website</a> to find information on how to vote.';
            // ballotTrack['NH'] = 'New Hampshire does not offer early voting. Please visit the <a href="https://www.sos.nh.gov/elections/voters" target="_blank">Secretary of State website</a> to find information on how to vote.';
            // ballotTrack['MS'] = 'Mississippi does not offer early voting. Please visit the <a href="https://www.sos.ms.gov/elections-voting" target="_blank">Secretary of State website</a> to find information on how to vote.';
            // ballotTrack['OR'] = 'Oregon does not offer in-person early voting. All eligible voters in Oregon will be mailed ballots. Please visit the <a href="https://sos.oregon.gov/voting-elections/Pages/default.aspx" target="_blank">Secretary of State website</a> for more information on how to vote.';
            // ballotTrack['UT'] = 'Utah does not offer in-person early voting. Eligible voters will be mailed ballots. Please visit the <a href="https://votesearch.utah.gov/voter-search/search/search-by-address/how-and-where-can-i-vote" target="_blank">Secretary of State website</a> for more information.';
            // ballotTrack['WA'] = 'Washington does not offer in-person early voting. Eligible voters will be mailed ballots. Please visit the <a href="https://voter.votewa.gov/portal2023/login.aspx" target="_blank">Secretary of State website</a> for more information.';

            /**
             * Address Autocomplete
             */
            function addressAutoComplete() {
                var input = document.getElementById('address');
                new google.maps.places.Autocomplete(input,{
                    fields: ["name", "geometry.location", "place_id", "formatted_address"]
                });
            }
            google.maps.event.addDomListener(window, 'load', addressAutoComplete);

            // Helpful date formatter
            function dateToHours(time) {
                var date = new Date(time);
                return date.toLocaleTimeString(navigator.language, {
                    hour: 'numeric',
                    minute: '2-digit'
                });
            }

            function formatAMPM(date) {
                var hours = date.getHours();
                var minutes = date.getMinutes();
                var ampm = hours >= 12 ? 'pm' : 'am';
                hours = hours % 12;
                hours = hours ? hours : 12; // the hour '0' should be '12'
                minutes = minutes < 10 ? '0'+minutes : minutes;
                var strTime = hours + ':' + minutes + ' ' + ampm;
                return strTime;
            }
            
            // General State Info
            function electionAdminsTable(data, index) {
                var item = data.electionAdministrationBody;
                var name = data.name;
                var html = '';
                if(item.name){ html += '<p><strong>'+item.name+'</strong></p>'; }
                if(item.electionInfoUrl){ html += '<p><strong>Election Information:</strong><br /><a target="_blank" href="'+item.electionInfoUrl+'">'+item.electionInfoUrl+'</a></p>'; }
                if(item.electionRegistrationConfirmationUrl){ html += '<p><strong>Registration Confirmation:</strong><br /><a target="_blank" href="'+item.electionRegistrationConfirmationUrl+'">'+item.electionRegistrationConfirmationUrl+'</a></p>'; }
                if(item.votingLocationFinderUrl){ html += '<p><strong>Voting Location Finder:</strong><br /><a target="_blank" href="'+item.votingLocationFinderUrl+'">'+item.votingLocationFinderUrl+'</a></p>'; }
                return html;
            } 

            /**
             * Get Voter Info
             */            
            jQuery(document).ready(function($) {

                function initMap() {
                    map = new google.maps.Map(document.getElementById("result-map"), {
                        zoom: 4,
                        center: { lat: 39, lng: -95 },
                    });                     
                }
                initMap();   

                
                /**
                 * Add Marker to Map
                 */
                function addMarker(location, map, customLabel, infoText, iconColor = '', indexCounter, bounds) {

                    if(customLabel == 'home'){  

                        marker = new google.maps.Marker({
                            position: location,
                            map: map,
                            icon: {
                                url: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png"
                            }
                        });

                    } else {
                            
                        if(!isNaN(location.lat)){

                            // Update duplicate ever so slighty to avoid overlapping
                            for(i = 0; i < uniqueMarkers.length; ++i){
                                if(uniqueMarkers[i].lat === location.lat){          
                                    if(uniqueMarkers[i].lng === location.lng){                     
                                        location.lat = location.lat + .00004;
                                        location.lng = location.lng + .00004;
                                    }
                                }
                            }

                            var infoWindowMarker = new google.maps.InfoWindow({
                                content: infoText
                            });

                            marker = new google.maps.Marker({
                                position: location,
                                label: ''+indexCounter+'',
                                map: map,
                                icon: {
                                    url: 'https://voterparticipation.org/wp-content/themes/voterparticipationcenter/assets/img/map-icon'+iconColor+'.png',
                                    size: new google.maps.Size(54, 54),
                                    scaledSize: new google.maps.Size(32,32),
                                    labelOrigin: new google.maps.Point(16, 16)
                                },
                                infowindow: infoWindowMarker
                            });
                            
                            google.maps.event.addListener(marker, 'click', function() {
                                this.infowindow.open(map, this);
                            });
                        }
                        
                    }

                    markers.push(marker);
                    uniqueMarkers.push(location);
                    loc = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
                    bounds.extend(loc);

                }

                /**
                 * Delete Markers
                 */
                // Deletes all markers in the array by removing references to them.
                function deleteMarkers() {
                    for(let i = 0; i < markers.length; i++) {
                        markers[i].setMap(null);
                        markerCluster.removeMarker(markers[i]);
                    }
                    marker = [];
                    markers = [];
                    uniqueMarkers = [];
                    if(markerCluster){
                        markerCluster = new MarkerClusterer({ markers, map });
                        markerCluster.setMap(null);
                    }
                }              
                                
                // Early Voting Locations
                function earlyLocationsTable(item, index, array) {   
                    
                    // Check County
                    var latlng = {
                            lat: item.lat,
                            lng: item.lon,
                        },
                        indexCounter = index + 1,
                        html = '',
                        bounds = new google.maps.LatLngBounds(), 
                        geocoder = new google.maps.Geocoder(),
                        hours = 'N/A',
                        pollingHours = 'N/A',
                        hoursHtml = 'N/A',
                        hoursData = [],
                        hoursClass = '',
                        startDate = 'N/A',
                        endDate = 'N/A';

                    // Construct the address
                    var address = '<strong>'+item.name+'</strong><br />';
                    if(item.address_line_1){ address += item.address_line_1+'<br />'; }
                    if(item.address_line_2){ address += item.address_line_2+'<br />'; }
                    address += item.city+', '+item.state+' '+item.zip;
                    
                    // Hours
                    if(item.hours && item.hours.length > 0 ){
                        var open_hours = item.hours.reverse();
                        hoursHtml = ''; // Reset the HTML to nothing
                        for (i = 0; i < item.hours.length; i++) {
                            
                            var tz = item.hours[i].timezone;
                            var opens = new Date(item.hours[i].open_at+'Z');
                            var opensDisplay = formatAMPM(opens);

                            var closes = new Date(item.hours[i].close_at+'Z');
                            var closesDisplay = formatAMPM(closes);

                            var today = new Date();
                            if(today.getTime() < opens.getTime()){ // Skip older days
                                
                                hoursHtml += '<div class="hours row-'+i+'">';
                                    hoursHtml += '<strong>'+ opens.toLocaleDateString('en-US', { month: "short", day: "numeric" })+':</strong> ';
                                    hoursHtml += opensDisplay+' to ';
                                    hoursHtml += closesDisplay+'';
                                hoursHtml += '</div>';
                                
                            }
                        }

                        var startDateRaw = new Date(item.hours[0].open_at);
                        startDateRaw.toLocaleString('en-US', { timeZone: item.hours[0].timezone });
                        startDate = startDateRaw.toLocaleDateString('en-US', { month: "long", day: "numeric" , year: "numeric" });

                        var endDateRaw = new Date(item.hours[item.hours.length - 1].open_at);
                        endDateRaw.toLocaleString('en-US', { timeZone: item.hours[item.hours.length - 1].timezone });
                        endDate = endDateRaw.toLocaleDateString('en-US', { month: "long", day: "numeric" , year: "numeric" });

                    }

                    var lastDay = null,
                        dayDisplay = '';
                        endDay = '';
                        hoursDisplay = '',
                        openHoursRow = false;

                    // Directions
                    var directions = '<a class="btn-small" target="_blank" href="https://www.google.com/maps/place/'+encodeURI(item.address_line_1+' '+item.city+' '+item.state+' '+item.zip)+'">Get Directions</a>';

                    var html = '<tr data-row="'+indexCounter+'">';
                        html += '<td class="counter-cell"><strong class="counter">'+ indexCounter +'</strong></td>';
                        html += '<td>'+address+'<br />'+directions+'</td>';
                        html += '<td><p><strong>Starts:</strong><br />'+startDate+'</p><p><strong>Ends:</strong><br />'+endDate+'</p></td>';
                        html += '<td>'+hoursHtml+'</td>';
                    html += '</tr>';
                    
                    // Don't add duplicates we've already parsed
                    if($('tr[data-row="'+indexCounter+'"]').length == 0){                                                 
                        $('#table-early-voting').append(html);

                        // Reorder...
                        $("#table-early-voting tr").sort(sort_rows).appendTo('#table-early-voting');
                        function sort_rows(a, b){
                            return ($(b).data('row')) < ($(a).data('row')) ? 1 : -1;    
                        }

                        // Add map marker
                        var locationLatLng = {
                            lat: item.lat,
                            lng: item.lon
                        };
                        infoText = '<p style="margin: 0;"><strong>Early Voting Site</strong><br /><br />'+address+'<br />'+directions+'</p>';

                        addMarker(locationLatLng, map, '', infoText, '', indexCounter, bounds);
                    }

                } 

                function earlyLocationsTableGoogle(item, index) {
                    var address = '<strong>'+item.address.locationName+'</strong><br />',
                        hours = 'N/A',
                        indexCounter = index + 1,
                        pollingHours = 'N/A',
                        hoursHtml = 'N/A',
                        hoursClass = '';
                    if(item.address.line1){ address += item.address.line1+'<br />'; }
                    if(item.address.line2){ address += item.address.line2+'<br />'; }
                    address += item.address.city+', '+item.address.state+' '+item.address.zip;

                    if(item.pollingHours){
                        pollingHours = item.pollingHours;
                        hoursHtml = '';
                        var separators = [';', '\n'];                        
                        var hoursArray = pollingHours.split(new RegExp(separators.join('|'), 'g'));
                        for (i = 0; i < hoursArray.length; i++) {
                            hoursHtml += '<div class="hours'+hoursClass+'">'+hoursArray[i]+'</div>';
                        }
                    }
                    var directions = '<a class="btn-small" target="_blank" href="https://www.google.com/maps/place/'+encodeURI(item.address.line1+' '+item.address.city+' '+item.address.state+' '+item.address.zip)+'">Get Directions</a>';

                    var notes = '';
                    if(item.notes){
                        notes = item.notes;
                    }

                    var startDate = 'N/A';
                    if(item.startDate){
                        startDate = item.startDate;
                    }
                    var endDate = 'N/A';
                    if(item.endDate){
                        endDate = item.endDate;
                    }

                    var html = '<tr>';
                        html += '<td class="counter-cell"><strong class="counter">'+counter+'</strong></td>';
                        html += '<td>'+address+'<br />'+directions+'</td>';
                        html += '<td><p><strong>Starts:</strong><br />'+startDate+'</p><p><strong>Ends:</strong><br />'+endDate+'</p></td>';
                        html += '<td>'+hoursHtml+'</td>';
                        //html += '<td>'+notes+'</td>';
                    html += '</tr>';
                    
                    var locationLatLng = {
                        lat: item.latitude,
                        lng: item.longitude
                    };

                    infoText = '<p style="margin: 0;"><strong>Ballot Drop Off</strong><br /><br />'+address+'<br />'+directions+'</p>';
                    //addMarker(locationLatLng, map, '', infoText);
                    addMarker(locationLatLng, map, '', infoText, '', indexCounter, bounds);
                    counter++;
                    return html;

                } 

                // Get Election Info
                $("#ev-lookup").submit(function(e) {
                    e.preventDefault();

                    if(!$('#ev-lookup').hasClass('loading')){

                        // Disable button and show "loading"
                        $('#ev-lookup .message').removeClass('error').html('');
                        $('#ev-lookup').prop('disabled',true).addClass('loading');

                        // Default vars
                        var form = $(this),
                            formData = $(this).serialize(),
                            address = encodeURI($('#address').val()),
                            addressVal = $('#address').val(),
                            geocoder = new google.maps.Geocoder();

                        // Get the state abbreviation/lat/long
                        geocoder.geocode( { 'address': addressVal}, function(results, status) {

                            if (status == 'OK') {

                                var state_abbrev = null,
                                    state_long = null,
                                    lat = results[0].geometry.location.lat() ? results[0].geometry.location.lat() : null,
                                    lng = results[0].geometry.location.lng() ? results[0].geometry.location.lng() : null,
                                    address_components = {
                                        street_number: '',
                                        pre_directional: '',
                                        street_name: '',
                                        city: '',
                                        state: '',
                                        zipcode: '',
                                        street_suffix: '',
                                        post_directional: '',
                                    };                                

                                var searchedAddressComp = results[0].address_components;                                    
                                for (i = 0; i < results[0].address_components.length; i++) {
                                    if(searchedAddressComp[i].types[0] == 'administrative_area_level_1'){
                                        state_abbrev = searchedAddressComp[i].short_name;
                                        state_long = searchedAddressComp[i].long_name;
                                        address_components['state'] = state_abbrev;
                                    }
                                    if(searchedAddressComp[i].types[0] == 'street_number'){
                                        address_components['street_number'] = searchedAddressComp[i].short_name;
                                    }
                                    if(searchedAddressComp[i].types[0] == 'locality'){
                                        address_components['city'] = searchedAddressComp[i].short_name;
                                    }
                                    if(searchedAddressComp[i].types[0] == 'postal_code'){
                                        address_components['zipcode'] = searchedAddressComp[i].short_name;
                                    }
                                } 

                                //console.log(results);

                                if( state_abbrev && lat && lng ){
                                
                                    $.ajax({
                                        type: "POST",
                                        dataType: "jsonp",
                                        url: "https://www.googleapis.com/civicinfo/v2/voterinfo?key=<?php echo $google_api_key; ?>&electionId=<?php echo $election_id; ?>",
                                        data: formData,
                                        success: function(data) {
                                            
                                            console.log(data);

                                            if(data.error){

                                                $('#ev-lookup .message').addClass('error').html('<p>The entered address is invalid, please double check and try again!</p>');

                                            } else {

                                                // No errors lets go
                                                $('#result-map').fadeIn();

                                                // Clear any previous results
                                                $('#result-location').html('');
                                                deleteMarkers();
                                                counter = 1;

                                                // Pre set map bounds
                                                bounds = new google.maps.LatLngBounds();

                                                // Ballot Drop off Locations
                                                var dropHtml = '',
                                                    earlytHtml = '',
                                                    ballotTrackCheck = '';
                                                if(ballotTrack[data.normalizedInput.state]){
                                                    ballotTrackCheck = '<p class="ballot-track content-row--bright-green p-4">'+ballotTrack[data.normalizedInput.state]+'</p>'
                                                } else {
                                                    ballotTrackCheck = '<p class="ballot-track content-row--bright-green p-4">No locations came up in our search; please check your state\'s election site for more information.</p>';
                                                }

                                                // General election info for the state
                                                if(data.state){
                                                    var electionAdmins = data.state;
                                                    var stateHtml = '<h5>'+data.state[0].name+' Voting Information</h5>';
                                                    
                                                    if(!data.hasOwnProperty('dropOffLocations') && !data.hasOwnProperty('earlyVoteSites')){
                                                        stateHtml += ballotTrackCheck;
                                                    }
                                                    stateHtml += electionAdmins.map(electionAdminsTable).join('');
                                                    $('#result-location').append(stateHtml);
                                                }

                                                //console.log(data);
                                                if(data.earlyVoteSites || data.dropOffLocations){
                                                    /*
                                                    if(data.earlyVoteSites){
                                                        var earlyVoteSites = data.earlyVoteSites;                          
                                                        earlytHtml += '<h5>Your Early Voting Locations</h5>';
                                                        
                                                        earlytHtml += '<table id="table-early"><tr><th class="counter-cell">#</th><th>Address</th><th>Dates</th><th>Polling Hours</th></tr>';
                                                        earlytHtml += earlyVoteSites.map(earlyLocationsTableGoogle).join('');
                                                        earlytHtml += '</table>';
                                                        $('#result-location').append(earlytHtml);
                                                    }
                                                    */

                                                    if(data.dropOffLocations){
                                                        var dropOffLocations = data.dropOffLocations;             
                                                        earlytHtml += '<h5>Your Early Voting Locations</h5>';
                                                        
                                                        dropHtml += '<table id="table-dropoff"><tr><th class="counter-cell">#</th><th>Address</th><th>Dates</th><th>Polling Hours</th></tr>';
                                                        dropHtml += dropOffLocations.map(earlyLocationsTableGoogle).join('');
                                                        dropHtml += '</table>';
                                                        $('#result-location').append(dropHtml);
                                                    }
                                                    
                                                } else {
                                                    /*
                                                    if(ballotTrack[data.state[0].name]){
                                                        dropHtml += ballotTrackCheck;
                                                        $('#result-location').append(dropHtml);
                                                    }
                                                    */
                                                }


                                                // Update map center
                                                var geocoder = new google.maps.Geocoder();
                                                geocoder.geocode( { 'address': addressVal}, function(results, status) {
                                                    if (status == 'OK') {
                                                        map.setCenter(results[0].geometry.location);
                                                        //addMarker(results[0].geometry.location, map, 'home');
                                                        addMarker(results[0].geometry.location, map, 'home', '', '', null, bounds);
                                                    }
                                                });

                                                // Update map center
                                                var latNe = Math.round(bounds.getNorthEast().lat());
                                                var latSw = Math.round(bounds.getSouthWest().lat());
                                                if (latNe == latSw) {
                                                    var extendPoint1 = new google.maps.LatLng(bounds.getNorthEast().lat()+0.2, bounds.getNorthEast().lng()+0.2);
                                                    var extendPoint2 = new google.maps.LatLng(bounds.getNorthEast().lat()-0.2, bounds.getNorthEast().lng()-0.2);
                                                    bounds.extend(extendPoint1);
                                                    bounds.extend(extendPoint2);
                                                }
                                                map.fitBounds(bounds);
                                                map.panToBounds(bounds);
                                                
                                                markerCluster = new MarkerClusterer(map, markers, {
                                                    imagePath: "https://voterparticipation.org/wp-content/themes/voterparticipationcenter/assets/img/m",
                                                });                              

                                                $('#ev-lookup').prop('disabled',false).removeClass('loading');
                                                
                                            }
                                        },
                                        error: function (request, status, error) {
                                            console.error(request.responseText);
                                        }

                                    });

                                } else {

                                    $('#ev-lookup .message').addClass('error').html('<p>The entered address is invalid, please double check and try again! [ST]</p>');
                                }

                            } else {

                                $('#ev-lookup .message').addClass('error').html('<p>The entered address is invalid, please double check and try again! [EG]</p>');

                            }
                            
                        });

                    }

                }); 


            });
        </script>

        <div class="gradient-banner" role="banner">
            <div class="container mw-840px">
                <h1 class="gradient-banner__headline text-center"><?php the_title(); ?></h1>
                    
                <div class="text-center mt-3"><?php the_content(); ?></div>

                <div class="row mt-4">
                <div class="col-12">
                    <form action="/lookup-tool#results" method="POST" id="ev-lookup" class="clearfix text-center">
                        <input type="text" id="address" name="address" class="address-search" placeholder="Enter your address" value="" /> 
                        <input type="submit" class="address-submit" name="submit" value="SEARCH" />
                        <div class="clear message"></div>
                    </form>
                </div>
                </div>
                
            </div> <!-- .container -->
        </div> <!-- .gradient-banner -->
            
        
        <div class="container-fluid">
            <div id="results">                
            <div id="result-content" class="row">
                <div id="result-left" class="col-12 col-lg-6 p-0">
                    <div id="result-location" class="p-3 p-lg-5"></div>
                </div>
                <div id="results-right" class="col-12 d-none d-lg-block col-lg-6 p-0">
                    <div id="result-map" class="map" style="display: none;"></div>
                </div>
            </div>
            </div>

        </div>
            
    <?php endwhile; endif; ?>
<?php get_footer(); ?>
