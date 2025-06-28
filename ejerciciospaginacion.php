<?php 
    include 'DBconnect.php';
    $objDb = new DBconnect;
    $cn = $objDb->connect();
    $pagina = $_GET["pagina"];
    $filasPagina = $_GET["filasPagina"];
    
    $inicioPagina = $pagina * $filasPagina;

    $sql = "SELECT idejercicio, nombre, categoria_beneficio, tipo_ejercicio, equipo_necesario 
            FROM ejercicios LIMIT $inicioPagina , $filasPagina";
    $rs = $cn->prepare($sql);
    $rs->execute();
    $rows = $rs->fetchAll(PDO::FETCH_ASSOC);
    
    // Consulta adicional para contar el número total de filas sin LIMIT
    $sqlCount = "SELECT COUNT(idejercicio) AS total_rows
    FROM ejercicios";
    $rsCount = $cn->prepare($sqlCount);
    $rsCount->execute();
    
    $totalRows = $rsCount->fetch(PDO::FETCH_ASSOC)['total_rows'];
    
    $response = [
    'total_rows' => $totalRows,
    'data' => $rows
    ];
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>