<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <script src="main.js"></script>
</head>
<body>
    <div class="admin__wrap">
    <div class="admin__container">
        <p>Admin</p>
        <div class="user__container">
            <img src="https://thumbs.dreamstime.com/b/admin-sign-laptop-icon-stock-vector-166205404.jpg" alt="">
            <p class="u__name">Nhóm 2 HTTT Địa Lý</p>
        </div>
        <div class="a__menu">
            <ul>
                <li><a href=""><i class="fas fa-tachometer-alt"></i> Trang chủ</a></li>
                <li><a href=""><i class="fas fa-paste"></i> <a href="categories.php">Quản lý dữ liệu</a></a></li>
                <li><a href=""><i class="fas fa-paste"></i> <a href="https://bacninh.gov.vn/phong-chong-dich-benh-corona">Bài viết</a></a></li>
                <li><a href=""><i class="fas fa-paste"></i> Thành viên</a></li>
            </ul>
        </div>
    </div>
    <div class="admin__wrap_r">
        <p>TRANG CHỦ</p>
        <?php
                    try{
                        $paPDO = new PDO('pgsql:host=Localhost; dbname=covidbn; port=5432', 'postgres', '123');
                    }
                    catch(PDOException $e) {
                        echo "Thất bại, Lỗi: " . $e->getMessage();
                        return null;
                    }
                    $paSQLStr ="SELECT sum(gadm36_vnm_3.ca_benh) as ca_benh from gadm36_vnm_3";
                    $paPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $stmt = $paPDO->prepare($paSQLStr);
                    $stmt->execute(); 
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $paResult = $stmt->fetchAll();   
                    if ($paResult != null)
                    {
                        foreach ($paResult as $item){
                            echo '<h2>Tỉnh Bắc Ninh: '.$item['ca_benh'].' ca bệnh</h2>';
                        }
                    }
                ?>    
        
    <div class="admin__container_r">
        
        <div class="cm">
            <p>02</p>
            <span>QUẢN LÝ DỮ LIỆU CA BỆNH
            </span><br/>
            <a href="categories.php">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
        </div>
        <div class="bv">
            <p>bacninh.gov.vn</p>
            <span>BÀI VIẾT</span><br/>
            <a href="https://bacninh.gov.vn/phong-chong-dich-benh-corona">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
        </div>
        <div class="tv">
            <p>02</p>
            <span>THÀNH VIÊN</span><br/>
            <a href="">Chi tiết <i class="fas fa-arrow-circle-right"></i></a>
        </div>
        
    </div>
</div>
</div>

</body>
</html>