<footer>
  <div class="footer-inner">
    <div class="footer-grid">
      <div class="footer-logo">
        <img src="<?php echo content_url('/uploads/LOGO.jpg'); ?>" alt="明德行" onerror="this.style.display='none'">
        <div class="company-zh">明德行國際有限公司</div>
        <div class="company-en">Meng Tak Hong International Co., Ltd.</div>
        <div class="est">Est. 1998</div>
        <address>
          澳門黑沙環慕拉士大馬路195號<br>南嶺工業大廈4樓F<br><br>
          <a href="tel:+85328415128">&#128222; +853 28415128</a> / <a href="tel:+85328584838">+853 28584838</a><br>
          <a href="mailto:info@mengtakhong.com">&#9993; info@mengtakhong.com</a>
        </address>
      </div>
      <div class="footer-col">
        <h4>產品分類</h4>
        <ul>
          <li><a href="<?php echo home_url('/product-category/whisky/'); ?>">威士忌</a></li>
          <li><a href="<?php echo home_url('/product-category/cognac/'); ?>">干邑/拔蘭地</a></li>
          <li><a href="<?php echo home_url('/product-category/japan/'); ?>">日本產品</a></li>
          <li><a href="<?php echo home_url('/product-category/korea/'); ?>">韓國/亞洲飲品</a></li>
          <li><a href="<?php echo home_url('/product-category/wine/'); ?>">葡萄酒/香檳</a></li>
          <li><a href="<?php echo home_url('/product-category/liqueur-gin-rum/'); ?>">力嬌/琴酒/冧酒</a></li>
          <li><a href="<?php echo home_url('/product-category/chinese/'); ?>">中國白酒</a></li>
          <li><a href="<?php echo home_url('/product-category/beer-beverages/'); ?>">啤酒/飲料</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>訂閱新到貨</h4>
        <form class="newsletter-form" id="newsletter-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
          <input type="hidden" name="action" value="mth_newsletter_subscribe">
          <?php wp_nonce_field('mth_newsletter', 'mth_newsletter_nonce'); ?>
          <input type="text" name="nl_name" placeholder="稱呼" required>
          <select name="nl_method" required>
            <option value="">聯絡方式</option>
            <option value="email">Email</option>
            <option value="whatsapp">WhatsApp</option>
            <option value="ig">Instagram</option>
            <option value="fb">Facebook</option>
          </select>
          <input type="text" name="nl_contact" placeholder="輸入聯絡方式" required>
          <button type="submit">訂閱</button>
          <div class="nl-msg" id="nl-msg"></div>
        </form>
      </div>
      <div class="footer-col">
        <h4>聯絡我們</h4>
        <div class="footer-social">
          <a href="https://www.facebook.com/profile.php?id=61555744448402" class="social-btn fb" target="_blank" rel="noopener noreferrer">
            Facebook
          </a>
          <a href="https://www.instagram.com/mengtakhong.mo/" class="social-btn ig" target="_blank" rel="noopener noreferrer">
            Instagram
          </a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <span>&copy; <?php echo date('Y'); ?> 明德行國際有限公司 Meng Tak Hong International Co., Ltd.</span>
      <span>澳門洋酒飲品批發代理</span>
    </div>
  </div>
</footer>

<div class="social-float">
  <!-- 1. 查詢購物車 -->
  <button class="sf-btn sf-cart" data-action="open-cart" aria-label="查詢清單">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18">
      <path d="M9 11V7a3 3 0 0 1 6 0v4"/><rect x="4" y="11" width="16" height="10" rx="2"/>
    </svg>
    <span class="cart-badge" style="display:none">0</span>
  </button>
  <!-- 2. Email -->
  <a href="mailto:info@mengtakhong.com" aria-label="Email" class="sf-btn sf-email">
    <span class="sf-emoji">📧</span>
  </a>
  <!-- 3. Facebook -->
  <a href="https://www.facebook.com/profile.php?id=61555744448402" target="_blank" rel="noopener noreferrer" aria-label="Facebook" class="sf-btn sf-fb">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="18" height="18">
      <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
    </svg>
  </a>
  <!-- 4. Instagram -->
  <a href="https://www.instagram.com/mengtakhong.mo/" target="_blank" rel="noopener noreferrer" aria-label="Instagram" class="sf-btn sf-ig">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="18" height="18">
      <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
    </svg>
  </a>
