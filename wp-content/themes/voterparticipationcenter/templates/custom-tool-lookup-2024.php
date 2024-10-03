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
            ballotTrack['AK'] = 'This tool does not have voting data for Alaska, <a target="_blank" href="https://myvoterinformation.alaska.gov/">please visit the Secretary of State website</a> for information on how to vote.';
            ballotTrack['AZ'] = 'We do not have any information for this location at this time. Please try again later or <a target="_blank" href="https://azsos.gov/elections">visit your Secretary of State website</a> for more information.';
            ballotTrack['AR'] = 'This tool does not have voting data for Arkansas, <a target="_blank" href="https://www.sos.arkansas.gov/elections">please visit the Secretary of State website</a> for information on how to vote.';
            ballotTrack['CA'] = 'California only offers early voting in select counties. <a target="_blank" href="https://www.sos.ca.gov/elections/voting-info/ways-vote">please visit the Secretary of State website</a> to find information on how to vote.';
            ballotTrack['DE'] = 'This tool does not have voting data for Delaware, <a target="_blank" href="https://elections.delaware.gov/">please visit the Secretary of State website</a> for information on how to vote.';
            ballotTrack['DC'] = '';
            ballotTrack['FL'] = '';
            ballotTrack['GA'] = 'We do not have any information for this location at this time. Please try again later or <a target="_blank" href="https://sos.ga.gov/">visit your Secretary of State website</a> for more information.';
            ballotTrack['HI'] = '';
            ballotTrack['ID'] = '';
            ballotTrack['IL'] = '';
            ballotTrack['IN'] = 'This tool does not have voting data for Indiana, <a target="_blank" href="https://www.in.gov/sos/elections/">please visit the Secretary of State website</a> for information on how to vote.';
            ballotTrack['IA'] = 'This tool does not have voting data for Iowa, <a target="_blank" href="https://sos.iowa.gov/elections/voterinformation/index.html">please visit the Secretary of State website</a> for information on how to vote.';
            ballotTrack['KS'] = 'We do not have any information for this location at this time. Please try again later or <a target="_blank" href="https://sos.ks.gov/elections/elections.html">visit your Secretary of State website</a> for more information.';
            ballotTrack['KY'] = 'This tool does not have voting data for Kentucky, <a target="_blank" href="https://elect.ky.gov/Pages/default.aspx">please visit the Secretary of State website</a> for information on how to vote.';
            ballotTrack['LA'] = 'This tool does not have voting data for Louisiana, <a target="_blank" href="https://www.sos.la.gov/ElectionsAndVoting/Pages/default.aspx">please visit the Secretary of State website</a> for information on how to vote.';
            ballotTrack['ME'] = 'We do not have any information for this location at this time. Please try again later or <a target="_blank" href="https://www.maine.gov/sos/">visit your Secretary of State website</a> for more information.';
            ballotTrack['MD'] = '';
            ballotTrack['MA'] = 'This tool does not have voting data for Massachusetts, <a target="_blank" href="https://www.sec.state.ma.us/ele/">please visit the Secretary of State website</a> for information on how to vote.';
            ballotTrack['MI'] = 'We do not have any information for this location at this time. Please try again later or <a target="_blank" href="https://www.michigan.gov/sos">visit your Secretary of State website</a> for more information.';
            ballotTrack['MN'] = '';
            ballotTrack['MO'] = 'This tool does not have voting data for Missouri, <a target="_blank" href="https://www.sos.mo.gov/elections">please visit the Secretary of State website</a> for information on how to vote.';
            ballotTrack['MT'] = 'This tool does not have voting data for Montana, <a target="_blank" href="https://sosmt.gov/elections/">please visit the Secretary of State website</a> for information on how to vote.';
            ballotTrack['NE'] = '';
            ballotTrack['NV'] = 'We do not have any information for this location at this time. Please try again later or <a target="_blank" href="https://www.nvsos.gov/sos">visit your Secretary of State website</a> for more information.';
            ballotTrack['NJ'] = 'This tool does not have voting data for New Jersey, <a target="_blank" href="https://www.state.nj.us/state/elections/vote.shtml">please visit the Secretary of State website</a> for information on how to vote.';
            ballotTrack['NM'] = '';
            ballotTrack['NY'] = 'This tool does not have voting data for New York, <a target="_blank" href="https://www.elections.ny.gov/">please visit the Secretary of State website</a> for information on how to vote.';
            ballotTrack['NC'] = '';
            ballotTrack['ND'] = '';
            ballotTrack['OH'] = '';
            ballotTrack['OK'] = '';
            ballotTrack['PA'] = 'We do not have any information for this location at this time. Please try again later or <a target="_blank" href="https://www.dos.pa.gov/Pages/default.aspx">visit your Secretary of State website</a> for more information.';
            ballotTrack['RI'] = '';
            ballotTrack['SC'] = 'This tool does not have voting data for South Carolina, <a target="_blank" href="https://scvotes.gov/">please visit the Secretary of State website</a> for information on how to vote.';
            ballotTrack['SD'] = '';
            ballotTrack['TN'] = 'This tool does not have voting data for Tennessee, <a target="_blank" href="https://sos.tn.gov/elections">please visit the Secretary of State website</a> for information on how to vote.';
            ballotTrack['TX'] = 'This tool does not have voting data for Texas, <a target="_blank" href="https://www.sos.state.tx.us/elections/">please visit the Secretary of State website</a> for information on how to vote.';
            ballotTrack['VT'] = '';
            ballotTrack['VA'] = '';
            ballotTrack['WV'] = 'This tool does not have voting data for West Virginia, <a target="_blank" href="https://sos.wv.gov/elections/Pages/default.aspx">please visit the Secretary of State website</a> for information on how to vote.';
            ballotTrack['WI'] = 'This tool does not have information for this location at this time. Please visit <a target="_blank" href="https://myvote.wi.gov/en-us/Find-My-Polling-Place">https://myvote.wi.gov/en-us/Find-My-Polling-Place</a> to find your voting locations.';
            ballotTrack['WY'] = '';

            // No early voting
            var noEarlyVoting = [];
            noEarlyVoting['AL'] = 'Alabama does not offer early voting. Please visit <a target="_blank" href="https://www.sos.alabama.gov/alabama-votes">the Secretary of State website</a> to find information on how to vote.';
            noEarlyVoting['CO'] = 'Colorado does not offer in-person early voting. Eligible voters will be mailed ballots. Please <a target="_blank" href="https://www.sos.state.co.us/pubs/elections/main.html?menuheaders=5">visit the Secretary of State website</a> for more information.';
            noEarlyVoting['CT'] = 'Connecticut does not offer early voting. Please visit <a target="_blank" href="https://portal.ct.gov/SOTS/Common-Elements/V5-Template---Redesign/Elections--Voting--Home-Page"the Secretary of State website</a> to find information on how to vote.';
            noEarlyVoting['MS'] = 'Mississippi does not offer early voting. Please visit <a target="_blank" href="https://www.sos.ms.gov/elections-voting">the Secretary of State website</a> to find information on how to vote.';
            noEarlyVoting['NH'] = 'New Hampshire does not offer early voting. Please visit <a href="_blank" href="https://www.sos.nh.gov/elections/voters">the Secretary of State website</a> to find information on how to vote.';
            noEarlyVoting['OR'] = 'Oregon does not offer in-person early voting. Eligible voters will be mailed ballots. Please visit <a href="_blank" href="https://sos.oregon.gov/voting-elections/Pages/default.aspx">the Secretary of State website</a> for more information.';
            noEarlyVoting['UT'] = 'Utah does not offer in-person early voting. Eligible voters will be mailed ballots. Please visit <a href="_blank" href="https://vote.utah.gov/">the Secretary of State website</a> for more information.';
            noEarlyVoting['WA'] = 'Washington does not offer in-person early voting. Eligible voters will be mailed ballots. Please visit <a href="_blank" href="https://www.sos.wa.gov/elections/">the Secretary of State website</a> for more information.';
                        
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
                    /*
                    if(hoursData.length){
                        for (i = 0; i < hoursData.length; i++) {                    
                            
                            var lastOpen = hoursData[0].open,
                                lastClose = hoursData[0].close;

                            // First Day
                            if(i == 0 || openHoursRow == false){
                                hoursDisplay += '<div class="hours"><strong>' + hoursData[i].day;
                                openHoursRow = true;
                                lastDay = hoursData[i].day;
                            }
                            
                            // Check if next day is different
                            var next = i + 1;
                            if( next < hoursData.length && lastOpen != hoursData[next].open || 
                                next < hoursData.length && lastClose != hoursData[next].close || 
                                i == hoursData.length - 1 && openHoursRow == true
                            ){  
                                if(hoursData[i].day != lastDay){
                                    hoursDisplay += ' - ' + hoursData[i].day;
                                }
                                var closing = '';
                                if(hoursData[i].close != ''){
                                    closing = ' to ' + hoursData[i].close;
                                }
                                hoursDisplay += '</strong>: ' + hoursData[i].open +''+ closing + '</div>';
                                openHoursRow = false;
                            }

                            lastOpen = hoursData[i].open;
                            lastClose = hoursData[i].close;
                        }
                    }
                    */


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

                                    //if( state_abbrev == 'MI'){

                                        /**
                                         * Michigan Override
                                         */
                                        $.ajax({
                                            type: "POST",
                                            dataType: "jsonp",
                                            url: "https://www.googleapis.com/civicinfo/v2/voterinfo?key=<?php echo $google_api_key; ?>&electionId=<?php echo $election_id; ?>",
                                            data: formData,
                                            success: function(data) {
                                                
                                                console.log(data);

                                                if(data.error){

                                                    $('#ev-lookup .message').addClass('error').html('<p>The entered address is invalid, please double check and try again! [MI]</p>');

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
                                                        ballotTrackCheck = '';
                                                    if(ballotTrack[data.normalizedInput.state]){
                                                        ballotTrackCheck = '<p class="ballot-track content-row--bright-green p-4">'+ballotTrack[data.normalizedInput.state]+'</p>'
                                                    }

                                                    // General election info for the state
                                                    if(data.state){
                                                        var electionAdmins = data.state;
                                                        var stateHtml = '<h5>'+data.state[0].name+' Voting Information</h5>';
                                                        
                                                        if(ballotTrack[data.normalizedInput.state]){
                                                            stateHtml += ballotTrackCheck;
                                                        }
                                                        
                                                        if(!data.dropOffLocations){
                                                            stateHtml += '<p>No locations came up in our search; please check your state\'s election site for more information.</p>';
                                                        }
                                                        stateHtml += electionAdmins.map(electionAdminsTable).join('');
                                                        $('#result-location').append(stateHtml);
                                                    }

                                                    //console.log(data);
                                                    if(data.earlyVoteSites){
                                                        var dropOffLocations = data.earlyVoteSites;                          
                                                        dropHtml += '<h5>Your Early Voting Locations</h5>';
                                                        
                                                        dropHtml += '<table id="table-dropoff"><tr><th class="counter-cell">#</th><th>Address</th><th>Dates</th><th>Polling Hours</th></tr>';
                                                        dropHtml += dropOffLocations.map(earlyLocationsTableGoogle).join('');
                                                        dropHtml += '</table>';
                                                        $('#result-location').append(dropHtml);
                                                    } else {
                                                        if(ballotTrack[data.state[0].name]){
                                                            dropHtml += ballotTrackCheck;
                                                            //$('#result-location').append(dropHtml);
                                                        }
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
                                                
                                    //} else {

                                        /**
                                         * Every Other State
                                         */
                                        $.ajax({
                                            url: "https://api.civicengine.com/polling_places",
                                            data: { 
                                                longitude:          lng,
                                                latitude:           lat,
                                                election_id:        <?php echo $election_id; ?>,                                            
                                                street_number:      address_components['street_number'],
                                                pre_directional:    address_components['pre_directional'],
                                                street_name:        address_components['street_name'],
                                                city:               address_components['city'],
                                                state:              address_components['state'],
                                                zipcode:            address_components['zipcode'],
                                                street_suffix:      address_components['street_suffix'],
                                                post_directional:   address_components['post_directional']
                                            },
                                            type: "GET",
                                            headers: { 'x-api-key': '<?php echo $ballotpedia_api_key; ?>' },
                                            success: function(data) {
                                                
                                                console.log(data);

                                                // No errors, lets go!
                                                $('#result-map').fadeIn();

                                                // Clear any previous results
                                                $('#result-location').html('');
                                                deleteMarkers();

                                                // General vars
                                                var counter = 1,
                                                    bounds = new google.maps.LatLngBounds(), // Pre-set map bounds
                                                    earlyHtml = '',// Ballot Drop off Locations
                                                    dayOfHtml = '',// Election Day Locations
                                                    ballotTrackCheck = '',
                                                    earlyVoteCheck = '';


                                                if(ballotTrack[state_abbrev] && !data.results.early_voting.length){
                                                    if(ballotTrack[state_abbrev] != ''){
                                                        //console.log('Check 1');
                                                        ballotTrackCheck = '<p class="ballot-track content-row--bright-green p-4">'+ballotTrack[state_abbrev]+'</p>';
                                                    } else {
                                                        //console.log('Check 2');
                                                        ballotTrackCheck = '<p class="ballot-track content-row--bright-green p-4">This tool does not have voting data for '+state_long+', please visit <a href="'+ballotTrack[state_abbrev]+'" target="_blank">'+ballotTrack[state_abbrev]+'</a> for information.</p>';
                                                    }
                                                }
                                                if(noEarlyVoting[state_abbrev]){
                                                    earlyVoteCheck = '<p class="ballot-track content-row--bright-green p-4">'+noEarlyVoting[state_abbrev]+'</p>';
                                                }

                                                // console.log('noEarlyVoting[state_abbrev]: ', noEarlyVoting[state_abbrev]);
                                                // console.log('Early Voting: ', data.results.early_voting.length);
                                                // console.log('State Abbreviation: ', state_abbrev);
                                                // console.log('ballotTrack: ', ballotTrack[state_abbrev]);
                                                // console.log('ballotTrackCheck: ', ballotTrackCheck);
                                                // console.log('earlyVoteCheck: ', earlyVoteCheck);

                                                // Early voting data
                                                if(data.results.early_voting.length > 0){
                                                    earlyHtml += '<h5>Your Early Voting Locations</h5>';

                                                    if(ballotTrack[state_abbrev]){
                                                        //console.log('Ballot track here');
                                                        earlyHtml += ballotTrackCheck;
                                                    }
                                                    else if(noEarlyVoting[state_abbrev]){
                                                        //console.log('Early vote check');
                                                        earlyHtml += earlyVoteCheck;
                                                    }
                                                    
                                                    // Get all our locations
                                                    var allLocations = [];
                                                    allLocations.push.apply(allLocations, data.results.early_voting);
                                                    //allLocations.push.apply(allLocations, data.results.election_day);

                                                    // Split Early Voting and Drop Boxes
                                                    /*
                                                    var onlyDropBoxes = allLocations.filter(function( obj ) {
                                                        return obj.ballot_drop_off == true;
                                                    });
                                                    */
                                                    var onlyEarlyVote = allLocations.filter(function( obj ) {
                                                        return obj.ballot_drop_off == false;
                                                    });


                                                    var today = new Date();
                                                    today.setHours(0,0,0,0);
                                                    var onlyEarlyVote = onlyEarlyVote.filter(function( obj ) {
                                                        let lastDay = new Date( obj.hours[0].close_at+'Z' );
                                                        lastDay.setHours(0,0,0,0);
                                                        return lastDay >= today;
                                                    });

                                                    // Create the table of locations
                                                    if(onlyEarlyVote.length > 0){
                                                        earlyHtml += '<table id="table-early-voting"><tr><th class="counter-cell">#</th><th>Address</th><th>Dates</th><th>Polling Hours</th></tr></table>';       
                                                        $('#result-location').append(earlyHtml);  
                                                        onlyEarlyVote.forEach(earlyLocationsTable);
                                                    } else {
                                                        if(ballotTrackCheck){
                                                            earlyHtml += ballotTrackCheck;
                                                            $('#result-location').append(earlyHtml);
                                                        } 
                                                        else if(earlyVoteCheck){
                                                            earlyHtml += earlyVoteCheck;
                                                            $('#result-location').append(earlyHtml);
                                                        } else {
                                                            if(ballotTrack[state_abbrev] != ''){
                                                                $('#result-location').append('<p class="ballot-track content-row--bright-green p-4">'+ballotTrack[state_abbrev]+'</p>');                                                                
                                                            } else {
                                                                $('#result-location').append('<p class="ballot-track content-row--bright-green p-4">We do not have any information for this location at this time. Please try again later or visit your Secretary of State website for more information.</p>');
                                                            }
                                                            
                                                        }
                                                    }
                                                    
                                                } else {
                                                    if(ballotTrackCheck){
                                                        earlyHtml += ballotTrackCheck;
                                                        $('#result-location').append(earlyHtml);
                                                    } 
                                                    else if(earlyVoteCheck){
                                                        earlyHtml += earlyVoteCheck;
                                                        $('#result-location').append(earlyHtml);
                                                    } else {
                                                        $('#result-location').append('<p class="ballot-track content-row--bright-green p-4">We do not have any information for this location at this time. Please try again later or visit your Secretary of State website for more information.</p>');
                                                    }
                                                }
                                                
                                                map.setCenter(results[0].geometry.location);
                                                addMarker(results[0].geometry.location, map, 'home', '', '', null, bounds);
                                                                                                                            
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

                                            },
                                            error: function (request, status, error) {
                                                console.error(request.responseText);
                                            }
                                        });
                                    //}

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
