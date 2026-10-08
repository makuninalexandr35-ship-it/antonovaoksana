const header = document.querySelector('.site-header');

// Keep punctuation and dashes with the phrase that follows them, so a line
// does not end with a comma, colon, semicolon, or dash.
const protectTypography = (root = document) => {
  if (!root) return;

  const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, {
    acceptNode(node) {
      const parent = node.parentElement;
      if (!parent || parent.closest('script, style, noscript, textarea, pre, code')) {
        return NodeFilter.FILTER_REJECT;
      }
      return node.nodeValue.trim() ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_REJECT;
    },
  });

  const nodes = [];
  while (walker.nextNode()) nodes.push(walker.currentNode);
  nodes.forEach((node) => {
    node.nodeValue = node.nodeValue
      .replace(/([,;:])\s+/g, '$1\u00a0')
      .replace(/\s*([—–])\s*/g, '\u00a0$1\u00a0')
      .replace(/([А-Яа-яЁё])-(?=[А-Яа-яЁё])/g, '$1\u2011')
      .replace(/(^|[\s(«])([ВвКкСсУуОоАаИи])\s+/g, '$1$2\u00a0');
  });
};

protectTypography(document.querySelector('main'));
protectTypography(document.querySelector('footer'));
const revealItems = document.querySelectorAll('.reveal');

