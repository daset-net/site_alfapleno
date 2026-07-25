<?php
// api/_diag.php — diagnóstico TEMPORÁRIO da matrícula.
// Protegido pelo mesmo token da purga. Remover depois de investigar.
//
//   ?passo=rede    → chamada ao AVASET (token falso)
//   ?passo=plano   → planoVigente('CL001')
//   ?passo=limite  → gravação do controle por IP
//   ?passo=envio   → POST real ao AVASET com payload INCOMPLETO (não grava aluno)

require __DIR__ . '/_catalogo.php';
header('Content-Type: application/json; charset=utf-8');

$esperado = conexao('TOKEN_PURGA_SITE');
if ($esperado === '' || !hash_equals($esperado, (string) ($_SERVER['HTTP_X_TOKEN'] ?? ''))) {
  http_response_code(403);
  echo json_encode(['ok' => false]);
  exit;
}

$passo = $_GET['passo'] ?? 'rede';
$alvo  = conexao('AVASET_MATRICULA_URL', 'https://ead.edualfa.com.br/api/matricula_externa.php');
$saida = ['passo' => $passo, 'php' => PHP_VERSION];

if ($passo === 'plano') {
  $p = planoVigente('CL001');
  $saida['plano'] = $p;

} elseif ($passo === 'limite') {
  $dir = __DIR__ . '/../../data';
  $saida['dir']        = $dir;
  $saida['existe']     = is_dir($dir);
  $saida['gravavel']   = is_writable($dir);
  $saida['arquivo_ok'] = @file_put_contents($dir . '/diag.txt', 'ok') !== false;

} else {
  // rede | envio
  $corpoEnvio = $passo === 'envio'
    ? json_encode(['cpf' => '', 'nome' => 'DIAG'])   // proposital: o AVASET recusa com 422
    : '{}';
  $token = $passo === 'envio' ? conexao('TOKEN_MATRICULA_EXTERNA') : 'diagnostico';

  $ch = curl_init($alvo);
  curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $corpoEnvio,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_TIMEOUT        => 20,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json', 'X-Api-Key: ' . $token],
  ]);
  $corpo = curl_exec($ch);

  $saida += [
    'alvo'       => $alvo,
    'curl_errno' => curl_errno($ch),
    'curl_erro'  => curl_error($ch),
    'status'     => curl_getinfo($ch, CURLINFO_HTTP_CODE),
    'tempo'      => round(curl_getinfo($ch, CURLINFO_TOTAL_TIME), 2),
    'resposta'   => substr((string) $corpo, 0, 300),
  ];
}

echo json_encode($saida, JSON_UNESCAPED_UNICODE);
