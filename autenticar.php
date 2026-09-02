<?php

session_start();

require_once __DIR__ . "/config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: login.php");
    exit;

}

$email = trim($_POST["email"] ?? "");
$senha = $_POST["senha"] ?? "";

if (empty($email) || empty($senha)) {

    $_SESSION["erro_login"] = "Preencha e-mail e senha.";

    header("Location: login.php");
    exit;

}

try {

    $sql = "
        SELECT
            id,
            nome,
            email,
            senha,
            tipo,
            status
        FROM usuarios
        WHERE email = :email
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(
        ":email",
        $email,
        PDO::PARAM_STR
    );

    $stmt->execute();

    $usuario = $stmt->fetch();

    if (!$usuario) {

        $_SESSION["erro_login"] =
            "E-mail ou senha inválidos.";

        header("Location: login.php");
        exit;

    }


    if ($usuario["status"] !== "ativo") {

        $_SESSION["erro_login"] =
            "Este usuário está inativo.";

        header("Location: login.php");
        exit;

    }


    if (!password_verify($senha, $usuario["senha"])) {

        $_SESSION["erro_login"] =
            "E-mail ou senha inválidos.";

        header("Location: login.php");
        exit;

    }


    session_regenerate_id(true);


    $_SESSION["usuario_id"] =
        $usuario["id"];

    $_SESSION["usuario_nome"] =
        $usuario["nome"];

    $_SESSION["usuario_email"] =
        $usuario["email"];

    $_SESSION["usuario_tipo"] =
        $usuario["tipo"];

    $_SESSION["logado"] = true;


    header(
        "Location: dashboard/index.php"
    );

    exit;


} catch (PDOException $e) {

    $_SESSION["erro_login"] =
        "Erro ao realizar login.";

    header("Location: login.php");
    exit;

}