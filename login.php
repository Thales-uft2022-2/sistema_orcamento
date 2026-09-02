<?php
session_start();

// Se futuramente o usuário já estiver logado,
// poderemos redirecionar direto para o dashboard.

/*
if (isset($_SESSION['usuario_id'])) {
    header("Location: dashboard/index.php");
    exit;
}
*/
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Meu Orçamento</title>

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

        body {
            min-height: 100vh;

            font-family: Arial, Helvetica, sans-serif;

            background:
                linear-gradient(
                    135deg,
                    #061d46,
                    #0d47a1,
                    #1976d2
                );

            display: flex;
            justify-content: center;
            align-items: center;

            overflow: hidden;
        }


        /* =========================
           FUNDO ANIMADO
        ========================= */

        .background-circle {

            position: absolute;

            border-radius: 50%;

            background: rgba(255, 255, 255, 0.08);

            animation: flutuar 7s ease-in-out infinite;

        }


        .circle-1 {

            width: 250px;
            height: 250px;

            top: -80px;
            left: -70px;

        }


        .circle-2 {

            width: 350px;
            height: 350px;

            right: -130px;
            bottom: -120px;

            animation-delay: 2s;

        }


        .circle-3 {

            width: 120px;
            height: 120px;

            right: 15%;
            top: 10%;

            background: rgba(255, 193, 7, 0.10);

            animation-delay: 1s;

        }


        .circle-4 {

            width: 90px;
            height: 90px;

            left: 18%;
            bottom: 10%;

            background: rgba(255, 193, 7, 0.10);

            animation-delay: 3s;

        }



        /* =========================
           CONTAINER PRINCIPAL
        ========================= */

        .login-container {

            width: 100%;
            max-width: 1000px;

            min-height: 600px;

            background: white;

            border-radius: 25px;

            overflow: hidden;

            display: flex;

            position: relative;

            z-index: 10;

            box-shadow:
                0 30px 80px rgba(0, 0, 0, 0.35);

            animation: entrada 0.9s ease;

        }



        /* =========================
           LADO ESQUERDO
        ========================= */

        .login-left {

            width: 50%;

            background:
                linear-gradient(
                    135deg,
                    #0d47a1,
                    #061d46
                );

            color: white;

            padding: 60px;

            display: flex;

            flex-direction: column;

            justify-content: center;

            position: relative;

            overflow: hidden;

        }


        .login-left::before {

            content: "";

            position: absolute;

            width: 350px;
            height: 350px;

            border-radius: 50%;

            background: rgba(255, 255, 255, 0.05);

            right: -180px;
            top: -100px;

        }


        .login-left::after {

            content: "";

            position: absolute;

            width: 250px;
            height: 250px;

            border-radius: 50%;

            background: rgba(255, 193, 7, 0.07);

            left: -100px;
            bottom: -100px;

        }


        .logo {

            position: relative;

            z-index: 2;

            margin-bottom: 40px;

        }


        .logo-icon {

            width: 65px;
            height: 65px;

            border-radius: 18px;

            background: #ffc107;

            color: #111;

            display: flex;

            justify-content: center;
            align-items: center;

            font-size: 33px;

            margin-bottom: 18px;

            animation: pulseIcon 3s infinite;

        }


        .logo h2 {

            font-size: 29px;

            font-weight: bold;

            margin: 0;

        }


        .login-left-content {

            position: relative;

            z-index: 2;

        }


        .login-left h1 {

            font-size: 43px;

            font-weight: bold;

            line-height: 1.2;

            margin-bottom: 20px;

        }


        .login-left h1 span {

            color: #ffc107;

        }


        .login-left p {

            color: #dce4f2;

            font-size: 17px;

            line-height: 1.7;

            margin-bottom: 30px;

        }


        .feature {

            display: flex;

            align-items: center;

            margin-bottom: 15px;

            font-size: 15px;

        }


        .feature i {

            color: #ffc107;

            font-size: 19px;

            margin-right: 12px;

        }



        /* =========================
           LADO DIREITO
        ========================= */

        .login-right {

            width: 50%;

            padding: 60px;

            display: flex;

            flex-direction: column;

            justify-content: center;

            background: #ffffff;

        }


        .login-title {

            margin-bottom: 35px;

        }


        .login-title h2 {

            font-size: 34px;

            font-weight: bold;

            color: #172033;

            margin-bottom: 10px;

        }


        .login-title p {

            color: #777;

            margin: 0;

        }



        /* =========================
           INPUTS
        ========================= */

        .form-group {

            margin-bottom: 22px;

        }


        .form-label {

            color: #333;

            font-weight: 600;

            font-size: 14px;

            margin-bottom: 8px;

        }


        .input-wrapper {

            position: relative;

        }


        .input-wrapper > i {

            position: absolute;

            left: 18px;

            top: 50%;

            transform: translateY(-50%);

            color: #7e8a9a;

            font-size: 18px;

            transition: 0.3s;

        }


        .form-control {

            height: 56px;

            border-radius: 13px;

            border: 1px solid #dce1e8;

            padding-left: 50px;

            padding-right: 50px;

            font-size: 15px;

            transition: 0.3s;

            background: #f9fafc;

        }


        .form-control:focus {

            background: white;

            border-color: #0d47a1;

            box-shadow:
                0 0 0 4px rgba(13, 71, 161, 0.10);

        }


        .input-wrapper:focus-within > i {

            color: #0d47a1;

        }



        /* MOSTRAR SENHA */

        .toggle-password {

            position: absolute;

            right: 18px;

            top: 50%;

            transform: translateY(-50%);

            border: none;

            background: transparent;

            color: #777;

            font-size: 18px;

            cursor: pointer;

        }


        .toggle-password:hover {

            color: #0d47a1;

        }



        /* OPÇÕES */

        .login-options {

            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-bottom: 25px;

            font-size: 14px;

        }


        .form-check-input:checked {

            background-color: #0d47a1;

            border-color: #0d47a1;

        }


        .forgot-link {

            text-decoration: none;

            color: #0d47a1;

            font-weight: 600;

        }


        .forgot-link:hover {

            text-decoration: underline;

        }



        /* BOTÃO */

        .btn-login {

            width: 100%;

            height: 56px;

            border: none;

            border-radius: 13px;

            background:
                linear-gradient(
                    135deg,
                    #0d47a1,
                    #1976d2
                );

            color: white;

            font-size: 16px;

            font-weight: bold;

            transition: 0.4s;

            position: relative;

            overflow: hidden;

        }


        .btn-login:hover {

            transform: translateY(-3px);

            box-shadow:
                0 12px 25px rgba(13, 71, 161, 0.35);

        }


        .btn-login:active {

            transform: translateY(0);

        }



        /* VOLTAR */

        .back-home {

            text-align: center;

            margin-top: 25px;

        }


        .back-home a {

            text-decoration: none;

            color: #666;

            font-size: 14px;

            transition: 0.3s;

        }


        .back-home a:hover {

            color: #0d47a1;

        }


        .back-home i {

            margin-right: 5px;

        }



        /* RODAPÉ LOGIN */

        .login-footer {

            text-align: center;

            color: #aaa;

            font-size: 12px;

            margin-top: 35px;

        }



        /* =========================
           ANIMAÇÕES
        ========================= */

        @keyframes entrada {

            from {

                opacity: 0;

                transform:
                    translateY(40px)
                    scale(0.97);

            }

            to {

                opacity: 1;

                transform:
                    translateY(0)
                    scale(1);

            }

        }


        @keyframes flutuar {

            0%, 100% {

                transform: translateY(0px);

            }

            50% {

                transform: translateY(-25px);

            }

        }


        @keyframes pulseIcon {

            0%, 100% {

                transform: scale(1);

            }

            50% {

                transform: scale(1.08);

            }

        }



        /* =========================
           RESPONSIVIDADE
        ========================= */

        @media (max-width: 900px) {

            body {

                overflow-y: auto;

                padding: 30px 15px;

            }


            .login-container {

                max-width: 520px;

                flex-direction: column;

            }


            .login-left {

                width: 100%;

                padding: 40px;

            }


            .login-left h1 {

                font-size: 32px;

            }


            .login-right {

                width: 100%;

                padding: 40px;

            }

        }


        @media (max-width: 500px) {

            body {

                padding: 15px;

            }


            .login-left {

                padding: 30px 25px;

            }


            .login-right {

                padding: 35px 25px;

            }


            .login-left h1 {

                font-size: 28px;

            }


            .login-title h2 {

                font-size: 29px;

            }


            .login-options {

                flex-direction: column;

                align-items: flex-start;

                gap: 10px;

            }

        }

    </style>

