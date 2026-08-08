<?php
session_start();

// ─── Ensure any PHP fatal error becomes valid JSON instead of a blank/HTML
//     response (this is what makes fetch()/await res.json() hang forever
//     with a "(pending)" request in DevTools) ─────────────────────────
error_reporting(E_ALL);
ini_set('display_errors', '0'); // never leak raw PHP errors as HTML to the browser

function eid_json_error($message, $code = 500) {
    if (!headers_sent()) {
        http_response_code($code);
        header('Content-Type: application/json');
    }
    echo json_encode(['success' => false, 'message' => $message]);
    exit();
}

set_exception_handler(function ($e) {
    eid_json_error('Server error: ' . $e->getMessage());
});
register_shutdown_function(function () {
    $err = error_get_last();
    if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        eid_json_error('Fatal server error: ' . $err['message'] . ' in ' . $err['file'] . ' on line ' . $err['line']);
    }
});

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$valid_username = "admin";
$valid_password = "password123";

// ─── LOGIN ENDPOINT ───────────────────────────────────────────────
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    if ($_POST['username'] === $valid_username && $_POST['password'] === $valid_password) {
        $_SESSION['admin_logged_in'] = true;
        echo json_encode(['success' => true, 'message' => 'Login successful']);
    } else {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
    }
    exit();
}

// ─── LOOKUP ENDPOINT (called from index.html login form) ──────────
// User enters eVisa number + passport number → return matching PDF
if (isset($_POST['action']) && $_POST['action'] === 'lookup') {
    $evisa_number  = trim($_POST['evisa_number']  ?? '');
    $passport_number = trim($_POST['passport_number'] ?? '');

    if (empty($evisa_number) || empty($passport_number)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'E-Viza broj i broj pasoša su obavezni']);
        exit();
    }

    // Load records
    $records_file = 'uploads/records.json';
    $records = [];
    if (file_exists($records_file)) {
        $records = json_decode(file_get_contents($records_file), true) ?? [];
    }

    // Search for matching record
    $found = null;
    foreach ($records as $record) {
        if (
            strtoupper($record['evisa_number'])    === strtoupper($evisa_number) &&
            strtoupper($record['passport_number']) === strtoupper($passport_number)
        ) {
            $found = $record;
            break;
        }
    }

    if ($found) {
        echo json_encode([
    'success'          => true,
    'pdf_url'          => 'uploads/' . $found['filename'],
    'name'             => $found['name'] ?? '',
    'passport_number'  => $found['passport_number'] ?? '',
    'evisa_number'     => $found['evisa_number'] ?? '',
    'message'          => $found['message'] ?? '',
    'issue_date'       => $found['issue_date'] ?? '',
    'valid_until'      => $found['valid_until'] ?? '',
    'verify_url'       => 'verify.php?id=' . urlencode($found['evisa_number'])
]);
    } else {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Nije pronađen dokument za unete podatke. Proverite broj e-vize i broj pasoša.'
        ]);
    }
    exit();
}

// ─── UPLOAD ENDPOINT (admin panel) ───────────────────────────────
if (isset($_POST['action']) && $_POST['action'] === 'upload') {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Not authorized']);
        exit();
    }

    $evisa_number    = trim($_POST['evisa_number']    ?? '');
    $passport_number = trim($_POST['passport_number'] ?? '');
    $name            = trim($_POST['name']            ?? '');

    $message = trim($_POST['message'] ?? '');
