<?php

require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;


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


if (
    in_array(
        $status,
        [
            "pendente",
            "aprovado",
            "recusado",
            "finalizado"
        ],
        true
    )
) {

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
    $where
        ? " WHERE " . implode(
            " AND ",
            $where
        )
        : "";


/*
====================================================
EMPRESA
====================================================
*/

$empresa = $pdo->query("
    SELECT *
    FROM empresa
    ORDER BY id ASC
    LIMIT 1
")->fetch();


/*
====================================================
DADOS
====================================================
*/

$sql = "
    SELECT

        o.*,

        c.nome AS cliente_nome

    FROM orcamentos o

    INNER JOIN clientes c
        ON c.id = o.cliente_id

    {$sqlWhere}

    ORDER BY
        o.data_orcamento DESC,
        o.id DESC
";


$stmt =
    $pdo->prepare(
        $sql
    );


$stmt->execute(
    $parametros
);


$orcamentos =
    $stmt->fetchAll();


/*
====================================================
TOTAIS
====================================================
*/

$totalGeral = 0;

$totalDescontos = 0;

$totalAprovado = 0;


foreach ($orcamentos as $orcamento) {

    $totalGeral +=
        (float) $orcamento["total"];

    $totalDescontos +=
        (float) $orcamento["desconto"];


    if (
        $orcamento["status"]
        === "aprovado"
    ) {

        $totalAprovado +=
            (float) $orcamento["total"];
    }
}


/*
====================================================
LOGO
====================================================
*/

$logoHtml = "";


if (
    $empresa &&
    !empty($empresa["logo"])
) {

    $arquivo =
        __DIR__
        . "/../uploads/logo/"
        . basename(
            $empresa["logo"]
        );


    if (file_exists($arquivo)) {

        $mime =
            mime_content_type(
                $arquivo
            );


        $base64 =
            base64_encode(
                file_get_contents(
                    $arquivo
                )
            );


        $logoHtml = '

        <img
            src="data:' .
            e($mime) .
            ';base64,' .
            $base64 .
            '"
            style="
                max-width:130px;
                max-height:70px;
            "
        >

        ';
    }
}


$nomeEmpresa =
    $empresa["nome_fantasia"]
    ??
    $empresa["razao_social"]
    ??
    "Meu Orçamento";


/*
====================================================
HTML
====================================================
*/

$html = '
<!DOCTYPE html>

<html lang="pt-BR">

<head>

<meta charset="UTF-8">

<style>

@page {

    margin:
        25px
        30px
        35px;

}

body {

    font-family:
        DejaVu Sans,
        sans-serif;

    font-size: 9px;

    color: #333;
}

.header {

    width: 100%;

    border-bottom:
        3px solid #0d47a1;

    margin-bottom: 20px;

    padding-bottom: 12px;
}

.header td {

    vertical-align: middle;
}

.empresa {

    text-align: right;
}

.empresa h2 {

    color: #0d47a1;

    margin:
        0
        0
        4px;

    font-size: 18px;
}

.title {

    margin-bottom: 15px;
}

.title h1 {

    font-size: 20px;

    color: #0d47a1;

    margin-bottom: 4px;
}

.cards {

    width: 100%;

    margin-bottom: 20px;
}

.cards td {

    width: 25%;

    padding: 10px;

    background: #f4f7fb;

    border:
        3px solid white;
}

.card-label {

    color: #777;

    font-size: 8px;
}

.card-value {

    font-size: 13px;

    font-weight: bold;

    margin-top: 4px;

    color: #0d47a1;
}

table.lista {

    width: 100%;

    border-collapse: collapse;
}

.lista th {

    background: #0d47a1;

    color: white;

    padding: 7px 5px;

    font-size: 8px;
}

.lista td {

    padding: 7px 5px;

    border-bottom:
        1px solid #ddd;
}

.right {

    text-align: right;
}

.center {

    text-align: center;
}

.footer {

    position: fixed;

    bottom: -20px;

    left: 0;

    right: 0;

    text-align: center;

    border-top:
        1px solid #ddd;

    padding-top: 5px;

    color: #777;

    font-size: 7px;
}

</style>

</head>

<body>


<table class="header">

<tr>

<td width="35%">

' . $logoHtml . '

</td>


<td
    width="65%"
    class="empresa"
>

<h2>

' . e($nomeEmpresa) . '

</h2>
';


if (
    $empresa &&
    !empty($empresa["cpf_cnpj"])
) {

    $html .= '

        CPF/CNPJ:
        ' .
        e(
            $empresa["cpf_cnpj"]
        )
        . '<br>

    ';
}


if (
    $empresa &&
    !empty($empresa["telefone"])
) {

    $html .= '

        Telefone:
        ' .
        e(
            $empresa["telefone"]
        )
        . '<br>

    ';
}


if (
    $empresa &&
    !empty($empresa["email"])
) {

    $html .=
        e(
            $empresa["email"]
        );
}


$html .= '

</td>

</tr>

</table>


<div class="title">

<h1>
Relatório de Orçamentos
</h1>

<div>
';


if ($dataInicio !== "") {

    $html .= '

        Período inicial:
        <strong>
            ' .
            dataBr($dataInicio)
            . '
        </strong>

    ';
}


if ($dataFim !== "") {

    $html .= '

        &nbsp; até &nbsp;

        <strong>
            ' .
            dataBr($dataFim)
            . '
        </strong>

    ';
}


if (
    $dataInicio === ""
    &&
    $dataFim === ""
) {

    $html .=
        "Todos os períodos";
}


$html .= '

</div>

</div>


<table class="cards">

<tr>

<td>

<div class="card-label">
ORÇAMENTOS
</div>

<div class="card-value">

' . count($orcamentos) . '

</div>

</td>


<td>

<div class="card-label">
TOTAL ORÇADO
</div>

<div class="card-value">

' . moeda($totalGeral) . '

</div>

</td>


<td>

<div class="card-label">
TOTAL APROVADO
</div>

<div class="card-value">

' . moeda($totalAprovado) . '

</div>

</td>


<td>

<div class="card-label">
DESCONTOS
</div>

<div class="card-value">

' . moeda($totalDescontos) . '

</div>

</td>

</tr>

</table>


<table class="lista">

<thead>

<tr>

<th>Número</th>

<th>Cliente</th>

<th>Data</th>

<th>Subtotal</th>

<th>Desconto</th>

<th>Total</th>

<th>Status</th>

</tr>

</thead>

<tbody>
';


if ($orcamentos) {

    foreach ($orcamentos as $orcamento) {

        $html .= '

        <tr>

            <td>

                ' .
                e(
                    $orcamento["numero"]
                )
                . '

            </td>


            <td>

                ' .
                e(
                    $orcamento["cliente_nome"]
                )
                . '

            </td>


            <td class="center">

                ' .
                dataBr(
                    $orcamento["data_orcamento"]
                )
                . '

            </td>


            <td class="right">

                ' .
                moeda(
                    $orcamento["subtotal"]
                )
                . '

            </td>


            <td class="right">

                ' .
                moeda(
                    $orcamento["desconto"]
                )
                . '

            </td>


            <td class="right">

                <strong>

                    ' .
                    moeda(
                        $orcamento["total"]
                    )
                    . '

                </strong>

            </td>


            <td class="center">

                ' .
                e(
                    nomeStatus(
                        $orcamento["status"]
                    )
                )
                . '

            </td>

        </tr>

        ';
    }

} else {

    $html .= '

    <tr>

        <td
            colspan="7"
            class="center"
        >

            Nenhum orçamento encontrado.

        </td>

    </tr>

    ';
}


$html .= '

</tbody>

</table>


<div class="footer">

Relatório gerado em
' .
date("d/m/Y H:i")
. '

</div>


</body>

</html>
';


/*
====================================================
PDF
====================================================
*/

$options =
    new Options();


$options->set(
    "isRemoteEnabled",
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


$dompdf->loadHtml(
    $html,
    "UTF-8"
);


/*
Paisagem para caber a tabela.
*/

$dompdf->setPaper(
    "A4",
    "landscape"
);


$dompdf->render();


/*
Número da página
*/

$canvas =
    $dompdf->getCanvas();


$font =
    $dompdf
        ->getFontMetrics()
        ->getFont(
            "DejaVu Sans"
        );


$canvas->page_text(

    735,
    570,

    "Página {PAGE_NUM} de {PAGE_COUNT}",

    $font,

    7,

    [
        90,
        90,
        90
    ]

);


$dompdf->stream(

    "Relatorio_Orcamentos.pdf",

    [
        "Attachment" => false
    ]

);


exit;