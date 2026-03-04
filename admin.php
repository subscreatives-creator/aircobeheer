<?php
// Load configuration
require_once __DIR__ . '/config.php';
?>
<!DOCTYPE html>
<html lang="nl">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/svg+xml" href="favicon.svg" />
  <title>Backoffice — Airco Beheer</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700&family=DM+Mono:wght@400;500;700&display=swap"
    rel="stylesheet" />
  <style>
    /* ── TOKENS ─────────────────────────────────────────────── */
    :root {
      --bg: #13293E;
      --bg-card: #0e2030;
      --accent: #1C9ADB;
      --text: #FFFFFF;
      --muted: rgba(255, 255, 255, 0.72);
      --border: rgba(226, 230, 234, 0.16);
      --cta-fill: #FFC400;
      --cta-text: #2B2B2B;
      --green: #22C55E;
      --red: #F87171;
      --input-bg: rgba(255, 255, 255, 0.05);
      --font-main: 'Sora', sans-serif;
      --font-mono: 'DM Mono', monospace;
    }

    /* ── RESET ──────────────────────────────────────────────── */
    *,
    *::before,
    *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      background: var(--bg);
      color: var(--text);
      font-family: var(--font-main);
      -webkit-font-smoothing: antialiased;
      min-height: 100vh;
    }

    ::-webkit-scrollbar {
      width: 6px;
    }

    ::-webkit-scrollbar-track {
      background: var(--bg-card);
    }

    ::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.15);
      border-radius: 3px;
    }

    /* ── LAYOUT ─────────────────────────────────────────────── */
    .page-wrap {
      max-width: 760px;
      margin: 0 auto;
      padding: 0 24px;
    }

    /* ── HEADER ─────────────────────────────────────────────── */
    .site-header {
      border-bottom: 1px solid var(--border);
      padding: 16px 0;
      background: var(--bg);
      position: sticky;
      top: 0;
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
    }

    .admin-badge {
      font-family: var(--font-mono);
      font-size: 12px;
      letter-spacing: 0.06em;
      background: rgba(255, 196, 0, 0.1);
      border: 1px solid rgba(255, 196, 0, 0.25);
      border-radius: 5px;
      padding: 3px 10px;
      color: var(--cta-fill);
    }

    /* ── MAIN ───────────────────────────────────────────────── */
    main {
      padding: 40px 0 80px;
    }

    .stack {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    /* ── PAGE TITLE ─────────────────────────────────────────── */
    .page-title {
      font-size: 22px;
      font-weight: 700;
      margin-bottom: 4px;
    }

    .page-sub {
      font-size: 13px;
      color: var(--muted);
    }

    /* ── CARD ───────────────────────────────────────────────── */
    .card {
      background: var(--bg-card);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 28px 32px;
    }

    /* ── SECTION TITLE ──────────────────────────────────────── */
    .section-title {
      font-family: var(--font-mono);
      font-size: 13px;
      font-weight: 700;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: var(--accent);
      margin-bottom: 20px;
      padding-bottom: 12px;
      border-bottom: 1px solid var(--border);
    }

    /* ── FORM LABEL ─────────────────────────────────────────── */
    .field-label {
      display: block;
      font-family: var(--font-mono);
      font-size: 12px;
      font-weight: 700;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      color: var(--accent);
      margin-bottom: 6px;
    }

    .field-label .req {
      color: var(--red);
    }

    /* ── INPUTS ─────────────────────────────────────────────── */
    .input,
    .textarea,
    .select {
      width: 100%;
      background: var(--input-bg);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 10px 14px;
      color: var(--text);
      font-size: 14px;
      font-family: var(--font-main);
      outline: none;
      transition: border-color 0.15s;
      appearance: none;
    }

    .input:focus,
    .textarea:focus,
    .select:focus {
      border-color: var(--accent);
    }

    .input::placeholder,
    .textarea::placeholder {
      color: rgba(255, 255, 255, 0.3);
    }

    .textarea {
      resize: vertical;
    }

    .select {
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='rgba(255,255,255,0.5)' stroke-width='1.5' fill='none'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 14px center;
      cursor: pointer;
    }

    .select option {
      background: #0e2030;
    }

    /* ── FORM GRID ──────────────────────────────────────────── */
    .form-grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }

    .span-2 {
      grid-column: 1 / -1;
    }

    .field {
      display: flex;
      flex-direction: column;
    }

    /* ── STATUS SELECTOR ────────────────────────────────────── */
    .status-options {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 10px;
    }

    .status-opt {
      background: var(--input-bg);
      border: 2px solid var(--border);
      border-radius: 8px;
      padding: 12px;
      color: var(--muted);
      font-size: 13px;
      font-weight: 700;
      font-family: var(--font-mono);
      cursor: pointer;
      text-align: center;
      transition: all 0.15s;
    }

    .status-opt[data-status="A"].active {
      background: rgba(34, 197, 94, 0.12);
      border-color: #22C55E;
      color: #22C55E;
    }

    .status-opt[data-status="B"].active {
      background: rgba(28, 154, 219, 0.12);
      border-color: var(--accent);
      color: var(--accent);
    }

    .status-opt[data-status="C"].active {
      background: rgba(245, 158, 11, 0.12);
      border-color: #F59E0B;
      color: #F59E0B;
    }

    /* ── BULLETS ────────────────────────────────────────────── */
    .bullets-list {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .bullet-row {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .bullet-dot {
      color: var(--accent);
      font-size: 20px;
      line-height: 1;
      padding-top: 2px;
    }

    .bullet-row .input {
      flex: 1;
    }

    .btn-remove {
      background: none;
      border: none;
      color: rgba(255, 255, 255, 0.3);
      font-size: 20px;
      cursor: pointer;
      padding: 0 4px;
      line-height: 1;
      transition: color 0.15s;
      flex-shrink: 0;
    }

    .btn-remove:hover {
      color: var(--red);
    }

    .btn-add-bullet {
      align-self: flex-start;
      margin-top: 4px;
      background: none;
      border: 1px dashed var(--border);
      border-radius: 6px;
      padding: 7px 14px;
      color: var(--muted);
      font-size: 13px;
      cursor: pointer;
      font-family: var(--font-main);
      transition: border-color 0.15s, color 0.15s;
    }

    .btn-add-bullet:hover {
      border-color: var(--accent);
      color: var(--text);
    }

    /* ── DROP ZONE ──────────────────────────────────────────── */
    .drop-zone {
      border: 2px dashed var(--border);
      border-radius: 10px;
      padding: 28px;
      text-align: center;
      cursor: pointer;
      transition: border-color 0.15s, background 0.15s;
    }

    .drop-zone:hover,
    .drop-zone.dragover {
      border-color: var(--accent);
      background: rgba(28, 154, 219, 0.05);
    }

    .drop-zone p {
      font-size: 14px;
      color: var(--muted);
      margin-bottom: 4px;
    }

    .drop-zone small {
      font-size: 12px;
      color: rgba(255, 255, 255, 0.3);
    }

    #foto-input {
      display: none;
    }

    /* ── PHOTO PREVIEWS ─────────────────────────────────────── */
    .preview-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
      gap: 12px;
      margin-top: 16px;
    }

    .preview-item {
      display: flex;
      flex-direction: column;
      gap: 6px;
    }

    .preview-thumb {
      position: relative;
      aspect-ratio: 16/9;
      border-radius: 8px;
      overflow: hidden;
      border: 1px solid var(--border);
    }

    .preview-thumb img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .preview-remove {
      position: absolute;
      top: 4px;
      right: 4px;
      background: rgba(0, 0, 0, 0.7);
      border: none;
      border-radius: 50%;
      width: 22px;
      height: 22px;
      color: #fff;
      font-size: 14px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      line-height: 1;
    }

    .preview-label {
      width: 100%;
      background: var(--input-bg);
      border: 1px solid var(--border);
      border-radius: 6px;
      padding: 6px 8px;
      color: var(--text);
      font-size: 12px;
      font-family: var(--font-main);
      outline: none;
    }

    .preview-label:focus {
      border-color: var(--accent);
    }

    /* ── ERROR / ALERT ──────────────────────────────────────── */
    .alert-error {
      background: rgba(248, 113, 113, 0.1);
      border: 1px solid rgba(248, 113, 113, 0.3);
      border-radius: 8px;
      padding: 12px 16px;
      color: var(--red);
      font-size: 14px;
      display: none;
    }

    .alert-error.show {
      display: block;
    }

    /* ── SUBMIT BUTTON ──────────────────────────────────────── */
    .btn-submit {
      background: var(--cta-fill);
      color: var(--cta-text);
      border: none;
      border-radius: 8px;
      padding: 15px 32px;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      font-family: var(--font-main);
      transition: opacity 0.15s;
      align-self: flex-start;
    }

    .btn-submit:hover:not(:disabled) {
      opacity: 0.88;
    }

    .btn-submit:disabled {
      opacity: 0.5;
      cursor: wait;
    }

    /* ── SUCCESS SCREEN ─────────────────────────────────────── */
    #success-screen {
      display: none;
      flex-direction: column;
      align-items: center;
      text-align: center;
      gap: 16px;
      padding: 48px 32px;
    }

    #success-screen.show {
      display: flex;
    }

    .success-icon {
      font-size: 48px;
    }

    .success-title {
      font-size: 22px;
      font-weight: 700;
      color: #22C55E;
    }

    .success-sub {
      font-size: 14px;
      color: var(--muted);
    }

    .link-box {
      width: 100%;
      max-width: 560px;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 12px 16px;
      display: flex;
      align-items: center;
      gap: 12px;
      flex-wrap: wrap;
    }

    .link-text {
      flex: 1;
      font-family: var(--font-mono);
      font-size: 13px;
      color: var(--accent);
      word-break: break-all;
    }

    .btn-copy {
      background: var(--cta-fill);
      color: var(--cta-text);
      border: none;
      border-radius: 6px;
      padding: 8px 16px;
      font-size: 13px;
      font-weight: 700;
      cursor: pointer;
      font-family: var(--font-main);
      white-space: nowrap;
      transition: background 0.2s;
    }

    .btn-copy.copied {
      background: #22C55E;
      color: #fff;
    }

    .btn-new {
      background: transparent;
      color: var(--accent);
      border: 1px solid var(--accent);
      border-radius: 8px;
      padding: 10px 20px;
      font-size: 14px;
      cursor: pointer;
      font-family: var(--font-main);
      transition: background 0.15s;
    }

    .btn-new:hover {
      background: rgba(28, 154, 219, 0.1);
    }

    /* ── FOOTER ─────────────────────────────────────────────── */
    .site-footer {
      border-top: 1px solid var(--border);
      padding: 20px 24px;
    }

    .site-footer .page-wrap {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 8px;
    }

    .footer-links {
      display: flex;
      gap: 20px;
    }

    .footer-links a {
      font-size: 13px;
      color: var(--muted);
      text-decoration: none;
    }

    .footer-copy {
      font-family: var(--font-mono);
      font-size: 12px;
      color: rgba(255, 255, 255, 0.3);
    }

    /* ── RESPONSIVE ─────────────────────────────────────────── */
    @media (max-width: 600px) {
      .card {
        padding: 20px 18px;
      }

      .form-grid-2 {
        grid-template-columns: 1fr;
      }

      .status-options {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>

  <!-- HEADER -->
  <header class="site-header">
    <div class="page-wrap">
      <span class="logo">AIRCO BEHEER</span>
      <span class="admin-badge">BACKOFFICE</span>
    </div>
  </header>

  <!-- MAIN -->
  <main>
    <div class="page-wrap">

      <!-- SUCCESS SCREEN (hidden by default) -->
      <div class="card" id="success-screen">
        <div class="success-icon">✓</div>
        <h2 class="success-title">Rapport aangemaakt</h2>
        <p class="success-sub">Stuur de onderstaande link naar de klant via e-mail.</p>
        <div class="link-box">
          <span class="link-text" id="generated-link">—</span>
          <button class="btn-copy" id="copy-btn" onclick="copyLink()">Kopieer link</button>
        </div>
        <button class="btn-new" onclick="resetForm()">+ Nieuw rapport aanmaken</button>
      </div>

      <!-- FORM (shown by default) -->
      <div class="stack" id="form-screen">

        <div>
          <h1 class="page-title">Nieuw onderhoudsrapport</h1>
          <p class="page-sub">Vul de gegevens in en upload de foto's. Na opslaan ontvangt u een klantlink.</p>
        </div>

        <!-- Klantgegevens -->
        <div class="card">
          <p class="section-title">Klantgegevens</p>
          <div class="form-grid-2">
            <div class="field">
              <label class="field-label" for="klant">Klantnaam <span class="req">*</span></label>
              <input class="input" id="klant" type="text" placeholder="Familie Jansen" />
            </div>
            <div class="field">
              <label class="field-label" for="datum">Datum bezoek <span class="req">*</span></label>
              <input class="input" id="datum" type="date" />
            </div>
            <div class="field span-2">
              <label class="field-label" for="locatie">Locatie / adres <span class="req">*</span></label>
              <input class="input" id="locatie" type="text" placeholder="Dorpsstraat 14, Amsterdam" />
            </div>
          </div>
        </div>

        <!-- Status -->
        <div class="card">
          <p class="section-title">Status installatie</p>
          <div class="status-options" id="status-options">
            <button class="status-opt" data-status="A" onclick="selectStatus('A')">A — Uitstekend</button>
            <button class="status-opt active" data-status="B" onclick="selectStatus('B')">B — Goed</button>
            <button class="status-opt" data-status="C" onclick="selectStatus('C')">C — Aandacht vereist</button>
          </div>
          <input type="hidden" id="status-value" value="B" />
        </div>

        <!-- Werkzaamheden -->
        <div class="card">
          <p class="section-title">Uitgevoerde werkzaamheden</p>
          <div class="bullets-list" id="bullets-list"></div>
          <button class="btn-add-bullet" onclick="addBullet('')" style="margin-top:10px;">+ Regel toevoegen</button>
        </div>

        <!-- Foto's -->
        <div class="card">
          <p class="section-title">Foto's <span id="foto-count"
              style="font-size:12px;color:var(--muted);font-weight:400;">(0/12)</span></p>
          <div class="drop-zone" id="drop-zone" onclick="document.getElementById('foto-input').click()"
            ondragover="onDragOver(event)" ondragleave="onDragLeave(event)" ondrop="onDrop(event)">
            <input type="file" id="foto-input" accept="image/*" multiple onchange="handleFiles(this.files)" />
            <p>📷 Klik of sleep foto's hierheen</p>
            <small>JPG, PNG, WEBP · Max 12 foto's</small>
          </div>
          <div class="preview-grid" id="preview-grid"></div>
        </div>

        <!-- Opmerking monteur -->
        <div class="card">
          <p class="section-title">Opmerking monteur</p>
          <label class="field-label" for="opmerking">Opmerking <span
              style="color:var(--muted);font-weight:400;">(optioneel)</span></label>
          <textarea class="textarea" id="opmerking" rows="2"
            placeholder="Korte opmerking van de monteur (max 1–2 zinnen)…"></textarea>
        </div>

        <!-- Error -->
        <div class="alert-error" id="alert-error"></div>

        <!-- Submit -->
        <button class="btn-submit" id="submit-btn" onclick="submitForm()">
          Rapport opslaan & link genereren →
        </button>

      </div><!-- /form-screen -->
    </div>
  </main>

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

    // ── STATE ──────────────────────────────────────────────────────────────
    let fotoFiles = []; // { file, label, preview }
    let selectedStatus = 'B';

    // ── INIT ───────────────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
      // Set today as default date
      document.getElementById('datum').value = new Date().toISOString().split('T')[0];

      // Default bullets
      const defaults = [
        'Reiniging van binnen- en buitenunit uitgevoerd',
        'Filters gecontroleerd en gereinigd',
        'Controle van elektrische aansluitingen',
        'Controle van koelprestaties en temperatuurverschil',
        'Controle op lekkages en veiligheid',
        'Functionele test uitgevoerd',
      ];
      defaults.forEach(b => addBullet(b));
    });

    // ── STATUS ─────────────────────────────────────────────────────────────
    function selectStatus(val) {
      selectedStatus = val;
      document.getElementById('status-value').value = val;
      document.querySelectorAll('.status-opt').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.status === val);
      });
    }

    // ── BULLETS ────────────────────────────────────────────────────────────
    function addBullet(text) {
      const list = document.getElementById('bullets-list');
      const row = document.createElement('div');
      row.className = 'bullet-row';
      const id = 'bullet-' + Date.now() + '-' + Math.random().toString(36).slice(2);
      row.innerHTML = `
        <span class="bullet-dot">·</span>
        <input class="input" id="${id}" type="text" value="${escHtml(text)}" placeholder="Werkzaamheid…" />
        <button class="btn-remove" onclick="removeBullet(this)" title="Verwijder">×</button>
      `;
      list.appendChild(row);
    }

    function removeBullet(btn) {
      btn.closest('.bullet-row').remove();
    }

    function getBullets() {
      return Array.from(document.querySelectorAll('.bullet-row .input'))
        .map(i => i.value.trim()).filter(Boolean);
    }

    // ── FOTO HANDLING ──────────────────────────────────────────────────────
    function handleFiles(fileList) {
      const files = Array.from(fileList);
      const remaining = 12 - fotoFiles.length;
      files.slice(0, remaining).forEach(file => {
        fotoFiles.push({
          file,
          label: file.name.replace(/\.[^.]+$/, ''),
          preview: URL.createObjectURL(file)
        });
      });
      renderPreviews();
    }

    function renderPreviews() {
      const grid = document.getElementById('preview-grid');
      document.getElementById('foto-count').textContent = `(${fotoFiles.length}/12)`;
      grid.innerHTML = '';
      fotoFiles.forEach((foto, i) => {
        const item = document.createElement('div');
        item.className = 'preview-item';
        item.innerHTML = `
          <div class="preview-thumb">
            <img src="${foto.preview}" alt="${escHtml(foto.label)}" />
            <button class="preview-remove" onclick="removeFoto(${i})">×</button>
          </div>
          <input class="preview-label" type="text" value="${escHtml(foto.label)}"
            onchange="fotoFiles[${i}].label = this.value"
            placeholder="Label (bijv. Binnenunit na)" />
        `;
        grid.appendChild(item);
      });
    }

    function removeFoto(index) {
      URL.revokeObjectURL(fotoFiles[index].preview);
      fotoFiles.splice(index, 1);
      renderPreviews();
    }

    // Drag & drop
    function onDragOver(e) {
      e.preventDefault();
      document.getElementById('drop-zone').classList.add('dragover');
    }
    function onDragLeave() {
      document.getElementById('drop-zone').classList.remove('dragover');
    }
    function onDrop(e) {
      e.preventDefault();
      document.getElementById('drop-zone').classList.remove('dragover');
      handleFiles(e.dataTransfer.files);
    }

    // ── SUBMIT ─────────────────────────────────────────────────────────────
    async function submitForm() {
      hideError();
      const klant = document.getElementById('klant').value.trim();
      const locatie = document.getElementById('locatie').value.trim();
      const datum = document.getElementById('datum').value;
      const status = selectedStatus;
      const bullets = getBullets();
      const opmerking = document.getElementById('opmerking').value.trim();

      if (!klant) return showError('Klantnaam is verplicht.');
      if (!locatie) return showError('Locatie is verplicht.');
      if (!datum) return showError('Datum is verplicht.');
      if (bullets.length === 0) return showError('Voeg minimaal één werkzaamheid toe.');

      const btn = document.getElementById('submit-btn');
      btn.disabled = true;
      btn.textContent = 'Opslaan en uploaden…';

      try {
        // 1. Upload foto's one by one, collect server paths
        const fotoData = [];
        for (const foto of fotoFiles) {
          const fd = new FormData();
          fd.append('action', 'upload_foto');
          fd.append('foto', foto.file);
          const res = await fetch(API_BASE, { method: 'POST', body: fd });
          const json = await res.json();
          if (!json.success) throw new Error(`Foto upload mislukt: ${json.message}`);
          fotoData.push({ url: json.data.url, label: foto.label });
        }

        // 2. Save rapport
        const payload = { klant, locatie, datum, status, samenvatting: bullets, monteur_opmerking: opmerking, fotos: fotoData };
        const res2 = await fetch(API_BASE, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'create_rapport', ...payload })
        });
        const json2 = await res2.json();
        if (!json2.success) throw new Error(json2.message || 'Opslaan mislukt.');

        // 3. Show success - Using BASE_URL from PHP config
        const link = `${BASE_URL}/rapport.php?id=${json2.data.id}`;
        document.getElementById('generated-link').textContent = link;
        document.getElementById('form-screen').style.display = 'none';
        const ss = document.getElementById('success-screen');
        ss.style.display = 'flex';
        ss.classList.add('show');

      } catch (err) {
        showError(err.message);
        btn.disabled = false;
        btn.textContent = 'Rapport opslaan & link genereren →';
      }
    }

    // ── COPY LINK ──────────────────────────────────────────────────────────
    function copyLink() {
      const link = document.getElementById('generated-link').textContent;
      navigator.clipboard.writeText(link).then(() => {
        const btn = document.getElementById('copy-btn');
        btn.textContent = 'Gekopieerd ✓';
        btn.classList.add('copied');
        setTimeout(() => { btn.textContent = 'Kopieer link'; btn.classList.remove('copied'); }, 2500);
      });
    }

    // ── RESET ─────────────────────────────────────────────────────────────
    function resetForm() {
      document.getElementById('klant').value = '';
      document.getElementById('locatie').value = '';
      document.getElementById('opmerking').value = '';
      document.getElementById('datum').value = new Date().toISOString().split('T')[0];
      document.getElementById('bullets-list').innerHTML = '';
      fotoFiles = [];
      renderPreviews();
      selectStatus('B');
      hideError();
      const defaults = [
        'Reiniging van binnen- en buitenunit uitgevoerd',
        'Filters gecontroleerd en gereinigd',
        'Controle van elektrische aansluitingen',
        'Controle van koelprestaties en temperatuurverschil',
        'Controle op lekkages en veiligheid',
        'Functionele test uitgevoerd',
      ];
      defaults.forEach(b => addBullet(b));
      document.getElementById('success-screen').style.display = 'none';
      document.getElementById('success-screen').classList.remove('show');
      document.getElementById('form-screen').style.display = 'flex';
      const btn = document.getElementById('submit-btn');
      btn.disabled = false;
      btn.textContent = 'Rapport opslaan & link genereren →';
    }

    // ── HELPERS ────────────────────────────────────────────────────────────
    function showError(msg) {
      const el = document.getElementById('alert-error');
      el.textContent = '⚠ ' + msg;
      el.classList.add('show');
      el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    function hideError() {
      document.getElementById('alert-error').classList.remove('show');
    }
    function escHtml(str) {
      return String(str)
        .replace(/&/g, '&').replace(/</g, '<')
        .replace(/>/g, '>').replace(/"/g, '"');
    }
  </script>
</body>

</html>
