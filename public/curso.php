<?php
// curso.php — página de conversão de um curso. Preço vem do Directus, o texto de _conteudo.php.
// Uso: /curso.php?id=CT005

require __DIR__ . '/api/_catalogo.php';
require __DIR__ . '/api/_conteudo.php';

$id    = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $_GET['id'] ?? ''));
$curso = $id !== '' ? cursoPorId($id) : null;

if (!$curso) {
  http_response_code(404);
  header('Location: index.php#cursos');
  exit;
}

[$todos] = catalogo();
$conteudo = conteudoCurso($curso);
$ano = date('Y');

// Até 3 cursos da mesma modalidade para o rodapé da página.
$relacionados = array_values(array_filter(
  $todos,
  fn($c) => $c['categoria'] === $curso['categoria'] && $c['id'] !== $curso['id']
));
shuffle($relacionados);
$relacionados = array_slice($relacionados, 0, 3);

$economia = max(0, (float) str_replace(['.', ','], ['', '.'], $curso['precoDe'])
                 - (float) str_replace(['.', ','], ['', '.'], $curso['preco']));

function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

$tituloPagina = $curso['nome'] . ' · ' . $curso['categoriaLabel'] . ' · EDUALFA';
$whatsapp = 'https://wa.me/5500000000000?text=' . rawurlencode('Olá! Quero saber mais sobre o curso ' . $curso['nome'] . '.');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= e($conteudo['chamada']) ?>. <?= e($curso['descricao']) ?> Matrículas abertas na EDUALFA.">
  <meta name="theme-color" content="#0f2f6b">
  <title><?= e($tituloPagina) ?></title>

  <meta property="og:title" content="<?= e($curso['nome']) ?> · EDUALFA">
  <meta property="og:description" content="<?= e($conteudo['chamada']) ?>">
  <meta property="og:type" content="website">

  <link rel="icon" href="assets/img/favicon.ico" sizes="any">
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
  <link rel="apple-touch-icon" href="assets/img/favicon.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="page-curso">

  <!-- ===================== HEADER ===================== -->
  <header class="header" id="header">
    <div class="container header__inner">
      <a href="index.php" class="brand">
        <img class="brand__neg" src="assets/img/edualfa-negativo.png" alt="EDUALFA">
        <img class="brand__cor" src="assets/img/edualfa.png" alt="EDUALFA">
      </a>
      <nav class="nav">
        <a href="index.php">Início</a>
        <a href="index.php#cursos">Cursos</a>
        <a href="index.php#diferenciais">Diferenciais</a>
        <a href="index.php#contato">Contato</a>
      </nav>
      <div class="header__cta">
        <a href="#matricula" class="btn btn-primary">Quero me matricular <i class="ri-arrow-right-line"></i></a>
      </div>
    </div>
  </header>

  <!-- ===================== HERO DO CURSO ===================== -->
  <section class="curso-hero">
    <div class="container curso-hero__grid">
      <div class="curso-hero__content">
        <nav class="crumbs">
          <a href="index.php">Início</a> <i class="ri-arrow-right-s-line"></i>
          <a href="index.php#cursos"><?= e($curso['categoriaLabel']) ?></a> <i class="ri-arrow-right-s-line"></i>
          <span><?= e($curso['nome']) ?></span>
        </nav>

        <span class="curso-hero__badge"><span class="dot"></span> Matrículas abertas · Turmas <?= $ano ?></span>
        <h1><?= e($conteudo['chamada']) ?></h1>
        <p class="curso-hero__nome"><?= e($curso['emoji']) ?> <?= e($curso['nome']) ?> · <?= e($curso['categoriaLabel']) ?></p>
        <p class="lead"><?= e($conteudo['promessa']) ?></p>

        <ul class="curso-hero__marcas">
          <li><i class="ri-time-line"></i> <?= e($curso['duracao']) ?></li>
          <li><i class="ri-computer-line"></i> <?= e($curso['modalidade']) ?></li>
          <li><i class="ri-award-line"></i> Certificado com validade nacional</li>
          <li><i class="ri-calendar-check-line"></i> Comece hoje mesmo</li>
        </ul>

        <div class="curso-hero__actions">
          <a href="#matricula" class="btn btn-light">Garantir minha vaga <i class="ri-arrow-right-line"></i></a>
          <a href="<?= e($whatsapp) ?>" target="_blank" rel="noopener" class="btn btn-ghost-light">
            <i class="ri-whatsapp-line"></i> Tirar dúvidas no WhatsApp
          </a>
        </div>
      </div>

      <!-- Cartão de oferta -->
      <aside class="oferta" id="oferta">
        <div class="oferta__topo" style="background: <?= e($curso['cor']) ?>">
          <span class="oferta__emoji"><?= e($curso['emoji']) ?></span>
          <?php if ($curso['desconto']): ?>
            <span class="oferta__off">-<?= (int) $curso['desconto'] ?>% hoje</span>
          <?php endif; ?>
        </div>
        <div class="oferta__corpo">
          <span class="oferta__cat"><?= e($curso['categoriaLabel']) ?></span>
          <h2><?= e($curso['nome']) ?></h2>

          <?php if ($curso['precoDe'] !== '0,00'): ?>
            <p class="oferta__de">De <s>R$ <?= e($curso['precoDe']) ?></s> por mês</p>
          <?php endif; ?>

          <p class="oferta__por">
            <?php if ($curso['parcelas']): ?><em><?= (int) $curso['parcelas'] ?>x de</em><?php endif; ?>
            <strong>R$ <?= e($curso['preco']) ?></strong>
          </p>
          <?php if ($curso['valorTotal'] !== '0,00'): ?>
            <p class="oferta__total">Total do curso: R$ <?= e($curso['valorTotal']) ?></p>
          <?php endif; ?>
          <?php if ($economia > 0): ?>
            <p class="oferta__economia"><i class="ri-price-tag-3-line"></i> Você economiza R$ <?= e(number_format($economia, 2, ',', '.')) ?> por parcela</p>
          <?php endif; ?>

          <a href="#matricula" class="btn btn-primary oferta__btn">Quero me matricular <i class="ri-arrow-right-line"></i></a>

          <ul class="oferta__lista">
            <li><i class="ri-check-line"></i> Sem taxa de matrícula</li>
            <li><i class="ri-check-line"></i> Material didático incluso</li>
            <li><i class="ri-check-line"></i> Tutoria durante todo o curso</li>
            <li><i class="ri-check-line"></i> Acesso imediato após a matrícula</li>
          </ul>
          <p class="oferta__nota">Condição válida para as matrículas desta turma. Consulte o consultor antes de fechar.</p>
        </div>
      </aside>
    </div>
  </section>

  <!-- ===================== POR QUE FAZER ===================== -->
  <section class="section">
    <div class="container">
      <div class="section-head">
        <span class="eyebrow">Por que fazer</span>
        <h2>O que muda na sua vida <span class="gradient-text">depois deste curso</span></h2>
        <p><?= e($conteudo['mercado']) ?></p>
      </div>

      <div class="curso-cols">
        <div class="curso-box">
          <h3><i class="ri-book-open-line"></i> O que você vai aprender</h3>
          <ul class="lista-check">
            <?php foreach ($conteudo['aprender'] as $item): ?>
              <li><i class="ri-check-line"></i> <?= e($item) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>

        <div class="curso-box">
          <h3><i class="ri-user-heart-line"></i> Este curso é para você se…</h3>
          <ul class="lista-check">
            <?php foreach ($conteudo['publico'] as $item): ?>
              <li><i class="ri-check-line"></i> <?= e($item) ?></li>
            <?php endforeach; ?>
          </ul>

          <h3 style="margin-top:26px"><i class="ri-briefcase-line"></i> Onde você pode atuar</h3>
          <ul class="lista-check">
            <?php foreach ($conteudo['saidas'] as $item): ?>
              <li><i class="ri-arrow-right-line"></i> <?= e($item) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- ===================== INCLUSO ===================== -->
  <section class="section section--soft">
    <div class="container">
      <div class="section-head">
        <span class="eyebrow">Está incluído</span>
        <h2>Tudo o que você recebe ao <span class="gradient-text">se matricular</span></h2>
      </div>
      <div class="inclui-grid">
        <div class="inclui"><i class="ri-smartphone-line"></i><h4>Estude pelo celular</h4><p>Plataforma leve, que abre no celular, no tablet ou no computador, a qualquer hora do dia.</p></div>
        <div class="inclui"><i class="ri-user-voice-line"></i><h4>Tutor de verdade</h4><p>Alguém para responder sua dúvida quando o conteúdo travar — do primeiro ao último módulo.</p></div>
        <div class="inclui"><i class="ri-award-line"></i><h4>Certificado</h4><p>Documento de conclusão com validade nacional, aceito por empresas e instituições de ensino.</p></div>
        <div class="inclui"><i class="ri-time-line"></i><h4>Seu ritmo</h4><p>Sem horário fixo de aula. Você acelera quando sobra tempo e desacelera quando a semana aperta.</p></div>
        <div class="inclui"><i class="ri-refresh-line"></i><h4>Provas refeitas</h4><p>Não passou de primeira? Refaz. Aqui o objetivo é você concluir, não reprovar.</p></div>
        <div class="inclui"><i class="ri-customer-service-2-line"></i><h4>Suporte na matrícula</h4><p>Um consultor acompanha você desde a documentação até o primeiro acesso à plataforma.</p></div>
      </div>
    </div>
  </section>

  <!-- ===================== PROVA SOCIAL ===================== -->
  <section class="section">
    <div class="container">
      <div class="curso-prova">
        <div>
          <span class="eyebrow">Quem já fez</span>
          <h2>Mais de <span class="gradient-text">12 mil alunos</span> já começaram aqui</h2>
          <p>Gente que trabalhava o dia inteiro, achava que não ia dar conta e hoje tem o certificado na mão. O curso foi desenhado exatamente para essa rotina apertada.</p>
          <div class="curso-prova__stats">
            <div><strong>+12 mil</strong><span>alunos matriculados</span></div>
            <div><strong>98%</strong><span>de satisfação</span></div>
            <div><strong>100%</strong><span>online e no seu ritmo</span></div>
          </div>
        </div>
        <div class="testi">
          <div class="stars"><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i><i class="ri-star-fill"></i></div>
          <p>“Achei que não ia conseguir estudar trabalhando. Fiz pelo celular, no intervalo e à noite, e consegui o certificado. Mudou a minha vida.”</p>
          <div class="who"><div class="av">MR</div><div><strong>Marcos R.</strong><span>Aluno EDUALFA</span></div></div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===================== MATRÍCULA ===================== -->
  <section class="section section--soft" id="matricula">
    <div class="container contact-grid">
      <div class="contact-info">
        <span class="eyebrow" style="display:inline-block;font-size:13px;font-weight:600;letter-spacing:2px;text-transform:uppercase;color:var(--blue-500);background:#fff;padding:6px 16px;border-radius:100px;margin-bottom:16px">Matrícula</span>
        <h2>Comece <span class="gradient-text">hoje</span> — a vaga é sua</h2>
        <p>Preencha os dados e um consultor entra em contato para confirmar a condição de <strong>R$ <?= e($curso['preco']) ?><?= $curso['parcelas'] ? ' em ' . (int) $curso['parcelas'] . 'x' : '' ?></strong> e concluir sua matrícula em <strong><?= e($curso['nome']) ?></strong>.</p>

        <div class="line"><div class="ic"><i class="ri-whatsapp-line"></i></div><div><strong>WhatsApp</strong><span>(00) 00000-0000</span></div></div>
        <div class="line"><div class="ic"><i class="ri-mail-line"></i></div><div><strong>E-mail</strong><span>contato@edualfa.com.br</span></div></div>
        <div class="line"><div class="ic"><i class="ri-map-pin-line"></i></div><div><strong>Atendimento</strong><span>Segunda a sexta, das 8h às 18h</span></div></div>
      </div>

      <form class="contact-form" id="form-matricula"
            data-curso="<?= e($curso['nome']) ?> (<?= e($curso['codigo']) ?>)">
        <div class="form-alert" id="form-alerta" hidden></div>

        <div class="field">
          <label>Nome completo</label>
          <input type="text" name="nome" placeholder="Seu nome" required>
        </div>
        <div class="field">
          <label>E-mail</label>
          <input type="email" name="email" placeholder="voce@email.com" required>
        </div>
        <div class="field">
          <label>WhatsApp</label>
          <input type="tel" name="telefone" placeholder="(00) 00000-0000" required>
        </div>
        <div class="field">
          <label>Curso escolhido</label>
          <input type="text" value="<?= e($curso['nome']) ?>" readonly>
        </div>
        <div class="field">
          <label>Mensagem (opcional)</label>
          <textarea name="mensagem" placeholder="Alguma dúvida antes de começar?"></textarea>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
          Quero me matricular <i class="ri-send-plane-line"></i>
        </button>
        <p class="form-nota">Seus dados são usados apenas para o contato da matrícula.</p>
      </form>
    </div>
  </section>

  <!-- ===================== FAQ ===================== -->
  <section class="section">
    <div class="container container--estreito">
      <div class="section-head">
        <span class="eyebrow">Dúvidas</span>
        <h2>Perguntas que <span class="gradient-text">todo mundo faz</span></h2>
      </div>
      <div class="faq">
        <details>
          <summary>O certificado tem validade?</summary>
          <p>Sim. Ao concluir o curso você recebe o certificado emitido por instituição parceira credenciada, com validade nacional — o mesmo aceito por empresas, instituições de ensino e órgãos públicos.</p>
        </details>
        <details>
          <summary>Preciso ir até algum lugar assistir aula?</summary>
          <p>Não. O conteúdo é <?= e($curso['modalidade']) ?>: você estuda de onde estiver, pelo celular ou computador, no horário que der. <?= $curso['categoria'] === 'tecnico' ? 'Nos cursos técnicos, apenas atividades práticas e estágio, quando exigidos, acontecem com apoio de polo.' : '' ?></p>
        </details>
        <details>
          <summary>Quanto tempo leva para concluir?</summary>
          <p>A duração prevista é de <?= e($curso['duracao']) ?>, mas o ritmo é seu: quem estuda mais horas por semana termina antes.</p>
        </details>
        <details>
          <summary>E se eu não conseguir acompanhar?</summary>
          <p>Você tem tutoria durante todo o curso e pode refazer as avaliações. O curso foi feito para quem trabalha e estuda no tempo que sobra — não para reprovar ninguém.</p>
        </details>
        <details>
          <summary>Como funciona o pagamento?</summary>
          <p><?= $curso['parcelas'] ? 'Você paga em ' . (int) $curso['parcelas'] . 'x de R$ ' . e($curso['preco']) . '.' : 'O consultor apresenta as formas de pagamento disponíveis.' ?> Não há taxa de matrícula, e o consultor confirma todas as condições com você antes de fechar.</p>
        </details>
        <details>
          <summary>Quando começo a estudar?</summary>
          <p>O acesso é liberado assim que a matrícula é confirmada. Não existe espera por início de turma.</p>
        </details>
      </div>
    </div>
  </section>

  <!-- ===================== RELACIONADOS ===================== -->
  <?php if ($relacionados): ?>
  <section class="section section--soft">
    <div class="container">
      <div class="section-head">
        <span class="eyebrow">Veja também</span>
        <h2>Outros cursos de <span class="gradient-text"><?= e($curso['categoriaLabel']) ?></span></h2>
      </div>
      <div class="course-grid">
        <?php foreach ($relacionados as $r): ?>
          <article class="course-card">
            <a class="course-card__link" href="curso.php?id=<?= e($r['id']) ?>">
              <div class="course-card__media" style="background: <?= e($r['cor']) ?>">
                <span class="emoji"><?= e($r['emoji']) ?></span>
                <span class="course-card__badge"><?= e($r['categoriaLabel']) ?></span>
                <?php if ($r['desconto']): ?><span class="course-card__off">-<?= (int) $r['desconto'] ?>%</span><?php endif; ?>
              </div>
              <div class="course-card__body">
                <h4><?= e($r['nome']) ?></h4>
                <p><?= e($r['descricao']) ?></p>
                <div class="course-card__meta">
                  <span><i class="ri-time-line"></i> <?= e($r['duracao']) ?></span>
                  <span><i class="ri-computer-line"></i> <?= e($r['modalidade']) ?></span>
                </div>
                <div class="course-card__foot">
                  <div class="course-card__price">
                    <small><s>R$ <?= e($r['precoDe']) ?></s></small>
                    <strong><em><?= (int) $r['parcelas'] ?>x</em> R$ <?= e($r['preco']) ?><span>/mês</span></strong>
                  </div>
                  <span class="btn btn-primary" style="padding:10px 18px;font-size:14px">Ver <i class="ri-arrow-right-line"></i></span>
                </div>
              </div>
            </a>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- ===================== CHAMADA FINAL ===================== -->
  <section class="curso-final">
    <div class="container">
      <h2>Daqui a <?= e($curso['duracao']) ?>, você pode estar com o certificado na mão</h2>
      <p>Ou exatamente onde está hoje. A diferença é uma decisão de dois minutos.</p>
      <a href="#matricula" class="btn btn-light">Quero me matricular agora <i class="ri-arrow-right-line"></i></a>
    </div>
  </section>

  <!-- ===================== FOOTER ===================== -->
  <footer class="footer">
    <div class="container">
      <div class="footer__grid">
        <div class="footer__brand">
          <img src="assets/img/edualfa-negativo.png" alt="EDUALFA">
          <p>Educação que transforma vidas. Supletivo EJA, cursos técnicos e cursos livres com certificação reconhecida e 100% online.</p>
          <div class="footer__social">
            <a href="#" aria-label="Instagram"><i class="ri-instagram-line"></i></a>
            <a href="#" aria-label="Facebook"><i class="ri-facebook-fill"></i></a>
            <a href="#" aria-label="WhatsApp"><i class="ri-whatsapp-line"></i></a>
            <a href="#" aria-label="YouTube"><i class="ri-youtube-fill"></i></a>
          </div>
        </div>
        <div>
          <h5>Modalidades</h5>
          <ul>
            <li><a href="index.php#cursos">Supletivo EJA</a></li>
            <li><a href="index.php#cursos">Curso Técnico</a></li>
            <li><a href="index.php#cursos">Curso Livre</a></li>
          </ul>
        </div>
        <div>
          <h5>Institucional</h5>
          <ul>
            <li><a href="index.php#categorias">Sobre nós</a></li>
            <li><a href="index.php#diferenciais">Diferenciais</a></li>
            <li><a href="index.php#contato">Contato</a></li>
          </ul>
        </div>
        <div>
          <h5>Atendimento</h5>
          <ul>
            <li><a href="#matricula">Matrícula</a></li>
            <li><a href="index.php#contato">Fale conosco</a></li>
            <li><a href="<?= e($whatsapp) ?>" target="_blank" rel="noopener">WhatsApp</a></li>
          </ul>
        </div>
      </div>
      <div class="footer__bottom">
        <span>© <?= $ano ?> EDUALFA · Todos os direitos reservados.</span>
        <span>Feito com <i class="ri-heart-fill" style="color:#ff5a5a"></i> para transformar vidas.</span>
      </div>
    </div>
  </footer>

  <a href="<?= e($whatsapp) ?>" class="whatsapp-float" target="_blank" rel="noopener" aria-label="WhatsApp">
    <i class="ri-whatsapp-line"></i>
  </a>

  <!-- Barra fixa de matrícula (mobile) -->
  <div class="barra-matricula">
    <div>
      <small><?= $curso['parcelas'] ? (int) $curso['parcelas'] . 'x de' : 'A partir de' ?></small>
      <strong>R$ <?= e($curso['preco']) ?></strong>
    </div>
    <a href="#matricula" class="btn btn-primary">Matricular <i class="ri-arrow-right-line"></i></a>
  </div>

<script src="assets/js/curso.js"></script>
</body>
</html>
