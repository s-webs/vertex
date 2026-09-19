const menuButton = document.querySelector('.mobile-toggle');
if (menuButton) {
  const drawer = document.createElement('dialog');
  drawer.id = 'mobile-menu';
  drawer.className = 'mobile-drawer';
  drawer.setAttribute('aria-label', 'Меню сайта');
  drawer.innerHTML = '<div class="drawer-header"><span>VERTEX <small>ELEVATOR</small></span><button type="button" class="drawer-close" aria-label="Закрыть меню" autofocus><i class="ph ph-x" aria-hidden="true"></i></button></div><nav class="drawer-links" aria-label="Мобильная навигация"></nav><div class="drawer-footer">Высокие стандарты.<br>Плавное движение.</div>';
  document.querySelectorAll('.nav a').forEach(link => drawer.querySelector('nav').append(link.cloneNode(true)));
  document.body.append(drawer);
  menuButton.setAttribute('aria-controls', drawer.id);
  menuButton.setAttribute('aria-haspopup', 'dialog');
  const closeMenu = () => drawer.close();
  menuButton.addEventListener('click', () => {
    drawer.showModal();
    document.body.classList.add('menu-open');
    menuButton.setAttribute('aria-expanded', 'true');
  });
  drawer.querySelector('.drawer-close').addEventListener('click', closeMenu);
  drawer.querySelectorAll('a').forEach(link => link.addEventListener('click', closeMenu));
  drawer.addEventListener('click', event => {
    const bounds = drawer.getBoundingClientRect();
    if (event.target === drawer && (event.clientX < bounds.left || event.clientX > bounds.right || event.clientY < bounds.top || event.clientY > bounds.bottom)) closeMenu();
  });
  drawer.addEventListener('close', () => {
    document.body.classList.remove('menu-open');
    menuButton.setAttribute('aria-expanded', 'false');
  });
  window.matchMedia('(max-width: 700px)').addEventListener('change', event => {
    if (!event.matches && drawer.open) closeMenu();
  });
}
const modal = document.querySelector('#detail-modal');
document.querySelectorAll('[data-detail]').forEach(button => button.addEventListener('click', () => {
  modal.querySelector('h2').textContent = button.dataset.detail;
  modal.querySelector('.detail-text').textContent = button.dataset.description;
  modal.showModal();
}));
modal?.querySelector('.modal-close').addEventListener('click', () => modal.close());
modal?.addEventListener('click', event => { if (event.target === modal) { const r = modal.getBoundingClientRect(); if(event.clientX < r.left || event.clientX > r.right || event.clientY < r.top || event.clientY > r.bottom) modal.close(); } });
document.querySelectorAll('[data-filter]').forEach(button => button.addEventListener('click', () => {
  document.querySelectorAll('[data-filter]').forEach(item => { item.classList.toggle('active', item === button); item.setAttribute('aria-pressed', String(item === button)); });
  let count = 0;
  document.querySelectorAll('[data-category]').forEach(card => { const show = button.dataset.filter === 'all' || card.dataset.category === button.dataset.filter; card.classList.toggle('hidden', !show); if(show) count++; });
  document.querySelector('#catalog-count').textContent = `Показано решений: ${count}`;
}));
document.querySelector('#contact-form')?.addEventListener('submit', event => {
  event.preventDefault();
  document.querySelector('#form-status').textContent = 'Форма заполнена корректно. Это демонстрационная версия: заявка не отправлена. Отправка станет доступна после подключения сервера.';
});
