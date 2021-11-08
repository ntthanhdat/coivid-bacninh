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


            function highLightObj(result) {
                var strObjJson = createJsonObj(result);
                var objJson = JSON.parse(strObjJson);
                drawGeoJsonObj(objJson);
            }
            map.on('singleclick', function(evt) {
                //alert("coordinate org: " + evt.coordinate);
                //var myPoint = 'POINT(12,5)';
                var lonlat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
                var lon = lonlat[0];
                var lat = lonlat[1];
                var myPoint = 'POINT(' + lon + ' ' + lat + ')';

                $.ajax({
                    type: "POST",
                    url: "covidBN_API.php",
                    data: {
                        functionname: 'diplayMapToAjax',
                        paPoint: myPoint
                    },
                    success: function(result, status, erro) {
                        ar=[];
                         ar = result.split('-');
                        for(var i = 0; i < ar.length-1; i++){
                            if(ar[i]!='');
                            highLightObj(ar[i]);
                        }
                        
                        
                    },
                    error: function(req, status, error) {
                        alert(req + " " + status + " " + error);
                    }
                });
                //*/
            });