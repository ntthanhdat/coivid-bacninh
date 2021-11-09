<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bản đồ Covid tỉnh Bắc Ninh</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css" />
    <script src="https://openlayers.org/en/v4.6.5/build/ol.js" type="text/javascript"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="index.css">
</head>
<body onload="initialize_map();">
    <div class="m_header">
        <img src="https://bandocovid.bacninh.gov.vn/images/bn.png" alt="">
        <p>BẢN ĐỒ CẬP NHẬT DỊCH BỆNH COVID-19 TỈNH BẮC NINH</p>
        <p>Hotline: <span>0965 411 919</span></p>
        <a href="admin/">Admin</a>
    </div>
    <table>
        <tr>
            <td class="row1">
                <div class= "col1" id="map" style="width: 77vw; height: 88vh;"></div>
                <div class="col2">
                    <p>TỈNH BẮC NINH</p>
                    <?php
                    try{
                        $paPDO = new PDO('pgsql:host=Localhost; dbname=covidbn; port=5432', 'postgres', '123');
                    }
                    catch(PDOException $e) {
                        echo "Thất bại, Lỗi: " . $e->getMessage();
                        return null;
                    }
                    $paSQLStr ="SELECT sum(gadm36_vnm_3.ca_benh) as ca_benh from gadm36_vnm_3";
                    $paSQLStr1 ="SELECT gadm36_vnm_3.name_2,sum(gadm36_vnm_3.ca_benh) as  ca_benh from gadm36_vnm_3 GROUP BY gadm36_vnm_3.name_2";
                    $paPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = $paPDO->prepare($paSQLStr);
                    $stmt->execute(); 
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $paResult = $stmt->fetchAll();   
                    if ($paResult != null)
                    {
                        foreach ($paResult as $item){
                            echo '<p class="ca_benh">Tỉnh Bắc Ninh: '.$item['ca_benh'].' ca bệnh</h2>
                            <p class="link">Chi tiết tình hình covid-19 tại Bắc Ninh <a href="https://bandocovid.bacninh.gov.vn/thong-tin">Tại đây</a></p> ';
                        }
                    }
                    $paPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt1 = $paPDO->prepare($paSQLStr1);
                    $stmt1->execute(); 
                    $stmt1->setFetchMode(PDO::FETCH_ASSOC);
                    $paResult1 = $stmt1->fetchAll();  
                    echo'<table>
                        <tr>
                            <th>Đại phương</th>
                            <th>Ca bệnh</th>
                        </tr>
                        ';
                        if ($paResult1 != null)
                    {
                        foreach ($paResult1 as $item){
                            echo '<tr>
                            <td>'.$item['name_2'].'</td>
                                  <td>'.$item['ca_benh'].'</td>
                                  </tr>';
                        }
                    }
                   echo'</table>';
                ?>   
                    
                </div>
            </td>
        </tr>
    </table>
    <div class="footer">
        <p>Dữ liệu được cung cấp bởi CDC Bắc Ninh</p>
        <p>Phát triển bởi Trung tâm Công nghệ thông tin và Truyền thông tỉnh Bắc Ninh</p>
    </div>
    <script>
        var format = 'image/png';
        var map;
        var mapLat=21.110436537668942; //21.110436537668942, 106.1109522568708
        var mapLng = 106.1109522568708;
        var mapDefaultZoom = 11.5;
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

                function createJsonObj(result) {                    
                    var geojsonObject = '{'
                            + '"type": "FeatureCollection",'
                            + '"crs": {'
                                + '"type": "name",'
                                + '"properties": {'
                                    + '"name": "EPSG:4326"'
                                + '}'
                            + '},'
                            + '"features": [{'
                                + '"type": "Feature",'
                                + '"geometry": ' + result
                            + '}]'
                        + '}';
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
                function displayObjInfo(result, coordinate)
                {
                    //alert("result: " + result);
                    //alert("coordinate des: " + coordinate);
					$("#info").html(result);
                }
                map.on('singleclick', function (evt) {
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
                        data: {functionname: 'getInfoCMRToAjax', paPoint: myPoint},
                        success : function (result, status, erro) {
                            displayObjInfo(result, evt.coordinate );
                        },
                        error: function (req, status, error) {
                            alert(req + " " + status + " " + error);
                        }
                    });
                    //*/
                });
    };
    </script>
</body>

</html>