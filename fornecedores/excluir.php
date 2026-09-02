<?php

require_once "../includes/auth.php";
require_once "../config/database.php";


$id = filter_input(
    INPUT_GET,
    "id",
    FILTER_VALIDATE_INT
);


if (!$id) {

    header("Location: index.php");
    exit;

}


$stmt = $pdo->prepare("
    DELETE FROM fornecedores
    WHERE id = :id
");


$stmt->execute([
    ":id" => $id
]);


$_SESSION["mensagem"] =
    "Fornecedor excluído com sucesso.";

$_SESSION["tipo_mensagem"] =
    "success";


header("Location: index.php");
exit;