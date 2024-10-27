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
    $section = $_POST['section']; // New field for section
    $branch = $_POST['branch']; // New field for branch

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
    $conn->close();
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Student</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
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
          <input type="text" class="form-control" id="usn" name="usn" required>
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
          <input type="text" class="form-control" id="department" name="department" required>
        </div>
        <div class="mb-3 col-md-6">
          <label for="semester" class="form-label">Semester</label>
          <input type="number" class="form-control" id="semester" name="semester" required>
        </div>
      </div>
      <div class="row">
        <div class="mb-3 col-md-6">
          <label for="section" class="form-label">Section</label>
          <input type="text" class="form-control" id="section" name="section" required>
        </div>
        <div class="mb-3 col-md-6">
          <label for="branch" class="form-label">batch</label>
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
</body>
</html>
