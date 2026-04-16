-- ═══════════════════════════════════════════════════════════════
-- database.sql  —  ResumeForge Schema
-- Run this in MySQL Workbench:
--   Open Workbench → connect "local host" → File → Open SQL Script
--   Select this file → click the ⚡ Execute button (or Ctrl+Shift+Enter)
-- ═══════════════════════════════════════════════════════════════

-- 1. Create the schema (database)
CREATE DATABASE IF NOT EXISTS resumeforge
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

-- 2. Select it
USE resumeforge;

-- ── 3. resumes — one row per CV ──────────────────────────────
CREATE TABLE IF NOT EXISTS resumes (
    id         INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    prenom     VARCHAR(100)     NOT NULL COMMENT 'First name',
    nom        VARCHAR(100)     NOT NULL COMMENT 'Last name',
    subtext    VARCHAR(220)     NOT NULL COMMENT 'Job title shown under name',
    phone      VARCHAR(40)      NOT NULL,
    email      VARCHAR(180)     NOT NULL,
    city       VARCHAR(120)     NOT NULL,
    summary    TEXT             NOT NULL COMMENT 'Professional summary / profile',
    created_at DATETIME         DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── 4. skills ────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS skills (
    id         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    resume_id  INT UNSIGNED  NOT NULL,
    skill_name TEXT          NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (resume_id) REFERENCES resumes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── 5. education ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS education (
    id          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    resume_id   INT UNSIGNED  NOT NULL,
    school      VARCHAR(220)  NOT NULL,
    start_date  VARCHAR(10)   DEFAULT '',
    end_date    VARCHAR(10)   DEFAULT '',
    description TEXT                   COMMENT 'Degree / relevant courses',
    PRIMARY KEY (id),
    FOREIGN KEY (resume_id) REFERENCES resumes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── 6. experience ────────────────────────────────────────────
--   role_title  = position / job title (italic line in Template 2)
--   location    = city / country of the company
CREATE TABLE IF NOT EXISTS experience (
    id          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    resume_id   INT UNSIGNED  NOT NULL,
    company     VARCHAR(220)  NOT NULL,
    role_title  VARCHAR(220)  DEFAULT '' COMMENT 'Job title / internship label',
    location    VARCHAR(120)  DEFAULT '' COMMENT 'Company city/country',
    start_date  VARCHAR(10)   DEFAULT '',
    end_date    VARCHAR(10)   DEFAULT '',
    description TEXT                    COMMENT 'Bullet-point achievements (one per line)',
    PRIMARY KEY (id),
    FOREIGN KEY (resume_id) REFERENCES resumes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── 7. certifications ────────────────────────────────────────
CREATE TABLE IF NOT EXISTS certifications (
    id        INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    resume_id INT UNSIGNED  NOT NULL,
    cert_name VARCHAR(350)  NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (resume_id) REFERENCES resumes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ═══════════════════════════════════════════════════════════════
-- SAMPLE DATA — mirrors the Template 2 PDF example
-- Delete after testing if you wish.
-- ═══════════════════════════════════════════════════════════════

INSERT INTO resumes (prenom, nom, subtext, phone, email, city, summary) VALUES (
  'Yassine', 'El Amrani',
  'Finance & Investment Analyst',
  '+212 661 234 567',
  'yassine.elamrani@gmail.com',
  'Casablanca',
  'Finance-focused professional with experience in portfolio management, equity research, and investment analysis. Adept at financial modelling, client reporting, and cross-functional collaboration in fast-paced banking and asset management environments.'
);

SET @rid = LAST_INSERT_ID();

INSERT INTO skills (resume_id, skill_name) VALUES
  (@rid, 'Fluent in Arabic and French'),
  (@rid, 'Proficient in English'),
  (@rid, 'Excel (Advanced), PowerPoint, Word'),
  (@rid, 'Bloomberg Terminal, Reuters Eikon'),
  (@rid, 'Python (pandas, matplotlib)'),
  (@rid, 'Rafting, Football, Historical Fiction');

INSERT INTO education (resume_id, school, start_date, end_date, description) VALUES
  (@rid, 'Université Mohammed VI Polytechnique — Ben Guerir', '2020-09', '2023-06',
   'Bachelor of Science in Finance & Economics; Minor in Data Analytics. Relevant Courses: Corporate Finance, Portfolio Theory, Derivatives, Financial Econometrics, Business Law, Statistics.'),
  (@rid, 'Lycée Lyautey — Casablanca', '2017-09', '2020-06',
   'Baccalauréat Sciences Économiques — Mention Très Bien');

INSERT INTO experience (resume_id, company, role_title, location, start_date, end_date, description) VALUES
  (@rid, 'Attijariwafa Bank', 'Capital Markets Intern', 'Casablanca, Morocco', '2022-06', '2022-08',
   'Assisted senior traders in monitoring equity and fixed-income portfolios worth MAD 4.2B\nPrepared daily market reports summarising macroeconomic indicators and sector performance\nBuilt an Excel model to automate portfolio attribution reporting, reducing preparation time by 35%\nResearched ESG investment opportunities and presented findings to the strategy committee'),
  (@rid, 'CDG Capital', 'Asset Management Intern', 'Rabat, Morocco', '2021-07', '2021-09',
   'Analysed mutual fund performance and prepared client statements for 120+ institutional clients\nUpdated CRM database with client KYC information in compliance with Bank Al-Maghrib regulations\nConducted DCF valuations on three listed companies; one recommendation adopted by the team');

INSERT INTO certifications (resume_id, cert_name) VALUES
  (@rid, 'CFA Institute — CFA Level I Candidate (registered Feb 2024)'),
  (@rid, 'Bloomberg Market Concepts (BMC) — Bloomberg LP (2023)'),
  (@rid, 'Financial Modelling & Valuation Analyst (FMVA) — CFI (2022)');

-- ═══════════════════════════════════════════════════════════════
-- END OF database.sql
-- ═══════════════════════════════════════════════════════════════
