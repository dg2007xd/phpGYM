<?php
    include 'DBconnect.php';
    $idejercicio = $_POST["idejercicio"];

    $objDb = new DBconnect;
    $cn = $objDb->connect();
    
    $sql = "DELETE FROM ejercicios  WHERE idejercicio=$idejercicio";
    $rs = $cn->prepare($sql);
    $rs->execute();
    echo $rs->rowCount();
?>