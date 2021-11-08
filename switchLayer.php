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
  
            function R_drawGeoJsonObj(paObjJson) {
                var vectorSource = new ol.source.Vector({
                    features: (new ol.format.GeoJSON()).readFeatures(paObjJson, {
                        dataProjection: 'EPSG:4326',
                        featureProjection: 'EPSG:3857'
                    })
                });
                var styles  = {
                    'MultiPolygon': new ol.style.Style({
                        fill: new ol.style.Fill({
                            color: 'red',
                            
                        }),
                        stroke: new ol.style.Stroke({
                            color: '#660000', 
                            width: 2
                        })
                    })
                };
                var styleFunction = function (feature) {
                    return styles[feature.getGeometry().getType()];
                };
                var vectorLayer = new ol.layer.Vector({
                    source: vectorSource,
                    style: styleFunction,
                });
                map.addLayer(vectorLayer);
            }
            function G_drawGeoJsonObj(paObjJson) {
                var vectorSource = new ol.source.Vector({
                    features: (new ol.format.GeoJSON()).readFeatures(paObjJson, {
                        dataProjection: 'EPSG:4326',
                        featureProjection: 'EPSG:3857'
                    })
                });
                var styles  = {
                    'MultiPolygon': new ol.style.Style({
                        fill: new ol.style.Fill({
                            color: 'green',
                            
                        }),
                        stroke: new ol.style.Stroke({
                            color: '#001a00', 
                            width: 2
                        })
                    })
                };
                var styleFunction = function (feature) {
                    return styles[feature.getGeometry().getType()];
                };
                var vectorLayer = new ol.layer.Vector({
                    source: vectorSource,
                    style: styleFunction,
                });
                map.addLayer(vectorLayer);
            }
            function Y_drawGeoJsonObj(paObjJson) {
                var vectorSource = new ol.source.Vector({
                    features: (new ol.format.GeoJSON()).readFeatures(paObjJson, {
                        dataProjection: 'EPSG:4326',
                        featureProjection: 'EPSG:3857'
                    })
                });
                var styles  = {
                    'MultiPolygon': new ol.style.Style({
                        fill: new ol.style.Fill({
                            color: 'yellow',
                            
                        }),
                        stroke: new ol.style.Stroke({
                            color: 'orange', 
                            width: 2
                        })
                    })
                };
                var styleFunction = function (feature) {
                    return styles[feature.getGeometry().getType()];
                };
                var vectorLayer = new ol.layer.Vector({
                    source: vectorSource,
                    style: styleFunction,
                });
                map.addLayer(vectorLayer);
            }


            function highLightObj(result) {
                var strObjJson = createJsonObj(result);
                var objJson = JSON.parse(strObjJson);
                drawGeoJsonObj(objJson);
            }
            
            function coloringLayer(result, color) {
                var strObjJson = createJsonObj(result);
                var objJson = JSON.parse(strObjJson);
                if(color=='R'){
                    R_drawGeoJsonObj(objJson);
                }else if(color=='G'){
                    G_drawGeoJsonObj(objJson);
                }else 
                Y_drawGeoJsonObj(objJson);
                
            }
            map.on('singleclick', function(evt) {
                var lonlat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
                var lon = lonlat[0];
                var lat = lonlat[1];
                var myPoint = 'POINT(' + lon + ' ' + lat + ')';
                //*

                $.ajax({
                    type: "POST",
                    url: "covidBN_API.php",
                    data: {
                        functionname: 'displayMapToAjax',
                        paPoint: myPoint
                    },
                    success: function(result, status, erro) {
                        ar=[];
                         ar = result.split('-');
                        for(var i = 0; i < ar.length; i+=2){
                            //alert(ar[i]);
                            if(ar[i+1]!='')
                            coloringLayer(ar[i],ar[i+1]);
                        }
                        
                        
                    },
                    error: function(req, status, error) {
                        alert(req + " " + status + " " + error);
                    }
                });
            });