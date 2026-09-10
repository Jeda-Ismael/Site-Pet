<div align="center">

# 🐾 Encontre Seu Pet

**Uma plataforma para reunir pets perdidos com seus tutores e conectar animais disponíveis a novos lares.**

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?style=flat-square&logo=docker&logoColor=white)
![License](https://img.shields.io/badge/licença-uso%20livre-orange?style=flat-square)

</div>

---

## 📖 Sobre o projeto

O **Encontre Seu Pet** ajuda tutores a divulgar pets desaparecidos e permite que qualquer pessoa cadastre animais para adoção. Qualquer visitante pode ver os casos ativos e os pets disponíveis; para cadastrar, editar ou marcar um caso como resolvido é preciso criar uma conta.

## ✨ Funcionalidades

| | |
|---|---|
| 🔍 | Cadastro e busca de **pets desaparecidos**, com filtro por espécie e busca por nome/local |
| 🏠 | Cadastro e listagem de **pets para adoção**, com filtro por espécie e busca por nome |
| 📸 | Upload de foto com **prévia instantânea** antes de enviar |
| 🐕 | Campos detalhados: espécie, raça, porte, cor, sexo, contato, descrição |
| ✅ | Botões para marcar um caso como **"Encontrado"** ou um pet como **"Adotado"**, saindo da lista de ativos |
| 🔐 | Cadastro, login e logout com sessão segura |
| 🎨 | Visual próprio, responsivo, com animações (Lottie) |

## 🛠 Stack

- **PHP 8.2** puro (sem framework), PDO com prepared statements
- **MySQL 8** para persistência
- **Docker Compose** — sobe o ambiente inteiro com um comando, sem precisar instalar PHP/MySQL na máquina
- **Apache** servindo a aplicação
- **Adminer** para inspecionar o banco visualmente durante o desenvolvimento
- CSS próprio (sem framework) + [Lottie](https://lottiefiles.com/) para as animações

## 🔒 Segurança

Este projeto passou por uma reescrita focada em segurança em cima da versão original:

- **PDO com prepared statements** em 100% das consultas (sem concatenação de SQL)
- **Upload de imagem validado de verdade**: checagem do tipo MIME real do arquivo (`finfo`) + `getimagesize()`, não apenas a extensão — e a pasta `uploads/` está configurada para **nunca executar PHP**, mesmo que um arquivo malicioso passe
- **Token CSRF** em todo formulário que altera dados; exclusões e ações de estado usam **POST**, nunca GET
- **Senhas com hash** (`password_hash`/`password_verify`), nunca em texto puro
- **Sessão regenerada** no login (proteção contra session fixation)
- **Bloqueio temporário** após várias tentativas de login malsucedidas
- Erros do PHP nunca aparecem na tela em produção (`APP_ENV=production`)

## 🚀 Rodando localmente

Pré-requisito: [Docker Desktop](https://www.docker.com/products/docker-desktop/) instalado e em execução.

```bash
git clone https://github.com/Jeda-Ismael/Site-Pet.git
cd Site-Pet
cp .env.example .env
docker compose up -d --build
```

- **Site:** http://localhost:8080
- **Adminer** (ver o banco visualmente): http://localhost:8081 — servidor `db`, usuário/senha do `.env`, base `pet`

O banco é criado automaticamente na primeira subida, a partir de [`db/init.sql`](db/init.sql) (tabelas `usuarios`, `casos`, `adocoes`).

### Parar ou resetar

```bash
docker compose down        # para os containers, mantém os dados
docker compose down -v     # para e apaga o volume do banco (reset total)
```

## 📁 Estrutura do projeto

```
├── docker-compose.yml     # serviços: app (PHP+Apache), db (MySQL), adminer
├── Dockerfile             # imagem da aplicação
├── db/
│   └── init.sql           # schema do banco (usuarios, casos, adocoes)
├── docker/
│   └── apache-vhost.conf  # vhost do Apache (document root, bloqueio de PHP em uploads/)
├── src/                   # código fora do document root
│   ├── config.php         # leitura do .env
│   ├── Database.php       # conexão PDO (singleton)
│   ├── auth.php           # sessão, login, throttle de tentativas
│   ├── csrf.php           # geração/validação de token CSRF
│   ├── upload.php         # validação e upload seguro de imagens
│   └── partials/          # header/footer compartilhados
└── public/                # document root do Apache
    ├── index.php, casos.php, adocao.php, ...
    └── uploads/           # imagens enviadas pelos usuários
```

## 🗃 Banco de dados

| Tabela | Descrição |
|---|---|
| `usuarios` | contas de usuário (nome, e-mail, senha com hash) |
| `casos` | pets desaparecidos — espécie, raça, porte, cor, sexo, tutor, contato, local, data, foto, status (`ativo`/`encontrado`) |
| `adocoes` | pets para adoção — espécie, raça, porte, cor, sexo, idade, foto, status (`disponivel`/`adotado`) |

O schema completo está em [`db/init.sql`](db/init.sql) e é aplicado automaticamente pelo Docker na primeira subida do container do banco.

---

<div align="center">

Feito com 🧡 para ajudar pets a encontrarem seu caminho de volta pra casa.

</div>
