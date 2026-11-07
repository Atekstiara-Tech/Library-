<?php
$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $dob = $_POST['date_of_birth'];
    $programme = $_POST['programme'];
    $level = $_POST['level'];
    $gender = $_POST['gender'] ?? '';

    if ($full_name == "" || $email == "" || $telephone == "" || $dob == "" || $programme == "" || $level == "" || $gender == "") {
        $message = "Please fill all fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO students (full_name, email, telephone, date_of_birth, programme, level, gender) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $full_name, $email, $telephone, $dob, $programme, $level, $gender);
        if ($stmt->execute()) {
            $message = "Student registered successfully!";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
$result = $conn->query("SELECT * FROM students ORDER BY student_id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Student Registration Form</h2>

    <?php if ($message != "") { echo "<p class='message'><b>$message</b></p>"; } ?>

    <form method="POST">
        <div class="form-header">Register Student</div>

        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="full_name" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Telephone</label>
            <input type="tel" name="telephone" required>
        </div>

        <div class="form-group">
            <label>Date of Birth</label>
            <input type="date" name="date_of_birth" required>
        </div>

        <div class="form-group">
            <label>Programme</label>
            <select name="programme" required>
                <option value="">Select Programme</option>
                <option>Computer Science</option>
                <option>Software Engineering</option>
                <option>Information Technology</option>
                <option>Business Administration</option>
            </select>
        </div>
        <div class="form-group">
            <label>Level</label>
            <select name="level" required>
                <option value="">Select Level</option>
                <option>Level 1</option>
                <option>Level 2</option>
                <option>Level 3</option>
            </select>
        </div>
        <div class="form-group">
            <label>Gender</label>
            <div class="radio-group">
                <label><input type="radio" name="gender" value="Male" required> Male</label>
                <label><input type="radio" name="gender" value="Female"> Female</label>
            </div>
        </div>
        <div class="form-buttons">
            <button type="submit">Register</button>
            <button type="reset">Clear</button>
        </div>
    </form>
    <h2>Registered Students</h2>
    <table>
        <tr>
            <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>DOB</th><th>Programme</th><th>Level</th><th>Gender</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['student_id']; ?></td>
            <td><?php echo $row['full_name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['telephone']; ?></td>
            <td><?php echo $row['date_of_birth']; ?></td>
            <td><?php echo $row['programme']; ?></td>
            <td><?php echo $row['level']; ?></td>
            <td><?php echo $row['gender']; ?></td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>