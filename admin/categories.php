<?php
require_once 'Classes/PHPExcel.php';
//Đường dẫn file
if(isset($_POST['import'])){
$file = $_FILES['file']['name'];
//Tiến hành xác thực file
$objFile = PHPExcel_IOFactory::identify($file);
$objData = PHPExcel_IOFactory::createReader($objFile);
//Chỉ đọc dữ liệu
$objData->setReadDataOnly(true);
// Load dữ liệu sang dạng đối tượng
$objPHPExcel = $objData->load($file);
//Lấy ra số trang sử dụng phương thức getSheetCount();
// Lấy Ra tên trang sử dụng getSheetNames();
//Chọn trang cần truy xuất
$sheet = $objPHPExcel->setActiveSheetIndex(0);
//Lấy ra số dòng cuối cùng
$Totalrow = $sheet->getHighestRow();
//Lấy ra tên cột cuối cùng
$LastColumn = $sheet->getHighestColumn();
//Chuyển đổi tên cột đó về vị trí thứ, VD: C là 3,D là 4
$TotalCol = PHPExcel_Cell::columnIndexFromString($LastColumn);
//Tạo mảng chứa dữ liệu
$data = [];
//Tiến hành lặp qua từng ô dữ liệu
//----Lặp dòng, Vì dòng đầu là tiêu đề cột nên chúng ta sẽ lặp giá trị từ dòng 2
for ($i = 2; $i <= $Totalrow; $i++) {
    //----Lặp cột
    for ($j = 0; $j < $TotalCol; $j++) {
        // Tiến hành lấy giá trị của từng ô đổ vào mảng
        $data[$i - 2][$j] = $sheet->getCellByColumnAndRow($j, $i)->getValue();
    }
}
//Hiển thị mảng dữ liệu
foreach ($data as $item){
    try{
        $paPDO = new PDO('pgsql:host=Localhost; dbname=covidbn; port=5432', 'postgres', '123');
    }
    catch(PDOException $e) {
        echo "Thất bại, Lỗi: " . $e->getMessage();
        return null;
    }
    $ca_benh = $item[2];
    $color=$item[3];
    $xa = $item[1];
    if($ca_benh!=""&&$color!=""&&$xa!=""){
    $paSQLStr ="UPDATE gadm36_vnm_3 SET ca_benh = $ca_benh,  color = '$color'  WHERE gadm36_vnm_3.name_3 = '$xa'";
        $paPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $paPDO->prepare($paSQLStr);
        $stmt->execute(); 
    }
}
}
?>
<?php
if(isset($_POST['submit']) && $_POST['ca_benh']!=""){
    try{
        $paPDO = new PDO('pgsql:host=Localhost; dbname=covidbn; port=5432', 'postgres', '123');
    }
    catch(PDOException $e) {
        echo "Thất bại, Lỗi: " . $e->getMessage();
        return null;
    }
    $ca_benh=$_POST['ca_benh'];
    $xa=$_POST['customerWard'];

    if($_POST['color']!=""){
        $color = $_POST['color'];
        $paSQLStr ="UPDATE gadm36_vnm_3 SET ca_benh = $ca_benh,  color = '$color'  WHERE gadm36_vnm_3.name_3 = '$xa'";
        $paPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $paPDO->prepare($paSQLStr);
        $stmt->execute(); 
}else{
    $paSQLStr ="UPDATE gadm36_vnm_3 SET ca_benh = $ca_benh WHERE gadm36_vnm_3.name_3 = '$xa'";
    $paPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $paPDO->prepare($paSQLStr);
    $stmt->execute(); 
}
}
?>
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
<body>
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
                    try{
                        $paPDO = new PDO('pgsql:host=Localhost; dbname=covidbn; port=5432', 'postgres', '123');
                    }
                    catch(PDOException $e) {
                        echo "Thất bại, Lỗi: " . $e->getMessage();
                        return null;
                    }
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
    <script>
    function myFunction(str) {
        console.log(str);
        if(window.XMLHttpRequest){
            xmlhttp = new XMLHttpRequest();
        
        }else{
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");

        }
        xmlhttp.onreadystatechange = function(){
            if(this.readyState==4 && this.status==200){
                document.getElementById('customerWard').innerHTML=this.responseText;
            }
        }
        xmlhttp.open("GET", "helpre.php?value="+str, true);
        xmlhttp.send();
}
</script>          
</body>
</html>