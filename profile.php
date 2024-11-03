<?php
include("./config.php");
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$loggedName = $_SESSION['Full_Name'];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql_check = "SELECT * FROM socials WHERE student_id = ?";
    if ($stmt_check = $conn->prepare($sql_check)) {
        $stmt_check->bind_param("i", $id);
        $stmt_check->execute();
        $stmt_check->store_result();
        if ($stmt_check->num_rows <= 0) {
            $sql_studentSocial = "INSERT INTO socials (student_id) VALUES (?)";
            if ($stmt = $conn->prepare($sql_studentSocial)) {
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $stmt->close();
            }
        }
        $stmt_check->close();
    }

    $sql_studentDetails = "
        SELECT 
            sd.name, sd.usn, sd.Bio, sd.email, sd.phone, sd.department, sd.semester, sd.tenth_aggr, sd.twelveth_aggr, sd.engg_aggr,
            pd.technical_skills, pd.technology_interested_in, pd.professional_skills, pd.certification, pd.professional_bodies, pd.professional_role, pd.projects, pd.internships, pd.areas_of_interest, pd.counsellor,
            s.linkedin, s.github, s.resume, s.photo
        FROM student_details sd
        LEFT JOIN professional_details pd ON sd.id = pd.student_id
        LEFT JOIN socials s ON sd.id = s.student_id
        WHERE sd.id = ?";

    if ($stmt = $conn->prepare($sql_studentDetails)) {
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
    }

    $technical_skills = isset($student['technical_skills']) ? explode(',', $student['technical_skills']) : [];
} else {
    echo "No valid student ID specified";
    exit();
}
$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background: #f4f6f9;
            color: #333;
            overflow-x: hidden;
        }

        .sideNavBar {
            width: 250px;
            position: fixed;
            height: 100%;
            background: #00496E;
            padding: 20px;
        }

        .sideNavBar h4 {
            color: white;
            text-align: center;
        }

        .sideNavBar ul {
            list-style-type: none;
            padding: 0;
        }

        .sideNavBar li {
            margin: 15px 0;
        }

        .sideNavBar a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
        }

        .sideNavBar a:hover {
            color: #ffffff;
        }

        .logout a {
            color: white;
            text-decoration: none;
        }

        .topBar {
            margin-left: 250px;
            background: #00496E;
            padding: 15px;
            color: white;
        }

        .profile span {
            font-size: 16px;
        }

        .container {
            margin-left: 270px;
            margin-top: 20px;
        }

        .card {
            background: #fff;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 80vw;
        }

        .card-body {
            padding: 20px;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #007bff;
            border-bottom: 2px solid #007bff;
            margin-bottom: 15px;
        }

        .skills span,
        .btn-outline-primary,
        .btn-outline-secondary,
        .btn-outline-info {
            margin: 5px;
        }

        .bioContent img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
        }

        .table {
            font-size: 14px;
        }

        .card-columns {
            column-count: 2;
        }

        .certifications {
            width: 36vw !important;
        }
    </style>
</head>

