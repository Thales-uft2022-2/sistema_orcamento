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
    FROM servicos
    WHERE id = :id
    LIMIT 1
");

$stmt->execute([
    ":id" => $id
]);


$servico = $stmt->fetch();


if (!$servico) {

    $_SESSION["mensagem"] =
        "Serviço não encontrado.";

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

    <title>Editar Serviço</title>

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
            font-family: Arial, Helvetica, sans-serif;
        }

        .container-form {
            max-width: 950px;
            margin: 40px auto;
        }

        .form-box {
            background: white;
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.07);
        }

        .section-title {
            border-bottom: 1px solid #eee;
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
            min-height: 120px;
        }

    </style>

</head>

<body>

<div class="container-form">

    <div
        class="d-flex justify-content-between align-items-center mb-4"
    >

        <div>

            <h2 class="fw-bold">
                Editar Serviço
            </h2>

            <div class="text-muted">
                Serviço #<?= $servico["id"] ?>
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
                value="<?= $servico["id"] ?>"
            >


            <div class="section-title">
                Informações
            </div>


            <div class="row g-3">

                <div class="col-md-8">

                    <label class="form-label">
                        Nome *
                    </label>

                    <input
                        type="text"
                        name="nome"
                        class="form-control"
                        required
                        value="<?= htmlspecialchars($servico["nome"]) ?>"
                    >

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        Categoria
                    </label>

                    <input
                        type="text"
                        name="categoria"
                        class="form-control"
                        value="<?= htmlspecialchars($servico["categoria"] ?? "") ?>"
                    >

                </div>


                <div class="col-md-12">

                    <label class="form-label">
                        Descrição
                    </label>

                    <textarea
                        name="descricao"
                        class="form-control"
                    ><?= htmlspecialchars($servico["descricao"] ?? "") ?></textarea>

                </div>

            </div>


            <div class="section-title">
                Valores
            </div>


            <div class="row g-3">

                <div class="col-md-3">

                    <label class="form-label">
                        Unidade
                    </label>

                    <select
                        name="unidade"
                        class="form-select"
                    >

                        <?php

                        $unidades = [
                            "Serviço",
                            "Hora",
                            "Diária",
                            "Unidade",
                            "Metro",
                            "M²",
                            "Pacote"
                        ];

                        foreach ($unidades as $unidade):

                        ?>

                            <option
                                value="<?= htmlspecialchars($unidade) ?>"
                                <?= $servico["unidade"] === $unidade ? "selected" : "" ?>
                            >

                                <?= htmlspecialchars($unidade) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        Custo
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="custo"
                        class="form-control"
                        value="<?= htmlspecialchars($servico["custo"]) ?>"
                    >

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        Valor de Venda *
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="valor"
                        class="form-control"
                        required
                        value="<?= htmlspecialchars($servico["valor"]) ?>"
                    >

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        Status
                    </label>

                    <select
                        name="status"
                        class="form-select"
                    >

                        <option
                            value="ativo"
                            <?= $servico["status"] === "ativo" ? "selected" : "" ?>
                        >
                            Ativo
                        </option>

                        <option
                            value="inativo"
                            <?= $servico["status"] === "inativo" ? "selected" : "" ?>
                        >
                            Inativo
                        </option>

                    </select>

                </div>

            </div>


            <div
                class="mt-4 d-flex justify-content-end gap-2"
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

                    Salvar alterações

                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>