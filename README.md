# Student Management System - Laboratory Activity

## Overview
This is my laboratory project for our web development class. I created a student management system using PHP and MySQL that can perform basic CRUD (Create, Read, Update, Delete) operations. The system allows users to manage student records with a clean and modern interface.

## What I Built
- A homepage that shows what the system can do
- Add new student form with validation
- View all students in a nice table format
- Edit existing student information
- Delete students with confirmation popup
- Modern design using Bootstrap

## Technologies Used
- **PHP** - For server-side programming
- **MySQL** - Database to store student records
- **Bootstrap 5** - For styling and responsive design
- **JavaScript** - For form validation and interactive features
- **HTML/CSS** - Basic structure and styling

## Database Structure
The system uses a MySQL database called `student_lab` with a table called `students`:

```sql
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    age INT NOT NULL,
    course VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## Files in the Project
- `index.php` - Main homepage with navigation
- `db_connect.php` - Database connection setup
- `insert.php` - Add new student form and processing
- `select.php` - Display all students with sorting and delete options
- `update.php` - Edit student information
- `delete.php` - Delete student record (with confirmation)

## Features I Implemented
- ✅ Add new students with form validation
- ✅ View all students in a sortable table
- ✅ Edit student information
- ✅ Delete students with confirmation modal
- ✅ Responsive design that works on mobile
- ✅ Input validation (both client and server side)
- ✅ Clean, modern UI design
- ✅ Success/error messages for user feedback

## How to Run This Project

### Prerequisites
- XAMPP or WAMP server
- Web browser

### Installation Steps
1. Download or clone this project
2. Place the folder in your `htdocs` directory (for XAMPP) or `www` (for WAMP)
3. Start Apache and MySQL services in XAMPP/WAMP
4. Open your browser and go to `http://localhost/student_lab_crud/`
5. The database and table will be created automatically when you first run the project

## What I Learned
While working on this project, I learned:
- How to connect PHP to MySQL database
- Using PDO for secure database operations
- Form validation (both frontend and backend)
- Creating responsive web designs with Bootstrap
- Implementing CRUD operations
- Session management for user feedback messages
- Basic security practices like input sanitization

## Challenges I Faced
- Initially had trouble with form validation - learned to do both client and server side validation
- Spent time figuring out how to make the design look modern and clean
- Had to learn about PDO prepared statements for security
- Making the delete confirmation modal work properly took some debugging

## Future Improvements
If I had more time, I would add:
- Search functionality to find specific students
- Pagination for large numbers of students
- Export student list to Excel/PDF
- User authentication system
- Better error handling and logging
- File upload for student photos

## Screenshots
The system has a clean, modern interface with:
- A welcoming homepage with quick action cards
- A comprehensive student list with sorting capabilities
- User-friendly forms for adding and editing students
- Confirmation dialogs for important actions like deletion

## Course Information
- **Subject**: Web Development / PHP Programming
- **Activity**: CRUD Laboratory Exercise
- **Semester**: [Your Semester]
- **Academic Year**: 2025

---

*Note: This project was created as part of my laboratory requirements. All code is original work following the specifications provided in class.*