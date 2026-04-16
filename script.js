let eduCount = 1;
let expCount = 0;
let skillCount = 1;

/* ── Required fields for progress bar ── */
const REQUIRED = ['prenom','nom','subtext','phone','email','city','summary'];

function updateProgress() {
  let filled = 0;
  REQUIRED.forEach(id => {
    const el = document.getElementById(id);
    if (el && el.value.trim() !== '') filled++;
  });
  const pct = Math.round((filled / REQUIRED.length) * 100);
  document.getElementById('progressBar').style.width = pct + '%';
  document.getElementById('progressLabel').textContent = pct + '% Complete';
}

document.addEventListener('DOMContentLoaded', () => {
  /* Attach progress listeners */
  REQUIRED.forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', updateProgress);
  });

  /* Summary char counter */
  const summary = document.getElementById('summary');
  const counter = document.getElementById('summaryCount');
  if (summary && counter) {
    summary.addEventListener('input', () => {
      const l = summary.value.length;
      counter.textContent = l;
      counter.style.color = l > 550 ? '#f87171' : '';
    });
  }

  /* Form submit */
  const form = document.getElementById('resumeForm');
  if (form) form.addEventListener('submit', handleSubmit);
});

/* ── ADD EDUCATION ─────────────────────────────────────────── */
function addEducation() {
  eduCount++;
  const container = document.getElementById('educationContainer');
  const div = document.createElement('div');
  div.className = 'dynamic-entry';
  div.innerHTML = `
    <div class="entry-header">
      <span class="entry-label">Education Entry ${eduCount}</span>
      <button type="button" class="btn-remove-entry" onclick="removeEntry(this)">✕ Remove</button>
    </div>
    <div class="field-group">
      <label>School / University</label>
      <input type="text" name="edu_school[]" placeholder="e.g. ISCAE, Casablanca"/>
    </div>
    <div class="field-group">
      <label>Degree &amp; Relevant Courses</label>
      <textarea name="edu_desc[]" rows="3"
        placeholder="e.g. Master in Finance. Relevant Courses: Corporate Finance, Derivatives, Financial Modelling."></textarea>
    </div>
    <div class="form-grid two-col">
      <div class="field-group">
        <label>Start Date</label>
        <input type="month" name="edu_start[]"/>
      </div>
      <div class="field-group">
        <label>End Date (or Expected)</label>
        <input type="month" name="edu_end[]"/>
      </div>
    </div>
  `;
  container.appendChild(div);
  div.querySelector('input[type="text"]').focus();
}

function addExperience() {
  expCount++;
  const container = document.getElementById('experienceContainer');
  const div = document.createElement('div');
  div.className = 'dynamic-entry';
  div.innerHTML = `
    <div class="entry-header">
      <span class="entry-label">Experience #${expCount}</span>
      <button type="button" class="btn-remove-entry" onclick="removeEntry(this)">✕ Remove</button>
    </div>

    <div class="form-grid two-col">
      <div class="field-group">
        <label>Company / Organisation</label>
        <input type="text" name="exp_company[]"
          placeholder="e.g. Attijariwafa Bank"/>
      </div>
      <div class="field-group">
        <label>Company Location</label>
        <input type="text" name="exp_location[]"
          placeholder="e.g. Casablanca, Morocco"/>
      </div>
    </div>

    <div class="form-grid two-col">
      <div class="field-group">
        <label>Job Title / Role</label>
        <input type="text" name="exp_role[]"
          placeholder="e.g. Capital Markets Intern"/>
      </div>
      <div class="field-group" style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
        <div>
          <label>Start Date</label>
          <input type="month" name="exp_start[]"/>
        </div>
        <div>
          <label>End Date</label>
          <input type="month" name="exp_end[]"/>
        </div>
      </div>
    </div>

    <div class="field-group">
      <label>Key Achievements (one per line)</label>
      <textarea name="exp_desc[]" rows="4"
        placeholder="Researched and analysed portfolio holdings worth MAD 4.2B&#10;Built an Excel model that reduced reporting time by 35%&#10;Presented ESG investment findings to the strategy committee"></textarea>
    </div>
  `;
  container.appendChild(div);
  div.querySelector('input[type="text"]').focus();
}

/* ── ADD SKILL ─────────────────────────────────────────────── */
function addSkill() {
  const container = document.getElementById('skillsContainer');
  const row = document.createElement('div');
  row.className = 'skill-row';
  row.innerHTML = `
    <input type="text" name="skills[]" class="skill-input"
      placeholder="e.g. JavaScript, React, Node.js"/>
    <button type="button" class="btn-remove" onclick="removeRow(this)">✕</button>
  `;
  container.appendChild(row);
  row.querySelector('input').focus();
}

