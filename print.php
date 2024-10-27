<?php
include("./config.php");


// Query to get data for a specific student (replace '4pa20cs070' with the USN you want to fetch)
$usn = $_GET['usn'];
$sql = "
    SELECT 
        sd.name,
        sd.usn,
        sd.email,
        sd.phone,
        sd.department,
        sd.semester,
        sd.tenth_aggr,
        sd.twelveth_aggr,
        sd.engg_aggr,
        sd.section,
        sd.branch,
        pd.technical_skills,
        pd.technology_interested_in,
        pd.professional_skills,
        pd.certification,
        pd.professional_bodies,
        pd.projects,
        pd.internships,
        pd.areas_of_interest,
        pd.counsellor,
        pd.feedback,
        s.linkedin,
        s.github,
        s.resume,
        s.photo
    FROM student_details sd
    LEFT JOIN professional_details pd ON sd.id = pd.student_id
    LEFT JOIN socials s ON sd.id = s.student_id
    WHERE sd.usn = ?";

// Prepare and execute the query
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usn);
$stmt->execute();
$result = $stmt->get_result();

// Fetch the data and assign to variables
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Student details
    $name = $row['name'];
    $usn = $row['usn'];
    $email = $row['email'];
    $phone = $row['phone'];
    $department = $row['department'];
    $semester = $row['semester'];
    $tenth_aggr = $row['tenth_aggr'];
    $twelveth_aggr = $row['twelveth_aggr'];
    $engg_aggr = $row['engg_aggr'];
    $section = $row['section'];
    $branch = $row['department'];

    // Professional details
    $technical_skills = $row['technical_skills'];
    $technology_interested_in = $row['technology_interested_in'];
    $professional_skills = $row['professional_skills'];
    $certification = $row['certification'];
    $professional_bodies = $row['professional_bodies'];
    $projects = $row['projects'];
    $internships = $row['internships'];
    $areas_of_interest = $row['areas_of_interest'];
    $counsellor = $row['counsellor'];
    $feedback = $row['feedback'];

    // Socials
    $linkedin = $row['linkedin'];
    $github = $row['github'];
    $resume = $row['resume'];
    $photo = $row['photo'];
} else {
    echo "No records found for the given USN.";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .header-logo {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .header-logo img {
            height: 80px;
            margin-right: 20px;
        }

        .header-info {
            text-align: center;
        }

        .header-info h3 {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .info-section h5 {
            font-weight: bold;
            margin-top: 20px;
            color: #007bff;
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px;
        }

        .info-section p, .info-section .row > div {
            margin-bottom: 8px;
        }

        .table th, .table td {
            vertical-align: middle;
        }

        .badge {
            background-color: #6c757d;
            color: white;
            padding: 0.3em 0.6em;
            font-size: 0.9rem;
            margin: 0 0.2em;
            border-radius: 12px;
        }

        hr {
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header-logo">
            <img src="./images/pacelogo.png" alt="PACE Logo">
            <div class="header-info">
                <h3>P A COLLEGE OF ENGINEERING</h3>
                <p>Nadupadav, Montepadav Post, Near Mangalore University - 574153 <br>
                    0824-08242284701, Fax: 08242282705 <br>
                    Email: info@pace.edu.in, Website: www.pace.edu.in</p>
            </div>
        </div>

        <!-- Student Information -->
        <div class="info-section">
            <h5>Student Information</h5>
            <div class="row">
                <div class="col-6"><strong>Name:</strong>  <br> <?php echo htmlspecialchars($name); ?></div>
                <div class="col-6"><strong>Semester/Branch/Section: <br> </strong> <?php echo htmlspecialchars($semester); ?> / <?php echo htmlspecialchars($branch); ?> / <?php echo htmlspecialchars($section); ?></div>
                <div class="col-6"><strong>USN:</strong> <?php echo htmlspecialchars($usn); ?></div>
                <div class="col-6"><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></div>
                <div class="col-6"><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></div>
                <div class="col-6"><strong>Department:</strong> <?php echo htmlspecialchars($department); ?></div>
            </div>
        </div>

        <hr>

        <!-- Academic Information -->
        <div class="info-section">
            <h5>Academic Information</h5>
            <p><strong>10th Aggregate:</strong> <?php echo htmlspecialchars($tenth_aggr); ?>%</p>
            <p><strong>12th Aggregate:</strong> <?php echo htmlspecialchars($twelveth_aggr); ?>%</p>
            <p><strong>Engineering Aggregate:</strong> <?php echo htmlspecialchars($engg_aggr); ?>%</p>
        </div>

        <hr>

        <!-- Skills & Interests -->
        <div class="info-section">
            <h5>Skills & Interests</h5>
            <p><strong>Technical Skills:</strong> <?php echo htmlspecialchars($technical_skills); ?></p>
            <p><strong>Technology Interested In:</strong> <?php echo htmlspecialchars($technology_interested_in); ?></p>
            <p><strong>Professional Skills:</strong> <?php echo htmlspecialchars($professional_skills); ?></p>
        </div>

        <hr>

        <!-- Certifications & Professional Memberships -->
        <div class="info-section">
            <h5>Certifications & Professional Memberships</h5>
            <p><strong>Certifications:</strong> <?php echo htmlspecialchars($certification); ?></p>
            <p><strong>Professional Bodies:</strong> <?php echo htmlspecialchars($professional_bodies); ?></p>
        </div>

        <hr>

        <!-- Projects & Internships -->
        <div class="info-section">
            <h5>Projects & Internships</h5>
            <p><strong>Projects:</strong> <?php echo htmlspecialchars($projects); ?></p>
            <p><strong>Internships:</strong> <?php echo htmlspecialchars($internships); ?></p>
        </div>

        <hr>

        <!-- Other Information -->
        <div class="info-section">
            <h5>Other Information</h5>
            <p><strong>Areas of Interest:</strong> <?php echo htmlspecialchars($areas_of_interest); ?></p>
            <p><strong>Counsellor:</strong> <?php echo htmlspecialchars($counsellor); ?></p>
            <p><strong>Feedback:</strong> <?php echo htmlspecialchars($feedback); ?></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
