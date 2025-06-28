<?php
    include 'DBconnect.php';

    $objDb = new DBconnect;
    $cn = $objDb->connect();
    
    $sql = "SELECT * FROM ejercicios";
    $rs = $cn->prepare($sql);
    $rs->execute();

    $rows = $rs->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows);
?>