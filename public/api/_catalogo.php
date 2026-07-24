<?php
// api/_catalogo.php — leitura dos dados do site no Directus.
//
// Três coleções, com papéis bem separados:
//   ava_catalogo_curso    → PREÇO (fonte única; não é duplicado em lugar nenhum)
//   site_catalogo_cursos  → camada editorial do site (imagem, textos, destaque)
//   site_configuracoes    → configurações gerais (contato, textos da home, SEO)
//
// Configuração (EasyPanel → Environment):
//   API_DIRECTUS_CONFIGURACOES   = https://cloud.edualfa.com.br
//   TOKEN_DIRECTUS_CONFIGURACOES = <token estático do Directus>
// (também aceita DIRECTUS_URL / DIRECTUS_TOKEN)

const COL_PRECOS = 'ava_catalogo_curso';
const COL_CURSOS = 'site_catalogo_cursos';
const COL_CONFIG = 'site_configuracoes';

const CACHE_TTL    = 600; // segundos
const HTTP_TIMEOUT = 8;

const COR_AZUL  = 'linear-gradient(140deg,#0f2f6b,#1e56d6)';
const COR_CIANO = 'linear-gradient(140deg,#1747b8,#22c9ec)';
const COR_NAVY  = 'linear-gradient(140deg,#061a3a,#1747b8)';

// Emoji por palavra-chave, usado só quando o curso não tem emoji nem capa definidos.
$EMOJIS = [
  'enfermagem' => '🩺', 'saúde bucal' => '🦷', 'estética' => '💅',
  'segurança' => '🦺', 'eletrot' => '⚡', 'eletromec' => '⚙️',
  'meio ambiente' => '🌱', 'edificações' => '🏗️',
  'administra' => '💼', 'contábil' => '🧾', 'informática' => '💻',
  'design' => '🎨', 'fundamental e médio' => '📚', '3º ano' => '📝',
  'médio' => '🎓',
];

$CATEGORIAS = [
  'EJA'          => ['eja', 'Supletivo EJA'],
  'TECNICO'      => ['tecnico', 'Curso Técnico'],
  'INFORMATICA'  => ['livre', 'Curso Livre'],
  'PROFISSIONAL' => ['livre', 'Curso Livre'],
];

// ---------------------------------------------------------------- config
function conexao(string $chave, string $padrao = ''): string {
  // Nomes equivalentes: o padrão dos outros sistemas EDUALFA (usado no EasyPanel)
  // e os nomes curtos deste projeto. Tenta ambos, em variável de ambiente e arquivo.
  $mapa = [
    'DIRECTUS_URL'   => 'API_DIRECTUS_CONFIGURACOES',
    'DIRECTUS_TOKEN' => 'TOKEN_DIRECTUS_CONFIGURACOES',
  ];
  $nomes = array_unique([$chave, $mapa[$chave] ?? $chave]);

  // Sob LiteSpeed/LSPHP a variável pode chegar por getenv, $_ENV ou $_SERVER.
  foreach ($nomes as $nome) {
    foreach ([getenv($nome), $_ENV[$nome] ?? false, $_SERVER[$nome] ?? false] as $valor) {
      if ($valor !== false && $valor !== '') return (string) $valor;
    }
  }

  foreach ($nomes as $nome) {
    $valor = arquivosConexao()[$nome] ?? '';
    if ($valor !== '') return $valor;
  }
  return $padrao;
}

/** Lê as credenciais de arquivos (dev local e .env gerado pelo entrypoint), com cache. */
function arquivosConexao(): array {
  static $dados = null;
  if ($dados !== null) return $dados;

  $dados = [];
  foreach ([
    __DIR__ . '/../../conexao/conexao_directus_avaset_unico_edualfa.txt', // dev local
    __DIR__ . '/../../.env',                                              // gravado pelo entrypoint
    __DIR__ . '/../.env',
    __DIR__ . '/.env',
    getcwd() . '/.env',
    '/.env',
    '/app/.env',
  ] as $caminho) {
    foreach (@file($caminho, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $linha) {
      $linha = trim($linha);
      if ($linha === '' || $linha[0] === '#' || strpos($linha, '=') === false) continue;
      [$k, $v] = explode('=', $linha, 2);
      $k = trim($k);
      if (!isset($dados[$k])) $dados[$k] = trim($v, " \t\"'");
    }
  }
  return $dados;
}

// ---------------------------------------------------------------- Directus
function directusBase(): string {
  return rtrim(conexao('DIRECTUS_URL'), '/');
}

/** GET numa coleção do Directus. Devolve as linhas ou null se falhar. */
function buscarColecao(string $colecao, array $params = []): ?array {
  $base  = directusBase();
  $token = conexao('DIRECTUS_TOKEN');
  if ($base === '' || $token === '') return null;

  $url = $base . '/items/' . $colecao . '?' . http_build_query($params + ['limit' => -1]);

  $ch = curl_init($url);
  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => HTTP_TIMEOUT,
    CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $token],
  ]);
  $corpo  = curl_exec($ch);
  $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  if ($corpo === false || $status !== 200) return null;
  $json = json_decode($corpo, true);
  return isset($json['data']) && is_array($json['data']) ? $json['data'] : null;
}

