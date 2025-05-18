<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

include 'DBconnect.php';

try {
    // Obtener parámetro id si existe
    $idcategoria = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Construir consulta SQL
    $sql = "SELECT idcategoria, nombrecategoria, total FROM categoria";
    $params = [];
    
    // Si se especifica un ID de categoría, filtrar
    if ($idcategoria > 0) {
        $sql .= " WHERE idcategoria = :idcategoria";
        $params[':idcategoria'] = $idcategoria;
    }

    // Ordenar por nombre
    $sql .= " ORDER BY nombrecategoria";

    // Preparar y ejecutar consulta
    $stmt = $cn->prepare($sql);
    $stmt->execute($params);

    // Obtener resultados
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Si se filtró por ID y no se encontró la categoría
    if ($idcategoria > 0 && empty($categorias)) {
        http_response_code(404);
        echo json_encode(["message" => "Categoría no encontrada"]);
        exit;
    }

    // Devolver resultados
    echo json_encode($categorias);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "message" => "Error al obtener categorías",
        "error" => $e->getMessage()
    ]);
}
?>