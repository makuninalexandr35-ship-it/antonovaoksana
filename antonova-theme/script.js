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
document.documentElement.classList.add('reveal-enabled');

if (header) {
  const updateHeader = () => header.classList.toggle('is-scrolled', window.scrollY > 120);
  window.addEventListener('scroll', updateHeader, { passive: true });
  updateHeader();

  const menuToggle = header.querySelector('.menu-toggle');
  const navigation = header.querySelector('.site-navigation');

  if (menuToggle && navigation) {
    const categoriesMenu = navigation.querySelector('.nav-categories');
    const closeMenu = () => {
      document.body.classList.remove('menu-open');
      menuToggle.setAttribute('aria-expanded', 'false');
      menuToggle.setAttribute('aria-label', 'Открыть меню');
      if (categoriesMenu) categoriesMenu.open = false;
    };

    menuToggle.addEventListener('click', () => {
      const willOpen = !document.body.classList.contains('menu-open');
      document.body.classList.toggle('menu-open', willOpen);
      menuToggle.setAttribute('aria-expanded', String(willOpen));
      menuToggle.setAttribute('aria-label', willOpen ? 'Закрыть меню' : 'Открыть меню');
    });

    navigation.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMenu));
    document.addEventListener('click', (event) => {
      if (categoriesMenu && categoriesMenu.open && !categoriesMenu.contains(event.target)) {
        categoriesMenu.open = false;
      }
    });
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
    if (link.matches('[data-work-category-link], [data-work-filter]')) return;
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

  const activateFilter = (filterName) => {
    if (!filters.some((filter) => filter.dataset.workFilter === filterName)) return;
    activeFilter = filterName;
    visibleCount = pageSize;
    filters.forEach((item) => {
      const isActive = item.dataset.workFilter === activeFilter;
      item.classList.toggle('is-active', isActive);
      item.setAttribute('aria-pressed', String(isActive));
    });
    refreshWorks();
    document.dispatchEvent(new CustomEvent('home_gallery_filter_click', { detail: { category: activeFilter } }));
  };

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
      activateFilter(filter.dataset.workFilter);
    });
    filter.addEventListener('keydown', (event) => {
      if (event.key === ' ' || event.key === 'Spacebar') {
        event.preventDefault();
        filter.click();
      }
    });
  });

  grid.addEventListener('antonova:work-filter', (event) => {
    if (event.detail && event.detail.category) activateFilter(event.detail.category);
  });

  const requestedFilter = new URLSearchParams(window.location.search).get('work_category');
  if (requestedFilter) activateFilter(requestedFilter);

  if (moreButton) {
    moreButton.addEventListener('click', () => {
      visibleCount += pageSize;
      refreshWorks();
    });
  }

  refreshWorks();
});

document.querySelectorAll('[data-work-category-link]').forEach((link) => {
  link.addEventListener('click', (event) => {
    const category = link.dataset.workCategoryLink;
    const grid = document.querySelector('#works [data-work-grid]');
    const worksHeading = document.getElementById('works-heading');
    if (!category || !grid || !worksHeading) return;
    event.preventDefault();
    grid.dispatchEvent(new CustomEvent('antonova:work-filter', { detail: { category } }));
    worksHeading.scrollIntoView({ behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth', block: 'start' });
    worksHeading.focus({ preventScroll: true });
    document.dispatchEvent(new CustomEvent('home_cake_category_click', { detail: { category } }));
  });
});

const workLightbox = document.querySelector('[data-work-lightbox-dialog]');

if (workLightbox) {
  const image = workLightbox.querySelector('[data-work-lightbox-image]');
  const title = workLightbox.querySelector('[data-work-lightbox-title]');
  const viewer = workLightbox.querySelector('[data-work-lightbox-viewer]');
  const source = workLightbox.querySelector('[data-work-lightbox-source]');
  const lens = workLightbox.querySelector('[data-work-lightbox-lens]');
  const zoom = workLightbox.querySelector('[data-work-lightbox-zoom]');
  const previousButton = workLightbox.querySelector('[data-work-lightbox-previous]');
  const nextButton = workLightbox.querySelector('[data-work-lightbox-next]');
  const lightboxTriggers = Array.from(document.querySelectorAll('[data-work-lightbox]'));
  const touchPoints = new Map();
  const zoomFactor = 3;
  let trigger = null;
  let touchScale = 1;
  let pinchDistance = 0;
  let pinchScale = touchScale;
  let currentIndex = -1;

  const showWork = (index) => {
    if (!lightboxTriggers.length) return;
    currentIndex = (index + lightboxTriggers.length) % lightboxTriggers.length;
    const item = lightboxTriggers[currentIndex];
    image.src = item.dataset.workImage;
    image.alt = item.dataset.workTitle;
    title.textContent = item.dataset.workTitle;
    resetZoom();
  };

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

  lightboxTriggers.forEach((button, index) => {
    button.addEventListener('click', (event) => {
      event.preventDefault();
      trigger = button;
      showWork(index);
      workLightbox.hidden = false;
      document.body.classList.add('work-lightbox-open');
      workLightbox.querySelector('.work-lightbox-close').focus();
    });
  });

  if (previousButton) {
    previousButton.addEventListener('click', () => showWork(currentIndex - 1));
  }

  if (nextButton) {
    nextButton.addEventListener('click', () => showWork(currentIndex + 1));
  }

  workLightbox.querySelectorAll('[data-work-lightbox-close]').forEach((button) => {
    button.addEventListener('click', closeLightbox);
  });

  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && !workLightbox.hidden) closeLightbox();
    if (event.key === 'ArrowLeft' && !workLightbox.hidden) showWork(currentIndex - 1);
    if (event.key === 'ArrowRight' && !workLightbox.hidden) showWork(currentIndex + 1);
  });
}