$issue_date = trim($_POST['issue_date'] ?? '');
$valid_until = trim($_POST['valid_until'] ?? '');

    if (empty($evisa_number) || empty($passport_number)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Obavezna polja nedostaju']);
        exit();
    }

    if (!isset($_FILES['pdf_file']) || $_FILES['pdf_file']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'PDF fajl nije ispravno otpremljen']);
        exit();
    }

    // Validate PDF
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $_FILES['pdf_file']['tmp_name']);
    finfo_close($finfo);

    if ($mime !== 'application/pdf') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Samo PDF fajlovi su dozvoljeni']);
        exit();
    }

    // Save file
    $uploads_dir = 'uploads/';
    if (!is_dir($uploads_dir)) {
        if (!@mkdir($uploads_dir, 0755, true)) {
            eid_json_error('Uploads folder ne postoji i ne može se kreirati. Proverite dozvole na serveru (folder "uploads" mora biti writable, npr. chmod 755 ili 775).');
        }
    }
    if (!is_writable($uploads_dir)) {
        eid_json_error('Uploads folder nije writable. Na serveru pokrenite: chmod 755 uploads (ili 775 ako 755 ne radi).');
    }

    $safe_name = strtoupper(preg_replace('/[^A-Za-z0-9_\-]/', '_', $evisa_number));
    $filename  = $safe_name . '_' . time() . '.pdf';
    $target    = $uploads_dir . $filename;

    if (!move_uploaded_file($_FILES['pdf_file']['tmp_name'], $target)) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Greška pri čuvanju fajla']);
        exit();
    }

    // Update records.json
    $records_file = $uploads_dir . 'records.json';
    $records = [];
    if (file_exists($records_file)) {
        $records = json_decode(file_get_contents($records_file), true) ?? [];
    }

    // Remove old record for same evisa+passport if exists
    $records = array_filter($records, function($r) use ($evisa_number, $passport_number) {
        return !(
            strtoupper($r['evisa_number'])    === strtoupper($evisa_number) &&
            strtoupper($r['passport_number']) === strtoupper($passport_number)
        );
    });

    // Add new record
   $records[] = [
    'evisa_number'    => strtoupper($evisa_number),
    'passport_number' => strtoupper($passport_number),
    'name'            => $name,
    'message'         => $message,
    'issue_date'      => $issue_date,
    'valid_until'     => $valid_until,
    'filename'        => $filename,
    'uploaded_at'     => date('Y-m-d H:i:s'),
];

    if (@file_put_contents($records_file, json_encode(array_values($records), JSON_PRETTY_PRINT)) === false) {
        eid_json_error('PDF je sačuvan, ali records.json nije writable. Proverite dozvole (chmod 644 na uploads/records.json, chmod 755 na uploads folder).');
    }

    echo json_encode(['success' => true, 'message' => 'Fajl uspešno otpremljen', 'filename' => $filename]);
    exit();
}

// ─── EDIT ENDPOINT (admin panel) ─────────────────────────────────
// Updates an existing record identified by its original evisa+passport.
// Can also replace the PDF file if a new one is provided.
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Not authorized']);
        exit();
    }

    $original_evisa    = trim($_POST['original_evisa_number']    ?? '');
    $original_passport = trim($_POST['original_passport_number'] ?? '');

    $evisa_number    = trim($_POST['evisa_number']    ?? '');
    $passport_number = trim($_POST['passport_number'] ?? '');
    $name            = trim($_POST['name']            ?? '');
    $message         = trim($_POST['message']         ?? '');
    $issue_date      = trim($_POST['issue_date']      ?? '');
    $valid_until     = trim($_POST['valid_until']     ?? '');

    if (empty($original_evisa) || empty($original_passport)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing original record identifier']);
        exit();
    }

    if (empty($evisa_number) || empty($passport_number)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Obavezna polja nedostaju']);
        exit();
    }

    $uploads_dir   = 'uploads/';
    $records_file  = $uploads_dir . 'records.json';
    $records = [];
    if (file_exists($records_file)) {
        $records = json_decode(file_get_contents($records_file), true) ?? [];
    }

    // Find the record to edit
    $targetIndex = -1;
    foreach ($records as $i => $r) {
        if (
            strtoupper($r['evisa_number'] ?? '')    === strtoupper($original_evisa) &&
            strtoupper($r['passport_number'] ?? '') === strtoupper($original_passport)
        ) {
            $targetIndex = $i;
            break;
        }
    }

    if ($targetIndex === -1) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Record not found']);
        exit();
    }

    // If the new evisa+passport combo (excluding itself) already belongs to another record, block it
    foreach ($records as $i => $r) {
        if ($i === $targetIndex) continue;
        if (
            strtoupper($r['evisa_number'] ?? '')    === strtoupper($evisa_number) &&
            strtoupper($r['passport_number'] ?? '') === strtoupper($passport_number)
        ) {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => 'Another record already uses that eVisa/passport combination']);
            exit();
        }
    }

    $filename = $records[$targetIndex]['filename'] ?? '';

    // Optional: replace PDF file
    if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $_FILES['pdf_file']['tmp_name']);
        finfo_close($finfo);

        if ($mime !== 'application/pdf') {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Samo PDF fajlovi su dozvoljeni']);
            exit();
        }

        $safe_name    = strtoupper(preg_replace('/[^A-Za-z0-9_\-]/', '_', $evisa_number));
        $new_filename = $safe_name . '_' . time() . '.pdf';
        $target       = $uploads_dir . $new_filename;

        if (!move_uploaded_file($_FILES['pdf_file']['tmp_name'], $target)) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Greška pri čuvanju fajla']);
            exit();
        }

        // Remove old PDF file if it exists and differs from the new one
        $oldPath = $uploads_dir . $filename;
        if ($filename && $filename !== $new_filename && file_exists($oldPath)) {
            @unlink($oldPath);
        }

        $filename = $new_filename;
    }

    $records[$targetIndex] = [
        'evisa_number'    => strtoupper($evisa_number),
        'passport_number' => strtoupper($passport_number),
        'name'            => $name,
        'message'         => $message,
        'issue_date'      => $issue_date,
        'valid_until'     => $valid_until,
        'filename'        => $filename,
        'uploaded_at'     => $records[$targetIndex]['uploaded_at'] ?? date('Y-m-d H:i:s'),
        'updated_at'      => date('Y-m-d H:i:s'),
    ];

    if (@file_put_contents($records_file, json_encode(array_values($records), JSON_PRETTY_PRINT)) === false) {
        eid_json_error('records.json nije writable. Proverite dozvole (chmod 644 na uploads/records.json).');
    }

    echo json_encode(['success' => true, 'message' => 'Zapis uspešno izmenjen']);
    exit();
}

