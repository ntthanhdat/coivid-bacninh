function loadData(result){
    $("#info").html(result);
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
                        functionname: 'loadDataAJAX',
                        paPoint: myPoint
                    },
                    success: function(result, status, erro) {
                        //alert("hi");
                        loadData(result);
                        
                    },
                    error: function(req, status, error) {
                        alert(req + " " + status + " " + error);
                    }
                });

            });