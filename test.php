<?php
include("./config.php");

$studentId = isset($_GET['id']) ? (int)$_GET['id'] : 0; // Get student ID from URL (cast to integer)

$sql = "SELECT s.linkedin, s.github, s.resume, s.photo ";
$sql .= "FROM socials AS s ";
$sql .= "WHERE s.student_id = $studentId"; // Filter by student ID

$result = $conn->query($sql);
$socialDetails = [];

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc(); // Assuming only one student per ID
  $linkedin = $row['linkedin'];
  $github = $row['github'];
  $resume= $row['resume'];
  $photo = $row['photo'];


} else {
  // Handle case where no social details found
  $socialDetails = null;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Social Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

  <div class="container mt-5 text-center">
    <img src="<?php echo $photo; ?>" alt="Student Photo" class="rounded-circle mb-3" style="width: 150px; height: 150px;">
    <a href="<?php echo $resume; ?>" download class="btn btn-primary mb-3">Download Resume</a>
    <div class="d-flex justify-content-center">
        <a href="<?php echo $linkedin; ?>" target="_blank" class="me-3"> 
        <img height="100" src="./images/linkedinLogo.webp" alt="">

        </a>
        <a href="<?php echo $github; ?>" target="_blank">
        <img height="100" src="./images/githubLogo.png" alt="">
        </a>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>