<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert student details
$sql = "INSERT INTO student_details (name, usn, email, phone, department, semester, tenth_aggr, twelfth_aggr, engg_aggr) VALUES
('John Doe', 'USN001', 'john.doe@example.com', '1234567890', 'Computer Science', 6, 85.0, 88.5, 82.3),
('Jane Smith', 'USN002', 'jane.smith@example.com', '0987654321', 'Information Technology', 4, 90.5, 87.0, 84.5),
('Robert Brown', 'USN003', 'robert.brown@example.com', '1122334455', 'Electronics', 8, 78.0, 80.5, 76.5)";

if ($conn->query($sql) === TRUE) {
    echo "New records created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
