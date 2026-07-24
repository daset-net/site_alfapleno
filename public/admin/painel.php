<?php
// admin/painel.php — configurações gerais do site (site_configuracoes).

require __DIR__ . '/_auth.php';
require __DIR__ . '/_dados.php';
exigirLogin();

$aviso = '';
$tipo  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!csrfValido($_POST['csrf'] ?? null)) {
    $aviso = 'Sessão inválida. Recarregue a página e tente de novo.';
    $tipo  = 'erro';
  } else {
    $valores = $_POST['valor'] ?? [];
    $erros = [];
    $n = 0;

    foreach ($valores as $id => $valor) {
      $valor = trim((string) $valor);
      // Textos longos vão para valor_extendido, que tem precedência na leitura.
      $campos = mb_strlen($valor) > 200
        ? ['valor' => '', 'valor_extendido' => $valor]
        : ['valor' => $valor, 'valor_extendido' => ''];

      [$ok, $msg] = salvarItem(COL_CONFIG, (int) $id, $campos);
      if ($ok) { $n++; } else { $erros[] = $msg; }
    }

    limparCache();
    if ($erros) {
      $aviso = 'Algumas alterações não foram salvas: ' . implode(' ', array_unique($erros));
      $tipo  = 'erro';
    } else {
      $aviso = "Pronto! $n configurações salvas. O site já está mostrando as mudanças.";
      $tipo  = 'ok';
    }
  }
}

$configs = configuracoesDoPainel();
$titulo  = 'Configurações do site';
$abaAtiva = 'painel';
require __DIR__ . '/_topo.php';
?>

<form method="post" class="painel-form">
  <input type="hidden" name="csrf" value="<?= e(csrf()) ?>">

  <?php if ($aviso): ?>
    <div class="aviso aviso--<?= e($tipo) ?>">
      <i class="ri-<?= $tipo === 'ok' ? 'check' : 'error-warning' ?>-line"></i> <?= e($aviso) ?>
    </div>
  <?php endif; ?>

  <p class="painel-intro">
    Estes campos aparecem no site na hora em que você salva. Deixe em branco os links
    de rede social que você não usa — o ícone some do rodapé sozinho.
  </p>

  <div class="campos">
    <?php foreach ($configs as $c):
      $valor = trim((string) ($c['valor_extendido'] ?? '')) !== ''
        ? $c['valor_extendido'] : ($c['valor'] ?? '');
      $longo = mb_strlen((string) $valor) > 80 || in_array($c['chave'], ['hero_subtitulo', 'seo_descricao'], true);
      $tabela = str_starts_with((string) $c['chave'], 'api_');
    ?>
      <div class="campo <?= $tabela ? 'campo--tecnico' : '' ?>">
        <label for="c<?= (int) $c['id'] ?>">
          <?= e(ucfirst(str_replace('_', ' ', $c['chave']))) ?>
          <?php if (!empty($c['descricao'])): ?>
            <small><?= e($c['descricao']) ?></small>
          <?php endif; ?>
        </label>
        <?php if ($longo): ?>
          <textarea id="c<?= (int) $c['id'] ?>" name="valor[<?= (int) $c['id'] ?>]" rows="3"><?= e((string) $valor) ?></textarea>
        <?php else: ?>
          <input id="c<?= (int) $c['id'] ?>" type="text" name="valor[<?= (int) $c['id'] ?>]" value="<?= e((string) $valor) ?>">
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="barra-salvar">
    <button type="submit" class="btn btn-primary">Salvar alterações <i class="ri-save-line"></i></button>
  </div>
</form>

<?php require __DIR__ . '/_rodape.php'; ?>