</div>

<style>
.social-float {
    position: fixed;
    bottom: 30px;
    right: 30px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    z-index: 9999;
}
.sf-btn {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform .25s, background .25s, color .25s, box-shadow .25s;
    box-shadow: 2px 2px 8px rgba(0,0,0,0.2);
    text-decoration: none;
    border: none; cursor: pointer; padding: 0;
    position: relative;
    font-family: inherit;
}
.sf-btn:hover { transform: scale(1.15); }
.sf-cart  { background: #1C1C1C; color: #D4AF37; border: 1px solid rgba(212,175,55,.5); }
.sf-cart:hover  { background: #D4AF37; color: #1C1C1C; }
.sf-email { background: #fff; color: #1C1C1C; border: 1px solid #D4AF37; }
.sf-email:hover { background: #fff8e8; }
.sf-email .sf-emoji { font-size: 18px; line-height: 1; }
.sf-fb    { background-color: #1877F2; }
.sf-ig    { background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); }
.cart-badge {
    position: absolute; top: -4px; right: -4px;
    min-width: 18px; height: 18px; padding: 0 5px; border-radius: 9px;
    background: #D4AF37; color: #1C1C1C;
    font-size: 10px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
}
@media (max-width: 760px) {
  .social-float { bottom: 80px !important; right: 14px !important; }
  .sf-btn { width: 34px !important; height: 34px !important; }
  .sf-email .sf-emoji { font-size: 16px; }
}
</style>

<!-- Mobile Bottom Navigation Bar -->
<nav class="mobile-bottom-nav" aria-label="手機底部導航">
  <a href="<?php echo home_url('/'); ?>" class="mbn-item <?php echo is_front_page() ? 'active' : ''; ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
    </svg>
    <span class="mbn-label">首頁</span>
  </a>
  <button class="mbn-item" id="mbn-cats-btn" aria-label="分類">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
      <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
    </svg>
    <span class="mbn-label">分類</span>
  </button>
  <button class="mbn-item" id="mbn-search-btn" aria-label="搜尋">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
    </svg>
    <span class="mbn-label">搜尋</span>
  </button>
  <a href="<?php echo home_url('/about/'); ?>" class="mbn-item <?php echo is_page('about') ? 'active' : ''; ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
      <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
    </svg>
    <span class="mbn-label">關於</span>
  </a>
</nav>

<!-- Mobile JS: hamburger, search overlay, carousel swipe -->
<script>
(function() {
  var hamburgerBtn     = document.getElementById('hamburger-btn');
  var mobileNavOverlay = document.getElementById('mobile-nav-overlay');
  var mobileNavClose   = document.getElementById('mobile-nav-close');
  var searchBtnNav     = document.getElementById('mobile-search-btn-nav');
  var searchOverlay    = document.getElementById('mobile-search-overlay');
  var searchCancel     = document.getElementById('mobile-search-cancel');
  var searchInput      = document.getElementById('mobile-search-input');
  var mbnCatsBtn       = document.getElementById('mbn-cats-btn');
  var mbnSearchBtn     = document.getElementById('mbn-search-btn');

  function openNav() {
    if (!mobileNavOverlay) return;
    mobileNavOverlay.classList.add('open');
    mobileNavOverlay.setAttribute('aria-hidden', 'false');
    if (hamburgerBtn) { hamburgerBtn.classList.add('active'); hamburgerBtn.setAttribute('aria-expanded', 'true'); }
    document.body.style.overflow = 'hidden';
  }
  function closeNav() {
    if (!mobileNavOverlay) return;
    mobileNavOverlay.classList.remove('open');
    mobileNavOverlay.setAttribute('aria-hidden', 'true');
    if (hamburgerBtn) { hamburgerBtn.classList.remove('active'); hamburgerBtn.setAttribute('aria-expanded', 'false'); }
    document.body.style.overflow = '';
  }
  function openSearch() {
    if (!searchOverlay) return;
    searchOverlay.classList.add('open');
    searchOverlay.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    if (searchInput) setTimeout(function() { searchInput.focus(); }, 80);
  }
  function closeSearch() {
    if (!searchOverlay) return;
    searchOverlay.classList.remove('open');
    searchOverlay.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  if (hamburgerBtn)   hamburgerBtn.addEventListener('click', openNav);
  if (mobileNavClose) mobileNavClose.addEventListener('click', closeNav);
  if (mbnCatsBtn)     mbnCatsBtn.addEventListener('click', openNav);
  if (searchBtnNav)   searchBtnNav.addEventListener('click', openSearch);
  if (mbnSearchBtn)   mbnSearchBtn.addEventListener('click', openSearch);
  if (searchCancel)   searchCancel.addEventListener('click', closeSearch);

  // Close nav when clicking a link inside it
  if (mobileNavOverlay) {
    mobileNavOverlay.querySelectorAll('a').forEach(function(link) {
      link.addEventListener('click', closeNav);
    });
  }

  // ESC key closes both overlays
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { closeNav(); closeSearch(); }
  });

  // ── Carousel touch swipe ──
  function getTranslateX(el) {
    var style = window.getComputedStyle(el).transform;
    if (!style || style === 'none') return 0;
    var m = style.match(/matrix\(([^)]+)\)/);
    if (!m) return 0;
    return parseFloat(m[1].split(',')[4]) || 0;
  }

  document.querySelectorAll('.carousel-track-outer').forEach(function(outer) {
    var track = outer.querySelector('.carousel-track');
    if (!track || !track.children.length) return;
    var startX = 0, startTime = 0, dragging = false;

    outer.addEventListener('touchstart', function(e) {
      startX    = e.touches[0].clientX;
      startTime = Date.now();
      dragging  = true;
    }, {passive: true});

    outer.addEventListener('touchmove', function(e) {
      if (!dragging) return;
    }, {passive: true});

    outer.addEventListener('touchend', function(e) {
      if (!dragging) return;
      dragging = false;
      var diff    = startX - e.changedTouches[0].clientX;
      var elapsed = Date.now() - startTime;
      if (Math.abs(diff) < 35 || elapsed > 600) return;

      var firstCard = track.children[0];
      if (!firstCard) return;
      var cardW    = firstCard.offsetWidth + 18;
      var visible  = Math.max(1, Math.floor(outer.offsetWidth / cardW));
      var maxIdx   = Math.max(0, track.children.length - visible);
      var curOffset = Math.abs(getTranslateX(track));
      var curIdx   = Math.round(curOffset / cardW);
      var newIdx   = diff > 0 ? Math.min(maxIdx, curIdx + 1) : Math.max(0, curIdx - 1);

      track.style.transform = 'translateX(-' + (newIdx * cardW) + 'px)';
    }, {passive: true});
  });
})();
</script>

<!-- 防盜圖 -->
<script>
document.addEventListener('contextmenu', function(e) {
    if (e.target.tagName === 'IMG') {
        e.preventDefault();
        return false;
    }
});
document.addEventListener('dragstart', function(e) {
    if (e.target.tagName === 'IMG') {
        e.preventDefault();
        return false;
    }
});
</script>
<style>
img { -webkit-user-select: none; -moz-user-select: none; user-select: none; }
</style>

<!-- 年齡驗證 -->
<div id="age-gate-overlay">
    <div id="age-gate-modal">
        <img src="<?php echo content_url('/uploads/LOGO.jpg'); ?>" alt="明德行國際" id="age-gate-logo" onerror="this.style.display='none'">
        <div class="age-gate-divider"></div>
        <h2 id="age-gate-title">你是否已年滿18歲？</h2>
        <p id="age-gate-subtitle">Are you over 18 years old?</p>
        <p id="age-gate-warning">根據第 6/2023 號法律的規定，禁止向未滿十八歲人士銷售或提供酒精飲料</p>
        <div id="age-gate-buttons">
            <button id="age-gate-yes">是 YES</button>
            <button id="age-gate-no">否 NO</button>
        </div>
    </div>
</div>

<style>
#age-gate-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.85);
    z-index: 999999;
    align-items: center;
    justify-content: center;
}
#age-gate-overlay.active {
    display: flex;
}
#age-gate-modal {
    background: #FFFFFF;
    padding: 50px 60px;
    border-radius: 4px;
    text-align: center;
    max-width: 480px;
    width: 90%;
    box-shadow: 0 20px 60px rgba(0,0,0,0.5);
}
#age-gate-logo {
    max-width: 180px;
    height: auto;
    margin-bottom: 20px;
}
.age-gate-divider {
    width: 60px;
    height: 1px;
    background: #D4AF37;
    margin: 0 auto 25px;
}
#age-gate-title {
    font-size: 22px;
    font-weight: 700;
    color: #1C1C1C;
    margin: 0 0 10px;
}
#age-gate-subtitle {
    font-size: 14px;
    color: #666;
    margin: 0 0 15px;
}
#age-gate-warning {
    font-size: 10px;
    color: #aaa;
    margin: 0 0 30px;
    line-height: 1.6;
}
#age-gate-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
}
#age-gate-yes {
    background: #1C1C1C;
    color: #FFFFFF;
    border: none;
    padding: 14px 50px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    letter-spacing: 1px;
}
#age-gate-yes:hover {
    background: #D4AF37;
    color: #1C1C1C;
}
#age-gate-no {
    background: #FFFFFF;
    color: #1C1C1C;
    border: 2px solid #1C1C1C;
    padding: 14px 50px;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    letter-spacing: 1px;
}
#age-gate-no:hover {
    background: #f5f5f5;
}
@media (max-width: 480px) {
    #age-gate-modal { padding: 40px 25px; }
    #age-gate-buttons { flex-direction: column; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var overlay = document.getElementById('age-gate-overlay');
    var btnYes = document.getElementById('age-gate-yes');
    var btnNo = document.getElementById('age-gate-no');

    if (!overlay || !btnYes || !btnNo) return;

    // 首次進入顯示彈窗
    if (!sessionStorage.getItem('age_verified')) {
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    btnYes.addEventListener('click', function() {
        sessionStorage.setItem('age_verified', '1');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    });

    btnNo.addEventListener('click', function() {
        window.location.replace('https://www.google.com');
    });
});
</script>

