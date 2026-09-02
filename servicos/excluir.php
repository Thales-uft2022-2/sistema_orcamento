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


try {

    $stmt = $pdo->prepare("
        DELETE FROM servicos
        WHERE id = :id
    ");

    $stmt->execute([
        ":id" => $id
    ]);


    $_SESSION["mensagem"] =
        "Serviço excluído com sucesso.";

    $_SESSION["tipo_mensagem"] =
        "success";


} catch (PDOException $e) {

    $_SESSION["mensagem"] =
        "Não foi possível excluir o serviço.";

    $_SESSION["tipo_mensagem"] =
        "danger";

}


header("Location: index.php");
exit;