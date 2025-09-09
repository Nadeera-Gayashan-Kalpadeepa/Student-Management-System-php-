<?php
require_once "../includes/config.php";

// Initialize error and success messages
$error = $success = "";

// Create a new student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["create"])) {
    try {
        $sql = "INSERT INTO students (name, email, phone, date_of_birth) VALUES (:name, :email, :phone, :dob)";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            ':name' => $_POST['name'],
            ':email' => $_POST['email'],
            ':phone' => $_POST['phone'],
            ':dob' => $_POST['date_of_birth']
        ]);
        
        $success = "Student added successfully";
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Delete a student
if (isset($_GET["delete"]) && !empty($_GET["delete"])) {
    try {
        $sql = "DELETE FROM students WHERE student_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $_GET["delete"]]);
        $success = "Student deleted successfully";
    } catch(PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Fetch all students
try {
    $sql = "SELECT * FROM students ORDER BY name";
    $students = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
    <?php include "../includes/header.php"; ?>

    <div class="container mt-4">
        <h2>Manage Students</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Add Student Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title">Add New Student</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label required">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label required">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="col-md-6">
                        <label for="date_of_birth" class="form-label required">Date of Birth</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" name="create" class="btn btn-primary">Add Student</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Students List -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Students List</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Date of Birth</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                <td><?php echo htmlspecialchars($student['name']); ?></td>
                                <td><?php echo htmlspecialchars($student['email']); ?></td>
                                <td><?php echo htmlspecialchars($student['phone']); ?></td>
                                <td><?php echo htmlspecialchars($student['date_of_birth']); ?></td>
                                <td class="action-buttons">
                                    <a href="edit_student.php?id=<?php echo $student['student_id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="javascript:void(0);" onclick="confirmDelete(<?php echo $student['student_id']; ?>)" class="btn btn-sm btn-danger">Delete</a>
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
            if (confirm('Are you sure you want to delete this student?')) {
                window.location.href = `?delete=${id}`;
            }
        }
    </script>
</body>
</html>
