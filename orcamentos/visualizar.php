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


/*
ORÇAMENTO
*/

$stmt = $pdo->prepare("
    SELECT
        o.*,
        c.nome AS cliente_nome,
        c.cpf_cnpj,
        c.telefone,
        c.whatsapp,
        c.email,
        c.endereco,
        c.numero AS cliente_numero,
        c.bairro,
        c.cidade,
        c.estado
    FROM orcamentos o
    INNER JOIN clientes c
        ON c.id = o.cliente_id
    WHERE o.id = :id
    LIMIT 1
");

$stmt->execute([
    ":id" => $id
]);

$orcamento =
    $stmt->fetch();


if (!$orcamento) {

    $_SESSION["mensagem"] =
        "Orçamento não encontrado.";

    $_SESSION["tipo_mensagem"] =
        "danger";

    header("Location: index.php");
    exit;

}


/*
ITENS
*/

$stmtItens = $pdo->prepare("
    SELECT *
    FROM orcamento_itens
    WHERE orcamento_id = :id
    ORDER BY id
");

$stmtItens->execute([
    ":id" => $id
]);

$itens =
    $stmtItens->fetchAll();

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
    <?= htmlspecialchars($orcamento["numero"]) ?>
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
    font-family: Arial, Helvetica, sans-serif;
}

.container-view {
    max-width: 1100px;
    margin: 40px auto;
}

.box {
    background: white;
    padding: 35px;
    border-radius: 18px;
    box-shadow: 0 8px 30px rgba(0,0,0,.07);
    margin-bottom: 25px;
}

.orcamento-header {
    border-bottom: 3px solid #0d47a1;
    padding-bottom: 20px;
    margin-bottom: 25px;
}

.numero {
    color: #0d47a1;
    font-weight: bold;
}

.info-title {
    font-size: 13px;
    text-transform: uppercase;
    color: #888;
    font-weight: bold;
}

.info-value {
    font-size: 16px;
    margin-top: 3px;
}

.table th {
    background: #f5f7fb;
}

.total-area {
    max-width: 420px;
    margin-left: auto;
}

.total-line {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
}

.total-final {
    font-size: 25px;
    color: #0d47a1;
    font-weight: bold;
    border-top: 2px solid #eee;
    margin-top: 8px;
    padding-top: 15px;
}

</style>

</head>

<body>

<div class="container-view">

    <div
        class="d-flex justify-content-between align-items-center mb-4"
    >

        <a
            href="index.php"
            class="btn btn-outline-secondary"
        >

            <i class="bi bi-arrow-left"></i>

            Voltar

        </a>


        <div class="d-flex gap-2">

            <a
                href="gerar_pdf.php?id=<?= $orcamento["id"] ?>"
                target="_blank"
                class="btn btn-danger"
            >

                <i class="bi bi-file-earmark-pdf"></i>

                Gerar PDF

            </a>

        </div>

    </div>


    <div class="box">

        <div class="orcamento-header">

            <div
                class="d-flex justify-content-between align-items-start"
            >

                <div>

                    <h2 class="fw-bold">
                        ORÇAMENTO
                    </h2>

                    <div class="numero">

                        <?= htmlspecialchars(
                            $orcamento["numero"]
                        ) ?>

                    </div>

                </div>

                <span class="badge text-bg-primary">

                    <?= ucfirst(
                        $orcamento["status"]
                    ) ?>

                </span>

            </div>

        </div>


        <div class="row g-4 mb-4">

            <div class="col-md-6">

                <div class="info-title">
                    Cliente
                </div>

                <div class="info-value">

                    <strong>
                        <?= htmlspecialchars(
                            $orcamento["cliente_nome"]
                        ) ?>
                    </strong>

                </div>


                <?php if ($orcamento["cpf_cnpj"]): ?>

                    <div>
                        CPF/CNPJ:
                        <?= htmlspecialchars(
                            $orcamento["cpf_cnpj"]
                        ) ?>
                    </div>

                <?php endif; ?>


                <?php if ($orcamento["telefone"]): ?>

                    <div>

                        Telefone:
                        <?= htmlspecialchars(
                            $orcamento["telefone"]
                        ) ?>

                    </div>

                <?php endif; ?>


                <?php if ($orcamento["email"]): ?>

                    <div>

                        E-mail:
                        <?= htmlspecialchars(
                            $orcamento["email"]
                        ) ?>

                    </div>

                <?php endif; ?>

            </div>


            <div class="col-md-3">

                <div class="info-title">
                    Data
                </div>

                <div class="info-value">

                    <?= date(
                        "d/m/Y",
                        strtotime(
                            $orcamento["data_orcamento"]
                        )
                    ) ?>

                </div>

            </div>


            <div class="col-md-3">

                <div class="info-title">
                    Validade
                </div>

                <div class="info-value">

                    <?php if ($orcamento["validade"]): ?>

                        <?= date(
                            "d/m/Y",
                            strtotime(
                                $orcamento["validade"]
                            )
                        ) ?>

                    <?php else: ?>

                        Não informada

                    <?php endif; ?>

                </div>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table">

                <thead>

                    <tr>

                        <th>Descrição</th>
                        <th>Qtd.</th>
                        <th>Valor Unitário</th>
                        <th>Total</th>

                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($itens as $item): ?>

                        <tr>

                            <td>

                                <?= htmlspecialchars(
                                    $item["descricao"]
                                ) ?>

                            </td>

                            <td>

                                <?= number_format(
                                    $item["quantidade"],
                                    2,
                                    ",",
                                    "."
                                ) ?>

                            </td>

                            <td>

                                R$
                                <?= number_format(
                                    $item["valor_unitario"],
                                    2,
                                    ",",
                                    "."
                                ) ?>

                            </td>

                            <td>

                                <strong>

                                    R$
                                    <?= number_format(
                                        $item["total"],
                                        2,
                                        ",",
                                        "."
                                    ) ?>

                                </strong>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>


        <div class="total-area">

            <div class="total-line">

                <span>
                    Subtotal
                </span>

                <strong>

                    R$
                    <?= number_format(
                        $orcamento["subtotal"],
                        2,
                        ",",
                        "."
                    ) ?>

                </strong>

            </div>


            <div class="total-line">

                <span>
                    Desconto
                </span>

                <strong>

                    R$
                    <?= number_format(
                        $orcamento["desconto"],
                        2,
                        ",",
                        "."
                    ) ?>

                </strong>

            </div>


            <div class="total-line total-final">

                <span>
                    TOTAL
                </span>

                <span>

                    R$
                    <?= number_format(
                        $orcamento["total"],
                        2,
                        ",",
                        "."
                    ) ?>

                </span>

            </div>

        </div>


        <?php if ($orcamento["observacoes"]): ?>

            <hr>

            <h5 class="fw-bold">
                Observações
            </h5>

            <div>

                <?= nl2br(
                    htmlspecialchars(
                        $orcamento["observacoes"]
                    )
                ) ?>

            </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html>