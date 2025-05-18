<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include 'DBconnect.php';

$idcategoria = isset($_GET['idcategoria']) ? (int)$_GET['idcategoria'] : 0;

try {
    if ($idcategoria > 0) {
        $sql = "SELECT * FROM productos WHERE idcategoria = :idcategoria";
        $stmt = $cn->prepare($sql);
        $stmt->bindParam(':idcategoria', $idcategoria, PDO::PARAM_INT);
    } else {
        $sql = "SELECT * FROM productos";
        $stmt = $cn->prepare($sql);
    }

    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($productos);
} catch (PDOException $e) {
    http_response_code(400);
    echo json_encode(["error" => $e->getMessage()]);
}
?>