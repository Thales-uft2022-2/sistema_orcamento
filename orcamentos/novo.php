<?php

require_once "../includes/auth.php";
require_once "../config/database.php";


$clientes = $pdo->query("
    SELECT id, nome, cpf_cnpj
    FROM clientes
    WHERE status = 'ativo'
    ORDER BY nome
")->fetchAll();


$servicos = $pdo->query("
    SELECT
        id,
        nome,
        descricao,
        valor,
        unidade
    FROM servicos
    WHERE status = 'ativo'
    ORDER BY nome
")->fetchAll();


$ultimo = $pdo->query("
    SELECT MAX(id) AS ultimo
    FROM orcamentos
")->fetch();

$proximoNumero =
    ((int) ($ultimo["ultimo"] ?? 0)) + 1;

$numero =
    "ORC-" .
    date("Y") .
    "-" .
    str_pad(
        $proximoNumero,
        5,
        "0",
        STR_PAD_LEFT
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

<title>Novo Orçamento</title>

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

.container-orcamento {
    max-width: 1200px;
    margin: 40px auto;
}

.card-box {
    background: white;
    padding: 30px;
    border-radius: 18px;
    box-shadow: 0 8px 30px rgba(0,0,0,.07);
    margin-bottom: 25px;
}

.section-title {
    color: #0d47a1;
    font-weight: bold;
    font-size: 18px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
    margin-bottom: 20px;
}

.form-control,
.form-select {
    min-height: 48px;
    border-radius: 10px;
}

.table-itens th {
    background: #f5f7fb;
}

.table-itens td {
    vertical-align: middle;
}

.btn-adicionar {
    background: #198754;
    color: white;
    border: none;
    border-radius: 9px;
    padding: 10px 18px;
}

.btn-remover {
    border: none;
    width: 38px;
    height: 38px;
    border-radius: 8px;
    background: #fee2e2;
    color: #dc2626;
}

.total-box {
    background: #f5f7fb;
    border-radius: 15px;
    padding: 25px;
}

.total-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
}

.total-final {
    font-size: 27px;
    font-weight: bold;
    color: #0d47a1;
    border-top: 1px solid #ddd;
    padding-top: 15px;
}

.btn-salvar {
    background: #0d47a1;
    color: white;
    border: none;
    padding: 13px 28px;
    border-radius: 10px;
    font-weight: bold;
}

</style>

</head>

<body>

<div class="container-orcamento">

    <div
        class="d-flex justify-content-between align-items-center mb-4"
    >

        <div>

            <h2 class="fw-bold">
                Novo Orçamento
            </h2>

            <div class="text-muted">

                Crie um orçamento para seu cliente.

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


    <form
        action="salvar.php"
        method="POST"
        id="formOrcamento"
    >


        <div class="card-box">

            <div class="section-title">
                Informações do Orçamento
            </div>


            <div class="row g-3">

                <div class="col-md-3">

                    <label class="form-label">
                        Número
                    </label>

                    <input
                        type="text"
                        name="numero"
                        class="form-control"
                        value="<?= htmlspecialchars($numero) ?>"
                        readonly
                    >

                </div>


                <div class="col-md-5">

                    <label class="form-label">
                        Cliente *
                    </label>

                    <select
                        name="cliente_id"
                        class="form-select"
                        required
                    >

                        <option value="">
                            Selecione o cliente
                        </option>

                        <?php foreach ($clientes as $cliente): ?>

                            <option
                                value="<?= $cliente["id"] ?>"
                            >

                                <?= htmlspecialchars($cliente["nome"]) ?>

                                <?php if ($cliente["cpf_cnpj"]): ?>

                                    -
                                    <?= htmlspecialchars($cliente["cpf_cnpj"]) ?>

                                <?php endif; ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <div class="col-md-2">

                    <label class="form-label">
                        Data
                    </label>

                    <input
                        type="date"
                        name="data_orcamento"
                        class="form-control"
                        value="<?= date("Y-m-d") ?>"
                        required
                    >

                </div>


                <div class="col-md-2">

                    <label class="form-label">
                        Validade
                    </label>

                    <input
                        type="date"
                        name="validade"
                        class="form-control"
                        value="<?= date(
                            "Y-m-d",
                            strtotime("+15 days")
                        ) ?>"
                    >

                </div>

            </div>

        </div>


        <div class="card-box">

            <div
                class="d-flex justify-content-between align-items-center mb-3"
            >

                <div class="section-title mb-0">
                    Serviços
                </div>

                <button
                    type="button"
                    class="btn-adicionar"
                    onclick="adicionarItem()"
                >

                    <i class="bi bi-plus-lg"></i>

                    Adicionar Serviço

                </button>

            </div>


            <div class="table-responsive">

                <table
                    class="table table-itens"
                    id="tabelaItens"
                >

                    <thead>

                        <tr>

                            <th style="width:30%">
                                Serviço
                            </th>

                            <th>
                                Descrição
                            </th>

                            <th style="width:100px">
                                Qtd.
                            </th>

                            <th style="width:150px">
                                Valor
                            </th>

                            <th style="width:150px">
                                Total
                            </th>

                            <th style="width:60px"></th>

                        </tr>

                    </thead>

                    <tbody id="itensBody"></tbody>

                </table>

            </div>

        </div>


        <div class="row g-4">

            <div class="col-lg-7">

                <div class="card-box h-100">

                    <div class="section-title">
                        Informações adicionais
                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="pendente">
                                Pendente
                            </option>

                            <option value="aprovado">
                                Aprovado
                            </option>

                            <option value="recusado">
                                Recusado
                            </option>

                            <option value="finalizado">
                                Finalizado
                            </option>

                        </select>

                    </div>


                    <div>

                        <label class="form-label">
                            Observações
                        </label>

                        <textarea
                            name="observacoes"
                            class="form-control"
                            rows="6"
                            placeholder="Condições de pagamento, prazo de execução, garantia..."
                        ></textarea>

                    </div>

                </div>

            </div>


            <div class="col-lg-5">

                <div class="card-box">

                    <div class="section-title">
                        Resumo
                    </div>


                    <div class="total-box">

                        <div class="total-row">

                            <span>
                                Subtotal
                            </span>

                            <strong id="subtotalTexto">
                                R$ 0,00
                            </strong>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">
                                Desconto
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    R$
                                </span>

                                <input
                                    type="number"
                                    name="desconto"
                                    id="desconto"
                                    class="form-control"
                                    value="0.00"
                                    step="0.01"
                                    min="0"
                                >

                            </div>

                        </div>


                        <div class="total-row total-final">

                            <span>TOTAL</span>

                            <span id="totalTexto">
                                R$ 0,00
                            </span>

                        </div>


                        <input
                            type="hidden"
                            name="subtotal"
                            id="subtotal"
                            value="0"
                        >

                        <input
                            type="hidden"
                            name="total"
                            id="total"
                            value="0"
                        >

                    </div>


                    <button
                        type="submit"
                        class="btn-salvar w-100 mt-4"
                    >

                        <i class="bi bi-check-lg"></i>

                        Salvar Orçamento

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>


<script>

const servicos = <?= json_encode(
    $servicos,
    JSON_UNESCAPED_UNICODE |
    JSON_UNESCAPED_SLASHES
) ?>;


let contador = 0;


function formatarMoeda(valor) {

    return valor.toLocaleString(
        "pt-BR",
        {
            style: "currency",
            currency: "BRL"
        }
    );

}


function gerarOpcoes() {

    let html =
        '<option value="">Selecione...</option>';


    servicos.forEach(servico => {

        html += `

            <option
                value="${servico.id}"
                data-valor="${servico.valor}"
                data-descricao="${escaparHtml(servico.descricao || servico.nome)}"
            >

                ${escaparHtml(servico.nome)}

            </option>

        `;

    });


    return html;

}


function escaparHtml(texto) {

    const div =
        document.createElement("div");

    div.textContent =
        texto ?? "";

    return div.innerHTML;

}


function adicionarItem() {

    contador++;


    const tbody =
        document.getElementById("itensBody");


    const linha =
        document.createElement("tr");


    linha.innerHTML = `

        <td>

            <select
                name="servico_id[]"
                class="form-select servico"
                onchange="selecionarServico(this)"
                required
            >

                ${gerarOpcoes()}

            </select>

        </td>


        <td>

            <input
                type="text"
                name="descricao[]"
                class="form-control descricao"
                required
            >

        </td>


        <td>

            <input
                type="number"
                name="quantidade[]"
                class="form-control quantidade"
                value="1"
                min="0.01"
                step="0.01"
                oninput="calcularTudo()"
                required
            >

        </td>


        <td>

            <input
                type="number"
                name="valor_unitario[]"
                class="form-control valor-unitario"
                value="0.00"
                min="0"
                step="0.01"
                oninput="calcularTudo()"
                required
            >

        </td>


        <td>

            <strong class="total-item">
                R$ 0,00
            </strong>

        </td>


        <td>

            <button
                type="button"
                class="btn-remover"
                onclick="removerItem(this)"
                title="Remover"
            >

                <i class="bi bi-trash"></i>

            </button>

        </td>

    `;


    tbody.appendChild(linha);


    calcularTudo();

}


function selecionarServico(select) {

    const option =
        select.options[
            select.selectedIndex
        ];


    const linha =
        select.closest("tr");


    const descricao =
        linha.querySelector(
            ".descricao"
        );


    const valor =
        linha.querySelector(
            ".valor-unitario"
        );


    descricao.value =
        option.dataset.descricao || "";


    valor.value =
        parseFloat(
            option.dataset.valor || 0
        ).toFixed(2);


    calcularTudo();

}


function removerItem(botao) {

    const linhas =
        document.querySelectorAll(
            "#itensBody tr"
        );


    if (linhas.length <= 1) {

        alert(
            "O orçamento precisa ter pelo menos um serviço."
        );

        return;

    }


    botao.closest("tr").remove();

    calcularTudo();

}


function calcularTudo() {

    const linhas =
        document.querySelectorAll(
            "#itensBody tr"
        );


    let subtotal = 0;


    linhas.forEach(linha => {

        const quantidade =
            parseFloat(
                linha.querySelector(
                    ".quantidade"
                ).value
            ) || 0;


        const valor =
            parseFloat(
                linha.querySelector(
                    ".valor-unitario"
                ).value
            ) || 0;


        const totalItem =
            quantidade * valor;


        subtotal += totalItem;


        linha.querySelector(
            ".total-item"
        ).textContent =
            formatarMoeda(totalItem);

    });


    let desconto =
        parseFloat(
            document.getElementById(
                "desconto"
            ).value
        ) || 0;


    if (desconto < 0) {
        desconto = 0;
    }


    let total =
        subtotal - desconto;


    if (total < 0) {
        total = 0;
    }


    document.getElementById(
        "subtotalTexto"
    ).textContent =
        formatarMoeda(subtotal);


    document.getElementById(
        "totalTexto"
    ).textContent =
        formatarMoeda(total);


    document.getElementById(
        "subtotal"
    ).value =
        subtotal.toFixed(2);


    document.getElementById(
        "total"
    ).value =
        total.toFixed(2);

}


document
    .getElementById("desconto")
    .addEventListener(
        "input",
        calcularTudo
    );


document
    .getElementById("formOrcamento")
    .addEventListener(
        "submit",
        function(event) {

            const linhas =
                document.querySelectorAll(
                    "#itensBody tr"
                );

            if (linhas.length === 0) {

                event.preventDefault();

                alert(
                    "Adicione pelo menos um serviço."
                );

            }

        }
    );


adicionarItem();

</script>

</body>
</html>