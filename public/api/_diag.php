<?php
// api/_diag.php — diagnóstico TEMPORÁRIO da saída HTTP do contêiner.
// Protegido pelo mesmo token da purga. Remover depois de investigar.

require __DIR__ . '/_catalogo.php';
header('Content-Type: application/json; charset=utf-8');

$esperado = conexao('TOKEN_PURGA_SITE');
if ($esperado === '' || !hash_equals($esperado, (string) ($_SERVER['HTTP_X_TOKEN'] ?? ''))) {
  http_response_code(403);
  echo json_encode(['ok' => false]);
  exit;
}

$alvo = conexao('AVASET_MATRICULA_URL', 'https://ead.edualfa.com.br/api/matricula_externa.php');

$ch = curl_init($alvo);
curl_setopt_array($ch, [
  CURLOPT_POST           => true,
  CURLOPT_POSTFIELDS     => '{}',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CONNECTTIMEOUT => 5,
  CURLOPT_TIMEOUT        => 20,
  CURLOPT_HTTPHEADER     => ['Content-Type: application/json', 'X-Api-Key: diagnostico'],
]);
$corpo = curl_exec($ch);

echo json_encode([
  'alvo'        => $alvo,
  'tem_token'   => conexao('TOKEN_MATRICULA_EXTERNA') !== '',
  'curl_errno'  => curl_errno($ch),
  'curl_erro'   => curl_error($ch),
  'status'      => curl_getinfo($ch, CURLINFO_HTTP_CODE),
  'ip'          => curl_getinfo($ch, CURLINFO_PRIMARY_IP),
  'tempo'       => round(curl_getinfo($ch, CURLINFO_TOTAL_TIME), 2),
  'resposta'    => substr((string) $corpo, 0, 300),
  'php'         => PHP_VERSION,
], JSON_UNESCAPED_UNICODE);
