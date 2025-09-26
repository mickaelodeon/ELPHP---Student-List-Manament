<?php
session_start();
require_once 'db_connect.php';

// Handle sorting
$orderBy = $_GET['sort'] ?? 'id';
$orderDir = $_GET['dir'] ?? 'ASC';

// Validate sort parameters
$allowedColumns = ['id', 'name', 'email', 'age', 'course', 'created_at'];
$allowedDirections = ['ASC', 'DESC'];

if (!in_array($orderBy, $allowedColumns)) {
    $orderBy = 'id';
}

if (!in_array($orderDir, $allowedDirections)) {
    $orderDir = 'ASC';
}

try {
    $stmt = $pdo->prepare("SELECT * FROM students ORDER BY $orderBy $orderDir");
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Function to get next sort direction
function getNextSortDir($current) {
    return $current === 'ASC' ? 'DESC' : 'ASC';
}

// Function to get sort icon
function getSortIcon($column, $currentSort, $currentDir) {
    if ($column === $currentSort) {
        return $currentDir === 'ASC' ? '<i class="fas fa-sort-up"></i>' : '<i class="fas fa-sort-down"></i>';
    }
    return '<i class="fas fa-sort text-muted"></i>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --success-color: #059669;
            --warning-color: #d97706;
            --danger-color: #dc2626;
        }
        
        body {
            background-color: #f8fafc;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        .sortable {
            cursor: pointer;
            user-select: none;
            transition: background-color 0.2s;
        }
        
        .sortable:hover {
            background-color: rgba(37, 99, 235, 0.1);
        }
        
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .btn-group .btn {
            border-radius: 6px !important;
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
                <a href="insert.php" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus me-1"></i>Add Student
                </a>
                <a href="index.php" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-home me-1"></i>Home
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold text-dark mb-0">Students</h2>
                    <span class="text-muted"><?php echo count($students); ?> student(s)</span>
                </div>
                
                <div class="table-container">
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i><?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                        
                    <?php if (empty($students)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No students found</h5>
                            <p class="text-muted">Add your first student to get started</p>
                            <a href="insert.php" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>Add Student
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="sortable border-0 py-3" onclick="sortTable('name')">
                                            Name <?php echo getSortIcon('name', $orderBy, $orderDir); ?>
                                        </th>
                                        <th class="sortable border-0 py-3" onclick="sortTable('email')">
                                            Email <?php echo getSortIcon('email', $orderBy, $orderDir); ?>
                                        </th>
                                        <th class="sortable border-0 py-3" onclick="sortTable('age')">
                                            Age <?php echo getSortIcon('age', $orderBy, $orderDir); ?>
                                        </th>
                                        <th class="sortable border-0 py-3" onclick="sortTable('course')">
                                            Course <?php echo getSortIcon('course', $orderBy, $orderDir); ?>
                                        </th>
                                        <th class="border-0 py-3" width="120">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $student): ?>
                                        <tr class="border-bottom">
                                            <td class="py-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                        <i class="fas fa-user text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold"><?php echo htmlspecialchars($student['name']); ?></div>
                                                        <small class="text-muted">ID: <?php echo $student['id']; ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3">
                                                <a href="mailto:<?php echo htmlspecialchars($student['email']); ?>" 
                                                   class="text-decoration-none text-primary">
                                                    <?php echo htmlspecialchars($student['email']); ?>
                                                </a>
                                            </td>
                                            <td class="py-3">
                                                <span class="badge bg-light text-dark"><?php echo $student['age']; ?> years</span>
                                            </td>
                                            <td class="py-3">
                                                <span class="fw-medium"><?php echo htmlspecialchars($student['course']); ?></span>
                                            </td>
                                            <td class="py-3">
                                                <div class="d-flex gap-1">
                                                    <a href="update.php?id=<?php echo $student['id']; ?>" 
                                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            onclick="confirmDelete(<?php echo $student['id']; ?>, '<?php echo htmlspecialchars($student['name'], ENT_QUOTES); ?>')"
                                                            title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-2">
                    <h5 class="modal-title fw-bold" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>Confirm Delete
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0">
                    <p class="mb-3">Are you sure you want to delete this student?</p>
                    <div class="bg-light rounded p-3">
                        <strong id="studentName" class="text-dark"></strong>
                        <div><small class="text-muted">This action cannot be undone.</small></div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" id="confirmDeleteBtn" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Delete
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function sortTable(column) {
            const currentSort = '<?php echo $orderBy; ?>';
            const currentDir = '<?php echo $orderDir; ?>';
            
            let newDir = 'ASC';
            if (column === currentSort && currentDir === 'ASC') {
                newDir = 'DESC';
            }
            
            window.location.href = `?sort=${column}&dir=${newDir}`;
        }

        function confirmDelete(id, name) {
            document.getElementById('studentName').textContent = name;
            document.getElementById('confirmDeleteBtn').href = `delete.php?id=${id}`;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        // Add loading animation for sort clicks
        document.querySelectorAll('.sortable').forEach(header => {
            header.addEventListener('click', function() {
                this.innerHTML += ' <i class="fas fa-spinner fa-spin"></i>';
            });
        });
    </script>
</body>
</html>