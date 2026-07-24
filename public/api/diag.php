<?php
// api/diag.php — diagnóstico TEMPORÁRIO da conexão com o Directus.
// Não expõe o token (só presença/tamanho). Remover após validar o deploy.
require __DIR__ . '/_catalogo.php';

header('Content-Type: application/json; charset=utf-8');

$nomes = ['DIRECTUS_URL', 'DIRECTUS_TOKEN', 'API_DIRECTUS_CONFIGURACOES', 'TOKEN_DIRECTUS_CONFIGURACOES'];

$origem = [];
foreach ($nomes as $n) {
  $origem[$n] = [
    'getenv'   => getenv($n) !== false && getenv($n) !== '',
    '_ENV'     => !empty($_ENV[$n]),
    '_SERVER'  => !empty($_SERVER[$n]),
    'arquivo'  => !empty(arquivosConexao()[$n]),
  ];
}

$candidatos = [
  __DIR__ . '/../../conexao/conexao_directus_avaset_unico_edualfa.txt',
  __DIR__ . '/../../.env', __DIR__ . '/../.env', __DIR__ . '/.env',
  getcwd() . '/.env', '/.env', '/app/.env',
];
$arquivos = [];
foreach ($candidatos as $c) $arquivos[$c] = is_readable($c);

$url   = conexao('DIRECTUS_URL');
$token = conexao('DIRECTUS_TOKEN');

$curl = ['tentado' => false];
if ($url !== '' && $token !== '') {
  $curl['tentado'] = true;
  $ch = curl_init(rtrim($url, '/') . '/items/' . COLECAO . '?limit=1');
  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 8,
    CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $token],
  ]);
  $corpo = curl_exec($ch);
  $curl['errno']   = curl_errno($ch);
  $curl['erro']    = curl_error($ch);
  $curl['status']  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $curl['trecho']  = is_string($corpo) ? mb_substr($corpo, 0, 160) : null;
  curl_close($ch);
}

[$cursos, $orig] = catalogo();

echo json_encode([
  'php'          => PHP_VERSION,
  'sapi'         => php_sapi_name(),
  'curl_ext'     => extension_loaded('curl'),
  'cwd'          => getcwd(),
  'dir'          => __DIR__,
  'url_resolvida'=> $url !== '' ? preg_replace('#^(https?://[^/]+).*#', '$1', $url) : '',
  'token_len'    => strlen($token),
  'variaveis'    => $origem,
  'arquivos'     => $arquivos,
  'curl'         => $curl,
  'catalogo'     => ['origem' => $orig, 'total' => count($cursos)],
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
