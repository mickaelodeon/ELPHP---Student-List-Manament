<?php
require_once 'db_connect.php';

$message = '';
$messageType = '';

// Handle form submission
if ($_POST) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $age = $_POST['age'] ?? '';
    $course = trim($_POST['course'] ?? '');
    
    // Server-side validation
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($age) || !is_numeric($age) || $age < 1 || $age > 120) {
        $errors[] = "Valid age is required (1-120)";
    }
    
    if (empty($course)) {
        $errors[] = "Course is required";
    }
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO students (name, email, age, course) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $age, $course]);
            
            $message = "Student added successfully!";
            $messageType = "success";
            
            // Clear form data
            $name = $email = $age = $course = '';
            
        } catch(PDOException $e) {
            if ($e->getCode() == 23000) {
                $message = "Email already exists. Please use a different email.";
            } else {
                $message = "Error: " . $e->getMessage();
            }
            $messageType = "danger";
        }
    } else {
        $message = implode("<br>", $errors);
        $messageType = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --success-color: #059669;
        }
        
        body {
            background-color: #f8fafc;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        .form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-light bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold text-dark" href="index.php">
                <i class="fas fa-users text-primary me-2"></i>Student Management
            </a>
            <div class="d-flex gap-2">
                <a href="select.php" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-list me-1"></i>View Students
                </a>
                <a href="index.php" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-home me-1"></i>Home
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-container p-4">
                    <h2 class="fw-bold mb-4">
                        <i class="fas fa-user-plus text-primary me-2"></i>Add New Student
                    </h2>
                    <?php if ($message): ?>
                        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                            <i class="fas fa-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-circle'; ?> me-2"></i>
                            <?php echo $message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" id="studentForm" novalidate>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-medium">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" id="name" name="name" 
                                       value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
                                <div class="invalid-feedback">Please provide a valid name.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-medium">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                                <div class="invalid-feedback">Please provide a valid email.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="age" class="form-label fw-medium">Age <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-lg" id="age" name="age" min="1" max="120"
                                       value="<?php echo htmlspecialchars($age ?? ''); ?>" required>
                                <div class="invalid-feedback">Please provide a valid age (1-120).</div>
                            </div>
                            <div class="col-md-6">
                                <label for="course" class="form-label fw-medium">Course <span class="text-danger">*</span></label>
                                <select class="form-select form-select-lg" id="course" name="course" required>
                                    <option value="">Choose a course</option>
                                    <option value="Computer Science" <?php echo ($course ?? '') == 'Computer Science' ? 'selected' : ''; ?>>Computer Science</option>
                                    <option value="Information Technology" <?php echo ($course ?? '') == 'Information Technology' ? 'selected' : ''; ?>>Information Technology</option>
                                    <option value="Engineering" <?php echo ($course ?? '') == 'Engineering' ? 'selected' : ''; ?>>Engineering</option>
                                    <option value="Business Administration" <?php echo ($course ?? '') == 'Business Administration' ? 'selected' : ''; ?>>Business Administration</option>
                                    <option value="Nursing" <?php echo ($course ?? '') == 'Nursing' ? 'selected' : ''; ?>>Nursing</option>
                                    <option value="Education" <?php echo ($course ?? '') == 'Education' ? 'selected' : ''; ?>>Education</option>
                                </select>
                                <div class="invalid-feedback">Please select a course.</div>
                            </div>
                            <div class="col-12 pt-3">
                                <button type="submit" class="btn btn-primary btn-lg px-4 me-2">
                                    <i class="fas fa-plus me-2"></i>Add Student
                                </button>
                                <a href="select.php" class="btn btn-outline-primary btn-lg px-4">
                                    <i class="fas fa-list me-2"></i>View Students
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Bootstrap form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        // Real-time validation
        document.getElementById('studentForm').addEventListener('submit', function(e) {
            const form = this;
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });

        // Email validation
        document.getElementById('email').addEventListener('input', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                this.setCustomValidity('Please enter a valid email address');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
</body>
</html>