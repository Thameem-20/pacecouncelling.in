<?php
include("./config.php");

if (isset($_GET['id'])) {
  $id = intval($_GET['id']);

  $stmt = $conn->prepare("
        SELECT 
            sd.name, sd.usn, sd.email, sd.phone, sd.department, sd.semester, sd.tenth_aggr, sd.twelveth_aggr, sd.engg_aggr,
            pd.technical_skills, pd.technology_interested_in, pd.professional_skills, pd.certification, pd.professional_bodies, pd.professional_role, pd.projects, pd.internships, pd.areas_of_interest, pd.counsellor
        FROM student_details sd
        LEFT JOIN professional_details pd ON sd.id = pd.student_id
        WHERE sd.id = ?
    ");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
  } else {
    echo "No student found";
    exit();
  }

  $stmt->close();
} else {
  echo "No student ID specified";
  exit();
}
$technical_skills = isset($student['technical_skills']) ? explode(',', $student['technical_skills']) : [];

$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Student Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold" href="#">Orientation</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link " aria-current="page" href="./">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Dropdown
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Action</a></li>
              <li><a class="dropdown-item" href="#">Another action</a></li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link active">Details</a>
          </li>
        </ul>

      </div>
    </div>
  </nav>

  <div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="h3">Student Details</h1>
      <div class="row"> <a class="col-md btn btn-primary" href="./add_orientation.php?id=<?php echo $id; ?>">Update Orientation Details</a> &nbsp;
      <a class=" col-md btn btn-primary" href="./socialDisplay.php?id=<?php echo $id; ?>">View Social Details</a>
</div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="card shadow-sm mb-4">
          <div class="card-body">
            <h5 class="card-title"><?php echo htmlspecialchars($student['name'] ?? ''); ?></h5>
            <p class="card-text"><strong>USN:</strong> <?php echo htmlspecialchars($student['usn'] ?? ''); ?></p>
            <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($student['email'] ?? ''); ?></p>
            <p class="card-text"><strong>Phone:</strong> <?php echo htmlspecialchars($student['phone'] ?? ''); ?></p>
            <p class="card-text"><strong>Department:</strong> <?php echo htmlspecialchars($student['department'] ?? ''); ?></p>
            <p class="card-text"><strong>Semester:</strong> <?php echo htmlspecialchars($student['semester'] ?? ''); ?></p>
            <p class="card-text"><strong>10th Aggregate:</strong> <?php echo htmlspecialchars($student['tenth_aggr'] ?? ''); ?></p>
            <p class="card-text"><strong>12th Aggregate:</strong> <?php echo htmlspecialchars($student['twelveth_aggr'] ?? ''); ?></p>
            <p class="card-text"><strong>Engineering Aggregate:</strong> <?php echo htmlspecialchars($student['engg_aggr'] ?? ''); ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card shadow-sm mb-4">
          <div class="card-body">
            <h5 class="card-title">Professional Details</h5>
            <p class="card-text"><strong>Technical Skills:</strong></p>
            <div>
              <?php foreach ($technical_skills as $skill) : ?>
                <span class="badge bg-secondary m-1"><?php echo htmlspecialchars(trim($skill)); ?></span>
              <?php endforeach; ?>
            </div>
            <p class="card-text"><strong>Technology Interested In:</strong> <?php echo htmlspecialchars($student['technology_interested_in'] ?? ''); ?></p>
            <p class="card-text"><strong>Professional Skills:</strong> <?php echo htmlspecialchars($student['professional_skills'] ?? ''); ?></p>
            <p class="card-text"><strong>Certification:</strong> <?php echo htmlspecialchars($student['certification'] ?? ''); ?></p>
            <p class="card-text"><strong>Professional Bodies:</strong> <?php echo htmlspecialchars($student['professional_bodies'] ?? ''); ?></p>
            <p class="card-text"><strong>Professional Role:</strong> <?php echo htmlspecialchars($student['professional_role'] ?? ''); ?></p>
            <p class="card-text"><strong>Projects:</strong> <?php echo htmlspecialchars($student['projects'] ?? ''); ?></p>
            <p class="card-text"><strong>Internships:</strong> <?php echo htmlspecialchars($student['internships'] ?? ''); ?></p>
            <p class="card-text"><strong>Areas of Interest:</strong> <?php echo htmlspecialchars($student['areas_of_interest'] ?? ''); ?></p>
            <p class="card-text"><strong>Counsellor:</strong> <?php echo htmlspecialchars($student['counsellor'] ?? ''); ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>