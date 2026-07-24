/* curso.js — comportamento da página do curso (sem Vue: a página é renderizada no PHP) */
(function () {
  'use strict';

  // Header ganha sombra ao rolar
  var header = document.getElementById('header');
  var onScroll = function () {
    if (header) header.classList.toggle('scrolled', window.scrollY > 40);
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  // Envio da matrícula → api/contato.php
  var form = document.getElementById('form-matricula');
  var alerta = document.getElementById('form-alerta');
  if (!form) return;

  var mostrar = function (tipo, msg) {
    alerta.className = 'form-alert ' + tipo;
    alerta.textContent = msg;
    alerta.hidden = false;
    alerta.scrollIntoView({ behavior: 'smooth', block: 'center' });
  };

  form.addEventListener('submit', function (ev) {
    ev.preventDefault();
    var botao = form.querySelector('button[type=submit]');
    var textoOriginal = botao.innerHTML;
    botao.disabled = true;
    botao.textContent = 'Enviando…';

    var dados = {
      nome: form.nome.value,
      email: form.email.value,
      telefone: form.telefone.value,
      interesse: form.dataset.curso,
      mensagem: form.mensagem.value
    };

    fetch('api/contato.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(dados)
    })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (data.ok) {
          mostrar('ok', data.mensagem || 'Recebemos seus dados! Em breve entramos em contato.');
          form.reset();
        } else {
          mostrar('err', data.mensagem || 'Verifique os dados e tente novamente.');
        }
      })
      .catch(function () {
        mostrar('err', 'Não foi possível enviar agora. Tente novamente em instantes.');
      })
      .finally(function () {
        botao.disabled = false;
        botao.innerHTML = textoOriginal;
      });
  });
})();