</head>


<body>


<!-- FUNDO ANIMADO -->

<div class="background-circle circle-1"></div>

<div class="background-circle circle-2"></div>

<div class="background-circle circle-3"></div>

<div class="background-circle circle-4"></div>



<!-- CONTAINER -->

<div class="login-container">


    <!-- LADO ESQUERDO -->

    <div class="login-left">

        <div class="logo">

            <div class="logo-icon">

                <i class="bi bi-file-earmark-text"></i>

            </div>

            <h2>
                Meu Orçamento
            </h2>

        </div>


        <div class="login-left-content">

            <h1>

                Gerencie seus

                <span>
                    orçamentos
                </span>

                com facilidade.

            </h1>


            <p>

                Tenha clientes, fornecedores, serviços e
                orçamentos organizados em um único sistema.

            </p>


            <div class="feature">

                <i class="bi bi-check-circle-fill"></i>

                Cadastro de clientes e fornecedores

            </div>


            <div class="feature">

                <i class="bi bi-check-circle-fill"></i>

                Gerenciamento de serviços

            </div>


            <div class="feature">

                <i class="bi bi-check-circle-fill"></i>

                Criação de orçamentos

            </div>


            <div class="feature">

                <i class="bi bi-check-circle-fill"></i>

                Geração de orçamento em PDF

            </div>


            <div class="feature">

                <i class="bi bi-check-circle-fill"></i>

                Dashboard administrativo

            </div>

        </div>

    </div>



    <!-- LADO DIREITO -->

    <div class="login-right">


        <div class="login-title">

            <h2>
                Bem-vindo!
            </h2>

            <p>
                Informe seus dados para acessar o sistema.
            </p>

        </div>



        <!-- FORMULÁRIO -->

        <form
            action="autenticar.php"
            method="POST"
            id="formLogin"
        >


            <!-- EMAIL -->

            <div class="form-group">

                <label
                    for="email"
                    class="form-label"
                >
                    E-mail
                </label>


                <div class="input-wrapper">

                    <i class="bi bi-envelope"></i>


                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-control"
                        placeholder="Digite seu e-mail"
                        autocomplete="email"
                        required
                    >

                </div>

            </div>



            <!-- SENHA -->

            <div class="form-group">

                <label
                    for="senha"
                    class="form-label"
                >
                    Senha
                </label>


                <div class="input-wrapper">

                    <i class="bi bi-lock"></i>


                    <input
                        type="password"
                        name="senha"
                        id="senha"
                        class="form-control"
                        placeholder="Digite sua senha"
                        autocomplete="current-password"
                        required
                    >


                    <button
                        type="button"
                        class="toggle-password"
                        id="togglePassword"
                        title="Mostrar ou ocultar senha"
                    >

                        <i
                            class="bi bi-eye"
                            id="eyeIcon"
                        ></i>

                    </button>

                </div>

            </div>



            <!-- OPÇÕES -->

            <div class="login-options">

                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="lembrar"
                        id="lembrar"
                    >

                    <label
                        class="form-check-label"
                        for="lembrar"
                    >

                        Lembrar acesso

                    </label>

                </div>


                <a
                    href="#"
                    class="forgot-link"
                >

                    Esqueceu a senha?

                </a>

            </div>



            <!-- BOTÃO -->

            <button
                type="submit"
                class="btn-login"
                id="btnLogin"
            >

                <span id="btnText">

                    <i class="bi bi-box-arrow-in-right"></i>

                    Entrar no sistema

                </span>

            </button>


        </form>



        <!-- VOLTAR -->

        <div class="back-home">

            <a href="index.php">

                <i class="bi bi-arrow-left"></i>

                Voltar para página inicial

            </a>

        </div>


        <div class="login-footer">

            &copy;

            <span id="ano"></span>

            Meu Orçamento

        </div>


    </div>

