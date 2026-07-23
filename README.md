# EDUALFA — Site institucional

Site moderno e responsivo da **EDUALFA**, construído com **HTML, CSS, JavaScript, PHP** e **Vue 3 (via CDN)**, servido por **OpenLiteSpeed + LSPHP** (extremamente rápido) em contêiner Docker.

Paleta visual baseada na logo da marca (globo azul + monograma "EA"): azul-marinho, azul royal e ciano.

## ✨ Recursos

- **Design moderno e responsivo** (mobile-first), com animações suaves.
- **3 modalidades de cursos:** Supletivo EJA, Curso Técnico e Curso Livre.
- **Catálogo dinâmico** com filtro por modalidade (Vue 3).
- **API PHP** para catálogo (`/api/cursos.php`) e formulário de contato/matrícula (`/api/contato.php`).
- **Leads** salvos em CSV persistente (`data/leads.csv`).
- Botão flutuante de WhatsApp.

## 📁 Estrutura

```
site_edualfa/
├── Dockerfile              # OpenLiteSpeed + LSPHP
├── .dockerignore
├── public/                 # docroot servido pelo OpenLiteSpeed
│   ├── index.php           # página principal (Vue 3)
│   ├── assets/
│   │   ├── css/style.css
│   │   ├── js/app.js
│   │   └── img/
│   │       ├── edualfa.png            # logo colorida (fundos claros)
│   │       ├── edualfa-negativo.png   # logo branca (fundos escuros)
│   │       └── favicon.ico / .png
│   └── api/
│       ├── cursos.php      # catálogo em JSON
│       └── contato.php     # recebe leads → data/leads.csv
└── README.md
```

## 🚀 Deploy no EasyPanel (App via Dockerfile)

1. No EasyPanel, crie um **App** no projeto desejado.
2. Em **Source**, selecione **GitHub** e aponte para o repositório `daset-net/site_edualfa`, branch `main`.
3. Em **Build**, escolha **Dockerfile** (o EasyPanel detecta o `Dockerfile` na raiz).
4. Em **Domains/Ports**, publique a **porta 80** do contêiner no domínio desejado.
5. (Opcional) Em **Mounts**, adicione um volume persistente montado em
   `/var/www/vhosts/localhost/data` para preservar os leads (`leads.csv`).
6. **Deploy**. O OpenLiteSpeed sobe automaticamente e serve o site na porta 80.

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
- **Cursos:** edite o array em `public/api/cursos.php`.
- **Contatos (WhatsApp/e-mail):** ajuste em `public/index.php` e `public/api/contato.php`.
