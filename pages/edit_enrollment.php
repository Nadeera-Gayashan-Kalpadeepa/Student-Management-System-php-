<?php
require_once "../includes/config.php";

// Initialize error and success messages
$error = $success = "";

// Check if id parameter exists
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header("location: enrollments.php");
    exit();
}

$enrollment_id = $_GET["id"];

// Update enrollment information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    try {
        $sql = "UPDATE enrollments SET student_id = :student_id, course_id = :course_id, enrollment_date = :enrollment_date 
                WHERE enrollment_id = :id";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':student_id' => $_POST['student_id'],
            ':course_id' => $_POST['course_id'],
            ':enrollment_date' => $_POST['enrollment_date'],
            ':id' => $enrollment_id
        ]);
        
        $success = "Enrollment updated successfully";
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Fetch enrollment data
try {
    $sql = "SELECT * FROM enrollments WHERE enrollment_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $enrollment_id]);
    $enrollment = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$enrollment) {
        header("location: enrollments.php");
        exit();
    }
} catch(PDOException $e) {
    $error = "Error: " . $e->getMessage();
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Enrollment - Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <?php include "../includes/header.php"; ?>

    <div class="container mt-4">
        <h2>Edit Enrollment</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Edit Enrollment Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $enrollment_id; ?>" class="row g-3">
                    <div class="col-md-4">
                        <label for="student_id" class="form-label required">Student</label>
                        <select class="form-select" id="student_id" name="student_id" required>
                            <?php foreach ($students as $student): ?>
                                <option value="<?php echo $student['student_id']; ?>" 
                                    <?php echo ($student['student_id'] == $enrollment['student_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($student['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="course_id" class="form-label required">Course</label>
                        <select class="form-select" id="course_id" name="course_id" required>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?php echo $course['course_id']; ?>"
                                    <?php echo ($course['course_id'] == $enrollment['course_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($course['course_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="enrollment_date" class="form-label required">Enrollment Date</label>
                        <input type="date" class="form-control" id="enrollment_date" name="enrollment_date" 
                               value="<?php echo htmlspecialchars($enrollment['enrollment_date']); ?>" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" name="update" class="btn btn-primary">Update Enrollment</button>
                        <a href="enrollments.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