if (header) {
  const updateHeader = () => header.classList.toggle('is-scrolled', window.scrollY > 120);
  window.addEventListener('scroll', updateHeader, { passive: true });
  updateHeader();

  const menuToggle = header.querySelector('.menu-toggle');
  const navigation = header.querySelector('.site-navigation');

  if (menuToggle && navigation) {
    const closeMenu = () => {
      document.body.classList.remove('menu-open');
      menuToggle.setAttribute('aria-expanded', 'false');
      menuToggle.setAttribute('aria-label', 'Открыть меню');
    };

    menuToggle.addEventListener('click', () => {
      const willOpen = !document.body.classList.contains('menu-open');
      document.body.classList.toggle('menu-open', willOpen);
      menuToggle.setAttribute('aria-expanded', String(willOpen));
      menuToggle.setAttribute('aria-label', willOpen ? 'Закрыть меню' : 'Открыть меню');
    });

    navigation.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));
    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape') closeMenu();
    });
    window.addEventListener('resize', () => {
      if (window.innerWidth > 1100) closeMenu();
    });
  }
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
  const pageSize = 12;
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
    filter.addEventListener('click', (event) => {
      event.preventDefault();
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
  const viewer = workLightbox.querySelector('[data-work-lightbox-viewer]');
  const source = workLightbox.querySelector('[data-work-lightbox-source]');
  const lens = workLightbox.querySelector('[data-work-lightbox-lens]');
  const zoom = workLightbox.querySelector('[data-work-lightbox-zoom]');
  const touchPoints = new Map();
  const zoomFactor = 3;
  let trigger = null;
  let touchScale = 1;
  let pinchDistance = 0;
  let pinchScale = touchScale;

  const resetZoom = () => {
    viewer.classList.remove('is-desktop-zooming', 'is-touch-zooming');
    lens.hidden = true;
    zoom.hidden = true;
    zoom.style.backgroundImage = '';
    image.style.transform = '';
    image.style.transformOrigin = '';
    touchPoints.clear();
    touchScale = 1;
    pinchDistance = 0;
  };

  const updateDesktopZoom = (event) => {
    const imageRect = image.getBoundingClientRect();
    const sourceRect = source.getBoundingClientRect();
    const zoomRect = zoom.getBoundingClientRect();
    if (!imageRect.width || !imageRect.height || !zoomRect.width || !zoomRect.height) return;

    const lensWidth = Math.min(imageRect.width, zoomRect.width / zoomFactor);
    const lensHeight = Math.min(imageRect.height, zoomRect.height / zoomFactor);
    const x = Math.max(lensWidth / 2, Math.min(event.clientX - imageRect.left, imageRect.width - lensWidth / 2));
    const y = Math.max(lensHeight / 2, Math.min(event.clientY - imageRect.top, imageRect.height - lensHeight / 2));

    lens.style.width = `${lensWidth}px`;
    lens.style.height = `${lensHeight}px`;
    lens.style.left = `${imageRect.left - sourceRect.left + x}px`;
    lens.style.top = `${imageRect.top - sourceRect.top + y}px`;
    zoom.style.backgroundSize = `${imageRect.width * zoomFactor}px ${imageRect.height * zoomFactor}px`;
    zoom.style.backgroundPosition = `${zoomRect.width / 2 - x * zoomFactor}px ${zoomRect.height / 2 - y * zoomFactor}px`;
  };

  source.addEventListener('pointerenter', (event) => {
    if (event.pointerType === 'touch' || !window.matchMedia('(hover: hover)').matches) return;
    viewer.classList.add('is-desktop-zooming');
    lens.hidden = false;
    zoom.hidden = false;
    zoom.style.backgroundImage = `url("${image.currentSrc || image.src}")`;
    requestAnimationFrame(() => updateDesktopZoom(event));
  });

  source.addEventListener('pointermove', (event) => {
    if (event.pointerType === 'touch') {
      if (!touchPoints.has(event.pointerId)) return;
      touchPoints.set(event.pointerId, { x: event.clientX, y: event.clientY });
      const points = Array.from(touchPoints.values());
      if (points.length < 2) return;
      event.preventDefault();
      let focusX = points[0].x;
      let focusY = points[0].y;

      const distance = Math.hypot(points[0].x - points[1].x, points[0].y - points[1].y);
      if (pinchDistance) touchScale = Math.max(1, Math.min(5, pinchScale * distance / pinchDistance));
      focusX = (points[0].x + points[1].x) / 2;
      focusY = (points[0].y + points[1].y) / 2;

      const imageRect = image.getBoundingClientRect();
      const originX = Math.max(0, Math.min(100, (focusX - imageRect.left) / imageRect.width * 100));
      const originY = Math.max(0, Math.min(100, (focusY - imageRect.top) / imageRect.height * 100));
      image.style.transformOrigin = `${originX}% ${originY}%`;
      image.style.transform = `scale(${touchScale})`;
      return;
    }

    if (viewer.classList.contains('is-desktop-zooming')) updateDesktopZoom(event);
  });

  source.addEventListener('pointerleave', (event) => {
    if (event.pointerType === 'touch') return;
    viewer.classList.remove('is-desktop-zooming');
    lens.hidden = true;
    zoom.hidden = true;
  });

  source.addEventListener('pointerdown', (event) => {
    if (event.pointerType !== 'touch') return;
    source.setPointerCapture(event.pointerId);
    touchPoints.set(event.pointerId, { x: event.clientX, y: event.clientY });
    if (touchPoints.size === 2) {
      event.preventDefault();
      const points = Array.from(touchPoints.values());
      pinchDistance = Math.hypot(points[0].x - points[1].x, points[0].y - points[1].y);
      pinchScale = touchScale;
      viewer.classList.add('is-touch-zooming');
    }
  });

  const endTouchZoom = (event) => {
    if (event.pointerType !== 'touch') return;
    touchPoints.delete(event.pointerId);
    if (touchPoints.size < 2) {
      pinchDistance = 0;
      viewer.classList.remove('is-touch-zooming');
      image.style.transform = '';
      image.style.transformOrigin = '';
      touchScale = 1;
    }
  };

  source.addEventListener('pointerup', endTouchZoom);
  source.addEventListener('pointercancel', endTouchZoom);

  const closeLightbox = () => {
    resetZoom();
    workLightbox.hidden = true;
    document.body.classList.remove('work-lightbox-open');
    if (trigger) trigger.focus();
  };

  document.querySelectorAll('[data-work-lightbox]').forEach((button) => {
    button.addEventListener('click', (event) => {
      event.preventDefault();
      trigger = button;
      image.src = button.dataset.workImage;
      image.alt = button.dataset.workTitle;
      title.textContent = button.dataset.workTitle;
      resetZoom();
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

// The SEO module appends this editorial block after the theme has rendered.
// Watch for it, then place it beneath the custom-design section and before
// the fillings information.
if (window.location.pathname.replace(/\/+$/, '') === '/birthday-cakes') {
  const normaliseHeading = (element) => element.textContent.replace(/\s+/g, ' ').trim().toLowerCase();
  const replaceBirthdaySeoTelegramWithMax = (section) => {
    const textNodes = [];
    const walker = document.createTreeWalker(section, NodeFilter.SHOW_TEXT);
    let textNode;

    while ((textNode = walker.nextNode())) textNodes.push(textNode);
    textNodes.forEach((node) => {
      node.nodeValue = node.nodeValue.replace(/telegram/gi, 'MAX');
    });
  };
  const placeBirthdayEditorialSection = () => {
    const mensCakeSection = Array.from(document.querySelectorAll('.seo-agent-section')).find((section) => {
      const heading = section.querySelector('h2');
      return heading && normaliseHeading(heading).startsWith('торт на день рождения мужу');
    });
    const fillingsHeading = Array.from(document.querySelectorAll('.page-content h2')).find((heading) => (
      normaliseHeading(heading) === 'начинки и размер торта'
    ));

    if (!mensCakeSection || !fillingsHeading) return false;
    replaceBirthdaySeoTelegramWithMax(mensCakeSection);
    if (mensCakeSection.nextElementSibling !== fillingsHeading) {
      fillingsHeading.before(mensCakeSection);
    }
    return true;
  };

  if (!placeBirthdayEditorialSection()) {
    const birthdayEditorialObserver = new MutationObserver(() => {
      if (placeBirthdayEditorialSection()) birthdayEditorialObserver.disconnect();
    });
    birthdayEditorialObserver.observe(document.documentElement, { childList: true, subtree: true });
  }
}
