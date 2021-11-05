<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Bản đồ Covid tỉnh Bắc Ninh</title>
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
    <script>
        var format = 'image/png';
        var map;
        var mapLat=21.174342976614675;
        var mapLng =106.06795881323355;
        var mapDefaultZoom = 12;
        var vectorSource = new ol.source.Vector({
features: (new
ol.format.GeoJSON()).readFeatures(ObjJson, {
dataProjection: 'EPSG:4326',
featureProjection: 'EPSG:3857'
})
});
        function initialize_map() {
            //*
            layerBG = new ol.layer.Tile({
                source: new ol.source.OSM({})
            });
            //*/
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
                        map = new ol.Map({
                            target: "map",
                            layers: [layerBG, layerVNM_1, layerVNM_2, layerVNM_3],
                            view: viewMap
                        });
                        var styles = {
                       'MultiPolygon': new ol.style.Style({
                        fill: new ol.style.Fill({
                            color: 'orange'
                        }),
                        stroke: new ol.style.Stroke({
                            color: 'yellow', 
                            width: 2
                        })
                    })
                };
                var styleFunction = function (feature) {
                    return styles[feature.getGeometry().getType()];
                };
                var vectorLayer = new ol.layer.Vector({
                    //source: vectorSource,
                    style: styleFunction
                });
                map.addLayer(vectorLayer);

    };
    </script>
</body>

</html>