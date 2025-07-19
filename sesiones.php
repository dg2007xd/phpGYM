<?php
include 'DBconnect.php';

$objDb = new DBconnect;
$cn = $objDb->connect();

$filasPagina = $_GET["filas_pagina"]; // Por ejemplo: 5
$numeroPagina = $_GET["numero_pagina"]; // Por ejemplo: 2
$inicioPagina = ($numeroPagina - 1) * $filasPagina;

// Consulta principal: datos de sesiones con cliente y entrenador
$sql = "SELECT sesiones.idsesion, sesiones.fechasesion,
                   clientes.nombre AS nombre_cliente,
                   clientes.edad AS edad_cliente,
                   entrenadores.nombreentrenador AS nombre_entrenador,
                   entrenadores.especialidad AS especialidad_entrenador,
                   entrenadores.experiencia AS experiencia_entrenador
            FROM sesiones
            INNER JOIN clientes ON sesiones.idcliente = clientes.idcliente
            INNER JOIN entrenadores ON sesiones.identrenador = entrenadores.identrenador
            ORDER BY sesiones.idsesion DESC
            LIMIT $inicioPagina, $filasPagina";

$rs = $cn->prepare($sql);
$rs->execute();
$rows = $rs->fetchAll(PDO::FETCH_ASSOC);

// Por cada sesión obtenemos sus servicios aplicados
foreach ($rows as &$row) {
    $sqlDetalle = "SELECT detalle_sesion.idservicio, servicios.nombreservicio, servicios.descripcion, servicios.precioxhora,
                              detalle_sesion.duracion_horas
                       FROM detalle_sesion
                       INNER JOIN servicios ON detalle_sesion.idservicio = servicios.idservicio
                       WHERE detalle_sesion.idsesion = " . $row["idsesion"];

    $rsDetalle = $cn->prepare($sqlDetalle);
    $rsDetalle->execute();
    $row["detalle"] = $rsDetalle->fetchAll(PDO::FETCH_ASSOC);
}

// Total de sesiones (para la paginación)
$sqlTotal = "SELECT COUNT(idsesion) AS total FROM sesiones";
$rsTotal = $cn->prepare($sqlTotal);
$rsTotal->execute();
$totalFilas = $rsTotal->fetch(PDO::FETCH_ASSOC)["total"];

$response = [
    "total" => $totalFilas,
    "sesiones" => $rows,
];

echo json_encode($response);
