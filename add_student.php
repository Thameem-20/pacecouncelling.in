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

$conn->close();
?>

<?php
include("./config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $usn = $_POST['usn'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $department = $_POST['department'];
    $semester = $_POST['semester'];
    $tenth_aggr = $_POST['tenth_aggr'];
    $twelveth_aggr = $_POST['twelveth_aggr'];
    $engg_aggr = $_POST['engg_aggr'];
    $section = $_POST['section'];
    $branch = $_POST['branch'];

    // Check if USN already exists
    $check_usn_stmt = $conn->prepare("SELECT usn FROM student_details WHERE usn = ?");
    $check_usn_stmt->bind_param("s", $usn);
    $check_usn_stmt->execute();
    $check_usn_stmt->store_result();

    if ($check_usn_stmt->num_rows > 0) {
        echo "<script>alert('Error: USN already exists.');</script>";
    } else {
        // Proceed with the insertion
        $stmt = $conn->prepare("INSERT INTO student_details (name, usn, email, phone, department, semester, tenth_aggr, twelveth_aggr, engg_aggr, section, branch) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssiiiiss", $name, $usn, $email, $phone, $department, $semester, $tenth_aggr, $twelveth_aggr, $engg_aggr, $section, $branch);

        if ($stmt->execute()) {
            echo "New student added successfully";
            header("Location: ./index.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $check_usn_stmt->close();
}

// Fetch all unique departments for the dropdown
$department_query = "SELECT department_name FROM departments ORDER BY department_name";
$department_result = $conn->query($department_query);
$conn->close();
?>




<?php
include("./config.php");


// Fetch all unique branches
$branch_query = "SELECT DISTINCT branch FROM student_details ORDER BY branch";
$branch_result = $conn->query($branch_query);
$branches = [];
while ($row = $branch_result->fetch_assoc()) {
    $branches[] = $row['branch'];
}

$conn->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
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
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="./index.php">Home</a>
          </li>
          
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Add
            </a>
            <div class="dropdown-menu">
              <ul>
                <li><a class="dropdown-item" href="./add_student.php">Add Student Details</a></li>

              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./Add-Departments.php">Add Departments</a>
          </li>
          <li>
            &nbsp;

            &nbsp;
            &nbsp;
          </li>
          

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

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
            <div class="container mt-5">
    <h2>Add Student</h2>
    <form action="" method="post">
      <div class="row">
        <div class="mb-3 col-md-6">
          <label for="name" class="form-label">Name</label>
          <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3 col-md-6">
  <label for="usn" class="form-label">USN</label>
  <input type="text" class="form-control" id="usn" name="usn" required 
         pattern="^[1-9][A-Z]{2}\d{2}[A-Z]{2}\d{1,3}$" 
         title="Enter a valid USN in the format like 4PA20CS100 or 4PA20CS01">
</div>

      </div>
      <div class="row">
        <div class="mb-3 col-md-6">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3 col-md-6">
          <label for="phone" class="form-label">Phone</label>
          <input type="text" class="form-control" id="phone" name="phone" required>
        </div>
      </div>
      <div class="row">
        <div class="mb-3 col-md-6">
          <label for="department" class="form-label">Department</label>
          <select class="form-control" id="department" name="department" required>
            <option value="">Select Department</option>
            <?php while ($row = $department_result->fetch_assoc()): ?>
              <option value="<?= htmlspecialchars($row['department_name']) ?>"><?= htmlspecialchars($row['department_name']) ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="mb-3 col-md-6">
          <label for="semester" class="form-label">Semester</label>
          <input type="number" class="form-control" id="semester" name="semester" required>
        </div>
      </div>
      <div class="row">
      <div class="mb-3 col-md-6">
  <label for="section" class="form-label">Section</label>
  <input type="text" class="form-control" id="section" name="section" required 
         pattern="^[A-Z]$" 
         title="Enter a valid section (A, B, C, D, etc.)">
</div>

        <div class="mb-3 col-md-6">
          <label for="branch" class="form-label">Batch</label>
          <input type="text" class="form-control" id="branch" name="branch" required>
        </div>
      </div>
      <div class="row">
        <div class="mb-3 col-md-6">
          <label for="tenth_aggr" class="form-label">10th Aggregate</label>
          <input type="number" step="0.01" class="form-control" id="tenth_aggr" name="tenth_aggr" required>
        </div>
        <div class="mb-3 col-md-6">
          <label for="twelveth_aggr" class="form-label">12th Aggregate</label>
          <input type="number" step="0.01" class="form-control" id="twelveth_aggr" name="twelveth_aggr" required>
        </div>
      </div>
      <div class="mb-3">
        <label for="engg_aggr" class="form-label">Engineering Aggregate</label>
        <input type="number" step="0.01" class="form-control" id="engg_aggr" name="engg_aggr" required>
      </div>

      <button type="submit" class="btn btn-primary">Add Student</button>
    </form>
  </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./files/js/main.js"></script>
</body>
</html>