/* ── ADD CERTIFICATION ROW ─────────────────────────────────── */
function addCert() {
  const container = document.getElementById('certContainer');
  const row = document.createElement('div');
  row.className = 'skill-row';
  row.innerHTML = `
    <input type="text" name="certifications[]" class="skill-input"
      placeholder="e.g. Bloomberg Market Concepts — Bloomberg LP (2023)"/>
    <button type="button" class="btn-remove" onclick="removeRow(this)">✕</button>
  `;
  container.appendChild(row);
  row.querySelector('input').focus();
}

/* ── REMOVE ROW ────────────────────────────────────────────── */
function removeRow(btn) {
  const row = btn.closest('.skill-row');
  if (!row) return;
  const siblings = row.parentElement.querySelectorAll('.skill-row');
  if (siblings.length <= 1) { row.querySelector('input').value = ''; return; }
  animateOut(row);
}

/* ── REMOVE DYNAMIC ENTRY ──────────────────────────────────── */
function removeEntry(btn) {
  const entry = btn.closest('.dynamic-entry');
  if (!entry) return;
  animateOut(entry);
}

function animateOut(el) {
  el.style.transition = 'all 0.22s ease';
  el.style.opacity = '0';
  el.style.transform = 'translateY(-6px) scale(0.98)';
  el.style.overflow = 'hidden';
  el.style.maxHeight = el.offsetHeight + 'px';
  setTimeout(() => { el.style.maxHeight = '0'; el.style.padding = '0'; el.style.margin = '0'; }, 50);
  setTimeout(() => el.remove(), 300);
}

/* ── VALIDATION ────────────────────────────────────────────── */
function validateForm() {
  let valid = true;
  document.querySelectorAll('.field-error').forEach(e => e.textContent = '');
  document.querySelectorAll('.error').forEach(e => e.classList.remove('error'));

  const checks = [
    { id: 'prenom',  msg: 'First name is required.' },
    { id: 'nom',     msg: 'Last name is required.' },
    { id: 'subtext', msg: 'Job title is required.' },
    { id: 'phone',   msg: 'Phone number is required.' },
    { id: 'email',   msg: 'Email address is required.' },
    { id: 'city',    msg: 'Please select your city.' },
    { id: 'summary', msg: 'Professional summary is required.' },
  ];

  checks.forEach(({ id, msg }) => {
    const el  = document.getElementById(id);
    const err = document.getElementById('err-' + id);
    if (el && el.value.trim() === '') {
      el.classList.add('error');
      if (err) err.textContent = msg;
      valid = false;
    }
  });

  const emailEl = document.getElementById('email');
  if (emailEl && emailEl.value.trim()) {
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailEl.value)) {
      emailEl.classList.add('error');
      const err = document.getElementById('err-email');
      if (err) err.textContent = 'Please enter a valid email address.';
      valid = false;
    }
  }

  const sumEl = document.getElementById('summary');
  if (sumEl && sumEl.value.length > 600) {
    sumEl.classList.add('error');
    const err = document.getElementById('err-summary');
    if (err) err.textContent = 'Summary must be under 600 characters.';
    valid = false;
  }

  return valid;
}

/* ── SUBMIT HANDLER ────────────────────────────────────────── */
function handleSubmit(e) {
  if (!validateForm()) {
    e.preventDefault();
    const first = document.querySelector('.error');
    if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
    showFlash('Please fix the errors before submitting.', 'err');
    return;
  }
  const btn = document.getElementById('submitBtn');
  if (btn) { btn.style.opacity = '0.7'; btn.style.pointerEvents = 'none'; }
}

/* ── FLASH MESSAGE ─────────────────────────────────────────── */
function showFlash(msg, type = 'success') {
  const ex = document.querySelector('.flash-msg');
  if (ex) ex.remove();
  const d = document.createElement('div');
  d.className = `flash-msg ${type}`;
  d.textContent = msg;
  document.body.appendChild(d);
  setTimeout(() => {
    d.style.opacity = '0'; d.style.transform = 'translateX(28px)'; d.style.transition = 'all 0.28s ease';
    setTimeout(() => d.remove(), 300);
  }, 4200);
}

/* ── Section focus highlight ───────────────────────────────── */
document.addEventListener('focusin', e => {
  if (['INPUT','TEXTAREA','SELECT'].includes(e.target.tagName)) {
    const s = e.target.closest('.form-section');
    if (s) s.style.borderColor = 'rgba(139,92,246,0.3)';
  }
});
document.addEventListener('focusout', e => {
  if (['INPUT','TEXTAREA','SELECT'].includes(e.target.tagName)) {
    const s = e.target.closest('.form-section');
    if (s) s.style.borderColor = '';
  }
});
