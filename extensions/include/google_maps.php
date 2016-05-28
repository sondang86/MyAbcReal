<?php
    if(!defined('IN_SCRIPT')) die("");
?>
<label id="map"></label>
<div class="note"><strong>Gợi ý:</strong> Chọn địa điểm làm việc giúp ứng viên có thể tìm đến dễ dàng hơn.</div>
<input type="hidden" id="job-map-latitude" name="job_map_latitude" value="<?php echo $latitude;?>">
<input type="hidden" id="job-map-longitude" name="job_map_longitude" value="<?php echo $longitude;?>">

<script>
    /*GOOGLE MAPS*/
    var marker;    
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 13,
            center: {lat: <?php echo $latitude;?>, lng: <?php echo $longitude;?>}
        });
        
        marker = new google.maps.Marker({
            map: map,
            draggable: true,
            animation: google.maps.Animation.DROP,
            position: {lat: <?php echo $latitude;?>, lng: <?php echo $longitude;?>}
        });
        
        //Add listener
        google.maps.event.addListener(marker, 'dragend', function (event) {
            var latitude = this.position.lat();
            var longitude = this.position.lng();
            //Store value
            $("#job-map-latitude").prop("value", latitude);
            $("#job-map-longitude").prop("value", longitude);
        }); //end addListener
    }
    /*GOOGLE MAPS*/
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?callback=initMap">
</script>