<!-- Language toggle logic -->
<script>
(function() {
  var btn       = document.getElementById('lang-toggle-btn');
  var btnMobile = document.getElementById('lang-toggle-btn-mobile');
  if (!btn && !btnMobile) return;

  var isEN = localStorage.getItem('mth_lang') === 'en';

  function triggerGoogleTranslate(lang) {
    var combo = document.querySelector('.goog-te-combo');
    if (!combo) return false;
    combo.value = lang;
    combo.dispatchEvent(new Event('change'));
    combo.dispatchEvent(new Event('change', { bubbles: true }));
    return true;
  }

  function updateBtns() {
    var label = isEN ? 'EN ✓' : '中 / EN';
    document.body.classList.toggle('lang-en', isEN);
    if (btn) {
      btn.textContent = label;
      isEN ? btn.classList.add('en-active') : btn.classList.remove('en-active');
    }
    if (btnMobile) {
      btnMobile.textContent       = isEN ? 'EN ✓ 返回中文' : '切換英文 EN';
      btnMobile.style.background  = isEN ? '#D4AF37' : 'transparent';
      btnMobile.style.color       = isEN ? '#1C1C1C' : 'rgba(255,255,255,.75)';
      btnMobile.style.borderColor = isEN ? '#D4AF37' : 'rgba(212,175,55,.4)';
      btnMobile.style.fontWeight  = isEN ? '700' : 'normal';
    }
  }

  function applyEN() {
    // 嘗試觸發 Google Translate，唔成功就重試
    if (triggerGoogleTranslate('en')) return;
    var tries = 0;
    var t = setInterval(function() {
      if (triggerGoogleTranslate('en') || ++tries > 40) clearInterval(t);
    }, 250);
  }

  function toggle() {
    if (isEN) {
      // 返回中文：清除 googtrans cookie 再 reload
      localStorage.setItem('mth_lang', 'zh');
      document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
      document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=' + location.hostname + ';';
      document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=.' + location.hostname + ';';
      window.location.reload();
    } else {
      isEN = true;
      localStorage.setItem('mth_lang', 'en');
      updateBtns();
      applyEN();
    }
  }

  if (btn)       btn.addEventListener('click', toggle);
  if (btnMobile) btnMobile.addEventListener('click', toggle);

  // 頁面載入時恢復語言設定
  updateBtns();
  if (isEN) {
    // 等 Google Translate widget 完全初始化後觸發
    var initTries = 0;
    var initTimer = setInterval(function() {
      if (triggerGoogleTranslate('en') || ++initTries > 40) clearInterval(initTimer);
    }, 250);
  }
})();
</script>

