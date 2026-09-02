<?php

require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/database.php";


/*
====================================================
FUNÇÕES
====================================================
*/

function moeda($valor)
{
    return "R$ " . number_format(
        (float) $valor,
        2,
        ",",
        "."
    );
}


function dataBr($data)
{
    if (!$data) {
        return "-";
    }

    return date(
        "d/m/Y",
        strtotime($data)
    );
}


function classeStatus($status)
{
    switch ($status) {

        case "aprovado":
            return "status-aprovado";

        case "recusado":
            return "status-recusado";

        case "finalizado":
            return "status-finalizado";

        default:
            return "status-pendente";
    }
}


function nomeStatus($status)
{
    switch ($status) {

        case "aprovado":
            return "Aprovado";

        case "recusado":
            return "Recusado";

        case "finalizado":
            return "Finalizado";

        default:
            return "Pendente";
    }
}


/*
====================================================
FILTROS
====================================================
*/

$dataInicio =
    trim($_GET["data_inicio"] ?? "");

$dataFim =
    trim($_GET["data_fim"] ?? "");

$status =
    trim($_GET["status"] ?? "");

$clienteId =
    filter_input(
        INPUT_GET,
        "cliente_id",
        FILTER_VALIDATE_INT
    );


$statusPermitidos = [
    "",
    "pendente",
    "aprovado",
    "recusado",
    "finalizado"
];


if (!in_array(
    $status,
    $statusPermitidos,
    true
)) {
    $status = "";
}


/*
====================================================
CLIENTES PARA FILTRO
====================================================
*/

