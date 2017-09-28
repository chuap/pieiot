@extends('tp/enl') 

<?php
$tp = asset('themes/mdl');

?>
@section('page_title')

@stop
@section('body')
<style>
    #map {
        width: 100%;
        height: 400px;
    }
</style>   
<div class="row margin-vert-30">

    <h2 class="mb2">
        ติดต่อเรา
        
    </h2>

    <div class="col-md-9">
        <div id="map" class="shadow"></div>
    </div>
    <div class="col-md-3">
        
    </div>
</div>
@stop
@section('foot')


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD55soC1qSaKDPLgxItuhBHN0ezrnGzpvM" ></script>
<script>    
    function initMap() {
// Create a map object and specify the DOM element for display.
        var myLatLng = {lat: 13.867286, lng: 100.518334};
        var map = new google.maps.Map(document.getElementById('map'), {
            center: myLatLng,
            scrollwheel: true,
            zoom: 18,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        var marker = new google.maps.Marker({
            position: myLatLng,
            map: map,
            title: 'HOME',
            label: 'X'
        });
    }
    google.maps.event.addDomListener(window, 'load', initMap);
</script>
@stop