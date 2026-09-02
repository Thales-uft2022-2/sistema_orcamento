<?php

require_once "../includes/auth.php";
require_once "../config/database.php";


if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: index.php");
    exit;

}


$numero =
    trim($_POST["numero"] ?? "");

$clienteId =
    filter_input(
        INPUT_POST,
        "cliente_id",
        FILTER_VALIDATE_INT
    );

$dataOrcamento =
    $_POST["data_orcamento"] ?? "";

$validade =
    $_POST["validade"] ?? null;

$desconto =
    (float) ($_POST["desconto"] ?? 0);

$status =
    $_POST["status"] ?? "pendente";

$observacoes =
    trim($_POST["observacoes"] ?? "");

$usuarioId =
    $_SESSION["usuario_id"] ?? null;


$servicos =
    $_POST["servico_id"] ?? [];

$descricoes =
    $_POST["descricao"] ?? [];

$quantidades =
    $_POST["quantidade"] ?? [];

$valores =
    $_POST["valor_unitario"] ?? [];


if (
    !$clienteId ||
    !$dataOrcamento
) {

    $_SESSION["mensagem"] =
        "Cliente e data são obrigatórios.";

    $_SESSION["tipo_mensagem"] =
        "danger";

    header("Location: novo.php");
    exit;

}


if (count($servicos) === 0) {

    $_SESSION["mensagem"] =
        "Adicione pelo menos um serviço.";

    $_SESSION["tipo_mensagem"] =
        "danger";

    header("Location: novo.php");
    exit;

}


$statusPermitidos = [
    "pendente",
    "aprovado",
    "recusado",
    "finalizado"
];


if (!in_array(
    $status,
    $statusPermitidos,
    true
)) {

    $status = "pendente";

}


try {

    $pdo->beginTransaction();


    /*
    =====================================
    REFAZER TOTAIS NO SERVIDOR
    =====================================
    */

    $subtotal = 0;

    $itensValidos = [];


    foreach ($servicos as $indice => $servicoId) {

        $servicoId =
            filter_var(
                $servicoId,
                FILTER_VALIDATE_INT
            );


        $descricao =
            trim(
                $descricoes[$indice] ?? ""
            );


        $quantidade =
            (float) (
                $quantidades[$indice] ?? 0
            );


        $valorUnitario =
            (float) (
                $valores[$indice] ?? 0
            );


        if (
            !$servicoId ||
            $descricao === "" ||
            $quantidade <= 0 ||
            $valorUnitario < 0
        ) {

            continue;

        }


        /*
        Confere se o serviço realmente existe.
        */

        $checkServico =
            $pdo->prepare("
                SELECT id
                FROM servicos
                WHERE id = :id
                LIMIT 1
            ");


        $checkServico->execute([
            ":id" => $servicoId
        ]);


        if (!$checkServico->fetch()) {
            continue;
        }


        $totalItem =
            $quantidade *
            $valorUnitario;


        $subtotal +=
            $totalItem;


        $itensValidos[] = [

            "servico_id" =>
                $servicoId,

            "descricao" =>
                $descricao,

            "quantidade" =>
                $quantidade,

            "valor_unitario" =>
                $valorUnitario,

            "total" =>
                $totalItem

        ];

    }


    if (count($itensValidos) === 0) {

        throw new Exception(
            "Nenhum item válido foi informado."
        );

    }


    if ($desconto < 0) {
        $desconto = 0;
    }


    if ($desconto > $subtotal) {
        $desconto = $subtotal;
    }


    $total =
        $subtotal -
        $desconto;


    /*
    =====================================
    GARANTIR NÚMERO ÚNICO
    =====================================
    */

    $stmtNumero =
        $pdo->query("
            SELECT MAX(id) AS ultimo
            FROM orcamentos
        ");


    $resultadoNumero =
        $stmtNumero->fetch();


    $proximo =
        ((int) (
            $resultadoNumero["ultimo"] ?? 0
        )) + 1;


    $numero =
        "ORC-" .
        date("Y") .
        "-" .
        str_pad(
            $proximo,
            5,
            "0",
            STR_PAD_LEFT
        );


    /*
    =====================================
    SALVAR ORÇAMENTO
    =====================================
    */

    $sqlOrcamento = "
        INSERT INTO orcamentos
        (
            numero,
            cliente_id,
            data_orcamento,
            validade,
            subtotal,
            desconto,
            total,
            status,
            observacoes,
            usuario_id
        )
        VALUES
        (
            :numero,
            :cliente_id,
            :data_orcamento,
            :validade,
            :subtotal,
            :desconto,
            :total,
            :status,
            :observacoes,
            :usuario_id
        )
    ";


    $stmt =
        $pdo->prepare(
            $sqlOrcamento
        );


    $stmt->execute([

        ":numero" =>
            $numero,

        ":cliente_id" =>
            $clienteId,

        ":data_orcamento" =>
            $dataOrcamento,

        ":validade" =>
            $validade ?: null,

        ":subtotal" =>
            $subtotal,

        ":desconto" =>
            $desconto,

        ":total" =>
            $total,

        ":status" =>
            $status,

        ":observacoes" =>
            $observacoes ?: null,

        ":usuario_id" =>
            $usuarioId

    ]);


    $orcamentoId =
        $pdo->lastInsertId();


    /*
    =====================================
    SALVAR ITENS
    =====================================
    */

    $sqlItem = "
        INSERT INTO orcamento_itens
        (
            orcamento_id,
            servico_id,
            descricao,
            quantidade,
            valor_unitario,
            total
        )
        VALUES
        (
            :orcamento_id,
            :servico_id,
            :descricao,
            :quantidade,
            :valor_unitario,
            :total
        )
    ";


    $stmtItem =
        $pdo->prepare($sqlItem);


    foreach ($itensValidos as $item) {

        $stmtItem->execute([

            ":orcamento_id" =>
                $orcamentoId,

            ":servico_id" =>
                $item["servico_id"],

            ":descricao" =>
                $item["descricao"],

            ":quantidade" =>
                $item["quantidade"],

            ":valor_unitario" =>
                $item["valor_unitario"],

            ":total" =>
                $item["total"]

        ]);

    }


    $pdo->commit();


    $_SESSION["mensagem"] =
        "Orçamento {$numero} criado com sucesso!";

    $_SESSION["tipo_mensagem"] =
        "success";


    header(
        "Location: visualizar.php?id={$orcamentoId}"
    );

    exit;


} catch (Throwable $e) {


    if ($pdo->inTransaction()) {

        $pdo->rollBack();

    }


    $_SESSION["mensagem"] =
        "Não foi possível salvar o orçamento: "
        . $e->getMessage();

    $_SESSION["tipo_mensagem"] =
        "danger";


    header("Location: novo.php");
    exit;

}