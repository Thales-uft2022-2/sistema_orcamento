<?php
// Página inicial do Sistema de Orçamentos
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sistema de Orçamentos</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f7fb;
            color: #222;
        }

        /* MENU */

        .navbar {
            background: #0d47a1;
            padding: 15px 0;
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
        }

        .navbar-brand {
            color: white !important;
            font-size: 25px;
            font-weight: bold;
        }

        .navbar-brand i {
            margin-right: 8px;
        }

        .navbar-nav .nav-link {
            color: white !important;
            margin-left: 15px;
            font-weight: 500;
            transition: 0.3s;
        }

        .navbar-nav .nav-link:hover {
            color: #ffc107 !important;
            transform: translateY(-2px);
        }

        .btn-login {
            background: #ffc107;
            color: #111;
            border-radius: 25px;
            padding: 9px 25px !important;
            font-weight: bold !important;
        }

        .btn-login:hover {
            background: white;
            color: #0d47a1 !important;
        }

        /* HOME */

        .hero {
            min-height: 90vh;

            background:
                linear-gradient(
                    rgba(13, 71, 161, 0.90),
                    rgba(3, 25, 70, 0.92)
                );

            display: flex;
            align-items: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: "";
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            top: -200px;
            right: -150px;
        }

        .hero::after {
            content: "";
            position: absolute;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: rgba(255,193,7,0.08);
            bottom: -150px;
            left: -100px;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            animation: aparecer 1s ease;
        }

        .hero h1 {
            font-size: 55px;
            font-weight: bold;
            line-height: 1.2;
        }

        .hero h1 span {
            color: #ffc107;
        }

        .hero p {
            font-size: 20px;
            color: #e7e7e7;
            max-width: 650px;
            margin-top: 20px;
        }

        .hero-buttons {
            margin-top: 30px;
        }

        .btn-primary-custom {
            background: #ffc107;
            color: #111;
            border: none;
            padding: 14px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            margin-right: 10px;
            display: inline-block;
            transition: 0.3s;
        }

        .btn-primary-custom:hover {
            background: white;
            color: #0d47a1;
            transform: translateY(-4px);
        }

        .btn-outline-custom {
            border: 2px solid white;
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
            transition: 0.3s;
        }

        .btn-outline-custom:hover {
            background: white;
            color: #0d47a1;
            transform: translateY(-4px);
        }

        /* HERO CARD */

        .hero-card {
            background: rgba(255,255,255,0.10);
            backdrop-filter: blur(10px);
            padding: 35px;
            border-radius: 25px;
            border: 1px solid rgba(255,255,255,0.15);
            position: relative;
            z-index: 2;
            animation: subir 1s ease;
        }

        .hero-card-item {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }

        .hero-card-item:last-child {
            margin-bottom: 0;
        }

        .hero-card-icon {
            width: 60px;
            height: 60px;
            background: #ffc107;
            color: #111;
            border-radius: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 28px;
            margin-right: 15px;
        }

        .hero-card-item h5 {
            margin-bottom: 3px;
            font-weight: bold;
        }

        .hero-card-item p {
            font-size: 14px;
            margin: 0;
            color: #ddd;
        }

        /* SESSÕES */

        .section {
            padding: 90px 0;
        }

        .section-title {
            text-align: center;
            margin-bottom: 55px;
        }

        .section-title span {
            color: #0d47a1;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 2px;
        }

        .section-title h2 {
            font-size: 38px;
            font-weight: bold;
            margin-top: 10px;
        }

        .section-title p {
            max-width: 650px;
            margin: 15px auto 0;
            color: #666;
        }

        /* SERVIÇOS */

        .service-card {
            background: white;
            padding: 35px 25px;
            border-radius: 18px;
            text-align: center;
            height: 100%;
            box-shadow: 0 6px 25px rgba(0,0,0,0.07);
            border: 1px solid #eee;
            transition: 0.4s;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(13,71,161,0.15);
        }

        .service-icon {
            width: 75px;
            height: 75px;
            margin: 0 auto 20px;
            background: #e8f0fe;
            color: #0d47a1;
            border-radius: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 35px;
        }

        .service-card:hover .service-icon {
            background: #0d47a1;
            color: white;
        }

        .service-card h4 {
            font-weight: bold;
            margin-bottom: 15px;
        }

        .service-card p {
            color: #666;
        }

        /* SOBRE */

        .about {
            background: white;
        }

        .about-box {
            background: #0d47a1;
            color: white;
            border-radius: 25px;
            padding: 50px;
        }

        .about-box h2 {
            font-weight: bold;
            margin-bottom: 20px;
        }

        .about-box p {
            color: #e4e4e4;
            font-size: 17px;
        }

        .check-item {
            margin-top: 15px;
            display: flex;
            align-items: center;
        }

        .check-item i {
            color: #ffc107;
            font-size: 22px;
            margin-right: 10px;
        }

        /* CONTATO */

        .contact-card {
            background: white;
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            height: 100%;
        }

        .contact-item {
            display: flex;
            margin-bottom: 25px;
        }

        .contact-icon {
            width: 50px;
            height: 50px;
            background: #e8f0fe;
            color: #0d47a1;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 13px;
            font-size: 23px;
            margin-right: 15px;
        }

        /* CTA */

        .cta {
            background: linear-gradient(135deg, #0d47a1, #071d46);
            color: white;
            padding: 65px 20px;
            text-align: center;
        }

        .cta h2 {
            font-size: 38px;
            font-weight: bold;
        }

        .cta p {
            color: #ddd;
            margin-bottom: 30px;
        }

        /* FOOTER */

        footer {
            background: #05162f;
            color: #ccc;
            padding: 35px 0;
        }

        footer strong {
            color: white;
        }

        footer i {
            color: #ffc107;
        }

        /* ANIMAÇÕES */

        @keyframes aparecer {

            from {
                opacity: 0;
                transform: translateX(-40px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }

        }

        @keyframes subir {

            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }

        }

        /* RESPONSIVO */

        @media(max-width: 991px) {

            .hero {
                padding: 100px 0 60px;
            }

            .hero h1 {
                font-size: 42px;
            }

            .hero-card {
                margin-top: 50px;
            }

        }

        @media(max-width: 576px) {

            .hero h1 {
                font-size: 35px;
            }

            .hero p {
                font-size: 17px;
            }

            .hero-buttons a {
                display: block;
                margin: 10px 0;
                text-align: center;
            }

            .section-title h2 {
                font-size: 30px;
            }

        }

    </style>

</head>

<body>

<!-- MENU -->

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">

    <div class="container">

        <a class="navbar-brand" href="#inicio">
            <i class="bi bi-file-earmark-text"></i>
            Meu Orçamento
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#menu"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menu">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link" href="#inicio">
                        Início
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#servicos">
                        Serviços
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#sobre">
                        Sobre
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#contato">
                        Contato
                    </a>
                </li>

                <li class="nav-item ms-lg-3">

                    <a class="nav-link btn-login" href="login.php">

                        <i class="bi bi-box-arrow-in-right"></i>
                        Entrar

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>


<!-- INÍCIO -->

<section class="hero" id="inicio">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-7">

                <div class="hero-content">

                    <h1>
                        Crie seus
                        <span>orçamentos</span>
                        de forma rápida e profissional.
                    </h1>

                    <p>
                        Gerencie seus clientes, fornecedores, serviços e
                        orçamentos em um único sistema simples, organizado
                        e eficiente.
                    </p>

                    <div class="hero-buttons">

                        <a href="#servicos" class="btn-primary-custom">

                            <i class="bi bi-grid"></i>
                            Conhecer serviços

                        </a>

                        <a href="login.php" class="btn-outline-custom">

                            <i class="bi bi-box-arrow-in-right"></i>
                            Acessar sistema

                        </a>

                    </div>

                </div>

            </div>


            <div class="col-lg-5">

                <div class="hero-card">

                    <div class="hero-card-item">

                        <div class="hero-card-icon">
                            <i class="bi bi-people"></i>
                        </div>

                        <div>

                            <h5>Clientes</h5>

                            <p>
                                Cadastre e organize todos os seus clientes.
                            </p>

                        </div>

                    </div>


                    <div class="hero-card-item">

                        <div class="hero-card-icon">
                            <i class="bi bi-tools"></i>
                        </div>

                        <div>

                            <h5>Serviços</h5>

                            <p>
                                Controle serviços e valores.
                            </p>

                        </div>

                    </div>


                    <div class="hero-card-item">

                        <div class="hero-card-icon">
                            <i class="bi bi-file-earmark-pdf"></i>
                        </div>

                        <div>

                            <h5>Orçamentos em PDF</h5>

                            <p>
                                Gere documentos profissionais.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- SERVIÇOS -->

<section class="section" id="servicos">

    <div class="container">

        <div class="section-title">

            <span>Nossos recursos</span>

            <h2>
                Tudo que você precisa em um só sistema
            </h2>

            <p>
                Tenha mais organização e agilidade no gerenciamento
                dos seus serviços e orçamentos.
            </p>

        </div>


        <div class="row g-4">

            <!-- CLIENTES -->

            <div class="col-lg-4 col-md-6">

                <div class="service-card">

                    <div class="service-icon">
                        <i class="bi bi-person-vcard"></i>
                    </div>

                    <h4>
                        Cadastro de Clientes
                    </h4>

                    <p>
                        Cadastre clientes com nome, CPF ou CNPJ,
                        telefone, WhatsApp, e-mail e endereço.
                    </p>

                </div>

            </div>


            <!-- FORNECEDORES -->

            <div class="col-lg-4 col-md-6">

                <div class="service-card">

                    <div class="service-icon">
                        <i class="bi bi-truck"></i>
                    </div>

                    <h4>
                        Fornecedores
                    </h4>

                    <p>
                        Organize os dados dos seus fornecedores
                        e mantenha todas as informações centralizadas.
                    </p>

                </div>

            </div>


            <!-- SERVIÇOS -->

            <div class="col-lg-4 col-md-6">

                <div class="service-card">

                    <div class="service-icon">
                        <i class="bi bi-gear"></i>
                    </div>

                    <h4>
                        Cadastro de Serviços
                    </h4>

                    <p>
                        Cadastre os serviços oferecidos pela empresa
                        com descrição e valor padrão.
                    </p>

                </div>

            </div>


            <!-- ORÇAMENTOS -->

            <div class="col-lg-4 col-md-6">

                <div class="service-card">

                    <div class="service-icon">
                        <i class="bi bi-calculator"></i>
                    </div>

                    <h4>
                        Orçamentos
                    </h4>

                    <p>
                        Crie orçamentos selecionando clientes,
                        serviços, quantidades, valores e descontos.
                    </p>

                </div>

            </div>


            <!-- PDF -->

            <div class="col-lg-4 col-md-6">

                <div class="service-card">

                    <div class="service-icon">
                        <i class="bi bi-file-earmark-pdf"></i>
                    </div>

                    <h4>
                        Gerar PDF
                    </h4>

                    <p>
                        Gere orçamentos profissionais em PDF
                        para impressão ou envio ao cliente.
                    </p>

                </div>

            </div>


            <!-- DASHBOARD -->

            <div class="col-lg-4 col-md-6">

                <div class="service-card">

                    <div class="service-icon">
                        <i class="bi bi-bar-chart"></i>
                    </div>

                    <h4>
                        Dashboard
                    </h4>

                    <p>
                        Acompanhe clientes, serviços, fornecedores
                        e orçamentos diretamente pelo painel.
                    </p>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- SOBRE -->

<section class="section about" id="sobre">

    <div class="container">

        <div class="about-box">

            <div class="row align-items-center">

                <div class="col-lg-7">

                    <h2>
                        Sistema desenvolvido para facilitar seu dia a dia
                    </h2>

                    <p>
                        O Meu Orçamento foi pensado para pequenas empresas,
                        prestadores de serviços e profissionais que desejam
                        controlar seus clientes e gerar orçamentos de forma
                        rápida e organizada.
                    </p>

                    <div class="check-item">

                        <i class="bi bi-check-circle-fill"></i>

                        Sistema simples e fácil de utilizar.

                    </div>

                    <div class="check-item">

                        <i class="bi bi-check-circle-fill"></i>

                        Cadastro completo de clientes.

                    </div>

                    <div class="check-item">

                        <i class="bi bi-check-circle-fill"></i>

                        Controle de fornecedores e serviços.

                    </div>

                    <div class="check-item">

                        <i class="bi bi-check-circle-fill"></i>

                        Geração de orçamento profissional em PDF.

                    </div>

                </div>


                <div class="col-lg-5 text-center mt-5 mt-lg-0">

                    <i
                        class="bi bi-file-earmark-check"
                        style="font-size: 160px; color:#ffc107;"
                    ></i>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- CONTATO -->

<section class="section" id="contato">

    <div class="container">

        <div class="section-title">

            <span>Contato</span>

            <h2>
                Entre em contato
            </h2>

            <p>
                Estamos disponíveis para tirar dúvidas
                e fornecer mais informações.
            </p>

        </div>


        <div class="row justify-content-center">

            <div class="col-lg-7">

                <div class="contact-card">

                    <div class="contact-item">

                        <div class="contact-icon">
                            <i class="bi bi-envelope"></i>
                        </div>

                        <div>

                            <strong>E-mail</strong>

                            <div>
                                tipalmas25@gmail.com
                            </div>

                        </div>

                    </div>


                    <div class="contact-item">

                        <div class="contact-icon">
                            <i class="bi bi-whatsapp"></i>
                        </div>

                        <div>

                            <strong>WhatsApp</strong>

                            <div>
                                (63) 99242-6451
                            </div>

                        </div>

                    </div>


                    <div class="contact-item">

                        <div class="contact-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>

                        <div>

                            <strong>Localização</strong>

                            <div>
                                Palmas - TO
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- CTA -->

<section class="cta">

    <div class="container">

        <h2>
            Pronto para começar?
        </h2>

        <p>
            Entre no sistema e gerencie seus orçamentos.
        </p>

        <a href="login.php" class="btn-primary-custom">

            <i class="bi bi-box-arrow-in-right"></i>
            Acessar Sistema

        </a>

    </div>

</section>


<!-- RODAPÉ -->

<footer>

    <div class="container">

        <div class="row align-items-center">

            <div class="col-md-6">

                <strong>
                    <i class="bi bi-file-earmark-text"></i>
                    Meu Orçamento
                </strong>

                <div class="mt-2">
                    Sistema de Gestão de Orçamentos
                </div>

            </div>

            <div class="col-md-6 text-md-end mt-3 mt-md-0">

                &copy;
                <span id="ano"></span>
                Todos os direitos reservados.

            </div>

        </div>

    </div>

</footer>


<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


<script>

    // Ano automático no rodapé

    document.getElementById("ano").textContent =
        new Date().getFullYear();


    // Efeito de aparecer nos cards ao rolar

    const cards = document.querySelectorAll(".service-card");

    const observer = new IntersectionObserver((entries) => {

        entries.forEach((entry) => {

            if(entry.isIntersecting){

                entry.target.style.opacity = "1";
                entry.target.style.transform = "translateY(0)";

            }

        });

    }, {
        threshold: 0.15
    });


    cards.forEach((card) => {

        card.style.opacity = "0";
        card.style.transform = "translateY(30px)";
        card.style.transition = "all 0.7s ease";

        observer.observe(card);

    });

</script>

</body>
</html>