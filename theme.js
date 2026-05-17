/**
 * The Signal — Theme JS
 */
(function () {
  'use strict';

  /* ── THEME (DARK/LIGHT) ── */
  function applyTheme(t) {
    document.documentElement.setAttribute('data-theme', t);
    const btn = document.getElementById('themeToggleBtn');
    if (btn) btn.textContent = t === 'light' ? '☀' : '☾';
  }
  const savedTheme = localStorage.getItem('signal-theme') || 'dark';
  applyTheme(savedTheme);
  window.signalToggleTheme = function () {
    const cur = document.documentElement.getAttribute('data-theme');
    const next = cur === 'light' ? 'dark' : 'light';
    applyTheme(next);
    localStorage.setItem('signal-theme', next);
  };

  /* ── MOBILE MENU ── */
  window.signalOpenMenu = function () {
    const m = document.getElementById('mobileMenu');
    if (m) { m.classList.add('open'); document.body.style.overflow = 'hidden'; }
  };
  window.signalCloseMenu = function () {
    const m = document.getElementById('mobileMenu');
    if (m) { m.classList.remove('open'); document.body.style.overflow = ''; }
  };

  /* ── BACK TO TOP ── */
  const btt = document.getElementById('backToTop');
  if (btt) {
    window.addEventListener('scroll', function () {
      btt.classList.toggle('visible', window.scrollY > 300);
    });
    btt.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  /* ── READING PROGRESS BAR ── */
  const rpb = document.getElementById('readingProgressBar');
  const rpw = document.getElementById('rpwBar');
  const rpwPct = document.getElementById('rpwPct');
  const artBody = document.getElementById('articleBody');
  if (rpb || rpw) {
    window.addEventListener('scroll', function () {
      if (!artBody) return;
      const top = artBody.offsetTop;
      const height = artBody.offsetHeight;
      const pct = Math.min(100, Math.max(0, ((window.scrollY - top + 200) / height) * 100));
      if (rpb) rpb.style.width = pct + '%';
      if (rpw) rpw.style.width = pct + '%';
      if (rpwPct) rpwPct.textContent = Math.round(pct) + '% complete';
    });
  }

  /* ── TOC ACTIVE HIGHLIGHT ── */
  const tocLinks = document.querySelectorAll('.toc-link');
  if (tocLinks.length) {
    const headings = document.querySelectorAll('.article-body h2[id], .article-body h3[id]');
    window.addEventListener('scroll', function () {
      let current = '';
      headings.forEach(function (h) {
        if (window.scrollY >= h.offsetTop - 120) current = h.id;
      });
      tocLinks.forEach(function (l) {
        l.classList.toggle('active', l.getAttribute('href') === '#' + current);
      });
    });
  }

  /* ── STREAK CALENDAR ── */
  function buildStreakCal() {
    const cal = document.getElementById('streakCal');
    if (!cal) return;
    const days = ['M', 'T', 'W', 'T', 'F', 'S', 'S'];
    const streak = parseInt(localStorage.getItem('signal-streak') || '5');
    const done = days.map((_, i) => i < streak);
    days.forEach(function (d, i) {
      const w = document.createElement('div');
      w.className = 'streak-day';
      const dot = document.createElement('div');
      dot.className = 'streak-day-dot';
      dot.style.background = done[i] ? 'var(--accent)' : 'var(--border)';
      dot.style.opacity = done[i] ? '1' : '0.4';
      const lbl = document.createElement('div');
      lbl.className = 'streak-day-label';
      lbl.textContent = d;
      w.appendChild(dot);
      w.appendChild(lbl);
      cal.appendChild(w);
    });
    const msg = document.getElementById('streakMsg');
    if (msg) msg.textContent = '🔥 ' + streak + '-day streak! Come back tomorrow to keep it going.';
    const pill = document.getElementById('streakPill');
    if (pill) pill.textContent = '🔥 ' + streak + ' day streak';
  }
  buildStreakCal();

  /* ── LIVE DATE ── */
  const dateEl = document.getElementById('liveDate');
  if (dateEl) {
    dateEl.textContent = new Date().toLocaleDateString('en-US', {
      weekday: 'short', day: '2-digit', month: 'short', year: 'numeric'
    });
  }

  /* ── NEWSLETTER FORMS ── */
  document.querySelectorAll('.nl-form').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      const inp = form.querySelector('input[type="email"]');
      const btn = form.querySelector('button');
      if (!inp || !inp.value.includes('@')) {
        if (inp) inp.style.borderColor = 'var(--accent)';
        return;
      }
      if (btn) { btn.textContent = '✓ You\'re in!'; btn.style.background = 'var(--green)'; btn.disabled = true; }
      if (inp) inp.disabled = true;
    });
  });

  /* ── TOPIC PILLS TOGGLE ── */
  document.querySelectorAll('.topic-pill').forEach(function (pill) {
    pill.addEventListener('click', function () {
      this.classList.toggle('active');
      // Persist to localStorage
      const topics = Array.from(document.querySelectorAll('.topic-pill.active')).map(p => p.dataset.topic || p.textContent.trim());
      localStorage.setItem('signal-topics', JSON.stringify(topics));
    });
  });
  // Restore saved topics
  try {
    const saved = JSON.parse(localStorage.getItem('signal-topics') || '[]');
    document.querySelectorAll('.topic-pill').forEach(function (p) {
      const t = p.dataset.topic || p.textContent.trim();
      if (saved.includes(t)) p.classList.add('active');
    });
  } catch (e) {}

  /* ── BOOKMARK ── */
  window.signalToggleBookmark = function (btn, postId) {
    const bookmarks = JSON.parse(localStorage.getItem('signal-bookmarks') || '[]');
    const idx = bookmarks.indexOf(postId);
    if (idx > -1) {
      bookmarks.splice(idx, 1);
      if (btn) { btn.classList.remove('saved'); btn.textContent = '🔖 Save'; }
    } else {
      bookmarks.push(postId);
      if (btn) { btn.classList.add('saved'); btn.textContent = '✓ Saved'; }
    }
    localStorage.setItem('signal-bookmarks', JSON.stringify(bookmarks));
  };
  // Restore bookmark state
  try {
    const bookmarks = JSON.parse(localStorage.getItem('signal-bookmarks') || '[]');
    document.querySelectorAll('[data-bookmark]').forEach(function (btn) {
      if (bookmarks.includes(btn.dataset.bookmark)) {
        btn.classList.add('saved');
        btn.textContent = '✓ Saved';
      }
    });
  } catch (e) {}

  /* ── SHARE FUNCTIONS ── */
  window.signalShareX = function () {
    window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent(document.title) + '&url=' + encodeURIComponent(location.href));
  };
  window.signalShareWA = function () {
    window.open('https://wa.me/?text=' + encodeURIComponent(document.title + ' ' + location.href));
  };
  window.signalCopyLink = function (btn) {
    navigator.clipboard.writeText(location.href).then(function () {
      if (btn) { const orig = btn.textContent; btn.textContent = '✓'; setTimeout(() => btn.textContent = orig, 2000); }
    });
  };

  /* ── SORT BUTTONS ── */
  document.querySelectorAll('.sort-opt').forEach(function (opt) {
    opt.addEventListener('click', function () {
      this.closest('.sort-opts').querySelectorAll('.sort-opt').forEach(function (o) { o.classList.remove('active'); });
      this.classList.add('active');
    });
  });

  /* ── SCROLL REVEAL ── */
  const reveals = document.querySelectorAll('.reveal');
  const observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry, i) {
      if (entry.isIntersecting) {
        setTimeout(function () { entry.target.classList.add('visible'); }, i * 60);
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.06 });
  reveals.forEach(function (r) { observer.observe(r); });

  /* ── COMMENT FORM AJAX (WP nonce required) ── */
  const commentForm = document.getElementById('commentform');
  if (commentForm) {
    commentForm.style.display = 'block'; // Ensure it's visible
  }

  /* ── UPDATE STREAK ON PAGE LOAD ── */
  (function updateStreak() {
    const today = new Date().toDateString();
    const lastVisit = localStorage.getItem('signal-last-visit');
    let streak = parseInt(localStorage.getItem('signal-streak') || '0');
    if (lastVisit !== today) {
      const yesterday = new Date(Date.now() - 86400000).toDateString();
      if (lastVisit === yesterday) {
        streak = Math.min(streak + 1, 7);
      } else if (!lastVisit) {
        streak = 1;
      } else {
        streak = 1; // Reset
      }
      localStorage.setItem('signal-streak', streak);
      localStorage.setItem('signal-last-visit', today);
    }
  })();

})();
