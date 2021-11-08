<?php
    if(isset($_POST['functionname']))
    {
        $paPDO = initDB();
        $paSRID = '4326';
        $paPoint = $_POST['paPoint'];
        $functionname = $_POST['functionname'];
        
        $aResult ="ajax";
        if  ($functionname == 'displayMapToAjax')
            $aResult = displayMapToAjax($paPDO, $paSRID, $paPoint);
        elseif($functionname == 'coloringAJAX')
        $aResult = coloringAJAX($paPDO, $paSRID, $paPoint);
        
        echo $aResult;
    
        closeDB($paPDO);
    }

    function initDB()
    {
        // Kết nối CSDL
        $paPDO = new PDO('pgsql:host=Localhost; dbname=covidbn; port=5433', 'postgres', '123');
        return $paPDO;
    }
    function query($paPDO, $paSQLStr)
    {
        try
        {
            // Khai báo exception
            $paPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Sử đụng Prepare 
            $stmt = $paPDO->prepare($paSQLStr);
            // Thực thi câu truy vấn
            $stmt->execute();
            
            // Khai báo fetch kiểu mảng kết hợp
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            
            // Lấy danh sách kết quả
            $paResult = $stmt->fetchAll();   
            return $paResult;                 
        }
        catch(PDOException $e) {
            echo "Thất bại, Lỗi: " . $e->getMessage();
            return null;
        }       
    }
    function closeDB($paPDO)
    {
        // Ngắt kết nối
        $paPDO = null;
    }
    function coloringAJAX($paPDO,$paSRID,$paPoint)
    {
        return "khong chon huyen nao";
       
    }
    function displayMapToAjax($paPDO,$paSRID,$paPoint) {
        $paPoint = str_replace(',', ' ', $paPoint); $mySQLStr1 = "select name_2 from \"gadm36_vnm_2\"  where ST_Within('SRID=".$paSRID.";".$paPoint."'::geometry,geom)";
        $result1 = query($paPDO, $mySQLStr1);
        if($result1!= null){
            $tenhuyen='Luong Tai';
            foreach ($result1 as $huyen){
                $tenhuyen=$huyen['name_2'];
            }
            $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo, color from \"gadm36_vnm_3\" where name_2 like '$tenhuyen' "; //ten cac xa trong huyen
            $result = query($paPDO, $mySQLStr);
            
            if ($result != null)
            {  
               
                $ds="";
                
                foreach($result as $xa){
                $ds=$ds.$xa['geo']."-".$xa['color']."-";
                }
                return $ds;
            }
            else
                return "khong co xa nao";
        }
        else
        return "khong chon huyen nao";
       
    }