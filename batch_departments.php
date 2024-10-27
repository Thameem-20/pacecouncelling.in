<?php
include("./config.php");
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$branch = isset($_GET['branch']) ? $_GET['branch'] : '';

// Fetch all unique departments for the selected branch
$dept_query = "SELECT DISTINCT department FROM student_details WHERE branch = ? ORDER BY department";
$stmt = $conn->prepare($dept_query);
$stmt->bind_param("s", $branch);
$stmt->execute();
$dept_result = $stmt->get_result();
$departments = [];
while ($row = $dept_result->fetch_assoc()) {
    $departments[] = $row['department'];
}

// Fetch department statistics for the selected branch
$dept_stats_query = "SELECT department, COUNT(*) as student_count 
                    FROM student_details 
                    WHERE branch = ?
                    GROUP BY department 
                    ORDER BY department";
$stmt = $conn->prepare($dept_stats_query);
$stmt->bind_param("s", $branch);
$stmt->execute();
$dept_stats_result = $stmt->get_result();
$department_stats = [];
while ($row = $dept_stats_result->fetch_assoc()) {
    $department_stats[] = $row;
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
    <style>
        .card {
            transition: transform 0.2s ease-in-out;
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-body {
            padding: 1.5rem;
        }

        .display-4 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #00496e;
        }

        .card-title {
            color: #333;
            font-weight: 600;
        }

        .btn-primary {
            background-color: #00496e;
            border: none;
        }

        .btn-primary:hover {
            background-color: #003857;
        }

        /* Sidebar styles */
        .sidebar {
            background-color: #f8f9fa;
            padding: 20px;
        }

        .side-link {
            color: #333;
            padding: 8px 16px;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s;
        }

        .side-link:hover {
            background-color: #e9ecef;
            border-radius: 5px;
        }

        /* Main content area */
        .main-content {
            padding: 20px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navBar navbar-expand-lg navbar-dark" style="background-color: #00496e !important;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">Orientation</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="./index.php">Home</a>
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
                            </ul>
                        </div>
                    </li>
                    <li>&nbsp;&nbsp;&nbsp;</li>
                </ul>
                <a class="btn btn-danger" href="./logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar for branches -->
            <nav class="my-3 col-md-3 col-lg-2 d-md-block sidebar">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <hr style="margin-top: -5px; margin-left:-12px; color: white; width:250px;">
                        <?php foreach ($departments as $dept): ?>
                            <li class="nav-item">
                                <a class="nav-link side-link" href="department_students.php?branch=<?= urlencode($branch) ?>&dept=<?= urlencode($dept) ?>">
                                    <?= htmlspecialchars($dept) ?>
                                </a>
                            </li>
                            
                        <?php endforeach; ?>
                    </ul>
                </div>
            </nav>

            <!-- Main content area -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <h2 class="mt-4">Welcome to Student Management System</h2>
                
                <!-- Department Statistics Cards -->
                <div class="row mt-4">
                    <?php foreach ($department_stats as $dept): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?= htmlspecialchars($dept['department']) ?></h5>
                                    <div class="display-4 my-3"><?= htmlspecialchars($dept['student_count']) ?></div>
                                    <p class="card-text text-muted">Total Students</p>
                                    <a href="department_students.php?dept=<?= urlencode($dept['department'])?>&branch=<?php echo $branch?>" 
                                       class="btn btn-primary">View Details</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Additional Statistics or Charts can be added here -->
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./files/js/main.js"></script>
</body>
</html>