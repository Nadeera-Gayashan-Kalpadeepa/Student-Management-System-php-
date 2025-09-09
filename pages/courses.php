<?php
require_once "../includes/config.php";

// Initialize error and success messages
$error = $success = "";

// Create a new course
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create"])) {
    try {
        $sql = "INSERT INTO courses (course_name, credits, department) VALUES (:course_name, :credits, :department)";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':course_name' => $_POST['course_name'],
            ':credits' => $_POST['credits'],
            ':department' => $_POST['department']
        ]);
        
        $success = "Course added successfully";
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Delete a course
if (isset($_GET["delete"]) && !empty($_GET["delete"])) {
    try {
        $sql = "DELETE FROM courses WHERE course_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $_GET["delete"]]);
        $success = "Course deleted successfully";
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Fetch all courses
try {
    $sql = "SELECT * FROM courses ORDER BY course_name";
    $courses = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses - Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <?php include "../includes/header.php"; ?>

    <div class="container mt-4">
        <h2>Manage Courses</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Add Course Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title">Add New Course</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="row g-3">
                    <div class="col-md-6">
                        <label for="course_name" class="form-label required">Course Name</label>
                        <input type="text" class="form-control" id="course_name" name="course_name" required>
                    </div>
                    <div class="col-md-3">
                        <label for="credits" class="form-label required">Credits</label>
                        <input type="number" class="form-control" id="credits" name="credits" required min="1" max="6">
                    </div>
                    <div class="col-md-3">
                        <label for="department" class="form-label required">Department</label>
                        <input type="text" class="form-control" id="department" name="department" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" name="create" class="btn btn-primary">Add Course</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Courses List -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Courses List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Course Name</th>
                                <th>Credits</th>
                                <th>Department</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $course): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($course['course_id']); ?></td>
                                <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                                <td><?php echo htmlspecialchars($course['credits']); ?></td>
                                <td><?php echo htmlspecialchars($course['department']); ?></td>
                                <td class="action-buttons">
                                    <a href="edit_course.php?id=<?php echo $course['course_id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $course['course_id']; ?>)" class="btn btn-sm btn-danger">Delete</a>
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
            if (confirm('Are you sure you want to delete this course?')) {
                window.location.href = `?delete=${id}`;
            }
        }
    </script>
</body>
</html>
