// ── 搜尋自動完成 ──
(function() {
  function makeAutocomplete(inputEl, panelEl) {
    if (!inputEl || !panelEl) return;
    var debounceTimer;
    var lastQuery = '';

    function hide() { panelEl.style.display = 'none'; panelEl.innerHTML = ''; }
    function show() { panelEl.style.display = 'block'; }

    function fetchSuggest(q) {
      var url = (typeof MTH_WP !== 'undefined' ? MTH_WP.home_url : '/') + 'wp-json/mth/v1/suggest?q=' + encodeURIComponent(q);
      fetch(url).then(function(r) { return r.json(); }).then(function(items) {
        if (inputEl.value.trim() !== q) return; // 已輸入新內容
        renderItems(items);
      }).catch(function() { hide(); });
    }

    function renderItems(items) {
      if (!items || !items.length) {
        panelEl.innerHTML = '<div class="ac-empty">未找到相關產品</div>';
        show();
        return;
      }
      var html = '';
      items.forEach(function(it) {
        var imgHtml = it.img ? '<img src="' + it.img + '" alt="" loading="lazy">' : '<span class="ac-noimg">🍾</span>';
        var nameEn  = it.name_en ? '<div class="ac-en">' + escapeHtml(it.name_en) + '</div>' : '';
        var flag    = it.flag ? '<span class="ac-flag">' + escapeHtml(it.flag) + '</span>' : '';
        html += '<a class="ac-item" href="' + it.url + '">' +
                '<div class="ac-img">' + imgHtml + '</div>' +
                '<div class="ac-body"><div class="ac-zh">' + escapeHtml(it.title) + flag + '</div>' + nameEn + '</div>' +
                '</a>';
      });
      panelEl.innerHTML = html;
      show();
    }

    function escapeHtml(s) {
      return String(s).replace(/[&<>"']/g, function(c) {
        return ({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' })[c];
      });
    }

    inputEl.addEventListener('input', function() {
      var q = this.value.trim();
      if (q === lastQuery) return;
      lastQuery = q;
      clearTimeout(debounceTimer);
      if (q.length < 1) { hide(); return; }
      debounceTimer = setTimeout(function() { fetchSuggest(q); }, 180);
    });

    inputEl.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' && this.value.trim()) {
        var base = (typeof MTH_WP !== 'undefined') ? MTH_WP.search_url : '/?s=';
        window.location.href = base + encodeURIComponent(this.value.trim());
      } else if (e.key === 'Escape') {
        hide(); this.blur();
      }
    });

    inputEl.addEventListener('focus', function() {
      if (this.value.trim().length >= 1 && panelEl.innerHTML) show();
    });

    document.addEventListener('click', function(e) {
      if (!panelEl.contains(e.target) && e.target !== inputEl) hide();
    });
  }

  // 桌面導航搜尋
  var desktopInput = document.getElementById('global-search');
  if (desktopInput && desktopInput.parentElement) {
    var panel = document.createElement('div');
    panel.className = 'search-autocomplete';
    panel.style.cssText = 'display:none;position:absolute;top:100%;right:0;width:340px;max-height:480px;overflow-y:auto;background:#fff;border:1px solid #E5DDD0;border-radius:8px;box-shadow:0 8px 24px rgba(0,0,0,.18);z-index:10000;margin-top:6px;';
    desktopInput.parentElement.style.position = 'relative';
    desktopInput.parentElement.appendChild(panel);
    makeAutocomplete(desktopInput, panel);
  }

  // 手機搜尋 overlay
  var mobileInput = document.getElementById('mobile-search-input');
  if (mobileInput) {
    var mPanel = document.createElement('div');
    mPanel.className = 'search-autocomplete search-autocomplete-mobile';
    mPanel.style.cssText = 'display:none;margin-top:16px;background:#1C1C1C;border:1px solid rgba(212,175,55,.25);border-radius:10px;max-height:60vh;overflow-y:auto;';
    var form = mobileInput.closest('.mobile-search-form');
    if (form && form.parentElement) form.parentElement.appendChild(mPanel);
    makeAutocomplete(mobileInput, mPanel);
  }
})();

