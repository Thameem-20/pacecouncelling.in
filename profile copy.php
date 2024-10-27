<?php
include("./config.php");
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
  $loggedName = $_SESSION['Full_Name'];
  $id = intval($_GET['id']);

if (isset($_GET['id'])) {
  $id = intval($_GET['id']);

  $stmt = $conn->prepare("
        SELECT 
            sd.name, sd.usn,sd.Bio, sd.email, sd.phone, sd.department, sd.semester, sd.tenth_aggr, sd.twelveth_aggr, sd.engg_aggr,
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
    <title>Student Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="./files/css/sideBar.css">
    <link rel="stylesheet" href="./files/css/topBar.css">
  </head>
  <body>
<div class="sideNavBar">
  <div class="logo">
    <img src="./images/githubLogo.png" alt="">
  </div>

  <ul>
    <li> <a href="./"><i class="fa fa-home"></i>  Home</a></li>  <hr>
    
    <li><a href=""> <i class="fa fa-graduation-cap"></i>Home</a></li> <hr>
  </ul>
  <div class="logout">
    <a class="btn btn-danger" href="./logout.php"> <i class="fa fa-power-off"></i>      Logout</a>
  </div>
</div>



<div class="topBar">
  <div class="searchBar">
    <form class="d-flex" role="search" id="searchForm">
      <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" id="searchBar">
      <button class="btn btn-outline-primary" type="submit">Search</button>
    </form>
  </div>
  <div class="profile">

    <?php echo $loggedName ?>

    <img src="./images/user-icon-1024x1024-dtzturco.png" alt="">
  </div>

</div>
<div class="bodyContents">

<div class="row">
  <div class="bioContent col-md">
    <div class="nameBio">
      <img src="./images/user-icon-1024x1024-dtzturco.png" alt="">
    
      <h4><?php echo htmlspecialchars($student['name'] ?? ''); ?></h4>
      <p><?php echo htmlspecialchars($student['Bio'] ?? ''); ?></p>
    </div>
        <div class="row">
          <div class="col-md-4"><a class="btn btn-outline-primary" href="">update Profile</a></div>
          <div class="col-md-6"><a class="btn btn-outline-primary" href="./add_orientation.php?id=<?php echo $id; ?>">update Orientation</a></div>

        </div>
      </div>
      <div class="Certifications col-md">
        <h3>Certifications</h3>
        <hr>
        <?php 
$certification = htmlspecialchars($student['certification'] ?? '');
echo str_replace(',', '<hr>', $certification);
?>

<hr>
<div class="text-center ">
  <i class="fa fa-chevron-down position-absolute"></i>

</div>


      </div>
</div>
<div class="row">
  <div class="personals col-md">
    <h3>Personal Details</h3>
    <hr>


<table class="table table-bordered rounded">
    <tbody>
        <tr>
            <td class="col-30"><strong>USN:</strong></td>
            <td class="col-70"><?php echo htmlspecialchars($student['usn'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="col-30"><strong>Email:</strong></td>
            <td class="col-70"><?php echo htmlspecialchars($student['email'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="col-30"><strong>Phone:</strong></td>
            <td class="col-70"><?php echo htmlspecialchars($student['phone'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="col-30"><strong>Department:</strong></td>
            <td class="col-70"><?php echo htmlspecialchars($student['department'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="col-30"><strong>Semester:</strong></td>
            <td class="col-70"><?php echo htmlspecialchars($student['semester'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="col-30"><strong>10th Aggregate:</strong></td>
            <td class="col-70"><?php echo htmlspecialchars($student['tenth_aggr'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="col-30"><strong>12th Aggregate:</strong></td>
            <td class="col-70"><?php echo htmlspecialchars($student['twelveth_aggr'] ?? ''); ?></td>
        </tr>
        <tr>
            <td class="col-30"><strong>Engineering Aggregate:</strong></td>
            <td class="col-70"><?php echo htmlspecialchars($student['engg_aggr'] ?? ''); ?></td>
        </tr>
    </tbody>
</table>

  </div>
  <div class="skills col-md">
    <h3>Skills</h3>
    <hr>
                  <?php foreach ($technical_skills as $skill) : ?>
                <span class="badge bg-secondary m-1 fs-6"><?php echo htmlspecialchars(trim($skill)); ?></span>
              <?php endforeach; ?>
  </div>
</div>
<div class="row">
  <div class="professionals col-md-9">
    <h3>professional Details</h3>
    <hr>
<div class="row">
                  <p class="card-text col-md"><strong>Technology Interested In:</strong> <?php echo htmlspecialchars($student['technology_interested_in'] ?? ''); ?></p>
            <p class="card-text col-md"><strong>Professional Skills:</strong> <?php echo htmlspecialchars($student['professional_skills'] ?? ''); ?></p>
          
</div> 
<div class="row">
  <p class="card-text col-md"><strong>Counsellor:</strong> <?php echo htmlspecialchars($student['counsellor'] ?? ''); ?></p>

            <p class="card-text col-md"><strong>Professional Bodies:</strong> <?php echo htmlspecialchars($student['professional_bodies'] ?? ''); ?></p>
           
</div> 
<div class="row">
  <p class="card-text col-md"><strong>Professional Role:</strong> <?php echo htmlspecialchars($student['professional_role'] ?? ''); ?></p>
            <p class="card-text col-md"><strong>Projects:</strong> <?php echo htmlspecialchars($student['projects'] ?? ''); ?></p>
           
</div>
<div class="row">
  <p class="card-tex col-md"><strong>Internships:</strong> <?php echo htmlspecialchars($student['internships'] ?? ''); ?></p>
            <p class="card-text col-md"><strong>Areas of Interest:</strong> <?php echo htmlspecialchars($student['areas_of_interest'] ?? ''); ?></p>
            
</div>
  </div>
  <div class="col-md socialinfo">
  <h4>Socials</h4><a href="./updateSocial.php?id=<?php echo $id ?>" style='float:right; font-size:20px; color:green;'><i class="fa fa-pencil-square-o"></i></a>
  <hr>
<div class="linkedin"><i class="fa fa-linkedin-square "></i> LinkedIn: <a class="btn btn-outline-success " href="">Open</a></div> <br>
<div class="github"><i class="fa fa-github"></i> Github : &nbsp; <a class="btn btn-outline-success" href="">Open</a> </div> <br>
<div class="resume"><i class="fa fa-files-o"></i> Resume: <a class="btn btn-outline-success" href="">Open</a> </div>
</div>
</div>

</div><!--  bodyContents end -->

<footer class="footer mt-auto py-3 bg-light">
    <div class=" text-center">
      <img src="./images/codewizycredits.png" alt="couldn't Load Image">
    </div>
</footer>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>