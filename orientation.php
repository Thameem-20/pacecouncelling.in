<?php
include("./config.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $technical_skills = $_POST['technical_skills'];
    $technology_interested_in = $_POST['technology_interested_in'];
    $professional_skills = $_POST['professional_skills'];
    $certification = $_POST['certification'];
    $professional_bodies = $_POST['professional_bodies'];
    $professional_role = $_POST['professional_role'];
    $projects = $_POST['projects'];
    $internships = $_POST['internships'];
    $areas_of_interest = $_POST['areas_of_interest'];

    $stmt = $conn->prepare("INSERT INTO professional_details (student_id, technical_skills, technology_interested_in, professional_skills, certification, professional_bodies, professional_role, projects, internships, areas_of_interest, counsellor) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssssssss", $student_id, $technical_skills, $technology_interested_in, $professional_skills, $certification, $professional_bodies, $professional_role, $projects, $internships, $areas_of_interest, $counsellor);

    if ($stmt->execute()) {
        echo "Professional details submitted successfully.";
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
        .radio-label {
            margin-right: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <h2 class="form-title">Professional Details Form</h2>
            <?php
              $student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : '';
            ?>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="studentId">Student ID</label>
                    <input type="number" class="form-control" id="studentId" name="student_id" value="<?php echo $student_id; ?>" readonly required>
                </div>
                <div class="form-group">
                    <label for="technicalSkills">Technical Skills</label>
                    <div id="technicalSkills">
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="technical_skills" id="skill<?php echo $i; ?>" value="<?php echo $i; ?>" required>
                                <label class="form-check-label radio-label" for="skill<?php echo $i; ?>"><?php echo $i; ?></label>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="technologyInterestedIn">Technology Interested In</label>
                    <input type="text" class="form-control" id="technologyInterestedIn" name="technology_interested_in" required>
                </div>
                <div class="form-group">
                    <label for="professionalSkills">Professional Skills</label>
                    <input type="text" class="form-control" id="professionalSkills" name="professional_skills" required>
                </div>
                <div class="form-group">
                    <label for="certification">Certification</label>
                    <input type="text" class="form-control" id="certification" name="certification" required>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="professionalBodies">Professional Bodies</label>
                        <input type="text" class="form-control" id="professionalBodies" name="professional_bodies" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="professionalRole">Professional Role</label>
                        <input type="text" class="form-control" id="professionalRole" name="professional_role" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="projects">Projects</label>
                    <input type="text" class="form-control" id="projects" name="projects" required>
                </div>
                <div class="form-group">
                    <label for="internships">Internships</label>
                    <input type="text" class="form-control" id="internships" name="internships" required>
                </div>
                <div class="form-group">
                    <label for="areasOfInterest">Areas of Interest</label>
                    <select class="form-control" id="areasOfInterest" name="areas_of_interest" required>
                        <option value="">Select an area of interest</option>
                        <option value="placement">Placement</option>
                        <option value="startup">Startup</option>
                        <option value="higher_studies">Higher Studies</option>
                        <option value="entrepreneur">Entrepreneur</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="counsellor">Counsellor</label>
                    <input type="text" class="form-control" id="counsellor" name="counsellor" required>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