<!-- 查詢購物車 Drawer -->
<div class="inquiry-overlay" id="inquiry-drawer" aria-hidden="true">
  <div class="inq-backdrop" data-action="close-cart"></div>
  <aside class="inq-panel" role="dialog" aria-label="查詢清單">
    <div class="inq-header">
      <h3>查詢清單</h3>
      <button class="inq-close" data-action="close-cart" aria-label="關閉">✕</button>
    </div>
    <div class="inq-list"></div>
    <div class="inq-actions">
      <button class="inq-btn-clear" data-action="clear-cart">清空</button>
      <button class="inq-btn-send" data-action="open-send">發送查詢 →</button>
    </div>
  </aside>
</div>

<!-- 產品快速預覽 Modal -->
<div class="quick-view-modal" id="quick-view-modal" aria-hidden="true">
  <div class="inq-backdrop" data-action="close-qv"></div>
  <div class="qv-box" role="dialog" aria-label="產品快速預覽">
    <button class="inq-close qv-close" data-action="close-qv" aria-label="關閉">✕</button>
    <div class="qv-body">
      <div class="qv-img-wrap"><img class="qv-img" src="" alt=""><span class="qv-flag"></span></div>
      <div class="qv-info">
        <div class="qv-cat"></div>
        <h2 class="qv-title"></h2>
        <div class="qv-en" translate="no"></div>
        <table class="qv-spec"></table>
        <div class="qv-actions">
          <a class="qv-more" href="#">查看完整詳情 →</a>
          <button class="qv-add" data-action="add-to-inquiry">＋ 加入查詢</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- 發送方式選擇 Modal -->
