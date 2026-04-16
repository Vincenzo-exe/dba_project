# Resume Builder

An ATS-friendly resume builder web application that helps users create professional, clean resumes optimized for Applicant Tracking Systems (ATS).

## Features

- **ATS-Optimized Design** - Clean, simple formatting that passes ATS parsers
- **6-Section Resume Form**:
  1. Personal Information (Name, Job Title, Contact Details)
  2. Professional Summary
  3. Skills
  4. Education
  5. Work Experience
  6. Certifications
- **Dynamic Form Sections** - Add multiple education entries, work experiences, skills, and certifications
- **Progress Tracking** - Visual progress bar as you fill out the form
- **PDF Export** - Print or save your resume as PDF
- **Database Storage** - All resumes securely stored in MySQL

## Tech Stack

- **Frontend**: HTML5, CSS3, JavaScript
- **Backend**: PHP
- **Database**: MySQL

## Project Structure

```
resume-builder/
├── index.html           # Main form
├── generate_cv.php      # Resume display/output
├── submit.php           # Form validation & database insertion
├── config.php           # Database configuration
├── style.css            # All styling
├── script.js            # Form functionality
└── README.md            # This file
```

## Installation

1. **Clone/Extract** the project to `C:\xampp\htdocs\resume-builder\`

2. **Create MySQL Database**:
   ```sql
   CREATE DATABASE resume_builder;
   USE resume_builder;

   CREATE TABLE resumes (
     id INT AUTO_INCREMENT PRIMARY KEY,
     prenom VARCHAR(100),
     nom VARCHAR(100),
     subtext VARCHAR(150),
     phone VARCHAR(20),
     email VARCHAR(100),
     city VARCHAR(100),
     summary TEXT,
     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
   );

   CREATE TABLE skills (
     id INT AUTO_INCREMENT PRIMARY KEY,
     resume_id INT,
     skill_name VARCHAR(200),
     FOREIGN KEY (resume_id) REFERENCES resumes(id) ON DELETE CASCADE
   );

   CREATE TABLE education (
     id INT AUTO_INCREMENT PRIMARY KEY,
     resume_id INT,
     school VARCHAR(200),
     start_date VARCHAR(10),
     end_date VARCHAR(10),
     description VARCHAR(500),
     FOREIGN KEY (resume_id) REFERENCES resumes(id) ON DELETE CASCADE
   );

   CREATE TABLE experience (
     id INT AUTO_INCREMENT PRIMARY KEY,
     resume_id INT,
     company VARCHAR(150),
     role_title VARCHAR(150),
     location VARCHAR(100),
     start_date VARCHAR(10),
     end_date VARCHAR(10),
     description TEXT,
     FOREIGN KEY (resume_id) REFERENCES resumes(id) ON DELETE CASCADE
   );

   CREATE TABLE certifications (
     id INT AUTO_INCREMENT PRIMARY KEY,
     resume_id INT,
     cert_name VARCHAR(300),
     FOREIGN KEY (resume_id) REFERENCES resumes(id) ON DELETE CASCADE
   );
   ```

3. **Configure Database** in `config.php`:
   ```php
   <?php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'resume_builder');

   function getDB() {
     $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
     if (!$conn) die('Database connection failed');
     return $conn;
   }

   function esc($conn, $str) {
     return mysqli_real_escape_string($conn, $str);
   }
   ?>
   ```

4. **Start XAMPP** and navigate to `http://localhost/resume-builder/`

## Usage

1. **Fill Out Form** - Complete all 6 sections
2. **Add Multiple Entries** - Use "+" buttons to add education, experience, skills, and certifications
3. **Submit** - Click "Generate My Resume"
4. **View & Export** - Review your resume and print/save as PDF

## File Descriptions

### `index.html`
Main form with 6 sections matching resume structure. Includes:
- Form validation
- Progress bar
- Dynamic field addition

### `generate_cv.php`
Displays the generated resume in ATS-friendly format:
- Clean Arial font
- Standard spacing
- No graphics or decorative elements
- Print-optimized styling

### `submit.php`
Handles form submission:
- Validates all required fields
- Sanitizes input data
- Inserts data into MySQL
- Redirects to resume view

### `config.php`
Database configuration and helper functions

### `style.css`
All styling for form and generated resume

### `script.js`
JavaScript functionality for:
- Form validation
- Progress tracking
- Dynamic field management

## ATS Compatibility

This resume builder produces ATS-friendly resumes with:
- ✅ Simple, clean formatting
- ✅ Standard fonts (Arial)
- ✅ No images or graphics
- ✅ Proper heading hierarchy
- ✅ Clear section organization
- ✅ No tables or complex layouts
- ✅ Readable by all ATS systems

## Browser Support

- Chrome/Chromium
- Firefox
- Safari
- Edge

## Features for Employers

- Resume data stored securely in MySQL
- Easily retrieve and review submissions
- Export candidate information

## Future Enhancements

- [ ] Multiple resume templates
- [ ] Custom color themes
- [ ] Email delivery
- [ ] Resume analytics
- [ ] Candidate tracking system

## License

© 2025 Resume Builder - All Rights Reserved

## Support

For issues or questions, contact: abdelaziz.ahmamou@emsi-edu.ma
