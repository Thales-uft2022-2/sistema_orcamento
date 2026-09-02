<?php

require_once "../includes/auth.php";

$mensagem = $_SESSION["mensagem"] ?? null;

unset($_SESSION["mensagem"]);

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Novo Serviço</title>

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
            font-size: 17px;
            font-weight: bold;
            color: #0d47a1;
        }

        .form-control,
        .form-select {
            min-height: 48px;
            border-radius: 10px;
        }

        textarea.form-control {
            min-height: 120px;
        }

        .btn-salvar {
            background: #0d47a1;
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: bold;
        }

        .btn-salvar:hover {
            background: #08367d;
        }

        .resumo {
            background: #f5f7fb;
            border-radius: 15px;
            padding: 20px;
            margin-top: 25px;
        }

        .margem {
            font-size: 25px;
            font-weight: bold;
            color: #0d47a1;
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
                Novo Serviço
            </h2>

            <div class="text-muted">
                Cadastre um serviço para utilizar nos orçamentos.
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


    <?php if ($mensagem): ?>

        <div class="alert alert-danger">

            <?= htmlspecialchars($mensagem) ?>

        </div>

    <?php endif; ?>


    <div class="form-box">

        <form
            action="salvar.php"
            method="POST"
            id="formServico"
        >

            <div class="section-title">
                Informações do Serviço
            </div>


            <div class="row g-3">

                <div class="col-md-8">

                    <label class="form-label">
                        Nome do Serviço *
                    </label>

                    <input
                        type="text"
                        name="nome"
                        class="form-control"
                        placeholder="Ex: Instalação de computador"
                        required
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
                        placeholder="Ex: Informática"
                    >

                </div>


                <div class="col-md-12">

                    <label class="form-label">
                        Descrição
                    </label>

                    <textarea
                        name="descricao"
                        class="form-control"
                        placeholder="Descreva o serviço..."
                    ></textarea>

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

                        <option value="Serviço">
                            Serviço
                        </option>

                        <option value="Hora">
                            Hora
                        </option>

                        <option value="Diária">
                            Diária
                        </option>

                        <option value="Unidade">
                            Unidade
                        </option>

                        <option value="Metro">
                            Metro
                        </option>

                        <option value="M²">
                            M²
                        </option>

                        <option value="Pacote">
                            Pacote
                        </option>

                    </select>

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        Custo
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            R$
                        </span>

                        <input
                            type="number"
                            name="custo"
                            id="custo"
                            class="form-control"
                            step="0.01"
                            min="0"
                            value="0.00"
                        >

                    </div>

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        Valor de Venda *
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            R$
                        </span>

                        <input
                            type="number"
                            name="valor"
                            id="valor"
                            class="form-control"
                            step="0.01"
                            min="0"
                            value="0.00"
                            required
                        >

                    </div>

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        Status
                    </label>

                    <select
                        name="status"
                        class="form-select"
                    >

                        <option value="ativo">
                            Ativo
                        </option>

                        <option value="inativo">
                            Inativo
                        </option>

                    </select>

                </div>

            </div>


            <div class="resumo">

                <div class="text-muted">
                    Margem estimada
                </div>

                <div
                    class="margem"
                    id="margem"
                >
                    0,00%
                </div>

                <small class="text-muted">

                    Diferença percentual entre custo e valor de venda.

                </small>

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
                    class="btn-salvar"
                >

                    <i class="bi bi-check-lg"></i>

                    Salvar Serviço

                </button>

            </div>

        </form>

    </div>

</div>


<script>

const custo =
    document.getElementById("custo");

const valor =
    document.getElementById("valor");

const margem =
    document.getElementById("margem");


function calcularMargem() {

    const custoValor =
        parseFloat(custo.value) || 0;

    const vendaValor =
        parseFloat(valor.value) || 0;


    if (vendaValor <= 0) {

        margem.textContent = "0,00%";
        return;

    }


    const percentual =
        ((vendaValor - custoValor) / vendaValor) * 100;


    margem.textContent =
        percentual.toLocaleString(
            "pt-BR",
            {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }
        ) + "%";

}


custo.addEventListener(
    "input",
    calcularMargem
);

valor.addEventListener(
    "input",
    calcularMargem
);

</script>

</body>
</html>