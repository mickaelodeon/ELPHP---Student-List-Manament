<?php
session_start();
require_once 'db_connect.php';

$message = '';
$messageType = '';
$student = null;

// Get student ID from URL
$id = $_GET['id'] ?? '';

if (empty($id) || !is_numeric($id)) {
    header('Location: select.php');
    exit;
}

// Fetch student data
try {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->execute([$id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$student) {
        header('Location: select.php');
        exit;
    }
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}

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
            $stmt = $pdo->prepare("UPDATE students SET name = ?, email = ?, age = ?, course = ? WHERE id = ?");
            $stmt->execute([$name, $email, $age, $course, $id]);
            
            $message = "Student updated successfully!";
            $messageType = "success";
            
            // Refresh student data
            $student['name'] = $name;
            $student['email'] = $email;
            $student['age'] = $age;
            $student['course'] = $course;
            
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
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --warning-color: #d97706;
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
                    <i class="fas fa-list me-1"></i>Back to List
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
                        <i class="fas fa-user-edit text-warning me-2"></i>Edit Student
                        <small class="text-muted">#<?php echo $student['id']; ?></small>
                    </h2>
                    <?php if ($message): ?>
                        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                            <i class="fas fa-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-circle'; ?> me-2"></i>
                            <?php echo $message; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" id="updateForm" novalidate>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-medium">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-lg" id="name" name="name" 
                                       value="<?php echo htmlspecialchars($student['name']); ?>" required>
                                <div class="invalid-feedback">Please provide a valid name.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-medium">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($student['email']); ?>" required>
                                <div class="invalid-feedback">Please provide a valid email.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="age" class="form-label fw-medium">Age <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-lg" id="age" name="age" min="1" max="120"
                                       value="<?php echo $student['age']; ?>" required>
                                <div class="invalid-feedback">Please provide a valid age (1-120).</div>
                            </div>
                            <div class="col-md-6">
                                <label for="course" class="form-label fw-medium">Course <span class="text-danger">*</span></label>
                                <select class="form-select form-select-lg" id="course" name="course" required>
                                    <option value="">Choose a course</option>
                                    <option value="Computer Science" <?php echo $student['course'] == 'Computer Science' ? 'selected' : ''; ?>>Computer Science</option>
                                    <option value="Information Technology" <?php echo $student['course'] == 'Information Technology' ? 'selected' : ''; ?>>Information Technology</option>
                                    <option value="Engineering" <?php echo $student['course'] == 'Engineering' ? 'selected' : ''; ?>>Engineering</option>
                                    <option value="Business Administration" <?php echo $student['course'] == 'Business Administration' ? 'selected' : ''; ?>>Business Administration</option>
                                    <option value="Nursing" <?php echo $student['course'] == 'Nursing' ? 'selected' : ''; ?>>Nursing</option>
                                    <option value="Education" <?php echo $student['course'] == 'Education' ? 'selected' : ''; ?>>Education</option>
                                </select>
                                <div class="invalid-feedback">Please select a course.</div>
                                </div>
                            </div>
                            
                            <div class="col-12 pt-3">
                                <button type="submit" class="btn btn-warning btn-lg px-4 me-2">
                                    <i class="fas fa-save me-2"></i>Update Student
                                </button>
                                <a href="select.php" class="btn btn-outline-primary btn-lg px-4">
                                    <i class="fas fa-arrow-left me-2"></i>Back to List
                                </a>
                            </div>
                            
                            <div class="col-12 pt-3">
                                <div class="bg-light rounded p-3">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Created: <?php echo date('M j, Y', strtotime($student['created_at'])); ?> • 
                                        Last Updated: <?php echo date('M j, Y', strtotime($student['updated_at'])); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        document.getElementById('updateForm').addEventListener('submit', function(e) {
            const form = this;
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });

        // Real-time email validation
        document.getElementById('email').addEventListener('input', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                this.setCustomValidity('Please enter a valid email address');
            } else {
                this.setCustomValidity('');
            }
        });

        // Highlight changes
        const originalValues = {
            name: '<?php echo addslashes($student['name']); ?>',
            email: '<?php echo addslashes($student['email']); ?>',
            age: '<?php echo $student['age']; ?>',
            course: '<?php echo addslashes($student['course']); ?>'
        };

        function highlightChanges() {
            const inputs = ['name', 'email', 'age', 'course'];
            inputs.forEach(input => {
                const element = document.getElementById(input);
                const currentValue = element.value;
                
                if (currentValue !== originalValues[input]) {
                    element.classList.add('border-warning');
                    element.style.backgroundColor = '#fff3cd';
                } else {
                    element.classList.remove('border-warning');
                    element.style.backgroundColor = '';
                }
            });
        }

        // Add event listeners for real-time change highlighting
        ['name', 'email', 'age', 'course'].forEach(input => {
            document.getElementById(input).addEventListener('input', highlightChanges);
            document.getElementById(input).addEventListener('change', highlightChanges);
        });
    </script>
</body>
</html>