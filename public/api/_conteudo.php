<?php
// api/_conteudo.php — texto de vendas da página de cada curso.
//
// O Directus guarda preço, parcelas e desconto; o conteúdo editorial (argumento
// de matrícula, grade, público e saídas profissionais) fica curado aqui.
// Cursos sem entrada própria caem no texto padrão da sua modalidade, e os combos
// "EJA + Técnico" (CE002CT00x) são montados a partir das duas partes.

$CONTEUDO = [

  // ------------------------------------------------------------------ EJA
  'CE001' => [
    'chamada'  => 'Termine o fundamental e o médio sem repetir anos perdidos',
    'promessa' => 'A vida atropelou os estudos — mas ela não precisa cobrar isso de você para sempre. Em um único programa você conclui as duas etapas da educação básica, estudando de casa, pelo celular, no horário que sobrar do seu dia. Sem sala de aula, sem constrangimento, sem começar do zero.',
    'aprender' => [
      'Português, matemática, ciências, história e geografia em linguagem direta, feita para adulto',
      'Conteúdo dividido em módulos curtos, que cabem em 30 ou 40 minutos por dia',
      'Provas online, refeitas quantas vezes for preciso até você passar',
      'Tutor disponível para tirar dúvidas durante todo o curso',
      'Revisão focada só no que cai na avaliação — nada de encher linguiça',
      'Certificado de conclusão do fundamental e do médio, com validade nacional',
    ],
    'publico' => [
      'Quem parou de estudar cedo e nunca conseguiu voltar',
      'Quem precisa do certificado para conseguir emprego ou promoção',
      'Quem tem filhos, trabalho e não consegue frequentar escola presencial',
      'Quem quer prestar concurso ou entrar na faculdade',
    ],
    'saidas' => [
      'Certificado de conclusão do ensino fundamental e médio',
      'Acesso a vagas que exigem escolaridade completa',
      'Porta de entrada para faculdade, técnico e concursos',
    ],
    'mercado' => 'Mais de 60 milhões de brasileiros adultos não concluíram a educação básica — e é exatamente esse papel que separa muita gente de uma vaga melhor. Quem termina os estudos costuma sentir a diferença já na primeira seleção de que participa.',
  ],

  'CE002' => [
    'chamada'  => 'Conclua o ensino médio e destrave a sua próxima fase',
    'promessa' => 'O certificado do ensino médio é a chave que abre faculdade, concurso público, curso técnico e a maior parte das vagas com carteira assinada. Aqui você conclui essa etapa 100% online, no seu ritmo, sem precisar largar o trabalho nem enfrentar sala de aula.',
    'aprender' => [
      'Todas as disciplinas do ensino médio em módulos objetivos e sem enrolação',
      'Material digital acessível pelo celular, tablet ou computador, 24 horas por dia',
      'Simulados e provas online com correção imediata',
      'Tutoria para tirar dúvidas e não travar em nenhuma matéria',
      'Cronograma flexível: você acelera quando dá e desacelera quando precisa',
      'Certificado de conclusão do ensino médio com validade nacional',
    ],
    'publico' => [
      'Quem parou no meio do ensino médio e quer fechar essa conta',
      'Quem precisa do certificado para uma vaga, promoção ou concurso',
      'Quem quer entrar na faculdade e só falta esse documento',
      'Quem tenta há anos e nunca teve tempo de ir à escola',
    ],
    'saidas' => [
      'Certificado de conclusão do ensino médio',
      'Ingresso em faculdade, curso técnico e concursos públicos',
      'Qualificação para vagas que exigem médio completo',
    ],
    'mercado' => 'Ensino médio completo é o requisito mínimo na maioria dos anúncios de emprego formais do país. Sem ele, o currículo nem chega ao entrevistador — com ele, você entra no jogo.',
  ],

  'CE003' => [
    'chamada'  => 'Falta só o 3º ano? Conclua apenas o que ficou para trás',
    'promessa' => 'Não faz sentido refazer o que você já venceu. Se o que ficou pendente foi o último ano do ensino médio, você conclui só essa etapa — mais rápido, mais barato e com o mesmo certificado de quem cursou tudo.',
    'aprender' => [
      'Conteúdo completo do 3º ano do ensino médio, direto ao ponto',
      'Estudo por módulos curtos, feitos para caber na rotina de quem trabalha',
      'Provas online refeitas quantas vezes for necessário',
      'Tutoria para dúvidas ao longo de todo o percurso',
      'Ritmo acelerado: dá para concluir em poucos meses',
      'Certificado de conclusão do ensino médio com validade nacional',
    ],
    'publico' => [
      'Quem cursou até o 2º ano e parou',
      'Quem precisa fechar o médio com urgência para uma vaga ou matrícula',
      'Quem quer o caminho mais curto e barato até o certificado',
    ],
    'saidas' => [
      'Certificado de conclusão do ensino médio',
      'Liberação para faculdade, técnico e concursos',
    ],
    'mercado' => 'É o famoso "faltou pouco". Muita gente carrega esse pouco por dez anos — e resolve em alguns meses quando finalmente começa.',
  ],

  // -------------------------------------------------------------- TÉCNICOS
  'CT003' => [
    'chamada'  => 'Entre para a saúde por uma porta que emprega o ano todo',
    'promessa' => 'Todo consultório odontológico precisa de alguém que domine biossegurança, instrumental e atendimento ao paciente. O técnico em saúde bucal é essa pessoa — e é uma das funções da saúde com contratação mais constante, dentro e fora do SUS.',
    'aprender' => [
      'Anatomia bucal, dentística e as principais especialidades odontológicas',
      'Biossegurança, esterilização e controle de infecção na prática',
      'Instrumentação e trabalho a quatro mãos com o cirurgião-dentista',
      'Radiologia odontológica e revelação de imagens',
      'Prevenção, profilaxia e educação em saúde bucal',
      'Atendimento, acolhimento e gestão do consultório',
    ],
    'publico' => [
      'Quem quer entrar na área da saúde com formação rápida',
      'Quem já é auxiliar e quer subir para técnico',
      'Quem busca estabilidade em clínicas, convênios ou no serviço público',
    ],
    'saidas' => [
      'Técnico em saúde bucal em clínicas e consultórios',
      'Equipes de Saúde da Família e unidades básicas do SUS',
      'Concursos municipais e estaduais na área da saúde',
    ],
    'mercado' => 'O Brasil tem consultório odontológico em praticamente toda cidade, e a rotina de biossegurança ficou mais exigente nos últimos anos — o que valorizou justamente quem tem formação técnica na função.',
  ],

  'CT004' => [
    'chamada'  => 'Duas formações em uma: mecânica e elétrica industrial',
    'promessa' => 'Quando uma máquina para, a indústria perde dinheiro por minuto. Por isso o eletromecânico — que entende do motor e do circuito — é um dos perfis mais bem pagos do chão de fábrica e um dos primeiros a ser efetivado.',
    'aprender' => [
      'Eletricidade industrial, comandos elétricos e leitura de diagramas',
      'Mecânica: elementos de máquina, rolamentos, transmissão e alinhamento',
      'Manutenção preventiva, preditiva e corretiva',
      'Pneumática, hidráulica e automação com CLP',
      'Metrologia, desenho técnico e interpretação de projetos',
      'Segurança do trabalho aplicada à indústria (NR-10 e NR-12)',
    ],
    'publico' => [
      'Quem já trabalha na produção e quer virar manutenção',
      'Quem gosta de resolver problema com a mão e com a cabeça',
      'Quem quer uma profissão que a indústria contrata em qualquer região',
    ],
    'saidas' => [
      'Técnico de manutenção eletromecânica na indústria',
      'Montagem e instalação de máquinas e equipamentos',
      'Manutenção predial e de sistemas industriais',
    ],
    'mercado' => 'Manutenção industrial é uma das áreas que mais reclamam da falta de mão de obra qualificada no país — quem se forma costuma ter mais de uma proposta na mesa.',
  ],

  'CT005' => [
    'chamada'  => 'A formação que abre porta em empresa de qualquer setor',
    'promessa' => 'Comércio, indústria, escritório, hospital, escola: todos precisam de alguém que organize processos, controle números e faça a máquina girar. O técnico em administração é a formação mais versátil que existe — e a que mais dá margem para crescer dentro da empresa.',
    'aprender' => [
      'Rotinas administrativas, organização de processos e documentos',
      'Departamento pessoal: admissão, folha, férias e rescisão',
      'Finanças: fluxo de caixa, contas a pagar e receber, custos e formação de preço',
      'Gestão de pessoas, liderança e comunicação profissional',
      'Compras, estoque e relacionamento com fornecedores',
      'Marketing, vendas e atendimento ao cliente',
    ],
    'publico' => [
      'Quem quer entrar no mercado de escritório e não sabe por onde começar',
      'Quem já trabalha na empresa e quer ser promovido',
      'Quem pretende abrir o próprio negócio e precisa entender de gestão',
    ],
    'saidas' => [
      'Assistente e técnico administrativo',
      'Departamento pessoal, financeiro, compras e logística',
      'Concursos públicos de nível técnico',
      'Gestão do próprio negócio',
    ],
    'mercado' => 'Administração é a área com o maior número de vagas abertas no Brasil, ano após ano. É também a formação que menos limita você: serve para praticamente qualquer empresa do país.',
  ],

  'CT006' => [
    'chamada'  => 'A profissão que a lei obriga a empresa a contratar',
    'promessa' => 'Não é força de expressão: a partir de certo porte e grau de risco, a legislação exige que a empresa tenha técnico em segurança do trabalho no quadro. É uma das poucas carreiras em que a demanda não depende do humor do mercado — depende da norma.',
    'aprender' => [
      'Normas regulamentadoras (NRs) e legislação de segurança e saúde',
      'Análise de risco, PPRA/PGR, PCMSO e mapas de risco',
      'Prevenção e investigação de acidentes de trabalho',
      'EPIs e EPCs: escolha, fiscalização e treinamento',
      'CIPA, SIPAT e programas de conscientização',
      'Prevenção e combate a incêndio e primeiros socorros',
    ],
    'publico' => [
      'Quem quer uma carreira estável, com registro e piso definido',
      'Quem já atua em obra ou indústria e quer mudar de função',
      'Quem gosta de organização, norma e trabalho com pessoas',
    ],
    'saidas' => [
      'Técnico de segurança do trabalho em indústrias e construtoras',
      'Consultoria e assessoria em SST para várias empresas',
      'Concursos públicos e órgãos fiscalizadores',
    ],
    'mercado' => 'Obrigatoriedade legal, multas pesadas e o custo de um acidente fazem da segurança do trabalho um investimento que a empresa não corta. Por isso a profissão emprega até em ano ruim.',
  ],

  'CT007' => [
    'chamada'  => 'Eletricidade: a habilidade que nunca fica sem serviço',
    'promessa' => 'Da instalação residencial ao painel industrial, passando pela energia solar, o técnico em eletrotécnica é quem assina, executa e mantém. É uma formação que dá emprego com carteira assinada — e também a liberdade de trabalhar por conta, cobrando por serviço.',
    'aprender' => [
      'Fundamentos de eletricidade, circuitos CC e CA',
      'Instalações elétricas residenciais, prediais e industriais',
      'Comandos elétricos, motores e quadros de distribuição',
      'Projeto elétrico, dimensionamento e leitura de diagramas',
      'Energia solar fotovoltaica e eficiência energética',
      'Segurança em eletricidade conforme a NR-10',
    ],
    'publico' => [
      'Quem já faz "bico" de elétrica e quer se formalizar e cobrar mais',
      'Quem quer trabalhar com energia solar, o setor que mais cresce',
      'Quem busca uma profissão prática, com registro e boa remuneração',
    ],
    'saidas' => [
      'Técnico eletrotécnico em indústria, comércio e construção',
      'Instalador e projetista de sistemas de energia solar',
      'Manutenção elétrica predial e industrial',
      'Prestador de serviço autônomo com serviço o ano todo',
    ],
    'mercado' => 'A expansão da energia solar e a modernização das instalações elétricas colocaram o eletrotécnico numa das listas mais procuradas do país — e o serviço autônomo paga bem desde o primeiro cliente.',
  ],

  'CT008' => [
    'chamada'  => 'Trabalhe na área que toda empresa precisa e poucas dominam',
    'promessa' => 'Licenciamento, resíduos, água, ESG: a pressão ambiental sobre as empresas só aumenta, e com ela a necessidade de gente que saiba transformar exigência legal em prática do dia a dia. O técnico em meio ambiente é essa ponte.',
    'aprender' => [
      'Legislação ambiental e processo de licenciamento',
      'Gestão de resíduos sólidos, efluentes e emissões',
      'Sistemas de gestão ambiental e a norma ISO 14001',
      'Educação ambiental e programas internos de conscientização',
      'Monitoramento, indicadores e relatórios ambientais',
      'Saneamento, recursos hídricos e recuperação de áreas',
    ],
    'publico' => [
      'Quem quer uma profissão em uma área em crescimento estrutural',
      'Quem se interessa por sustentabilidade e quer viver disso',
      'Quem busca vagas em indústrias, consultorias e no setor público',
    ],
    'saidas' => [
      'Técnico ambiental em indústrias e consultorias',
      'Gestão de resíduos e licenciamento',
      'Órgãos ambientais, prefeituras e concursos',
    ],
    'mercado' => 'Cada vez mais contratos, financiamentos e licenças dependem de conformidade ambiental. Empresa sem esse controle simplesmente não opera — e quem sabe fazer isso é contratado.',
  ],

  'CT009' => [
    'chamada'  => 'Saia do serviço braçal e passe a comandar a obra',
    'promessa' => 'Quem lê projeto, calcula material e cobra prazo ganha em outro patamar dentro do canteiro. O técnico em edificações é o profissional que acompanha a obra do alicerce à entrega — e o que a construtora promove primeiro.',
    'aprender' => [
      'Leitura e interpretação de projetos arquitetônicos e estruturais',
      'Materiais de construção, traços e controle tecnológico',
      'Topografia aplicada, locação de obra e fundações',
      'Orçamento, composição de custos e cronograma físico-financeiro',
      'Desenho técnico e AutoCAD aplicado à construção',
      'Instalações prediais, patologias e segurança na obra',
    ],
    'publico' => [
      'Quem já trabalha na construção e quer crescer de cargo',
      'Quem quer atuar com reforma e construção por conta própria',
      'Quem pretende cursar engenharia ou arquitetura depois',
    ],
    'saidas' => [
      'Técnico em edificações e auxiliar de engenharia',
      'Mestre de obras, orçamentista e fiscal de obra',
      'Empreiteira própria de reformas e construções',
    ],
    'mercado' => 'A construção civil é cíclica, mas nunca para: reforma, retrofit e obra pública seguram a demanda mesmo nos anos mais fracos — e sempre falta quem saiba ler projeto direito.',
  ],

  'CT010' => [
    'chamada'  => 'Entre para a tecnologia pela porta que realmente contrata',
    'promessa' => 'Nem todo mundo precisa virar programador de startup para viver de tecnologia. Suporte, redes, infraestrutura e desenvolvimento formam a base que toda empresa informatizada precisa — e é exatamente essa base que o curso entrega.',
    'aprender' => [
      'Hardware, montagem e manutenção de computadores',
      'Sistemas operacionais, instalação e administração',
      'Redes de computadores, cabeamento e configuração',
      'Lógica de programação e desenvolvimento de aplicações',
      'Banco de dados e SQL na prática',
      'Suporte técnico, help desk e segurança da informação',
    ],
    'publico' => [
      'Quem quer migrar para a área de TI sem passar quatro anos na faculdade',
      'Quem já mexe com computador e quer transformar isso em profissão',
      'Quem busca uma carreira com trabalho remoto e salário acima da média',
    ],
    'saidas' => [
      'Suporte técnico e analista de help desk',
      'Técnico de redes e infraestrutura',
      'Desenvolvimento júnior e manutenção de sistemas',
      'Assistência técnica própria',
    ],
    'mercado' => 'A demanda por profissionais de tecnologia no Brasil segue maior do que o número de gente formada — e o suporte técnico continua sendo a porta de entrada mais concreta para quem está começando.',
  ],

  'CT011' => [
    'chamada'  => 'Transforme cuidado com a beleza em uma profissão de verdade',
    'promessa' => 'O mercado de estética não para de crescer, mas o cliente ficou exigente: quer protocolo, higiene e resultado. Formação técnica é o que separa quem cobra pouco e vive de indicação de quem monta agenda cheia e preço próprio.',
    'aprender' => [
      'Anatomia e fisiologia da pele, do corpo e dos cabelos',
      'Estética facial: limpeza, peelings, protocolos e cuidados',
      'Estética corporal: massagens, drenagem e tratamentos',
      'Equipamentos e cosmetologia aplicada',
      'Biossegurança e atendimento ao cliente',
      'Gestão do próprio espaço: preço, agenda e divulgação',
    ],
    'publico' => [
      'Quem já trabalha com beleza e quer se profissionalizar de verdade',
      'Quem quer abrir o próprio estúdio de estética',
      'Quem busca uma profissão com renda flexível e clientela recorrente',
    ],
    'saidas' => [
      'Técnico em estética em clínicas e spas',
      'Estúdio próprio de estética facial e corporal',
      'Atendimento domiciliar e parcerias com salões',
    ],
    'mercado' => 'O Brasil é um dos maiores mercados de beleza do mundo. É um setor em que a formação técnica se converte rápido em preço maior por atendimento.',
  ],

  // ----------------------------------------------------------- CURSOS LIVRES
  'CL001' => [
    'chamada'  => 'Pare de perder vaga por não saber usar o computador',
    'promessa' => 'Digitar um documento, montar uma planilha, salvar um arquivo na pasta certa, enviar um e-mail com anexo: parece pouco, mas é o que muita seleção usa como corte. Em poucas semanas você deixa de travar nessas tarefas — e passa a colocar isso no currículo.',
    'aprender' => [
      'Windows: arquivos, pastas, instalação de programas e organização',
      'Word: documentos, currículo, formatação e impressão',
      'Excel: planilhas, fórmulas essenciais e controle de dados',
      'PowerPoint: apresentações apresentáveis, sem exagero',
      'Internet, e-mail profissional, nuvem e segurança básica',
      'Rotinas do dia a dia de escritório no computador',
    ],
    'publico' => [
      'Quem nunca teve intimidade com computador e sente vergonha disso',
      'Quem vai concorrer a vaga de escritório, comércio ou atendimento',
      'Quem quer ajudar os filhos e resolver a própria vida online',
    ],
    'saidas' => [
      'Requisito atendido para vagas administrativas e de atendimento',
      'Base para cursos técnicos e para a área de tecnologia',
      'Autonomia total no computador e no celular',
    ],
    'mercado' => 'Informática básica aparece como exigência em quase todo anúncio de emprego formal. É o curso de menor esforço com maior efeito imediato no currículo.',
  ],

  'CL003' => [
    'chamada'  => 'Aprenda a criar arte e comece a receber por isso',
    'promessa' => 'Todo comércio, salão, igreja, loja e político precisa de post, logo, cardápio e banner — toda semana. Design gráfico é uma das poucas habilidades que você consegue transformar em renda extra antes mesmo de terminar o curso.',
    'aprender' => [
      'Fundamentos: composição, hierarquia, cor e tipografia',
      'Criação de logotipos e identidade visual',
      'Artes para redes sociais: feed, stories e anúncios',
      'Materiais impressos: cartão, panfleto, cardápio e banner',
      'Tratamento de imagem e montagem',
      'Como precificar, fechar e entregar um trabalho de freelancer',
    ],
    'publico' => [
      'Quem quer uma renda extra fazendo trabalho criativo',
      'Quem cuida das redes sociais do próprio negócio',
      'Quem quer entrar na área de comunicação e marketing',
    ],
    'saidas' => [
      'Designer freelancer para comércios locais',
      'Social media e criação de conteúdo',
      'Setor de marketing de empresas e agências',
    ],
    'mercado' => 'O comércio local vive de aparecer na internet e quase nunca tem designer fixo. É um mercado de clientes pequenos e recorrentes — o tipo que sustenta um freelancer o ano inteiro.',
  ],

  'CL006' => [
    'chamada'  => 'Trabalhe em consultório odontológico em poucos meses',
    'promessa' => 'É uma das entradas mais rápidas na área da saúde: formação curta, função clara e vaga em praticamente toda cidade, já que consultório de dentista existe em cada esquina e não funciona sem auxiliar.',
    'aprender' => [
      'Anatomia bucal e noções das especialidades odontológicas',
      'Biossegurança, esterilização e controle de infecção',
      'Instrumental: nomes, uso e preparo para cada procedimento',
      'Auxílio ao dentista durante o atendimento',
      'Recepção, agendamento e organização do consultório',
      'Orientação de higiene e prevenção ao paciente',
    ],
    'publico' => [
      'Quem quer entrar na saúde sem uma formação longa',
      'Quem já faz recepção em clínica e quer subir de função',
      'Quem busca emprego estável e com carteira assinada',
    ],
    'saidas' => [
      'Auxiliar em saúde bucal em consultórios e clínicas',
      'Equipes de odontologia da rede pública',
      'Base para depois virar técnico em saúde bucal',
    ],
    'mercado' => 'A odontologia é um dos setores da saúde com mais estabelecimentos abertos no país, e a rotina de biossegurança tornou o auxiliar treinado indispensável no atendimento.',
  ],

  'CL011' => [
    'chamada'  => 'A porta de entrada mais rápida para o trabalho de escritório',
    'promessa' => 'Auxiliar administrativo é a vaga que mais aparece nos sites de emprego — e uma das poucas que aceita quem está começando. O que separa você dela é saber a rotina: documento, planilha, atendimento e organização. É exatamente isso que o curso entrega.',
    'aprender' => [
      'Rotinas de escritório: documentos, arquivos, protocolos e agenda',
      'Atendimento ao cliente por telefone, e-mail e presencial',
      'Noções de departamento pessoal e financeiro',
      'Controle de estoque, compras e conferência de notas',
      'Planilhas e organização de informação',
      'Postura, comunicação e ética profissional',
    ],
    'publico' => [
      'Quem quer o primeiro emprego de carteira assinada',
      'Quem está voltando ao mercado depois de um tempo parado',
      'Quem trabalha no operacional e quer migrar para o escritório',
    ],
    'saidas' => [
      'Auxiliar e assistente administrativo',
      'Recepção, atendimento e apoio a departamentos',
      'Base para crescer até o técnico em administração',
    ],
    'mercado' => 'Auxiliar administrativo está entre as funções com maior volume de contratações do Brasil, presente em empresa de qualquer tamanho e setor.',
  ],

  'CL017' => [
    'chamada'  => 'Números organizados: a habilidade que empresa nenhuma dispensa',
    'promessa' => 'Escritório de contabilidade vive de prazo e não pode errar. Por isso valoriza quem chega sabendo lançar, conferir e organizar documento fiscal. É uma função técnica, de rotina previsível e com demanda o ano inteiro — inclusive em época de fechamento e declaração.',
    'aprender' => [
      'Fundamentos de contabilidade e o plano de contas',
      'Lançamentos, escrituração e conciliação',
      'Documentos fiscais: notas, guias e obrigações acessórias',
      'Noções de tributos: Simples Nacional, ICMS, ISS e retenções',
      'Rotinas de departamento pessoal e folha de pagamento',
      'Organização de prazos, arquivos e atendimento ao cliente do escritório',
    ],
    'publico' => [
      'Quem quer trabalhar em escritório de contabilidade',
      'Quem cuida do financeiro de uma empresa e precisa entender o contábil',
      'Quem tem o próprio negócio e quer parar de depender de terceiros para tudo',
    ],
    'saidas' => [
      'Auxiliar contábil e fiscal em escritórios',
      'Departamento financeiro e fiscal de empresas',
      'Serviços contábeis de apoio como autônomo',
    ],
    'mercado' => 'Toda empresa aberta no país precisa de contabilidade por obrigação legal. É uma demanda que não depende de moda nem de temporada — depende de existir empresa.',
  ],
];

