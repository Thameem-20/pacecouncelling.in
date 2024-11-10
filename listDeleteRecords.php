<?php
include("./config.php");

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM student_details WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('Student record deleted successfully!');</script>";
        echo "<script>window.location.href = 'listDeleteRecords.php';</script>"; // Redirect to the same page after deletion
    } else {
        echo "<script>alert('Failed to delete record.');</script>";
    }
    $stmt->close();
}

// Fetch filter and search values
$search = isset($_GET['search']) ? $_GET['search'] : '';
$department = isset($_GET['department']) ? $_GET['department'] : '';

// Query to fetch departments for the dropdown
$dept_sql = "SELECT DISTINCT department FROM student_details";
$dept_result = $conn->query($dept_sql);

// Query to fetch student records with optional filtering and searching
$sql = "SELECT * FROM student_details WHERE (name LIKE ? OR ? = '') AND (department = ? OR ? = '')";
$stmt = $conn->prepare($sql);
$search_term = '%' . $search . '%';
$stmt->bind_param("ssss", $search_term, $search, $department, $department);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 20px;
        }
        .table-container {
            max-width: 90%;
            margin: 0 auto;
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">Student List</h2>

    <!-- Filter and Search Form -->
    <form class="row g-3 mb-3" method="GET" action="">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search by name" value="<?php echo htmlspecialchars($search); ?>">
        </div>
        <div class="col-md-4">
            <select name="department" class="form-select">
                <option value="">Filter by Department</option>
                <?php while ($dept_row = $dept_result->fetch_assoc()): ?>
                    <option value="<?php echo $dept_row['department']; ?>" <?php echo ($department == $dept_row['department']) ? 'selected' : ''; ?>>
                        <?php echo $dept_row['department']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <div class="table-container">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>USN</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Department</th>
                    <th>Semester</th>
                    <th>Section</th>
                    <th>Branch</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['usn']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['department']); ?></td>
                            <td><?php echo htmlspecialchars($row['semester']); ?></td>
                            <td><?php echo htmlspecialchars($row['section']); ?></td>
                            <td><?php echo htmlspecialchars($row['branch']); ?></td>
                            <td>
                                <a href="?delete_id=<?php echo $row['id']; ?>" 
                                   onclick="return confirm('Are you sure you want to delete this student?');" 
                                   class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center">No students found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
