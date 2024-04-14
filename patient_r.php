<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Registration - HealSys</title>
    <style>
        body {
            background: url('register.jpg') center/cover;
            height: 100vh;
            width : 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #form-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
        }

        table {
            width: 100%;
        }

        table tr td {
            padding: 10px;
        }
    </style>
</head>
<body>
    <div id="form-container">
        <h2>Patient Registration</h2>
        <form id="patient-registration-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table>
                <tr>
                    <td><label for="first-name">First Name:</label></td>
                    <td><input type="text" id="first-name" name="first-name" required></td>
                </tr>
                <tr>
                    <td><label for="middle-name">Middle Name:</label></td>
                    <td><input type="text" id="middle-name" name="middle-name"></td>
                </tr>
                <tr>
                    <td><label for="last-name">Last Name:</label></td>
                    <td><input type="text" id="last-name" name="last-name" required></td>
                </tr>
                <tr>
                    <td><label for="dob">DOB:</label></td>
                    <td><input type="date" id="dob" name="dob" required></td>
                </tr>
                <tr>
                    <td><label for="gender">Gender:</label></td>
                    <td><select name="gender" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select></td>
                </tr>
                <tr>
                    <td><label for="phone-number">Phone Number:</label></td>
                    <td><input type="tel" id="phone-number" name="phone-number" required></td>
                </tr>
                <tr>
                    <td><label for="address">Address:</label></td>
                    <td><textarea id="address" name="address" rows="4" required></textarea></td>
                </tr>
                <tr>
                    <td colspan="2"><input type="hidden" id="unique-id" name="unique-id" value=""></td>
                </tr>
            </table>
            <button type="submit">SUBMIT</button>
            <input type="reset" value="RESET">
        </form>
    </div>

    <script>
        // Function to set a unique ID before form submission
        document.getElementById('patient-registration-form').addEventListener('submit', function() {
            var generatedUniqueId = generateUniqueId();
            document.getElementById('unique-id').value = generatedUniqueId;
        });

        // Function to generate a unique ID (you can customize this logic)
        function generateUniqueId() {
            // Example: Using current timestamp for simplicity
            return 'PAT' + Date.now();
        }
    </script>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "patientdb";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get form data
        $uniqueId = $_POST['unique-id'];
        $firstName = $_POST['first-name'];
        $middleName = $_POST['middle-name'];
        $lastName = $_POST['last-name'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $phoneNumber = $_POST['phone-number'];
        $address = $_POST['address'];

        // Using prepared statements to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO patient (unique_id, fname, mname, lname, dob, gender, phone_number, address)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $uniqueId, $firstName, $middleName, $lastName, $dob, $gender, $phoneNumber, $address);

        if ($stmt->execute()) {
            echo "<script>alert('Record inserted successfully. Unique ID: $uniqueId');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>