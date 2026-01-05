# Catálogo de Filmes

Sistema de catálogo de filmes desenvolvido com Laravel (backend) e Vue.js (frontend), que permite buscar filmes na API do TMDB e gerenciar uma lista de filmes favoritos.

## 📋 Índice

- [Tecnologias](#tecnologias)
- [Pré-requisitos](#pré-requisitos)
- [Como rodar o projeto localmente com Docker](#como-rodar-o-projeto-localmente-com-docker)
- [Como importar o banco de dados](#como-importar-o-banco-de-dados)
- [Onde está implementado o CRUD](#onde-está-implementado-o-crud)
- [Como testar a aplicação](#como-testar-a-aplicação)
- [Link para obter a chave da API do TMDB](#link-para-obter-a-chave-da-api-do-tmdb)
- [Como subir o frontend separado](#como-subir-o-frontend-separado)

## Tecnologias

- **Backend**: Laravel 12 com PHP 8.2
- **Frontend**: Vue.js 3 com Vite
- **Banco de Dados**: MySQL 8.0
- **Containerização**: Docker e Docker Compose

## Pré-requisitos

- Docker
- Docker Compose
- Chave de API do TMDB (veja instruções abaixo)

## Como rodar o projeto localmente com Docker

### Passo 1: Clone o repositório

```bash
git clone <url-do-repositorio>
cd catalogo-filmes-king
```

### Passo 2: Configure o arquivo .env

Copie o arquivo `.env.example` para `.env` na pasta `backend-app`.

Depois, edite o arquivo `.env` e ajuste as seguintes variáveis conforme necessário:

```env
APP_NAME="Catálogo de Filmes"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=America/Sao_Paulo
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=catalogo_filmes
DB_USERNAME=root
DB_PASSWORD=root

TMDB_TOKEN="SEU_ACCESS_TOKEN_AQUI"
```

**Importante**:

- Substitua `SEU_ACCESS_TOKEN_AQUI` pela sua chave da API do TMDB. Veja a seção [Link para obter a chave da API do TMDB](#link-para-obter-a-chave-da-api-do-tmdb) para obter sua chave.

### Passo 3: Subir os containers

Execute o seguinte comando na raiz do projeto:

```bash
docker-compose up -d
```

Este comando irá:

- Criar e iniciar os containers do MySQL, Backend (Laravel) e Frontend (Vue.js)
- Executar as migrations do banco de dados
- Iniciar os servidores de desenvolvimento

### Passo 4: Acessar a aplicação

Após os containers estarem rodando, acesse:

- **Frontend**: http://localhost:5173
- **Backend API**: http://localhost:8000/api
- **MySQL**: localhost:3306

### Comandos úteis do Docker

#### Ver logs dos containers

```bash
# Todos os serviços
docker-compose logs -f

# Apenas backend
docker-compose logs -f backend

# Apenas frontend
docker-compose logs -f frontend
```

#### Parar os containers

```bash
docker-compose down
```

## Como importar o banco de dados

O projeto utiliza **migrations** do Laravel para criar a estrutura do banco de dados. Não é necessário importar um arquivo `.sql` manualmente.

### Opção 1: Migrations automáticas (recomendado)

As migrations são executadas automaticamente quando você sobe os containers com `docker-compose up -d`. O comando no `docker-compose.yml` já inclui:

```bash
php artisan migrate --force
```

### Opção 2: Executar migrations manualmente

Se precisar executar as migrations manualmente:

```bash
docker-compose exec backend php artisan migrate
```

### Estrutura do banco de dados

A tabela principal é `favorites_movies`, criada pela migration:

- **Arquivo**: `backend-app/database/migrations/2025_12_31_190902_create_favorites_movies_table.php`

### Credenciais do banco de dados

- **Database**: `catalogo_filmes`
- **Username**: `root`
- **Password**: `root`
- **Port**: `3306`
- **Host** (dentro do Docker): `mysql`

## Onde está implementado o CRUD

O CRUD de filmes favoritos está implementado nas seguintes localizações:

### Backend (Laravel)

#### Rotas da API

- **Arquivo**: `backend-app/routes/api.php`
- **Endpoints**:
  - `GET /api/favorites/list` - Lista todos os filmes favoritos (com paginação e filtros)
  - `POST /api/favorites/add-tmdb` - Adiciona um filme favorito pelo ID do TMDB
  - `DELETE /api/favorites/remove` - Remove um filme favorito

#### Controller

- **Arquivo**: `backend-app/app/Http/Controllers/FavoriteMovieController.php`
- **Métodos**:
  - `list()` - Lista filmes favoritos com paginação e filtros
  - `addByTmdbId()` - Adiciona filme favorito usando ID do TMDB
  - `remove()` - Remove filme favorito

#### Service (Lógica de negócio)

- **Arquivo**: `backend-app/app/Services/FavoriteMovieService.php`
- **Métodos**:
  - `list()` - Lógica de listagem com filtros
  - `addByTmdbId()` - Lógica para adicionar filme via TMDB
  - `add()` - Lógica para adicionar filme diretamente
  - `remove()` - Lógica para remover filme

#### Model

- **Arquivo**: `backend-app/app/Models/FavoriteMovie.php`
- **Tabela**: `favorites_movies`
- **Scopes**: `filterBySearch()`, `filterByGenres()`

#### Migration

- **Arquivo**: `backend-app/database/migrations/2025_12_31_190902_create_favorites_movies_table.php`

### Frontend (Vue.js)

#### View (Página principal)

- **Arquivo**: `frontend-app/src/views/Favorites.vue`
- **Funcionalidades**: Listagem, busca, filtros por gênero, paginação e remoção de favoritos

#### Store (Gerenciamento de estado)

- **Arquivo**: `frontend-app/src/stores/favorites.js`
- **Ações**: `fetchFavorites()`, `removeFavorite()`

## Como testar a aplicação

### 1. Teste de acesso à interface web

1. Acesse http://localhost:5173 no navegador
2. Verifique se a página carrega corretamente
3. Teste a busca de filmes na página inicial
4. Teste adicionar filmes aos favoritos
5. Acesse a página de favoritos e verifique:
   - Listagem de filmes favoritos
   - Busca por nome
   - Filtro por gêneros
   - Paginação
   - Remoção de favoritos

### 2. Teste da API do backend

Você pode testar os endpoints usando ferramentas como Postman, Insomnia ou curl:

#### Listar filmes favoritos

```bash
curl http://localhost:8000/api/favorites/list
```

#### Adicionar filme favorito

```bash
curl -X POST http://localhost:8000/api/favorites/add-tmdb \
  -H "Content-Type: application/json" \
  -d '{"tmdb_id": 550}'
```

#### Remover filme favorito

```bash
curl -X DELETE http://localhost:8000/api/favorites/remove \
  -H "Content-Type: application/json" \
  -d '{"id": 1}'
```

### 3. Testes automatizados

O projeto possui testes automatizados usando PHPUnit (testes de unidade e integração).

#### Executar todos os testes

```bash
docker-compose exec backend php artisan test
```

#### Executar testes específicos

```bash
# Apenas testes de unidade
docker-compose exec backend php artisan test --testsuite=Unit

# Apenas testes de integração
docker-compose exec backend php artisan test --testsuite=Feature

# Arquivo específico
docker-compose exec backend php artisan test tests/Feature/FavoriteMovieControllerTest.php
```

#### Testes disponíveis

- **Feature Tests**: `FavoriteMovieControllerTest`, `TmdbControllerTest`
- **Unit Tests**: `FavoriteMovieServiceTest`, `TmdbServiceTest`

Os testes cobrem funcionalidades como listagem, adição, remoção de favoritos, busca na API do TMDB, validações e tratamento de erros.

**Nota**: Os testes utilizam SQLite em memória e simulam chamadas à API do TMDB, então não é necessário configurar MySQL ou ter uma chave válida do TMDB para executá-los.

### 4. Verificar logs

Para verificar se há erros:

```bash
# Logs do backend
docker-compose logs -f backend

# Logs do frontend
docker-compose logs -f frontend
```

## Link para obter a chave da API do TMDB

### Passo 1: Criar conta no TMDB

1. Acesse o site oficial do TMDB: https://www.themoviedb.org/
2. Clique em "Sign Up" ou "Entrar" no canto superior direito
3. Crie uma conta gratuita ou faça login se já tiver uma

### Passo 2: Gerar a chave da API

1. Após fazer login, acesse: https://www.themoviedb.org/settings/api
2. Clique em "Request an API Key"
3. Preencha o formulário:
   - **Tipo**: Selecione "Developer" (para uso pessoal/desenvolvimento)
   - **Aplicação**: Preencha com informações sobre seu projeto
   - **URL**: Pode deixar em branco ou colocar `http://localhost:5173`
4. Aceite os termos de uso
5. Clique em "Submit"

### Passo 3: Obter o Access Token

Após a aprovação (geralmente imediata para contas de desenvolvedor):

1. Na página de configurações da API, você verá sua **API Key (v3 auth)**
2. Para usar com Bearer Token, você precisará do **Access Token**

### Passo 4: Configurar no projeto

Adicione a chave no arquivo `.env` do backend:

```env
TMDB_TOKEN=sua_chave_aqui
```

**Links úteis**:

- Site oficial: https://www.themoviedb.org/
- Documentação da API: https://developers.themoviedb.org/3
- Configurações da API: https://www.themoviedb.org/settings/api

## Como subir o frontend separado

Se você quiser rodar apenas o frontend Vue.js separadamente (sem Docker):

### Pré-requisitos

- Node.js (versão 20 ou superior)
- npm ou yarn

### Passo 1: Instalar dependências

```bash
cd frontend-app
npm install
```

### Passo 2: Configurar a URL do backend

Verifique o arquivo `frontend-app/src/config/axios.js` e ajuste a `baseURL` se necessário:

```javascript
const api = axios.create({
  baseURL: "http://localhost:8000/api", // Ajuste conforme sua URL do backend
  // ...
});
```

### Passo 3: Rodar o servidor de desenvolvimento

```bash
npm run dev
```

O frontend estará disponível em http://localhost:5173

### Comandos disponíveis

```bash
# Desenvolvimento
npm run dev

# Build para produção
npm run build

# Preview do build de produção
npm run preview
```

### Nota importante

Certifique-se de que o backend está rodando e acessível na URL configurada no `axios.js`, caso contrário, o frontend não conseguirá fazer requisições à API.

## 📝 Estrutura do Projeto

```
catalogo-filmes-king/
├── backend-app/          # Aplicação Laravel
│   ├── app/
│   │   ├── Http/Controllers/
│   │   ├── Models/
│   │   └── Services/
│   ├── database/migrations/
│   ├── routes/
│   └── .env             # Arquivo de configuração (criar)
├── frontend-app/         # Aplicação Vue.js
│   ├── src/
│   │   ├── components/
│   │   ├── views/
│   │   ├── stores/
│   │   └── config/
│   └── package.json
├── docker-compose.yml    # Configuração Docker Compose
└── README.md            # Este arquivo
```

## 🐛 Solução de Problemas

### Problema: Containers não iniciam

- Verifique se as portas 8000, 5173 e 3306 não estão em uso
- Execute `docker-compose down -v` e depois `docker-compose up -d --build`

### Problema: Erro de conexão com banco de dados

- Verifique se o container do MySQL está rodando: `docker-compose ps`
- Verifique as credenciais no `.env` do backend
- Aguarde alguns segundos após subir os containers para o MySQL estar pronto

### Problema: Erro ao buscar filmes do TMDB

- Verifique se a chave `TMDB_TOKEN` está configurada no `.env`
- Verifique se a chave está ativa no site do TMDB
- Verifique os logs: `docker-compose logs -f backend`

### Problema: Frontend não carrega

- Verifique se o container está rodando: `docker-compose ps`
- Verifique os logs: `docker-compose logs -f frontend`
- Limpe o cache do navegador
