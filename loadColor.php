$.ajax({
                    type: "POST",
                    url: "covidBN_API.php",
                    data: {
                        functionname: 'coloringAJAX',
                    },
                    success: function(result, status, erro) {
                        ar=[];
                         ar = result.split('-');
                        for(var i = 0; i < ar.length-1; i+=2){
                            //alert(ar[i]);
                            if(ar[i+1]!='')
                            coloringLayer(ar[i],ar[i+1]);
                        }
                        
                        
                    },
                    error: function(req, status, error) {
                        alert(req + " " + status + " " + error);
                    }
                });
               