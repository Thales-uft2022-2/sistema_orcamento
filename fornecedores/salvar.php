<?php

require_once "../includes/auth.php";
require_once "../config/database.php";


if ($_SERVER["REQUEST_METHOD"] !== "POST") {

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

    header("Location: cadastrar.php");
    exit;

}


$sql = "
    INSERT INTO fornecedores
    (
        razao_social,
        nome_fantasia,
        cnpj,
        responsavel,
        telefone,
        whatsapp,
        email,
        cep,
        endereco,
        numero,
        complemento,
        bairro,
        cidade,
        estado,
        observacoes,
        status
    )
    VALUES
    (
        :razao_social,
        :nome_fantasia,
        :cnpj,
        :responsavel,
        :telefone,
        :whatsapp,
        :email,
        :cep,
        :endereco,
        :numero,
        :complemento,
        :bairro,
        :cidade,
        :estado,
        :observacoes,
        :status
    )
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
        $status

]);


$_SESSION["mensagem"] =
    "Fornecedor cadastrado com sucesso!";

$_SESSION["tipo_mensagem"] =
    "success";


header("Location: index.php");
exit;