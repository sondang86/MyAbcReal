<?php
    if(!defined('IN_SCRIPT')) die("");
?>
<input id="pac-input" class="controls" type="text" placeholder="Tìm kiếm địa điểm">
<label id="map"></label>

<?php if(!empty($note)):?>
<div class="note"><strong>Gợi ý:</strong> <?php echo $note;?></div>
<?php endif;?>

<input type="hidden" id="job-map-latitude" name="job_map_latitude" value="<?php echo $latitude;?>">
<input type="hidden" id="job-map-longitude" name="job_map_longitude" value="<?php echo $longitude;?>">

<script>
    /*GOOGLE MAPS WITH MARKER + SEARCH BOX + LOCATION DETECTS*/
    var marker;        
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 13,
            center: {lat: <?php echo $latitude;?>, lng: <?php echo $longitude;?>}
        });        
        
        //Set marker on click
        google.maps.event.addListener(map, 'click', function(event) {
            placeMarker(event.latLng);
            //Store value
            $("#job-map-latitude").prop("value", event.latLng.lat());
            $("#job-map-longitude").prop("value", event.latLng.lng());
        });
        
        var marker_exists = [];        
        //Set marker with default position
        marker = new google.maps.Marker({
            map: map,
            draggable: true,
            animation: google.maps.Animation.DROP,
            position : {lat: <?php echo $latitude;?>, lng: <?php echo $longitude;?>}
        });        
        // Push newly created marker into the array:
        marker_exists.push(marker);
        
        //Set marker value on drag
        google.maps.event.addListener(marker, 'dragend', function (event) {
            var latitude = this.position.lat();
            var longitude = this.position.lng();
            //Store value
            $("#job-map-latitude").prop("value", latitude);
            $("#job-map-longitude").prop("value", longitude);
        }); 
        
        
        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
            searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
            var places = searchBox.getPlaces();

            if (places.length == 0) {
                return;
            }

            // Clear out the old markers.
            markers.forEach(function(marker) {
                marker.setMap(null);
            });
            markers = [];

            // For each place, get the icon, name and location.
            var bounds = new google.maps.LatLngBounds();
            places.forEach(function(place) {
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

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
            });
            map.fitBounds(bounds);
        });
               

        // Try HTML5 geolocation.
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };               
            
                //Center the map
//                map.setCenter(pos);

//                if(marker_exists.length > 0){ //Marker exists
//                    //Remove exists marker first
//                    marker.setMap(null);
//                
//                    //Re-set marker
//                    marker = new google.maps.Marker({
//                        map: map,
//                        draggable: true,
//                        animation: google.maps.Animation.DROP,
//                        position: {lat: position.coords.latitude, lng: position.coords.longitude}
//                    });
//                
//                    //Set marker value on drag
//                    google.maps.event.addListener(marker, 'dragend', function (event) {
//                        var latitude = this.position.lat();
//                        var longitude = this.position.lng();
//                        //Store value
//                        $("#job-map-latitude").prop("value", latitude);
//                        $("#job-map-longitude").prop("value", longitude);
//                    }); 
//                }
            
            }, function() {
                handleLocationError(true, infoWindow, map.getCenter());
            });
        } else {
            // Browser doesn't support Geolocation
            handleLocationError(false, infoWindow, map.getCenter());
        }
    }
    
    function placeMarker(location) {
        if (marker) {
            marker.setPosition(location);
        } else {
            marker = new google.maps.Marker({
                position: location,
                map: map,
                draggable: true
            });            
        }
    }
    
    /*GOOGLE MAPS*/
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?callback=initMap&libraries=places">
</script>
