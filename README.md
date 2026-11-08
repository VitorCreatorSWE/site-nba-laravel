# 🏀 Basq NBA — Portal de Basquete & Estatísticas

> Plataforma completa sobre a NBA desenvolvida com **PHP 8.3+**, **Laravel 13**, **Bootstrap 5.3**, **MySQL 8**, **Redis**, autenticação com **Laravel Breeze**, geração de **QR Code Pix** para doações e integração com APIs externas de dados esportivos.

---

## 📋 Sumário

- [Visão Geral](#-visão-geral)
- [Funcionalidades](#-funcionalidades)
- [Stack Tecnológica](#-stack-tecnológica)
- [Pré-requisitos](#-pré-requisitos)
- [Instalação e Configuração](#-instalação-e-configuração)
- [Executando com Docker](#-executando-com-docker)
- [Comandos Artisan Úteis](#-comandos-artisan-úteis)
- [Estrutura do Projeto](#-estrutura-do-projeto)
- [Integrações e APIs](#-integrações-e-apis)
- [Licença](#-licença)

---

## 🌟 Visão Geral

O **Basq NBA** é um portal dinâmico voltado para fãs e analistas do basquete norte-americano. O projeto agrega notícias, placares em tempo real, calendário da temporada, fichas detalhadas de franquias e jogadores, análises de confrontos, sistema de moderação de comentários e arrecadação de doações via Pix com chave estática/dinâmica e QR Code EMV.

---

## ⚡ Funcionalidades

### 🌐 Área Pública
- **Home:** Destaques das principais notícias, carrossel/grid de próximos jogos, partidas ao vivo e resultados recentes.
- **Notícias e Recentes (`/noticias`, `/recentes`):** Listagem paginada de artigos e reportagens com busca por texto, filtro de categorias e página de leitura com comentários aprovados.
- **Jogos (`/jogos`):** Acompanhamento de partidas (agendadas, ao vivo e finalizadas), placares por quarto, detalhes de arena e análises pré/pós jogo (`/jogos/{id}`).
- **Estatísticas de Franquias (`/estatisticas`):** Classificação e divisão das conferências Leste e Oeste, histórico e página individual de franquia (`/estatisticas/{abbreviation}`).
- **Catálogo de Jogadores (`/jogadores`):** Listagem com busca e filtros por franquia e posição; página individual (`/jogadores/{slug}`) contendo dados físicos (altura, peso), nascimento, número da camisa, universidade e dados de draft.
- **Doações via Pix (`/doacoes`):** Formulário interativo para doação de qualquer valor, gerando payload Copia e Cola (BR Code) e imagem QR Code instantânea.
- **Internacionalização (i18n):** Alternância instantânea de idioma entre **Português (PT)** e **Inglês (EN)**.
- **Tema Claro / Escuro (Dark Mode):** Alternância com persistência em sessão e banco de dados para usuários autenticados.

### 🔐 Autenticação & Usuários
- Login, registro e redefinição de senhas com **Laravel Breeze**.
- Gerenciamento de perfil (`/profile`): foto de avatar, alteração de dados cadastrais, preferência de tema e idioma.
- Sistema de comentários em notícias para usuários autenticados (sujeito à moderação).

### 🛠️ Painel Administrativo (`/admin`)
- **Dashboard:** Visão geral de métricas, contagem de posts, usuários, jogos sincronizados e doações.
- **Gestão de Conteúdo (CRUD):** Criação, edição e exclusão de Notícias, Análises e Posts com upload de imagem de capa e slug automático.
- **Gestão de Jogos & Estatísticas:** Gerenciamento manual e geração automática de notícias de jogos.
- **Gestão de Times e Jogadores:** Edição de dados complementares das franquias e atletas.
- **Moderação de Comentários:** Fila de comentários pendentes com aprovação em 1 clique ou remoção.
- **Gestão de Usuários:** Listagem de inscritos e ferramenta de bloqueio/desbloqueio e exclusão de contas.
- **Gestão de Doações:** Visualização de transferências Pix geradas, valores e status de pagamento.
- **Sincronizador Manual:** Gatilhos na interface web para sincronizar franquias e jogos das APIs.

---

## 🚀 Stack Tecnológica

| Camada | Tecnologia |
|---|---|
| **Backend** | PHP 8.3+ / Laravel 13 |
| **Frontend** | Blade Templates, Bootstrap 5.3, Bootstrap Icons, Alpine.js / Vite |
| **Banco de Dados** | MySQL 8.0 (charset `utf8mb4_unicode_ci`) |
| **Cache & Filas** | Redis / Database Driver |
| **Pagamentos** | Pix BR Code EMV + `chillerlan/php-qrcode` |
| **Autenticação** | Laravel Breeze |
| **APIs Externas** | [balldontlie.io](https://balldontlie.io) & [API-Sports (v1.basketball)](https://api-sports.io) |
| **Containerização** | Docker & Docker Compose (Nginx + PHP-FPM + MySQL + Redis) |

---

## 📦 Pré-requisitos

Para rodar o projeto localmente sem Docker:
- **PHP >= 8.3** com extensões: `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `gd`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`
- **Composer >= 2.x**
- **Node.js >= 18.x** e **npm**
- **MySQL >= 8.0**
- **Redis** (opcional, pode-se usar database driver para cache/queue)

---

## ⚙️ Instalação e Configuração

### 1. Clonar o repositório e entrar no diretório
```bash
git clone https://github.com/seu-usuario/nba-website.git
cd NBA/nba-website
```

### 2. Instalar dependências PHP e JavaScript
```bash
composer install
npm install
npm run build
```

### 3. Configurar as variáveis de ambiente
Copie o arquivo de exemplo:
```bash
cp .env.example .env
php artisan key:generate
```

Edite o arquivo `.env` com suas credenciais:
```ini
APP_NAME="Basq NBA"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Conexão MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nba_website
DB_USERNAME=root
DB_PASSWORD=

# Cache e Filas (Database ou Redis)
CACHE_STORE=database
QUEUE_CONNECTION=database

# Chaves das APIs da NBA
BALLDONTLIE_API_KEY=sua_chave_balldontlie
APISPORTS_KEY=sua_chave_apisports
APISPORTS_SEASON=2024

# Chave Pix para Doações
PIX_KEY=sua-chave-pix-aqui@email.com
PIX_MERCHANT_NAME="Basq NBA"
PIX_MERCHANT_CITY="Sao Paulo"
```

### 4. Executar as Migrations
```bash
php artisan migrate
```

### 5. Criar o Usuário Administrador
O projeto conta com um comando Artisan dedicado para criar administradores de forma prática e segura:
```bash
php artisan make:user --admin --name="Administrador" --email=admin@basqnba.com --password=admin123
```

### 6. Sincronizar Dados da NBA
Carregue os times, jogos e jogadores a partir das APIs externas:
```bash
# Sincroniza todas as entidades (times, jogos e jogadores)
php artisan nba:sync

# Ou sincronize entidades individuais:
php artisan nba:sync --only=teams
php artisan nba:sync --only=games
php artisan nba:sync --only=players
```

### 7. Iniciar o Servidor
```bash
php artisan serve
```
Acesse a aplicação no navegador em: **`http://localhost:8000`**  
Painel Administrativo: **`http://localhost:8000/admin`**

---

## 🐳 Executando com Docker

O projeto já inclui ambiente Docker completo com Nginx, PHP 8.3 FPM, MySQL 8 e Redis:

```bash
# Subir os contêineres em background
docker compose up -d

# Executar migrations no contêiner
docker compose exec app php artisan migrate

# Criar o usuário admin
docker compose exec app php artisan make:user --admin --name="Admin" --email=admin@basqnba.com --password=admin123

# Sincronizar dados
docker compose exec app php artisan nba:sync --only=teams
docker compose exec app php artisan nba:sync --only=games

# Acessar aplicação
# Nginx exposto na porta 8080: http://localhost:8080
```

---

## ⌨️ Comandos Artisan Úteis

O projeto possui comandos customizados na pasta `app/Console/Commands`:

```bash
# Sincronização geral com opções
php artisan nba:sync {--only=teams|games|players|all} {--season=2024} {--fresh} {--source=balldontlie|apisports}

# Criar novos usuários (admin ou comum)
php artisan make:user --name="Nome" --email="email@exemplo.com" [--password="senha"] [--admin]

# Gerar resumos e notícias automáticas com base nos resultados de jogos
php artisan nba:generate-news

# Gerar análises automáticas para confrontos
php artisan nba:generate-analysis

# Ajustar e formatar horários de jogos para timezone local
php artisan nba:fix-game-times

# Padronizar nomes, cidades e conferências dos times
php artisan nba:fix-teams

# Limpeza e higienização de registros duplicados/órfãos
php artisan nba:clean-db {--dry-run}

# Executar o agendador de tarefas diário (sincronização automática)
php artisan schedule:run

# Executar worker de filas (caso utilize queues em background)
php artisan queue:work
```

---

## 📂 Estrutura do Projeto

```
nba-website/
├── app/
│   ├── Console/Commands/        # Comandos Artisan (nba:sync, make:user, etc.)
│   ├── Http/
│   │   ├── Controllers/         # Controladores públicos e de perfil
│   │   │   ├── Admin/           # Controladores do painel administrativo
│   │   │   └── Auth/            # Controladores do Laravel Breeze
│   │   └── Middleware/          # Admin, Locale e Theme middlewares
│   ├── Mail/                    # Classes Mailables (boas-vindas, comentários, doações)
│   ├── Models/                  # Models Eloquent (User, Team, Player, Game, Post, etc.)
│   └── Services/                # Serviços de integração (Balldontlie, ApiSports, Pix, Sync)
├── config/                      # Configurações do framework
├── database/
│   ├── migrations/              # Migrações do banco de dados MySQL
│   └── seeders/                 # Seeders de desenvolvimento
├── docker/                      # Arquivos de configuração Nginx e PHP para Docker
├── lang/
│   ├── en/                      # Dicionário de traduções em Inglês
│   └── pt/                      # Dicionário de traduções em Português
├── resources/
│   ├── css/ & js/               # Assets frontend compilados pelo Vite
│   └── views/
│       ├── admin/               # Telas do painel administrativo
│       ├── auth/                # Telas de login, registro e recuperação
│       ├── components/          # Componentes Blade reutilizáveis
│       ├── donations/           # Telas do fluxo de doação via Pix
│       ├── estatisticas/        # Telas de estatísticas e franquias
│       ├── games/ & jogos/      # Telas de visualização e calendário de jogos
│       ├── jogadores/           # Telas de catálogo e ficha de atletas
│       ├── layouts/             # Layouts mestres (app.blade.php, guest.blade.php)
│       ├── noticias/ & posts/   # Telas de notícias e artigos
│       └── profile/             # Telas de edição de perfil
├── routes/
│   ├── auth.php                 # Rotas de autenticação Breeze
│   ├── console.php              # Agendamento de comandos (Schedule)
│   └── web.php                  # Rotas web (Públicas e Admin)
├── docker-compose.yml           # Configuração de containers Docker
├── Dockerfile                   # Build da imagem PHP
└── package.json & composer.json # Dependências do projeto
```

---

## 🔗 Integrações e APIs

1. **[balldontlie.io](https://balldontlie.io):**
   - Importação de dados completos de franquias, histórico e catálogo de jogadores.
   - Sincronização automatizada via `BalldontlieService` e `PlayerSyncService`.

2. **[API-Sports Basketball](https://api-sports.io):**
   - Dados ao vivo, calendário de temporadas, placares e dados complementares de confrontos.
   - Gerenciado via `ApiSportsService`.

3. **Pix BR Code (EMV):**
   - Geração estática e dinâmica de código Copia e Cola compatível com o padrão do Banco Central do Brasil.
   - Renderização de QR Code vetorial/rasterizado diretamente na tela via biblioteca `chillerlan/php-qrcode`.

---

## 📄 Licença

Este projeto é disponibilizado sob a licença [MIT](LICENSE).
