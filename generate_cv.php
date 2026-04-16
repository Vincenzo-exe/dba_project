<?php

require_once 'config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die('<p style="font-family:sans-serif;color:red;padding:40px;">
         Invalid ID. <a href="index.html">← Back</a></p>');
}

$conn = getDB();
$res  = mysqli_query($conn, "SELECT * FROM resumes WHERE id=$id LIMIT 1");

if (!$res || mysqli_num_rows($res) === 0) {
    die('<p style="font-family:sans-serif;color:red;padding:40px;">
         Resume not found. <a href="index.html">← Back</a></p>');
}

$r = mysqli_fetch_assoc($res);

/* ── Fetch child records ── */
$skills = [];
$q = mysqli_query($conn, "SELECT skill_name FROM skills WHERE resume_id=$id ORDER BY id ASC");
while ($row = mysqli_fetch_assoc($q)) $skills[] = $row['skill_name'];

$edu = [];
$q   = mysqli_query($conn, "SELECT * FROM education WHERE resume_id=$id ORDER BY start_date DESC");
while ($row = mysqli_fetch_assoc($q)) $edu[] = $row;

$exp = [];
$q   = mysqli_query($conn, "SELECT * FROM experience WHERE resume_id=$id ORDER BY start_date DESC");
while ($row = mysqli_fetch_assoc($q)) $exp[] = $row;

$certs = [];
$q     = mysqli_query($conn, "SELECT cert_name FROM certifications WHERE resume_id=$id ORDER BY id ASC");
while ($row = mysqli_fetch_assoc($q)) $certs[] = $row['cert_name'];

mysqli_close($conn);

/* ── Helpers ── */
function h(?string $s): string {
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

function fd(?string $d): string {
    if (!$d || trim($d) === '') return 'Present';
    $ts = strtotime($d . '-01');
    return $ts ? date('M Y', $ts) : h($d);
}

function bullets(?string $text): string {
    if (!$text || trim($text) === '') return '';
    $lines = array_filter(array_map('trim', explode("\n", $text)));
    if (empty($lines)) return '';
    $out = '<ul class="cv2-bullets">';
    foreach ($lines as $l) $out .= '<li>' . h($l) . '</li>';
    return $out . '</ul>';
}

$fullName = h($r['prenom']) . ' ' . h($r['nom']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= $fullName ?> — Resume</title>
  <link rel="stylesheet" href="style.css"/>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
</head>
<body>
<div class="bg-grid"></div>
<div class="bg-glow"></div>

<!-- ── Site Header ── -->
<header class="site-header">
  <div class="logo">
    <span class="logo-icon">◈</span>
    <span class="logo-text">Resume Builder</span>
  </div>
  <p class="header-tagline">ATS-friendly CV in seconds</p>
</header>

<div class="cv-wrapper">
  <div class="cv-actions">
    <button class="cv-btn primary" onclick="window.print()">
      🖨 Print / Save as PDF
    </button>
    <a href="index.html" class="cv-btn secondary">← Build Another Resume</a>
  </div>

  <div id="resumeDocument" class="cv2-doc">

    <!-- ══ PERSONAL INFORMATION ════════════════════════════ -->
    <div class="cv2-name"><?= $fullName ?></div>
    <div class="cv2-subtitle"><?= h($r['subtext']) ?></div>

    <div class="cv2-contact">
      <?= h($r['phone']) ?>
      <?php if ($r['phone'] && $r['email']): ?> &bull; <?php endif; ?>
      <?= h($r['email']) ?>
      <?php if ($r['city']): ?> &bull; <?= h($r['city']) ?> <?php endif; ?>
    </div>

    <!-- ══ PROFESSIONAL SUMMARY ════════════════════════════ -->
    <?php if ($r['summary']): ?>
    <div class="cv2-section">
      <div class="cv2-section-title">Professional Summary</div>
      <p style="font-size:11pt; line-height:1.5; margin:0;">
        <?= nl2br(h($r['summary'])) ?>
      </p>
    </div>
    <?php endif; ?>

    <!-- ══ SKILLS ══════════════════════════════════════════ -->
    <?php if (!empty($skills)): ?>
    <div class="cv2-section">
      <div class="cv2-section-title">Skills</div>
      <p style="font-size:11pt; line-height:1.5; margin:0;">
        <?= h(implode(', ', $skills)) ?>
      </p>
    </div>
    <?php endif; ?>

    <!-- ══ EDUCATION ═══════════════════════════════════════ -->
    <?php if (!empty($edu)): ?>
    <div class="cv2-section">
      <div class="cv2-section-title">Education</div>

      <?php foreach ($edu as $e): ?>
      <div class="cv2-edu-entry">
        <div class="cv2-edu-school-row">
          <span class="cv2-edu-school"><strong><?= h($e['school']) ?></strong></span>
          <span class="cv2-edu-city"><?= h($r['city']) ?>, Morocco</span>
        </div>

        <div class="cv2-edu-degree-row">
          <span class="cv2-edu-degree"><?= h($e['description']) ?></span>
          <span class="cv2-edu-dates">
            <?= fd($e['start_date']) ?> – <?= fd($e['end_date']) ?>
          </span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- ══ WORK EXPERIENCE ═════════════════════════════════ -->
    <?php if (!empty($exp)): ?>
    <div class="cv2-section">
      <div class="cv2-section-title">Work Experience</div>

      <?php foreach ($exp as $e): ?>
      <div class="cv2-exp-entry">
        <div class="cv2-exp-company-row">
          <span class="cv2-exp-company"><strong><?= h($e['company']) ?></strong></span>
          <span class="cv2-exp-location">
            <?= $e['location'] ? h($e['location']) : h($r['city']) . ', Morocco' ?>
          </span>
        </div>

        <div class="cv2-exp-role-row">
          <span class="cv2-exp-role"><em><?= h($e['role_title']) ?></em></span>
          <span class="cv2-exp-dates">
            <?= fd($e['start_date']) ?> – <?= fd($e['end_date']) ?>
          </span>
        </div>

        <?= bullets($e['description']) ?>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- ══ CERTIFICATIONS ══════════════════════════════════ -->
    <?php if (!empty($certs)): ?>
    <div class="cv2-section">
      <div class="cv2-section-title">Certifications</div>
      <ul class="cv2-cert-list">
        <?php foreach ($certs as $c): ?>
        <li class="cv2-cert-item"><?= h($c) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; ?>

  </div><!-- /.cv2-doc -->
</div><!-- /.cv-wrapper -->

<footer class="site-footer">
  <p>Generated by Resume Builder &mdash; <?= date('F j, Y') ?></p>
</footer>

</body>
</html>
