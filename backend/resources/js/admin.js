import { bindPhoneMasks } from './phone-mask';

bindPhoneMasks();

const titleInput = document.querySelector('[data-seo-title]');
const descriptionInput = document.querySelector('[data-seo-description]');
const previewTitle = document.querySelector('[data-seo-preview-title]');
const previewDescription = document.querySelector('[data-seo-preview-description]');

const syncPreview = () => {
  if (previewTitle && titleInput) {
    previewTitle.textContent = titleInput.value || titleInput.dataset.fallback || 'Заголовок страницы';
  }
  if (previewDescription && descriptionInput) {
    previewDescription.textContent = descriptionInput.value || descriptionInput.dataset.fallback || 'Описание страницы появится здесь.';
  }
};

titleInput?.addEventListener('input', syncPreview);
descriptionInput?.addEventListener('input', syncPreview);
syncPreview();
