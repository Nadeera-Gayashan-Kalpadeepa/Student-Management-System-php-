<?php
require_once "../includes/config.php";

// Initialize error and success messages
$error = $success = "";

// Check if id parameter exists
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header("location: students.php");
    exit();
}

$student_id = $_GET["id"];

// Update student information
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    try {
        $sql = "UPDATE students SET name = :name, email = :email, phone = :phone, date_of_birth = :dob 
                WHERE student_id = :id";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':name' => $_POST['name'],
            ':email' => $_POST['email'],
            ':phone' => $_POST['phone'],
            ':dob' => $_POST['date_of_birth'],
            ':id' => $student_id
        ]);
        
        $success = "Student updated successfully";
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Fetch student data
try {
    $sql = "SELECT * FROM students WHERE student_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $student_id]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$student) {
        header("location: students.php");
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
    <title>Edit Student - Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <?php include "../includes/header.php"; ?>

    <div class="container mt-4">
        <h2>Edit Student</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Edit Student Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $student_id; ?>" class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label required">Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label required">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($student['email']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($student['phone']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="date_of_birth" class="form-label required">Date of Birth</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="<?php echo htmlspecialchars($student['date_of_birth']); ?>" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" name="update" class="btn btn-primary">Update Student</button>
                        <a href="students.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
