<?php
include("./config.php");

$studentId = $_GET['s_id'];

if (isset($_GET['s_id'])) {
    $studentId = $_GET['s_id'];

    // Check if the student ID exists in the socials table
    $stmt = $conn->prepare("SELECT * FROM socials WHERE student_id = ?");
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch existing data if available
        $row = $result->fetch_assoc();
        // Assign fetched data to variables
        $existingPhoto = $row['photo'] ?? null;
        $existingResume = $row['resume'] ?? null;
        $existingLinkedin = $row['linkedin'] ?? null;
        $existingGithub = $row['github'] ?? null;
    } else {
        $stmt_insert = $conn->prepare("INSERT INTO socials (student_id) VALUES (?)");
        $stmt_insert->bind_param("i", $studentId);
        if ($stmt_insert->execute()) {
            // New record inserted successfully
        } else {
            echo "Error: " . $stmt_insert->error;
            exit();
        }
        $stmt_insert->close();
    }

    $stmt->close();
} else {
    echo "No student ID specified.";
    exit();
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $uploadOk = 1;
    $uploadMessage = ""; // Initialize upload message

    // Photo upload (if selected)
    if ($_FILES["photo"]["error"] !== UPLOAD_ERR_NO_FILE) {
        $target_dir = "uploads/photos/"; // Change this for photo uploads
        $target_file = $target_dir . basename($_FILES["photo"]["name"]);
        $photoFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is selected
        if (empty($_FILES["photo"]["tmp_name"])) {
            $uploadOk = 0;
            $uploadMessage .= "Please select a photo to upload.<br>";
        }

        // Check if file already exists (for photo)
        if (file_exists($target_file)) {
            $uploadOk = 0;
            $uploadMessage .= "Sorry, photo file already exists.<br>";
        }

        // Check image file size
        if ($_FILES["photo"]["size"] > 5000000) { // 5MB limit
            $uploadOk = 0;
            $uploadMessage .= "Sorry, photo file is too large.<br>";
        }

        // Allow certain image formats
        $allowedImageTypes = array("jpg", "jpeg", "png");
        if (!in_array($photoFileType, $allowedImageTypes)) {
            $uploadOk = 0;
            $uploadMessage .= "Sorry, only JPG, JPEG and PNG image formats are allowed for photo.<br>";
        }

        // Upload photo if no errors
        if ($uploadOk && move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $photo = $target_file; // Update photo path
        } else {
            $uploadOk = 0;
            $uploadMessage .= "Sorry, there was an error uploading your photo.<br>";
        }
    }

    // Resume upload (if selected)
    if ($_FILES["resume"]["error"] !== UPLOAD_ERR_NO_FILE) {
        $target_dir = "uploads/resumes/"; // Change this for resume uploads
        $target_file = $target_dir . basename($_FILES["resume"]["name"]);
        $resumeFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if resume file is selected
        if (empty($_FILES["resume"]["tmp_name"])) {
            $uploadOk = 0;
            $uploadMessage .= "Please select a resume file to upload.<br>";
        }

        // Check if file already exists (for resume)
        if (file_exists($target_file)) {
            $uploadOk = 0;
            $uploadMessage .= "Sorry, resume file already exists.<br>";
        }

        // Check resume file size
        if ($_FILES["resume"]["size"] > 10000000) { // 10MB limit
            $uploadOk = 0;
            $uploadMessage .= "Sorry, resume file is too large.<br>";
        }

        // Allow certain file formats
        $allowedResumeTypes = array("pdf", "docx", "doc");
        if (!in_array($resumeFileType, $allowedResumeTypes)) {
            $uploadOk = 0;
            $uploadMessage .= "Sorry, only PDF, DOCX and DOC file formats are allowed for resume.<br>";
        }

        // Upload resume if no errors
        if ($uploadOk && move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file)) {
            $resume = $target_file; // Update resume path
        } else {
            $uploadOk = 0;
            $uploadMessage .= "Sorry, there was an error uploading your resume.<br>";
        }
    }

    // Prepare update query based on upload status
    $sql = "UPDATE socials SET ";
    $updateFields = array();

    if (isset($photo)) {
        $updateFields[] = "photo='$photo'";
    }
    if (isset($resume)) {
        $updateFields[] = "resume='$resume'";
    }

    $linkedin = isset($_POST['linkedin']) ? $_POST['linkedin'] : null;
    if ($linkedin) {
        $updateFields[] = "linkedin='$linkedin'";
    }

    $github = isset($_POST['github']) ? $_POST['github'] : null;
    if ($github) {
        $updateFields[] = "github='$github'";
    }

    if (count($updateFields) > 0) {
        $sql .= implode(",", $updateFields);
        $sql .= " WHERE student_id=$studentId";

        // Update the socials table
        if ($conn->query($sql) === TRUE) {
            $uploadMessage = "Student social details updated successfully!";
        } else {
            $uploadMessage = "Error updating student social details: " . $conn->error;
        }
    } else {
        $uploadMessage = "No changes selected for update.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Student Social Details</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<div class="container mt-5">
  <h2>Update Student Social Details</h2>
  <form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="student_id" value="<?php echo $studentId; ?>">

    <div class="mb-3">

                <label for="photoUpload" class="form-label">Photo (JPG, JPEG, PNG)</label>
                <input class="form-control" type="file" id="photoUpload" name="photo" accept=".jpg,.jpeg,.png">
            </div>

            <div class="mb-3">
                <label for="resumeUpload" class="form-label">Resume (PDF, DOCX, DOC)</label>
                <input  class="form-control" type="file" id="resumeUpload" name="resume" accept=".pdf,.docx,.doc">
            </div>

            <div class="mb-3">
                <label for="linkedinLink" class="form-label">LinkedIn URL</label>
                <input value="<?php echo $existingLinkedin;?>" class="form-control" type="url" id="linkedinLink" name="linkedin" placeholder="Enter LinkedIn URL">
            </div>
            <div class="mb-3">
                <label for="githubLink" class="form-label">GitHub URL</label>
                <input value="<?php echo $existingGithub;?>" class="form-control" type="url" id="githubLink" name="github" placeholder="Enter GitHub URL">
            </div>

            <button type="submit" class="btn btn-primary">Update Details</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>