// The landing galleries render every photograph in HTML first. JavaScript only
// improves the view by showing one selected image at a time; there is no timer
// or automatic slide change.
document.querySelectorAll('[data-thumbnail-gallery]').forEach((gallery) => {
  const panels = Array.from(gallery.querySelectorAll('[data-thumbnail-panel]'));
  const triggers = Array.from(gallery.querySelectorAll('[data-thumbnail-trigger]'));
  const previous = gallery.querySelector('[data-thumbnail-previous]');
  const next = gallery.querySelector('[data-thumbnail-next]');
  const dialog = gallery.querySelector('[data-thumbnail-dialog]');
  const dialogImage = gallery.querySelector('[data-thumbnail-dialog-image]');
  const dialogCaption = gallery.querySelector('[data-thumbnail-dialog-caption]');
  const dialogPrevious = gallery.querySelector('[data-thumbnail-dialog-previous]');
  const dialogNext = gallery.querySelector('[data-thumbnail-dialog-next]');
  const dialogClose = Array.from(gallery.querySelectorAll('[data-thumbnail-dialog-close]'));
  if (!panels.length || !triggers.length) return;

  let selectedIndex = 0;
  let opener = null;

  const selectPhoto = (trigger) => {
    const targetId = trigger.dataset.thumbnailTarget;
    selectedIndex = Math.max(0, triggers.indexOf(trigger));
    const choice = trigger.htmlFor ? document.getElementById(trigger.htmlFor) : null;
    if (choice) choice.checked = true;
    panels.forEach((panel) => panel.classList.toggle('is-active', panel.id === targetId));
    triggers.forEach((item) => {
      const isSelected = item === trigger;
      item.classList.toggle('is-active', isSelected);
      item.setAttribute('aria-current', String(isSelected));
    });
  };

  const selectRelative = (offset) => {
    const nextIndex = (selectedIndex + offset + triggers.length) % triggers.length;
    selectPhoto(triggers[nextIndex]);
  };

  const showDialogPhoto = (index) => {
    if (!dialog || !dialogImage) return;
    const panel = panels[index];
    const image = panel.querySelector('img');
    const caption = panel.querySelector('figcaption');
    dialogImage.src = image.currentSrc || image.src;
    dialogImage.alt = image.alt;
    if (dialogCaption) dialogCaption.textContent = caption ? caption.textContent : image.alt;
    selectPhoto(triggers[index]);
  };

  const openDialog = (index, trigger) => {
    if (!dialog) return;
    opener = trigger;
    showDialogPhoto(index);
    dialog.hidden = false;
    document.body.classList.add('landing-gallery-lightbox-open');
    const closeButton = dialog.querySelector('[data-thumbnail-dialog-close]');
    if (closeButton) closeButton.focus();
  };

  const closeDialog = () => {
    if (!dialog) return;
    dialog.hidden = true;
    document.body.classList.remove('landing-gallery-lightbox-open');
    if (opener) opener.focus();
  };

  triggers.forEach((trigger) => {
    trigger.addEventListener('click', (event) => {
      event.preventDefault();
      selectPhoto(trigger);
    });
  });

  if (previous) previous.addEventListener('click', () => selectRelative(-1));
  if (next) next.addEventListener('click', () => selectRelative(1));

  panels.forEach((panel, index) => {
    const open = panel.querySelector('[data-thumbnail-open]');
    if (open) open.addEventListener('click', (event) => {
      event.preventDefault();
      openDialog(index, open);
    });
  });

  if (dialogPrevious) dialogPrevious.addEventListener('click', () => showDialogPhoto((selectedIndex + triggers.length - 1) % triggers.length));
  if (dialogNext) dialogNext.addEventListener('click', () => showDialogPhoto((selectedIndex + 1) % triggers.length));
  dialogClose.forEach((button) => button.addEventListener('click', closeDialog));
  document.addEventListener('keydown', (event) => {
    if (!dialog || dialog.hidden) return;
    if (event.key === 'Escape') closeDialog();
    if (event.key === 'ArrowLeft') showDialogPhoto((selectedIndex + triggers.length - 1) % triggers.length);
    if (event.key === 'ArrowRight') showDialogPhoto((selectedIndex + 1) % triggers.length);
  });

  gallery.classList.add('is-enhanced');
  selectPhoto(triggers[0]);
});

