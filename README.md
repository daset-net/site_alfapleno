# EDUALFA — Site institucional

Site moderno e responsivo da **EDUALFA**, construído com **HTML, CSS, JavaScript, PHP** e **Vue 3 (via CDN)**, servido por **OpenLiteSpeed + LSPHP** (extremamente rápido) em contêiner Docker.

Paleta visual baseada na logo da marca (globo azul + monograma "EA"): azul-marinho, azul royal e ciano.

## ✨ Recursos

- **Design moderno e responsivo** (mobile-first), com animações suaves.
- **3 modalidades de cursos:** Supletivo EJA, Curso Técnico e Curso Livre.
- **Catálogo dinâmico** com filtro por modalidade (Vue 3), alimentado pelo **Directus**,
  com preços e descontos reais.
- **Conteúdo editável no Directus**: textos, imagens de capa, contatos e SEO são
  administrados nas coleções do site — sem precisar mexer no código.
- **Página de conversão por curso** (`/curso.php?id=tecnico-em-administracao`):
  argumento de matrícula, grade, público, saídas profissionais, oferta com
  desconto, FAQ e formulário. Aceita o slug ou o código do curso.
- **API PHP** para catálogo (`/api/cursos.php`) e formulário de contato/matrícula (`/api/contato.php`).
- **Painel próprio** em `/admin`, com o **mesmo login do `ead.edualfa.com.br`**
  (sem cadastro nem senha separada), para trocar capas e editar textos.
- **Leads** salvos em CSV persistente (`data/leads.csv`).
- Botão flutuante de WhatsApp.

## 📁 Estrutura

```
site_edualfa/
├── Dockerfile              # OpenLiteSpeed + LSPHP
├── .dockerignore
├── public/                 # docroot servido pelo OpenLiteSpeed
│   ├── index.php           # página principal (Vue 3)
│   ├── curso.php           # página de conversão de um curso (?id=CT005)
│   ├── robots.txt          # bloqueia /admin e /api nos buscadores
│   ├── admin/              # painel: login do ead + capas e textos
│   │   ├── index.php       # login (tabela_gestores)
│   │   ├── painel.php      # configurações gerais do site
│   │   ├── cursos.php      # lista dos cursos + envio das capas
│   │   ├── curso.php       # edição dos textos de um curso
│   │   ├── _auth.php       # sessão, CSRF, limite de tentativas
│   │   ├── _dados.php      # escrita no Directus e upload de imagem
│   │   └── admin.css
│   ├── assets/
│   │   ├── css/style.css
│   │   ├── js/app.js       # Vue da home
│   │   ├── js/curso.js     # formulário e header da página do curso
│   │   └── img/
│   │       ├── edualfa.png            # logo colorida (fundos claros)
│   │       ├── edualfa-negativo.png   # logo branca (fundos escuros)
│   │       └── favicon.ico / .png
│   └── api/
│       ├── _catalogo.php   # leitura do Directus + cache (usado por todas as páginas)
│       ├── _conteudo.php   # texto de reserva por modalidade
│       ├── cursos.php      # catálogo em JSON
│       ├── imagem.php      # proxy das capas do Directus (não expõe o token)
│       ├── purgar.php      # limpa o cache sob demanda (chamado pelo AVASET)
│       └── contato.php     # recebe leads → data/leads.csv
└── README.md
```

## 🚀 Deploy no EasyPanel (App via Dockerfile)

1. No EasyPanel, crie um **App** no projeto desejado.
2. Em **Source**, selecione **GitHub** e aponte para o repositório `daset-net/site_edualfa`, branch `main`.
3. Em **Build**, escolha **Dockerfile** (o EasyPanel detecta o `Dockerfile` na raiz).
4. Em **Domains/Ports**, publique a **porta 80** do contêiner no domínio desejado.
5. Em **Environment**, configure o acesso ao Directus (a pasta `conexao/` **não**
   vai para a imagem — está no `.dockerignore`):

   ```
   DIRECTUS_URL=https://cloud.edualfa.com.br
   DIRECTUS_TOKEN=<token estático do Directus>
   TOKEN_PURGA_SITE=<segredo compartilhado com o AVASET>
   ```

   O `TOKEN_PURGA_SITE` é opcional: sem ele, `api/purgar.php` responde 503 e o
   cache só vence pelo tempo. Com ele, o AVASET limpa o cache na hora (veja
   *Cache e resiliência*).

   Localmente, sem essas variáveis, o `cursos.php` lê os valores de
   `conexao/conexao_directus_avaset_unico_edualfa.txt`.
6. (Opcional) Em **Mounts**, adicione um volume persistente montado em
   `/var/www/vhosts/localhost/data` para preservar os leads (`leads.csv`).
7. **Deploy**. O OpenLiteSpeed sobe automaticamente e serve o site na porta 80.

> Painel admin do OpenLiteSpeed disponível na porta **7080** (exponha apenas se necessário).

## 🐳 Rodando localmente

```bash
docker build -t edualfa .
docker run -p 8080:80 edualfa
# abra http://localhost:8080
```

## 🎨 Personalização

- **Logos:** os originais ficam em `logo/`. As versões web (fundo transparente,
  redimensionadas e otimizadas) ficam em `public/assets/img/`. A **negativa** é
  usada sobre fundos escuros (hero, rodapé e topo do header) e a **colorida**
  sobre fundos claros (header ao rolar). O header alterna entre as duas
  automaticamente via CSS.
- **Cores:** variáveis CSS no topo de `public/assets/css/style.css`.

## 🗄️ Conteúdo no Directus

