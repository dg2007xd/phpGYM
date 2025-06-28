<?php
    include 'DBconnect.php';

    $idejercicio = $_POST["idejercicio"];
    $nombre = $_POST["nombre"];
    $categoria_beneficio = $_POST["categoria_beneficio"];
    $tipo_ejercicio = $_POST["tipo_ejercicio"];
    $equipo_necesario = $_POST["equipo_necesario"];

    $objDb = new DBconnect;
    $cn = $objDb->connect();
    
    $sql = "INSERT INTO ejercicios (nombre, categoria_beneficio, tipo_ejercicio, equipo_necesario) 
    values('$nombre','$categoria_beneficio', '$tipo_ejercicio', '$equipo_necesario')";
    
    $rs = $cn->prepare($sql);
    $rs->execute();
    echo $cn->lastInsertId();
?>