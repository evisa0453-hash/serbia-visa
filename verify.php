<?php
$recordsFile = "uploads/records.json";
$id = trim($_GET['id'] ?? '');

$record = null;
if ($id !== '' && file_exists($recordsFile)) {
    $records = json_decode(file_get_contents($recordsFile), true) ?? [];
    foreach ($records as $r) {
        if (strtoupper($r['evisa_number'] ?? '') === strtoupper($id)) {
            $record = $r;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>eVisa Verification</title>
<style>
  :root {
    --navy: #0f2f5c;
    --green: #1aa64b;
    --green-dark: #0b7d34;
    --red: #c0392b;
    --card-shadow: 0 20px 50px rgba(15,47,92,.12);
  }
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: linear-gradient(180deg, #eef2f8 0%, #e4eaf5 100%);
    min-height: 100vh;
    padding: 40px 16px;
    display: flex;
    align-items: flex-start;
    justify-content: center;
  }
  .wrap { width: 100%; max-width: 640px; }

  .card {
    background: #fff;
    border-radius: 18px;
    box-shadow: var(--card-shadow);
    overflow: hidden;
  }

  .head {
    background: linear-gradient(135deg, var(--green) 0%, var(--green-dark) 100%);
    color: #fff;
    padding: 34px 30px 26px;
    text-align: center;
  }
  .head .icon {
    width: 60px; height: 60px; border-radius: 50%;
    background: rgba(255,255,255,.2);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 14px;
  }
  .head .icon svg { width: 30px; height: 30px; stroke: #fff; }
  .head h1 { font-size: 21px; font-weight: 700; margin-bottom: 8px; }
  .status-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.18);
    padding: 5px 14px; border-radius: 30px;
    font-size: 11.5px; font-weight: 700; letter-spacing: .5px; text-transform: uppercase;
  }
  .status-badge .dot { width: 7px; height: 7px; border-radius: 50%; background: #fff; }

  .body { padding: 28px 30px; }
  .info-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 13px 0; border-bottom: 1px solid #edf0f6;
    font-size: 13.5px;
  }
  .info-row:last-of-type { border-bottom: none; }
  .info-row .k { color: #8a94a6; font-weight: 600; }
  .info-row .v { color: var(--navy); font-weight: 700; text-align: right; }

  .btn {
    display: block; text-align: center; margin-top: 24px;
    padding: 13px 20px; border-radius: 10px;
    background: linear-gradient(135deg, var(--green) 0%, var(--green-dark) 100%);
    color: #fff; text-decoration: none; font-weight: 700; font-size: 14px;
    box-shadow: 0 8px 20px rgba(26,166,75,.3);
  }

  .error-card {
    background: #fff; border-radius: 18px; box-shadow: var(--card-shadow);
    padding: 50px 30px; text-align: center;
  }
  .error-icon {
    width: 72px; height: 72px; border-radius: 50%; background: #fdecea;
    display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;
  }
  .error-icon svg { width: 34px; height: 34px; stroke: var(--red); }
  .error-card h2 { color: var(--red); font-size: 20px; margin-bottom: 10px; }
  .error-card p { color: #6b7686; font-size: 14px; }
</style>
</head>
<body>
<div class="wrap">

<?php if ($record): ?>
  <div class="card">
    <div class="head">
      <div class="icon">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
      </div>
      <h1>eVisa Verified</h1>
      <div class="status-badge"><span class="dot"></span> Approved</div>
    </div>
    <div class="body">
      <div class="info-row"><span class="k">Name</span><span class="v"><?php echo htmlspecialchars($record['name'] ?: '-'); ?></span></div>
      <div class="info-row"><span class="k">Passport Number</span><span class="v"><?php echo htmlspecialchars($record['passport_number'] ?? '-'); ?></span></div>
      <div class="info-row"><span class="k">eVisa Number</span><span class="v"><?php echo htmlspecialchars($record['evisa_number'] ?? '-'); ?></span></div>
      <div class="info-row"><span class="k">Issue Date</span><span class="v"><?php echo htmlspecialchars($record['issue_date'] ?: '-'); ?></span></div>
      <div class="info-row"><span class="k">Valid Until</span><span class="v"><?php echo htmlspecialchars($record['valid_until'] ?: '-'); ?></span></div>

      <a class="btn" href="<?php echo 'uploads/' . htmlspecialchars($record['filename']); ?>" target="_blank" rel="noopener noreferrer">
        📄 Open PDF
      </a>
    </div>
  </div>
<?php else: ?>
  <div class="error-card">
    <div class="error-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <line x1="18" y1="6" x2="6" y2="18"></line>
        <line x1="6" y1="6" x2="18" y2="18"></line>
      </svg>
    </div>
    <h2>Verification Failed</h2>
    <p>Document Not Found</p>
  </div>
<?php endif; ?>

</div>
</body>
</html>
