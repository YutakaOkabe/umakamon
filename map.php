<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>うまかもんマップ</title>

    <!-- <script src="http://maps.google.com/maps/api/js?key=AIzaSyDkEPvm0e8uG5qDm4LgCEx57ODRxCSSlUI&language=ja"></script> -->

<!-- <style>
html { height: 100% }
body { height: 100% }
#map { height: 100%; width: 100%}
</style> -->

<style>
/* マップを表示する div 要素の高さを必ず明示的に指定します。 */
#map {
    height: 400px;
    width: 600px;
}
</style>

</head>
<body>

<div id="map"></div><!-- 地図を表示する div 要素（id="map"）-->
    <script>
    var map;
    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: -34.397, lng: 150.644},
        zoom: 8
        });
    }
    </script> 
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDkEPvm0e8uG5qDm4LgCEx57ODRxCSSlUI&callback=initMap" async defer></script>

<!-- <div id="map"></div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDkEPvm0e8uG5qDm4LgCEx57ODRxCSSlUI"></script>
    <script>
    function map_canvas() {
        var data = new Array();
        data.push({
            lat: '34.987578', //緯度
            lng: '135.747720', //経度
            content: '京都水族館' //情報ウィンドウ
        });
        var latlng = new google.maps.LatLng(data[0].lat, data[0].lng);
        var opts = {
            zoom: 16,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map"), opts);
        var markers = new Array();
        for (i = 0; i < data.length; i++) {
            markers[i] = new google.maps.Marker({
                position: new google.maps.LatLng(data[i].lat, data[i].lng),
                map: map
            });
            markerInfo(markers[i], data[i].content);
        }
    }
    function markerInfo(marker, name) {
        new google.maps.InfoWindow({
            content: name
        }).open(marker.getMap(), marker);
    }
    google.maps.event.addDomListener(window, 'load', map_canvas);
    </script> -->

<!-- <div id="map"></div>
<script>
var MyLatLng = new google.maps.LatLng(35.6811673, 139.7670516);
var Options = {
 zoom: 15,      //地図の縮尺値
 center: MyLatLng,    //地図の中心座標
 mapTypeId: 'roadmap'   //地図の種類
};
var map = new google.maps.Map(document.getElementById('map'), Options);
</script> -->


</body>
</html>