</div>



<!-- BOOTSTRAP -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


<script>

    /*
    ======================================
    MOSTRAR / ESCONDER SENHA
    ======================================
    */

    const senha =
        document.getElementById("senha");


    const togglePassword =
        document.getElementById("togglePassword");


    const eyeIcon =
        document.getElementById("eyeIcon");


    togglePassword.addEventListener("click", function () {


        if (senha.type === "password") {

            senha.type = "text";

            eyeIcon.classList.remove("bi-eye");

            eyeIcon.classList.add("bi-eye-slash");

        } else {

            senha.type = "password";

            eyeIcon.classList.remove("bi-eye-slash");

            eyeIcon.classList.add("bi-eye");

        }

    });



    /*
    ======================================
    ANO AUTOMÁTICO
    ======================================
    */

    document.getElementById("ano").textContent =
        new Date().getFullYear();



    /*
    ======================================
    ANIMAÇÃO AO ENVIAR
    ======================================
    */

    const form =
        document.getElementById("formLogin");


    const btn =
        document.getElementById("btnLogin");


    const btnText =
        document.getElementById("btnText");


    form.addEventListener("submit", function () {


        btn.disabled = true;


        btnText.innerHTML = `

            <span
                class="spinner-border
                       spinner-border-sm
                       me-2"
            ></span>

            Entrando...

        `;

    });

</script>


</body>

</html>