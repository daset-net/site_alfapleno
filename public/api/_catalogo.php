<?php
// api/_catalogo.php — leitura do catálogo de cursos no Directus (ava_catalogo_curso).
// Usado por api/cursos.php (JSON para a home) e por curso.php (página do curso).
//
// Configuração (EasyPanel → Environment):
//   DIRECTUS_URL   = https://cloud.edualfa.com.br
//   DIRECTUS_TOKEN = <token estático do Directus>
// Em desenvolvimento local, se as variáveis não existirem, os valores são lidos
// de ../../conexao/conexao_directus_avaset_unico_edualfa.txt (pasta não versionada).

const COLECAO      = 'ava_catalogo_curso';
const CACHE_TTL    = 600; // segundos
const HTTP_TIMEOUT = 8;

const COR_AZUL  = 'linear-gradient(140deg,#0f2f6b,#1e56d6)';
const COR_CIANO = 'linear-gradient(140deg,#1747b8,#22c9ec)';
const COR_NAVY  = 'linear-gradient(140deg,#061a3a,#1747b8)';

// ---------------------------------------------------------------- curadoria
// Cursos livres em destaque no site (os demais ficam fora da vitrine).
$LIVRES_DESTAQUE = ['CL001', 'CL003', 'CL006', 'CL011', 'CL017'];

// Duração e descrição não existem no Directus — ficam curadas aqui.
$DURACAO = [
  'CE001' => '12 meses', 'CE002' => '8 meses', 'CE003' => '6 meses',
  'CT002' => '18 meses', 'CT003' => '18 meses', 'CT004' => '18 meses',
  'CT005' => '18 meses', 'CT006' => '18 meses', 'CT007' => '18 meses',
  'CT008' => '18 meses', 'CT009' => '18 meses', 'CT010' => '18 meses',
  'CT011' => '18 meses',
  'CL001' => '80 horas', 'CL003' => '100 horas', 'CL006' => '120 horas',
  'CL011' => '120 horas', 'CL017' => '120 horas',
];

$DESCRICAO = [
  'CE001' => 'Conclua o ensino fundamental e o médio de uma só vez, com certificação reconhecida e válida em todo o país.',
  'CE002' => 'Termine o ensino médio no seu ritmo e conquiste o certificado para faculdade, concursos e trabalho.',
  'CE003' => 'Só falta o 3º ano? Conclua apenas a etapa que ficou pendente e receba seu certificado.',
  'CT002' => 'Atue com topografia, georreferenciamento e levantamentos em obras e projetos de engenharia.',
  'CT003' => 'Trabalhe ao lado do cirurgião-dentista em clínicas e na saúde pública, com formação técnica completa.',
  'CT004' => 'Una mecânica e eletricidade industrial para manter e operar máquinas em qualquer indústria.',
  'CT005' => 'Domine gestão, finanças e rotinas administrativas exigidas pelo mercado de trabalho.',
  'CT006' => 'Torne-se especialista em prevenção de acidentes e normas regulamentadoras (NRs).',
  'CT007' => 'Projete, instale e faça a manutenção de sistemas elétricos residenciais, prediais e industriais.',
  'CT008' => 'Atue com licenciamento, gestão de resíduos e sustentabilidade em empresas e órgãos públicos.',
  'CT009' => 'Acompanhe obras, leia projetos e domine as etapas da construção civil.',
  'CT010' => 'Aprenda desenvolvimento, redes e suporte para atuar na área de tecnologia.',
  'CT011' => 'Formação completa em estética facial, corporal e capilar para atuar no mercado da beleza.',
  'CL001' => 'Windows, Word, Excel e internet: a base que todo profissional precisa dominar.',
  'CL003' => 'Crie artes profissionais para redes sociais, marcas e materiais impressos.',
  'CL006' => 'Prepare-se para atuar em consultórios odontológicos com biossegurança e atendimento ao paciente.',
  'CL011' => 'Rotinas de escritório, atendimento, documentos e organização para começar já a trabalhar.',
  'CL017' => 'Escrituração, obrigações fiscais e rotinas de departamento pessoal na prática.',
];

