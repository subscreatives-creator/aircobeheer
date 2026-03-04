<?php
/**
 * api.php — Airco Beheer Rapportensysteem
 * Backend API voor rapport.php en admin.php
 * Verbindt met MySQL (phpMyAdmin)
 *
 * Ondersteunde acties:
 *   GET  ?action=get_rapport&id=UUID   → rapport ophalen
 *   POST action=create_rapport         → nieuw rapport opslaan (JSON body)
 *   POST action=upload_foto            → foto uploaden (multipart/form-data)
 *   POST action=activate_contract      → upgrade aanvraag registreren (JSON body)
 */

// Load configuration
require_once __DIR__ . '/config.php';

// ── CORS (pas aan naar uw domein in productie) ────────────────────────────────
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

// ── DATABASE CONFIG ───────────────────────────────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_NAME', 'airco_beheer');    // ← uw database naam in phpMyAdmin
define('DB_USER', 'root');            // ← uw MySQL gebruikersnaam
define('DB_PASS', '');                // ← uw MySQL wachtwoord
define('DB_CHARSET', 'utf8mb4');

// ── FOTO UPLOAD CONFIG ────────────────────────────────────────────────────────
define('UPLOAD_DIR', __DIR__ . '/uploads/fotos/');
define('UPLOAD_URL_PATH', '/uploads/fotos/');         // ← publieke URL naar uploads map
define('MAX_FILE_SIZE', 8 * 1024 * 1024);        // 8 MB
define('ALLOWED_TYPES', ['image/jpeg','image/png','image/webp','image/gif']);

// ── DATABASE VERBINDING ───────────────────────────────────────────────────────
function getDb(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            jsonError('Database verbinding mislukt: ' . $e->getMessage(), 500);
        }
    }
    return $pdo;
}

// ── ROUTER ────────────────────────────────────────────────────────────────────
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// POST: lees JSON body OF gebruik $_POST voor multipart
$body = [];
if ($method === 'POST') {
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (str_contains($contentType, 'application/json')) {
        $raw  = file_get_contents('php://input');
        $body = json_decode($raw, true) ?? [];
        if (!$action && isset($body['action'])) $action = $body['action'];
    } elseif (str_contains($contentType, 'multipart/form-data')) {
        $body   = $_POST;
        $action = $body['action'] ?? $action;
    }
}

match(true) {
    $method === 'GET'  && $action === 'get_rapport'      => getRapport(),
    $method === 'POST' && $action === 'create_rapport'   => createRapport($body),
    $method === 'POST' && $action === 'upload_foto'      => uploadFoto(),
    $method === 'POST' && $action === 'activate_contract'=> activateContract($body),
    default => jsonError("Onbekende actie: {$action}", 400)
};

// ── GET RAPPORT ───────────────────────────────────────────────────────────────
function getRapport(): void {
    $id = trim($_GET['id'] ?? '');
    if (!$id) jsonError('id is verplicht', 400);

    $db   = getDb();
    $stmt = $db->prepare('SELECT * FROM rapporten WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $row  = $stmt->fetch();

    if (!$row) jsonError('Rapport niet gevonden', 404);

    // Decode JSON velden
    $row['samenvatting'] = json_decode($row['samenvatting'] ?? '[]', true);
    $row['fotos']        = json_decode($row['fotos']        ?? '[]', true);

    jsonSuccess($row);
}

// ── CREATE RAPPORT ────────────────────────────────────────────────────────────
function createRapport(array $body): void {
    $klant      = trim($body['klant']            ?? '');
    $locatie    = trim($body['locatie']          ?? '');
    $datum      = trim($body['datum']            ?? '');
    $status     = strtoupper(trim($body['status'] ?? 'B'));
    $samenvatting = $body['samenvatting']        ?? [];
    $opmerking  = trim($body['monteur_opmerking'] ?? '');
    $fotos      = $body['fotos']                 ?? [];

    if (!$klant)   jsonError('klant is verplicht', 400);
    if (!$locatie) jsonError('locatie is verplicht', 400);
    if (!$datum)   jsonError('datum is verplicht', 400);
    if (!in_array($status, ['A','B','C'])) jsonError('ongeldige status', 400);
    if (!is_array($samenvatting) || count($samenvatting) === 0) jsonError('samenvatting is verplicht', 400);

    // Genereer UUID v4
    $id = generateUuid();

    $db   = getDb();
    $stmt = $db->prepare('
        INSERT INTO rapporten (id, klant, locatie, datum, status, samenvatting, monteur_opmerking, fotos, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ');
    $stmt->execute([
        $id,
        $klant,
        $locatie,
        $datum,
        $status,
        json_encode($samenvatting, JSON_UNESCAPED_UNICODE),
        $opmerking ?: null,
        json_encode($fotos, JSON_UNESCAPED_UNICODE),
    ]);

    jsonSuccess(['id' => $id]);
}

// ── UPLOAD FOTO ───────────────────────────────────────────────────────────────
function uploadFoto(): void {
    if (!isset($_FILES['foto'])) jsonError('Geen bestand ontvangen', 400);

    $file = $_FILES['foto'];
    if ($file['error'] !== UPLOAD_ERR_OK) jsonError('Upload fout code: ' . $file['error'], 400);
    if ($file['size'] > MAX_FILE_SIZE)    jsonError('Bestand te groot (max 8 MB)', 400);

    // Controleer MIME type (niet alleen extensie)
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($file['tmp_name']);
    if (!in_array($mime, ALLOWED_TYPES)) jsonError('Ongeldig bestandstype: ' . $mime, 400);

    // Maak upload map aan indien nodig
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }

    // Unieke bestandsnaam
    $ext      = match($mime) {
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
        default      => 'jpg'
    };
    $filename = uniqid('foto_', true) . '.' . $ext;
    $dest     = UPLOAD_DIR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        jsonError('Verplaatsen van bestand mislukt', 500);
    }

    $url = rtrim(getBaseUrl(), '/') . UPLOAD_URL_PATH . $filename;
    jsonSuccess(['url' => $url, 'filename' => $filename]);
}

// ── ACTIVATE CONTRACT ─────────────────────────────────────────────────────────
function activateContract(array $body): void {
    $rapport_id = trim($body['rapport_id'] ?? '');
    if (!$rapport_id) jsonError('rapport_id is verplicht', 400);

    $db = getDb();

    // Controleer of rapport bestaat
    $stmt = $db->prepare('SELECT id FROM rapporten WHERE id = ? LIMIT 1');
    $stmt->execute([$rapport_id]);
    if (!$stmt->fetch()) jsonError('Rapport niet gevonden', 404);

    // Sla contract aanvraag op
    $stmt = $db->prepare('
        INSERT INTO contract_aanvragen (rapport_id, aangevraagd_op)
        VALUES (?, NOW())
        ON DUPLICATE KEY UPDATE aangevraagd_op = NOW()
    ');
    $stmt->execute([$rapport_id]);

    jsonSuccess(['message' => 'Contract aanvraag geregistreerd']);
}

// ── HELPERS ───────────────────────────────────────────────────────────────────
function jsonSuccess(mixed $data): void {
    echo json_encode(['success' => true, 'data' => $data], JSON_UNESCAPED_UNICODE);
    exit;
}

function jsonError(string $message, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['success' => false, 'message' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

function generateUuid(): string {
    $data    = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function getBaseUrl(): string {
    return rtrim(BASE_URL, '/');
}
