# 📄 Sistema de Orçamentos

Sistema web desenvolvido em **PHP + MySQL**, voltado para gerenciamento de clientes, fornecedores, serviços e criação de orçamentos profissionais.

O projeto foi desenvolvido para funcionar inicialmente em ambiente **localhost utilizando XAMPP**, permitindo gerenciar todo o fluxo de criação de orçamentos, desde o cadastro do cliente até a geração do documento em PDF.

---

# 📌 Sobre o Projeto

O **Sistema de Orçamentos** foi criado com o objetivo de facilitar o gerenciamento de empresas e profissionais que precisam criar, organizar e acompanhar propostas comerciais e orçamentos.

O sistema possui uma área pública para apresentação dos serviços e uma área administrativa protegida por autenticação.

Entre os principais recursos estão:

* Cadastro de clientes
* Cadastro de fornecedores
* Cadastro de serviços
* Criação de orçamentos
* Inclusão de vários serviços em um orçamento
* Cálculo automático de valores
* Aplicação de descontos
* Status dos orçamentos
* Dashboard administrativo
* Configuração dos dados da empresa
* Upload da logo
* Geração de orçamento em PDF
* Relatórios
* Exportação de relatórios em CSV

---

# 🚀 Funcionalidades

## 🏠 Página Inicial

A página inicial apresenta informações sobre o sistema e os serviços oferecidos.

Recursos:

* Menu de navegação
* Apresentação do sistema
* Seção de serviços
* Informações institucionais
* Contato
* Botão para acesso ao sistema
* Layout responsivo
* Animações em CSS e JavaScript

---

## 🔐 Sistema de Login

O sistema possui autenticação através de e-mail e senha.

Recursos:

* Login com PHP
* Sessões
* Senhas criptografadas com `password_hash()`
* Validação com `password_verify()`
* Proteção das páginas administrativas
* Botão para mostrar/ocultar senha
* Animação de carregamento
* Logout seguro

---

# 📊 Dashboard

O Dashboard apresenta informações gerais do sistema em tempo real.

Indicadores disponíveis:

* Total de clientes
* Total de fornecedores
* Total de serviços
* Total de orçamentos
* Orçamentos pendentes
* Orçamentos aprovados
* Orçamentos recusados
* Orçamentos finalizados
* Valor total dos orçamentos
* Valor aprovado
* Valor pendente
* Quantidade de orçamentos do mês
* Valor orçado no mês
* Últimos orçamentos
* Últimos clientes cadastrados

Também possui atalhos para:

* Criar orçamento
* Cadastrar cliente
* Cadastrar serviço
* Configurar empresa

---

# 👥 Clientes

O módulo de clientes permite realizar o gerenciamento completo dos clientes.

Funcionalidades:

* Cadastrar cliente
* Editar cliente
* Excluir cliente
* Pesquisar cliente
* Definir cliente como ativo ou inativo

Dados disponíveis:

* Nome / Razão Social
* Pessoa Física ou Jurídica
* CPF / CNPJ
* Telefone
* WhatsApp
* E-mail
* CEP
* Endereço
* Número
* Complemento
* Bairro
* Cidade
* Estado
* Observações
* Status

O sistema também possui consulta automática de CEP utilizando a API ViaCEP.

---

# 🚚 Fornecedores

O módulo de fornecedores permite cadastrar e organizar empresas fornecedoras.

Campos disponíveis:

* Razão Social
* Nome Fantasia
* CNPJ
* Responsável
* Telefone
* WhatsApp
* E-mail
* CEP
* Endereço
* Número
* Complemento
* Bairro
* Cidade
* Estado
* Observações
* Status

Funcionalidades:

* Cadastrar
* Listar
* Pesquisar
* Editar
* Excluir

---

# 🛠️ Serviços

O módulo de serviços registra os serviços utilizados nos orçamentos.

Campos disponíveis:

* Nome
* Descrição
* Categoria
* Unidade
* Custo
* Valor de venda
* Status

Unidades disponíveis:

