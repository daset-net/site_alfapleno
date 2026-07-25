<?php
// api/avisos.php — balões de prova social ("Fulano de tal se matriculou em…").
//
// Lê a coleção de inscrições especiais no Directus (nome da tabela na chave
// site_alunos_inscricoes_especiais da site_configuracoes) e devolve as linhas
// prontas para o balão, junto com o texto e os tempos configurados no painel.
//
// O texto é um modelo com marcadores, escrito na site_configuracoes:
//   {nome} {primeiro_nome} {curso} {cidade} {estado} {quando}
// *entre asteriscos* vira negrito.

require __DIR__ . '/_catalogo.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: public, max-age=300');

const AVISOS_LIMITE = 60; // quantas inscrições entram no rodízio

/** "há 12 minutos", "ontem", "há 3 meses" — a partir do data_hora da linha. */
function avisoQuando(?string $bruto): string {
  $texto = trim((string) $bruto);
  if ($texto === '') return '';

  $ts = strtotime($texto);
  if ($ts === false) return '';

  $seg = time() - $ts;
  if ($seg < 0)      return 'agora mesmo';
  if ($seg < 120)    return 'agora mesmo';
  if ($seg < 3600)   return 'há ' . (int) ($seg / 60) . ' minutos';
  if ($seg < 7200)   return 'há 1 hora';
  if ($seg < 86400)  return 'há ' . (int) ($seg / 3600) . ' horas';
  if ($seg < 172800) return 'ontem';
  if ($seg < 2592000) return 'há ' . (int) ($seg / 86400) . ' dias';
  if ($seg < 5184000) return 'há 1 mês';
  if ($seg < 31536000) return 'há ' . (int) ($seg / 2592000) . ' meses';
  return 'há mais de um ano';
}

/** Iniciais para o avatar quando o curso não tem capa. */
function avisoIniciais(string $nome): string {
  $partes = preg_split('/\s+/u', trim($nome)) ?: [];
  $partes = array_values(array_filter($partes, fn($p) => mb_strlen($p, 'UTF-8') > 2));
  if (!$partes) return '🎓';
  $ini = mb_substr($partes[0], 0, 1, 'UTF-8');
  if (count($partes) > 1) $ini .= mb_substr(end($partes), 0, 1, 'UTF-8');
  return mb_strtoupper($ini, 'UTF-8');
}

/**
 * Casa o curso escrito na inscrição com o curso do catálogo, para o balão
 * levar a capa e o link da página de conversão. Sem correspondência, o balão
 * ainda aparece — só sem imagem e sem link.
 */
function avisoCurso(string $nome, array $cursos): array {
  $alvo = mb_strtolower(trim($nome), 'UTF-8');
  if ($alvo === '') return [];

  foreach ($cursos as $c) {
    if (mb_strtolower($c['nome'], 'UTF-8') === $alvo) return $c;
  }
  // O catálogo tira prefixos ("Profissional", "Tecnologia") do nome exibido;
  // a inscrição costuma trazer o nome cheio, então tenta pelo miolo também.
  $curto = mb_strtolower(nomeCurso($nome), 'UTF-8');
  foreach ($cursos as $c) {
    if (mb_strtolower($c['nome'], 'UTF-8') === $curto) return $c;
  }
  // Nomes compostos ("EJA Ensino Médio + Técnico em Estética") batem com mais de
  // um curso: fica com o mais específico, isto é, o nome mais longo.
  $melhor = [];
  foreach ($cursos as $c) {
    $n = mb_strtolower($c['nome'], 'UTF-8');
    if ($n === '') continue;
    if (mb_strpos($alvo, $n, 0, 'UTF-8') === false && mb_strpos($curto, $n, 0, 'UTF-8') === false) continue;
    if (!$melhor || mb_strlen($n, 'UTF-8') > mb_strlen(mb_strtolower($melhor['nome'], 'UTF-8'), 'UTF-8')) {
      $melhor = $c;
    }
  }
  return $melhor;
}

/** Inscrições prontas para o balão, com cache em disco. */
function avisosInscricoes(): array {
  $cache = caminhoCache('avisos');
  if (is_readable($cache) && (time() - filemtime($cache) < CACHE_TTL)) {
    $itens = json_decode((string) file_get_contents($cache), true);
    if (is_array($itens)) return $itens;
  }

  $colecao = config('site_alunos_inscricoes_especiais', 'site_alunos_inscricoes_especiais');
  $linhas  = buscarColecao($colecao, [
    'fields' => 'nome,curso,cidade,estado,data_hora',
    'sort'   => '-data_hora',
    'limit'  => AVISOS_LIMITE,
  ]);

  if ($linhas === null) {
    // Directus fora do ar: serve a última lista conhecida, mesmo vencida.
    $itens = is_readable($cache) ? json_decode((string) file_get_contents($cache), true) : [];
    return is_array($itens) ? $itens : [];
  }

  [$cursos] = catalogo();

  $itens = [];
  foreach ($linhas as $l) {
    $nome  = trim((string) ($l['nome'] ?? ''));
    $curso = trim((string) ($l['curso'] ?? ''));
    if ($nome === '' || $curso === '') continue;

    $c = avisoCurso($curso, $cursos);

    $itens[] = [
      'nome'        => $nome,
      'primeiroNome' => preg_split('/\s+/u', $nome)[0] ?? $nome,
      'curso'       => $curso,
      'cidade'      => trim((string) ($l['cidade'] ?? '')),
      'estado'      => trim((string) ($l['estado'] ?? '')),
      'quando'      => avisoQuando($l['data_hora'] ?? null),
      'iniciais'    => avisoIniciais($nome),
      'imagem'      => $c['imagem'] ?? '',
      'emoji'       => $c['emoji'] ?? '',
      'link'        => isset($c['id']) ? 'curso.php?id=' . rawurlencode($c['slug'] !== '' ? $c['slug'] : $c['id']) : '',
    ];
  }

  @file_put_contents($cache, json_encode($itens, JSON_UNESCAPED_UNICODE));
  return $itens;
}

$ativo = strtolower(config('aviso_ativo', 'sim'));
$ativo = !in_array($ativo, ['nao', 'não', 'no', '0', 'off', 'false'], true);

$itens = $ativo ? avisosInscricoes() : [];

echo json_encode([
  'ok'    => $itens !== [],
  'ativo' => $ativo && $itens !== [],
  'config' => [
    'texto'     => config('aviso_texto', '*{nome}*, de {cidade} - {estado}, se matriculou no curso *{curso}*'),
    'rodape'    => config('aviso_rodape', '{quando} · matrícula confirmada'),
    'posicao'   => strtolower(config('aviso_posicao', 'esquerda')) === 'direita' ? 'direita' : 'esquerda',
    'primeiro'  => max(1, (int) config('aviso_primeiro_segundos', '8')),
    'intervalo' => max(3, (int) config('aviso_intervalo_segundos', '25')),
    'duracao'   => max(2, (int) config('aviso_duracao_segundos', '7')),
  ],
  'itens' => $itens,
], JSON_UNESCAPED_UNICODE);
