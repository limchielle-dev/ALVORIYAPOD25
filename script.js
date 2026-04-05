document.addEventListener('DOMContentLoaded', () => {

  // ============================================
  // 1. SCROLL ANIMATION OBSERVER (declared first)
  // ============================================
  const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.1
  };

  const scrollObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        scrollObserver.unobserve(entry.target);
      }
    });
  }, observerOptions);

  const startPageAnimations = () => {
    document.querySelectorAll('.fade-in-up').forEach(el => {
      scrollObserver.observe(el);
    });
  };

  // ============================================
  // 2. INTRO VIDEO HANDLING
  // ============================================
  const introOverlay = document.getElementById('intro-video-overlay');
  const introVideo   = document.getElementById('intro-video');
  const skipBtn      = document.getElementById('skip-intro');

  let introDone = false;

  const finishIntro = () => {
    if (!introOverlay || introDone) return;
    introDone = true;

    // Pause video to free resources
    if (introVideo) introVideo.pause();

    // Fade out overlay
    introOverlay.classList.add('hidden');

    // Unlock body scroll
    document.body.style.overflow = '';

    // Scroll to very top (Hero section)
    window.scrollTo({ top: 0, behavior: 'instant' });

    // After CSS fade-out animation (1s), start page animations
    setTimeout(startPageAnimations, 1000);
  };

  if (introOverlay && introVideo) {
    // Lock scroll while video is showing
    document.body.style.overflow = 'hidden';

    // Listen for video end → auto finish
    introVideo.addEventListener('ended', finishIntro);

    // Skip button
    if (skipBtn) skipBtn.addEventListener('click', finishIntro);

    // --- Try autoplay (muted = browsers allow it) ---
    introVideo.muted = true;
    introVideo.playsInline = true;

    const tryPlay = introVideo.play();

    if (tryPlay !== undefined) {
      tryPlay
        .then(() => {
          // Autoplay worked ✅ — remove the click-to-start screen if it exists
          const tapScreen = document.getElementById('tap-to-enter');
          if (tapScreen) tapScreen.remove();

          // Safety: force-finish after video duration + 1s buffer
          const safeDuration = (introVideo.duration || 12) * 1000 + 1000;
          setTimeout(finishIntro, Math.min(safeDuration, 15000));
        })
        .catch(() => {
          // Autoplay BLOCKED by browser → show "Tap to Enter" screen
          showTapScreen();
        });
    }

    // If duration is 0 (video not loaded yet), wait for metadata
    introVideo.addEventListener('loadedmetadata', () => {
      if (!introDone) {
        const safeDuration = (introVideo.duration || 12) * 1000 + 1000;
        setTimeout(finishIntro, Math.min(safeDuration, 15000));
      }
    });

    // Hard fallback: 15 seconds no matter what
    setTimeout(finishIntro, 15000);

  } else {
    startPageAnimations();
  }

  // Build and show the "Tap to Enter" click gate
  function showTapScreen() {
    // Don't create twice
    if (document.getElementById('tap-to-enter')) return;

    const tapScreen = document.createElement('div');
    tapScreen.id = 'tap-to-enter';
    tapScreen.innerHTML = `
      <div class="tap-inner">
        <div class="tap-logo">ALVORI<span>YA</span>.</div>
        <p class="tap-sub">Click to experience the intro</p>
        <button class="tap-btn" id="tap-btn">
          <span>▶ &nbsp;Enter</span>
        </button>
        <button class="tap-skip" id="tap-skip">Skip intro →</button>
      </div>
    `;

    // Styles injected inline so no CSS file edits needed
    tapScreen.style.cssText = `
      position:fixed; inset:0; z-index:10001;
      background:radial-gradient(circle at center, #1a202c 0%, #000 100%);
      display:flex; align-items:center; justify-content:center;
      font-family:'Outfit',sans-serif; color:#fff;
    `;

    const style = document.createElement('style');
    style.textContent = `
      .tap-inner { text-align:center; }
      .tap-logo { font-size:3rem; font-weight:800; letter-spacing:2px; margin-bottom:0.5rem; }
      .tap-logo span { background:linear-gradient(135deg,#b8860b,#d4af37);
        -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text; }
      .tap-sub { color:rgba(255,255,255,0.5); margin-bottom:2rem; font-size:1rem; }
      .tap-btn { background:linear-gradient(135deg,#b8860b,#d4af37); border:none; color:#fff;
        padding:1.1rem 3.5rem; border-radius:50px; font-size:1.2rem; font-weight:700;
        cursor:pointer; letter-spacing:1px; margin-bottom:1.2rem;
        box-shadow:0 0 30px rgba(184,134,11,0.4); transition:transform .2s,box-shadow .2s; display:block; margin:0 auto 1rem; }
      .tap-btn:hover { transform:scale(1.05); box-shadow:0 0 45px rgba(184,134,11,0.7); }
      .tap-skip { background:none; border:1px solid rgba(255,255,255,0.2); color:rgba(255,255,255,0.5);
        padding:0.5rem 1.5rem; border-radius:50px; font-size:0.85rem; cursor:pointer;
        transition:all .2s; display:block; margin:0.8rem auto 0; }
      .tap-skip:hover { color:#fff; border-color:rgba(255,255,255,0.5); }
    `;
    document.head.appendChild(style);
    document.body.appendChild(tapScreen);

    // Click "Enter" → play video, hide tap screen
    document.getElementById('tap-btn').addEventListener('click', () => {
      tapScreen.style.display = 'none';
      introVideo.muted = false; // try with sound since user interacted
      introVideo.play().catch(() => {
        introVideo.muted = true;
        introVideo.play();
      });
    });

    // Click "Skip intro" → go directly to home
    document.getElementById('tap-skip').addEventListener('click', () => {
      tapScreen.style.display = 'none';
      finishIntro();
    });
  }

  // ============================================
  // 3. MOBILE NAV TOGGLE
  // ============================================
  const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
  const navLinks      = document.querySelector('.nav-links');

  if (mobileMenuBtn && navLinks) {
    mobileMenuBtn.addEventListener('click', () => {
      navLinks.classList.toggle('active');
    });
    navLinks.querySelectorAll('a').forEach(item => {
      item.addEventListener('click', () => {
        navLinks.classList.remove('active');
      });
    });
  }

  // ============================================
  // 4. ACTIVE NAV HIGHLIGHT
  // ============================================
  const currentPath = window.location.pathname;
  document.querySelectorAll('.nav-links a').forEach(item => {
    const href = item.getAttribute('href');
    if (!href || href.startsWith('#')) return;
    if (href === 'index.html' && (currentPath.endsWith('index.html') || currentPath === '/' || currentPath.endsWith('/'))) {
      item.classList.add('active');
    } else if (href !== 'index.html' && currentPath.includes(href)) {
      item.classList.add('active');
    }
  });

});
