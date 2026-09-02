<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$pesquisa = trim($_GET["pesquisa"] ?? "");

if ($pesquisa !== "") {

    $sql = "
        SELECT *
        FROM clientes
        WHERE
            nome LIKE :pesquisa
            OR cpf_cnpj LIKE :pesquisa
            OR telefone LIKE :pesquisa
            OR email LIKE :pesquisa
        ORDER BY id DESC
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ":pesquisa" => "%$pesquisa%"
    ]);

} else {

    $stmt = $pdo->query("
        SELECT *
        FROM clientes
        ORDER BY id DESC
    ");

}

$clientes = $stmt->fetchAll();

$mensagem = $_SESSION["mensagem"] ?? null;
$tipoMensagem = $_SESSION["tipo_mensagem"] ?? "success";

unset(
    $_SESSION["mensagem"],
    $_SESSION["tipo_mensagem"]
);

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
        Clientes | Meu Orçamento
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

            background:
                linear-gradient(
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

            padding:
                10px
                15px
                30px;

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

            transition: 0.3s;

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
                    0.12
                );

            color: white;

        }

        .sidebar a.active {

            border-left:
                4px
                solid
                #ffc107;

        }

        .main {

            margin-left: 260px;

            min-height: 100vh;

        }

        .topbar {

            background: white;

            height: 75px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 0 30px;

            box-shadow:
                0 2px 15px
                rgba(0,0,0,0.06);

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

            margin: 0;

            font-weight: bold;

            color: #172033;

        }

        .page-header p {

            color: #777;

            margin: 5px 0 0;

        }

        .btn-novo {

            background: #0d47a1;

            border: none;

            color: white;

            text-decoration: none;

            padding: 12px 20px;

            border-radius: 10px;

            font-weight: 600;

            transition: 0.3s;

        }

        .btn-novo:hover {

            background: #08367d;

            color: white;

            transform:
                translateY(-2px);

        }

        .box {

            background: white;

            border-radius: 16px;

            padding: 25px;

            box-shadow:
                0 5px 25px
                rgba(0,0,0,0.06);

        }

        .search-box {

            display: flex;

            gap: 10px;

            margin-bottom: 25px;

        }

        .search-box input {

            height: 45px;

            border-radius: 10px;

        }

        .table {

            vertical-align: middle;

        }

        .table thead th {

            background: #f5f7fb;

            color: #555;

            border-bottom: 0;

            padding: 14px;

        }

        .table tbody td {

            padding: 15px 14px;

        }

        .badge-ativo {

            background: #dcfce7;

            color: #166534;

            padding: 7px 10px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: bold;

        }

        .badge-inativo {

            background: #fee2e2;

            color: #991b1b;

            padding: 7px 10px;

            border-radius: 20px;

            font-size: 12px;

            font-weight: bold;

        }

        .acoes {

            display: flex;

            gap: 5px;

        }

        .acoes a {

            width: 38px;
            height: 38px;

            border-radius: 8px;

            display: flex;

            justify-content: center;
            align-items: center;

            text-decoration: none;

        }

        .btn-editar {

            background: #e8f0fe;

            color: #0d47a1;

        }

        .btn-excluir {

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

            font-size: 50px;

            margin-bottom: 15px;

            color: #bbb;

        }

        @media(max-width: 900px) {

            .sidebar {
                display: none;
            }

            .main {
                margin-left: 0;
            }

            .page-header {

                flex-direction: column;

                align-items: flex-start;

                gap: 15px;

            }

            .table-responsive {
                overflow-x: auto;
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

    <a href="index.php" class="active">

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

    <a href="../orcamentos/">

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
            Gerenciamento de Clientes
        </strong>

        <div>

            <i class="bi bi-person-circle"></i>

            <?= htmlspecialchars(
                $_SESSION["usuario_nome"]
                ?? "Usuário"
            ) ?>

        </div>

    </div>


    <div class="content">

        <div class="page-header">

            <div>

                <h2>
                    Clientes
                </h2>

                <p>
                    Cadastre e gerencie seus clientes.
                </p>

            </div>


            <a
                href="cadastrar.php"
                class="btn-novo"
            >

                <i class="bi bi-plus-lg"></i>

                Novo Cliente

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
                    placeholder="Pesquisar por nome, CPF/CNPJ, telefone ou e-mail..."
                    value="<?= htmlspecialchars($pesquisa) ?>"
                >

                <button
                    type="submit"
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


            <?php if (count($clientes) > 0): ?>

                <div class="table-responsive">

                    <table class="table">

                        <thead>

                            <tr>

                                <th>
                                    Cliente
                                </th>

                                <th>
                                    CPF/CNPJ
                                </th>

                                <th>
                                    Telefone
                                </th>

                                <th>
                                    Cidade
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Ações
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                        <?php foreach ($clientes as $cliente): ?>

                            <tr>

                                <td>

                                    <strong>
                                        <?= htmlspecialchars($cliente["nome"]) ?>
                                    </strong>

                                    <?php if (!empty($cliente["email"])): ?>

                                        <div
                                            style="
                                                font-size:13px;
                                                color:#888;
                                            "
                                        >

                                            <?= htmlspecialchars($cliente["email"]) ?>

                                        </div>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $cliente["cpf_cnpj"]
                                        ?: "-"
                                    ) ?>

                                </td>


                                <td>

                                    <?= htmlspecialchars(
                                        $cliente["telefone"]
                                        ?: "-"
                                    ) ?>

                                </td>


                                <td>

                                    <?php

                                    $cidade =
                                        $cliente["cidade"]
                                        ?: "-";

                                    if (!empty($cliente["estado"])) {
                                        $cidade .=
                                            " - "
                                            . $cliente["estado"];
                                    }

                                    ?>

                                    <?= htmlspecialchars($cidade) ?>

                                </td>


                                <td>

                                    <?php if ($cliente["status"] === "ativo"): ?>

                                        <span class="badge-ativo">
                                            Ativo
                                        </span>

                                    <?php else: ?>

                                        <span class="badge-inativo">
                                            Inativo
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <td>

                                    <div class="acoes">

                                        <a
                                            href="editar.php?id=<?= $cliente["id"] ?>"
                                            class="btn-editar"
                                            title="Editar"
                                        >

                                            <i class="bi bi-pencil"></i>

                                        </a>


                                        <a
                                            href="excluir.php?id=<?= $cliente["id"] ?>"
                                            class="btn-excluir"
                                            title="Excluir"
                                            onclick="return confirmarExclusao();"
                                        >

                                            <i class="bi bi-trash"></i>

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

                    <i class="bi bi-people"></i>

                    Nenhum cliente encontrado.

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>


<script>

function confirmarExclusao() {

    return confirm(
        "Deseja realmente excluir este cliente?"
    );

}

</script>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>