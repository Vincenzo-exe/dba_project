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
