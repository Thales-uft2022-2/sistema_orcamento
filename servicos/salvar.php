<?php

require_once "../includes/auth.php";
require_once "../config/database.php";


if ($_SERVER["REQUEST_METHOD"] !== "POST") {

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

    header("Location: cadastrar.php");
    exit;

}


if ($valor < 0 || $custo < 0) {

    $_SESSION["mensagem"] =
        "Os valores não podem ser negativos.";

    header("Location: cadastrar.php");
    exit;

}


$sql = "
    INSERT INTO servicos
    (
        nome,
        descricao,
        categoria,
        unidade,
        valor,
        custo,
        status
    )
    VALUES
    (
        :nome,
        :descricao,
        :categoria,
        :unidade,
        :valor,
        :custo,
        :status
    )
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
        $status

]);


$_SESSION["mensagem"] =
    "Serviço cadastrado com sucesso!";

$_SESSION["tipo_mensagem"] =
    "success";


header("Location: index.php");
exit;