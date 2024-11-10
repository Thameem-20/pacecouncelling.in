<?php
include("./config.php");
$usn = mysqli_real_escape_string($conn, $_GET['usn'] ?? '');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = date('Y-m-d'); // Automatically set to the current date
    $semester = mysqli_real_escape_string($conn, $_POST['semester']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $remark = mysqli_real_escape_string($conn, $_POST['remark']);

    $sql = "INSERT INTO remarks (usn, date, semester, title, description, remark) 
            VALUES ('$usn', '$date', '$semester', '$title', '$description', '$remark')";

    if ($conn->query($sql)) {
        echo "<script>alert('Remark added successfully!');</script>";
    } else {
        echo "<script>alert('Error adding remark: " . $conn->error . "');</script>";
    }
}

// Fetch student details
$student_sql = "SELECT * FROM student_details WHERE usn = '$usn'";
$student_result = $conn->query($student_sql);
$student = $student_result->fetch_assoc();

// Fetch remarks
$remarks_sql = "SELECT * FROM remarks WHERE usn = '$usn' ORDER BY date DESC";
$remarks_result = $conn->query($remarks_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Remarks - <?php echo htmlspecialchars($student['name'] ?? ''); ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-4">
<!-- Student Info Section -->
<div class="card mb-5 shadow border-0">
    <div class="card-header bg-secondary text-white">
        <h3 class="mb-0">Student Details</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p class="mb-2"><strong>Name:</strong> <?php echo htmlspecialchars($student['name'] ?? ''); ?></p>
            </div>
            <div class="col-md-6">
                <p class="mb-2"><strong>USN:</strong> <?php echo htmlspecialchars($usn); ?></p>
            </div>

        </div>
    </div>
</div>


        <!-- Add Remark Button -->
        <button type="button" class="btn btn-primary mb-4" data-toggle="modal" data-target="#remarkModal">
            Add New Remark
        </button>
<!-- Remarks List Section -->
<div class="card shadow-sm border-0 my-4">
    <div class="card-header bg-primary text-white">
        <h3 class="mb-0">Remarks History</h3>
    </div>
    <div class="card-body">
        <?php
        if ($remarks_result->num_rows > 0) {
            while ($row = $remarks_result->fetch_assoc()) {
                ?>
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-primary"><?php echo htmlspecialchars($row['title']); ?></h5>
                        <p class="mb-3"><strong>Answer:</strong><br> <?php echo htmlspecialchars($row['description']); ?></p>
                        <p><strong>Remark:</strong><br> <?php echo htmlspecialchars($row['remark']); ?></p>
                        <p class="text-muted mb-2"><strong>During Semester:</strong> <?php echo htmlspecialchars($row['semester']); ?></p>

                        <p class="text-muted mb-2"><strong>Date:</strong> <?php echo htmlspecialchars($row['date']); ?></p>

                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p class='text-muted'>No remarks found for this student.</p>";
        }
        ?>
    </div>
</div>

        <!-- Modal Form -->
        <div class="modal fade" id="remarkModal" tabindex="-1" aria-labelledby="remarkModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="remarkModalLabel">Add New Remark</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                            <div class="form-group">
                                <label for="semester">Semester:</label>
                                <select id="semester" name="semester" class="form-control" required>
                                    <?php for($i=1; $i<=8; $i++) { ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title">Question:</label>
                                <input type="text" id="title" name="title" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Answer:</label>
                                <textarea id="description" name="description" rows="3" class="form-control" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="remark">Remark:</label>
                                <textarea id="remark" name="remark" rows="3" class="form-control" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">Submit Remark</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