// Texto padrão por modalidade, para cursos ainda sem conteúdo próprio.
$CONTEUDO_PADRAO = [
  'eja' => [
    'chamada'  => 'Conclua seus estudos e destrave a sua próxima fase',
    'promessa' => 'Estude 100% online, no seu ritmo, e conquiste o certificado que falta para você concorrer às vagas, ao concurso ou à faculdade que quiser.',
    'aprender' => [
      'Conteúdo em módulos curtos, em linguagem feita para adulto',
      'Material acessível pelo celular a qualquer hora',
      'Provas online com quantas tentativas você precisar',
      'Tutoria para tirar dúvidas do começo ao fim',
    ],
    'publico' => ['Quem parou de estudar e quer retomar', 'Quem precisa do certificado para trabalhar ou estudar'],
    'saidas'  => ['Certificado com validade nacional', 'Acesso a faculdade, técnico e concursos'],
    'mercado' => 'Escolaridade completa é o primeiro filtro da maioria das seleções de emprego do país.',
  ],
  'tecnico' => [
    'chamada'  => 'Uma profissão de verdade, sem passar anos na faculdade',
    'promessa' => 'Formação técnica é o caminho mais curto entre onde você está e uma carteira assinada melhor — com conteúdo prático e certificado reconhecido.',
    'aprender' => [
      'Conteúdo técnico aplicado ao dia a dia da profissão',
      'Material online completo, disponível 24 horas',
      'Avaliações por módulo com correção imediata',
      'Tutoria especializada durante todo o curso',
    ],
    'publico' => ['Quem quer mudar de área', 'Quem quer crescer na empresa onde já está'],
    'saidas'  => ['Vagas técnicas no mercado formal', 'Concursos de nível técnico', 'Trabalho autônomo na área'],
    'mercado' => 'Falta técnico qualificado em praticamente todos os setores produtivos do Brasil.',
  ],
  'livre' => [
    'chamada'  => 'Aprenda rápido, aplique amanhã e melhore o seu currículo',
    'promessa' => 'Curso livre é objetivo: conteúdo direto, certificado na conclusão e uma habilidade nova que você já leva para a próxima entrevista ou para o próprio negócio.',
    'aprender' => [
      'Conteúdo prático, sem teoria desnecessária',
      'Aulas curtas que cabem na sua rotina',
      'Acesso pelo celular, tablet ou computador',
      'Certificado de conclusão ao final',
    ],
    'publico' => ['Quem quer se qualificar rápido', 'Quem busca renda extra ou recolocação'],
    'saidas'  => ['Novas vagas e funções', 'Renda extra como autônomo', 'Diferencial no currículo'],
    'mercado' => 'Qualificação curta é a forma mais rápida de sair da lista de descartados numa seleção.',
  ],
];

