<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Covid Bac Ninh</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css" />
    <script src="https://openlayers.org/en/v4.6.5/build/ol.js" type="text/javascript"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="index.css">
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
    <div class="m_header">
        <img src="https://bandocovid.bacninh.gov.vn/images/bn.png" alt="">
        <p>BẢN ĐỒ CẬP NHẬT DỊCH BỆNH COVID-19 TỈNH BẮC NINH</p>
        <a href="admin/">Admin</a>
    </div>
    <table>
        <tr>
            <td class="row1">

                <div class="col1" id="map" style="width: 77vw; height: 88vh;"></div>
                <div class="col2">
                    <p>TỈNH BẮC NINH</p>
                    <?php
                    try{
                        $paPDO = new PDO('pgsql:host=Localhost; dbname=covidbn; port=5433', 'postgres', '123');
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
            </td>

        </tr>
    </table>
    <div class="footer">
        <p>Dữ liệu được cung cấp bởi CDC Bắc Ninh</p>
        <p>Phát triển bởi Nguyễn Thế Thành Đạt</p>
    </div>
    <script>
        var format = 'image/png';
        var map;
        var mapLat = 21.110436537668942; //21.110436537668942, 106.1109522568708
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