<div class="inquiry-modal" id="inquiry-send-modal" aria-hidden="true">
  <div class="inq-backdrop" data-action="close-send"></div>
  <div class="inq-modal-box" role="dialog" aria-label="選擇發送方式">
    <div class="inq-modal-head">
      <h3>發送查詢</h3>
      <button class="inq-close" data-action="close-send" aria-label="關閉">✕</button>
    </div>
    <p class="inq-modal-sub">揀以下任何方式發送你嘅查詢清單</p>
    <div class="inq-send-options">
      <button class="inq-send-opt opt-email" data-action="send-email">
        <span class="opt-icon">📧</span>
        <span class="opt-body"><strong>Email</strong><small>自動帶入清單到 info@mengtakhong.com</small></span>
      </button>
      <button class="inq-send-opt opt-fb" data-action="send-fb">
        <span class="opt-icon">f</span>
        <span class="opt-body"><strong>Facebook Messenger</strong><small>已複製清單，貼上即可發送</small></span>
      </button>
      <button class="inq-send-opt opt-ig" data-action="send-ig">
        <span class="opt-icon ig-grad">IG</span>
        <span class="opt-body"><strong>Instagram DM</strong><small>已複製清單，貼上即可發送</small></span>
      </button>
      <button class="inq-send-opt opt-copy" data-action="copy-only">
        <span class="opt-icon">📋</span>
        <span class="opt-body"><strong>只複製清單</strong><small>自己貼去任何地方</small></span>
      </button>
    </div>
  </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