<body>

    <div class="sideNavBar">
        <h4>
            Dashboard</h4>
        <ul>
            <li><a href="./index.php"><i class="fa fa-home"></i>&nbsp;Home</a></li>

            <li><a href="./updateprofile.php"><i class="fa fa-user"></i>&nbsp;&nbsp;Profile Update</a></li>
            <li><a href="./add_orientation.php?id=<?php echo $id; ?>"><i class="fa fa-arrows"></i>&nbsp;update Orientation</a></li>

        </ul>
        <div class="logout">
            <a class="btn btn-danger w-100" href="./logout.php"><i class="fa fa-power-off"></i> Logout</a>
        </div>
    </div>

    <div class="topBar d-flex justify-content-between align-items-center  ">
        <div>
            <h5 class="m-0"> <i style="color:white;cursor:pointer"  onclick="window.history.back()" class="fa fa-arrow-circle-left "></i> &nbsp;&nbsp;
                Student Dashboard</h5>
        </div>
        <div class="profile">
            <span><?php echo htmlspecialchars($loggedName); ?></span>
            <img src="./images/user-icon-1024x1024-dtzturco.png" alt="" class="rounded-circle" width="30" height="30">
        </div>
    </div>

    <div class="container ">
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body d-flex align-items-center">
                        <img height="100px" src="./uploads/photos/<?php echo htmlspecialchars($student['photo'] ?? '../..//images/default.png'); ?>" alt="Student Photo">
                        <div class="ms-3 ">
                            <h4><?php echo htmlspecialchars($student['name'] ?? 'Default Name'); ?></h4>
                            <p><?php echo htmlspecialchars($student['Bio'] ?? 'Default Bio'); ?></p>
                            <div class="row align-items-center justify-content-centwer " style="width: 800px !important;">
                                <div class="col-md">&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-outline-success" href="./updateprofile.php?id=<?php echo $id; ?>">update Profile</a></div>
                                <div class="col-md"><a class="btn btn-outline-primary" href="./add_orientation.php?id=<?php echo $id; ?>">update Orientation</a></div>

                                <div class="col-md"><a class="btn btn-outline-dark" href="./remarks.php?usn=<?php echo $student['usn']; ?>">Remarks </a></div>

                                <div class="col-md"><a class="btn btn-outline-warning" href="./print.php?usn=<?php echo $student['usn']; ?>">Print </a></div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row card-columns">
            <div class="card mb-4">
                <div class="card-body">
                    <h3 class="section-title mb-4">Personal Details</h3>
                    <table class="table table-borderless">
                        <tbody>
                            <?php foreach (['usn' => 'USN', 'email' => 'Email', 'phone' => 'Phone', 'department' => 'Department', 'semester' => 'Semester', 'tenth_aggr' => '10th Aggregate', 'twelveth_aggr' => '12th Aggregate', 'engg_aggr' => 'Engineering Aggregate'] as $key => $label): ?>
                                <tr>
                                    <td class="fw-bold" style="width: 30%;"><?php echo $label; ?>:</td>
                                    <td><?php echo htmlspecialchars($student[$key] ?? 'N/A'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="card">
                <div class="card-body">
                    <h3 class="section-title">Skills</h3>
                    <?php foreach ($technical_skills as $skill): ?>
                        <span class="badge bg-secondary"><?php echo htmlspecialchars(trim($skill)); ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4">
                    <div class=" border-dark">
                        <div class="card-body">
                            <h3 class="section-title">Projects</h3>
                            <p><?php echo str_replace(',', '<hr>', htmlspecialchars($student['projects'] ?? 'No Projects Available')); ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class=" border-dark">
                        <div class="card-body">
                            <h3 class="section-title">Certifications</h3>
                            <p><?php echo str_replace(',', '<hr>', htmlspecialchars($student['certification'] ?? 'No Certifications Available')); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h3 class="section-title">Internships</h3>
                    <p><?php echo str_replace(',', '<hr>', htmlspecialchars($student['internships'] ?? 'No Internships Available')); ?></p>
                </div>
            </div>

            <div class="card mt-2">
                <div class="card-body">
                    <h3 class="section-title">Social Links</h3>
                    <a href="./updateSocial.php?id=<?php echo $id ?>" style='float:right; font-size:20px; color: #00496E;'><i title="edit" class="fa fa-pencil-square-o"></i></a>
                    <a href="<?php echo htmlspecialchars($student['linkedin'] ?? '#'); ?>" class="btn btn-outline-primary">LinkedIn</a>
                    <a href="<?php echo htmlspecialchars($student['github'] ?? '#'); ?>" class="btn btn-outline-dark">GitHub</a>
                    <a href="./uploads/resumes/<?php echo htmlspecialchars($student['resume'] ?? '#'); ?>" class="btn btn-outline-info">Resume</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>