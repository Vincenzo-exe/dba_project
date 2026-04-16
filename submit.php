<?php
/* ═══════════════════════════════════════════════════════════════
   submit.php — Validate form & store in MySQL (via Workbench)
   Template 2: captures company, role_title, location, dates, bullets
═══════════════════════════════════════════════════════════════ */

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

$conn   = getDB();
$errors = [];

/* ── Helper: require a POST field ── */
function req(string $key, string $label): string {
    global $errors;
    $val = trim($_POST[$key] ?? '');
    if ($val === '') $errors[] = "$label is required.";
    return $val;
}

/* ── Collect & validate ── */
$prenom  = req('prenom',  'First name');
$nom     = req('nom',     'Last name');
$subtext = req('subtext', 'Job title');
$phone   = req('phone',   'Phone number');
$email   = req('email',   'Email');
$city    = req('city',    'City');
$summary = req('summary', 'Professional summary');

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email address.';
}
if (strlen($summary) > 600) {
    $errors[] = 'Summary must be under 600 characters.';
}

if (!empty($errors)) {
    $msg = urlencode(implode(' | ', $errors));
    header("Location: index.html?error=$msg");
    exit;
}

/* ── Escape helpers ── */
$prenom  = esc($conn, $prenom);
$nom     = esc($conn, $nom);
$subtext = esc($conn, $subtext);
$phone   = esc($conn, $phone);
$email   = esc($conn, $email);
$city    = esc($conn, $city);
$summary = esc($conn, $summary);

/* ── 1. INSERT main resume ── */
$sql = "INSERT INTO resumes (prenom, nom, subtext, phone, email, city, summary, created_at)
        VALUES ('$prenom','$nom','$subtext','$phone','$email','$city','$summary', NOW())";

if (!mysqli_query($conn, $sql)) {
    die('DB error (resumes): ' . mysqli_error($conn));
}
$resume_id = mysqli_insert_id($conn);

/* ── 2. Skills (3 fixed inputs: language, computer, interests) ── */
$skills = $_POST['skills'] ?? [];
foreach ($skills as $skill) {
    if (trim($skill) === '') continue;
    if (strlen($skill) > 500) {
        $errors[] = "Skill exceeds 500 character limit.";
        continue;
    }
    $s = esc($conn, $skill);
    if (!mysqli_query($conn, "INSERT INTO skills (resume_id, skill_name) VALUES ($resume_id, '$s')")) {
        die('DB error (skills): ' . mysqli_error($conn));
    }
}

/* ── 3. Education ── */
$edu_schools = $_POST['edu_school'] ?? [];
$edu_descs   = $_POST['edu_desc']   ?? [];
$edu_starts  = $_POST['edu_start']  ?? [];
$edu_ends    = $_POST['edu_end']    ?? [];

for ($i = 0; $i < count($edu_schools); $i++) {
    $school = esc($conn, $edu_schools[$i] ?? '');
    $desc   = esc($conn, $edu_descs[$i]   ?? '');
    $start  = esc($conn, $edu_starts[$i]  ?? '');
    $end    = esc($conn, $edu_ends[$i]    ?? '');
    if ($school === '') continue;
    $sql = "INSERT INTO education (resume_id, school, start_date, end_date, description)
            VALUES ($resume_id, '$school', '$start', '$end', '$desc')";
    if (!mysqli_query($conn, $sql)) die('DB error (education): ' . mysqli_error($conn));
}

/* ── 4. Experience (company + role_title + location + dates + bullets) ── */
$exp_companies = $_POST['exp_company']  ?? [];
$exp_roles     = $_POST['exp_role']     ?? [];
$exp_locations = $_POST['exp_location'] ?? [];
$exp_starts    = $_POST['exp_start']    ?? [];
$exp_ends      = $_POST['exp_end']      ?? [];
$exp_descs     = $_POST['exp_desc']     ?? [];

for ($i = 0; $i < count($exp_companies); $i++) {
    $company  = esc($conn, $exp_companies[$i] ?? '');
    $role     = esc($conn, $exp_roles[$i]     ?? '');
    $location = esc($conn, $exp_locations[$i] ?? '');
    $start    = esc($conn, $exp_starts[$i]    ?? '');
    $end      = esc($conn, $exp_ends[$i]      ?? '');
    $desc     = esc($conn, $exp_descs[$i]     ?? '');
    if ($company === '') continue;
    $sql = "INSERT INTO experience (resume_id, company, role_title, location, start_date, end_date, description)
            VALUES ($resume_id, '$company', '$role', '$location', '$start', '$end', '$desc')";
    if (!mysqli_query($conn, $sql)) die('DB error (experience): ' . mysqli_error($conn));
}

/* ── 5. Certifications / Leadership ── */
$certs = $_POST['certifications'] ?? [];
foreach ($certs as $cert) {
    if (trim($cert) === '') continue;
    if (strlen($cert) > 350) {
        $errors[] = "Certification exceeds 350 character limit.";
        continue;
    }
    $c = esc($conn, $cert);
    if (!mysqli_query($conn, "INSERT INTO certifications (resume_id, cert_name) VALUES ($resume_id, '$c')")) {
        die('DB error (certifications): ' . mysqli_error($conn));
    }
}

mysqli_close($conn);

/* ── Redirect to the CV ── */
header("Location: generate_cv.php?id=$resume_id");
exit;
