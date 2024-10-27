<?php
include("./config.php");

if (isset($_GET['id'])) {
    $student_id = intval($_GET['id']);

    // Check if the student ID exists in the professional_details table
    $stmt = $conn->prepare("SELECT id FROM professional_details WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Student ID not found in professional_details, insert the student_id
        $stmt_insert = $conn->prepare("INSERT INTO professional_details (student_id) VALUES (?)");
        $stmt_insert->bind_param("i", $student_id);

        if ($stmt_insert->execute()) {
            // echo "Student ID inserted into professional_details.";
        } else {
            echo "Error: " . $stmt_insert->error;
        }

        $stmt_insert->close();
    } else {
        // echo "Student ID already exists in professional_details.";
    }

    $stmt->close();
} else {
    echo "No student ID specified.";
    exit();
}

// Fetch existing data
if ($student_id) {
    $stmt = $conn->prepare("
        SELECT 
            sd.name, sd.usn, sd.email, sd.phone, sd.department, sd.semester, sd.tenth_aggr, sd.twelveth_aggr, sd.engg_aggr,
            pd.technical_skills, pd.technology_interested_in, pd.professional_skills, pd.certification, pd.professional_bodies, pd.professional_role, pd.projects, pd.internships, pd.areas_of_interest, pd.counsellor
        FROM student_details sd
        LEFT JOIN professional_details pd ON sd.id = pd.student_id
        WHERE sd.id = ?
    ");
    $stmt->bind_param("i", $student_id);
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $technical_skills = $_POST['technical_skills'];
    $technology_interested_in = $_POST['technology_interested_in'];
    $professional_skills = $_POST['professional_skills'];
    $certification = $_POST['certification'];
    $professional_bodies = $_POST['professional_bodies'];
    $professional_role = $_POST['professional_role'];
    $projects = $_POST['projects'];
    $internships = $_POST['internships'];
    $areas_of_interest = $_POST['areas_of_interest'];
    $counsellor = $_POST['counsellor'];

    $stmt = $conn->prepare("
        UPDATE professional_details 
        SET technical_skills = ?, technology_interested_in = ?, professional_skills = ?, certification = ?, professional_bodies = ?, professional_role = ?, projects = ?, internships = ?, areas_of_interest = ?, counsellor = ?
        WHERE student_id = ?
    ");
    $stmt->bind_param("ssssssssssi", $technical_skills, $technology_interested_in, $professional_skills, $certification, $professional_bodies, $professional_role, $projects, $internships, $areas_of_interest, $counsellor, $student_id);

    if ($stmt->execute()) {
        header("Location: ./profile.php?id=$student_id");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Details Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            margin-top: 50px;
            padding: 30px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-title {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2 class="form-title">Professional Details Form</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="studentId">Student ID</label>
                    <input type="number" class="form-control" id="studentId" name="student_id" value="<?php echo htmlspecialchars($student['id'] ?? $student_id); ?>" readonly >
                </div>
                <div class="form-group">
                    <label for="technicalSkills">Technical Skills</label>
                    <input type="text" class="form-control" id="technicalSkills" name="technical_skills" value="<?php echo htmlspecialchars($student['technical_skills'] ?? ''); ?>" >
                </div>
                <div class="form-group">
                    <label for="technologyInterestedIn">Technology Interested In</label>
                    <input type="text" class="form-control" id="technologyInterestedIn" name="technology_interested_in" value="<?php echo htmlspecialchars($student['technology_interested_in'] ?? ''); ?>" >
                </div>
                <div class="form-group">
                    <label for="professionalSkills">Professional Skills</label>
                    <input type="text" class="form-control" id="professionalSkills" name="professional_skills" value="<?php echo htmlspecialchars($student['professional_skills'] ?? ''); ?>" >
                </div>
                <div class="form-group">
                    <label for="certification">Certification</label>
                    <input type="text" class="form-control" id="certification" name="certification" value="<?php echo htmlspecialchars($student['certification'] ?? ''); ?>" >
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="professionalBodies">Professional Bodies</label>
                        <input type="text" class="form-control" id="professionalBodies" name="professional_bodies" value="<?php echo htmlspecialchars($student['professional_bodies'] ?? ''); ?>" >
                    </div>
                    <div class="form-group col-md-6">
                        <label for="professionalRole">Professional Role</label>
                        <input type="text" class="form-control" id="professionalRole" name="professional_role" value="<?php echo htmlspecialchars($student['professional_role'] ?? ''); ?>" >
                    </div>
                </div>
                <div class="form-group">
                    <label for="projects">Projects</label>
                    <input type="text" class="form-control" id="projects" name="projects" value="<?php echo htmlspecialchars($student['projects'] ?? ''); ?>" >
                </div>
                <div class="form-group">
                    <label for="internships">Internships</label>
                    <input type="text" class="form-control" id="internships" name="internships" value="<?php echo htmlspecialchars($student['internships'] ?? ''); ?>" >
                </div>
                <div class="form-group">
                    <label for="areasOfInterest">Areas of Interest</label>
                    <select class="form-control" id="areasOfInterest" name="areas_of_interest" >
                        <option value="">Select an area of interest</option>
                        <option value="placement" <?php echo isset($student['areas_of_interest']) && $student['areas_of_interest'] == 'placement' ? 'selected' : ''; ?>>Placement</option>
                        <option value="startup" <?php echo isset($student['areas_of_interest']) && $student['areas_of_interest'] == 'startup' ? 'selected' : ''; ?>>Startup</option>
                        <option value="higher_studies" <?php echo isset($student['areas_of_interest']) && $student['areas_of_interest'] == 'higher_studies' ? 'selected' : ''; ?>>Higher Studies</option>
                        <option value="entrepreneur" <?php echo isset($student['areas_of_interest']) && $student['areas_of_interest'] == 'entrepreneur' ? 'selected' : ''; ?>>Entrepreneur</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="counsellor">Counsellor</label>
                    <input type="text" class="form-control" id="counsellor" name="counsellor" value="<?php echo htmlspecialchars($student['counsellor'] ?? ''); ?>" >
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
                <button class='btn btn-outline-secondary' onclick="window.history.back()">Cancel</button>

            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