// ─── DELETE ENDPOINT (admin panel) ───────────────────────────────
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Not authorized']);
        exit();
    }

    $evisa_number    = trim($_POST['evisa_number']    ?? '');
    $passport_number = trim($_POST['passport_number'] ?? '');

    if (empty($evisa_number) || empty($passport_number)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing record identifier']);
        exit();
    }

    $uploads_dir  = 'uploads/';
    $records_file = $uploads_dir . 'records.json';
    $records = [];
    if (file_exists($records_file)) {
        $records = json_decode(file_get_contents($records_file), true) ?? [];
    }

    $deletedFilename = null;
    $remaining = [];
    foreach ($records as $r) {
        if (
            strtoupper($r['evisa_number'] ?? '')    === strtoupper($evisa_number) &&
            strtoupper($r['passport_number'] ?? '') === strtoupper($passport_number)
        ) {
            $deletedFilename = $r['filename'] ?? null;
            continue; // skip = delete
        }
        $remaining[] = $r;
    }

    if ($deletedFilename === null) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Record not found']);
        exit();
    }

    if (@file_put_contents($records_file, json_encode(array_values($remaining), JSON_PRETTY_PRINT)) === false) {
        eid_json_error('records.json nije writable. Proverite dozvole (chmod 644 na uploads/records.json).');
    }

    // Remove the PDF file from disk too
    if ($deletedFilename) {
        $path = $uploads_dir . $deletedFilename;
        if (file_exists($path)) {
            @unlink($path);
        }
    }

    echo json_encode(['success' => true, 'message' => 'Zapis uspešno obrisan']);
    exit();
}

