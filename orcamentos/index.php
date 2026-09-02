<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$pesquisa = trim($_GET["pesquisa"] ?? "");

if ($pesquisa !== "") {

    $sql = "
        SELECT
            o.*,
            c.nome AS cliente_nome
        FROM orcamentos o
        INNER JOIN clientes c
            ON c.id = o.cliente_id
        WHERE
            o.numero LIKE :pesquisa
            OR c.nome LIKE :pesquisa
            OR o.status LIKE :pesquisa
        ORDER BY o.id DESC
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":pesquisa" => "%{$pesquisa}%"
    ]);

} else {

    $stmt = $pdo->query("
        SELECT
            o.*,
            c.nome AS cliente_nome
        FROM orcamentos o
        INNER JOIN clientes c
            ON c.id = o.cliente_id
        ORDER BY o.id DESC
    ");

}

$orcamentos = $stmt->fetchAll();

$mensagem = $_SESSION["mensagem"] ?? null;
$tipoMensagem = $_SESSION["tipo_mensagem"] ?? "success";

unset(
    $_SESSION["mensagem"],
    $_SESSION["tipo_mensagem"]
);

function badgeStatus($status)
{
    return match ($status) {

        "aprovado" =>
            "success",

        "recusado" =>
            "danger",

        "finalizado" =>
            "primary",

        default =>
            "warning"
    };
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
        Orçamentos | Meu Orçamento
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
            margin: 0;
            background: #f4f7fb;
            font-family: Arial, Helvetica, sans-serif;
        }

        .sidebar {
            position: fixed;
            width: 260px;
            height: 100vh;
            background: linear-gradient(
                180deg,
                #0d47a1,
                #061d46
            );
            color: white;
            padding: 25px 15px;
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
            padding: 10px 15px 30px;
        }

        .logo i {
            color: #ffc107;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            color: #dce5f2;
            text-decoration: none;
            padding: 14px 16px;
            margin-bottom: 7px;
            border-radius: 10px;
            transition: .3s;
        }

        .sidebar a i {
            width: 27px;
            margin-right: 8px;
            font-size: 18px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(255,255,255,.12);
            color: white;
        }

        .sidebar a.active {
            border-left: 4px solid #ffc107;
        }

        .main {
            margin-left: 260px;
            min-height: 100vh;
        }

        .topbar {
            background: white;
            height: 75px;
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 15px rgba(0,0,0,.06);
        }

        .content {
            padding: 30px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-header h2 {
            font-weight: bold;
            margin: 0;
        }

        .page-header p {
            color: #777;
            margin: 5px 0 0;
        }

        .btn-novo {
            background: #0d47a1;
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: bold;
        }

        .btn-novo:hover {
            background: #08367d;
            color: white;
        }

        .box {
            background: white;
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 5px 25px rgba(0,0,0,.06);
        }

        .search-box {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
        }

        .table {
            vertical-align: middle;
        }

        .table th {
            background: #f5f7fb;
            padding: 14px;
        }

        .table td {
            padding: 15px 14px;
        }

        .valor {
            color: #0d47a1;
            font-weight: bold;
        }

        .acoes {
            display: flex;
            gap: 5px;
        }

        .acoes a {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            text-decoration: none;
        }

        .btn-ver {
            background: #dcfce7;
            color: #166534;
        }

        .btn-pdf {
            background: #fee2e2;
            color: #dc2626;
        }

        .empty {
            text-align: center;
            padding: 60px;
            color: #888;
        }

        .empty i {
            display: block;
            font-size: 55px;
            margin-bottom: 15px;
        }

        @media(max-width:900px) {

            .sidebar {
                display: none;
            }

            .main {
                margin-left: 0;
            }

        }

    </style>

</head>

<body>

<div class="sidebar">

    <div class="logo">

        <i class="bi bi-file-earmark-text"></i>

        Meu Orçamento

    </div>

    <a href="../dashboard/">

        <i class="bi bi-speedometer2"></i>

        Dashboard

    </a>

    <a href="../clientes/">

        <i class="bi bi-people"></i>

        Clientes

    </a>

    <a href="../fornecedores/">

        <i class="bi bi-truck"></i>

        Fornecedores

    </a>

    <a href="../servicos/">

        <i class="bi bi-tools"></i>

        Serviços

    </a>

    <a href="index.php" class="active">

        <i class="bi bi-file-earmark-text"></i>

        Orçamentos

    </a>

    <a href="../relatorios/">

        <i class="bi bi-bar-chart"></i>

        Relatórios

    </a>

    <hr>

    <a href="../logout.php">

        <i class="bi bi-box-arrow-left"></i>

        Sair

    </a>

</div>


<div class="main">

    <div class="topbar">

        <strong>
            Gerenciamento de Orçamentos
        </strong>

        <div>

            <i class="bi bi-person-circle"></i>

            <?= htmlspecialchars(
                $_SESSION["usuario_nome"] ?? "Usuário"
            ) ?>

        </div>

    </div>


    <div class="content">

        <div class="page-header">

            <div>

                <h2>Orçamentos</h2>

                <p>
                    Crie e acompanhe seus orçamentos.
                </p>

            </div>

            <a
                href="novo.php"
                class="btn-novo"
            >

                <i class="bi bi-plus-lg"></i>

                Novo Orçamento

            </a>

        </div>


        <?php if ($mensagem): ?>

            <div
                class="alert alert-<?= htmlspecialchars($tipoMensagem) ?> alert-dismissible fade show"
            >

                <?= htmlspecialchars($mensagem) ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

        <?php endif; ?>


        <div class="box">

            <form
                method="GET"
                class="search-box"
            >

                <input
                    type="text"
                    name="pesquisa"
                    class="form-control"
                    placeholder="Número, cliente ou status..."
                    value="<?= htmlspecialchars($pesquisa) ?>"
                >

                <button
                    class="btn btn-primary"
                >

                    <i class="bi bi-search"></i>

                    Pesquisar

                </button>

                <?php if ($pesquisa !== ""): ?>

                    <a
                        href="index.php"
                        class="btn btn-outline-secondary"
                    >
                        Limpar
                    </a>

                <?php endif; ?>

            </form>


            <?php if ($orcamentos): ?>

                <div class="table-responsive">

                    <table class="table">

                        <thead>

                            <tr>

                                <th>Número</th>
                                <th>Cliente</th>
                                <th>Data</th>
                                <th>Validade</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Ações</th>

                            </tr>

                        </thead>

                        <tbody>

                        <?php foreach ($orcamentos as $orcamento): ?>

                            <tr>

                                <td>

                                    <strong>
                                        <?= htmlspecialchars($orcamento["numero"]) ?>
                                    </strong>

                                </td>

                                <td>

                                    <?= htmlspecialchars(
                                        $orcamento["cliente_nome"]
                                    ) ?>

                                </td>

                                <td>

                                    <?= date(
                                        "d/m/Y",
                                        strtotime($orcamento["data_orcamento"])
                                    ) ?>

                                </td>

                                <td>

                                    <?php if ($orcamento["validade"]): ?>

                                        <?= date(
                                            "d/m/Y",
                                            strtotime($orcamento["validade"])
                                        ) ?>

                                    <?php else: ?>

                                        -

                                    <?php endif; ?>

                                </td>

                                <td class="valor">

                                    R$
                                    <?= number_format(
                                        $orcamento["total"],
                                        2,
                                        ",",
                                        "."
                                    ) ?>

                                </td>

                                <td>

                                    <span
                                        class="badge text-bg-<?= badgeStatus($orcamento["status"]) ?>"
                                    >

                                        <?= ucfirst(
                                            $orcamento["status"]
                                        ) ?>

                                    </span>

                                </td>

                                <td>

                                    <div class="acoes">

                                        <a
                                            href="visualizar.php?id=<?= $orcamento["id"] ?>"
                                            class="btn-ver"
                                            title="Visualizar"
                                        >

                                            <i class="bi bi-eye"></i>

                                        </a>

                                        <a
                                            href="gerar_pdf.php?id=<?= $orcamento["id"] ?>"
                                            class="btn-pdf"
                                            title="PDF"
                                            target="_blank"
                                        >

                                            <i class="bi bi-file-earmark-pdf"></i>

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            <?php else: ?>

                <div class="empty">

                    <i class="bi bi-file-earmark-text"></i>

                    Nenhum orçamento encontrado.

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>
</html>