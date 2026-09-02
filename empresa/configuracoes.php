<?php

require_once "../includes/auth.php";
require_once "../config/database.php";

$stmt = $pdo->query("
    SELECT *
    FROM empresa
    ORDER BY id ASC
    LIMIT 1
");

$empresa = $stmt->fetch();

if (!$empresa) {

    $empresa = [
        "id" => "",
        "razao_social" => "",
        "nome_fantasia" => "",
        "cpf_cnpj" => "",
        "telefone" => "",
        "whatsapp" => "",
        "email" => "",
        "site" => "",
        "cep" => "",
        "endereco" => "",
        "numero" => "",
        "complemento" => "",
        "bairro" => "",
        "cidade" => "",
        "estado" => "",
        "logo" => "",
        "observacoes" => ""
    ];

}

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

<title>Configurações da Empresa</title>

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
    max-width: 1050px;
    margin: 40px auto;
}

.form-box {
    background: white;
    padding: 35px;
    border-radius: 18px;
    box-shadow: 0 8px 30px rgba(0,0,0,.07);
}

.section-title {
    color: #0d47a1;
    font-size: 18px;
    font-weight: bold;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
    margin: 25px 0 20px;
}

.form-control,
.form-select {
    min-height: 48px;
    border-radius: 10px;
}

.logo-preview {
    width: 160px;
    height: 120px;
    border: 2px dashed #ddd;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    background: #fafafa;
}

.logo-preview img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
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

<div class="container-form">

    <div
        class="d-flex justify-content-between align-items-center mb-4"
    >

        <div>

            <h2 class="fw-bold">
                Dados da Empresa
            </h2>

            <div class="text-muted">
                Essas informações aparecerão nos orçamentos.
            </div>

        </div>

        <a
            href="../dashboard/"
            class="btn btn-outline-secondary"
        >

            <i class="bi bi-arrow-left"></i>
            Dashboard

        </a>

    </div>


    <?php if ($mensagem): ?>

        <div
            class="alert alert-<?= htmlspecialchars($tipoMensagem) ?>"
        >

            <?= htmlspecialchars($mensagem) ?>

        </div>

    <?php endif; ?>


    <div class="form-box">

        <form
            action="salvar.php"
            method="POST"
            enctype="multipart/form-data"
        >

            <input
                type="hidden"
                name="id"
                value="<?= htmlspecialchars($empresa["id"]) ?>"
            >


            <div class="section-title">
                Identificação
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
                        value="<?= htmlspecialchars($empresa["razao_social"]) ?>"
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
                        value="<?= htmlspecialchars($empresa["nome_fantasia"]) ?>"
                    >

                </div>


                <div class="col-md-4">

                    <label class="form-label">
                        CPF / CNPJ
                    </label>

                    <input
                        type="text"
                        name="cpf_cnpj"
                        class="form-control"
                        value="<?= htmlspecialchars($empresa["cpf_cnpj"]) ?>"
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
                        value="<?= htmlspecialchars($empresa["telefone"]) ?>"
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
                        value="<?= htmlspecialchars($empresa["whatsapp"]) ?>"
                    >

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        E-mail
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="<?= htmlspecialchars($empresa["email"]) ?>"
                    >

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Site
                    </label>

                    <input
                        type="text"
                        name="site"
                        class="form-control"
                        placeholder="www.suaempresa.com.br"
                        value="<?= htmlspecialchars($empresa["site"]) ?>"
                    >

                </div>

            </div>


            <div class="section-title">
                Logo
            </div>


            <div class="row align-items-center g-4">

                <div class="col-md-3">

                    <div class="logo-preview">

                        <?php if (!empty($empresa["logo"])): ?>

                            <img
                                src="../uploads/logo/<?= htmlspecialchars($empresa["logo"]) ?>"
                                alt="Logo"
                            >

                        <?php else: ?>

                            <span class="text-muted">
                                Sem logo
                            </span>

                        <?php endif; ?>

                    </div>

                </div>


                <div class="col-md-9">

                    <label class="form-label">
                        Selecionar logo
                    </label>

                    <input
                        type="file"
                        name="logo"
                        class="form-control"
                        accept=".jpg,.jpeg,.png,.webp"
                    >

                    <small class="text-muted">

                        Formatos aceitos:
                        JPG, PNG ou WEBP.

                    </small>

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
                        value="<?= htmlspecialchars($empresa["cep"]) ?>"
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
                        value="<?= htmlspecialchars($empresa["endereco"]) ?>"
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
                        value="<?= htmlspecialchars($empresa["numero"]) ?>"
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
                        value="<?= htmlspecialchars($empresa["complemento"]) ?>"
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
                        value="<?= htmlspecialchars($empresa["bairro"]) ?>"
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
                        value="<?= htmlspecialchars($empresa["cidade"]) ?>"
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
                        maxlength="2"
                        class="form-control"
                        value="<?= htmlspecialchars($empresa["estado"]) ?>"
                    >

                </div>

            </div>


            <div class="section-title">
                Informações adicionais
            </div>

            <textarea
                name="observacoes"
                class="form-control"
                rows="4"
                placeholder="Ex: condições gerais, dados bancários etc."
            ><?= htmlspecialchars($empresa["observacoes"]) ?></textarea>


            <div class="text-end mt-4">

                <button
                    type="submit"
                    class="btn-salvar"
                >

                    <i class="bi bi-check-lg"></i>

                    Salvar Configurações

                </button>

            </div>

        </form>

    </div>

</div>


<script>

document
    .getElementById("cep")
    .addEventListener(
        "blur",
        async function () {

            const cep =
                this.value.replace(/\D/g, "");

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

                    document.getElementById("endereco").value =
                        dados.logradouro || "";

                    document.getElementById("bairro").value =
                        dados.bairro || "";

                    document.getElementById("cidade").value =
                        dados.localidade || "";

                    document.getElementById("estado").value =
                        dados.uf || "";

                }

            } catch (erro) {

                console.log("Erro ao consultar CEP.");

            }

        }
    );

</script>

</body>
</html>