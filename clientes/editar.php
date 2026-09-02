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
    SELECT *
    FROM clientes
    WHERE id = :id
");

$stmt->execute([
    ":id" => $id
]);

$cliente = $stmt->fetch();


if (!$cliente) {

    $_SESSION["mensagem"] =
        "Cliente não encontrado.";

    $_SESSION["tipo_mensagem"] =
        "danger";

    header("Location: index.php");
    exit;

}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Editar Cliente
    </title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>

        body {

            background: #f4f7fb;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

        }

        .container-form {

            max-width: 1000px;

            margin: 40px auto;

        }

        .form-box {

            background: white;

            padding: 35px;

            border-radius: 18px;

            box-shadow:
                0 8px 30px
                rgba(0,0,0,0.07);

        }

        .section-title {

            color: #0d47a1;

            font-weight: bold;

            border-bottom:
                1px solid #eee;

            padding-bottom: 10px;

            margin:
                20px 0;

        }

        .form-control,
        .form-select {

            min-height: 48px;

            border-radius: 10px;

        }

        textarea.form-control {
            min-height: 100px;
        }

    </style>

</head>

<body>


<div class="container-form">

    <div
        class="
            d-flex
            justify-content-between
            align-items-center
            mb-4
        "
    >

        <div>

            <h2 class="fw-bold">
                Editar Cliente
            </h2>

            <div class="text-muted">

                Cliente #
                <?= $cliente["id"] ?>

            </div>

        </div>


        <a
            href="index.php"
            class="btn btn-outline-secondary"
        >

            <i class="bi bi-arrow-left"></i>

            Voltar

        </a>

    </div>


    <div class="form-box">

        <form
            action="atualizar.php"
            method="POST"
        >

            <input
                type="hidden"
                name="id"
                value="<?= $cliente["id"] ?>"
            >


            <div class="section-title">
                Dados principais
            </div>


            <div class="row g-3">

                <div class="col-md-8">

                    <label class="form-label">
                        Nome / Razão Social *
                    </label>

                    <input
                        type="text"
                        name="nome"
                        class="form-control"
                        required
                        value="<?= htmlspecialchars($cliente["nome"]) ?>"
                    >

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Tipo de Pessoa
                    </label>

                    <select
                        name="tipo_pessoa"
                        class="form-select"
                    >

                        <option
                            value="fisica"
                            <?= $cliente["tipo_pessoa"] === "fisica" ? "selected" : "" ?>
                        >
                            Pessoa Física
                        </option>

                        <option
                            value="juridica"
                            <?= $cliente["tipo_pessoa"] === "juridica" ? "selected" : "" ?>
                        >
                            Pessoa Jurídica
                        </option>

                    </select>

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        CPF/CNPJ
                    </label>

                    <input
                        type="text"
                        name="cpf_cnpj"
                        class="form-control"
                        value="<?= htmlspecialchars($cliente["cpf_cnpj"] ?? "") ?>"
                    >

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Telefone
                    </label>

                    <input
                        type="text"
                        name="telefone"
                        class="form-control"
                        value="<?= htmlspecialchars($cliente["telefone"] ?? "") ?>"
                    >

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        WhatsApp
                    </label>

                    <input
                        type="text"
                        name="whatsapp"
                        class="form-control"
                        value="<?= htmlspecialchars($cliente["whatsapp"] ?? "") ?>"
                    >

                </div>


                <div class="col-md-8">

                    <label class="form-label">
                        E-mail
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="<?= htmlspecialchars($cliente["email"] ?? "") ?>"
                    >

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Status
                    </label>

                    <select
                        name="status"
                        class="form-select"
                    >

                        <option
                            value="ativo"
                            <?= $cliente["status"] === "ativo" ? "selected" : "" ?>
                        >
                            Ativo
                        </option>

                        <option
                            value="inativo"
                            <?= $cliente["status"] === "inativo" ? "selected" : "" ?>
                        >
                            Inativo
                        </option>

                    </select>

                </div>

            </div>


            <div class="section-title">
                Endereço
            </div>


            <div class="row g-3">

                <div class="col-md-3">

                    <label class="form-label">
                        CEP
                    </label>

                    <input
                        type="text"
                        name="cep"
                        class="form-control"
                        value="<?= htmlspecialchars($cliente["cep"] ?? "") ?>"
                    >

                </div>


                <div class="col-md-7">

                    <label class="form-label">
                        Endereço
                    </label>

                    <input
                        type="text"
                        name="endereco"
                        class="form-control"
                        value="<?= htmlspecialchars($cliente["endereco"] ?? "") ?>"
                    >

                </div>


                <div class="col-md-2">

                    <label class="form-label">
                        Número
                    </label>

                    <input
                        type="text"
                        name="numero"
                        class="form-control"
                        value="<?= htmlspecialchars($cliente["numero"] ?? "") ?>"
                    >

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Complemento
                    </label>

                    <input
                        type="text"
                        name="complemento"
                        class="form-control"
                        value="<?= htmlspecialchars($cliente["complemento"] ?? "") ?>"
                    >

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        Bairro
                    </label>

                    <input
                        type="text"
                        name="bairro"
                        class="form-control"
                        value="<?= htmlspecialchars($cliente["bairro"] ?? "") ?>"
                    >

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        Cidade
                    </label>

                    <input
                        type="text"
                        name="cidade"
                        class="form-control"
                        value="<?= htmlspecialchars($cliente["cidade"] ?? "") ?>"
                    >

                </div>


                <div class="col-md-2">

                    <label class="form-label">
                        Estado
                    </label>

                    <input
                        type="text"
                        name="estado"
                        maxlength="2"
                        class="form-control"
                        value="<?= htmlspecialchars($cliente["estado"] ?? "") ?>"
                    >

                </div>

            </div>


            <div class="section-title">
                Observações
            </div>


            <textarea
                name="observacoes"
                class="form-control"
            ><?= htmlspecialchars($cliente["observacoes"] ?? "") ?></textarea>


            <div
                class="
                    mt-4
                    d-flex
                    justify-content-end
                    gap-2
                "
            >

                <a
                    href="index.php"
                    class="btn btn-light"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="btn btn-primary px-4"
                >

                    <i class="bi bi-check-lg"></i>

                    Salvar Alterações

                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>