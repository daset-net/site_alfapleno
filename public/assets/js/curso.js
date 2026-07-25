/* curso.js — comportamento da página do curso (sem Vue: a página é renderizada no PHP).
   O formulário de matrícula fica em matricula.js. */
(function () {
  'use strict';

  // Header ganha sombra ao rolar
  var header = document.getElementById('header');
  var onScroll = function () {
    if (header) header.classList.toggle('scrolled', window.scrollY > 40);
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
})();
