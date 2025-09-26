<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #64748b;
            --accent-color: #0ea5e9;
            --success-color: #059669;
            --warning-color: #d97706;
            --danger-color: #dc2626;
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            color: white;
            padding: 4rem 0;
        }
        
        .action-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .action-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        
        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-dark" href="index.php">
                <i class="fas fa-users text-primary me-2"></i>Student Management
            </a>
            <div class="navbar-nav ms-auto d-flex flex-row gap-3">
                <a class="nav-link text-dark fw-medium" href="select.php">
                    <i class="fas fa-list me-1"></i>Students
                </a>
                <a class="nav-link text-dark fw-medium" href="insert.php">
                    <i class="fas fa-plus me-1"></i>Add New
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-5 fw-bold mb-3">Student Management System</h1>
            <p class="lead mb-4 opacity-90">Manage student records with ease</p>
            <div class="d-flex gap-3 justify-content-center">
                <a href="select.php" class="btn btn-light btn-lg px-4">
                    <i class="fas fa-list me-2"></i>View Students
                </a>
                <a href="insert.php" class="btn btn-outline-light btn-lg px-4">
                    <i class="fas fa-plus me-2"></i>Add Student
                </a>
            </div>
        </div>
    </section>

    <!-- Quick Actions -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card action-card h-100 text-center p-4">
                        <div class="card-icon text-success">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <h5 class="fw-semibold mb-2">Add Student</h5>
                        <p class="text-muted small mb-3">Create new student record</p>
                        <a href="insert.php" class="btn btn-success btn-sm">
                            <i class="fas fa-plus me-1"></i>Add New
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card action-card h-100 text-center p-4">
                        <div class="card-icon text-primary">
                            <i class="fas fa-list"></i>
                        </div>
                        <h5 class="fw-semibold mb-2">View Students</h5>
                        <p class="text-muted small mb-3">Browse all student records</p>
                        <a href="select.php" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye me-1"></i>View All
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card action-card h-100 text-center p-4">
                        <div class="card-icon text-warning">
                            <i class="fas fa-edit"></i>
                        </div>
                        <h5 class="fw-semibold mb-2">Edit Records</h5>
                        <p class="text-muted small mb-3">Update student information</p>
                        <a href="select.php" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>Manage
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card action-card h-100 text-center p-4">
                        <div class="card-icon text-danger">
                            <i class="fas fa-trash-alt"></i>
                        </div>
                        <h5 class="fw-semibold mb-2">Remove Records</h5>
                        <p class="text-muted small mb-3">Delete student records</p>
                        <a href="select.php" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash me-1"></i>Delete
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-top py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0 text-muted small">
                Student Management System &copy; <?php echo date('Y'); ?>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>