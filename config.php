<?php 
try{
    $paPDO = new PDO('pgsql:host=Localhost; dbname=covidbn; port=5433', 'postgres', '123');
}
catch(PDOException $e) {
    echo "Thất bại, Lỗi: " . $e->getMessage();
    return null;
}
?>