const header = document.querySelector('.site-header');
const revealItems = document.querySelectorAll('.reveal');

if (header) {
  const updateHeader = () => header.classList.toggle('is-scrolled', window.scrollY > 120);
  window.addEventListener('scroll', updateHeader, { passive: true });
  updateHeader();
}

if ('IntersectionObserver' in window) {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.08 });
  revealItems.forEach((item) => observer.observe(item));
} else {
  revealItems.forEach((item) => item.classList.add('visible'));
}

document.querySelectorAll('a[href^="#"]').forEach((link) => {
  link.addEventListener('click', (event) => {
    const target = document.querySelector(link.getAttribute('href'));
    if (target) {
      event.preventDefault();
      target.scrollIntoView({ behavior: 'smooth' });
    }
  });
});

document.querySelectorAll('[data-work-grid]').forEach((grid) => {
  const scope = grid.closest('.works, .works-catalogue-page') || document;
  const cards = Array.from(grid.querySelectorAll('[data-work-card]'));
  const filters = Array.from(scope.querySelectorAll('[data-work-filter]'));
  const moreButton = scope.querySelector('[data-works-more]');
  const pageSize = 8;
  let activeFilter = 'all';
  let visibleCount = pageSize;

  const refreshWorks = () => {
    const matchingCards = cards.filter((card) => activeFilter === 'all' || card.dataset.categories.split(' ').includes(activeFilter));

    cards.forEach((card) => {
      card.hidden = !matchingCards.includes(card) || matchingCards.indexOf(card) >= visibleCount;
    });

    if (moreButton) {
      moreButton.hidden = matchingCards.length <= visibleCount;
    }
  };

  filters.forEach((filter) => {
    filter.addEventListener('click', () => {
      activeFilter = filter.dataset.workFilter;
      visibleCount = pageSize;
      filters.forEach((item) => item.classList.toggle('is-active', item === filter));
      refreshWorks();
    });
  });

  if (moreButton) {
    moreButton.addEventListener('click', () => {
      visibleCount += pageSize;
      refreshWorks();
    });
  }

  refreshWorks();
});

const workLightbox = document.querySelector('[data-work-lightbox-dialog]');

if (workLightbox) {
  const image = workLightbox.querySelector('[data-work-lightbox-image]');
  const title = workLightbox.querySelector('[data-work-lightbox-title]');
  let trigger = null;

  const closeLightbox = () => {
    workLightbox.hidden = true;
    document.body.classList.remove('work-lightbox-open');
    if (trigger) trigger.focus();
  };

  document.querySelectorAll('[data-work-lightbox]').forEach((button) => {
    button.addEventListener('click', () => {
      trigger = button;
      image.src = button.dataset.workImage;
      image.alt = button.dataset.workTitle;
      title.textContent = button.dataset.workTitle;
      workLightbox.hidden = false;
      document.body.classList.add('work-lightbox-open');
      workLightbox.querySelector('.work-lightbox-close').focus();
    });
  });

  workLightbox.querySelectorAll('[data-work-lightbox-close]').forEach((button) => {
    button.addEventListener('click', closeLightbox);
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && !workLightbox.hidden) closeLightbox();
  });
}
