<?php

require_once "../includes/auth.php";
require_once "../config/database.php";


if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: index.php");
    exit;

}


$id = filter_input(
    INPUT_POST,
    "id",
    FILTER_VALIDATE_INT
);


if (!$id) {

    header("Location: index.php");
    exit;

}


$nome =
    trim($_POST["nome"] ?? "");

$descricao =
    trim($_POST["descricao"] ?? "");

$categoria =
    trim($_POST["categoria"] ?? "");

$unidade =
    trim($_POST["unidade"] ?? "Serviço");

$valor =
    (float) ($_POST["valor"] ?? 0);

$custo =
    (float) ($_POST["custo"] ?? 0);

$status =
    $_POST["status"] ?? "ativo";


if ($nome === "") {

    $_SESSION["mensagem"] =
        "Informe o nome do serviço.";

    $_SESSION["tipo_mensagem"] =
        "danger";

    header(
        "Location: editar.php?id={$id}"
    );

    exit;

}


$sql = "
    UPDATE servicos

    SET
        nome = :nome,
        descricao = :descricao,
        categoria = :categoria,
        unidade = :unidade,
        valor = :valor,
        custo = :custo,
        status = :status

    WHERE id = :id
";


$stmt = $pdo->prepare($sql);


$stmt->execute([

    ":nome" => $nome,

    ":descricao" =>
        $descricao ?: null,

    ":categoria" =>
        $categoria ?: null,

    ":unidade" =>
        $unidade,

    ":valor" =>
        $valor,

    ":custo" =>
        $custo,

    ":status" =>
        $status,

    ":id" =>
        $id

]);


$_SESSION["mensagem"] =
    "Serviço atualizado com sucesso!";

$_SESSION["tipo_mensagem"] =
    "success";


header("Location: index.php");
exit;