* Serviço
* Hora
* Diária
* Unidade
* Metro
* M²
* Pacote

O sistema também apresenta uma estimativa de margem entre custo e valor de venda.

---

# 🧾 Orçamentos

O módulo de orçamentos é o principal recurso do sistema.

É possível selecionar um cliente e adicionar vários serviços ao mesmo orçamento.

Funcionalidades:

* Geração automática do número do orçamento
* Seleção de cliente
* Inclusão de vários serviços
* Quantidade por serviço
* Valor unitário
* Alteração manual do valor
* Cálculo automático por item
* Cálculo automático do subtotal
* Desconto
* Cálculo automático do total
* Data do orçamento
* Validade
* Observações
* Status
* Visualização do orçamento
* Geração em PDF

Exemplo de numeração:

```text
ORC-2026-00001
ORC-2026-00002
ORC-2026-00003
```

---

# 📌 Status dos Orçamentos

Os orçamentos podem possuir os seguintes status:

```text
Pendente
Aprovado
Recusado
Finalizado
```

Os status são utilizados no Dashboard e nos relatórios.

---

# 🏢 Dados da Empresa

O sistema possui uma área para configuração das informações da empresa.

Dados disponíveis:

* Razão Social
* Nome Fantasia
* CPF / CNPJ
* Telefone
* WhatsApp
* E-mail
* Site
* CEP
* Endereço
* Número
* Complemento
* Bairro
* Cidade
* Estado
* Observações

Também é possível enviar a logo da empresa.

Formatos permitidos:

```text
JPG
JPEG
PNG
WEBP
```

---

# 📄 Geração de PDF

Os orçamentos podem ser gerados em PDF utilizando a biblioteca:

**Dompdf**

O PDF possui:

* Logo da empresa
* Dados da empresa
* Número do orçamento
* Data
* Validade
* Status
* Dados do cliente
* CPF / CNPJ
* Telefone
* WhatsApp
* E-mail
* Endereço
* Serviços
* Quantidades
* Valores unitários
* Totais
* Subtotal
* Desconto
* Total geral
* Observações
* Assinatura da empresa
* Assinatura do cliente
* Numeração de páginas

---

# 📊 Relatórios

O sistema possui uma área de relatórios de orçamentos.

Filtros disponíveis:

* Data inicial
* Data final
* Cliente
* Status

Indicadores apresentados:

* Quantidade de orçamentos
* Total orçado
* Total aprovado
* Valor pendente
* Ticket médio
* Quantidade por status
* Valor por status

Os relatórios podem ser:

* Visualizados no sistema
* Gerados em PDF
* Exportados em CSV

---

# 💻 Tecnologias Utilizadas

O projeto utiliza:

### Backend

```text
PHP
PDO
MySQL / MariaDB
```

### Frontend

```text
HTML5
CSS3
JavaScript
Bootstrap 5
Bootstrap Icons
```

### Bibliotecas

```text
Dompdf
Composer
```

### Ambiente de desenvolvimento

```text
XAMPP
Apache
MySQL
phpMyAdmin
```

---

# 📁 Estrutura do Projeto

```text
sistema_orcamento/
│
├── index.php
├── login.php
├── autenticar.php
├── logout.php
│
├── config/
│   └── database.php
│
├── includes/
│   └── auth.php
│
├── dashboard/
│   └── index.php
│
├── clientes/
│   ├── index.php
│   ├── cadastrar.php
│   ├── salvar.php
│   ├── editar.php
│   ├── atualizar.php
│   └── excluir.php
│
├── fornecedores/
│   ├── index.php
│   ├── cadastrar.php
│   ├── salvar.php
│   ├── editar.php
│   ├── atualizar.php
│   └── excluir.php
│
├── servicos/
│   ├── index.php
│   ├── cadastrar.php
│   ├── salvar.php
│   ├── editar.php
│   ├── atualizar.php
│   └── excluir.php
│
├── orcamentos/
│   ├── index.php
│   ├── novo.php
│   ├── salvar.php
│   ├── visualizar.php
│   └── gerar_pdf.php
│
├── empresa/
│   ├── configuracoes.php
│   └── salvar.php
│
├── relatorios/
│   ├── index.php
│   ├── gerar_pdf.php
│   └── exportar_csv.php
│
├── uploads/
│   └── logo/
│
├── database/
│   └── sistema_orcamento.sql
│
├── vendor/
│
├── composer.json
├── composer.lock
│
└── README.md
```

