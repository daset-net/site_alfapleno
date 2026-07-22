<?php
// api/cursos.php — retorna o catálogo de cursos em JSON
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: public, max-age=300');

$AZUL   = 'linear-gradient(140deg,#0f2f6b,#1e56d6)';
$CIANO  = 'linear-gradient(140deg,#1747b8,#22c9ec)';
$NAVY   = 'linear-gradient(140deg,#061a3a,#1747b8)';

$cursos = [
  // ---------------- Supletivo EJA ----------------
  [
    'id' => 1, 'categoria' => 'eja', 'categoriaLabel' => 'Supletivo EJA',
    'nome' => 'EJA · Ensino Fundamental', 'emoji' => '📘',
    'descricao' => 'Conclua o ensino fundamental de forma rápida e com certificação reconhecida pelo MEC.',
    'duracao' => '3 a 6 meses', 'modalidade' => 'EAD', 'preco' => '79', 'cor' => $AZUL
  ],
  [
    'id' => 2, 'categoria' => 'eja', 'categoriaLabel' => 'Supletivo EJA',
    'nome' => 'EJA · Ensino Médio', 'emoji' => '🎓',
    'descricao' => 'Termine o ensino médio no seu ritmo e conquiste o certificado para faculdade ou concursos.',
    'duracao' => '4 a 8 meses', 'modalidade' => 'EAD', 'preco' => '99', 'cor' => $CIANO
  ],
  [
    'id' => 3, 'categoria' => 'eja', 'categoriaLabel' => 'Supletivo EJA',
    'nome' => 'EJA · Fund. + Médio', 'emoji' => '📚',
    'descricao' => 'Pacote completo para quem quer concluir toda a educação básica de uma só vez.',
    'duracao' => '8 a 12 meses', 'modalidade' => 'EAD', 'preco' => '129', 'cor' => $NAVY
  ],

  // ---------------- Curso Técnico ----------------
  [
    'id' => 4, 'categoria' => 'tecnico', 'categoriaLabel' => 'Curso Técnico',
    'nome' => 'Técnico em Enfermagem', 'emoji' => '🩺',
    'descricao' => 'Forme-se em uma das profissões que mais empregam no país, com estágio supervisionado.',
    'duracao' => '18 meses', 'modalidade' => 'Semipresencial', 'preco' => '189', 'cor' => $CIANO
  ],
  [
    'id' => 5, 'categoria' => 'tecnico', 'categoriaLabel' => 'Curso Técnico',
    'nome' => 'Técnico em Administração', 'emoji' => '💼',
    'descricao' => 'Domine gestão, finanças e rotinas administrativas exigidas pelo mercado.',
    'duracao' => '12 meses', 'modalidade' => 'EAD', 'preco' => '149', 'cor' => $AZUL
  ],
  [
    'id' => 6, 'categoria' => 'tecnico', 'categoriaLabel' => 'Curso Técnico',
    'nome' => 'Técnico em Informática', 'emoji' => '💻',
    'descricao' => 'Aprenda desenvolvimento, redes e suporte para atuar na área de tecnologia.',
    'duracao' => '12 meses', 'modalidade' => 'EAD', 'preco' => '159', 'cor' => $NAVY
  ],
  [
    'id' => 7, 'categoria' => 'tecnico', 'categoriaLabel' => 'Curso Técnico',
    'nome' => 'Técnico em Segurança do Trabalho', 'emoji' => '🦺',
    'descricao' => 'Torne-se especialista em prevenção de acidentes e normas regulamentadoras.',
    'duracao' => '18 meses', 'modalidade' => 'Semipresencial', 'preco' => '179', 'cor' => $AZUL
  ],

  // ---------------- Curso Livre ----------------
  [
    'id' => 8, 'categoria' => 'livre', 'categoriaLabel' => 'Curso Livre',
    'nome' => 'Marketing Digital', 'emoji' => '📈',
    'descricao' => 'Aprenda tráfego pago, redes sociais e vendas online com certificado imediato.',
    'duracao' => '40 horas', 'modalidade' => 'Online', 'preco' => '49', 'cor' => $CIANO
  ],
  [
    'id' => 9, 'categoria' => 'livre', 'categoriaLabel' => 'Curso Livre',
    'nome' => 'Excel do Básico ao Avançado', 'emoji' => '📊',
    'descricao' => 'Planilhas, fórmulas e dashboards para se destacar em qualquer empresa.',
    'duracao' => '30 horas', 'modalidade' => 'Online', 'preco' => '39', 'cor' => $AZUL
  ],
  [
    'id' => 10, 'categoria' => 'livre', 'categoriaLabel' => 'Curso Livre',
    'nome' => 'Design Gráfico', 'emoji' => '🎨',
    'descricao' => 'Crie artes profissionais para redes sociais e materiais impressos.',
    'duracao' => '35 horas', 'modalidade' => 'Online', 'preco' => '49', 'cor' => $NAVY
  ],
  [
    'id' => 11, 'categoria' => 'livre', 'categoriaLabel' => 'Curso Livre',
    'nome' => 'Informática Essencial', 'emoji' => '🖥️',
    'descricao' => 'Windows, Word, internet e ferramentas do dia a dia para o mercado de trabalho.',
    'duracao' => '25 horas', 'modalidade' => 'Online', 'preco' => '35', 'cor' => $CIANO
  ],
];

echo json_encode(['ok' => true, 'total' => count($cursos), 'cursos' => $cursos], JSON_UNESCAPED_UNICODE);