// ── 分類頁：本地搜尋 + 篩選 + 排序（統一）──
document.addEventListener('DOMContentLoaded', function() {
  var grid = document.getElementById('product-grid');
  if (!grid) return;
  // 支援新結構（.prod-card-wrap）同舊結構（.prod-card 直接係 grid child）
  var cards = Array.prototype.slice.call(grid.querySelectorAll('.prod-card-wrap, .prod-card'));
  cards = cards.filter(function(c) { return c.parentElement === grid; });
  if (!cards.length) return;

  var searchInput = document.getElementById('cat-search');
  var counter     = document.getElementById('search-count');
  var noResults   = document.getElementById('no-results');
  var sortSelect  = document.getElementById('cat-sort');
  var filterPanel = document.getElementById('cat-filter');

  var state = { q: '', country: '', source: '', abvMin: 0, abvMax: 100, brand: '', type: '' };

  function getCardData(c) {
    return {
      text:    (c.getAttribute('data-search') || '').toLowerCase(),
      country: c.getAttribute('data-country') || '',
      source:  c.getAttribute('data-source') || '',
      abv:     parseFloat(c.getAttribute('data-abv')) || 0,
      title:   (c.getAttribute('data-title') || '').toLowerCase(),
      date:    parseInt(c.getAttribute('data-date'), 10) || 0,
      brand:   c.getAttribute('data-brand') || '',
      types:   (c.getAttribute('data-types') || '').split(',').filter(Boolean),
    };
  }

  function applyFilter() {
    var visible = 0;
    cards.forEach(function(c) {
      var d = getCardData(c);
      var show =
        (state.q === '' || d.text.indexOf(state.q) !== -1) &&
        (state.country === '' || d.country === state.country) &&
        (state.source === '' || d.source === state.source) &&
        (state.brand === '' || d.brand === state.brand) &&
        (state.type === '' || d.types.indexOf(state.type) !== -1) &&
        (d.abv >= state.abvMin && d.abv <= state.abvMax);
      c.style.display = show ? '' : 'none';
      if (show) visible++;
    });
    if (counter) counter.textContent = '顯示 ' + visible + ' / ' + cards.length + ' 個產品';
    if (noResults) noResults.style.display = visible === 0 ? 'block' : 'none';
  }

  // 排序用：normalize 標題以將同品牌產品歸類埋一齊
  // - 去掉「日本」、「兵庫縣產米」等通用前綴
  // - 統一日文/繁體異體字（桜→櫻、醸→釀）
  // - 同品牌（神戶 / 白鶴 / 浜福鶴 等）會自動排埋一齊
  function normalizeSortTitle(t) {
    if (!t) return '';
    return t
      .replace(/^日本\s*/, '')
      .replace(/^兵庫縣產米\s*/, '')
      .replace(/桜/g, '櫻')
      .replace(/醸/g, '釀')
      .trim();
  }

  function applySort() {
    if (!sortSelect) return;
    var v = sortSelect.value;
    var sorted = cards.slice().sort(function(a, b) {
      var da = getCardData(a), db = getCardData(b);
      switch (v) {
        case 'abv-desc': return db.abv - da.abv;
        case 'abv-asc':  return da.abv - db.abv;
        case 'newest':   return db.date - da.date;
        default:         return normalizeSortTitle(da.title).localeCompare(normalizeSortTitle(db.title), 'zh-Hant');
      }
    });
    sorted.forEach(function(c) { grid.appendChild(c); });
  }

  // 搜尋
  if (searchInput) {
    var debounce;
    searchInput.addEventListener('input', function() {
      clearTimeout(debounce);
      var self = this;
      debounce = setTimeout(function() {
        state.q = self.value.toLowerCase().trim();
        applyFilter();
      }, 80);
    });
  }

  // 篩選
  if (filterPanel) {
    var backdrop = document.getElementById('cat-filter-backdrop');
    var toggle = document.getElementById('cat-filter-toggle');
    var closeBtn = document.getElementById('cat-filter-close');

    function isMobileLayout() { return window.innerWidth <= 900; }

    function openFilterPanel() {
      filterPanel.classList.add('open');
      if (backdrop) backdrop.classList.add('open');
      if (isMobileLayout()) document.body.style.overflow = 'hidden';
    }
    function closeFilterPanel() {
      filterPanel.classList.remove('open');
      if (backdrop) backdrop.classList.remove('open');
      document.body.style.overflow = '';
    }

    filterPanel.addEventListener('change', function(e) {
      var t = e.target;
      if (t.name === 'filter-country') state.country = t.value;
      if (t.name === 'filter-source')  state.source  = t.value;
      if (t.name === 'filter-brand')   state.brand   = t.value;
      if (t.name === 'filter-type')    state.type    = t.value;
      if (t.name === 'filter-abv') {
        var range = t.value.split('-');
        state.abvMin = parseFloat(range[0]) || 0;
        state.abvMax = parseFloat(range[1]) || 100;
      }
      applyFilter();

      // 手機版：揀完選項自動關閉 panel（延遲少少俾用家睇到結果）
      if (isMobileLayout()) {
        setTimeout(closeFilterPanel, 280);
      }
    });

    var clearBtn = document.getElementById('cat-filter-clear');
    if (clearBtn) clearBtn.addEventListener('click', function() {
      state.country = ''; state.source = ''; state.brand = ''; state.type = '';
      state.abvMin = 0; state.abvMax = 100;
      filterPanel.querySelectorAll('input[type=radio]').forEach(function(i) { i.checked = false; });
      filterPanel.querySelectorAll('input[value=""]').forEach(function(i) { i.checked = true; });
      filterPanel.querySelectorAll('input[value="0-100"]').forEach(function(i) { i.checked = true; });
      applyFilter();
    });

    // Toggle button (手機版「篩選」按鈕)
    if (toggle) toggle.addEventListener('click', function() {
      if (filterPanel.classList.contains('open')) closeFilterPanel();
      else openFilterPanel();
    });

    // X 關閉按鈕
    if (closeBtn) closeBtn.addEventListener('click', closeFilterPanel);

    // 點背景關閉
    if (backdrop) backdrop.addEventListener('click', closeFilterPanel);

    // ESC 鍵關閉
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && filterPanel.classList.contains('open')) {
        closeFilterPanel();
      }
    });

    // Resize 時：如果由手機切返桌面，自動清除 mobile overlay state
    window.addEventListener('resize', function() {
      if (!isMobileLayout()) {
        document.body.style.overflow = '';
        if (backdrop) backdrop.classList.remove('open');
      }
    });
  }

  // 排序
  if (sortSelect) {
    sortSelect.addEventListener('change', applySort);
    applySort(); // 開頁就自動按名稱排序，將同品牌歸類埋一齊
  }

  applyFilter();
});

