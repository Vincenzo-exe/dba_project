# ResumeForge — Setup Guide
## Template 2: Finance / Business · MySQL Workbench

---

## 📦 Project Files

```
resume-builder/
├── index.html        ← The form (fill in your resume info)
├── style.css         ← Dark UI + white ATS document styles
├── script.js         ← Dynamic fields + validation
├── config.php        ← MySQL Workbench connection settings
├── submit.php        ← Saves form data to MySQL
├── generate_cv.php   ← Fetches from DB → renders Template 2 CV
├── database.sql      ← Schema + sample data (run in Workbench)
└── README.md         ← This file
```

---

## ✅ Step 1 — Install XAMPP

1. Download from: **https://www.apachefriends.org/download.html**
2. Run the installer (default options are fine)
3. Install to `C:\xampp\`

---

## ▶️ Step 2 — Start Apache & MySQL in XAMPP

1. Open **XAMPP Control Panel**
2. Click **Start** next to **Apache** → turns green
3. Click **Start** next to **MySQL** → turns green

> MySQL must be running for Workbench and PHP to connect.

---

## 📁 Step 3 — Place Project Files

Copy all files into:
```
C:\xampp\htdocs\resume-builder\
```

Your folder should look like:
```
C:\xampp\htdocs\resume-builder\
  ├── index.html
  ├── style.css
  ├── script.js
  ├── config.php
  ├── submit.php
  ├── generate_cv.php
  └── database.sql
```

---

## 🗄 Step 4 — Create the Database in MySQL Workbench

Your Workbench connection (from your screenshot):
| Setting         | Value       |
|-----------------|-------------|
| Connection Name | local host  |
| Hostname        | 127.0.0.1   |
| Port            | 3306        |
| Username        | root        |
| Password        | *(empty)*   |

### Steps:
1. Open **MySQL Workbench**
2. Double-click **"local host"** connection
3. Enter your password if prompted (leave blank if none)
4. Go to **File → Open SQL Script...**
5. Browse to `C:\xampp\htdocs\resume-builder\database.sql`
6. Click **Open**
7. Press **Ctrl + Shift + Enter** (or click the ⚡ lightning bolt)
8. All 7 queries will run — you'll see green checkmarks in the Output panel

You should now see **resumeforge** in the Schemas panel on the left with these tables:
- `resumes`
- `skills`
- `education`
- `experience`
- `certifications`

---

## 🌐 Step 5 — Open the App in Your Browser

```
http://localhost/resume-builder/index.html
```

1. Fill in the form
2. Click **Generate My Resume**
3. You'll be redirected to your generated **Template 2** resume
4. Click **Print / Save as PDF** to download it

---

## 📄 Template 2 Layout (Finance / Business)

Your generated CV will exactly match this structure:

```
Name
Phone • Email

EDUCATION
School Name                               City, Morocco
Degree (italic)                           Start – End (italic)
Relevant Courses: ...

FINANCIAL EXPERIENCE
Company Name                              Location
Role / Job Title (italic)                 Start – End (italic)
• Achievement bullet point
• Achievement bullet point

LEADERSHIP
• Certification / leadership entry

SKILLS AND INTERESTS
Language: ...
Computer: ...
Interests: ...
```

---

## 💡 Tips for Template 2

- **Education → Degree field**: Write your full degree first, then add "Relevant Courses:" on the same line or next. Example:
  ```
  Bachelor of Arts in Finance; Minor in Economics. Relevant Courses: Corporate Finance, Portfolio Theory, Business Law.
  ```

- **Experience → Key Achievements**: Write one achievement per line — each line becomes a bullet point on the CV. Example:
  ```
  Researched and analysed portfolio holdings worth MAD 4.2B
  Built an Excel model that reduced reporting time by 35%
  Presented ESG investment findings to the strategy committee
  ```

- **Skills**: The three skill fields map exactly to Template 2's Language / Computer / Interests rows.

---

## 🖨 Saving as PDF

1. On the generated CV page, click **Print / Save as PDF**
2. In the browser print dialog:
   - Set **Destination** → **Save as PDF**
   - Set **Paper size** → **A4**
   - Disable **Headers and footers** (optional, for a cleaner look)
3. Click **Save**

---

## 🔧 Troubleshooting

| Problem | Fix |
|---|---|
| White page / PHP shown as text | Open via `http://localhost/...` not `file://...` |
| "Database connection failed" | Make sure MySQL is started in XAMPP Control Panel |
| Schema not found in Workbench | Re-run `database.sql` in Workbench |
| Port conflict on 3306 | Another MySQL instance may be running — stop it first |
| Experience bullets not showing | Each bullet must be on its own line in the textarea |

---

## 🔒 Security Note

This is a local development setup. For production deployment, replace `mysqli_real_escape_string` with **PDO prepared statements**.

---

*ResumeForge · Template 2: Finance / Business · Tufts ATS Format*
