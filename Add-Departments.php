<?php
include("./config.php");
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fetch all unique branches
$branch_query = "SELECT DISTINCT branch FROM student_details ORDER BY branch";
$branch_result = $conn->query($branch_query);
$branches = [];
while ($row = $branch_result->fetch_assoc()) {
    $branches[] = $row['branch'];
}

// Add Department Logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $departmentName = $_POST['departmentName'];

    // Check if the department already exists
    $check_department_stmt = $conn->prepare("SELECT department_name FROM departments WHERE department_name = ?");
    $check_department_stmt->bind_param("s", $departmentName);
    $check_department_stmt->execute();
    $check_department_stmt->store_result();

    if ($check_department_stmt->num_rows > 0) {
        echo "<script>alert('Error: Department already exists.');</script>";
    } else {
        // Insert the new department
        $stmt = $conn->prepare("INSERT INTO departments (department_name) VALUES (?)");
        $stmt->bind_param("s", $departmentName);

        if ($stmt->execute()) {
            echo "<script>alert('New department added successfully');</script>";
            header("Location: ./Add-Departments.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $check_department_stmt->close();
}

// Fetch all departments to display
$department_query = "SELECT department_name FROM departments ORDER BY department_name";
$department_result = $conn->query($department_query);

$conn->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Departments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./files/css/index.css">
    <link rel="stylesheet" href="./files/css/style.css">
</head>
<body>
<nav class="navbar navBar navbar-expand-lg navbar-dark" style="background-color: #00496e !important;">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold" href="#">Orientation</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link active" href="./index.php">Home</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">Add</a>
            <div class="dropdown-menu">
              <ul><li><a class="dropdown-item" href="../add_student.php">Add Student Details</a></li></ul>
            </div>
          </li>
          <li class="nav-item"><a class="nav-link active" href="#">Add Departments</a></li>
        </ul>
        <a class="btn btn-danger" href="./logout.php">Logout</a>
      </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar for branches -->
        <nav class="my-5 col-md-3 col-lg-2 d-md-block sidebar">
            <div class="position-sticky">
                <ul class="nav flex-column">
                    <hr style="margin-top: -5px; margin-left:-12px; color: white; width:250px;">
                    <?php foreach ($branches as $branch): ?>
                        <li class="nav-item">
                            <a class="nav-link side-link" href="batch_departments.php?branch=<?= urlencode($branch) ?>">
                                branch: <?= htmlspecialchars($branch) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
            <h2 class="mt-4">Add Department</h2>

            <div class="container mt-3">
              <form action="Add-Departments.php" method="post">
                <div class="form-group">
                  <label for="departmentName">Department Name</label>
                  <input type="text" class="form-control" id="departmentName" name="departmentName" placeholder="Enter department name" required>
                </div>
                <br>
                <button type="submit" class="btn btn-primary">Submit</button>
              </form>
            </div>

            <!-- Display Departments -->
            <div class="mt-5">
                <h3>Departments List</h3>
                <ul class="list-group">
                    <?php while ($dept = $department_result->fetch_assoc()): ?>
                        <li class="list-group-item"><?= htmlspecialchars($dept['department_name']) ?></li>
                    <?php endwhile; ?>
                </ul>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="./files/js/main.js"></script>
</body>
</html>