// ---------------------------------------------------------------- helpers
function caminhoCache(string $nome = 'catalogo'): string {
  return rtrim(sys_get_temp_dir(), '/\\') . '/edualfa_' . $nome . '.json';
}

/** Invalida o cache para que uma edição no Directus apareça no site na hora. */
function limparCache(): void {
  foreach (['catalogo', 'config'] as $nome) {
    @unlink(caminhoCache($nome));
  }
}

function nomeCurso(string $bruto): string {
  $nome = preg_replace('/^(Tecnologia|Profissional|Suporte)\s+/u', '', trim($bruto));
  return preg_replace('/\s+/u', ' ', $nome);
}

function emojiDe(string $nome, array $emojis): string {
  $alvo = mb_strtolower($nome, 'UTF-8');
  foreach ($emojis as $chave => $emoji) {
    if (mb_strpos($alvo, $chave, 0, 'UTF-8') !== false) return $emoji;
  }
  return '📘';
}

function moeda($valor): string {
  return number_format((float) $valor, 2, ',', '.');
}

/** Quebra um campo de texto "um item por linha" numa lista limpa. */
function linhas(?string $texto): array {
  if ($texto === null || trim($texto) === '') return [];
  $itens = preg_split('/\R/u', $texto);
  return array_values(array_filter(array_map('trim', $itens), fn($i) => $i !== ''));
}

// ---------------------------------------------------------------- configurações do site
/** Valor de uma chave da site_configuracoes. */
function config(string $chave, string $padrao = ''): string {
  static $mapa = null;
  if ($mapa === null) {
    $mapa = [];
    $cache = caminhoCache('config');

    $linhas = null;
    if (is_readable($cache) && (time() - filemtime($cache) < CACHE_TTL)) {
      $linhas = json_decode((string) file_get_contents($cache), true);
    }
    if (!is_array($linhas)) {
      $linhas = buscarColecao(COL_CONFIG, ['fields' => 'chave,valor,valor_extendido']);
      if ($linhas !== null) {
        @file_put_contents($cache, json_encode($linhas, JSON_UNESCAPED_UNICODE));
      } elseif (is_readable($cache)) {
        $linhas = json_decode((string) file_get_contents($cache), true) ?: [];
      } else {
        $linhas = [];
      }
    }
    foreach ($linhas as $l) {
      // valor_extendido tem precedência: é onde ficam os textos longos.
      $v = trim((string) ($l['valor_extendido'] ?? '')) !== ''
        ? $l['valor_extendido']
        : ($l['valor'] ?? '');
      if (isset($l['chave'])) $mapa[$l['chave']] = (string) $v;
    }
  }
  return ($mapa[$chave] ?? '') !== '' ? $mapa[$chave] : $padrao;
}

/** URL da imagem de capa (servida pelo proxy, para não expor o token). */
function urlImagem(?string $uuid): string {
  if (!$uuid || !preg_match('/^[0-9a-f-]{36}$/i', $uuid)) return '';
  return 'api/imagem.php?id=' . $uuid;
}

// ---------------------------------------------------------------- montagem
/**
 * Junta preço (ava_catalogo_curso) com a camada editorial (site_catalogo_cursos).
 * O preço nunca é lido da tabela do site — ela não guarda valores.
 */
