<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>OpenStreetMap &amp; OpenLayers - Marker Example</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css" />
    <script src="https://openlayers.org/en/v4.6.5/build/ol.js" type="text/javascript"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js" type="text/javascript"></script>

</head>

<body onload="initialize_map();">
    <table>
        <tr>
            <td>
                <div id="map" style="width: 80vw; height: 100vh;"></div>
            </td>
            <td>
                <button>Button</button>
            </td>
        </tr>
    </table>
    <?php include 'CMR_pgsqlAPI.php' ?>

    <script>
        var format = 'image/png';
        var map;
        var mapLat = 21.174342976614675;
        var mapLng = 106.06795881323355;
        var mapDefaultZoom = 11.5;
        /*
        var vectorSource = new ol.source.Vector({
            features: (new ol.format.GeoJSON()).readFeatures(ObjJson, {
                dataProjection: 'EPSG:4326',
                featureProjection: 'EPSG:3857'
            })
        });
*/
        function initialize_map() {
            //*
            layerBG = new ol.layer.Tile({
                source: new ol.source.OSM({})
            });
            // khai báo layer 1*/
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
            // Khai báo layer 2
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
            // Khai báo layer 3
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
            // Xác định view
            var viewMap = new ol.View({
                center: ol.proj.fromLonLat([mapLng, mapLat]),
                zoom: mapDefaultZoom
                //projection: projection
            });
            // Xác định map
            map = new ol.Map({
                target: "map",
                layers: [layerBG, layerVNM_1,layerVNM_2,layerVNM_3],
                //layers: [layerCMR_adm1],
                view: viewMap
            });
            //map.getView().fit(bounds, map.getSize());

            var styles = {
                'MultiPolygon': new ol.style.Style({
                    fill: new ol.style.Fill({
                        color: 'orange'
                    }),
                    stroke: new ol.style.Stroke({
                        color: 'red',
                        width: 2
                    })
                })
            };
            var styleFunction = function(feature) {
                return styles[feature.getGeometry().getType()];
            };
            
            var vectorLayer = new ol.layer.Vector({
                // source: vectorSource,
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

            function highLightGeoJsonObj(paObjJson) {
                var vectorSource = new ol.source.Vector({
                    features: (new ol.format.GeoJSON()).readFeatures(paObjJson, {
                        dataProjection: 'EPSG:4326',
                        featureProjection: 'EPSG:3857'
                    })
                });
                vectorLayer.setSource(vectorSource);
                /*
                var vectorLayer = new ol.layer.Vector({
                    source: vectorSource
                });
                map.addLayer(vectorLayer);
                */
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
                //alert("coordinate: " + evt.coordinate);
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
                    // highLightObj(result);
                    data: {
                        functionname: 'getGeoCMRToAjax',
                        paPoint: myPoint
                    },
                    success: function(result, status, erro) {
                        highLightObj(result);
                    },
                    error: function(req, status, error) {
                        alert(req + " " + status + " " + error);
                    }
                });

                

            }



              );


        };
    </script>
</body>

</html>