# Student Management System

## Overview
A web-based Student Management System built with PHP and MySQL that enables CRUD operations for managing student records, courses, and enrollments.

## Features
- Full CRUD operations for students, courses, and enrollments
- Normalized database design (3NF)
- Responsive UI with Bootstrap 5
- Input validation and security measures
- Search functionality
- Mobile-friendly interface

## Tech Stack
- **Frontend:** HTML, CSS, Bootstrap 5, JavaScript
- **Backend:** PHP (Procedural)
- **Database:** MySQL
- **Server:** XAMPP (Apache + MySQL)

## Database Schema
### Tables
1. **students**
   - student_id (PK)
   - name
   - email
   - phone
   - date_of_birth
   - created_at

2. **courses**
   - course_id (PK)
   - course_name
   - credits
   - department
   - created_at

3. **enrollments**
   - enrollment_id (PK)
   - student_id (FK)
   - course_id (FK)
   - enrollment_date
   - created_at

## Installation
1. Install XAMPP
2. Clone this repository to `htdocs` folder:
   ```bash
   git clone https://github.com/yourusername/student-management.git
   ```
3. Import database:
   - Open phpMyAdmin
   - Create new database named 'student_management'
   - Import database.sql

4. Configure database connection:
   - Open config.php
   - Update database credentials if needed

5. Access the application:
   ```
   http://localhost/student-management
   ```

## Security Features
- PDO prepared statements
- Input sanitization
- CSRF protection
- SQL injection prevention
- Data validation

## Project Structure
```
student-management/
├── css/
│   └── style.css
├── includes/
│   ├── config.php
│   ├── header.php
│   └── footer.php
├── students/
│   ├── create.php
│   ├── edit.php
│   └── delete.php
├── courses/
│   ├── create.php
│   ├── edit.php
│   └── delete.php
├── enrollments/
│   ├── create.php
│   ├── edit.php
│   └── delete.php
├── database.sql
└── index.php
```

## Usage
- Navigate to the dashboard
- Use the navigation menu to access different modules
- Add, view, edit, or delete records using the provided forms
- Search functionality available for each module

## Contributing
1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License
MIT License

## Author
[Nadeera Gayashan]

## Acknowledgments
- Bootstrap team
- XAMPP development team
- PHP community
