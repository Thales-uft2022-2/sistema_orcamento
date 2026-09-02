<?php

require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/database.php";


/*
========================================
CONTADORES
========================================
*/

$totalClientes = $pdo->query("
    SELECT COUNT(*) 
    FROM clientes
")->fetchColumn();


$totalFornecedores = $pdo->query("
    SELECT COUNT(*)
    FROM fornecedores
")->fetchColumn();


$totalServicos = $pdo->query("
    SELECT COUNT(*)
    FROM servicos
")->fetchColumn();


$totalOrcamentos = $pdo->query("
    SELECT COUNT(*)
    FROM orcamentos
")->fetchColumn();


$totalPendentes = $pdo->query("
    SELECT COUNT(*)
    FROM orcamentos
    WHERE status = 'pendente'
")->fetchColumn();


$totalAprovados = $pdo->query("
    SELECT COUNT(*)
    FROM orcamentos
    WHERE status = 'aprovado'
")->fetchColumn();


$totalRecusados = $pdo->query("
    SELECT COUNT(*)
    FROM orcamentos
    WHERE status = 'recusado'
")->fetchColumn();


$totalFinalizados = $pdo->query("
    SELECT COUNT(*)
    FROM orcamentos
    WHERE status = 'finalizado'
")->fetchColumn();


/*
========================================
VALORES
========================================
*/

$valorTotalOrcamentos = $pdo->query("
    SELECT COALESCE(SUM(total), 0)
    FROM orcamentos
")->fetchColumn();


$valorAprovados = $pdo->query("
    SELECT COALESCE(SUM(total), 0)
    FROM orcamentos
    WHERE status = 'aprovado'
")->fetchColumn();


$valorPendentes = $pdo->query("
    SELECT COALESCE(SUM(total), 0)
    FROM orcamentos
    WHERE status = 'pendente'
")->fetchColumn();


/*
========================================
ORÇAMENTOS DO MÊS
========================================
*/

$orcamentosMes = $pdo->query("
    SELECT COUNT(*)
    FROM orcamentos
    WHERE YEAR(data_orcamento) = YEAR(CURDATE())
      AND MONTH(data_orcamento) = MONTH(CURDATE())
")->fetchColumn();


$valorMes = $pdo->query("
    SELECT COALESCE(SUM(total), 0)
    FROM orcamentos
    WHERE YEAR(data_orcamento) = YEAR(CURDATE())
      AND MONTH(data_orcamento) = MONTH(CURDATE())
")->fetchColumn();


/*
========================================
ÚLTIMOS ORÇAMENTOS
========================================
*/

$stmtUltimos = $pdo->query("
    SELECT
        o.id,
        o.numero,
        o.data_orcamento,
        o.validade,
        o.total,
        o.status,
        c.nome AS cliente_nome
    FROM orcamentos o

    INNER JOIN clientes c
        ON c.id = o.cliente_id

    ORDER BY o.id DESC

    LIMIT 8
");


$ultimosOrcamentos =
    $stmtUltimos->fetchAll();


/*
========================================
ÚLTIMOS CLIENTES
========================================
*/

$stmtClientes = $pdo->query("
    SELECT
        id,
        nome,
        email,
        telefone,
        cidade,
        estado,
        criado_em
    FROM clientes
    ORDER BY id DESC
    LIMIT 5
");


$ultimosClientes =
    $stmtClientes->fetchAll();


/*
========================================
FUNÇÕES
========================================
*/

function moeda($valor)
{

    return "R$ " .
        number_format(
            (float) $valor,
            2,
            ",",
            "."
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


$nomeUsuario =
    $_SESSION["usuario_nome"] ??
    "Usuário";

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
    Dashboard | Meu Orçamento
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

    left: 0;
    top: 0;

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

    padding:
        10px
        15px
        30px;

    font-size: 22px;

    font-weight: bold;

    display: flex;

    align-items: center;

    gap: 10px;

}


.logo i {

    color: #ffc107;

    font-size: 27px;

}


.menu-title {

    color: #8da9cf;

    font-size: 11px;

    font-weight: bold;

    text-transform: uppercase;

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

    margin-bottom: 6px;

    border-radius: 10px;

    transition: 0.3s;

}


.sidebar a i {

    width: 27px;

    font-size: 18px;

    margin-right: 8px;

}


.sidebar a:hover {

    background:
        rgba(
            255,
            255,
            255,
            0.10
        );

    color: white;

    transform:
        translateX(3px);

}


.sidebar a.active {

    background:
        rgba(
            255,
            255,
            255,
            0.15
        );

    color: white;

    border-left:
        4px
        solid
        #ffc107;

}


.menu-sair {

    margin-top: 20px;

    border-top:
        1px solid
        rgba(
            255,
            255,
            255,
            0.10
        );

    padding-top: 15px;

}


/*
========================================
CONTEÚDO PRINCIPAL
========================================
*/

.main {

    margin-left: 260px;

    min-height: 100vh;

}


/*
========================================
TOPBAR
========================================
*/

.topbar {

    height: 75px;

    background: white;

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding:
        0
        30px;

    box-shadow:
        0
        2px
        15px
        rgba(
            0,
            0,
            0,
            0.05
        );

    position: sticky;

    top: 0;

    z-index: 100;

}


.topbar-title {

    font-weight: bold;

    font-size: 18px;

}


.user-area {

    display: flex;

    align-items: center;

    gap: 12px;

}


.avatar {

    width: 43px;
    height: 43px;

    border-radius: 50%;

    background:
        linear-gradient(
            135deg,
            #0d47a1,
            #1976d2
        );

    color: white;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 20px;

}


.user-name {

    font-size: 14px;

    font-weight: bold;

}


.user-role {

    font-size: 11px;

    color: #888;

}


/*
========================================
CONTENT
========================================
*/

.content {

    padding: 30px;

}


.welcome {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 30px;

}


.welcome h2 {

    font-weight: bold;

    margin-bottom: 5px;

}


.welcome p {

    color: #777;

    margin: 0;

}


.btn-novo-orcamento {

    background:
        linear-gradient(
            135deg,
            #0d47a1,
            #1976d2
        );

    color: white;

    text-decoration: none;

    padding:
        13px
        20px;

    border-radius: 10px;

    font-weight: bold;

    transition: 0.3s;

    box-shadow:
        0
        8px
        20px
        rgba(
            13,
            71,
            161,
            0.20
        );

}


.btn-novo-orcamento:hover {

    color: white;

    transform:
        translateY(-3px);

}


/*
========================================
CARDS
========================================
*/

.dashboard-card {

    background: white;

    border-radius: 17px;

    padding: 22px;

    box-shadow:
        0
        5px
        25px
        rgba(
            0,
            0,
            0,
            0.05
        );

    height: 100%;

    transition: 0.3s;

    border:
        1px
        solid
        #f0f1f5;

}


.dashboard-card:hover {

    transform:
        translateY(-5px);

    box-shadow:
        0
        12px
        30px
        rgba(
            0,
            0,
            0,
            0.08
        );

}


.card-header-info {

    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-bottom: 15px;

}


.card-icon {

    width: 52px;
    height: 52px;

    border-radius: 14px;

    display: flex;

    justify-content: center;

    align-items: center;

    font-size: 24px;

}


.icon-blue {

    background: #e8f0fe;

    color: #0d47a1;

}


.icon-green {

    background: #dcfce7;

    color: #15803d;

}


.icon-yellow {

    background: #fef3c7;

    color: #b45309;

}


.icon-purple {

    background: #f3e8ff;

    color: #7e22ce;

}


.icon-red {

    background: #fee2e2;

    color: #dc2626;

}


.icon-cyan {

    background: #cffafe;

    color: #0e7490;

}


.card-number {

    font-size: 30px;

    font-weight: bold;

    margin-bottom: 4px;

}


.card-label {

    color: #777;

    font-size: 14px;

}


/*
========================================
CARDS FINANCEIROS
========================================
*/

.finance-card {

    color: white;

    padding: 25px;

    border-radius: 18px;

    height: 100%;

}


.finance-total {

    background:
        linear-gradient(
            135deg,
            #0d47a1,
            #1976d2
        );

}


.finance-approved {

    background:
        linear-gradient(
            135deg,
            #198754,
            #20a765
        );

}


.finance-pending {

    background:
        linear-gradient(
            135deg,
            #d98c00,
            #f0ad1b
        );

}


.finance-title {

    color:
        rgba(
            255,
            255,
            255,
            0.80
        );

    font-size: 13px;

    text-transform: uppercase;

    letter-spacing: .5px;

}


.finance-value {

    font-size: 27px;

    font-weight: bold;

    margin-top: 10px;

}


/*
========================================
SEÇÕES
========================================
*/

.section-box {

    background: white;

    border-radius: 18px;

    box-shadow:
        0
        5px
        25px
        rgba(
            0,
            0,
            0,
            0.05
        );

    overflow: hidden;

}


.section-header {

    padding:
        22px
        25px;

    display: flex;

    align-items: center;

    justify-content: space-between;

    border-bottom:
        1px
        solid
        #eee;

}


.section-header h5 {

    margin: 0;

    font-weight: bold;

}


.section-header a {

    text-decoration: none;

    font-size: 13px;

    font-weight: 600;

}


/*
========================================
TABELA
========================================
*/

.table-dashboard {

    margin: 0;

    vertical-align: middle;

}


.table-dashboard th {

    background: #f8fafc;

    color: #64748b;

    font-size: 12px;

    text-transform: uppercase;

    padding:
        13px
        20px;

    border: none;

}


.table-dashboard td {

    padding:
        15px
        20px;

    border-color: #f1f1f1;

}


.orcamento-numero {

    color: #0d47a1;

    font-weight: bold;

}


.total-orcamento {

    font-weight: bold;

}


/*
========================================
STATUS
========================================
*/

.status {

    display: inline-block;

    padding:
        6px
        10px;

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
CLIENTES
========================================
*/

.cliente-item {

    padding:
        16px
        22px;

    border-bottom:
        1px
        solid
        #f1f1f1;

    display: flex;

    align-items: center;

    justify-content: space-between;

}


.cliente-item:last-child {

    border-bottom: 0;

}


.cliente-avatar {

    width: 42px;
    height: 42px;

    border-radius: 12px;

    background: #e8f0fe;

    color: #0d47a1;

    display: flex;

    align-items: center;

    justify-content: center;

    font-weight: bold;

}


.cliente-info {

    flex: 1;

    margin-left: 12px;

}


.cliente-nome {

    font-weight: bold;

}


.cliente-contato {

    font-size: 12px;

    color: #888;

}


/*
========================================
ATALHOS
========================================
*/

.shortcut {

    display: block;

    text-decoration: none;

    color: #172033;

    background: white;

    padding: 20px;

    border-radius: 15px;

    border: 1px solid #eee;

    transition: 0.3s;

    height: 100%;

}


.shortcut:hover {

    transform:
        translateY(-4px);

    color: #0d47a1;

    box-shadow:
        0
        7px
        20px
        rgba(
            0,
            0,
            0,
            0.06
        );

}


.shortcut i {

    font-size: 27px;

    color: #0d47a1;

    display: block;

    margin-bottom: 12px;

}


.shortcut strong {

    display: block;

    margin-bottom: 5px;

}


.shortcut span {

    color: #888;

    font-size: 12px;

}


/*
========================================
VAZIO
========================================
*/

.empty {

    padding: 45px;

    text-align: center;

    color: #888;

}


.empty i {

    display: block;

    font-size: 40px;

    margin-bottom: 10px;

}


/*
========================================
RESPONSIVO
========================================
*/

.mobile-menu {

    display: none;

    border: none;

    background: transparent;

    font-size: 25px;

}


@media(max-width: 1000px) {

    .sidebar {

        transform:
            translateX(-100%);

        transition: .3s;

    }


    .sidebar.open {

        transform:
            translateX(0);

    }


    .main {

        margin-left: 0;

    }


    .mobile-menu {

        display: block;

    }


    .topbar {

        padding:
            0
            20px;

    }


    .content {

        padding: 20px;

    }

}


@media(max-width: 700px) {

    .welcome {

        flex-direction: column;

        align-items: flex-start;

        gap: 15px;

    }


    .user-area .user-text {

        display: none;

    }

}

</style>

</head>


<body>


<!-- ==========================
SIDEBAR
========================== -->

<div
    class="sidebar"
    id="sidebar"
>

    <div class="logo">

        <i class="bi bi-file-earmark-text"></i>

        Meu Orçamento

    </div>


    <div class="menu-title">
        Principal
    </div>


    <a
        href="index.php"
        class="active"
    >

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


    <a href="../relatorios/">

        <i class="bi bi-bar-chart"></i>

        Relatórios

    </a>


    <a href="../empresa/configuracoes.php">

        <i class="bi bi-building"></i>

        Minha Empresa

    </a>


    <div class="menu-sair">

        <a href="../index.php">

            <i class="bi bi-house"></i>

            Página Inicial

        </a>


        <a href="../logout.php">

            <i class="bi bi-box-arrow-left"></i>

            Sair

        </a>

    </div>

</div>


<!-- ==========================
MAIN
========================== -->

<div class="main">


    <!-- TOPBAR -->

    <div class="topbar">

        <div
            class="d-flex align-items-center gap-3"
        >

            <button
                class="mobile-menu"
                onclick="toggleMenu()"
            >

                <i class="bi bi-list"></i>

            </button>


            <div class="topbar-title">

                Painel Administrativo

            </div>

        </div>


        <div class="user-area">

            <div class="avatar">

                <i class="bi bi-person"></i>

            </div>


            <div class="user-text">

                <div class="user-name">

                    <?= htmlspecialchars(
                        $nomeUsuario
                    ) ?>

                </div>

                <div class="user-role">

                    <?= htmlspecialchars(
                        ucfirst(
                            $_SESSION["usuario_tipo"]
                            ?? "Administrador"
                        )
                    ) ?>

                </div>

            </div>

        </div>

    </div>


    <!-- CONTENT -->

    <div class="content">


        <!-- BOAS-VINDAS -->

        <div class="welcome">

            <div>

                <h2>

                    Olá,
                    <?= htmlspecialchars(
                        $nomeUsuario
                    ) ?>!

                </h2>

                <p>

                    Acompanhe os principais números
                    do seu sistema de orçamentos.

                </p>

            </div>


            <a
                href="../orcamentos/novo.php"
                class="btn-novo-orcamento"
            >

                <i class="bi bi-plus-lg"></i>

                Novo Orçamento

            </a>

        </div>



        <!-- ======================
        CARDS CONTADORES
        ======================= -->

        <div class="row g-4 mb-4">


            <div class="col-xl-3 col-md-6">

                <div class="dashboard-card">

                    <div class="card-header-info">

                        <div class="card-icon icon-blue">

                            <i class="bi bi-people"></i>

                        </div>

                    </div>

                    <div class="card-number">

                        <?= $totalClientes ?>

                    </div>

                    <div class="card-label">

                        Clientes cadastrados

                    </div>

                </div>

            </div>


            <div class="col-xl-3 col-md-6">

                <div class="dashboard-card">

                    <div class="card-header-info">

                        <div class="card-icon icon-purple">

                            <i class="bi bi-truck"></i>

                        </div>

                    </div>

                    <div class="card-number">

                        <?= $totalFornecedores ?>

                    </div>

                    <div class="card-label">

                        Fornecedores

                    </div>

                </div>

            </div>


            <div class="col-xl-3 col-md-6">

                <div class="dashboard-card">

                    <div class="card-header-info">

                        <div class="card-icon icon-cyan">

                            <i class="bi bi-tools"></i>

                        </div>

                    </div>

                    <div class="card-number">

                        <?= $totalServicos ?>

                    </div>

                    <div class="card-label">

                        Serviços cadastrados

                    </div>

                </div>

            </div>


            <div class="col-xl-3 col-md-6">

                <div class="dashboard-card">

                    <div class="card-header-info">

                        <div class="card-icon icon-blue">

                            <i class="bi bi-file-earmark-text"></i>

                        </div>

                    </div>

                    <div class="card-number">

                        <?= $totalOrcamentos ?>

                    </div>

                    <div class="card-label">

                        Orçamentos gerados

                    </div>

                </div>

            </div>

        </div>



        <!-- STATUS -->

        <div class="row g-4 mb-4">


            <div class="col-xl-3 col-md-6">

                <div class="dashboard-card">

                    <div class="card-header-info">

                        <div class="card-icon icon-yellow">

                            <i class="bi bi-clock"></i>

                        </div>

                    </div>

                    <div class="card-number">

                        <?= $totalPendentes ?>

                    </div>

                    <div class="card-label">

                        Orçamentos pendentes

                    </div>

                </div>

            </div>


            <div class="col-xl-3 col-md-6">

                <div class="dashboard-card">

                    <div class="card-header-info">

                        <div class="card-icon icon-green">

                            <i class="bi bi-check-circle"></i>

                        </div>

                    </div>

                    <div class="card-number">

                        <?= $totalAprovados ?>

                    </div>

                    <div class="card-label">

                        Orçamentos aprovados

                    </div>

                </div>

            </div>


            <div class="col-xl-3 col-md-6">

                <div class="dashboard-card">

                    <div class="card-header-info">

                        <div class="card-icon icon-red">

                            <i class="bi bi-x-circle"></i>

                        </div>

                    </div>

                    <div class="card-number">

                        <?= $totalRecusados ?>

                    </div>

                    <div class="card-label">

                        Orçamentos recusados

                    </div>

                </div>

            </div>


            <div class="col-xl-3 col-md-6">

                <div class="dashboard-card">

                    <div class="card-header-info">

                        <div class="card-icon icon-purple">

                            <i class="bi bi-flag"></i>

                        </div>

                    </div>

                    <div class="card-number">

                        <?= $totalFinalizados ?>

                    </div>

                    <div class="card-label">

                        Orçamentos finalizados

                    </div>

                </div>

            </div>

        </div>



        <!-- ======================
        FINANCEIRO
        ======================= -->

        <div class="row g-4 mb-4">


            <div class="col-lg-4">

                <div
                    class="
                        finance-card
                        finance-total
                    "
                >

                    <div class="finance-title">

                        Total em Orçamentos

                    </div>

                    <div class="finance-value">

                        <?= moeda(
                            $valorTotalOrcamentos
                        ) ?>

                    </div>

                </div>

            </div>


            <div class="col-lg-4">

                <div
                    class="
                        finance-card
                        finance-approved
                    "
                >

                    <div class="finance-title">

                        Valor Aprovado

                    </div>

                    <div class="finance-value">

                        <?= moeda(
                            $valorAprovados
                        ) ?>

                    </div>

                </div>

            </div>


            <div class="col-lg-4">

                <div
                    class="
                        finance-card
                        finance-pending
                    "
                >

                    <div class="finance-title">

                        Valor Pendente

                    </div>

                    <div class="finance-value">

                        <?= moeda(
                            $valorPendentes
                        ) ?>

                    </div>

                </div>

            </div>

        </div>



        <!-- ======================
        MÊS ATUAL
        ======================= -->

        <div class="row g-4 mb-4">

            <div class="col-md-6">

                <div class="dashboard-card">

                    <div class="card-header-info">

                        <div class="card-icon icon-blue">

                            <i class="bi bi-calendar3"></i>

                        </div>

                    </div>


                    <div class="card-number">

                        <?= $orcamentosMes ?>

                    </div>


                    <div class="card-label">

                        Orçamentos neste mês

                    </div>

                </div>

            </div>


            <div class="col-md-6">

                <div class="dashboard-card">

                    <div class="card-header-info">

                        <div class="card-icon icon-green">

                            <i class="bi bi-cash-stack"></i>

                        </div>

                    </div>


                    <div
                        class="card-number"
                        style="font-size:25px;"
                    >

                        <?= moeda(
                            $valorMes
                        ) ?>

                    </div>


                    <div class="card-label">

                        Valor orçado neste mês

                    </div>

                </div>

            </div>

        </div>



        <!-- ======================
        ATALHOS
        ======================= -->

        <div class="mb-4">

            <h5 class="fw-bold mb-3">

                Acesso rápido

            </h5>


            <div class="row g-3">

                <div class="col-xl-3 col-md-6">

                    <a
                        href="../orcamentos/novo.php"
                        class="shortcut"
                    >

                        <i class="bi bi-file-earmark-plus"></i>

                        <strong>
                            Criar Orçamento
                        </strong>

                        <span>
                            Criar um novo orçamento.
                        </span>

                    </a>

                </div>


                <div class="col-xl-3 col-md-6">

                    <a
                        href="../clientes/cadastrar.php"
                        class="shortcut"
                    >

                        <i class="bi bi-person-plus"></i>

                        <strong>
                            Novo Cliente
                        </strong>

                        <span>
                            Cadastrar um cliente.
                        </span>

                    </a>

                </div>


                <div class="col-xl-3 col-md-6">

                    <a
                        href="../servicos/cadastrar.php"
                        class="shortcut"
                    >

                        <i class="bi bi-tools"></i>

                        <strong>
                            Novo Serviço
                        </strong>

                        <span>
                            Cadastrar um serviço.
                        </span>

                    </a>

                </div>


                <div class="col-xl-3 col-md-6">

                    <a
                        href="../empresa/configuracoes.php"
                        class="shortcut"
                    >

                        <i class="bi bi-building-gear"></i>

                        <strong>
                            Minha Empresa
                        </strong>

                        <span>
                            Logo e dados da empresa.
                        </span>

                    </a>

                </div>

            </div>

        </div>



        <!-- ======================
        ÚLTIMOS ORÇAMENTOS
        ======================= -->

        <div class="row g-4">


            <div class="col-xl-8">

                <div class="section-box">

                    <div class="section-header">

                        <h5>

                            <i class="bi bi-clock-history"></i>

                            Últimos Orçamentos

                        </h5>


                        <a href="../orcamentos/">

                            Ver todos

                            <i class="bi bi-arrow-right"></i>

                        </a>

                    </div>


                    <?php if ($ultimosOrcamentos): ?>

                        <div class="table-responsive">

                            <table
                                class="
                                    table
                                    table-dashboard
                                "
                            >

                                <thead>

                                    <tr>

                                        <th>Número</th>

                                        <th>Cliente</th>

                                        <th>Data</th>

                                        <th>Total</th>

                                        <th>Status</th>

                                        <th></th>

                                    </tr>

                                </thead>


                                <tbody>

                                <?php foreach (
                                    $ultimosOrcamentos
                                    as $orcamento
                                ): ?>

                                    <tr>

                                        <td>

                                            <span class="orcamento-numero">

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

                                            <?= date(
                                                "d/m/Y",
                                                strtotime(
                                                    $orcamento["data_orcamento"]
                                                )
                                            ) ?>

                                        </td>


                                        <td class="total-orcamento">

                                            <?= moeda(
                                                $orcamento["total"]
                                            ) ?>

                                        </td>


                                        <td>

                                            <span
                                                class="
                                                    status
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


                                            <a
                                                href="../orcamentos/gerar_pdf.php?id=<?= $orcamento["id"] ?>"
                                                class="btn btn-sm btn-outline-danger"
                                                target="_blank"
                                                title="PDF"
                                            >

                                                <i class="bi bi-file-earmark-pdf"></i>

                                            </a>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                                </tbody>

                            </table>

                        </div>

                    <?php else: ?>

                        <div class="empty">

                            <i class="bi bi-file-earmark-text"></i>

                            Nenhum orçamento cadastrado.

                            <div class="mt-3">

                                <a
                                    href="../orcamentos/novo.php"
                                    class="btn btn-primary btn-sm"
                                >

                                    Criar primeiro orçamento

                                </a>

                            </div>

                        </div>

                    <?php endif; ?>

                </div>

            </div>



            <!-- ======================
            ÚLTIMOS CLIENTES
            ======================= -->

            <div class="col-xl-4">

                <div class="section-box">

                    <div class="section-header">

                        <h5>

                            <i class="bi bi-people"></i>

                            Novos Clientes

                        </h5>


                        <a href="../clientes/">

                            Ver todos

                        </a>

                    </div>


                    <?php if ($ultimosClientes): ?>


                        <?php foreach (
                            $ultimosClientes
                            as $cliente
                        ): ?>


                            <div class="cliente-item">

                                <div class="cliente-avatar">

                                    <?= strtoupper(
                                        substr(
                                            $cliente["nome"],
                                            0,
                                            1
                                        )
                                    ) ?>

                                </div>


                                <div class="cliente-info">

                                    <div class="cliente-nome">

                                        <?= htmlspecialchars(
                                            $cliente["nome"]
                                        ) ?>

                                    </div>


                                    <div class="cliente-contato">

                                        <?php

                                        if (
                                            !empty(
                                                $cliente["telefone"]
                                            )
                                        ) {

                                            echo htmlspecialchars(
                                                $cliente["telefone"]
                                            );

                                        } elseif (
                                            !empty(
                                                $cliente["email"]
                                            )
                                        ) {

                                            echo htmlspecialchars(
                                                $cliente["email"]
                                            );

                                        } else {

                                            echo "Sem contato";

                                        }

                                        ?>

                                    </div>


                                    <?php if (
                                        !empty(
                                            $cliente["cidade"]
                                        )
                                    ): ?>

                                        <div class="cliente-contato">

                                            <i class="bi bi-geo-alt"></i>

                                            <?= htmlspecialchars(
                                                $cliente["cidade"]
                                            ) ?>

                                            <?php if (
                                                !empty(
                                                    $cliente["estado"]
                                                )
                                            ): ?>

                                                -
                                                <?= htmlspecialchars(
                                                    $cliente["estado"]
                                                ) ?>

                                            <?php endif; ?>

                                        </div>

                                    <?php endif; ?>

                                </div>


                                <a
                                    href="../clientes/editar.php?id=<?= $cliente["id"] ?>"
                                    class="btn btn-sm btn-light"
                                >

                                    <i class="bi bi-pencil"></i>

                                </a>

                            </div>


                        <?php endforeach; ?>


                    <?php else: ?>

                        <div class="empty">

                            <i class="bi bi-person-plus"></i>

                            Nenhum cliente cadastrado.

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>


    </div>

</div>


<!-- ==========================
BOOTSTRAP
========================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


<script>

/*
========================================
MENU MOBILE
========================================
*/

function toggleMenu() {

    document
        .getElementById("sidebar")
        .classList
        .toggle("open");

}


/*
Fechar menu ao clicar fora
em telas pequenas.
*/

document.addEventListener(
    "click",
    function(event) {

        const sidebar =
            document.getElementById(
                "sidebar"
            );

        const botao =
            document.querySelector(
                ".mobile-menu"
            );


        if (
            window.innerWidth <= 1000 &&
            sidebar.classList.contains("open") &&
            !sidebar.contains(event.target) &&
            !botao.contains(event.target)
        ) {

            sidebar.classList.remove(
                "open"
            );

        }

    }
);

</script>

</body>

</html>