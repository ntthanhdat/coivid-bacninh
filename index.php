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
    <script>
       
        var format = 'image/png';
        var map;
        var mapLat = 21.174342976614675;
        var mapLng = 106.06795881323355;
        var mapDefaultZoom = 11.5;

        function initialize_map() {
            layerBG = new ol.layer.Tile({
                source: new ol.source.OSM({})
            });
            var layerVNM_2 = new ol.layer.Image({
                source: new ol.source.ImageWMS({
                    ratio: 1,
                    url: 'http://localhost:8080/geoserver/covidbn/wms',

                    params: {
                        'FORMAT': format,
                        'VERSION': '1.1.0',
                        'STYLES': '',
                        'LAYERS': 'gadm36_vnm_2'
                    }
                })
            });
            var viewMap = new ol.View({
                center: ol.proj.fromLonLat([mapLng, mapLat]),
                zoom: mapDefaultZoom
            });
            map = new ol.Map({
                target: "map",
                layers: [layerBG, layerVNM_2],
                view: viewMap
            });
            var styles = {
                'MultiPolygon': new ol.style.Style({
                    stroke: new ol.style.Stroke({
                        color: 'yellow',
                        width: 2
                    })
                })
            };

            // show info
            var styleFunction = function(feature) {
                return styles[feature.getGeometry().getType()];
            };
            var vectorLayer = new ol.layer.Vector({
                style: styleFunction
            });
            map.addLayer(vectorLayer);

            function createJsonObj(result) {
                var geojsonObject = '{' +
                    '"type": "FeatureCollection",' +
                    '"crs": {' +
                    '"type": "name",' +
                    '"properties": {' +
                    '"name": "EPSG:4326"' +
                    '}' +
                    '},' +
                    '"features": [{' +
                    '"type": "Feature",' +
                    '"geometry": ' + result +
                    '}]' +
                    '}';
                return geojsonObject;
            }
            

           <?php include('switchLayer.php'); ?>
        };
        //});
    </script>
</body>

</html>