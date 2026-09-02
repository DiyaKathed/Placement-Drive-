# Placement Drive Management System (PHP + MySQL + XAMPP)

## Folder structure
```
placement-drive-system/
├── index.html                 (role selection: officer / student)
├── css/            style.css, officer.css, student.css
├── js/             script.js (shared validation + helpers, no popups)
├── officer/        officer-dashboard.html, create-drive.html, manage-drives.html
├── student/        student-dashboard.html, drive-details.html, my-applications.html
├── php/            db.php + 8 CRUD endpoints (drives + applications)
├── database/       placement_drive.sql
└── uploads/         resume PDFs get saved here automatically
```

## Setup (XAMPP)

1. Copy this whole `placement-drive-system` folder into:
   - Windows: `C:\xampp\htdocs\`
   - Mac: `/Applications/XAMPP/xamppfiles/htdocs/`

2. Start **Apache** and **MySQL** from the XAMPP control panel.

3. Open `http://localhost/phpmyadmin`, click **Import**, choose
   `database/placement_drive.sql`, and click **Go**.
   (This creates the `placement_cell` database with `table_drives` and
   `application` tables, plus 3 sample drives.)

4. Make sure `php/db.php` matches your MySQL credentials — default XAMPP
   is `root` with an empty password, which is already set.

5. Open the app in your browser (NOT by double-clicking the HTML file):
   ```
   http://localhost/placement-drive-system/
   ```

6. Give the `uploads/` folder write permission if needed (on Mac/Linux):
   ```
   chmod -R 777 uploads
   ```

## What each portal does

**Officer** (`officer/`)
- `officer-dashboard.html` — live stats (total drives, open drives, applications)
- `create-drive.html` — add a new placement drive (inline validation, no alert() popups)
- `manage-drives.html` — view/edit/delete drives, view/update-status/delete applications,
  and a **"Highlight Strong Applicants"** button that turns a row green when a
  student's CGPA is 0.5+ above that drive's minimum CGPA

**Student** (`student/`)
- `student-dashboard.html` — browse currently open drives
- `drive-details.html?id=X` — view one drive's details and apply (uploads a real
  PDF resume, server checks eligibility before saving the application)
- `my-applications.html` — look up your own applications by roll number

## CRUD mapping

| Action                | Endpoint                        | SQL       |
|------------------------|----------------------------------|-----------|
| Create drive           | php/drive_create.php            | INSERT    |
| Read drives            | php/drive_read.php              | SELECT    |
| Update drive           | php/drive_update.php            | UPDATE    |
| Delete drive           | php/drive_delete.php            | DELETE (cascades to its applications) |
| Create application     | php/application_create.php      | INSERT (+ eligibility check + PDF upload) |
| Read applications      | php/application_read.php        | SELECT (joined with drive) |
| Update application     | php/application_update.php      | UPDATE (status) |
| Delete application     | php/application_delete.php      | DELETE    |

All validation errors show inline next to the relevant field (red text +
red border), and valid fields get a green border — there are no `alert()`
popups anywhere in the validation flow.
