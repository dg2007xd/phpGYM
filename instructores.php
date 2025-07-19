<?php
include 'DBconnect.php';

$objDb = new DBconnect;
$cn = $objDb->connect();

$filasPagina = $_GET["filas_pagina"]; // Por ejemplo: 5
$numeroPagina = $_GET["numero_pagina"]; // Por ejemplo: 2
$inicioPagina = ($numeroPagina - 1) * $filasPagina;

// Consulta principal: datos de sesiones con cliente y entrenador
$sql = "SELECT sesiones.id_sesion, sesiones.fecha,
                   CONCAT(clientes.nombre, ' (Edad: ', clientes.edad, ')') AS cliente,
                   CONCAT(entrenadores.nombre, ' - ', entrenadores.especialidad) AS entrenador
            FROM sesiones
            INNER JOIN clientes ON sesiones.id_cliente = clientes.id_cliente
            INNER JOIN entrenadores ON sesiones.id_entrenador = entrenadores.id_entrenador
            ORDER BY sesiones.id_sesion DESC
            LIMIT $inicioPagina, $filasPagina";

$rs = $cn->prepare($sql);
$rs->execute();
$rows = $rs->fetchAll(PDO::FETCH_ASSOC);

// Por cada sesión obtenemos sus servicios aplicados
foreach ($rows as &$row) {
    $sqlDetalle = "SELECT servicios.nombre_servicio, servicios.descripcion, servicios.precio_hora,
                              detalle_sesion.duracion_horas
                       FROM detalle_sesion
                       INNER JOIN servicios ON detalle_sesion.id_servicio = servicios.id_servicio
                       WHERE detalle_sesion.id_sesion = " . $row["id_sesion"];

    $rsDetalle = $cn->prepare($sqlDetalle);
    $rsDetalle->execute();
    $row["detalle"] = $rsDetalle->fetchAll(PDO::FETCH_ASSOC);
}

// Total de sesiones (para la paginación)
$sqlTotal = "SELECT COUNT(id_sesion) AS total FROM sesiones";
$rsTotal = $cn->prepare($sqlTotal);
$rsTotal->execute();
$totalFilas = $rsTotal->fetch(PDO::FETCH_ASSOC)["total"];

$response = [
    "total" => $totalFilas,
    "sesiones" => $rows,
];

echo json_encode($response);
