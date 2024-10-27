<?php
include("./config.php");

$student_id = $_GET['id'] ?? '';
$error_message = '';
$success_message = '';

// Initialize variables
$name = $usn = $email = $phone = $department = $semester = '';
$bio = $tenth_aggr = $twelveth_aggr = $engg_aggr = $section = $branch = '';
$photo = '';

// Fetch existing student details
if ($student_id != '') {
    $sql = "SELECT * FROM student_details WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $usn = $row['usn'];
        $email = $row['email'];
        $phone = $row['phone'];
        $department = $row['department'];
        $semester = $row['semester'];
        $bio = $row['Bio'];
        $tenth_aggr = $row['tenth_aggr'];
        $twelveth_aggr = $row['twelveth_aggr'];
        $engg_aggr = $row['engg_aggr'];
        $section = $row['section'];
        $branch = $row['branch'];
    }
    $stmt->close();
}

// Fetch existing socials details for photo
$sql = "SELECT * FROM socials WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $photo = $row['photo'] ?? '';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $usn = $_POST['usn'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $department = $_POST['department'];
    $semester = $_POST['semester'];
    $bio = $_POST['bio'];
    $tenth_aggr = $_POST['tenth_aggr'];
    $twelveth_aggr = $_POST['twelveth_aggr'];
    $engg_aggr = $_POST['engg_aggr'];
    $section = $_POST['section'];
    $branch = $_POST['branch'];

    // Update student_details
    $sql = "UPDATE student_details SET name = ?, usn = ?, email = ?, phone = ?, department = ?, semester = ?, Bio = ?, tenth_aggr = ?, twelveth_aggr = ?, engg_aggr = ?, section = ?, branch = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssiissssi", $name, $usn, $email, $phone, $department, $semester, $bio, $tenth_aggr, $twelveth_aggr, $engg_aggr, $section, $branch, $student_id);

    // Check if the update is successful
    if ($stmt->execute()) {
        // Handle photo upload
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
            $photo_file = $_FILES['photo'];
            $photo_filename = basename($photo_file['name']);
            $photo_temp_path = $photo_file['tmp_name'];
            $photo_upload_path = './uploads/photos/' . $photo_filename;

            if (move_uploaded_file($photo_temp_path, $photo_upload_path)) {
                // Update photo in socials table
                $sql = "UPDATE socials SET photo = ? WHERE student_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $photo_filename, $student_id);
                $stmt->execute();
            } else {
                $error_message = "Error uploading photo.";
            }
        }

        $success_message = "Details updated successfully.";
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
    <title>Update Student Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin-top: 50px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mb-4">Update Student Details</h2>
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>
            <div class="mb-3">
                <label for="usn" class="form-label">USN</label>
                <input type="text" class="form-control" id="usn" name="usn" value="<?php echo htmlspecialchars($usn); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
            </div>
            <div class="mb-3">
                <label for="department" class="form-label">Department</label>
                <input type="text" class="form-control" id="department" name="department" value="<?php echo htmlspecialchars($department); ?>" required>
            </div>
            <div class="mb-3">
                <label for="semester" class="form-label">Semester</label>
                <input type="number" class="form-control" id="semester" name="semester" value="<?php echo htmlspecialchars($semester); ?>" required>
            </div>
            <div class="mb-3">
                <label for="bio" class="form-label">Bio</label>
                <textarea class="form-control" id="bio" name="bio" rows="3" required><?php echo htmlspecialchars($bio); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="tenth_aggr" class="form-label">10th Aggregation (%)</label>
                <input type="number" step="0.01" class="form-control" id="tenth_aggr" name="tenth_aggr" value="<?php echo htmlspecialchars($tenth_aggr); ?>" required>
            </div>
            <div class="mb-3">
                <label for="twelveth_aggr" class="form-label">12th Aggregation (%)</label>
                <input type="number" step="0.01" class="form-control" id="twelveth_aggr" name="twelveth_aggr" value="<?php echo htmlspecialchars($twelveth_aggr); ?>" required>
            </div>
            <div class="mb-3">
                <label for="engg_aggr" class="form-label">Engineering Aggregation (%)</label>
                <input type="number" step="0.01" class="form-control" id="engg_aggr" name="engg_aggr" value="<?php echo htmlspecialchars($engg_aggr); ?>" required>
            </div>
            <div class="mb-3">
                <label for="section" class="form-label">Section</label>
                <input type="text" class="form-control" id="section" name="section" value="<?php echo htmlspecialchars($section); ?>" required>
            </div>
            <div class="mb-3">
                <label for="branch" class="form-label">Branch</label>
                <input type="text" class="form-control" id="branch" name="branch" value="<?php echo htmlspecialchars($branch); ?>" required>
            </div>
            <div class="mb-3">
                <label for="photo" class="form-label">Profile Picture</label>
                <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                <?php if ($photo): ?>
                    <img src="./uploads/photos/<?php echo htmlspecialchars($photo); ?>" alt="Profile Picture" class="mt-2" style="width: 150px; height: auto; border-radius: 8px;">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
