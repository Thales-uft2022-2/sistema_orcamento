<?php

require_once "../includes/auth.php";

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
        Novo Cliente
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

        .container-form {

            max-width: 1000px;

            margin:
                40px
                auto;

        }

        .header {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 25px;

        }

        .header h2 {

            font-weight: bold;

            margin: 0;

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
                1px solid
                #eee;

            padding-bottom: 10px;

            margin:
                20px
                0;

            font-size: 17px;

            font-weight: bold;

            color: #0d47a1;

        }

        .form-label {

            font-size: 14px;

            font-weight: 600;

        }

        .form-control,
        .form-select {

            min-height: 48px;

            border-radius: 10px;

        }

        textarea.form-control {

            min-height: 100px;

        }

        .btn-salvar {

            background: #0d47a1;

            color: white;

            border: none;

            padding: 12px 25px;

            border-radius: 10px;

            font-weight: bold;

        }

        .btn-salvar:hover {

            background: #08367d;

            color: white;

        }

    </style>

</head>

<body>


<div class="container-form">

    <div class="header">

        <div>

            <h2>
                Novo Cliente
            </h2>

            <div class="text-muted">
                Informe os dados do cliente.
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
            action="salvar.php"
            method="POST"
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

                        <option value="fisica">
                            Pessoa Física
                        </option>

                        <option value="juridica">
                            Pessoa Jurídica
                        </option>

                    </select>

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        CPF / CNPJ
                    </label>

                    <input
                        type="text"
                        name="cpf_cnpj"
                        id="cpf_cnpj"
                        class="form-control"
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

                        <option value="ativo">
                            Ativo
                        </option>

                        <option value="inativo">
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
                        id="cep"
                        class="form-control"
                    >

                </div>


                <div class="col-md-7">

                    <label class="form-label">
                        Endereço
                    </label>

                    <input
                        type="text"
                        name="endereco"
                        id="endereco"
                        class="form-control"
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
                    >

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        Bairro
                    </label>

                    <input
                        type="text"
                        name="bairro"
                        id="bairro"
                        class="form-control"
                    >

                </div>


                <div class="col-md-3">

                    <label class="form-label">
                        Cidade
                    </label>

                    <input
                        type="text"
                        name="cidade"
                        id="cidade"
                        class="form-control"
                    >

                </div>


                <div class="col-md-2">

                    <label class="form-label">
                        Estado
                    </label>

                    <input
                        type="text"
                        name="estado"
                        id="estado"
                        class="form-control"
                        maxlength="2"
                        placeholder="TO"
                    >

                </div>

            </div>


            <div class="section-title">
                Observações
            </div>


            <textarea
                name="observacoes"
                class="form-control"
                placeholder="Informações adicionais sobre o cliente..."
            ></textarea>


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
                    class="btn-salvar"
                >

                    <i class="bi bi-check-lg"></i>

                    Salvar Cliente

                </button>

            </div>

        </form>

    </div>

</div>


<script>

const cepInput =
    document.getElementById("cep");

cepInput.addEventListener(
    "blur",
    async function () {

        let cep =
            this.value.replace(
                /\D/g,
                ""
            );

        if (cep.length !== 8) {
            return;
        }

        try {

            const resposta =
                await fetch(
                    `https://viacep.com.br/ws/${cep}/json/`
                );

            const dados =
                await resposta.json();

            if (!dados.erro) {

                document.getElementById(
                    "endereco"
                ).value =
                    dados.logradouro || "";

                document.getElementById(
                    "bairro"
                ).value =
                    dados.bairro || "";

                document.getElementById(
                    "cidade"
                ).value =
                    dados.localidade || "";

                document.getElementById(
                    "estado"
                ).value =
                    dados.uf || "";

            }

        } catch (erro) {

            console.log(
                "Não foi possível consultar o CEP."
            );

        }

    }
);

</script>

</body>
</html>