$clientes = $pdo->query("
    SELECT id, nome
    FROM clientes
    ORDER BY nome
")->fetchAll();


/*
====================================================
MONTAR WHERE
====================================================
*/

$where = [];

$parametros = [];


if ($dataInicio !== "") {

    $where[] =
        "o.data_orcamento >= :data_inicio";

    $parametros[":data_inicio"] =
        $dataInicio;
}


if ($dataFim !== "") {

    $where[] =
        "o.data_orcamento <= :data_fim";

    $parametros[":data_fim"] =
        $dataFim;
}


if ($status !== "") {

    $where[] =
        "o.status = :status";

    $parametros[":status"] =
        $status;
}


if ($clienteId) {

    $where[] =
        "o.cliente_id = :cliente_id";

    $parametros[":cliente_id"] =
        $clienteId;
}


$sqlWhere =
    count($where) > 0
        ? " WHERE " . implode(
            " AND ",
            $where
        )
        : "";


/*
====================================================
RESUMO
====================================================
*/

$sqlResumo = "
    SELECT

        COUNT(*) AS total_orcamentos,

        COALESCE(
            SUM(o.total),
            0
        ) AS valor_total,

        COALESCE(
            AVG(o.total),
            0
        ) AS ticket_medio,

        COALESCE(
            SUM(
                CASE
                    WHEN o.status = 'aprovado'
                    THEN o.total
                    ELSE 0
                END
            ),
            0
        ) AS valor_aprovado,

        COALESCE(
            SUM(
                CASE
                    WHEN o.status = 'pendente'
                    THEN o.total
                    ELSE 0
                END
            ),
            0
        ) AS valor_pendente,

        SUM(
            CASE
                WHEN o.status = 'aprovado'
                THEN 1
                ELSE 0
            END
        ) AS qtd_aprovados,

        SUM(
            CASE
                WHEN o.status = 'pendente'
                THEN 1
                ELSE 0
            END
        ) AS qtd_pendentes

    FROM orcamentos o

    {$sqlWhere}
";


$stmtResumo =
    $pdo->prepare(
        $sqlResumo
    );


$stmtResumo->execute(
    $parametros
);


$resumo =
    $stmtResumo->fetch();


/*
====================================================
STATUS
====================================================
*/

$sqlStatus = "
    SELECT
        o.status,
        COUNT(*) AS quantidade,
        COALESCE(
            SUM(o.total),
            0
        ) AS valor
    FROM orcamentos o

    {$sqlWhere}

    GROUP BY o.status
";


$stmtStatus =
    $pdo->prepare(
        $sqlStatus
    );


$stmtStatus->execute(
    $parametros
);


$statusDados =
    $stmtStatus->fetchAll();


$estatisticasStatus = [

    "pendente" => [
        "quantidade" => 0,
        "valor" => 0
    ],

    "aprovado" => [
        "quantidade" => 0,
        "valor" => 0
    ],

    "recusado" => [
        "quantidade" => 0,
        "valor" => 0
    ],

    "finalizado" => [
        "quantidade" => 0,
        "valor" => 0
    ]

];


foreach ($statusDados as $item) {

    if (
        isset(
            $estatisticasStatus[
                $item["status"]
            ]
        )
    ) {

        $estatisticasStatus[
            $item["status"]
        ] = [

            "quantidade" =>
                $item["quantidade"],

            "valor" =>
                $item["valor"]

        ];
    }
}


/*
====================================================
LISTAGEM
====================================================
*/

$sqlOrcamentos = "
    SELECT

        o.id,
        o.numero,
        o.data_orcamento,
        o.validade,
        o.subtotal,
        o.desconto,
        o.total,
        o.status,

        c.nome AS cliente_nome

    FROM orcamentos o

    INNER JOIN clientes c
        ON c.id = o.cliente_id

    {$sqlWhere}

    ORDER BY
        o.data_orcamento DESC,
        o.id DESC
";


$stmtOrcamentos =
    $pdo->prepare(
        $sqlOrcamentos
    );


$stmtOrcamentos->execute(
    $parametros
);


$orcamentos =
    $stmtOrcamentos->fetchAll();


/*
====================================================
QUERY STRING
====================================================
*/

$filtrosQuery = http_build_query([

    "data_inicio" =>
        $dataInicio,

    "data_fim" =>
        $dataFim,

    "status" =>
        $status,

    "cliente_id" =>
        $clienteId ?: ""

]);


$nomeUsuario =
    $_SESSION["usuario_nome"]
    ?? "Usuário";

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
    Relatórios | Meu Orçamento
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

* {
    box-sizing: border-box;
}

body {

    margin: 0;

    background: #f4f7fb;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    color: #172033;
}


/*
========================================
SIDEBAR
========================================
*/

.sidebar {

    position: fixed;

    width: 260px;
    height: 100vh;

    background:
        linear-gradient(
            180deg,
            #0d47a1,
            #061d46
        );

    color: white;

    padding: 25px 15px;

    overflow-y: auto;

    z-index: 1000;
}


.logo {

    font-size: 22px;

    font-weight: bold;

    padding:
        10px
        15px
        30px;
}


.logo i {

    color: #ffc107;

    margin-right: 7px;
}


.menu-title {

    color: #8da9cf;

    font-size: 11px;

    text-transform: uppercase;

    font-weight: bold;

    letter-spacing: 1px;

    padding:
        10px
        15px;
}


.sidebar a {

    display: flex;

    align-items: center;

    color: #dce5f2;

    text-decoration: none;

    padding:
        13px
        15px;

    border-radius: 10px;

    margin-bottom: 6px;

    transition: .3s;
}


.sidebar a i {

    width: 27px;

    margin-right: 8px;

    font-size: 18px;
}


.sidebar a:hover,
.sidebar a.active {

    background:
        rgba(
            255,
            255,
            255,
            .12
        );

    color: white;
}


.sidebar a.active {

    border-left:
        4px solid #ffc107;
}


/*
========================================
MAIN
========================================
*/

.main {

    margin-left: 260px;

    min-height: 100vh;
}


.topbar {

    height: 75px;

    background: white;

    padding:
        0
        30px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    box-shadow:
        0
        2px
        15px
        rgba(0,0,0,.05);
}


.content {

    padding: 30px;
}


/*
========================================
HEADER
========================================
*/

.page-header {

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 20px;

    margin-bottom: 25px;
}


.page-header h2 {

    margin: 0;

    font-weight: bold;
}


.page-header p {

    margin: 5px 0 0;

    color: #777;
}


.header-buttons {

    display: flex;

    gap: 10px;
}


/*
========================================
FILTROS
========================================
*/

.filter-box {

    background: white;

    padding: 25px;

    border-radius: 17px;

    box-shadow:
        0
        5px
        25px
        rgba(0,0,0,.05);

    margin-bottom: 25px;
}


.form-control,
.form-select {

    min-height: 46px;

    border-radius: 9px;
}


/*
========================================
CARDS
========================================
*/

.info-card {

    background: white;

    border-radius: 17px;

    padding: 23px;

    height: 100%;

    border:
        1px solid
        #eff1f5;

    box-shadow:
        0
        5px
        20px
        rgba(0,0,0,.04);

    transition: .3s;
}


.info-card:hover {

    transform:
        translateY(-4px);
}


.icon {

    width: 50px;
    height: 50px;

    border-radius: 13px;

    display: flex;

    justify-content: center;

    align-items: center;

    font-size: 23px;

    margin-bottom: 15px;
}


.blue {

    background: #e8f0fe;
    color: #0d47a1;
}


.green {

    background: #dcfce7;
    color: #15803d;
}


.yellow {

    background: #fef3c7;
    color: #a16207;
}


.purple {

    background: #f3e8ff;
    color: #7e22ce;
}


.card-value {

    font-size: 25px;

    font-weight: bold;
}


.card-label {

    color: #777;

    font-size: 13px;

    margin-top: 4px;
}


/*
========================================
STATUS
========================================
*/

.status-box {

    background: white;

    border-radius: 17px;

    padding: 25px;

    box-shadow:
        0
        5px
        25px
        rgba(0,0,0,.05);
}


.status-line {

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding:
        14px
        0;

    border-bottom:
        1px solid #eee;
}


.status-line:last-child {

    border-bottom: none;
}


.status-badge {

    padding:
        6px
        11px;

    border-radius: 20px;

    font-size: 11px;

    font-weight: bold;
}


.status-pendente {

    background: #fef3c7;

    color: #92400e;
}


.status-aprovado {

    background: #dcfce7;

    color: #166534;
}


.status-recusado {

    background: #fee2e2;

    color: #991b1b;
}


.status-finalizado {

    background: #dbeafe;

    color: #1e40af;
}


/*
========================================
TABELA
========================================
*/

.table-box {

    margin-top: 25px;

    background: white;

    border-radius: 17px;

    box-shadow:
        0
        5px
        25px
        rgba(0,0,0,.05);

    overflow: hidden;
}


.table-header {

    padding: 22px 25px;

    border-bottom:
        1px solid #eee;

    display: flex;

    justify-content: space-between;

    align-items: center;
}


.table-header h5 {

    font-weight: bold;

    margin: 0;
}


.table {

    margin: 0;

    vertical-align: middle;
}


.table th {

    background: #f8fafc;

    color: #64748b;

    text-transform: uppercase;

    font-size: 11px;

    padding:
        13px
        15px;

    border: none;
}


.table td {

    padding:
        15px;

    border-color: #f0f0f0;
}


.numero {

    color: #0d47a1;

    font-weight: bold;
}


.valor {

    font-weight: bold;
}


.empty {

    padding: 60px;

    text-align: center;

    color: #888;
}


.empty i {

    display: block;

    font-size: 50px;

    margin-bottom: 12px;
}


/*
========================================
RESPONSIVO
========================================
*/

@media(max-width: 950px) {

    .sidebar {

        display: none;

    }


    .main {

        margin-left: 0;

    }


    .page-header {

        flex-direction: column;

        align-items: flex-start;

    }

}


@media(max-width: 650px) {

    .content {

        padding: 18px;

    }


    .header-buttons {

        flex-direction: column;

        width: 100%;
    }


    .header-buttons a {

        width: 100%;
    }

}

</style>

</head>


<body>


<!-- SIDEBAR -->

<div class="sidebar">

    <div class="logo">

        <i class="bi bi-file-earmark-text"></i>

        Meu Orçamento

    </div>


    <div class="menu-title">
        Principal
    </div>


    <a href="../dashboard/">

        <i class="bi bi-speedometer2"></i>

        Dashboard

    </a>


    <a href="../orcamentos/novo.php">

        <i class="bi bi-file-earmark-plus"></i>

        Novo Orçamento

    </a>


    <a href="../orcamentos/">

        <i class="bi bi-file-earmark-text"></i>

        Orçamentos

    </a>


    <div class="menu-title">
        Cadastros
    </div>


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


    <div class="menu-title">
        Gestão
    </div>


    <a
        href="index.php"
        class="active"
    >

        <i class="bi bi-bar-chart"></i>

        Relatórios

    </a>


    <a href="../empresa/configuracoes.php">

        <i class="bi bi-building"></i>

        Minha Empresa

    </a>


    <hr>


    <a href="../logout.php">

        <i class="bi bi-box-arrow-left"></i>

        Sair

    </a>

</div>



<!-- MAIN -->

<div class="main">


    <div class="topbar">

        <strong>

            <i class="bi bi-bar-chart"></i>

            Relatórios

        </strong>


        <div>

            <i class="bi bi-person-circle"></i>

            <?= htmlspecialchars(
                $nomeUsuario
            ) ?>

        </div>

    </div>


    <div class="content">


        <!-- HEADER -->

        <div class="page-header">

            <div>

                <h2>
                    Relatório de Orçamentos
                </h2>

                <p>

                    Consulte os resultados financeiros
                    e o histórico de orçamentos.

                </p>

            </div>


            <div class="header-buttons">

                <a
                    href="gerar_pdf.php?<?= htmlspecialchars($filtrosQuery) ?>"
                    target="_blank"
                    class="btn btn-danger"
                >

                    <i class="bi bi-file-earmark-pdf"></i>

                    Gerar PDF

                </a>


                <a
                    href="exportar_csv.php?<?= htmlspecialchars($filtrosQuery) ?>"
                    class="btn btn-success"
                >

                    <i class="bi bi-file-earmark-excel"></i>

                    Exportar CSV

                </a>

            </div>

        </div>



        <!-- FILTROS -->

        <div class="filter-box">

            <h5 class="fw-bold mb-3">

                <i class="bi bi-funnel"></i>

                Filtros

            </h5>


            <form method="GET">

                <div class="row g-3">

                    <div class="col-lg-2 col-md-6">

                        <label class="form-label">
                            Data Inicial
                        </label>

                        <input
                            type="date"
                            name="data_inicio"
                            class="form-control"
                            value="<?= htmlspecialchars($dataInicio) ?>"
                        >

                    </div>


                    <div class="col-lg-2 col-md-6">

                        <label class="form-label">
                            Data Final
                        </label>

                        <input
                            type="date"
                            name="data_fim"
                            class="form-control"
                            value="<?= htmlspecialchars($dataFim) ?>"
                        >

                    </div>


                    <div class="col-lg-3 col-md-6">

                        <label class="form-label">
                            Cliente
                        </label>

                        <select
                            name="cliente_id"
                            class="form-select"
                        >

                            <option value="">
                                Todos os clientes
                            </option>

                            <?php foreach ($clientes as $cliente): ?>

                                <option
                                    value="<?= $cliente["id"] ?>"
                                    <?= $clienteId == $cliente["id"] ? "selected" : "" ?>
                                >

                                    <?= htmlspecialchars(
                                        $cliente["nome"]
                                    ) ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <div class="col-lg-2 col-md-6">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">
                                Todos
                            </option>

                            <option
                                value="pendente"
                                <?= $status === "pendente" ? "selected" : "" ?>
                            >
                                Pendente
                            </option>

                            <option
                                value="aprovado"
                                <?= $status === "aprovado" ? "selected" : "" ?>
                            >
                                Aprovado
                            </option>

                            <option
                                value="recusado"
                                <?= $status === "recusado" ? "selected" : "" ?>
                            >
                                Recusado
                            </option>

                            <option
                                value="finalizado"
                                <?= $status === "finalizado" ? "selected" : "" ?>
                            >
                                Finalizado
                            </option>

                        </select>

                    </div>


                    <div
                        class="
                            col-lg-3
                            d-flex
                            align-items-end
                            gap-2
                        "
                    >

                        <button
                            type="submit"
                            class="btn btn-primary flex-fill"
                        >

                            <i class="bi bi-search"></i>

                            Filtrar

                        </button>


                        <a
                            href="index.php"
                            class="btn btn-outline-secondary"
                        >

                            <i class="bi bi-x-lg"></i>

                        </a>

                    </div>

                </div>

            </form>

        </div>



        <!-- CARDS -->

        <div class="row g-4 mb-4">


            <div class="col-xl-3 col-md-6">

                <div class="info-card">

                    <div class="icon blue">

                        <i class="bi bi-file-earmark-text"></i>

                    </div>


                    <div class="card-value">

                        <?= (int) $resumo["total_orcamentos"] ?>

                    </div>


                    <div class="card-label">

                        Orçamentos encontrados

                    </div>

                </div>

            </div>


            <div class="col-xl-3 col-md-6">

                <div class="info-card">

                    <div class="icon purple">

                        <i class="bi bi-cash-stack"></i>

                    </div>


                    <div class="card-value">

                        <?= moeda(
                            $resumo["valor_total"]
                        ) ?>

                    </div>


                    <div class="card-label">

                        Total orçado

                    </div>

                </div>

            </div>


            <div class="col-xl-3 col-md-6">

                <div class="info-card">

                    <div class="icon green">

                        <i class="bi bi-check-circle"></i>

                    </div>


                    <div class="card-value">

                        <?= moeda(
                            $resumo["valor_aprovado"]
                        ) ?>

                    </div>


                    <div class="card-label">

                        Valor aprovado

                    </div>

                </div>

            </div>


            <div class="col-xl-3 col-md-6">

                <div class="info-card">

                    <div class="icon yellow">

                        <i class="bi bi-calculator"></i>

                    </div>


                    <div class="card-value">

                        <?= moeda(
                            $resumo["ticket_medio"]
                        ) ?>

                    </div>


                    <div class="card-label">

                        Ticket médio

                    </div>

                </div>

            </div>

        </div>



        <!-- STATUS -->

        <div class="status-box">

            <h5 class="fw-bold mb-3">

                Situação dos Orçamentos

            </h5>


            <?php

            $listaStatus = [
                "pendente",
                "aprovado",
                "recusado",
                "finalizado"
            ];

            ?>


            <?php foreach ($listaStatus as $itemStatus): ?>

                <div class="status-line">

                    <div>

                        <span
                            class="
                                status-badge
                                <?= classeStatus($itemStatus) ?>
                            "
                        >

                            <?= nomeStatus($itemStatus) ?>

                        </span>

                    </div>


                    <div>

                        <strong>

                            <?= (int)
                                $estatisticasStatus[
                                    $itemStatus
                                ]["quantidade"]
                            ?>

                        </strong>

                        orçamento(s)

                        &nbsp; | &nbsp;

                        <strong>

                            <?= moeda(
                                $estatisticasStatus[
                                    $itemStatus
                                ]["valor"]
                            ) ?>

                        </strong>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>



        <!-- TABELA -->

        <div class="table-box">


            <div class="table-header">

                <h5>

                    <i class="bi bi-table"></i>

                    Orçamentos

                </h5>


                <span class="text-muted">

                    <?= count($orcamentos) ?>
                    registro(s)

                </span>

            </div>


            <?php if ($orcamentos): ?>

                <div class="table-responsive">

                    <table class="table">

                        <thead>

                            <tr>

                                <th>Número</th>

                                <th>Cliente</th>

                                <th>Data</th>

                                <th>Validade</th>

                                <th>Subtotal</th>

                                <th>Desconto</th>

                                <th>Total</th>

                                <th>Status</th>

                                <th></th>

                            </tr>

                        </thead>


                        <tbody>


                        <?php foreach ($orcamentos as $orcamento): ?>

                            <tr>


                                <td>

                                    <span class="numero">

                                        <?= htmlspecialchars(
                                            $orcamento["numero"]
                                        ) ?>

                                    </span>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $orcamento["cliente_nome"]
                                    ) ?>

                                </td>


                                <td>

                                    <?= dataBr(
                                        $orcamento["data_orcamento"]
                                    ) ?>

                                </td>


                                <td>

                                    <?= dataBr(
                                        $orcamento["validade"]
                                    ) ?>

                                </td>


                                <td>

                                    <?= moeda(
                                        $orcamento["subtotal"]
                                    ) ?>

                                </td>


                                <td>

                                    <?= moeda(
                                        $orcamento["desconto"]
                                    ) ?>

                                </td>


                                <td class="valor">

                                    <?= moeda(
                                        $orcamento["total"]
                                    ) ?>

                                </td>


                                <td>

                                    <span
                                        class="
                                            status-badge
                                            <?= classeStatus(
                                                $orcamento["status"]
                                            ) ?>
                                        "
                                    >

                                        <?= nomeStatus(
                                            $orcamento["status"]
                                        ) ?>

                                    </span>

                                </td>


                                <td>

                                    <a
                                        href="../orcamentos/visualizar.php?id=<?= $orcamento["id"] ?>"
                                        class="btn btn-sm btn-outline-primary"
                                        title="Visualizar"
                                    >

                                        <i class="bi bi-eye"></i>

                                    </a>

                                </td>


                            </tr>

                        <?php endforeach; ?>


                        </tbody>

                    </table>

                </div>

            <?php else: ?>

                <div class="empty">

                    <i class="bi bi-search"></i>

                    Nenhum orçamento encontrado
                    com os filtros selecionados.

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