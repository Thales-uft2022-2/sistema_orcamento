<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: configuracoes.php");
    exit;

}

$id = filter_input(
    INPUT_POST,
    "id",
    FILTER_VALIDATE_INT
);

$razaoSocial =
    trim($_POST["razao_social"] ?? "");

$nomeFantasia =
    trim($_POST["nome_fantasia"] ?? "");

$cpfCnpj =
    trim($_POST["cpf_cnpj"] ?? "");

$telefone =
    trim($_POST["telefone"] ?? "");

$whatsapp =
    trim($_POST["whatsapp"] ?? "");

$email =
    trim($_POST["email"] ?? "");

$site =
    trim($_POST["site"] ?? "");

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


if ($razaoSocial === "") {

    $_SESSION["mensagem"] =
        "Informe a razão social.";

    $_SESSION["tipo_mensagem"] =
        "danger";

    header("Location: configuracoes.php");
    exit;

}


/*
======================================
LOGO
======================================
*/

$nomeLogo = null;


if (
    isset($_FILES["logo"]) &&
    $_FILES["logo"]["error"] === UPLOAD_ERR_OK
) {

    if ($_FILES["logo"]["size"] > 3 * 1024 * 1024) {

        $_SESSION["mensagem"] =
            "A logo deve ter no máximo 3 MB.";

        $_SESSION["tipo_mensagem"] =
            "danger";

        header("Location: configuracoes.php");
        exit;

    }


    $finfo =
        new finfo(FILEINFO_MIME_TYPE);


    $mime =
        $finfo->file(
            $_FILES["logo"]["tmp_name"]
        );


    $tiposPermitidos = [

        "image/jpeg" => "jpg",
        "image/png" => "png",
        "image/webp" => "webp"

    ];


    if (!isset($tiposPermitidos[$mime])) {

        $_SESSION["mensagem"] =
            "Formato de logo inválido.";

        $_SESSION["tipo_mensagem"] =
            "danger";

        header("Location: configuracoes.php");
        exit;

    }


    $extensao =
        $tiposPermitidos[$mime];


    $nomeLogo =
        "logo_" .
        time() .
        "." .
        $extensao;


    $pasta =
        __DIR__ .
        "/../uploads/logo/";


    if (!is_dir($pasta)) {

        mkdir(
            $pasta,
            0755,
            true
        );

    }


    move_uploaded_file(
        $_FILES["logo"]["tmp_name"],
        $pasta . $nomeLogo
    );

}


/*
======================================
INSERT / UPDATE
======================================
*/

if ($id) {

    if ($nomeLogo) {

        $sql = "
            UPDATE empresa
            SET
                razao_social = :razao_social,
                nome_fantasia = :nome_fantasia,
                cpf_cnpj = :cpf_cnpj,
                telefone = :telefone,
                whatsapp = :whatsapp,
                email = :email,
                site = :site,
                cep = :cep,
                endereco = :endereco,
                numero = :numero,
                complemento = :complemento,
                bairro = :bairro,
                cidade = :cidade,
                estado = :estado,
                observacoes = :observacoes,
                logo = :logo
            WHERE id = :id
        ";

    } else {

        $sql = "
            UPDATE empresa
            SET
                razao_social = :razao_social,
                nome_fantasia = :nome_fantasia,
                cpf_cnpj = :cpf_cnpj,
                telefone = :telefone,
                whatsapp = :whatsapp,
                email = :email,
                site = :site,
                cep = :cep,
                endereco = :endereco,
                numero = :numero,
                complemento = :complemento,
                bairro = :bairro,
                cidade = :cidade,
                estado = :estado,
                observacoes = :observacoes
            WHERE id = :id
        ";

    }


    $stmt =
        $pdo->prepare($sql);


    $dados = [

        ":razao_social" => $razaoSocial,
        ":nome_fantasia" => $nomeFantasia ?: null,
        ":cpf_cnpj" => $cpfCnpj ?: null,
        ":telefone" => $telefone ?: null,
        ":whatsapp" => $whatsapp ?: null,
        ":email" => $email ?: null,
        ":site" => $site ?: null,
        ":cep" => $cep ?: null,
        ":endereco" => $endereco ?: null,
        ":numero" => $numero ?: null,
        ":complemento" => $complemento ?: null,
        ":bairro" => $bairro ?: null,
        ":cidade" => $cidade ?: null,
        ":estado" => $estado ?: null,
        ":observacoes" => $observacoes ?: null,
        ":id" => $id

    ];


    if ($nomeLogo) {

        $dados[":logo"] =
            $nomeLogo;

    }


    $stmt->execute($dados);

} else {

    $sql = "
        INSERT INTO empresa
        (
            razao_social,
            nome_fantasia,
            cpf_cnpj,
            telefone,
            whatsapp,
            email,
            site,
            cep,
            endereco,
            numero,
            complemento,
            bairro,
            cidade,
            estado,
            logo,
            observacoes
        )
        VALUES
        (
            :razao_social,
            :nome_fantasia,
            :cpf_cnpj,
            :telefone,
            :whatsapp,
            :email,
            :site,
            :cep,
            :endereco,
            :numero,
            :complemento,
            :bairro,
            :cidade,
            :estado,
            :logo,
            :observacoes
        )
    ";


    $stmt =
        $pdo->prepare($sql);


    $stmt->execute([

        ":razao_social" => $razaoSocial,
        ":nome_fantasia" => $nomeFantasia ?: null,
        ":cpf_cnpj" => $cpfCnpj ?: null,
        ":telefone" => $telefone ?: null,
        ":whatsapp" => $whatsapp ?: null,
        ":email" => $email ?: null,
        ":site" => $site ?: null,
        ":cep" => $cep ?: null,
        ":endereco" => $endereco ?: null,
        ":numero" => $numero ?: null,
        ":complemento" => $complemento ?: null,
        ":bairro" => $bairro ?: null,
        ":cidade" => $cidade ?: null,
        ":estado" => $estado ?: null,
        ":logo" => $nomeLogo,
        ":observacoes" => $observacoes ?: null

    ]);

}


$_SESSION["mensagem"] =
    "Configurações salvas com sucesso!";

$_SESSION["tipo_mensagem"] =
    "success";


header("Location: configuracoes.php");
exit;