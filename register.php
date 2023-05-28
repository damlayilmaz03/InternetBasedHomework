<?php
$host = 'localhost';
$db = 'student_registration';
$user = 'root';
$password = '';

// Connect to MySQL
$conn = new mysqli($host, $user, $password, $db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the students table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS students (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL
)";
if ($conn->query($sql) === FALSE) {
    echo "Error creating table: " . $conn->error;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];

   
    $errors = array();
    if (empty($fullname)) {
        $errors[] = 'Full Name is required.';
    }
    if (empty($email)) {
        $errors[] = 'Email Address is required.';
    } 
    if (empty($gender)) {
        $errors[] = 'Gender is required.';
    }

    if (empty($errors)) {
        // Insert student information into the database
        $stmt = $conn->prepare("INSERT INTO students (full_name, email, gender) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $fullname, $email, $gender);
        if ($stmt->execute()) {
            
            echo "Student registered successfully.";
        } else {
            
            echo "Error inserting data: " . $conn->error;
        }
    } else {
        
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    }
}


$sql = "SELECT * FROM students";
$result = $conn->query($sql);

if ($result !== FALSE && $result->num_rows > 0) {
    echo "<h2>Registered Students</h2>";
    echo "<table>";
    echo "<tr><th>ID</th> <th>Full Name</th> <th>Email</th> <th>Gender</th> </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['full_name'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['gender'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No students registered yet.";
}


$conn->close();
?>
