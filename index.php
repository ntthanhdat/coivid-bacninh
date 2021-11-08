<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>OpenStreetMap &amp; OpenLayers - Marker Example</title>
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
        #info
        {
            position: relative;
            
        }
    </style>
</head>

<body onload="initialize_map();">
    <div id="popup" class="ol-popup">
        <a href="#" id="popup-closer" class="ol-popup-closer"></a>
        <div id="popup-content"></div>
    </div>
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
        var container = document.getElementById('popup');
        var content = document.getElementById('popup-content');
        var closer = document.getElementById('popup-closer');
        //$("#document").ready(function () {
        var format = 'image/png';
        var map;
        var mapLat = 21.174342976614675;
        var mapLng = 106.06795881323355;
        var mapDefaultZoom = 11.5;
        //Khởi tạo openlayer
        function initialize_map() {
            layerBG = new ol.layer.Tile({
                source: new ol.source.OSM({})
            });
            // Khai báo lớp 1*/
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
                //projection: projection
            });
            //Khai báo lớp 3
            var layerVNM_3 = new ol.layer.Image({
                source: new ol.source.ImageWMS({
                    ratio: 1,
                    url: 'http://localhost:8080/geoserver/covidbn/wms',

                    params: {
                        'FORMAT': format,
                        'VERSION': '1.1.0',
                        'STYLES': '',
                        'LAYERS': 'gadm36_vnm_3'
                    }
                })
            });
            var viewMap = new ol.View({
                center: ol.proj.fromLonLat([mapLng, mapLat]),
                zoom: mapDefaultZoom
                //projection: projection
            });
            //Hiển thị map
            map = new ol.Map({
                target: "map",
                layers: [layerBG, layerVNM_2],
                //layers: [layerCMR_adm1],
                view: viewMap
            });

            //map.getView().fit(bounds, map.getSize());

            var styles = {
                'MultiPolygon': new ol.style.Style({
                    stroke: new ol.style.Stroke({
                        color: 'yellow',
                        width: 3
                    })
                })
            };

            // show info
            var styleFunction = function(feature) {
                return styles[feature.getGeometry().getType()];
            };
            var vectorLayer = new ol.layer.Vector({
                //source: vectorSource,
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

            function myFunction() {
                var popup = document.getElementById("popup");
                popup.classList.toggle("show");
            }

            function displayObjInfo(result, coordinate) {
                $("#popup-content").html(result);
                overlay.setPosition(coordinate);

            }

            function drawGeoJsonObj(paObjJson) {
                var vectorSource = new ol.source.Vector({
                    features: (new ol.format.GeoJSON()).readFeatures(paObjJson, {
                        dataProjection: 'EPSG:4326',
                        featureProjection: 'EPSG:3857'
                    })
                });
                var vectorLayer = new ol.layer.Vector({
                    source: vectorSource
                });
                map.addLayer(vectorLayer);
            }

            function displayObjInfo(result, coordinate) {
                //alert("result: " + result);
                //alert("coordinate des: " + coordinate);
                $("#info").html(result);
            }

            function highLightGeoJsonObj(paObjJson) {
                var vectorSource = new ol.source.Vector({
                    features: (new ol.format.GeoJSON()).readFeatures(paObjJson, {
                        dataProjection: 'EPSG:4326',
                        featureProjection: 'EPSG:3857'
                    })
                });
                vectorLayer.setSource(vectorSource);

                var vectorLayer = new ol.layer.Vector({
                    source: vectorSource
                });
                map.addLayer(vectorLayer);

            }

            function highLightObj(result) {
                //alert("result: " + result);
                var strObjJson = createJsonObj(result);
                //alert(strObjJson);
                var objJson = JSON.parse(strObjJson);
                //alert(JSON.stringify(objJson));
                //drawGeoJsonObj(objJson);
                highLightGeoJsonObj(objJson);
            }
            map.on('singleclick', function(evt) {
                //alert("coordinate org: " + evt.coordinate);
                //var myPoint = 'POINT(12,5)';
                var lonlat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
                var lon = lonlat[0];
                var lat = lonlat[1];
                var myPoint = 'POINT(' + lon + ' ' + lat + ')';
                //alert("myPoint: " + myPoint);
                //*


                $.ajax({
                    type: "POST",
                    url: "CMR_pgsqlAPI.php",
                    //dataType: 'json',
                    //data: {functionname: 'reponseGeoToAjax', paPoint: myPoint},
                    data: {
                        functionname: 'getInfoCMRToAjax',
                        paPoint: myPoint
                    },
                    success: function(result, status, erro) {
                        // alert(displayObjInfo(result,evt.coordinate));
                        displayObjInfo(result, evt.coordinate);
                        // highLightGeoJsonObj(result);
                    },
                    error: function(req, status, error) {
                        alert(req + " " + status + " " + error);
                    }
                });
                // $.ajax({
                //     type: "POST",
                //     url: "CMR_pgsqlAPI.php",
                //     //dataType: 'json',
                //     //data: {functionname: 'reponseGeoToAjax', paPoint: myPoint},
                //     data: {
                //         functionname: 'getGeoDistrictCMRToAjax',
                //         paPoint: myPoint
                //     },
                //     success: function(result, status, erro) {
                //         displayObjInfo(result, evt.coordinate);
                //         highLightGeoJsonObj(result);
                //     },
                //     error: function(req, status, error) {
                //         alert(req + " " + status + " " + error);
                //     }
                // });



                //*/
            });

            //Thêm sự kiện khi di chuyển đến vị trí Huyện

        };
        //});
    </script>
</body>

</html>