// ── 查詢購物車 ──
(function() {
  var STORAGE_KEY = 'mth_inquiry_cart';

  function getCart() {
    try { return JSON.parse(localStorage.getItem(STORAGE_KEY)) || []; }
    catch (e) { return []; }
  }
  function saveCart(items) { localStorage.setItem(STORAGE_KEY, JSON.stringify(items)); updateBadge(); }
  function updateBadge() {
    var n = getCart().length;
    document.querySelectorAll('.cart-badge').forEach(function(b) {
      b.textContent = n;
      b.style.display = n > 0 ? 'flex' : 'none';
    });
  }

  window.MTHCart = {
    add: function(item) {
      var items = getCart();
      if (items.some(function(x) { return x.id === item.id; })) {
        showToast('呢隻產品已喺查詢清單中');
        return;
      }
      items.push(item);
      saveCart(items);
      showToast('已加入查詢清單');
    },
    remove: function(id) {
      saveCart(getCart().filter(function(x) { return x.id !== id; }));
      renderDrawer();
    },
    clear: function() { saveCart([]); renderDrawer(); },
    items: getCart,
  };

  function showToast(msg) {
    var t = document.createElement('div');
    t.textContent = msg;
    t.style.cssText = 'position:fixed;bottom:90px;left:50%;transform:translateX(-50%);background:#1C1C1C;color:#D4AF37;padding:10px 22px;border-radius:24px;font-size:.85rem;z-index:100000;box-shadow:0 4px 16px rgba(0,0,0,.3);opacity:0;transition:opacity .2s;';
    document.body.appendChild(t);
    requestAnimationFrame(function() { t.style.opacity = '1'; });
    setTimeout(function() { t.style.opacity = '0'; setTimeout(function() { t.remove(); }, 200); }, 1600);
  }

  function buildInquiryText() {
    var items = getCart();
    if (!items.length) return '';
    var lines = ['你好，我想查詢以下產品報價：', ''];
    items.forEach(function(it, i) {
      lines.push((i + 1) + '. ' + it.title);
      if (it.spec || it.abv) {
        var meta = [];
        if (it.spec) meta.push(it.spec);
        if (it.abv) meta.push(it.abv + '%');
        lines.push('   規格：' + meta.join(' / '));
      }
      lines.push('   連結：' + it.url);
      lines.push('');
    });
    lines.push('請回覆最新箱價，謝謝！');
    lines.push('');
    lines.push('— 透過 mengtakhong-mo.com 查詢');
    return lines.join('\n');
  }

  function renderDrawer() {
    var drawer = document.getElementById('inquiry-drawer');
    if (!drawer) return;
    var list = drawer.querySelector('.inq-list');
    var items = getCart();
    if (!items.length) {
      list.innerHTML = '<div class="inq-empty">查詢清單係空嘅<br><span>瀏覽產品時按「＋ 加入查詢」</span></div>';
      drawer.querySelector('.inq-actions').style.display = 'none';
      return;
    }
    var html = '';
    items.forEach(function(it) {
      html += '<div class="inq-item" data-id="' + it.id + '">';
      html += '<div class="inq-item-body"><div class="inq-item-title">' + escapeHtml(it.title) + '</div>';
      if (it.spec || it.abv) {
        var meta = [];
        if (it.spec) meta.push(it.spec);
        if (it.abv) meta.push(it.abv + '%');
        html += '<div class="inq-item-meta">' + escapeHtml(meta.join(' · ')) + '</div>';
      }
      html += '</div><button class="inq-remove" data-id="' + it.id + '" aria-label="移除">✕</button></div>';
    });
    list.innerHTML = html;
    drawer.querySelector('.inq-actions').style.display = 'flex';
    list.querySelectorAll('.inq-remove').forEach(function(btn) {
      btn.addEventListener('click', function() {
        MTHCart.remove(parseInt(this.getAttribute('data-id'), 10));
      });
    });
  }

  function escapeHtml(s) {
    return String(s).replace(/[&<>"']/g, function(c) {
      return ({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' })[c];
    });
  }

  function openDrawer() {
    var d = document.getElementById('inquiry-drawer');
    if (!d) return;
    renderDrawer();
    d.classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function closeDrawer() {
    var d = document.getElementById('inquiry-drawer');
    if (!d) return;
    d.classList.remove('open');
    document.body.style.overflow = '';
  }

  function copyToClipboard(text) {
    if (navigator.clipboard) return navigator.clipboard.writeText(text);
    return new Promise(function(resolve) {
      var ta = document.createElement('textarea');
      ta.value = text; ta.style.position = 'fixed'; ta.style.left = '-9999px';
      document.body.appendChild(ta); ta.select();
      try { document.execCommand('copy'); resolve(); } catch (e) { resolve(); }
      ta.remove();
    });
  }

  function openSendModal() {
    var text = buildInquiryText();
    if (!text) return;
    var modal = document.getElementById('inquiry-send-modal');
    if (!modal) return;
    modal.classList.add('open');
  }
  function closeSendModal() {
    var m = document.getElementById('inquiry-send-modal');
    if (m) m.classList.remove('open');
  }

  document.addEventListener('click', function(e) {
    var t = e.target.closest('[data-action]');
    if (!t) return;
    var action = t.getAttribute('data-action');
    var text = buildInquiryText();

    if (action === 'open-cart')   { openDrawer(); }
    else if (action === 'close-cart') { closeDrawer(); }
    else if (action === 'clear-cart') {
      if (confirm('清空查詢清單？')) MTHCart.clear();
    }
    else if (action === 'open-send') { closeDrawer(); openSendModal(); }
    else if (action === 'close-send') { closeSendModal(); }
    else if (action === 'send-email') {
      var subj = '產品查詢 — 共 ' + getCart().length + ' 項';
      window.location.href = 'mailto:info@mengtakhong.com?subject=' +
        encodeURIComponent(subj) + '&body=' + encodeURIComponent(text);
    }
    else if (action === 'send-fb') {
      copyToClipboard(text).then(function() {
        showToast('清單已複製，請喺 Messenger 貼上');
        setTimeout(function() {
          window.open('https://m.me/mengtakhong', '_blank');
        }, 600);
      });
    }
    else if (action === 'send-ig') {
      copyToClipboard(text).then(function() {
        showToast('清單已複製，請喺 Instagram 貼上');
        setTimeout(function() {
          window.open('https://ig.me/m/mengtakhong.mo', '_blank');
        }, 600);
      });
    }
    else if (action === 'copy-only') {
      copyToClipboard(text).then(function() { showToast('清單已複製到剪貼板'); });
    }
    else if (action === 'add-to-inquiry') {
      var item = {
        id:    parseInt(t.getAttribute('data-id'), 10),
        title: t.getAttribute('data-title'),
        url:   t.getAttribute('data-url'),
        spec:  t.getAttribute('data-spec') || '',
        abv:   t.getAttribute('data-abv') || '',
      };
      MTHCart.add(item);
    }
  });

  // ESC 關閉
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { closeDrawer(); closeSendModal(); }
  });

  updateBadge();
})();

// ── 產品快速預覽 Modal ──
(function() {
  function getCountryZh(key) {
    var map = {
      scotland:'蘇格蘭',ireland:'愛爾蘭',canada:'加拿大',usa:'美國',
      france:'法國',spain:'西班牙',portugal:'葡萄牙',italy:'意大利',
      chile:'智利',australia:'澳洲',japan:'日本',china:'中國',
      korea:'韓國',taiwan:'台灣',thailand:'泰國',vietnam:'越南',
    };
    return map[key] || '';
  }

  function openQuickView(btn) {
    var modal = document.getElementById('quick-view-modal');
    if (!modal) return;
    var d = {
      id:      btn.getAttribute('data-id'),
      title:   btn.getAttribute('data-title'),
      en:      btn.getAttribute('data-en'),
      url:     btn.getAttribute('data-url'),
      img:     btn.getAttribute('data-img'),
      spec:    btn.getAttribute('data-spec'),
      abv:     btn.getAttribute('data-abv'),
      source:  btn.getAttribute('data-source'),
      flag:    btn.getAttribute('data-flag'),
      country: btn.getAttribute('data-country'),
      cat:     btn.getAttribute('data-cat'),
    };

    modal.querySelector('.qv-img').src = d.img;
    modal.querySelector('.qv-img').alt = d.title;
    modal.querySelector('.qv-flag').textContent = d.flag || '';
    modal.querySelector('.qv-flag').style.display = d.flag ? '' : 'none';
    modal.querySelector('.qv-cat').textContent = d.cat || '';
    modal.querySelector('.qv-title').textContent = d.title;
    var enEl = modal.querySelector('.qv-en');
    enEl.textContent = d.en || '';
    enEl.style.display = d.en ? '' : 'none';
    modal.querySelector('.qv-more').href = d.url;

    var rows = [];
    if (d.spec)   rows.push(['規格', d.spec]);
    if (d.abv)    rows.push(['酒精度', d.abv + '%']);
    if (d.source) rows.push(['來源', d.source]);
    var country_name = getCountryZh(d.country);
    if (country_name) rows.push(['原產國', (d.flag ? d.flag + ' ' : '') + country_name]);
    if (d.cat)    rows.push(['分類', d.cat]);
    var html = '';
    rows.forEach(function(r) {
      html += '<tr><td>' + r[0] + '</td><td>' + r[1] + '</td></tr>';
    });
    modal.querySelector('.qv-spec').innerHTML = html;

    var addBtn = modal.querySelector('.qv-add');
    addBtn.setAttribute('data-id', d.id);
    addBtn.setAttribute('data-title', d.title);
    addBtn.setAttribute('data-url', d.url);
    addBtn.setAttribute('data-spec', d.spec || '');
    addBtn.setAttribute('data-abv', d.abv || '');

    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function closeQuickView() {
    var m = document.getElementById('quick-view-modal');
    if (m) { m.classList.remove('open'); document.body.style.overflow = ''; }
  }

  document.addEventListener('click', function(e) {
    var btn = e.target.closest('[data-action="quick-view"]');
    if (btn) { e.preventDefault(); openQuickView(btn); return; }
    var cl = e.target.closest('[data-action="close-qv"]');
    if (cl) { closeQuickView(); }
  });
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeQuickView();
  });
})();

