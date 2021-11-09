<?php
try{
    $paPDO = new PDO('pgsql:host=Localhost; dbname=covidbn; port=5432', 'postgres', '123');
                    }
                    catch(PDOException $e) {
                        echo "Thất bại, Lỗi: " . $e->getMessage();
                        return null;
                    }
                    $val = $_GET["value"];
                    // echo $val;
                    $paSQLStr ="SELECT gadm36_vnm_3.name_3 FROM gadm36_vnm_3 where gadm36_vnm_3.name_2='$val'";
                    $paPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = $paPDO->prepare($paSQLStr);
                    $stmt->execute(); 
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $paResult = $stmt->fetchAll();   
                    if ($paResult != null)
                    {   echo '<option>Phường/Xã</option>';
                        echo "<select>";
                        foreach ($paResult as $item){
                            echo '<option>'.$item['name_3'].'
                            </option>';
                        }
                        echo"</select>" ;  
                    }
                ?>    