<?php
// Load configuration
require_once __DIR__ . '/config.php';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Onderhoudsrapport — Airco Beheer</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&family=DM+Mono:wght@400;500;700&display=swap" rel="stylesheet" />
  <style>
    /* ── TOKENS ─────────────────────────────────────────────── */
    :root {
      --bg:           #13293E;
      --bg-card:      #0e2030;
      --accent:       #1C9ADB;
      --text:         #FFFFFF;
      --muted:        rgba(255,255,255,0.72);
      --border:       rgba(226,230,234,0.16);
      --cta-fill:     #FFC400;
      --cta-text:     #2B2B2B;
      --green:        #22C55E;
      --green-bg:     rgba(34,197,94,0.12);
      --green-border: rgba(34,197,94,0.35);
      --amber:        #F59E0B;
      --amber-bg:     rgba(245,158,11,0.12);
      --font-main:    'Sora', sans-serif;
      --font-mono:    'DM Mono', monospace;
    }

    /* ── RESET ──────────────────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body {
      background: var(--bg);
      color: var(--text);
      font-family: var(--font-main);
      -webkit-font-smoothing: antialiased;
      min-height: 100vh;
    }
    a { color: var(--accent); text-decoration: none; }
    a:hover { text-decoration: underline; }
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: var(--bg-card); }
    ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 3px; }

    /* ── LAYOUT ─────────────────────────────────────────────── */
    .page-wrap { max-width: 740px; margin: 0 auto; padding: 0 24px; }

    /* ── HEADER ─────────────────────────────────────────────── */
    .site-header {
      border-bottom: 1px solid var(--border);
      padding: 16px 0;
      position: sticky;
      top: 0;
      background: var(--bg);
      z-index: 50;
    }
    .site-header .page-wrap {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .logo {
      font-family: var(--font-mono);
      font-size: 14px;
      font-weight: 700;
      letter-spacing: 0.1em;
      color: var(--text);
    }
    .header-link { font-size: 13px; color: var(--muted); }

    /* ── MAIN ───────────────────────────────────────────────── */
    main { padding: 40px 0 80px; }
    .stack { display: flex; flex-direction: column; gap: 20px; }

    /* ── CARD ───────────────────────────────────────────────── */
    .card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 28px 32px;
      animation: fadeUp 0.4s ease both;
    }
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(12px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .card:nth-child(1) { animation-delay: 0.05s; }
    .card:nth-child(2) { animation-delay: 0.10s; }
    .card:nth-child(3) { animation-delay: 0.15s; }
    .card:nth-child(4) { animation-delay: 0.20s; }
    .card:nth-child(5) { animation-delay: 0.25s; }
    .card:nth-child(6) { animation-delay: 0.30s; }

    /* ── SECTION LABEL ──────────────────────────────────────── */
    .section-label {
      font-family: var(--font-mono);
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      color: var(--accent);
      margin-bottom: 12px;
      display: block;
    }

    /* ── STATUS BADGE ───────────────────────────────────────── */
    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      border-radius: 6px;
      padding: 4px 12px;
      font-family: var(--font-mono);
      font-size: 13px;
      font-weight: 700;
      letter-spacing: 0.04em;
    }
    .status-badge .dot {
      width: 7px; height: 7px;
      border-radius: 50%;
      display: inline-block;
    }
    .status-A { background: rgba(34,197,94,0.12);  border: 1px solid #22C55E; color: #22C55E; }
    .status-A .dot { background: #22C55E; }
    .status-B { background: rgba(28,154,219,0.12); border: 1px solid var(--accent); color: var(--accent); }
    .status-B .dot { background: var(--accent); }
    .status-C { background: rgba(245,158,11,0.12); border: 1px solid #F59E0B; color: #F59E0B; }
    .status-C .dot { background: #F59E0B; }

    /* ── RAPPORT OVERZICHT ──────────────────────────────────── */
    .rapport-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      flex-wrap: wrap;
      gap: 12px;
      margin-bottom: 16px;
    }
    .rapport-title { font-size: 22px; font-weight: 700; margin-bottom: 4px; }
    .rapport-meta  { font-family: var(--font-mono); font-size: 13px; color: var(--muted); }
    .rapport-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
      border-top: 1px solid var(--border);
      padding-top: 16px;
    }
    .rapport-grid p { font-size: 14px; color: var(--muted); line-height: 1.6; }
    .rapport-timing {
      font-family: var(--font-mono);
      font-size: 12px;
      color: var(--accent);
      margin-top: 8px;
      display: block;
    }

    /* ── BULLET LIST ────────────────────────────────────────── */
    .bullet-list { list-style: none; display: flex; flex-direction: column; gap: 10px; }
    .bullet-list li {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      color: var(--muted);
      font-size: 15px;
      line-height: 1.5;
    }
    .bullet-list li::before {
      content: '';
      margin-top: 7px;
      width: 6px; height: 6px;
      border-radius: 50%;
      background: var(--accent);
      flex-shrink: 0;
      display: block;
    }

    /* ── PHOTO GRID ─────────────────────────────────────────── */
    .photo-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 12px;
    }
    .photo-item { display: flex; flex-direction: column; gap: 6px; }
    .photo-thumb {
      aspect-ratio: 16/9;
      border-radius: 8px;
      overflow: hidden;
      border: 1px solid var(--border);
      cursor: zoom-in;
    }
    .photo-thumb img {
      width: 100%; height: 100%;
      object-fit: cover;
      transition: transform 0.2s ease;
      display: block;
    }
    .photo-thumb:hover img { transform: scale(1.04); }
    .photo-label { font-size: 12px; color: var(--muted); text-align: center; }

    /* ── LIGHTBOX ───────────────────────────────────────────── */
    .lightbox {
      display: none;
      position: fixed; inset: 0;
      background: rgba(0,0,0,0.92);
      z-index: 200;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .lightbox.open { display: flex; }
    .lightbox-inner { max-width: 900px; width: 100%; position: relative; }
    .lightbox-inner img { width: 100%; border-radius: 10px; max-height: 80vh; object-fit: contain; display: block; }
    .lightbox-caption { text-align: center; color: var(--muted); font-size: 13px; margin-top: 10px; }
    .lightbox-nav {
      display: flex;
      justify-content: center;
      gap: 12px;
      margin-top: 12px;
    }
    .lightbox-nav button, .lightbox-close {
      background: rgba(255,255,255,0.1);
      border: none;
      border-radius: 6px;
      padding: 8px 16px;
      color: #fff;
      cursor: pointer;
      font-family: var(--font-main);
      font-size: 13px;
      transition: background 0.15s;
    }
    .lightbox-nav button:hover, .lightbox-close:hover { background: rgba(255,255,255,0.2); }
    .lightbox-nav button:disabled { opacity: 0.3; cursor: default; }
    .lightbox-close {
      position: absolute;
      top: -14px; right: -14px;
      border-radius: 50%;
      width: 32px; height: 32px;
      padding: 0;
      font-size: 18px;
      display: flex; align-items: center; justify-content: center;
    }

    /* ── MONTEUR OPMERKING ──────────────────────────────────── */
    .monteur-note {
      font-size: 14px;
      color: var(--muted);
      line-height: 1.6;
      font-style: italic;
    }

    /* ── UPGRADE CARD ───────────────────────────────────────── */
    .upgrade-card {
      border-color: rgba(255,196,0,0.2) !important;
      background: linear-gradient(135deg, #0e2030 0%, #131f2e 100%) !important;
    }
    .upgrade-card h2 { font-size: 18px; font-weight: 700; margin-bottom: 8px; }
    .upgrade-card .sub { font-size: 14px; color: var(--muted); line-height: 1.6; }
    .upgrade-benefits {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
      margin-top: 4px;
    }
    .upgrade-benefits .benefit {
      display: flex; align-items: center; gap: 8px;
      font-size: 13px; color: var(--muted);
    }
    .upgrade-benefits .check { color: var(--cta-fill); font-size: 14px; flex-shrink: 0; }

    /* ── BUTTON ─────────────────────────────────────────────── */
    .btn-primary {
      background: var(--cta-fill);
      color: var(--cta-text);
      border: none;
      border-radius: 8px;
      padding: 14px 28px;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      font-family: var(--font-main);
      letter-spacing: 0.01em;
      transition: opacity 0.15s, transform 0.1s;
      display: inline-block;
    }
    .btn-primary:hover { opacity: 0.88; }
    .btn-primary:active { transform: scale(0.98); }

    /* ── INLINE ALERT ───────────────────────────────────────── */
    .inline-alert {
      background: var(--green-bg);
      border: 1px solid var(--green-border);
      border-radius: 8px;
      padding: 14px 20px;
      color: var(--green);
      font-size: 15px;
      font-weight: 500;
      display: flex; align-items: center; gap: 10px;
    }

    /* ── LOADING / ERROR STATES ─────────────────────────────── */
    .state-screen {
      display: flex; align-items: center; justify-content: center;
      min-height: 60vh; flex-direction: column; gap: 12px; text-align: center;
    }
    .state-icon { font-size: 48px; margin-bottom: 8px; }
    .state-title { font-size: 20px; font-weight: 700; }
    .state-sub { font-size: 14px; color: var(--muted); }
    .spinner {
      width: 32px; height: 32px;
      border: 3px solid var(--border);
      border-top-color: var(--accent);
      border-radius: 50%;
      animation: spin 0.7s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ── FOOTER ─────────────────────────────────────────────── */
    .site-footer {
      border-top: 1px solid var(--border);
      padding: 24px;
    }
    .site-footer .page-wrap {
      display: flex; justify-content: space-between;
      align-items: center; flex-wrap: wrap; gap: 8px;
    }
    .footer-links { display: flex; gap: 20px; }
    .footer-links a { font-size: 13px; color: var(--muted); }
    .footer-copy {
      font-family: var(--font-mono);
      font-size: 12px;
      color: rgba(255,255,255,0.3);
    }

    /* ── RESPONSIVE ─────────────────────────────────────────── */
    @media (max-width: 600px) {
      .card { padding: 20px 18px; }
      .rapport-grid { grid-template-columns: 1fr; }
      .upgrade-benefits { grid-template-columns: 1fr; }
      .photo-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); }
    }
  </style>
</head>
<body>

  <!-- HEADER -->
  <header class="site-header">
    <div class="page-wrap">
      <span class="logo">AIRCO BEHEER</span>
      <a class="header-link" href="mailto:info@aircobeheer.nl">Hulp nodig?</a>
    </div>
  </header>

  <!-- MAIN -->
  <main>
    <div class="page-wrap">

      <!-- Loading state -->
      <div id="state-loading" class="state-screen">
        <div class="spinner"></div>
        <p class="state-sub" style="font-family:var(--font-mono);font-size:13px;">Rapport laden…</p>
      </div>

      <!-- Not found state -->
      <div id="state-notfound" class="state-screen" style="display:none;">
        <div class="state-icon">🔍</div>
        <h1 class="state-title">Rapport niet gevonden</h1>
        <p class="state-sub">Controleer de link in uw e-mail of neem contact op.</p>
        <a href="mailto:info@aircobeheer.nl" style="font-size:14px;margin-top:8px;">info@aircobeheer.nl</a>
      </div>

      <!-- Error state -->
      <div id="state-error" class="state-screen" style="display:none;">
        <div class="state-icon">⚠️</div>
        <h1 class="state-title">Er is iets misgegaan</h1>
        <p class="state-sub" id="error-message">Probeer de pagina te vernieuwen.</p>
      </div>

      <!-- Report content (hidden until loaded) -->
      <div id="rapport-content" class="stack" style="display:none;">

        <!-- 1. RAPPORT OVERZICHT -->
        <div class="card" id="card-overzicht">
          <div class="rapport-header">
            <div>
              <h1 class="rapport-title">Onderhoudsrapport</h1>
              <p class="rapport-meta" id="rapport-meta">—</p>
            </div>
            <span class="status-badge" id="status-badge">
              <span class="dot"></span>
              <span id="status-text">—</span>
            </span>
          </div>
          <div class="rapport-grid">
            <div>
              <span class="section-label">Bevindingen</span>
              <p id="rapport-uitleg">—</p>
            </div>
            <div>
              <span class="section-label">Aanbevolen vervolgstap</span>
              <p id="rapport-vervolgstap">—</p>
              <span class="rapport-timing" id="rapport-timing">—</span>
            </div>
          </div>
        </div>

        <!-- 2. WERKZAAMHEDEN -->
        <div class="card">
          <span class="section-label">Uitgevoerde werkzaamheden</span>
          <ul class="bullet-list" id="werkzaamheden-list"></ul>
        </div>

        <!-- 3. FOTO'S -->
        <div class="card" id="card-fotos" style="display:none;">
          <span class="section-label">Foto's</span>
          <div class="photo-grid" id="photo-grid"></div>
        </div>

        <!-- 4. OPMERKING MONTEUR -->
        <div class="card" id="card-opmerking" style="display:none; padding: 20px 32px;">
          <span class="section-label">Opmerking monteur</span>
          <p class="monteur-note" id="monteur-opmerking">—</p>
        </div>

        <!-- 5. UPGRADE BLOK -->
        <div class="card upgrade-card">
          <h2>Wilt u dit onderhoud jaarlijks automatisch laten uitvoeren?</h2>
          <p class="sub" style="margin-bottom:20px;">
            Met jaarlijks onderhoud blijven prestaties optimaal en worden aandachtspunten tijdig
            opgevolgd — u hoeft niets meer te plannen.
          </p>
          <div class="upgrade-benefits">
            <div class="benefit"><span class="check">✓</span> Automatische jaarlijkse planning</div>
            <div class="benefit"><span class="check">✓</span> Prioriteit bij storingen</div>
            <div class="benefit"><span class="check">✓</span> Vast aanspreekpunt</div>
            <div class="benefit"><span class="check">✓</span> Proactieve opvolging bij aandachtspunten</div>
          </div>
          <div id="cta-area" style="margin-top:20px;">
            <button class="btn-primary" id="cta-btn" onclick="activateContract()">
              Activeer jaarlijks onderhoud
            </button>
          </div>
        </div>

      </div><!-- /rapport-content -->
    </div>
  </main>

  <!-- LIGHTBOX -->
  <div class="lightbox" id="lightbox" onclick="closeLightbox(event)">
    <div class="lightbox-inner">
      <button class="lightbox-close" onclick="closeLightboxBtn()">×</button>
      <img id="lightbox-img" src="" alt="" />
      <p class="lightbox-caption" id="lightbox-caption"></p>
      <div class="lightbox-nav">
        <button id="lb-prev" onclick="lightboxNav(-1)">← Vorige</button>
        <button id="lb-next" onclick="lightboxNav(1)">Volgende →</button>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <footer class="site-footer">
    <div class="page-wrap">
      <div class="footer-links">
        <a href="tel:+31201234567">020 123 4567</a>
        <a href="mailto:info@aircobeheer.nl">info@aircobeheer.nl</a>
      </div>
      <span class="footer-copy">© 2026 AIRCO BEHEER</span>
    </div>
  </footer>

  <script>
    // ── CONFIG ─────────────────────────────────────────────────────────────
    // Base URL from PHP config
    const BASE_URL = '<?php echo rtrim(BASE_URL, "/"); ?>';
    const API_BASE = 'api.php';

    // ── STATUS CONFIG ──────────────────────────────────────────────────────
    const STATUS = {
      A: {
        label:       'A — Uitstekend',
        uitleg:      'Uw installatie functioneert optimaal en vertoont geen aandachtspunten.',
        vervolgstap: 'Geen directe actie vereist. Jaarlijkse controle volstaat.',
        timing:      'Volgende controle: over 12 maanden',
        cls:         'status-A'
      },
      B: {
        label:       'B — Goed',
        uitleg:      'Uw installatie functioneert correct. Kleine onderhoudspunten zijn uitgevoerd.',
        vervolgstap: 'Onderhoud succesvol afgerond. Jaarlijkse opvolging aanbevolen.',
        timing:      'Volgende controle: over 12 maanden',
        cls:         'status-B'
      },
      C: {
        label:       'C — Aandacht vereist',
        uitleg:      'Er zijn aandachtspunten geconstateerd. Wij adviseren opvolging om toekomstige storingen te voorkomen.',
        vervolgstap: 'Wij adviseren u contact op te nemen voor een vervolgbezoek.',
        timing:      'Aanbevolen controle: binnen 3–6 maanden',
        cls:         'status-C'
      }
    };

    // ── STATE ──────────────────────────────────────────────────────────────
    let currentPhotos = [];
    let lightboxIndex = 0;

    // ── INIT ───────────────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
      const params = new URLSearchParams(window.location.search);
      const id = params.get('id');
      if (!id) { showState('notfound'); return; }
      loadRapport(id);
    });

    // ── LOAD RAPPORT ───────────────────────────────────────────────────────
    async function loadRapport(id) {
      try {
        const res = await fetch(`${API_BASE}?action=get_rapport&id=${encodeURIComponent(id)}`);
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const json = await res.json();
        if (!json.success) { showState('notfound'); return; }
        renderRapport(json.data);
      } catch (err) {
        console.error(err);
        document.getElementById('error-message').textContent = err.message || 'Probeer de pagina te vernieuwen.';
        showState('error');
      }
    }

    // ── RENDER ─────────────────────────────────────────────────────────────
    function renderRapport(r) {
      const cfg = STATUS[r.status] || STATUS.B;

      // Meta
      const datum = new Date(r.datum).toLocaleDateString('nl-NL', { day: 'numeric', month: 'long', year: 'numeric' });
      document.getElementById('rapport-meta').textContent = `${datum} · ${r.klant} · ${r.locatie}`;

      // Status badge
      const badge = document.getElementById('status-badge');
      badge.className = `status-badge ${cfg.cls}`;
      document.getElementById('status-text').textContent = cfg.label;

      // Uitleg / vervolgstap / timing
      document.getElementById('rapport-uitleg').textContent      = cfg.uitleg;
      document.getElementById('rapport-vervolgstap').textContent = cfg.vervolgstap;
      document.getElementById('rapport-timing').textContent      = cfg.timing;

      // Werkzaamheden
      const list = document.getElementById('werkzaamheden-list');
      const bullets = Array.isArray(r.samenvatting) ? r.samenvatting : JSON.parse(r.samenvatting || '[]');
      bullets.forEach(item => {
        const li = document.createElement('li');
        li.textContent = item;
        list.appendChild(li);
      });

      // Foto's
      const fotos = Array.isArray(r.fotos) ? r.fotos : JSON.parse(r.fotos || '[]');
      if (fotos.length > 0) {
        currentPhotos = fotos;
        const grid = document.getElementById('photo-grid');
        fotos.forEach((foto, i) => {
          const item = document.createElement('div');
          item.className = 'photo-item';
          item.innerHTML = `
            <div class="photo-thumb" onclick="openLightbox(${i})">
              <img src="${escHtml(foto.url)}" alt="${escHtml(foto.label)}" loading="lazy" />
            </div>
            <span class="photo-label">${escHtml(foto.label)}</span>
          `;
          grid.appendChild(item);
        });
        document.getElementById('card-fotos').style.display = 'block';
      }

      // Monteur opmerking
      if (r.monteur_opmerking && r.monteur_opmerking.trim()) {
        document.getElementById('monteur-opmerking').textContent = `"${r.monteur_opmerking}"`;
        document.getElementById('card-opmerking').style.display = 'block';
      }

      // Pagina titel
      document.title = `Onderhoudsrapport ${r.klant} — Airco Beheer`;

      showState('content');
    }

    // ── UPGRADE CTA ────────────────────────────────────────────────────────
    async function activateContract() {
      const params = new URLSearchParams(window.location.search);
      const id = params.get('id');

      // Optimistic UI
      document.getElementById('cta-area').innerHTML = `
        <div class="inline-alert">
          ✓ Aanvraag ontvangen. Wij bevestigen uw onderhoudscontract per e-mail.
        </div>`;

      // Fire & forget to backend
      try {
        await fetch(`${API_BASE}?action=activate_contract`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ rapport_id: id })
        });
      } catch (e) { /* non-blocking */ }
    }

    // ── LIGHTBOX ───────────────────────────────────────────────────────────
    function openLightbox(index) {
      lightboxIndex = index;
      updateLightbox();
      document.getElementById('lightbox').classList.add('open');
      document.body.style.overflow = 'hidden';
    }

    function updateLightbox() {
      const foto = currentPhotos[lightboxIndex];
      document.getElementById('lightbox-img').src       = foto.url;
      document.getElementById('lightbox-img').alt       = foto.label;
      document.getElementById('lightbox-caption').textContent = foto.label;
      document.getElementById('lb-prev').disabled = lightboxIndex === 0;
      document.getElementById('lb-next').disabled = lightboxIndex === currentPhotos.length - 1;
    }

    function lightboxNav(dir) {
      lightboxIndex = Math.max(0, Math.min(currentPhotos.length - 1, lightboxIndex + dir));
      updateLightbox();
    }

    function closeLightbox(e) {
      if (e.target === document.getElementById('lightbox')) closeLightboxBtn();
    }

    function closeLightboxBtn() {
      document.getElementById('lightbox').classList.remove('open');
      document.body.style.overflow = '';
    }

    // keyboard nav
    document.addEventListener('keydown', e => {
      if (!document.getElementById('lightbox').classList.contains('open')) return;
      if (e.key === 'ArrowLeft')  lightboxNav(-1);
      if (e.key === 'ArrowRight') lightboxNav(1);
      if (e.key === 'Escape')     closeLightboxBtn();
    });

    // ── HELPERS ────────────────────────────────────────────────────────────
    function showState(state) {
      document.getElementById('state-loading').style.display  = state === 'loading'  ? 'flex' : 'none';
      document.getElementById('state-notfound').style.display = state === 'notfound' ? 'flex' : 'none';
      document.getElementById('state-error').style.display    = state === 'error'    ? 'flex' : 'none';
      document.getElementById('rapport-content').style.display = state === 'content' ? 'flex' : 'none';
    }

    function escHtml(str) {
      return String(str)
        .replace(/&/g,'&').replace(/</g,'<')
        .replace(/>/g,'>').replace(/"/g,'"');
    }
  </script>
</body>
</html>