/**
 * Conteúdo editorial de um curso do catálogo.
 * Combos "EJA + Técnico" (CE002CT00x) são montados a partir das duas partes.
 */
function conteudoCurso(array $curso): array {
  global $CONTEUDO, $CONTEUDO_PADRAO;

  $id = $curso['id'];
  if (isset($CONTEUDO[$id])) return $CONTEUDO[$id];

  // CE002CT004 → base EJA (CE002) + parte técnica (CT004).
  if (preg_match('/^(CE\d{3})(CT\d{3})$/', $id, $m)) {
    $eja     = $CONTEUDO[$m[1]] ?? $CONTEUDO_PADRAO['eja'];
    $tecnico = $CONTEUDO[$m[2]] ?? $CONTEUDO_PADRAO['tecnico'];
    return [
      'chamada'  => 'Ensino médio e uma profissão técnica de uma vez só',
      'promessa' => 'Em vez de terminar o médio e só depois pensar em profissão, você faz as duas coisas ao mesmo tempo, no mesmo curso e por um valor muito menor do que pagaria separadamente. Termina com dois certificados e um currículo que compete de igual para igual. ' . $tecnico['promessa'],
      'aprender' => array_merge(
        ['Todas as disciplinas do ensino médio em módulos objetivos'],
        array_slice($tecnico['aprender'], 0, 5)
      ),
      'publico'  => array_merge(
        ['Quem precisa concluir o médio e não quer perder mais tempo até ter uma profissão'],
        array_slice($tecnico['publico'], 0, 2)
      ),
      'saidas'   => array_merge(['Certificado de conclusão do ensino médio'], $tecnico['saidas']),
      'mercado'  => $tecnico['mercado'],
    ];
  }

  return $CONTEUDO_PADRAO[$curso['categoria']] ?? $CONTEUDO_PADRAO['livre'];
}
