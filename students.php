<?php
include("./config.php");
$branch = $_GET['branch'];
$sem = $_GET['sem'];
$sec = $_GET['sec'];
$searchTerm = isset($_GET['q']) ? $_GET['q'] : '';
$sql = "SELECT id, name, usn FROM student_details WHERE branch = '$branch' AND semester = '$sem' AND section = '$sec'";
if ($searchTerm) {
  $sql .= " WHERE name LIKE '%$searchTerm%' OR usn LIKE '%$searchTerm%' OR phone LIKE '%$searchTerm%";
}
$result = $conn->query($sql);
$students = '';

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $name = $row['name'];
    $usn = $row['usn'];
    $id = $row['id'];
    $students .= '
      <div class="col-md">
        <div class="solution_cards_box sol_card_top_3">
          <div class="solution_card">
            <div class="hover_color_bubble"></div>
            <div class="so_top_icon">
              <img src="./images/user-icon-1024x1024-dtzturco.png" alt="">
            </div>
            <div class="solu_title">
              <h3>' . $name . '</h3>
            </div>
            <div class="solu_description">
              <p>' . $usn . '</p>
              <a href="./profile.php?id=' . $id . '" type="button" class="read_more_btn">View More</a>
            </div>
          </div>
        </div>
      </div>';
  }
} else {
  $students = "<p>No student found</p>";
}

$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="./files/css/cards.css">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #309df0;">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold" href="#">Orientation</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Add
            </a>
            <div class="dropdown-menu">
              <ul>
                <li><a class="dropdown-item" href="./add_student.php">Add Student Details</a></li>
                <li><a class="dropdown-item" href="#">Mega Menu Link</a></li>

              </ul>
            </div>
          </li>

        </ul>
        <form class="d-flex" role="search" id="searchForm">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" id="searchBar">
          <button class="btn btn-outline-light" type="submit">Search</button>
        </form>
      </div>
    </div>
  </nav>
  <div class="text-center mt-2">
      <p>Showing details of:</p>
      <h5>branch <?php echo $branch; ?>, Sem <?php echo $sem; ?>, Section <?php echo $sec; ?>,</h5>
    </div>
  <div class="container cardsContainer">

    <div class="section_our_solution">
      <div class="row" id="results">
        <?php echo $students; ?>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="./files/js/main.js"></script>
</body>

</html>