<?php

require_once __DIR__ . "/vendor/autoload.php";

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();

$options->set("isRemoteEnabled", true);

$dompdf = new Dompdf($options);

$html = '
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
            padding: 30px;
        }

        h1 {
            color: #0d47a1;
        }

        .box {
            border: 1px solid #ddd;
            padding: 20px;
            margin-top: 20px;
        }

    </style>

</head>

<body>

    <h1>Meu Orçamento</h1>

    <div class="box">

        <h2>Dompdf instalado com sucesso!</h2>

        <p>
            Seu sistema já está preparado para gerar
            orçamentos profissionais em PDF.
        </p>

        <p>
            <strong>Valor de exemplo:</strong>
            R$ 1.500,00
        </p>

    </div>

</body>
</html>
';

$dompdf->loadHtml($html, "UTF-8");

$dompdf->setPaper(
    "A4",
    "portrait"
);

$dompdf->render();

$dompdf->stream(
    "teste-orcamento.pdf",
    [
        "Attachment" => false
    ]
);

exit;