// ─── ADMIN PANEL HTML ─────────────────────────────────────────────
// Direct browser access → serve the admin UI
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="sr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Panel — eVisa</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: Arial, sans-serif; background: #f0f2f5; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
  .card { background: #fff; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,.12); padding: 32px; width: 100%; max-width: 480px; }
  h2 { font-size: 20px; color: #1a3e72; margin-bottom: 24px; text-align: center; border-bottom: 2px solid #1a3e72; padding-bottom: 12px; }
  .form-group { margin-bottom: 16px; }
  label { display: block; font-size: 12px; font-weight: bold; color: #555; margin-bottom: 5px; text-transform: uppercase; }
  input[type=text], input[type=password], input[type=file] {
    width: 100%; padding: 9px 12px; border: 1px solid #ccc; border-radius: 5px; font-size: 13px;
  }
  input:focus { outline: none; border-color: #1a3e72; }
  .btn { width: 100%; padding: 11px; background: #1a3e72; color: #fff; border: none; border-radius: 5px; font-size: 14px; font-weight: bold; cursor: pointer; margin-top: 8px; }
  .btn:hover { background: #14305a; }
  .btn-danger { background: #c0392b; margin-top: 12px; }
  .btn-danger:hover { background: #96281b; }
  .msg { padding: 10px 14px; border-radius: 5px; margin-bottom: 16px; font-size: 13px; }
  .msg.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
  .msg.error   { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
  .records-table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 12px; }
  .records-table th, .records-table td { padding: 8px; text-align: left; border-bottom: 1px solid #eee; }
  .records-table th { background: #f4f6fa; font-weight: bold; color: #333; }
  .records-table tr:hover { background: #fafbff; }
  .section-title { font-size: 14px; font-weight: bold; color: #1a3e72; margin: 24px 0 12px; }
  #loginPanel, #uploadPanel { display: none; }
  #loginPanel.active, #uploadPanel.active { display: block; }
  .logout-btn { text-align: right; margin-bottom: 12px; }
  .logout-btn button { background: none; border: 1px solid #ccc; border-radius: 4px; padding: 5px 12px; cursor: pointer; font-size: 12px; color: #666; }
  .badge { display: inline-block; padding: 2px 7px; border-radius: 10px; font-size: 10px; background: #d4edda; color: #155724; }
  .action-btn { border: none; background: none; cursor: pointer; font-size: 12px; padding: 3px 6px; border-radius: 4px; }
  .action-btn.edit { color: #1a3e72; }
  .action-btn.edit:hover { background: #e8edf5; }
  .action-btn.delete { color: #c0392b; }
  .action-btn.delete:hover { background: #fbe9e7; }

  /* Edit modal */
  .modal-overlay {
    display: none;
    position: fixed; inset: 0;
    background: rgba(0,0,0,.45);
    align-items: center; justify-content: center;
    z-index: 1000;
    padding: 16px;
  }
  .modal-overlay.active { display: flex; }
  .modal-box {
    background: #fff; border-radius: 10px; padding: 28px;
    width: 100%; max-width: 460px;
    max-height: 90vh; overflow-y: auto;
  }
  .modal-box h3 { font-size: 17px; color: #1a3e72; margin-bottom: 18px; }
  .modal-actions { display: flex; gap: 10px; margin-top: 8px; }
  .modal-actions .btn { margin-top: 0; }
  .btn-cancel { background: #eee; color: #333; }
  .btn-cancel:hover { background: #ddd; }
</style>
</head>
<body>
<div class="card">
  <h2>🛂 eVisa Admin Panel</h2>

  <div id="msgBox"></div>

  <!-- LOGIN -->
  <div id="loginPanel" class="active">
    <div class="form-group">
      <label>Username</label>
      <input type="text" id="adminUser" placeholder="admin">
    </div>
    <div class="form-group">
      <label>Password</label>
      <input type="password" id="adminPass" placeholder="••••••••">
    </div>
    <button class="btn" onclick="doLogin()">Login</button>
  </div>

  <!-- UPLOAD -->
  <div id="uploadPanel">
    <div class="logout-btn">
      <button onclick="doLogout()">Logout</button>
    </div>

    <div class="section-title">📤 Upload eVisa PDF</div>
    <div class="form-group">
      <label>E-Viza broj *</label>
      <input type="text" id="evisaNum" placeholder="AL27520639E">
    </div>
    <div class="form-group">
      <label>Broj pasoša *</label>
      <input type="text" id="passNum" placeholder="US1842281">
    </div>
    <div class="form-group">
      <label>Ime i prezime</label>
      <input type="text" id="clientName" placeholder="Muhammad Arslan Asghar">
    </div>
    <div class="form-group">
      <label>Custom Approval Message</label>
      <input type="text" id="visaMessage"
             placeholder="Congratulations! Your eVisa has been approved successfully.">
    </div>
    <div class="form-group">
      <label>Issue Date</label>
      <input type="date" id="issueDate">
    </div>
    <div class="form-group">
      <label>Valid Until</label>
      <input type="date" id="validUntil">
    </div>
    <div class="form-group">
      <label>PDF fajl *</label>
      <input type="file" id="pdfFile" accept=".pdf">
    </div>
    <button class="btn" onclick="doUpload()">Upload PDF</button>

    <div class="section-title">📋 Uploadovani dokumenti</div>
    <div id="recordsContainer">Učitavanje...</div>
  </div>
</div>

<!-- EDIT MODAL -->
<div class="modal-overlay" id="editModal">
  <div class="modal-box">
    <h3>✏️ Izmeni zapis</h3>
    <div class="form-group">
      <label>E-Viza broj *</label>
      <input type="text" id="editEvisaNum">
    </div>
    <div class="form-group">
      <label>Broj pasoša *</label>
      <input type="text" id="editPassNum">
    </div>
    <div class="form-group">
      <label>Ime i prezime</label>
      <input type="text" id="editClientName">
    </div>
    <div class="form-group">
      <label>Custom Approval Message</label>
      <input type="text" id="editVisaMessage">
    </div>
    <div class="form-group">
      <label>Issue Date</label>
      <input type="date" id="editIssueDate">
    </div>
    <div class="form-group">
      <label>Valid Until</label>
      <input type="date" id="editValidUntil">
    </div>
    <div class="form-group">
      <label>Replace PDF (optional)</label>
      <input type="file" id="editPdfFile" accept=".pdf">
    </div>
    <div class="modal-actions">
      <button class="btn btn-cancel" onclick="closeEditModal()">Otkaži</button>
      <button class="btn" onclick="saveEdit()">Sačuvaj</button>
    </div>
  </div>
</div>

<script>
function msg(text, type) {
  const box = document.getElementById('msgBox');
  box.innerHTML = `<div class="msg ${type}">${text}</div>`;
  setTimeout(() => box.innerHTML = '', 4000);
}

// Wraps fetch('admin.php', ...) so a bad/HTML/empty response never hangs
// silently — it always resolves to {success:false, message:...} instead.
async function postToAdmin(formData) {
  let res;
  try {
    res = await fetch('admin.php', { method: 'POST', body: formData });
  } catch (networkErr) {
    return { success: false, message: 'Ne mogu da se povežem sa serverom (admin.php). Proverite internet konekciju.' };
  }

  const raw = await res.text();
  try {
    return JSON.parse(raw);
  } catch (parseErr) {
    console.error('admin.php did not return valid JSON:', raw);
    return {
      success: false,
      message: 'Server je vratio neočekivan odgovor (HTTP ' + res.status + '). Proverite PHP error log na serveru.'
    };
  }
}

async function doLogin() {
  const user = document.getElementById('adminUser').value;
  const pass = document.getElementById('adminPass').value;
  const fd = new FormData();
  fd.append('action', 'login');
  fd.append('username', user);
  fd.append('password', pass);
  const data = await postToAdmin(fd);
  if (data.success) {
    document.getElementById('loginPanel').classList.remove('active');
    document.getElementById('uploadPanel').classList.add('active');
    loadRecords();
  } else {
    msg(data.message, 'error');
  }
}

async function doLogout() {
  document.getElementById('uploadPanel').classList.remove('active');
  document.getElementById('loginPanel').classList.add('active');
}

async function doUpload() {
  const fd = new FormData();
  fd.append('action', 'upload');
  fd.append('evisa_number', document.getElementById('evisaNum').value);
  fd.append('passport_number', document.getElementById('passNum').value);
  fd.append('name', document.getElementById('clientName').value);
  fd.append('message', document.getElementById('visaMessage').value);
  fd.append('issue_date', document.getElementById('issueDate').value);
  fd.append('valid_until', document.getElementById('validUntil').value);
  const pdfInput = document.getElementById('pdfFile');
  if (!pdfInput.files[0]) { msg('❌ Izaberite PDF fajl.', 'error'); return; }
  fd.append('pdf_file', pdfInput.files[0]);

  const data = await postToAdmin(fd);
  if (data.success) {
    msg('✅ ' + data.message, 'success');
    document.getElementById('evisaNum').value = '';
    document.getElementById('passNum').value = '';
    document.getElementById('clientName').value = '';
    document.getElementById('visaMessage').value = '';
    document.getElementById('issueDate').value = '';
    document.getElementById('validUntil').value = '';
    document.getElementById('pdfFile').value = '';
    loadRecords();
  } else {
    msg('❌ ' + data.message, 'error');
  }
}

async function loadRecords() {
  try {
    const res = await fetch('uploads/records.json?t=' + Date.now());
    const records = await res.json();
    const container = document.getElementById('recordsContainer');
    if (!records.length) { container.innerHTML = '<p style="color:#999;font-size:12px;">Nema uploadovanih dokumenata.</p>'; return; }
    let html = '<table class="records-table"><thead><tr><th>eVisa #</th><th>Pasoš</th><th>Ime</th><th>Važi do</th><th>Datum</th><th>PDF</th><th>Verify</th><th>Akcije</th></tr></thead><tbody>';
    records.forEach(r => {
      const evisaEsc = encodeURIComponent(r.evisa_number);
      const passEsc  = encodeURIComponent(r.passport_number);
      html += `<tr>
        <td>${r.evisa_number}</td>
        <td>${r.passport_number}</td>
        <td>${r.name || '-'}</td>
        <td>${r.valid_until || '-'}</td>
        <td>${r.uploaded_at || '-'}</td>
        <td><a href="uploads/${r.filename}" target="_blank">📄 Open</a></td>
        <td><a href="verify.php?id=${encodeURIComponent(r.evisa_number)}" target="_blank">🔎 Verify</a></td>
        <td>
          <button class="action-btn edit" onclick="openEditModal('${evisaEsc}','${passEsc}')">✏️ Edit</button>
          <button class="action-btn delete" onclick="deleteRecord('${evisaEsc}','${passEsc}')">🗑️ Delete</button>
        </td>
      </tr>`;
    });
    html += '</tbody></table>';
    container.innerHTML = html;
    window._allRecords = records; // cache for edit modal lookups
  } catch(e) {
    document.getElementById('recordsContainer').innerHTML = '<p style="color:#999;font-size:12px;">Nema zapisa još.</p>';
  }
}

// ── EDIT ──
let _editOriginal = null; // { evisa, passport } of the record currently being edited

function openEditModal(evisaEnc, passEnc) {
  const evisa = decodeURIComponent(evisaEnc);
  const passport = decodeURIComponent(passEnc);
  const rec = (window._allRecords || []).find(r =>
    r.evisa_number === evisa && r.passport_number === passport
  );
  if (!rec) { msg('❌ Zapis nije pronađen', 'error'); return; }

  _editOriginal = { evisa: rec.evisa_number, passport: rec.passport_number };

  document.getElementById('editEvisaNum').value = rec.evisa_number || '';
  document.getElementById('editPassNum').value = rec.passport_number || '';
  document.getElementById('editClientName').value = rec.name || '';
  document.getElementById('editVisaMessage').value = rec.message || '';
  document.getElementById('editIssueDate').value = rec.issue_date || '';
  document.getElementById('editValidUntil').value = rec.valid_until || '';
  document.getElementById('editPdfFile').value = '';

  document.getElementById('editModal').classList.add('active');
}

function closeEditModal() {
  document.getElementById('editModal').classList.remove('active');
  _editOriginal = null;
}

async function saveEdit() {
  if (!_editOriginal) return;

  const fd = new FormData();
  fd.append('action', 'edit');
  fd.append('original_evisa_number', _editOriginal.evisa);
  fd.append('original_passport_number', _editOriginal.passport);
  fd.append('evisa_number', document.getElementById('editEvisaNum').value);
  fd.append('passport_number', document.getElementById('editPassNum').value);
  fd.append('name', document.getElementById('editClientName').value);
  fd.append('message', document.getElementById('editVisaMessage').value);
  fd.append('issue_date', document.getElementById('editIssueDate').value);
  fd.append('valid_until', document.getElementById('editValidUntil').value);

  const fileInput = document.getElementById('editPdfFile');
  if (fileInput.files[0]) {
    fd.append('pdf_file', fileInput.files[0]);
  }

  const res = await postToAdmin(fd);
  const data = res;

  if (data.success) {
    msg('✅ ' + data.message, 'success');
    closeEditModal();
    loadRecords();
  } else {
    msg('❌ ' + data.message, 'error');
  }
}

// ── DELETE ──
async function deleteRecord(evisaEnc, passEnc) {
  const evisa = decodeURIComponent(evisaEnc);
  const passport = decodeURIComponent(passEnc);

  if (!confirm(`Da li ste sigurni da želite da obrišete zapis za ${evisa}?`)) return;

  const fd = new FormData();
  fd.append('action', 'delete');
  fd.append('evisa_number', evisa);
  fd.append('passport_number', passport);

  const data = await postToAdmin(fd);

  if (data.success) {
    msg('🗑️ ' + data.message, 'success');
    loadRecords();
  } else {
    msg('❌ ' + data.message, 'error');
  }
}
</script>
</body>
</html>
