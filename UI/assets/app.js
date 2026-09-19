const menuButton = document.querySelector('.mobile-toggle');
document.querySelectorAll('a.placeholder-link').forEach(link => link.addEventListener('click', event => event.preventDefault()));
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

const workDetail = document.querySelector('#work-detail');
if (workDetail) {
  const workId = new URLSearchParams(window.location.search).get('id');
  const work = window.WORKS_DATA?.[workId];
  if (work) {
    document.title = `${work.title} — Adilet-lift`;
    const photosWord = work.photos.length % 10 === 1 && work.photos.length % 100 !== 11 ? 'фотография' : (work.photos.length % 10 >= 2 && work.photos.length % 10 <= 4 && (work.photos.length % 100 < 10 || work.photos.length % 100 >= 20) ? 'фотографии' : 'фотографий');
    workDetail.innerHTML = `<div class="page-head work-detail-head"><div class="breadcrumbs"><a href="index.html">Главная</a><span>/</span><a href="works.html">Наши работы</a><span>/</span><span>${work.title}</span></div><div class="eyebrow">${work.number}</div><h1>${work.title}</h1><p>${work.description}</p><div class="work-meta">${work.meta.map(item => `<span>${item}</span>`).join('')}<span>${work.photos.length} ${photosWord}</span></div></div><div class="work-detail-gallery">${work.photos.map((photo, index) => `<button data-gallery-image data-src="${photo.src}" data-caption="${photo.caption}" aria-label="Открыть фотографию ${index + 1}: ${photo.caption}"><img src="${photo.src}" alt="${photo.alt}" loading="lazy"><span>${String(index + 1).padStart(2, '0')} / ${photo.caption}</span></button>`).join('')}</div><a class="text-link work-back" href="works.html"><i class="ph ph-arrow-left" aria-hidden="true"></i> Все объекты</a>`;
  } else {
    workDetail.innerHTML = '<div class="page-head"><div class="eyebrow">Портфолио</div><h1>Объект не найден.</h1><p>Возможно, ссылка устарела или объект был перемещён.</p><a class="button" href="works.html">Вернуться к работам<i class="ph ph-arrow-right" aria-hidden="true"></i></a></div>';
  }
}

const galleryModal = document.querySelector('#gallery-modal');
document.querySelectorAll('[data-gallery-image]').forEach(button => button.addEventListener('click', () => {
  const image = galleryModal.querySelector('img');
  image.src = button.dataset.src;
  image.alt = button.querySelector('img')?.alt || button.dataset.caption;
  galleryModal.querySelector('figcaption').textContent = button.dataset.caption;
  galleryModal.showModal();
}));
galleryModal?.querySelector('.gallery-modal-close').addEventListener('click', () => galleryModal.close());
galleryModal?.addEventListener('click', event => {
  if (event.target === galleryModal) galleryModal.close();
});
