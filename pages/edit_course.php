<?php
require_once "../includes/config.php";

// Initialize error and success messages
$error = $success = "";

// Check if id parameter exists
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header("location: courses.php");
    exit();
}

$course_id = $_GET["id"];

// Update course information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    try {
        $sql = "UPDATE courses SET course_name = :course_name, credits = :credits, department = :department 
                WHERE course_id = :id";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':course_name' => $_POST['course_name'],
            ':credits' => $_POST['credits'],
            ':department' => $_POST['department'],
            ':id' => $course_id
        ]);
        
        $success = "Course updated successfully";
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Fetch course data
try {
    $sql = "SELECT * FROM courses WHERE course_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $course_id]);
    $course = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$course) {
        header("location: courses.php");
        exit();
    }
} catch(PDOException $e) {
    $error = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course - Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <?php include "../includes/header.php"; ?>

    <div class="container mt-4">
        <h2>Edit Course</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Edit Course Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $course_id; ?>" class="row g-3">
                    <div class="col-md-6">
                        <label for="course_name" class="form-label required">Course Name</label>
                        <input type="text" class="form-control" id="course_name" name="course_name" value="<?php echo htmlspecialchars($course['course_name']); ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="credits" class="form-label required">Credits</label>
                        <input type="number" class="form-control" id="credits" name="credits" value="<?php echo htmlspecialchars($course['credits']); ?>" required min="1" max="6">
                    </div>
                    <div class="col-md-3">
                        <label for="department" class="form-label required">Department</label>
                        <input type="text" class="form-control" id="department" name="department" value="<?php echo htmlspecialchars($course['department']); ?>" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" name="update" class="btn btn-primary">Update Course</button>
                        <a href="courses.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