---

# 🗄️ Banco de Dados

Banco utilizado:

```text
sistema_orcamento
```

Principais tabelas:

```text
usuarios
clientes
fornecedores
servicos
orcamentos
orcamento_itens
empresa
```

---

# 🔗 Relacionamento Principal

```text
CLIENTES
    │
    │
    └──────────── ORÇAMENTOS
                       │
                       │
                       └──────── ORÇAMENTO_ITENS
                                      │
                                      │
                                      └──────── SERVIÇOS
```

---

# ⚙️ Requisitos

Antes de instalar o projeto, tenha instalado:

```text
XAMPP
PHP 8.0 ou superior
MySQL / MariaDB
Composer
```

O projeto foi desenvolvido e testado utilizando PHP 8.

---

# 📥 Instalação

## 1. Colocar o projeto no XAMPP

Copie a pasta para:

```text
C:\xampp\htdocs\
```

O resultado deve ser:

```text
C:\xampp\htdocs\sistema_orcamento
```

---

## 2. Iniciar o XAMPP

Abra o XAMPP Control Panel e inicie:

```text
Apache
MySQL
```

---

## 3. Criar banco de dados

Abra:

```text
http://localhost/phpmyadmin
```

Crie o banco:

```text
sistema_orcamento
```

Utilize:

```text
utf8mb4
```

como conjunto de caracteres.

Depois importe:

```text
database/sistema_orcamento.sql
```

Caso o arquivo SQL ainda não tenha sido consolidado, crie as tabelas utilizadas pelo sistema através dos scripts SQL do projeto.

---

# 🔌 Configuração do Banco

Arquivo:

```text
config/database.php
```

Configuração padrão do XAMPP:

```php
$host = "localhost";
$dbname = "sistema_orcamento";
$usuario = "root";
$senha = "";
```

---

# 📦 Instalar Dependências

Abra o Prompt de Comando.

Entre na pasta:

```bash
cd C:\xampp\htdocs\sistema_orcamento
```

Certifique-se de que o PHP do XAMPP está sendo utilizado:

```bash
set PATH=C:\xampp\php;%PATH%
```

Verifique:

```bash
php -v
```

Depois execute:

```bash
composer install
```

Caso o Dompdf ainda não esteja instalado:

```bash
composer require dompdf/dompdf
```

Após a instalação deverá existir:

```text
vendor/autoload.php
```

---

# 📦 Extensão ZIP

Caso o Composer apresente erro relacionado a ZIP, abra:

```text
C:\xampp\php\php.ini
```

Procure:

```ini
;extension=zip
```

e altere para:

```ini
extension=zip
```

Depois confirme:

```bash
php -m | findstr zip
```

O resultado deverá mostrar:

```text
zip
```

---

# 🔑 Usuário Administrador

O sistema utiliza senha criptografada através de:

```php
password_hash()
```

Para verificar a senha durante o login:

```php
password_verify()
```

Exemplo de usuário de desenvolvimento:

```text
E-mail:
admin@admin.com

Senha:
123456
```

> Recomenda-se alterar imediatamente esses dados em ambientes reais.

---

# 🌐 Acessando o Sistema

Página inicial:

```text
http://localhost/sistema_orcamento/
```

Login:

```text
http://localhost/sistema_orcamento/login.php
```

Dashboard:

```text
http://localhost/sistema_orcamento/dashboard/
```

Clientes:

```text
http://localhost/sistema_orcamento/clientes/
```

Fornecedores:

```text
http://localhost/sistema_orcamento/fornecedores/
```

Serviços:

```text
http://localhost/sistema_orcamento/servicos/
```