// The video carousel switches through native HTML radio controls. JavaScript
// only pauses videos that are no longer selected; there is no autoplay.
document.querySelectorAll('[data-chocolate-video-slider]').forEach((slider) => {
  const choices = Array.from(slider.querySelectorAll('.chocolate-video-choice'));
  const videos = Array.from(slider.querySelectorAll('video'));
  const pauseHiddenVideos = () => {
    choices.forEach((choice, index) => {
      if (!choice.checked && videos[index]) videos[index].pause();
    });
  };

  choices.forEach((choice) => choice.addEventListener('change', pauseHiddenVideos));
  pauseHiddenVideos();
});

// Birthday landing: the full SEO copy is server-rendered and stays readable
// without JavaScript. When JavaScript is available, only its visual state is
// enhanced into independent, keyboard-accessible accordions.
document.querySelectorAll('[data-birthday-accordion-button]').forEach((button) => {
  const contentId = button.getAttribute('aria-controls');
  const content = contentId ? document.getElementById(contentId) : null;
  if (!content) return;

  button.setAttribute('aria-expanded', 'false');
  content.hidden = true;

  button.addEventListener('click', () => {
    const isExpanded = button.getAttribute('aria-expanded') === 'true';
    button.setAttribute('aria-expanded', String(!isExpanded));
    content.hidden = isExpanded;
  });
});

// Chocolate landing follows the same progressive-enhancement pattern: all
// text is in server HTML, then optional H3 sections become collapsible.
document.querySelectorAll('[data-chocolate-accordion-button]').forEach((button) => {
  const contentId = button.getAttribute('aria-controls');
  const content = contentId ? document.getElementById(contentId) : null;
  if (!content) return;
  button.setAttribute('aria-expanded', 'false');
  content.hidden = true;
  button.addEventListener('click', () => {
    const expanded = button.getAttribute('aria-expanded') === 'true';
    button.setAttribute('aria-expanded', String(!expanded));
    content.hidden = expanded;
    if (!expanded) document.dispatchEvent(new CustomEvent('chocolate_accordion_open', { detail: { section: contentId } }));
  });
});

document.querySelectorAll('[data-chocolate-max],[data-chocolate-phone]').forEach((link) => {
  link.addEventListener('click', () => {
    const placement = link.getAttribute('data-chocolate-max') || link.getAttribute('data-chocolate-phone');
    document.dispatchEvent(new CustomEvent(link.hasAttribute('data-chocolate-max') ? 'chocolate_max_click' : 'chocolate_phone_click', { detail: { placement } }));
  });
});

// Wedding landing uses the same server-rendered, progressive pattern: the six
// panels are in the initial HTML, while JavaScript only makes them collapsible.
document.querySelectorAll('[data-wedding-accordion-button]').forEach((button) => {
  const contentId = button.getAttribute('aria-controls');
  const content = contentId ? document.getElementById(contentId) : null;
  if (!content) return;
  button.setAttribute('aria-expanded', 'false');
  content.hidden = true;
  button.addEventListener('click', () => {
    const expanded = button.getAttribute('aria-expanded') === 'true';
    button.setAttribute('aria-expanded', String(!expanded));
    content.hidden = expanded;
    if (!expanded) document.dispatchEvent(new CustomEvent('wedding_accordion_open', { detail: { section: contentId } }));
  });
});

document.querySelectorAll('[data-wedding-max],[data-wedding-phone]').forEach((link) => {
  link.addEventListener('click', () => {
    const placement = link.getAttribute('data-wedding-max') || link.getAttribute('data-wedding-phone');
    document.dispatchEvent(new CustomEvent(link.hasAttribute('data-wedding-max') ? 'wedding_max_click' : 'wedding_phone_click', { detail: { placement } }));
  });
});
