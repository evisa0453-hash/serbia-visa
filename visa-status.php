<?php
// ============================================================
// Visa Status Page — shown after successful login
// Reads record by evisa_number + passport_number from records.json
// ============================================================

$recordsFile = "uploads/records.json";

$evisa    = trim($_GET['evisa'] ?? '');
$passport = trim($_GET['passport'] ?? '');

$record = null;

if ($evisa !== '' && $passport !== '' && file_exists($recordsFile)) {
    $records = json_decode(file_get_contents($recordsFile), true) ?? [];
    foreach ($records as $r) {
        if (
            strtoupper($r['evisa_number'] ?? '')    === strtoupper($evisa) &&
            strtoupper($r['passport_number'] ?? '') === strtoupper($passport)
        ) {
            $record = $r;
            break;
        }
    }
}

// Build verification URL (used inside QR code)
$verifyUrl = '';
if ($record) {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $base   = rtrim(str_replace('visa-status.php', '', $_SERVER['PHP_SELF']), '/');
    $verifyUrl = $scheme . '://' . $host . $base . '/verify.php?id=' . urlencode($record['evisa_number']);
}
?>
<!DOCTYPE html>
<html lang="sr-Cyrl-RS">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eVisa Status</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="styles.css">
    <script src="lib/qrcode.min.js"></script>
    <style>
        /* ---- Visa Status page-specific styles (reuses theme vars from styles.css) ---- */
        .status-wrap {
            max-width: 820px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .status-card {
            background: var(--bg-card);
            border-radius: 10px;
            box-shadow: 0 10px 35px rgba(27,50,95,.12);
            overflow: hidden;
        }
        .status-head {
            background: linear-gradient(135deg, var(--btn-submit-bg) 0%, var(--text-primary) 130%);
            color: #fff;
            padding: 34px 30px 28px;
            text-align: center;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,.18);
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }
        .status-badge .dot { width: 8px; height: 8px; border-radius: 50%; background: #fff; display: inline-block; }
        .status-head h2 { font-size: 24px; font-weight: 700; margin-bottom: 8px; font-family: var(--font-heading); }
        .status-head p.msg { font-size: 14px; opacity: .95; max-width: 480px; margin: 0 auto; line-height: 1.5; }

        .status-body { padding: 30px; }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 28px;
        }
        .info-item {
            background: var(--bg-page);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 14px 16px;
        }
        .info-item .label {
            font-size: 10.5px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .info-item .value {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-primary);
            word-break: break-word;
        }
        .info-item.full { grid-column: 1 / -1; }

        .qr-section {
            display: flex;
            align-items: center;
            gap: 24px;
            background: var(--bg-page);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 22px;
            margin-bottom: 26px;
            flex-wrap: wrap;
        }
        .qr-box {
            width: 150px; height: 150px;
            background: #fff;
            border-radius: 6px;
            padding: 8px;
            box-shadow: 0 4px 14px rgba(0,0,0,.08);
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .qr-box img, .qr-box canvas { width: 100%; height: 100%; }
        .qr-info { flex: 1; min-width: 200px; }
        .qr-info h4 { font-size: 14px; color: var(--text-primary); margin-bottom: 6px; }
        .qr-info p { font-size: 12.5px; color: var(--text-secondary); line-height: 1.5; }

        .status-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }
        .status-actions .btn {
            flex: 1;
            min-width: 180px;
            text-align: center;
            padding: 14px 20px;
            border-radius: 6px;
            font-size: 14.5px;
            font-weight: 700;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: transform .15s, box-shadow .15s;
        }
        .btn-pdf {
            background: var(--btn-submit-bg);
            color: #fff;
            box-shadow: 0 8px 20px rgba(0,122,51,.25);
        }
        .btn-pdf:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(0,122,51,.35); }
        .btn-back {
            background: #fff;
            color: var(--text-primary);
            border: 1.5px solid var(--border-color);
        }
        .btn-back:hover { background: var(--bg-page); }

        .error-card {
            background: var(--bg-card);
            border-radius: 10px;
            box-shadow: 0 10px 35px rgba(27,50,95,.12);
            padding: 50px 30px;
            text-align: center;
        }
        .error-icon {
            width: 72px; height: 72px;
            border-radius: 50%;
            background: #fdecea;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
        }
        .error-icon svg { width: 34px; height: 34px; stroke: #c0392b; }
        .error-card h2 { color: #c0392b; font-size: 20px; margin-bottom: 10px; font-family: var(--font-heading); }
        .error-card p { color: var(--text-secondary); font-size: 14px; margin-bottom: 24px; }

        @media (max-width: 560px) {
            .info-grid { grid-template-columns: 1fr; }
            .qr-section { flex-direction: column; text-align: center; }
            .status-actions { flex-direction: column; }
        }
    </style>
</head>
<body class="theme-default font-normal">

    <!-- Flag stripes at the very top (Serbian Tricolor red-blue-white) -->
    <div class="top-flag-stripes">
        <div class="stripe red"></div>
        <div class="stripe blue"></div>
        <div class="stripe white"></div>
    </div>

    <div class="page-wrapper">

        <!-- Header (same as login page) -->
        <header class="main-header">
            <div class="top-header-bar">
                <div class="top-header-container">
                    <div class="language-dropdown-container">
                        <button class="lang-selector-btn" disabled style="opacity:.6;cursor:default;">
                            <span>Ћирилица</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="header-body">
                <div class="header-body-container">
                    <div class="branding">
                        <a href="index.html" class="logo-link">
                            <div class="eid-circle-logo">
                                <svg viewBox="0 0 100 110" class="eid-svg">
                                    <path d="M 28,48 H 72 A 22,22 0 1,0 65,64" fill="none" stroke="currentColor" stroke-width="6" stroke-linecap="round"/>
                                    <line x1="38" y1="78" x2="62" y2="78" stroke="#c92c3b" stroke-width="5" stroke-linecap="round"/>
                                    <line x1="38" y1="86" x2="62" y2="86" stroke="currentColor" stroke-width="5" stroke-linecap="round"/>
                                    <line x1="38" y1="94" x2="62" y2="94" stroke="#9ca3af" stroke-width="5" stroke-linecap="round"/>
                                </svg>
                            </div>
                            <div class="logo-text-wrapper">
                                <div class="logo-text-row">
                                    <span class="logo-bold">eID</span><span class="logo-domain">.gov.rs</span>
                                </div>
                                <h1 class="logo-title">Портал за електронску идентификацију</h1>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="nav-bar">
                <div class="nav-container">
                    <nav class="header-nav">
                        <ul>
                            <li><a href="#" onclick="return false;">еГрађанин</a></li>
                            <li class="nav-divider">|</li>
                            <li><a href="#" onclick="return false;">Потпис у клауду</a></li>
                            <li class="nav-divider">|</li>
                            <li><a href="#" onclick="return false;">Помоћ</a></li>
                            <li class="nav-divider">|</li>
                            <li><a href="#" onclick="return false;">Контакт</a></li>
                            <li class="nav-divider">|</li>
                            <li><a href="#" onclick="return false;" class="nav-highlight">Портал еУправа</a></li>
                            <li class="nav-divider">|</li>
                            <li><a href="index.html">Назад</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Visa Status Content -->
        <main>
        <div class="status-wrap">

        <?php if ($record): ?>
          <div class="status-card">
            <div class="status-head">
              <div class="status-badge"><span class="dot"></span> Approved</div>
              <h2>Congratulations, <?php echo htmlspecialchars($record['name'] ?: 'Applicant'); ?>!</h2>
              <p class="msg"><?php echo htmlspecialchars($record['message'] ?: 'Your eVisa has been approved successfully.'); ?></p>
            </div>

            <div class="status-body">
              <div class="info-grid">
                <div class="info-item full">
                  <div class="label">Full Name</div>
                  <div class="value"><?php echo htmlspecialchars($record['name'] ?: '-'); ?></div>
                </div>
                <div class="info-item">
                  <div class="label">Passport Number</div>
                  <div class="value"><?php echo htmlspecialchars($record['passport_number']); ?></div>
                </div>
                <div class="info-item">
                  <div class="label">eVisa Number</div>
                  <div class="value"><?php echo htmlspecialchars($record['evisa_number']); ?></div>
                </div>
                <div class="info-item">
                  <div class="label">Issue Date</div>
                  <div class="value"><?php echo htmlspecialchars($record['issue_date'] ?: '-'); ?></div>
                </div>
                <div class="info-item">
                  <div class="label">Valid Until</div>
                  <div class="value"><?php echo htmlspecialchars($record['valid_until'] ?: '-'); ?></div>
                </div>
              </div>

              <div class="qr-section">
                <div class="qr-box" id="qrBox"></div>
                <div class="qr-info">
                  <h4>eVisa No and Passport No is a basic level of assurance verification</h4>
                  <p>Scan this QR code to confirm the authenticity of this eVisa record on the official verification page.</p>
                </div>
              </div>

              <div class="status-actions">
                <a class="btn btn-pdf" href="uploads/<?php echo htmlspecialchars($record['filename']); ?>" target="_blank" rel="noopener noreferrer">
                  📄 Open PDF
                </a>
                <a class="btn btn-back" href="index.html">← Back to Portal</a>
              </div>
            </div>
          </div>

          <script>
            new QRCode(document.getElementById("qrBox"), {
              text: <?php echo json_encode($verifyUrl); ?>,
              width: 134,
              height: 134,
              colorDark: "#1b325f",
              colorLight: "#ffffff",
              correctLevel: QRCode.CorrectLevel.M
            });
          </script>

        <?php else: ?>
          <div class="error-card">
            <div class="error-icon">
              <svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
              </svg>
            </div>
            <h2>Document Not Found</h2>
            <p>No eVisa record matches the details provided. Please check your eVisa number and passport number and try again.</p>
            <a class="btn btn-back" style="display:inline-block;max-width:220px;padding:12px 24px;border-radius:6px;text-decoration:none;border:1.5px solid var(--border-color);color:var(--text-primary);" href="index.html">← Back to Portal</a>
          </div>
        <?php endif; ?>

        </div>
        </main>

        <!-- Footer (same as login page) -->
        <footer class="main-footer">
            <div class="footer-top-anchor">
                <div class="footer-top-container">
                    <a href="#" class="to-top-btn" onclick="window.scrollTo({top:0,behavior:'smooth'});return false;">
                        <span>Врх стране ↑</span>
                    </a>
                </div>
            </div>

            <div class="footer-main-content">
                <div class="footer-container">
                    <div class="footer-branding">
                        <img src="serbian-coat-of-arms.png" alt="Grb Srbije" class="footer-logo">
                        <div class="footer-text-wrapper">
                            <h3>eid.gov.rs</h3>
                            <p>Портал за електронску идентификацију</p>
                        </div>
                    </div>

                    <div class="footer-license">
                        Веб презентација је лиценцирана под условима лиценце Creative Commons Ауторство-Некомерцијално-Без прерада 3.0 Србија. Веб пројекат <a href="https://www.ite.gov.rs/" target="_blank">ite.gov.rs</a>
                    </div>

                    <nav class="footer-nav">
                        <ul>
                            <li><a href="#" onclick="return false;">Изјава о приватности</a></li>
                            <li><a href="#" onclick="return false;">Услови коришћења</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </footer>

    </div>
</body>
</html>