Orçamentos:

```text
http://localhost/sistema_orcamento/orcamentos/
```

Relatórios:

```text
http://localhost/sistema_orcamento/relatorios/
```

Configurações da empresa:

```text
http://localhost/sistema_orcamento/empresa/configuracoes.php
```

---

# 🔒 Segurança

O projeto utiliza algumas práticas importantes de segurança:

* PDO
* Prepared Statements
* `password_hash()`
* `password_verify()`
* Sessões PHP
* `session_regenerate_id()`
* Validação de autenticação
* `htmlspecialchars()`
* Validação de IDs
* Validação de upload
* Restrição de formato de imagens
* Transações no salvamento dos orçamentos

---

# 🧪 Fluxo de Uso

O fluxo recomendado é:

```text
1. Fazer login

        ↓

2. Configurar os dados da empresa

        ↓

3. Cadastrar clientes

        ↓

4. Cadastrar fornecedores

        ↓

5. Cadastrar serviços

        ↓

6. Criar orçamento

        ↓

7. Adicionar serviços

        ↓

8. Aplicar desconto, se necessário

        ↓

9. Salvar orçamento

        ↓

10. Visualizar orçamento

        ↓

11. Gerar PDF

        ↓

12. Acompanhar no Dashboard

        ↓

13. Consultar relatórios
```

---

# 📈 Exemplo de Orçamento

```text
ORC-2026-00001

Cliente:
João da Silva

------------------------------------------------

Serviço                Qtd       Valor

Formatação               1      R$ 180,00

Manutenção               2      R$ 150,00

------------------------------------------------

Subtotal                        R$ 480,00

Desconto                        R$  30,00

TOTAL                           R$ 450,00
```

---

# 🛠️ Possíveis Melhorias Futuras

O sistema pode receber novas funcionalidades, como:

* Edição completa de orçamentos
* Exclusão controlada de orçamentos
* Alteração rápida de status
* Duplicação de orçamento
* Cadastro de produtos
* Estoque
* Contas a pagar
* Contas a receber
* Formas de pagamento
* Parcelamento
* Conversão de orçamento em venda
* Ordem de serviço
* Assinatura digital
* Envio de orçamento por e-mail
* Envio pelo WhatsApp
* Dashboard com gráficos
* Relatórios avançados
* Controle de usuários
* Níveis de acesso
* Recuperação de senha
* Logs de atividades
* Backup do banco de dados
* Hospedagem online

---

# 📱 Responsividade

As principais páginas foram desenvolvidas com suporte a telas menores utilizando:

```text
Bootstrap
Media Queries
CSS Responsivo
```

O sistema pode ser utilizado em:

* Computadores
* Notebooks
* Tablets
* Smartphones

---

# 👨‍💻 Desenvolvimento

Projeto desenvolvido para estudo e aplicação prática de:

```text
PHP
MySQL
HTML
CSS
JavaScript
Bootstrap
PDO
Sessões
Composer
Dompdf
```

---

# 📜 Licença

Este projeto pode ser utilizado e modificado para fins de estudo e desenvolvimento.

Caso seja utilizado comercialmente, recomenda-se revisar:

* Segurança
* LGPD
* Controle de acesso
* Backup
* Configuração do servidor
* HTTPS
* Validação de dados
* Logs
* Permissões de arquivos

---

# ✅ Status do Projeto

```text
Página Inicial              ✅
Login                       ✅
Autenticação                ✅
Dashboard                   ✅
Clientes                    ✅
Fornecedores                ✅
Serviços                    ✅
Orçamentos                  ✅
Cálculos Automáticos        ✅
Dados da Empresa            ✅
Upload de Logo              ✅
PDF de Orçamento            ✅
Relatórios                  ✅
PDF de Relatórios           ✅
Exportação CSV              ✅
```

**Status:** Projeto funcional em ambiente localhost com XAMPP.

---

# 📄 Sistema de Orçamentos

Sistema desenvolvido para tornar a criação e o gerenciamento de orçamentos mais simples, rápidos e organizados.