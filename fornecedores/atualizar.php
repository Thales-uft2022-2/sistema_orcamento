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


$razaoSocial =
    trim($_POST["razao_social"] ?? "");

$nomeFantasia =
    trim($_POST["nome_fantasia"] ?? "");

$cnpj =
    trim($_POST["cnpj"] ?? "");

$responsavel =
    trim($_POST["responsavel"] ?? "");

$telefone =
    trim($_POST["telefone"] ?? "");

$whatsapp =
    trim($_POST["whatsapp"] ?? "");

$email =
    trim($_POST["email"] ?? "");

$cep =
    trim($_POST["cep"] ?? "");

$endereco =
    trim($_POST["endereco"] ?? "");

$numero =
    trim($_POST["numero"] ?? "");

$complemento =
    trim($_POST["complemento"] ?? "");

$bairro =
    trim($_POST["bairro"] ?? "");

$cidade =
    trim($_POST["cidade"] ?? "");

$estado =
    strtoupper(
        trim($_POST["estado"] ?? "")
    );

$observacoes =
    trim($_POST["observacoes"] ?? "");

$status =
    $_POST["status"] ?? "ativo";


if ($razaoSocial === "") {

    $_SESSION["mensagem"] =
        "A razão social é obrigatória.";

    $_SESSION["tipo_mensagem"] =
        "danger";

    header(
        "Location: editar.php?id=$id"
    );

    exit;

}


$sql = "
    UPDATE fornecedores
    SET
        razao_social = :razao_social,
        nome_fantasia = :nome_fantasia,
        cnpj = :cnpj,
        responsavel = :responsavel,
        telefone = :telefone,
        whatsapp = :whatsapp,
        email = :email,
        cep = :cep,
        endereco = :endereco,
        numero = :numero,
        complemento = :complemento,
        bairro = :bairro,
        cidade = :cidade,
        estado = :estado,
        observacoes = :observacoes,
        status = :status
    WHERE id = :id
";


$stmt = $pdo->prepare($sql);


$stmt->execute([

    ":razao_social" =>
        $razaoSocial,

    ":nome_fantasia" =>
        $nomeFantasia ?: null,

    ":cnpj" =>
        $cnpj ?: null,

    ":responsavel" =>
        $responsavel ?: null,

    ":telefone" =>
        $telefone ?: null,

    ":whatsapp" =>
        $whatsapp ?: null,

    ":email" =>
        $email ?: null,

    ":cep" =>
        $cep ?: null,

    ":endereco" =>
        $endereco ?: null,

    ":numero" =>
        $numero ?: null,

    ":complemento" =>
        $complemento ?: null,

    ":bairro" =>
        $bairro ?: null,

    ":cidade" =>
        $cidade ?: null,

    ":estado" =>
        $estado ?: null,

    ":observacoes" =>
        $observacoes ?: null,

    ":status" =>
        $status,

    ":id" =>
        $id

]);


$_SESSION["mensagem"] =
    "Fornecedor atualizado com sucesso!";

$_SESSION["tipo_mensagem"] =
    "success";


header("Location: index.php");
exit;