// Emoji por palavra-chave do nome do curso (primeira correspondência vence).
$EMOJIS = [
  'enfermagem' => '🩺', 'saúde bucal' => '🦷', 'estética' => '💅',
  'segurança' => '🦺', 'eletrot' => '⚡', 'eletromec' => '⚙️',
  'meio ambiente' => '🌱', 'edificações' => '🏗️', 'agrimensura' => '📐',
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
  $env = getenv($chave);
  if ($env !== false && $env !== '') return $env;

  static $arquivo = null;
  if ($arquivo === null) {
    $arquivo = [];
    $caminho = __DIR__ . '/../../conexao/conexao_directus_avaset_unico_edualfa.txt';
    foreach (@file($caminho, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $linha) {
      if (strpos($linha, '=') === false) continue;
      [$k, $v] = explode('=', $linha, 2);
      $arquivo[trim($k)] = trim($v);
    }
  }
  $mapa = [
    'DIRECTUS_URL'   => 'API_DIRECTUS_CONFIGURACOES',
    'DIRECTUS_TOKEN' => 'TOKEN_DIRECTUS_CONFIGURACOES',
  ];
  return $arquivo[$mapa[$chave] ?? $chave] ?? $padrao;
}

// ---------------------------------------------------------------- helpers
function caminhoCache(): string {
  return rtrim(sys_get_temp_dir(), '/\\') . '/edualfa_catalogo.json';
}

function buscarDirectus(): ?array {
  $base  = rtrim(conexao('DIRECTUS_URL'), '/');
  $token = conexao('DIRECTUS_TOKEN');
  if ($base === '' || $token === '') return null;

  $url = $base . '/items/' . COLECAO . '?' . http_build_query([
    'limit'  => -1,
    'fields' => 'id,id_curso,categoria,curso,ingresso,desconto,qtd_parcela,'
              . 'valor_parcela,valor_parcela_normal,valor_total,codigo_unico_especial',
  ]);

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

// ---------------------------------------------------------------- montagem
function montarCatalogo(array $linhas, array $ctx): array {
  extract($ctx); // $LIVRES_DESTAQUE, $DURACAO, $DESCRICAO, $EMOJIS, $CATEGORIAS

  // Uma linha por curso: a versão com a menor parcela (melhor oferta vigente).
  $melhores = [];
  foreach ($linhas as $l) {
    $id  = $l['id_curso'] ?? '';
    $cat = strtoupper($l['categoria'] ?? '');
    if ($id === '' || !isset($CATEGORIAS[$cat])) continue;
    if ($CATEGORIAS[$cat][0] === 'livre' && !in_array($id, $LIVRES_DESTAQUE, true)) continue;

    $parcela = (float) ($l['valor_parcela'] ?? 0);
    if ($parcela <= 0) continue;
    if (!isset($melhores[$id]) || $parcela < (float) $melhores[$id]['valor_parcela']) {
      $melhores[$id] = $l;
    }
  }

  $ordem = ['eja' => 1, 'tecnico' => 2, 'livre' => 3];
  uasort($melhores, function ($a, $b) use ($CATEGORIAS, $ordem) {
    $ca = $ordem[$CATEGORIAS[strtoupper($a['categoria'])][0]];
    $cb = $ordem[$CATEGORIAS[strtoupper($b['categoria'])][0]];
    return $ca <=> $cb ?: strcmp($a['id_curso'], $b['id_curso']);
  });

  $cores  = [COR_AZUL, COR_CIANO, COR_NAVY];
  $cursos = [];
  $i = 0;
  foreach ($melhores as $id => $l) {
    [$slug, $rotulo] = $CATEGORIAS[strtoupper($l['categoria'])];
    $nome     = nomeCurso($l['curso'] ?? '');
    $parcelas = (int) ($l['qtd_parcela'] ?? 0);

    $cursos[] = [
      'id'             => $id,
      'categoria'      => $slug,
      'categoriaLabel' => $rotulo,
      'nome'           => $nome,
      'emoji'          => emojiDe($nome, $EMOJIS),
      'descricao'      => $DESCRICAO[$id] ?? 'Curso com certificação reconhecida e material 100% online.',
      'duracao'        => $DURACAO[$id] ?? ($parcelas > 0 ? $parcelas . ' meses' : 'Flexível'),
      'modalidade'     => $slug === 'tecnico' ? 'EAD com polo de apoio' : 'EAD',
      'preco'          => moeda($l['valor_parcela']),
      'precoDe'        => moeda($l['valor_parcela_normal'] ?? 0),
      'parcelas'       => $parcelas,
      'desconto'       => (int) ($l['desconto'] ?? 0),
      'valorTotal'     => moeda($l['valor_total'] ?? 0),
      'codigo'         => $l['codigo_unico_especial'] ?? $id,
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
  global $LIVRES_DESTAQUE, $DURACAO, $DESCRICAO, $EMOJIS, $CATEGORIAS;
  $ctx = compact('LIVRES_DESTAQUE', 'DURACAO', 'DESCRICAO', 'EMOJIS', 'CATEGORIAS');

  $cache = caminhoCache();

  if (is_readable($cache) && (time() - filemtime($cache) < CACHE_TTL)) {
    $cursos = json_decode((string) file_get_contents($cache), true);
    if (is_array($cursos) && $cursos !== []) return [$cursos, 'cache'];
  }

  $linhas = buscarDirectus();
  if ($linhas !== null) {
    $cursos = montarCatalogo($linhas, $ctx);
    @file_put_contents($cache, json_encode($cursos, JSON_UNESCAPED_UNICODE));
    return [$cursos, 'directus'];
  }

  // Directus fora do ar: serve o último catálogo conhecido, mesmo vencido.
  if (is_readable($cache)) {
    $cursos = json_decode((string) file_get_contents($cache), true);
    if (is_array($cursos) && $cursos !== []) return [$cursos, 'cache-expirado'];
  }

  return [[], 'indisponivel'];
}

/** Um curso do catálogo pelo id_curso (ex.: CT005), ou null. */
function cursoPorId(string $id): ?array {
  [$cursos] = catalogo();
  foreach ($cursos as $c) {
    if ($c['id'] === $id) return $c;
  }
  return null;
}
