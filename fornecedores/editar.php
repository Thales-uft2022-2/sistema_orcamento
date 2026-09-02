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
    FROM fornecedores
    WHERE id = :id
");

$stmt->execute([
    ":id" => $id
]);


$fornecedor = $stmt->fetch();


if (!$fornecedor) {

    $_SESSION["mensagem"] =
        "Fornecedor não encontrado.";

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
        Editar Fornecedor
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

            border-bottom:
                1px solid #eee;

            padding-bottom: 10px;

            margin: 20px 0;

            color: #0d47a1;

            font-weight: bold;

        }

        .form-control,
        .form-select {

            min-height: 48px;

            border-radius: 10px;

        }

        textarea.form-control {
            min-height: 110px;
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
                Editar Fornecedor
            </h2>

            <div class="text-muted">

                Fornecedor #
                <?= $fornecedor["id"] ?>

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
                value="<?= $fornecedor["id"] ?>"
            >


            <div class="section-title">
                Dados da Empresa
            </div>


            <div class="row g-3">


                <div class="col-md-7">

                    <label class="form-label">
                        Razão Social *
                    </label>

                    <input
                        type="text"
                        name="razao_social"
                        class="form-control"
                        required
                        value="<?= htmlspecialchars($fornecedor["razao_social"]) ?>"
                    >

                </div>


                <div class="col-md-5">

                    <label class="form-label">
                        Nome Fantasia
                    </label>

                    <input
                        type="text"
                        name="nome_fantasia"
                        class="form-control"
                        value="<?= htmlspecialchars($fornecedor["nome_fantasia"] ?? "") ?>"
                    >

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        CNPJ
                    </label>

                    <input
                        type="text"
                        name="cnpj"
                        class="form-control"
                        value="<?= htmlspecialchars($fornecedor["cnpj"] ?? "") ?>"
                    >

                </div>


                <div class="col-md-8">

                    <label class="form-label">
                        Responsável
                    </label>

                    <input
                        type="text"
                        name="responsavel"
                        class="form-control"
                        value="<?= htmlspecialchars($fornecedor["responsavel"] ?? "") ?>"
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
                        value="<?= htmlspecialchars($fornecedor["telefone"] ?? "") ?>"
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
                        value="<?= htmlspecialchars($fornecedor["whatsapp"] ?? "") ?>"
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
                            <?= $fornecedor["status"] === "ativo" ? "selected" : "" ?>
                        >
                            Ativo
                        </option>

                        <option
                            value="inativo"
                            <?= $fornecedor["status"] === "inativo" ? "selected" : "" ?>
                        >
                            Inativo
                        </option>

                    </select>

                </div>


                <div class="col-md-12">

                    <label class="form-label">
                        E-mail
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="<?= htmlspecialchars($fornecedor["email"] ?? "") ?>"
                    >

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
                        value="<?= htmlspecialchars($fornecedor["cep"] ?? "") ?>"
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
                        value="<?= htmlspecialchars($fornecedor["endereco"] ?? "") ?>"
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
                        value="<?= htmlspecialchars($fornecedor["numero"] ?? "") ?>"
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
                        value="<?= htmlspecialchars($fornecedor["complemento"] ?? "") ?>"
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
                        value="<?= htmlspecialchars($fornecedor["bairro"] ?? "") ?>"
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
                        value="<?= htmlspecialchars($fornecedor["cidade"] ?? "") ?>"
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
                        value="<?= htmlspecialchars($fornecedor["estado"] ?? "") ?>"
                    >

                </div>

            </div>


            <div class="section-title">
                Observações
            </div>


            <textarea
                name="observacoes"
                class="form-control"
            ><?= htmlspecialchars($fornecedor["observacoes"] ?? "") ?></textarea>


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