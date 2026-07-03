// ============================================================
// Vyralabs Landing Page — behaviour layer
// ============================================================

document.addEventListener('DOMContentLoaded', function () {

  // ---------- Sticky navbar shadow on scroll ----------
  const navbar = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 60);
  });

  // ---------- FAQ accordion ----------
  document.querySelectorAll('.faq-item').forEach(item => {
    item.querySelector('.faq-q').addEventListener('click', () => {
      const open = item.classList.contains('open');
      document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
      if (!open) item.classList.add('open');
    });
  });

  // ---------- Scroll-reveal animations ----------
  const revealObs = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.classList.add('revealed');
        revealObs.unobserve(e.target);
      }
    });
  }, { threshold: .12 });
  document.querySelectorAll('[data-reveal]').forEach(el => revealObs.observe(el));

  // ---------- Animated stat counters ----------
  function countUp(el, target, duration = 1600) {
    let start = 0;
    const step = target / duration * 16;
    const timer = setInterval(() => {
      start = Math.min(start + step, target);
      el.textContent = Math.floor(start) + '%';
      if (start >= target) clearInterval(timer);
    }, 16);
  }
  const countObs = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        const target = parseInt(e.target.dataset.count);
        countUp(e.target, target);
        countObs.unobserve(e.target);
      }
    });
  }, { threshold: .5 });
  document.querySelectorAll('[data-count]').forEach(el => countObs.observe(el));

  // ---------- Mobile nav toggle ----------
  const toggle = document.querySelector('.nav-toggle');
  const navLinks = document.querySelector('.nav-links');
  toggle?.addEventListener('click', () => {
    const vis = navLinks.style.display === 'flex';
    navLinks.style.cssText = vis ? '' : `
      display:flex;flex-direction:column;position:fixed;
      top:72px;left:0;right:0;background:rgba(7,11,18,.97);
      backdrop-filter:blur(20px);padding:24px 32px;gap:20px;
      border-bottom:1px solid rgba(255,255,255,.06);z-index:899;
    `;
  });

  // ---------- Animated biomarker bar segments ----------
  const barObs = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.querySelectorAll('.seg').forEach((seg, i) => {
          const targets = ['25%', '35%', '30%', '10%'];
          seg.style.width = '0%';
          setTimeout(() => {
            seg.style.width = targets[i] || '25%';
          }, i * 80 + 200);
        });
        barObs.unobserve(e.target);
      }
    });
  }, { threshold: .3 });
  document.querySelectorAll('.bm-bar-track').forEach(el => barObs.observe(el));

  // ---------- Video / Article modal system ----------
  const videoModal = document.getElementById('vyrEcosystemVideoModal');
  const videoIframe = document.getElementById('vyrEcosystemIframeTarget');
  const videoCloseBtn = document.getElementById('vyrEcosystemCloseBtn');
  const videoModalTitle = document.getElementById('vyrVideoModalTitle');
  const videoModalDesc = document.getElementById('vyrVideoModalDesc');
  const videoTriggers = document.querySelectorAll('[data-video-trigger], .vyr-video-modal-trigger');

  const articleModal = document.getElementById('vyrEcosystemArticleModal');
  const articleTitle = document.getElementById('vyrArticleModalTitle');
  const articleBody = document.getElementById('vyrArticleModalBody');
  const articleDate = document.getElementById('vyrArticleModalMetaDate');
  const articleCloseBtn = document.getElementById('vyrArticleCloseBtn');
  const articleTriggers = document.querySelectorAll('.vyr-article-trigger');

  function parseToEmbedUrl(url) {
    let videoId = '';
    try {
      if (url.includes('youtube.com/shorts/')) {
        videoId = url.split('shorts/')[1].split(/[?#]/)[0];
      } else if (url.includes('youtu.be/')) {
        videoId = url.split('youtu.be/')[1].split(/[?#]/)[0];
      } else if (url.includes('youtube.com/watch')) {
        const urlParams = new URLSearchParams(new URL(url).search);
        videoId = urlParams.get('v');
      } else {
        return url;
      }
      return `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0&modestbranding=1&controls=0&disablekb=1&fs=0&iv_load_policy=3&playsinline=1`;
    } catch (e) {
      return url;
    }
  }

  videoTriggers.forEach(trigger => {
    trigger.addEventListener('click', function (e) {
      e.preventDefault();
      const streamUrl = this.getAttribute('data-stream-url');
      if (streamUrl) {
        videoIframe.src = parseToEmbedUrl(streamUrl);
        const vTitle = this.getAttribute('data-video-title');
        const vDesc = this.getAttribute('data-video-desc');
        if (videoModalTitle) videoModalTitle.innerText = vTitle || 'Vyralabs Media Stream Hub';
        if (videoModalDesc) {
          if (vDesc) {
            videoModalDesc.innerText = vDesc;
            videoModalDesc.style.display = 'block';
          } else {
            videoModalDesc.innerText = '';
            videoModalDesc.style.display = 'none';
          }
        }
        videoModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
      }
    });
  });

  function killVideoPlayer() {
    videoModal.style.display = 'none';
    videoIframe.src = '';
    if (videoModalTitle) videoModalTitle.innerText = 'Vyralabs Media Stream Hub';
    if (videoModalDesc) {
      videoModalDesc.innerText = '';
      videoModalDesc.style.display = 'none';
    }
    document.body.style.overflow = '';
  }
  if (videoCloseBtn) videoCloseBtn.addEventListener('click', killVideoPlayer);
  if (videoModal) {
    videoModal.addEventListener('click', function (e) {
      if (e.target === videoModal) killVideoPlayer();
    });
  }

  articleTriggers.forEach(trigger => {
    trigger.addEventListener('click', function (e) {
      e.preventDefault();
      const title = this.getAttribute('data-article-title');
      const body = this.getAttribute('data-article-body');
      const date = this.getAttribute('data-article-date');
      if (title && body) {
        articleTitle.innerText = title;
        articleBody.innerHTML = body;
        articleDate.innerText = 'PUBLISHED AT: ' + date;
        articleModal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
      }
    });
  });

  function closeArticleReader() {
    articleModal.style.display = 'none';
    document.body.style.overflow = '';
  }
  if (articleCloseBtn) articleCloseBtn.addEventListener('click', closeArticleReader);
  if (articleModal) {
    articleModal.addEventListener('click', function (e) {
      if (e.target === articleModal) closeArticleReader();
    });
  }

  // ---------- Knowledge stream type filter ----------
  const filterBtns = document.querySelectorAll('.vyr-filter-btn');
  const streamCards = document.querySelectorAll('.stream-item-node');
  filterBtns.forEach(btn => {
    btn.addEventListener('click', function () {
      filterBtns.forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      const val = this.getAttribute('data-filter');
      streamCards.forEach(card => {
        card.style.display = (val === 'all' || card.getAttribute('data-type-node') === val) ? 'flex' : 'none';
      });
    });
  });

  // ---------- Knowledge stream swiper ----------
  if (window.Swiper) {
    new Swiper('.vyr-knowledge-swiper', {
      slidesPerView: 1,
      spaceBetween: 24,
      loop: true,
      autoplay: {
        delay: 3500,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      },
      navigation: {
        nextEl: '.vyr-swiper-next',
        prevEl: '.vyr-swiper-prev',
      },
      breakpoints: {
        640: { slidesPerView: 1.5 },
        768: { slidesPerView: 2 },
        1024: { slidesPerView: 3 }
      }
    });
  }

  // ---------- Language dropdown ----------
  document.addEventListener('click', function () {
    const menu = document.getElementById('customLangMenu');
    const chevron = document.getElementById('langChevron');
    if (menu) {
      menu.style.display = 'none';
      if (chevron) chevron.style.transform = 'rotate(0deg)';
    }
  });

  // ---------- Profile dropdown outside-click close ----------
  window.onclick = function (event) {
    if (!event.target.matches('.dropdown-toggle') && !event.target.closest('.dropdown-toggle')) {
      const dropdowns = document.getElementsByClassName('dropdown-menu-custom');
      for (let i = 0; i < dropdowns.length; i++) {
        const openDropdown = dropdowns[i];
        if (openDropdown.classList.contains('show-dropdown')) {
          openDropdown.classList.remove('show-dropdown');
        }
      }
    }
  };

});

// ---------- Language dropdown toggle (global, used inline in nav) ----------
function toggleLangMenu(event) {
  event.stopPropagation();
  const menu = document.getElementById('customLangMenu');
  const chevron = document.getElementById('langChevron');
  if (menu.style.display === 'none' || menu.style.display === '') {
    menu.style.display = 'block';
    chevron.style.transform = 'rotate(180deg)';
  } else {
    menu.style.display = 'none';
    chevron.style.transform = 'rotate(0deg)';
  }
}

// ---------- User profile dropdown toggle (global, used inline in nav) ----------
function toggleDropdown(id) {
  document.getElementById(id).classList.toggle('show-dropdown');
}