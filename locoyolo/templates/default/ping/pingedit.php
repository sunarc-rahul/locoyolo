<?php include_once(TEMPPATH . "/header.php") ?>

<script>
    function insertRow() {
        var index = parseInt(document.getElementById("objective_number").value) + 1;
        var table = document.getElementById("objectivestable");
        var row = table.insertRow(table.rows.length);
        var cell1 = row.insertCell(0);
        cell1.style.height = '40px';
        cell1.innerHTML = "<font class=\"ArialVeryDarkGrey15\">#" + index + "</font>";

        var cell2 = row.insertCell(1);
        cell2.style.height = '40px';
        var t2 = document.createElement("input");
        t2.id = "event_objective" + index;
        t2.setAttribute("name", "event_objective" + index);
        t2.setAttribute("class", 'textboxbottomborder');
        t2.setAttribute("size", '80');
        t2.setAttribute("placeholder", 'Objective #' + index);
        cell2.appendChild(t2);

        var objectivenumber = document.getElementById("objective_number");
        objectivenumber.remove();
        var t3 = document.createElement("input");
        t3.setAttribute("type", 'hidden');
        t3.setAttribute("id", 'objective_number');
        t3.setAttribute("name", 'objective_number');
        t3.setAttribute("value", index);
        cell2.appendChild(t3);
    }
</script>

<style>
    /* Always set the map height explicitly to define the size of the div
     * element that contains the map. */
    #map {
        height: 400px;
    }

    .page-header {
        font-weight: bold;
    }

    /* Optional: Makes the sample page fill the window. */
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }

    #description {
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
    }

    #infowindow-content .title {
        font-weight: bold;
    }

    #infowindow-content {
        display: none;
    }

    #map #infowindow-content {
        display: inline;
    }

    .pac-card {
        margin: 10px 10px 0 0;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        background-color: #fff;
        font-family: Roboto;
    }

    #pac-container {
        padding-bottom: 12px;
        margin-right: 12px;
    }

    .pac-controls {
        display: inline-block;
        padding: 5px 11px;
    }

    .pac-controls label {
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
    }

    #event_location {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 550px;
    }

    #event_location:focus {
        border-color: #4d90fe;
    }

    #title {
        color: #fff;
        background-color: #4d90fe;
        font-size: 25px;
        font-weight: 500;
        padding: 6px 12px;
    }

    #target {
        width: 345px;
    }
</style>
</head>

<body>

<?php
if (count($error) >= 1 ) { ?>
    <div class="shows-errors_div has-error-message">
    <div class="container is-error-message">
        <div class="Show-error">
            <span class="error-close">X</span>
            <?php
            foreach ($error as $errormessage) {
                if ($errormessage !== "") { ?>
                    <div class="alert alert-danger"><?php echo  $errormessage; ?></div>
                    <?php  echo $_SESSION['success'];

                    unset($_SESSION['success']);
                    ?>

                <?php }
            } ?>
        </div>
    </div>
    </div>
<?php } ?>
<?php if (isset($_SESSION['success'])) {?>
<div class="shows-errors_div has-error-message">
    <div class="container is-error-message">
        <div class="Show-error on-success">
            <span class="error-close on-success-close">X</span>
                    <div class="alert alert-success">
                    <?php  echo $_SESSION['success'];
                    unset($_SESSION['success']);
                    ?></div>
</div>
</div>
</div>
    <?php
}
?>

