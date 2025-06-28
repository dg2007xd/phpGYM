<?php
    include 'DBconnect.php';
    $idejercicio = $_POST["idejercicio"];
    $nombre = $_POST["nombre"];
    $categoria_beneficio = $_POST["categoria_beneficio"];
    $tipo_ejercicio = $_POST["tipo_ejercicio"];
    $equipo_necesario = $_POST["equipo_necesario"];

    $objDb = new DBconnect;
    $cn = $objDb->connect();
    
    $sql = "UPDATE ejercicios  set nombre = '$nombre', categoria_beneficio ='$categoria_beneficio', 
    tipo_ejercicio ='$tipo_ejercicio', equipo_necesario ='$equipo_necesario' WHERE idejercicio=$idejercicio";

    $rs = $cn->prepare($sql);
    $rs->execute();
    echo $rs->rowCount();
?>