// ── Newsletter 提交回饋（URL ?nl=ok / ?nl=error） ──
(function() {
  var params = new URLSearchParams(window.location.search);
  var nl = params.get('nl');
  if (!nl) return;
  var msg = document.getElementById('nl-msg');
  var form = document.getElementById('newsletter-form');
  if (!msg) return;
  if (nl === 'ok') {
    msg.textContent = '✓ 多謝訂閱！我哋會喺有新到貨時通知你。';
    msg.className = 'nl-msg ok';
    if (form) form.reset();
  } else {
    msg.textContent = '訂閱失敗，請填寫所有欄位再試。';
    msg.className = 'nl-msg err';
  }
  // 5 秒後清除 URL param
  setTimeout(function() {
    var url = window.location.pathname + window.location.hash;
    history.replaceState({}, '', url);
  }, 5000);
})();

// ── 最近瀏覽（純 localStorage） ──
(function() {
  var STORAGE_KEY = 'mth_recently_viewed';
  var MAX_ITEMS = 12;

  function get() {
    try { return JSON.parse(localStorage.getItem(STORAGE_KEY)) || []; }
    catch (e) { return []; }
  }
  function save(items) { localStorage.setItem(STORAGE_KEY, JSON.stringify(items)); }

  function escapeHtml(s) {
    return String(s == null ? '' : s).replace(/[&<>"']/g, function(c) {
      return ({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' })[c];
    });
  }

  document.addEventListener('DOMContentLoaded', function() {
    var section = document.getElementById('mth-recently-viewed');
    if (!section) return;

    var currentJSON = section.getAttribute('data-current');
    var current;
    try { current = JSON.parse(currentJSON); } catch (e) { current = null; }
    if (!current || !current.id) return;

    // 1. 將當前產品 prepend 到 list（先移除舊嘅）
    var items = get().filter(function(it) { return it.id !== current.id; });
    items.unshift(current);
    if (items.length > MAX_ITEMS) items = items.slice(0, MAX_ITEMS);
    save(items);

    // 2. 渲染（排除當前產品）
    var others = items.filter(function(it) { return it.id !== current.id; });
    if (!others.length) return;

    var track = document.getElementById('rv-track');
    if (!track) return;

    var html = '';
    others.forEach(function(it) {
      var imgHtml = it.img
        ? '<img src="' + escapeHtml(it.img) + '" alt="' + escapeHtml(it.title) + '" loading="lazy">'
        : '<span class="rv-noimg">🍾</span>';
      var flag = it.flag ? '<span class="rv-flag">' + escapeHtml(it.flag) + '</span>' : '';
      var meta = '';
      if (it.spec || it.abv) {
        var parts = [];
        if (it.spec) parts.push(it.spec);
        if (it.abv) parts.push(it.abv + '%');
        meta = '<div class="rv-meta">' + escapeHtml(parts.join(' · ')) + '</div>';
      }
      html += '<a href="' + escapeHtml(it.url) + '" class="rv-card">' +
              '<div class="rv-img">' + imgHtml + flag + '</div>' +
              '<div class="rv-body"><div class="rv-name">' + escapeHtml(it.title) + '</div>' + meta + '</div>' +
              '</a>';
    });
    track.innerHTML = html;
    section.style.display = '';
  });
})();