</div>
<div class="container fixed-footer">
    <div class="row">
       
        <div class="col-sm-12">
            <form class="form-vertical" method="post" action="" enctype="multipart/form-data">
                <h3 class="page-header text-warning">Ping</h3>

                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-12 col-md-12 ">
                            <div class="form-group">
                                <label for="event_name" style="font-weight: bold" class="title">Title:</label>
                                <input type="text" maxlength="50" name="event_name" value="<?php echo  $userEventData->event_name ?>" cols="100" rows="1" class="form-control input-sm" id="event_name ping-title" placeholder="Title should not exceed more than 50 words">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                           <div class="col-xs-12 col-sm-3 col-md-3 ">
                        <div class="form-group">
                            <label style="font-weight: bold;" class="title">Date-Time:</label>
                             <div class='input-group date' id='datetimepicker'>
                                    <input type='text'  name="timerange" value="<?php echo $userEventData->start_date; ?>"  id="timerange" class="form-control" />
                                    <span class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </span>
                             </div>
                        </div>
                    </div>
                    <?php
//                    echo $userEventData->end_date;
                    $start_datetime = new DateTime($userEventData->start_date);
                    $end_datetime = new DateTime($userEventData->end_date);
                    $interval = $start_datetime->diff($end_datetime);
//                    $ff = $interval->format('%h')." Hours ".$interval->format('%i')." Minutes";
                    $calculated_hours = $interval->format('%h');
                    ?>
                    <div class="col-xs-12 col-sm-3 col-md-3 ">
                        <div class="form-group">
                            <label for="duratuion" class="title">Duration:</label>
                            <select name="duratuion" class="form-control btn-sm" id="duratuion">

                               <?php for($i=1; $i<=24;$i++){ ?>
                                <option value="<?php echo $i;?>" <?php if($calculated_hours == $i) { ?> selected="selected" <?php } ?>>
                                   <?php echo $i;  if($i==1)echo' hour';else echo' hours';  ?>
                               </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-3 ">
                        <div class="form-group">
                            <label for="event_category" class="title">Category: </label>
                            <select name="event_category" class="form-control btn-sm" id="event_category">


                                <option value="1" <?php if ($userEventData->event_category == "1") { ?> selected="selected" <?php } ?>  <?php if ($_POST["event_category"] == "") { ?> selected="selected" <?php } ?> >
                                    Sports
                                </option>
                                <option value="2" <?php if ($userEventData->event_category == "2") { ?> selected="selected" <?php } ?> >
                                    Relaxation
                                </option>
                                <option value="3" <?php if ($userEventData->event_category == "3") { ?> selected="selected" <?php } ?> >
                                    Food &amp; Drink
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-3 col-md-3">
                        <div class="form-group">
                            <label for="event_participants_max">
                                <span class="mandatory">*</span>Max. participants:
                            </label>
                            <input name="event_participants_max" type="text" class="form-control input-sm"
                                   id="event_participants_max"
                                   value="<?php echo $_POST['event_participants_max']?$_POST['event_participants_max']:$userEventData->event_participants_max; ?>" size="5"/>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-12 col-md-12 ">
                            <div class="form-group">
                                <label for="event_name" style="font-weight: bold" class="title">What's the ping about? </label>
                                <textarea name="event_description" cols="100" rows="2" class="form-control input-sm"   id="event_description" placeholder="What's the meetup about?"><?php echo   $userEventData->event_description ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <h3 class="page-header text-warning">Location</h3>
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12 col-xs-12 col-md-12">
                            <label for="event_location" style="font-weight: normal">Please search for the exact or closest location to your event, then click on the map to mark the location of your event. </label>
                            <input type="hidden" name="event_lat" id="event_lat" value="<?php echo $userEventData->event_lat; ?>">
                            <input type="hidden" name="event_long" id="event_long" value="<?php echo $userEventData->event_long; ?>">
                            <input type="hidden" name="city2" id="city2" value="<?php echo $userEventData->event_location; ?>">
                            <input type="text" style="border: 2px solid gray;" class="col-md-6 form-control input-sm"
                             value="<?php echo  $userLocationData->event_location ; ?>"      id="event_location" name="event_location" placeholder="Search for the exact or nearest location of your event..." size="60">
                            <div id="map" style="height:300px"></div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="form-group">
                        <div>
                            <input type="hidden" id="new_event_entry" name="new_event_entry" value="Y"/>
                            <input class="standardbutton" style="cursor:pointer" type="submit"
                                   name="edit_ping_submit" id="submit" value="Ping it!">
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
<script>
    // This example adds a search box to a map, using the Google Place Autocomplete
    // feature. People can enter geographical searches. The search box will return a
    // pick list containing a mix of places and predicted search terms.

    // This example requires the Places library. Include the libraries=places
    // parameter when you first load the API. For example:
    // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

    function initAutocomplete() {

        var directionsService = new google.maps.DirectionsService();
        var directionsDisplay = new google.maps.DirectionsRenderer();
        var marker;

        var map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: <?php echo $userEventData->event_lat?$userEventData->event_lat:'-33.8688'; ?>, lng: <?php echo $userEventData->event_lat?$userEventData->event_long:'151.2195'; ?>},
            zoom: 13,
            mapTypeId: 'roadmap'
        });

        // Create the search box and link it to the UI element.
        var input = document.getElementById('event_location');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function () {
            searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function () {
            var places = searchBox.getPlaces();
            places.forEach(function (place) {

                document.getElementById('city2').value = place.name;
//                                alert(place.geometry.location.lat());

                document.getElementById('event_lat').value = place.geometry.location.lat();
                document.getElementById('event_long').value = place.geometry.location.lng();
            });
            var geocoder = new google.maps.Geocoder();
            var address = document.getElementById('event_location').value;
            geocoder.geocode({'address': address}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    document.getElementById('event_lat').value = results[0].geometry.location.lat();
                    document.getElementById('event_long').value = results[0].geometry.location.lng();
                }
            });

            if (places.length == 0) {
                return;
            }

            // Clear out the old markers.
            markers.forEach(function (marker) {
                marker.setMap(null);
            });
            markers = [];

            // For each place, get the icon, name and location.
            var bounds = new google.maps.LatLngBounds();
            places.forEach(function (place) {
                if (!place.geometry) {
                    console.log("Returned place contains no geometry");
                    return;
                }
                var icon = {
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(25, 25)
                };

                // Create a marker for each place.
                markers.push(new google.maps.Marker({
                    map: map,
                    icon: icon,
                    title: place.name,
                    position: place.geometry.location
                }));

                google.maps.event.addListener(map, 'click', function (event) {
                    placeMarker(event.latLng);
                });

                function placeMarker(location) {

                    if (marker == null) {
                        marker = new google.maps.Marker({
                            position: location,
                            icon: 'images/red_pos_marker.fw.png',
                            map: map
                        });
//                                        alert(getElementById('event_lat'));
                        document.getElementById('event_lat').value = marker.getPosition().lat();
                        document.getElementById('event_long').value = marker.getPosition().lng();
                        //getDirections(document.getElementById('city2').value);
                    } else {
                        marker.setPosition(location);
                        document.getElementById('event_lat').value = marker.getPosition().lat();
                        document.getElementById('event_long').value = marker.getPosition().lng();
                        getDirections(document.getElementById('city2').value);
                    }
                }

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
        });
        if($(window).width()>=1200) {
            $('#map').css('width', '1100px');
        }
    }

    /*function getDirections(destination) {
     var start = marker.getPosition();
     var dest = destination;
     alert(dest);
     var request = {
     origin: start,
     destination: dest,
     travelMode: google.maps.TravelMode.WALKING
     };
     directionsService.route(request, function (result, status) {
     if (status == google.maps.DirectionsStatus.OK) {
     directionsDisplay.setDirections(result);
     }
     });
     }*/

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDHXsI2hOfs6x7NJLR8LnN5wG-2N-ha0S8&libraries=places&callback=initAutocomplete"
        async defer></script>
</body>
<script type="text/javascript">
    $(function () {
        $('#datetimepicker').datetimepicker({
            date: '<?php echo $userEventData->start_date?$userEventData->start_date:date('m/d/Y h:i:s') ?>'
        })
    });

</script>

</html>