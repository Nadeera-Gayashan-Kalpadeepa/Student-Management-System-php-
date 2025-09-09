<?php
require_once "../includes/config.php";

// Initialize error and success messages
$error = $success = "";

// Create a new enrollment
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create"])) {
    try {
        $sql = "INSERT INTO enrollments (student_id, course_id, enrollment_date) VALUES (:student_id, :course_id, :enrollment_date)";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':student_id' => $_POST['student_id'],
            ':course_id' => $_POST['course_id'],
            ':enrollment_date' => $_POST['enrollment_date']
        ]);
        
        $success = "Enrollment added successfully";
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Delete an enrollment
if (isset($_GET["delete"]) && !empty($_GET["delete"])) {
    try {
        $sql = "DELETE FROM enrollments WHERE enrollment_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $_GET["delete"]]);
        $success = "Enrollment deleted successfully";
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Fetch all students for the dropdown
try {
    $sql = "SELECT student_id, name FROM students ORDER BY name";
    $students = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error: " . $e->getMessage();
}

// Fetch all courses for the dropdown
try {
    $sql = "SELECT course_id, course_name FROM courses ORDER BY course_name";
    $courses = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error: " . $e->getMessage();
}

// Fetch all enrollments with student and course details
try {
    $sql = "SELECT e.*, s.name as student_name, c.course_name 
            FROM enrollments e 
            JOIN students s ON e.student_id = s.student_id 
            JOIN courses c ON e.course_id = c.course_id 
            ORDER BY e.enrollment_date DESC";
    $enrollments = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Enrollments - Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <?php include "../includes/header.php"; ?>

    <div class="container mt-4">
        <h2>Manage Enrollments</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Add Enrollment Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title">Add New Enrollment</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="row g-3">
                    <div class="col-md-4">
                        <label for="student_id" class="form-label required">Student</label>
                        <select class="form-select" id="student_id" name="student_id" required>
                            <option value="">Select Student</option>
                            <?php foreach ($students as $student): ?>
                                <option value="<?php echo $student['student_id']; ?>">
                                    <?php echo htmlspecialchars($student['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="course_id" class="form-label required">Course</label>
                        <select class="form-select" id="course_id" name="course_id" required>
                            <option value="">Select Course</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?php echo $course['course_id']; ?>">
                                    <?php echo htmlspecialchars($course['course_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="enrollment_date" class="form-label required">Enrollment Date</label>
                        <input type="date" class="form-control" id="enrollment_date" name="enrollment_date" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" name="create" class="btn btn-primary">Add Enrollment</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Enrollments List -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Enrollments List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student Name</th>
                                <th>Course Name</th>
                                <th>Enrollment Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($enrollments as $enrollment): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($enrollment['enrollment_id']); ?></td>
                                <td><?php echo htmlspecialchars($enrollment['student_name']); ?></td>
                                <td><?php echo htmlspecialchars($enrollment['course_name']); ?></td>
                                <td><?php echo htmlspecialchars($enrollment['enrollment_date']); ?></td>
                                <td class="action-buttons">
                                    <a href="edit_enrollment.php?id=<?php echo $enrollment['enrollment_id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $enrollment['enrollment_id']; ?>)" class="btn btn-sm btn-danger">Delete</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this enrollment?')) {
                window.location.href = `?delete=${id}`;
            }
        }
    </script>
</body>
</html>
