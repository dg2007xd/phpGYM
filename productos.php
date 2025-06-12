<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include 'DBconnect.php';

$idcategoria = isset($_GET['idcategoria']) ? $_GET['idcategoria'] : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;


if (isset($_GET['total']) && $_GET['total'] == 1) {
    // Devuelve el total general de productos
    $sql = "SELECT COUNT(*) as total FROM productos";
    $stmt = $cn->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode(['total' => (int)$row['total']]);
    exit;
} else {
    // Todos los productos con datos de categoría
    $sql = "SELECT p.*, c.nombrecategoria, c.total 
                FROM productos p 
                LEFT JOIN categoria c ON p.idcategoria = c.idcategoria";
    $stmt = $cn->prepare($sql);
}

try {
    if ($id > 0) {
        // Producto específico con datos de categoría
        $sql = "SELECT p.*, c.nombrecategoria, c.total 
                FROM productos p 
                LEFT JOIN categoria c ON p.idcategoria = c.idcategoria
                WHERE p.id = :id";
        $stmt = $cn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    } else if (!empty($idcategoria)) {
        // Productos por múltiples categorías
        $ids = array_filter(array_map('intval', explode(',', $idcategoria)));
        if (count($ids) > 0) {
            $in = implode(',', array_fill(0, count($ids), '?'));
            $sql = "SELECT p.*, c.nombrecategoria, c.total 
                    FROM productos p 
                    LEFT JOIN categoria c ON p.idcategoria = c.idcategoria
                    WHERE p.idcategoria IN ($in)";
            $stmt = $cn->prepare($sql);
            foreach ($ids as $k => $v) {
                $stmt->bindValue($k + 1, $v, PDO::PARAM_INT);
            }
        } else {
            // Si no hay IDs válidos, mostrar todos los productos
            $sql = "SELECT p.*, c.nombrecategoria, c.total 
                    FROM productos p 
                    LEFT JOIN categoria c ON p.idcategoria = c.idcategoria";
            $stmt = $cn->prepare($sql);
        }
    } else {
        // Todos los productos con datos de categoría
        $sql = "SELECT p.*, c.nombrecategoria, c.total 
                FROM productos p 
                LEFT JOIN categoria c ON p.idcategoria = c.idcategoria";
        $stmt = $cn->prepare($sql);
    }

    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($productos);
} catch (PDOException $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
