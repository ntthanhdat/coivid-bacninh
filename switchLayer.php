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
                //alert("result: " + result);
                var strObjJson = createJsonObj(result);
                //alert(strObjJson);
                var objJson = JSON.parse(strObjJson);
                //alert(JSON.stringify(objJson)); //test
                drawGeoJsonObj(objJson);
                //highLightGeoJsonObj(objJson);
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
                    url: "covidBN_API.php",
                    //dataType: 'json',
                    //data: {functionname: 'reponseGeoToAjax', paPoint: myPoint},
                    data: {
                        functionname: 'diplayMapToAjax',
                        paPoint: myPoint
                    },
                    success: function(result, status, erro) {
                        //displayObjInfo(result);
                        //highLightObj(result);
                        //alert(result);
                        //alert(typeof(result));
                        
                        ar=[];
                         ar = result.split('-');
                        for(var i = 0; i < ar.length; i++){
                            //alert(ar[i]);
                            highLightObj(ar[i]);
                        }
                        
                        
                    },
                    error: function(req, status, error) {
                        alert(req + " " + status + " " + error);
                    }
                });
                //*/
            });