<?php

require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;


/*
====================================================
FUNÇÕES
====================================================
*/

function e($valor)
{
    return htmlspecialchars(
        (string) ($valor ?? ""),
        ENT_QUOTES,
        "UTF-8"
    );
}


function moeda($valor)
{
    return "R$ " . number_format(
        (float) $valor,
        2,
        ",",
        "."
    );
}


function formatarData($data)
{
    if (
        empty($data) ||
        $data === "0000-00-00"
    ) {
        return "-";
    }

    return date(
        "d/m/Y",
        strtotime($data)
    );
}


function formatarQuantidade($valor)
{
    $valor = (float) $valor;

    if (floor($valor) == $valor) {
        return number_format(
            $valor,
            0,
            ",",
            "."
        );
    }

    return number_format(
        $valor,
        2,
        ",",
        "."
    );
}


/*
====================================================
VALIDAR ID
====================================================
*/

$id = filter_input(
    INPUT_GET,
    "id",
    FILTER_VALIDATE_INT
);


if (!$id) {

    http_response_code(400);

    die(
        "Orçamento inválido."
    );

}


/*
====================================================
BUSCAR EMPRESA
====================================================
*/

$stmtEmpresa = $pdo->query("
    SELECT *
    FROM empresa
    ORDER BY id ASC
    LIMIT 1
");


$empresa = $stmtEmpresa->fetch();


if (!$empresa) {

    $empresa = [

        "razao_social" => "Minha Empresa",
        "nome_fantasia" => "Meu Orçamento",
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


/*
====================================================
BUSCAR ORÇAMENTO + CLIENTE
====================================================
*/

$sql = "
    SELECT

        o.id,
        o.numero,
        o.cliente_id,
        o.data_orcamento,
        o.validade,
        o.subtotal,
        o.desconto,
        o.total,
        o.status,
        o.observacoes,
        o.criado_em,

        c.nome AS cliente_nome,
        c.tipo_pessoa,
        c.cpf_cnpj,
        c.telefone,
        c.whatsapp,
        c.email,
        c.cep AS cliente_cep,
        c.endereco AS cliente_endereco,
        c.numero AS cliente_numero,
        c.complemento AS cliente_complemento,
        c.bairro AS cliente_bairro,
        c.cidade AS cliente_cidade,
        c.estado AS cliente_estado

    FROM orcamentos o

    INNER JOIN clientes c
        ON c.id = o.cliente_id

    WHERE o.id = :id

    LIMIT 1
";


$stmt = $pdo->prepare($sql);


$stmt->execute([
    ":id" => $id
]);


$orcamento = $stmt->fetch();


if (!$orcamento) {

    http_response_code(404);

    die(
        "Orçamento não encontrado."
    );

}


/*
====================================================
BUSCAR ITENS
====================================================
*/

$stmtItens = $pdo->prepare("
    SELECT

        oi.id,
        oi.servico_id,
        oi.descricao,
        oi.quantidade,
        oi.valor_unitario,
        oi.total,

        s.nome AS servico_nome,
        s.unidade

    FROM orcamento_itens oi

    LEFT JOIN servicos s
        ON s.id = oi.servico_id

    WHERE oi.orcamento_id = :orcamento_id

    ORDER BY oi.id ASC
");


$stmtItens->execute([
    ":orcamento_id" => $id
]);


$itens = $stmtItens->fetchAll();


/*
====================================================
LOGO EM BASE64
====================================================
*/

$logoHtml = "";


if (!empty($empresa["logo"])) {

    $arquivoLogo =
        __DIR__
        . "/../uploads/logo/"
        . basename($empresa["logo"]);


    if (file_exists($arquivoLogo)) {

        $mimeType =
            mime_content_type(
                $arquivoLogo
            );


        $tiposPermitidos = [

            "image/jpeg",
            "image/png",
            "image/webp"

        ];


        if (
            in_array(
                $mimeType,
                $tiposPermitidos,
                true
            )
        ) {

            $conteudoImagem =
                file_get_contents(
                    $arquivoLogo
                );


            $imagemBase64 =
                base64_encode(
                    $conteudoImagem
                );


            $logoHtml = '

                <img
                    src="data:' .
                    e($mimeType) .
                    ';base64,' .
                    $imagemBase64 .
                    '"
                    class="logo"
                    alt="Logo"
                >

            ';

        }

    }

}


/*
====================================================
NOME DA EMPRESA
====================================================
*/

$nomeEmpresa =
    !empty($empresa["nome_fantasia"])
        ? $empresa["nome_fantasia"]
        : $empresa["razao_social"];


if (empty($nomeEmpresa)) {

    $nomeEmpresa =
        "Minha Empresa";

}


/*
====================================================
ENDEREÇO DA EMPRESA
====================================================
*/

$partesEnderecoEmpresa = [];


if (!empty($empresa["endereco"])) {

    $endereco =
        $empresa["endereco"];


    if (!empty($empresa["numero"])) {

        $endereco .=
            ", "
            . $empresa["numero"];

    }


    $partesEnderecoEmpresa[] =
        $endereco;

}


if (!empty($empresa["bairro"])) {

    $partesEnderecoEmpresa[] =
        $empresa["bairro"];

}


$cidadeEmpresa = "";


if (!empty($empresa["cidade"])) {

    $cidadeEmpresa =
        $empresa["cidade"];

}


if (!empty($empresa["estado"])) {

    if ($cidadeEmpresa !== "") {

        $cidadeEmpresa .=
            " - ";

    }

    $cidadeEmpresa .=
        $empresa["estado"];

}


if ($cidadeEmpresa !== "") {

    $partesEnderecoEmpresa[] =
        $cidadeEmpresa;

}


$enderecoEmpresa =
    implode(
        " | ",
        $partesEnderecoEmpresa
    );


/*
====================================================
ENDEREÇO DO CLIENTE
====================================================
*/

$enderecoCliente = "";


if (!empty($orcamento["cliente_endereco"])) {

    $enderecoCliente =
        $orcamento["cliente_endereco"];


    if (!empty($orcamento["cliente_numero"])) {

        $enderecoCliente .=
            ", "
            . $orcamento["cliente_numero"];

    }

}


if (!empty($orcamento["cliente_complemento"])) {

    if ($enderecoCliente !== "") {

        $enderecoCliente .=
            " - ";

    }

    $enderecoCliente .=
        $orcamento["cliente_complemento"];

}


if (!empty($orcamento["cliente_bairro"])) {

    if ($enderecoCliente !== "") {

        $enderecoCliente .=
            " - ";

    }

    $enderecoCliente .=
        $orcamento["cliente_bairro"];

}


if (!empty($orcamento["cliente_cidade"])) {

    if ($enderecoCliente !== "") {

        $enderecoCliente .=
            " - ";

    }

    $enderecoCliente .=
        $orcamento["cliente_cidade"];

}


if (!empty($orcamento["cliente_estado"])) {

    $enderecoCliente .=
        "/"
        . $orcamento["cliente_estado"];

}


/*
====================================================
STATUS
====================================================
*/

$statusTexto =
    ucfirst(
        $orcamento["status"]
    );


$statusClasse =
    "status-pendente";


switch ($orcamento["status"]) {

    case "aprovado":

        $statusClasse =
            "status-aprovado";

        break;


    case "recusado":

        $statusClasse =
            "status-recusado";

        break;


    case "finalizado":

        $statusClasse =
            "status-finalizado";

        break;

}


/*
====================================================
MONTAR HTML DO PDF
====================================================
*/

$html = '
<!DOCTYPE html>

<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<style>

/*
================================================
PÁGINA
================================================
*/

@page {

    margin:
        25px
        32px
        35px
        32px;

}


* {

    box-sizing: border-box;

}


body {

    font-family:
        DejaVu Sans,
        sans-serif;

    font-size: 11px;

    color: #252525;

    line-height: 1.45;

}


/*
================================================
CABEÇALHO
================================================
*/

.header-table {

    width: 100%;

    border-collapse: collapse;

    margin-bottom: 12px;

}


.header-table td {

    vertical-align: middle;

}


.logo-area {

    width: 28%;

}


.logo {

    max-width: 150px;

    max-height: 80px;

}


.empresa-area {

    width: 72%;

    text-align: right;

}


.empresa-nome {

    color: #0d47a1;

    font-size: 20px;

    font-weight: bold;

    margin-bottom: 4px;

}


.empresa-razao {

    font-size: 11px;

    font-weight: bold;

}


.empresa-info {

    color: #555;

    font-size: 9.5px;

    line-height: 1.5;

}


/*
================================================
LINHA PRINCIPAL
================================================
*/

.top-line {

    border-top:
        3px solid
        #0d47a1;

    margin-bottom: 18px;

}


/*
================================================
TÍTULO
================================================
*/

.document-header {

    background: #0d47a1;

    color: white;

    padding: 13px 15px;

    margin-bottom: 18px;

}


.document-table {

    width: 100%;

    border-collapse: collapse;

}


.document-table td {

    color: white;

    vertical-align: middle;

}


.document-title {

    font-size: 21px;

    font-weight: bold;

}


.document-number {

    margin-top: 2px;

    font-size: 11px;

}


.document-info {

    text-align: right;

    font-size: 10px;

    line-height: 1.7;

}


/*
================================================
SEÇÕES
================================================
*/

.section {

    margin-bottom: 18px;

}


.section-title {

    color: #0d47a1;

    font-size: 11px;

    font-weight: bold;

    text-transform: uppercase;

    border-bottom:
        1px solid
        #d8dde5;

    padding-bottom: 5px;

    margin-bottom: 10px;

}


/*
================================================
CLIENTE
================================================
*/

.client-table {

    width: 100%;

    border-collapse: collapse;

}


.client-table td {

    padding:
        3px
        5px
        3px
        0;

    vertical-align: top;

}


.label {

    color: #777;

    font-size: 8px;

    text-transform: uppercase;

}


.client-name {

    font-size: 14px;

    font-weight: bold;

    color: #222;

}


/*
================================================
STATUS
================================================
*/

.status {

    display: inline-block;

    padding:
        4px
        8px;

    border-radius: 10px;

    font-size: 8px;

    font-weight: bold;

}


.status-pendente {

    background: #fff3cd;

    color: #856404;

}


.status-aprovado {

    background: #d4edda;

    color: #155724;

}


.status-recusado {

    background: #f8d7da;

    color: #721c24;

}


.status-finalizado {

    background: #dbeafe;

    color: #1e40af;

}


/*
================================================
ITENS
================================================
*/

.items-table {

    width: 100%;

    border-collapse: collapse;

}


.items-table thead th {

    background: #0d47a1;

    color: white;

    padding: 9px 7px;

    font-size: 9px;

    text-transform: uppercase;

    border:
        1px solid
        #0d47a1;

}


.items-table tbody td {

    padding: 9px 7px;

    border-bottom:
        1px solid
        #e3e6ea;

    vertical-align: top;

}


.items-table tbody tr:nth-child(even) {

    background: #f8fafc;

}


.center {

    text-align: center;

}


.right {

    text-align: right;

}


/*
================================================
TOTAIS
================================================
*/

.summary-wrapper {

    width: 100%;

    margin-top: 18px;

}


.summary-table {

    width: 42%;

    margin-left: 58%;

    border-collapse: collapse;

}


.summary-table td {

    padding:
        6px
        8px;

}


.summary-label {

    color: #666;

}


.summary-value {

    text-align: right;

    font-weight: bold;

}


.discount {

    color: #c62828;

}


.grand-total td {

    background: #f0f5fc;

    color: #0d47a1;

    font-size: 14px;

    font-weight: bold;

    border-top:
        2px solid
        #0d47a1;

    padding:
        10px
        8px;

}


/*
================================================
OBSERVAÇÕES
================================================
*/

.observacoes {

    background: #f7f8fa;

    border-left:
        4px solid
        #0d47a1;

    padding: 12px;

    margin-top: 22px;

}


.observacoes-title {

    color: #0d47a1;

    font-weight: bold;

    margin-bottom: 5px;

}


/*
================================================
VALIDADE
================================================
*/

.validade-box {

    margin-top: 18px;

    border:
        1px solid
        #e0e3e7;

    padding: 10px;

    background: #fafafa;

}


/*
================================================
ASSINATURAS
================================================
*/

.signature-table {

    width: 100%;

    border-collapse: collapse;

    margin-top: 65px;

}


.signature-table td {

    width: 50%;

    text-align: center;

    vertical-align: top;

    padding:
        0
        25px;

}


.signature-line {

    border-top:
        1px solid
        #555;

    padding-top: 7px;

    font-size: 9px;

}


.signature-name {

    font-weight: bold;

    font-size: 10px;

}


/*
================================================
RODAPÉ
================================================
*/

.footer {

    position: fixed;

    bottom: -23px;

    left: 0;

    right: 0;

    border-top:
        1px solid
        #ddd;

    padding-top: 5px;

    text-align: center;

    color: #777;

    font-size: 8px;

}

</style>

</head>


<body>


<!-- CABEÇALHO -->

<table class="header-table">

<tr>

<td class="logo-area">

    ' . $logoHtml . '

</td>


<td class="empresa-area">

    <div class="empresa-nome">

        ' . e($nomeEmpresa) . '

    </div>
';


if (
    !empty($empresa["razao_social"]) &&
    $empresa["razao_social"] !== $nomeEmpresa
) {

    $html .= '

    <div class="empresa-razao">

        ' .
        e(
            $empresa["razao_social"]
        )
        . '

    </div>

    ';

}


$html .= '

    <div class="empresa-info">
';


if (!empty($empresa["cpf_cnpj"])) {

    $html .= '

        CPF/CNPJ:
        ' .
        e(
            $empresa["cpf_cnpj"]
        )
        . '

        <br>

    ';

}


if ($enderecoEmpresa !== "") {

    $html .= '

        ' .
        e(
            $enderecoEmpresa
        )
        . '

        <br>

    ';

}


if (!empty($empresa["telefone"])) {

    $html .= '

        Telefone:
        ' .
        e(
            $empresa["telefone"]
        )
        . '

    ';

}


if (!empty($empresa["whatsapp"])) {

    if (!empty($empresa["telefone"])) {

        $html .= " | ";

    }

    $html .= '

        WhatsApp:
        ' .
        e(
            $empresa["whatsapp"]
        );

}


if (
    !empty($empresa["telefone"]) ||
    !empty($empresa["whatsapp"])
) {

    $html .= "<br>";

}


if (!empty($empresa["email"])) {

    $html .=
        e(
            $empresa["email"]
        );

}


if (!empty($empresa["site"])) {

    if (!empty($empresa["email"])) {

        $html .= " | ";

    }

    $html .=
        e(
            $empresa["site"]
        );

}


$html .= '

    </div>

</td>

</tr>

</table>


<div class="top-line"></div>


<!-- IDENTIFICAÇÃO DO ORÇAMENTO -->

<div class="document-header">

<table class="document-table">

<tr>

<td>

    <div class="document-title">

        ORÇAMENTO

    </div>

    <div class="document-number">

        ' .
        e(
            $orcamento["numero"]
        )
        . '

    </div>

</td>


<td class="document-info">

    Data:
    <strong>
        ' .
        formatarData(
            $orcamento["data_orcamento"]
        )
        . '
    </strong>

    <br>

    Validade:
    <strong>
        ' .
        formatarData(
            $orcamento["validade"]
        )
        . '
    </strong>

</td>

</tr>

</table>

</div>


<!-- CLIENTE -->

<div class="section">

<div class="section-title">

    Dados do Cliente

</div>


<table class="client-table">

<tr>

<td width="60%">

    <div class="label">
        Cliente
    </div>

    <div class="client-name">

        ' .
        e(
            $orcamento["cliente_nome"]
        )
        . '

    </div>

</td>


<td width="40%">

    <div class="label">
        CPF / CNPJ
    </div>

    <div>

        ' .
        e(
            $orcamento["cpf_cnpj"]
            ?: "-"
        )
        . '

    </div>

</td>

</tr>


<tr>

<td>

    <div class="label">
        Telefone
    </div>

    <div>

        ' .
        e(
            $orcamento["telefone"]
            ?: "-"
        )
        . '

    </div>

</td>


<td>

    <div class="label">
        WhatsApp
    </div>

    <div>

        ' .
        e(
            $orcamento["whatsapp"]
            ?: "-"
        )
        . '

    </div>

</td>

</tr>


<tr>

<td>

    <div class="label">
        E-mail
    </div>

    <div>

        ' .
        e(
            $orcamento["email"]
            ?: "-"
        )
        . '

    </div>

</td>


<td>

    <div class="label">
        Status do orçamento
    </div>

    <div>

        <span class="status ' .
        e(
            $statusClasse
        )
        . '">

            ' .
            e(
                $statusTexto
            )
            . '

        </span>

    </div>

</td>

</tr>
';


if ($enderecoCliente !== "") {

    $html .= '

    <tr>

        <td colspan="2">

            <div class="label">
                Endereço
            </div>

            <div>

                ' .
                e(
                    $enderecoCliente
                )
                . '

            </div>

        </td>

    </tr>

    ';

}


$html .= '

</table>

</div>


<!-- ITENS -->

<div class="section">

<div class="section-title">

    Serviços / Itens

</div>


<table class="items-table">

<thead>

<tr>

<th width="7%">

    Item

</th>

<th width="45%">

    Descrição

</th>

<th width="12%">

    Qtd.

</th>

<th width="17%">

    Valor Unit.

</th>

<th width="19%">

    Total

</th>

</tr>

</thead>


<tbody>
';


if (count($itens) > 0) {

    $contadorItem = 1;


    foreach ($itens as $item) {

        $descricaoItem =
            $item["descricao"];


        if (
            empty($descricaoItem) &&
            !empty($item["servico_nome"])
        ) {

            $descricaoItem =
                $item["servico_nome"];

        }


        $html .= '

        <tr>

            <td class="center">

                ' .
                $contadorItem
                . '

            </td>


            <td>

                <strong>

                    ' .
                    e(
                        $descricaoItem
                    )
                    . '

                </strong>
        ';


        if (!empty($item["unidade"])) {

            $html .= '

                <br>

                <span
                    style="
                        color:#888;
                        font-size:8px;
                    "
                >

                    Unidade:
                    ' .
                    e(
                        $item["unidade"]
                    )
                    . '

                </span>

            ';

        }


        $html .= '

            </td>


            <td class="center">

                ' .
                formatarQuantidade(
                    $item["quantidade"]
                )
                . '

            </td>


            <td class="right">

                ' .
                moeda(
                    $item["valor_unitario"]
                )
                . '

            </td>


            <td class="right">

                <strong>

                    ' .
                    moeda(
                        $item["total"]
                    )
                    . '

                </strong>

            </td>

        </tr>
        ';


        $contadorItem++;

    }

} else {

    $html .= '

        <tr>

            <td
                colspan="5"
                class="center"
            >

                Nenhum item encontrado.

            </td>

        </tr>

    ';

}


$html .= '

</tbody>

</table>


<!-- TOTAIS -->

<div class="summary-wrapper">

<table class="summary-table">

<tr>

<td class="summary-label">

    Subtotal

</td>

<td class="summary-value">

    ' .
    moeda(
        $orcamento["subtotal"]
    )
    . '

</td>

</tr>


<tr>

<td class="summary-label">

    Desconto

</td>

<td class="summary-value discount">

    - ' .
    moeda(
        $orcamento["desconto"]
    )
    . '

</td>

</tr>


<tr class="grand-total">

<td>

    TOTAL

</td>

<td class="right">

    ' .
    moeda(
        $orcamento["total"]
    )
    . '

</td>

</tr>

</table>

</div>

</div>
';


/*
====================================================
OBSERVAÇÕES
====================================================
*/

if (!empty($orcamento["observacoes"])) {

    $html .= '

    <div class="observacoes">

        <div class="observacoes-title">

            Observações

        </div>

        ' .
        nl2br(
            e(
                $orcamento["observacoes"]
            )
        )
        . '

    </div>

    ';

}


/*
====================================================
VALIDADE
====================================================
*/

$html .= '

<div class="validade-box">

    Este orçamento foi emitido em

    <strong>

        ' .
        formatarData(
            $orcamento["data_orcamento"]
        )
        . '

    </strong>
';


if (!empty($orcamento["validade"])) {

    $html .= '

        e é válido até

        <strong>

            ' .
            formatarData(
                $orcamento["validade"]
            )
            . '

        </strong>.

    ';

} else {

    $html .= ".";

}


$html .= '

</div>


<!-- ASSINATURAS -->

<table class="signature-table">

<tr>

<td>

    <div class="signature-line">

        <div class="signature-name">

            ' .
            e(
                $nomeEmpresa
            )
            . '

        </div>

        Responsável / Empresa

    </div>

</td>


<td>

    <div class="signature-line">

        <div class="signature-name">

            ' .
            e(
                $orcamento["cliente_nome"]
            )
            . '

        </div>

        Cliente

    </div>

</td>

</tr>

</table>


<!-- RODAPÉ -->

<div class="footer">

    ' .
    e(
        $nomeEmpresa
    )
    . '

    &nbsp; • &nbsp;

    Orçamento
    ' .
    e(
        $orcamento["numero"]
    )
    . '

</div>


</body>

</html>
';


/*
====================================================
CONFIGURAÇÃO DO DOMPDF
====================================================
*/

$options = new Options();


$options->set(
    "isRemoteEnabled",
    true
);


$options->set(
    "isHtml5ParserEnabled",
    true
);


$options->set(
    "defaultFont",
    "DejaVu Sans"
);


$dompdf =
    new Dompdf(
        $options
    );


/*
====================================================
CARREGAR HTML
====================================================
*/

$dompdf->loadHtml(
    $html,
    "UTF-8"
);


/*
====================================================
TAMANHO DA PÁGINA
====================================================
*/

$dompdf->setPaper(
    "A4",
    "portrait"
);


/*
====================================================
GERAR PDF
====================================================
*/

$dompdf->render();


/*
====================================================
NÚMERO DE PÁGINA
====================================================
*/

$canvas =
    $dompdf->getCanvas();


$fontMetrics =
    $dompdf->getFontMetrics();


$font =
    $fontMetrics->getFont(
        "DejaVu Sans",
        "normal"
    );


$canvas->page_text(
    520,
    815,
    "Página {PAGE_NUM} de {PAGE_COUNT}",
    $font,
    7,
    [
        100,
        100,
        100
    ]
);


/*
====================================================
NOME DO ARQUIVO
====================================================
*/

$numeroArquivo =
    preg_replace(
        "/[^A-Za-z0-9_-]/",
        "",
        $orcamento["numero"]
    );


$nomeClienteArquivo =
    preg_replace(
        "/[^A-Za-z0-9_-]/",
        "_",
        iconv(
            "UTF-8",
            "ASCII//TRANSLIT//IGNORE",
            $orcamento["cliente_nome"]
        )
    );


$nomeArquivo =
    "Orcamento_"
    . $numeroArquivo
    . "_"
    . $nomeClienteArquivo
    . ".pdf";


/*
====================================================
EXIBIR PDF NO NAVEGADOR
====================================================
*/

$dompdf->stream(

    $nomeArquivo,

    [

        /*
        false = abre no navegador
        true  = força download
        */

        "Attachment" => false

    ]

);


exit;