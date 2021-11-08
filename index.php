<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Covid Bac Ninh</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css" />
    <script src="https://openlayers.org/en/v4.6.5/build/ol.js" type="text/javascript"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js" type="text/javascript"></script>
    <style>
        /*
            .map, .righ-panel {
                height: 500px;
                width: 80%;
                float: left;
            }
            */
        .map,
        .righ-panel {
            height: 98vh;
            width: 80vw;
            float: left;
        }

        .map {
            border: 1px solid #000;
        }
    </style>
</head>

<body onload="initialize_map();">
    <table>
        <tr>
            <td>
                <div id="map" class="map"></div>
                <!--<div id="map" style="width: 80vw; height: 100vh;"></div>-->
            </td>
            <td>
                <div id="info"></div>
                <button>Button</button>
            </td>
        </tr>
    </table>
    <?php include 'CMR_pgsqlAPI.php' ?>
    <script>
        var format = 'image/png';
        var map;
        var mapLat=21.110436537668942; //21.110436537668942, 106.1109522568708
        var mapLng = 106.1109522568708;
        var mapDefaultZoom = 11.5;

        function initialize_map() {
            layerBG = new ol.layer.Tile({
                source: new ol.source.OSM({})
            });
            var layerVNM_1 = new ol.layer.Image({
                source: new ol.source.ImageWMS({
                    ratio: 1,
                    url: 'http://localhost:8080/geoserver/covidbn/wms',

                    params: {
                        'FORMAT': format,
                        'VERSION': '1.1.0',
                        'STYLES': '',
                        'LAYERS': 'gadm36_vnm_1'
                    }
                })
            });
            var viewMap = new ol.View({
                center: ol.proj.fromLonLat([mapLng, mapLat]),
                zoom: mapDefaultZoom
            });
            map = new ol.Map({
                target: "map",
                layers: [layerBG, layerVNM_1],
                view: viewMap
            });
            //ad layer vnm_3, coloring
            <?php include('switchLayer.php'); ?>       

            <?php include('loadColor.php'); ?> //to mau tu dong cac huyen
            
            
        };
    </script>
</body>

</html>