function montarCatalogo(array $precos, array $editorial, array $ctx): array {
  extract($ctx); // $EMOJIS, $CATEGORIAS

  $site = [];
  foreach ($editorial as $e) {
    if (!empty($e['id_curso'])) $site[$e['id_curso']] = $e;
  }

  // Uma linha por curso: a versão com a menor parcela (melhor oferta vigente).
  $melhores = [];
  foreach ($precos as $l) {
    $id  = $l['id_curso'] ?? '';
    $cat = strtoupper($l['categoria'] ?? '');
    if ($id === '' || !isset($CATEGORIAS[$cat])) continue;

    $s = $site[$id] ?? null;
    if ($s && !($s['ativo'] ?? true)) continue;                       // desligado no Directus
    if ($CATEGORIAS[$cat][0] === 'livre' && !($s['destaque'] ?? false)) continue; // livre só em destaque

    $parcela = (float) ($l['valor_parcela'] ?? 0);
    if ($parcela <= 0) continue;
    if (!isset($melhores[$id]) || $parcela < (float) $melhores[$id]['valor_parcela']) {
      $melhores[$id] = $l;
    }
  }

  $peso = ['eja' => 1, 'tecnico' => 2, 'livre' => 3];
  uasort($melhores, function ($a, $b) use ($CATEGORIAS, $peso, $site) {
    $ca = $peso[$CATEGORIAS[strtoupper($a['categoria'])][0]];
    $cb = $peso[$CATEGORIAS[strtoupper($b['categoria'])][0]];
    if ($ca !== $cb) return $ca <=> $cb;
    $oa = (int) ($site[$a['id_curso']]['ordem'] ?? 999);
    $ob = (int) ($site[$b['id_curso']]['ordem'] ?? 999);
    return $oa <=> $ob ?: strcmp($a['id_curso'], $b['id_curso']);
  });

  $cores  = [COR_AZUL, COR_CIANO, COR_NAVY];
  $cursos = [];
  $i = 0;

  foreach ($melhores as $id => $l) {
    [$slug, $rotulo] = $CATEGORIAS[strtoupper($l['categoria'])];
    $s        = $site[$id] ?? [];
    $parcelas = (int) ($l['qtd_parcela'] ?? 0);
    $nome     = trim($s['nome_exibicao'] ?? '') !== '' ? $s['nome_exibicao'] : nomeCurso($l['curso'] ?? '');

    $cursos[] = [
      'id'             => $id,
      'categoria'      => $slug,
      'categoriaLabel' => $rotulo,
      'nome'           => $nome,
      'slug'           => $s['slug'] ?? '',
      'emoji'          => trim($s['emoji'] ?? '') !== '' ? $s['emoji'] : emojiDe($nome, $EMOJIS),
      'imagem'         => urlImagem($s['imagem_capa'] ?? null),
      'descricao'      => trim($s['descricao_card'] ?? '') !== ''
                            ? $s['descricao_card']
                            : 'Curso com certificação reconhecida e material 100% online.',
      'duracao'        => trim($s['duracao'] ?? '') !== ''
                            ? $s['duracao']
                            : ($parcelas > 0 ? $parcelas . ' meses' : 'Flexível'),
      'modalidade'     => trim($s['modalidade'] ?? '') !== ''
                            ? $s['modalidade']
                            : ($slug === 'tecnico' ? 'EAD com polo de apoio' : 'EAD'),

      // preço — sempre do ava_catalogo_curso
      'preco'          => moeda($l['valor_parcela']),
      'precoDe'        => moeda($l['valor_parcela_normal'] ?? 0),
      'parcelas'       => $parcelas,
      'desconto'       => (int) ($l['desconto'] ?? 0),
      'valorTotal'     => moeda($l['valor_total'] ?? 0),
      'codigo'         => $l['codigo_unico_especial'] ?? $id,

      // conteúdo da página de conversão
      'chamada'        => $s['chamada']  ?? '',
      'promessa'       => $s['promessa'] ?? '',
      'mercado'        => $s['mercado']  ?? '',
      'aprender'       => linhas($s['aprender'] ?? null),
      'publico'        => linhas($s['publico']  ?? null),
      'saidas'         => linhas($s['saidas']   ?? null),
      'seoTitulo'      => $s['seo_titulo']    ?? '',
      'seoDescricao'   => $s['seo_descricao'] ?? '',

      'cor'            => $cores[$i++ % 3],
    ];
  }

  return $cursos;
}

/**
 * Catálogo pronto para exibição, com cache em disco.
 * @return array{0: array, 1: string} lista de cursos e origem dos dados
 */
function catalogo(): array {
  global $EMOJIS, $CATEGORIAS;
  $ctx = compact('EMOJIS', 'CATEGORIAS');

  $cache = caminhoCache();

  if (is_readable($cache) && (time() - filemtime($cache) < CACHE_TTL)) {
    $cursos = json_decode((string) file_get_contents($cache), true);
    if (is_array($cursos) && $cursos !== []) return [$cursos, 'cache'];
  }

  $precos    = buscarColecao(COL_PRECOS, ['fields' =>
    'id_curso,categoria,curso,ingresso,desconto,qtd_parcela,valor_parcela,'
    . 'valor_parcela_normal,valor_total,codigo_unico_especial']);
  $editorial = buscarColecao(COL_CURSOS, ['fields' => '*']);

  if ($precos !== null) {
    $cursos = montarCatalogo($precos, $editorial ?? [], $ctx);
    if ($cursos !== []) {
      @file_put_contents($cache, json_encode($cursos, JSON_UNESCAPED_UNICODE));
      return [$cursos, 'directus'];
    }
  }

  // Directus fora do ar: serve o último catálogo conhecido, mesmo vencido.
  if (is_readable($cache)) {
    $cursos = json_decode((string) file_get_contents($cache), true);
    if (is_array($cursos) && $cursos !== []) return [$cursos, 'cache-expirado'];
  }

  return [[], 'indisponivel'];
}

/** Um curso do catálogo pelo id_curso (ex.: CT005) ou pelo slug. */
function cursoPorId(string $chave): ?array {
  [$cursos] = catalogo();
  foreach ($cursos as $c) {
    if ($c['id'] === $chave) return $c;
  }
  foreach ($cursos as $c) {
    if ($c['slug'] !== '' && strcasecmp($c['slug'], $chave) === 0) return $c;
  }
  return null;
}
