<?php
	include ('../config.php');
	// session_start();
	$username=$_POST['username'];
	$pw=$_POST['pw'];
	$paSQLStr ="SELECT * FROM users where username='$username' AND pw ='$pw'";
    $paPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Sử đụng Prepare 
            $stmt = $paPDO->prepare($paSQLStr);
            // Thực thi câu truy vấn
            $stmt->execute(); 
            // Khai báo fetch kiểu mảng kết hợp
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            // Lấy danh sách kết quả
            $paResult = $stmt->fetchAll();   
    if($paResult!=null){
        header("location: admin.php");
    }
    else{
        // echo '<script>alert("login false");</script>';
        header("location: index.php");
        
    }
?>