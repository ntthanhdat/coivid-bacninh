
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="categories.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<?php
include('../config.php');

?>
<main class="container clearfix pt-3">
<div class="cgr__wrap">
    <h2>QUẢN LÝ THÔNG TIN CA BỆNH TỈNH BẮC NINH</h2>
        <div class="cgr__col1">
            <table>
                <tr>
                    <th>Xã</th>
                    <th>Quận/Huyện/Thành Phố</th>
                    <th>Tỉnh</th>
                    <th>Ca bệnh</th>
                    <th>Color</th>
                </tr>
                <?php
                   include ('../config.php');
                    $paSQLStr ="SELECT gadm36_vnm_3.name_1,gadm36_vnm_3.name_2,gadm36_vnm_3.name_3,gadm36_vnm_3.ca_benh,gadm36_vnm_3.color FROM gadm36_vnm_3";
                    $paPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = $paPDO->prepare($paSQLStr);
                    $stmt->execute(); 
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $paResult = $stmt->fetchAll();   
                    if ($paResult != null)
                    {
                        foreach ($paResult as $item){
                            echo '<tr>
                                    <td>'.$item['name_3'].'</td>
                                    <td>'.$item['name_2'].'</td>
                                    <td>'.$item['name_1'].'</td>
                                    <td>'.$item['ca_benh'].'</td>
                                    <td>'.$item['color'].'</td>  
                                 </tr>';
                        }
                    }
                ?>    
            </table>
        </div>
        <h3>Cập Nhật Ca Bệnh Các Xã</h3>
         <form action="#" method="post" enctype="multipart/form-data">
             <div>
            <select onchange="myFunction(this.value);" class="form-control" id="customerDistrict" name="customerDistrict">
                <option value="0">Quận/ Huyện</option>
                <option value="Gia Bình">Gia Bình</option>
                <option value="Lương Tài">Lương Tài</option>
                <option value="Quế Võ">Quế Võ</option>
                <option value="Thuận Thành">Thuận Thành</option>
                <option value="Tiên Du">Tiên Du</option>
                <option value="Yên Phong">Yên Phong</option>
                <option value="Bắc Ninh">Bắc Ninh</option>
                <option value="Từ Sơn">Từ Sơn</option>
            </select>
            <select class="form-control" id="customerWard" name="customerWard">
                <option value="0">Phường/ Xã</option>
            </select>
            </div>
            <label for="cabenh">Nhập số ca bệnh:</label>
            <input type="number" name="ca_benh"  class="form-control" placeholder="Số ca bệnh" id="cabenh">   
            <label for="color" class="l__color">Chọn màu:</label>
            <select class="form-control" name="color" id="color">
                <option value="">Chọn màu</option>
                <option value="G">Green</option>
                <option value="R">Red</option>
                <option value="B">Blue</option>
            </select>  
            <button type="submit" class="btn btn-primary" name="submit">Lưu</button>
            <h3>Import file excel</h3>
            <input type="file" name="file"  class="form-control" id="file">   
            <button type="submit" class="btn btn-primary" name="import">Import</button>
        </form>
    </div>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <form action="process-upload.php" method="post" enctype="multipart/form-data">

                
                    <div class="form-group">
                        <span class="btn btn-default btn-file">
                            <input type="file" name="file" id="imgInp">
                        </span>
                    </div>

                <button type="submit" name="submit" class="btn btn-primary">Lưu</button>
            </form>

        </div>
    </div>
</main>
</body>
</html>