<?php

require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../config/database.php";


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
        ? " WHERE " .
          implode(
              " AND ",
              $where
          )
        : "";


/*
====================================================
BUSCAR ORÇAMENTOS
====================================================
*/

$sql = "
    SELECT

        o.numero,
        c.nome AS cliente,
        o.data_orcamento,
        o.validade,
        o.subtotal,
        o.desconto,
        o.total,
        o.status

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


/*
====================================================
HEADERS
====================================================
*/

$nomeArquivo =
    "relatorio_orcamentos_"
    . date("Y-m-d_H-i-s")
    . ".csv";


header(
    "Content-Type: text/csv; charset=UTF-8"
);


header(
    'Content-Disposition: attachment; filename="' .
    $nomeArquivo .
    '"'
);


/*
====================================================
CRIAR CSV
====================================================
*/

$arquivo =
    fopen(
        "php://output",
        "w"
    );


/*
BOM UTF-8 para abrir acentos
corretamente no Excel.
*/

fwrite(
    $arquivo,
    "\xEF\xBB\xBF"
);


/*
Cabeçalho
*/

fputcsv(
    $arquivo,
    [
        "Número",
        "Cliente",
        "Data",
        "Validade",
        "Subtotal",
        "Desconto",
        "Total",
        "Status"
    ],
    ";"
);


/*
Dados
*/

while (
    $orcamento =
        $stmt->fetch()
) {

    fputcsv(
        $arquivo,
        [

            $orcamento["numero"],

            $orcamento["cliente"],

            date(
                "d/m/Y",
                strtotime(
                    $orcamento["data_orcamento"]
                )
            ),

            $orcamento["validade"]
                ? date(
                    "d/m/Y",
                    strtotime(
                        $orcamento["validade"]
                    )
                )
                : "",

            number_format(
                $orcamento["subtotal"],
                2,
                ",",
                "."
            ),

            number_format(
                $orcamento["desconto"],
                2,
                ",",
                "."
            ),

            number_format(
                $orcamento["total"],
                2,
                ",",
                "."
            ),

            ucfirst(
                $orcamento["status"]
            )

        ],
        ";"
    );
}


fclose(
    $arquivo
);


exit;