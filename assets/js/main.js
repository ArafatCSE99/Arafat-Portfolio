document.addEventListener('DOMContentLoaded', function () {
  /* ---------- Click-and-drag scroll (mouse acts like a touch-scroll drag) ----------
     Desktop only (touch devices already scroll natively by dragging). Skips a real
     drag until the pointer has moved past a small threshold, so plain clicks and
     text selection still work normally. */
  if (!('ontouchstart' in window) && !navigator.maxTouchPoints) {
    (function () {
      var isDown = false, dragging = false, startY = 0, startScroll = 0;
      var THRESHOLD = 6;
      document.addEventListener('mousedown', function (e) {
        if (e.button !== 0) return;
        if (e.target.closest('a, button, input, textarea, select, label, [contenteditable], .filter-chip, .admin-sidebar, canvas')) return;
        isDown = true; dragging = false;
        startY = e.clientY; startScroll = window.scrollY;
      });
      document.addEventListener('mousemove', function (e) {
        if (!isDown) return;
        var dy = e.clientY - startY;
        if (!dragging && Math.abs(dy) > THRESHOLD) {
          dragging = true;
          document.body.classList.add('is-dragging');
          var sel = window.getSelection();
          if (sel) sel.removeAllRanges();
        }
        if (dragging) window.scrollTo({ top: startScroll - dy, behavior: 'instant' });
      });
      function endDrag() {
        isDown = false;
        document.body.classList.remove('is-dragging');
      }
      document.addEventListener('mouseup', endDrag);
      document.addEventListener('mouseleave', endDrag);
      document.addEventListener('click', function (e) {
        if (dragging) { e.preventDefault(); e.stopPropagation(); }
        dragging = false;
      }, true);
    })();
  }

  /* ---------- Floating back button ---------- */
  var backFab = document.getElementById('backFab');
  if (backFab) {
    backFab.addEventListener('click', function () {
      history.back();
    });
  }

  /* ---------- Mobile nav ---------- */
  var navToggle = document.querySelector('.nav-toggle');
  var navLinks = document.querySelector('.nav-links');
  if (navToggle && navLinks) {
    navToggle.addEventListener('click', function () {
      navLinks.classList.toggle('open');
    });
    navLinks.querySelectorAll('a').forEach(function (a) {
      a.addEventListener('click', function () { navLinks.classList.remove('open'); });
    });
  }

  /* ---------- Scroll reveal ---------- */
  var revealEls = document.querySelectorAll('.reveal');
  if ('IntersectionObserver' in window && revealEls.length) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('in-view');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });
    revealEls.forEach(function (el) { observer.observe(el); });
  } else {
    revealEls.forEach(function (el) { el.classList.add('in-view'); });
  }

  /* ---------- Skill bar fill animation ---------- */
  var bars = document.querySelectorAll('.skill-bar-fill');
  if ('IntersectionObserver' in window && bars.length) {
    var barObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.style.width = entry.target.dataset.width + '%';
          barObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.3 });
    bars.forEach(function (el) { barObserver.observe(el); });
  } else {
    bars.forEach(function (el) { el.style.width = el.dataset.width + '%'; });
  }

  /* ---------- Project filter (client-side) ---------- */
  var filterBar = document.querySelector('.filter-bar');
  if (filterBar) {
    var chips = filterBar.querySelectorAll('.filter-chip');
    var items = document.querySelectorAll('[data-category]');
    chips.forEach(function (chip) {
      chip.addEventListener('click', function () {
        chips.forEach(function (c) { c.classList.remove('active'); });
        chip.classList.add('active');
        var cat = chip.dataset.filter;
        items.forEach(function (item) {
          var show = cat === 'all' || item.dataset.category === cat;
          item.style.display = show ? '' : 'none';
        });
      });
    });
  }

  /* ---------- Blog search (client-side) ---------- */
  var searchInput = document.getElementById('blog-search');
  if (searchInput) {
    var posts = document.querySelectorAll('[data-search]');
    searchInput.addEventListener('input', function () {
      var q = searchInput.value.trim().toLowerCase();
      posts.forEach(function (post) {
        var match = post.dataset.search.toLowerCase().indexOf(q) !== -1;
        post.style.display = match ? '' : 'none';
      });
    });
  }

  /* ---------- FAQ accordion ---------- */
  document.querySelectorAll('.faq-question').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var item = btn.closest('.faq-item');
      var wasOpen = item.classList.contains('open');
      item.parentElement.querySelectorAll('.faq-item.open').forEach(function (el) { el.classList.remove('open'); el.querySelector('.faq-question').setAttribute('aria-expanded', 'false'); });
      if (!wasOpen) {
        item.classList.add('open');
        btn.setAttribute('aria-expanded', 'true');
      }
    });
  });

  /* ---------- Admin: off-canvas sidebar on mobile ---------- */
  var adminToggle = document.getElementById('adminSidebarToggle');
  var adminSidebar = document.getElementById('adminSidebar');
  var adminBackdrop = document.getElementById('adminSidebarBackdrop');
  if (adminToggle && adminSidebar && adminBackdrop) {
    function closeAdminSidebar() {
      adminSidebar.classList.remove('open');
      adminBackdrop.classList.remove('open');
    }
    adminToggle.addEventListener('click', function () {
      adminSidebar.classList.toggle('open');
      adminBackdrop.classList.toggle('open');
    });
    adminBackdrop.addEventListener('click', closeAdminSidebar);
    adminSidebar.querySelectorAll('a').forEach(function (a) {
      a.addEventListener('click', closeAdminSidebar);
    });
  }

  /* ---------- Live demo preview modal ---------- */
  var demoOverlay = document.getElementById('demoModalOverlay');
  if (demoOverlay) {
    var demoImg = document.getElementById('demoModalImg');
    var demoUrlEl = document.getElementById('demoModalUrl');
    var demoClose = document.getElementById('demoModalClose');
    var closeDemoModal = function () {
      demoOverlay.classList.remove('open');
      document.body.style.overflow = '';
    };
    document.querySelectorAll('[data-demo-open]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        demoImg.src = btn.dataset.demoImg;
        demoImg.alt = btn.dataset.demoTitle || 'Live preview';
        demoUrlEl.textContent = 'yeasinarafat.dev/projects/' + (btn.dataset.demoSlug || '');
        demoOverlay.classList.add('open');
        document.body.style.overflow = 'hidden';
      });
    });
    demoClose.addEventListener('click', closeDemoModal);
    demoOverlay.addEventListener('click', function (e) { if (e.target === demoOverlay) closeDemoModal(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeDemoModal(); });
  }

  /* ---------- Back to top ----------
     Driven by JS rather than a plain #top anchor: the target sits on the
     sticky header, and browsers often treat an already-stuck sticky element
     as "in view" and skip the scroll entirely. */
  var backToTopBtn = document.getElementById('backToTopBtn');
  if (backToTopBtn) {
    backToTopBtn.addEventListener('click', function (e) {
      e.preventDefault();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ---------- Auto-dismiss alerts ---------- */
  document.querySelectorAll('.alert[data-autohide]').forEach(function (el) {
    setTimeout(function () { el.style.display = 'none'; }, 6000);
  });

  /* ---------- Per-post Bangla / English blog toggle ---------- */
  var langBtn = document.getElementById('langSwitchBtn');
  if (langBtn) {
    var postTitle = document.getElementById('postTitle');
    var articleEn = document.getElementById('articleEn');
    var articleBn = document.getElementById('articleBn');
    var langLabel = document.getElementById('langSwitchLabel');
    var showingBn = false;
    langBtn.addEventListener('click', function () {
      showingBn = !showingBn;
      if (articleEn) articleEn.hidden = showingBn;
      if (articleBn) articleBn.hidden = !showingBn;
      if (postTitle) postTitle.textContent = showingBn ? postTitle.dataset.bn : postTitle.dataset.en;
      if (langLabel) langLabel.textContent = showingBn ? langBtn.dataset.labelEn : langBtn.dataset.labelBn;
      document.documentElement.lang = showingBn ? 'bn' : 'en';
    });
  }

  /* ---------- Header clock + Dhaka temperature ---------- */
  var clockTimeEl = document.getElementById('navClockTime');
  var clockTempEl = document.getElementById('navClockTemp');
  if (clockTimeEl) {
    var timeFormatter = new Intl.DateTimeFormat('en-US', {
      timeZone: 'Asia/Dhaka', hour: '2-digit', minute: '2-digit', hour12: true
    });
    function tickClock() { clockTimeEl.textContent = timeFormatter.format(new Date()); }
    tickClock();
    setInterval(tickClock, 1000);
  }
  if (clockTempEl) {
    var today = new Date().toISOString().slice(0, 10);
    var cacheKey = 'dhakaTempCache';
    var cached = null;
    try { cached = JSON.parse(localStorage.getItem(cacheKey) || 'null'); } catch (e) { cached = null; }
    if (cached && cached.date === today && typeof cached.temp === 'number') {
      clockTempEl.textContent = Math.round(cached.temp) + '°C';
    } else {
      fetch('https://api.open-meteo.com/v1/forecast?latitude=23.8103&longitude=90.4125&current=temperature_2m&timezone=Asia%2FDhaka')
        .then(function (res) { return res.json(); })
        .then(function (data) {
          var temp = data && data.current && data.current.temperature_2m;
          if (typeof temp === 'number') {
            clockTempEl.textContent = Math.round(temp) + '°C';
            try { localStorage.setItem(cacheKey, JSON.stringify({ date: today, temp: temp })); } catch (e) { /* ignore */ }
          }
        })
        .catch(function () { /* keep placeholder on failure */ });
    }
  }
});
