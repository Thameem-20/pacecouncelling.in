<?php
include("./config.php");

$linkedin = $github = $resume = $photo = "";
$student_id = $_GET['id'] ?? '';
$error_message = '';

if ($student_id != '') {
    $sql = "SELECT * FROM socials WHERE student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $linkedin = $row['linkedin'] ?? '';
        $github = $row['github'] ?? '';
        $resume = $row['resume'] ?? '';
        $photo = $row['photo'] ?? '';
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $linkedin = $_POST['linkedin'];
    $github = $_POST['github'];

    $sql = "UPDATE socials SET linkedin = ?, github = ?";
    $types = "ss";
    $params = [$linkedin, $github];

    // Handle resume upload
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == UPLOAD_ERR_OK) {
        $resume_file = $_FILES['resume'];
        $resume_filename = basename($resume_file['name']);
        $resume_temp_path = $resume_file['tmp_name'];
        $resume_upload_path = './uploads/resumes/' . $resume_filename;

        if (move_uploaded_file($resume_temp_path, $resume_upload_path)) {
            $sql .= ", resume = ?";
            $types .= "s";
            $params[] = $resume_filename;
        } else {
            $error_message = "Error uploading resume.";
        }
    }

    // Handle photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $photo_file = $_FILES['photo'];
        $photo_filename = basename($photo_file['name']);
        $photo_temp_path = $photo_file['tmp_name'];
        $photo_upload_path = './uploads/photos/' . $photo_filename;

        if (move_uploaded_file($photo_temp_path, $photo_upload_path)) {
            $sql .= ", photo = ?";
            $types .= "s";
            $params[] = $photo_filename;
        } else {
            $error_message = "Error uploading photo.";
        }
    }

    $sql .= " WHERE student_id = ?";
    $types .= "i";
    $params[] = $student_id;

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    // Check if the update is successful
    if ($stmt->execute()) {
        header("Location: ./profile.php?id={$student_id}");
        exit();
    } else {
        $error_message = "Error updating record: " . $conn->error;
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Socials</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-3">Update Social Media Links</h2>
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="linkedin" class="form-label">LinkedIn Profile</label>
                <input type="text" class="form-control" id="linkedin" name="linkedin" value="<?php echo htmlspecialchars($linkedin); ?>">
            </div>
            <div class="mb-3">
                <label for="github" class="form-label">GitHub Profile</label>
                <input type="text" class="form-control" id="github" name="github" value="<?php echo htmlspecialchars($github); ?>">
            </div>
            <div class="mb-3">
                <label for="resume" class="form-label">Resume</label>
                <input type="file" class="form-control" id="resume" name="resume">
                <?php if ($resume): ?>
                    <small>Current Resume: <a href="./uploads/resumes/<?php echo htmlspecialchars($resume); ?>" target="_blank">View</a></small>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label for="photo" class="form-label">Profile Photo</label>
                <input type="file" class="form-control" id="photo" name="photo">
                <?php if ($photo): ?>
                    <small>Current Photo: <img src="./uploads/photos/<?php echo htmlspecialchars($photo); ?>" alt="Profile Photo" style="width: 100px; height: auto;"></small>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-outline-secondary" onclick="window.history.back()">Cancel</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
