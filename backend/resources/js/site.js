import { bindPhoneMasks } from './phone-mask';

bindPhoneMasks();

const menuButton = document.querySelector('.mobile-toggle');
if (menuButton) {
  const brand = document.body.dataset.brand || 'Adilet-lift';
  const drawer = document.createElement('dialog');
  drawer.id = 'mobile-menu';
  drawer.className = 'mobile-drawer';
  drawer.setAttribute('aria-label', 'Меню сайта');
  drawer.innerHTML = `<div class="drawer-header"><span>${brand}</span><button type="button" class="drawer-close" aria-label="Закрыть меню" autofocus><i class="ph ph-x" aria-hidden="true"></i></button></div><nav class="drawer-links" aria-label="Мобильная навигация"></nav><div class="drawer-footer">Высокие стандарты.<br>Плавное движение.</div>`;
  document.querySelectorAll('.nav a').forEach((link) => drawer.querySelector('nav').append(link.cloneNode(true)));
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
  drawer.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));
  drawer.addEventListener('click', (event) => {
    const bounds = drawer.getBoundingClientRect();
    if (event.target === drawer && (event.clientX < bounds.left || event.clientX > bounds.right || event.clientY < bounds.top || event.clientY > bounds.bottom)) {
      closeMenu();
    }
  });
  drawer.addEventListener('close', () => {
    document.body.classList.remove('menu-open');
    menuButton.setAttribute('aria-expanded', 'false');
  });
  window.matchMedia('(max-width: 700px)').addEventListener('change', (event) => {
    if (!event.matches && drawer.open) {
      closeMenu();
    }
  });
}

const modal = document.querySelector('#detail-modal');
document.querySelectorAll('[data-detail]').forEach((button) => button.addEventListener('click', () => {
  modal.querySelector('h2').textContent = button.dataset.detail;
  modal.querySelector('.detail-text').textContent = button.dataset.description;
  modal.showModal();
}));
modal?.querySelector('.modal-close')?.addEventListener('click', () => modal.close());
modal?.addEventListener('click', (event) => {
  if (event.target === modal) {
    const r = modal.getBoundingClientRect();
    if (event.clientX < r.left || event.clientX > r.right || event.clientY < r.top || event.clientY > r.bottom) {
      modal.close();
    }
  }
});

const galleryModal = document.querySelector('#gallery-modal');
document.querySelectorAll('[data-gallery-image]').forEach((button) => button.addEventListener('click', () => {
  const image = galleryModal.querySelector('img');
  image.src = button.dataset.src;
  image.alt = button.querySelector('img')?.alt || button.dataset.caption;
  galleryModal.querySelector('figcaption').textContent = button.dataset.caption;
  galleryModal.showModal();
}));
galleryModal?.querySelector('.gallery-modal-close')?.addEventListener('click', () => galleryModal.close());
galleryModal?.addEventListener('click', (event) => {
  if (event.target === galleryModal) {
    galleryModal.close();
  }
});
