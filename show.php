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
                    echo'<table id="info">
                        <tr>
                            <th>Địa phương</th>
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