Quase tudo do site é editado no Directus da EDUALFA, **sem mexer no código**.
Três coleções, com papéis bem separados:

| Coleção | Papel |
|---|---|
| `ava_catalogo_curso` | **Preço + quais cursos existem.** Fonte única de valores, parcelas e descontos. O campo `ativo` liga/desliga o curso (controlado no painel do AVASET). |
| `site_catalogo_cursos` | **Camada editorial (opcional).** Imagem de capa, textos, slug e ordem de cada curso. |
| `site_configuracoes` | **Configurações gerais.** Contato, redes sociais, textos da home, números e SEO. |

> **Quais cursos aparecem no site:** todos os do `ava_catalogo_curso`, **menos os desativados**
> (`ativo = false`). O interruptor fica no painel do AVASET (Catálogo de Cursos) e vale ao mesmo
> tempo para o site e para a matrícula. A ficha em `site_catalogo_cursos` é **opcional**: quando
> existe, dá capa e textos próprios; quando não existe, o curso ainda aparece usando o nome do
> catálogo e um texto padrão da modalidade.

### `site_catalogo_cursos` — uma linha por curso

Ligada ao preço pelo campo `id_curso` (ex.: `CT005`). **Não guarda valores** — o
preço é buscado no `ava_catalogo_curso` na hora da leitura, então alterar um
desconto lá se reflete no site sozinho, sem risco de anunciar preço errado.

- `ativo` — oculta o curso **só do site**, sem tirá-lo da matrícula (para desligar dos dois, use o interruptor no AVASET).
- `ordem` — posição dentro da modalidade.
- `imagem_capa` — capa do card e da página. Sem imagem, o site usa o `emoji`.
- `nome_exibicao`, `descricao_card`, `duracao`, `modalidade`, `slug`.
- `chamada`, `promessa`, `mercado` — texto de conversão da página do curso.
- `aprender`, `publico`, `saidas` — **um item por linha**.
- `seo_titulo`, `seo_descricao`.

Campo em branco cai num padrão sensato da modalidade (`public/api/_conteudo.php`),
então a página nunca abre quebrada.

### `site_configuracoes` — chave/valor

Mesmo formato da `avaset_configuracoes`. Chaves usadas hoje: `whatsapp` (só
dígitos, formato internacional), `telefone_exibicao`, `email_contato`,
`horario_atendimento`, `instagram`, `facebook`, `youtube` (vazio esconde o
ícone), `hero_badge`, `hero_titulo`, `hero_subtitulo`, `stat_alunos`,
`stat_cursos`, `stat_satisfacao`, `seo_titulo`, `seo_descricao`.

Textos longos podem ir em `valor_extendido`, que tem precedência sobre `valor`.

### Cache e resiliência

O catálogo e as configurações ficam **10 minutos em cache** em disco. Se o
Directus ficar fora do ar, o site continua servindo a última versão conhecida em
vez de aparecer vazio.

Edições feitas pelo `/admin` do site limpam o cache na hora. Mudanças feitas no
**AVASET** (GESET → *Catálogo cursos*, ligar/desligar um curso) chegam pela
purga: o AVASET chama `POST /api/purgar.php` com o header `X-Token`, e o site
descarta o cache. Sem o `TOKEN_PURGA_SITE` configurado dos dois lados, a
mudança continua valendo — só demora até 10 minutos para aparecer.

```bash
curl -X POST -H "X-Token: $TOKEN_PURGA_SITE" https://edualfa.com.br/api/purgar.php
```

> O cache é um arquivo no disco do contêiner: com mais de uma réplica, a purga
> atinge só a réplica que atendeu a chamada.

### Imagens

As capas são servidas por `public/api/imagem.php`, que busca o arquivo no
Directus pelo servidor e devolve só os bytes — assim o token **não** vai para o
navegador. Aceita `?w=` em 400, 600, 800, 1200 ou 1600.

## 🔐 Painel do site (`/admin`)

Para quem não quer abrir o Directus, o site tem um painel próprio em
`https://edualfa.com.br/admin`.

**Não existe cadastro nem senha separada.** O login é validado contra a
`tabela_gestores` do Directus da EDUALFA — a mesma do `ead.edualfa.com.br`.
Quem troca a senha no AVASET troca aqui junto; quem é bloqueado lá perde o
acesso aqui na hora.

- **Quem entra:** gestores de nível `admin`/`geral` (mesma regra que o painel do
  AVASET usa para liberar a tela de gestores). Gestor com `situacao` bloqueado,
  inativo ou desativado é barrado.
- **Verificação de senha:** bcrypt → texto puro (legado) → MD5, na mesma ordem do
  `api/login.php` do AVASET, para nenhum gestor existente ficar de fora.
- **O que dá para fazer:** trocar a **imagem do topo (hero)** da home,
  trocar/remover a capa dos cursos, mostrar ou esconder um curso, pôr um curso
  livre na vitrine, editar todos os textos da página do curso e as configurações
  gerais do site (topo, contatos, redes, números e SEO).
- **O que NÃO dá para fazer:** mexer em preço, parcelas ou desconto — isso é do
  catálogo do AVASET, de propósito.

Ao salvar, o cache é limpo automaticamente, então a mudança aparece no site na
hora. O painel tem `noindex`, está bloqueado no `robots.txt`, exige token CSRF
em todo formulário, expira a sessão em 2 horas de inatividade e trava o IP após
8 tentativas de login erradas em 15 minutos.

> As imagens são validadas pelo conteúdo (não pela extensão) e limitadas a 8 MB.
> Aceita JPG, PNG, WEBP e GIF.
