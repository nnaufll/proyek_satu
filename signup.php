<?php
include 'db_connect.php';  // Pastikan koneksi ke database sudah benar

$signup_message = "";  // Variabel untuk menyimpan pesan signup

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];  // Ambil role dari form

    // Validasi role
    if ($role == "") {
        $signup_message = "Pilih role (jobseeker atau employer) sebelum mendaftar.";
    } else if ($password !== $confirm_password) {
        $signup_message = "Password dan konfirmasi password tidak cocok!";
    } else {
        // Lanjutkan dengan proses signup jika semua validasi berhasil
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (email, password, role) VALUES ('$email', '$hashed_password', '$role')";

        if ($conn->query($sql) === TRUE) {
            $signup_message = "Pendaftaran berhasil. Silakan login.";
            header("Location: login.php");
            exit();
        } else {
            $signup_message = "Terjadi kesalahan saat mendaftar: " . $conn->error;
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portoline - Sign Up</title>
    <link rel="stylesheet" href="style.css">
</head>
<body onload="checkSignupMessage()">
    <div class="login-container">
        <div class="image-section">
            <!-- Transparent box remains here but without img tag, background will be applied via CSS -->
        </div>
        <div class="login-section">
            <div class="logo">
                <img src="logo.png" alt="Portoline Logo">
            </div>
            <h2>Create Your Account</h2>
            <p>Join Portoline and get started</p>

            <!-- Sign Up Form -->
            <form name="signupForm" action="signup.php" method="POST" onsubmit="return validateForm()">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>

                <!-- Button to trigger modal -->
                <button type="button" id="selectRoleBtn" class="btn select-role-btn">Select Role</button>

                <!-- The Modal -->
                <div id="roleModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2 style="color: balck;">Select Account Type</h2>
                        <button type="button" class="modal-option-btn employer-btn" onclick="selectRole('Employer')">Employer</button>
                        <button type="button" class="modal-option-btn jobseeker-btn" onclick="selectRole('Jobseeker')">Job Seeker</button>
                    </div>
                </div>

                <!-- Hidden input for role selection -->
                <input type="hidden" id="selectedRole" name="role" value="">

                <button type="submit" class="btn sign-up-btn">Sign Up</button>
                <a href="login.php" class="forgot-password">Already have an account? Login here</a>
            </form>
        </div>
    </div>

    <script>
        // Get the modal element
        var modal = document.getElementById('roleModal');
        
        // Get the button that opens the modal
        var selectRoleBtn = document.getElementById('selectRoleBtn');
        
        // Get the close button inside the modal
        var closeModal = document.getElementsByClassName('close')[0];
        
        // When the user clicks on the Select Role button, open the modal
        selectRoleBtn.onclick = function() {
            modal.style.display = "flex";  // Display the modal
        }
        
        // When the user clicks on the close button, close the modal
        closeModal.onclick = function() {
            modal.style.display = "none";  // Hide the modal
        }
        
        // When the user clicks outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";  // Hide the modal
            }
        }

        // Function to select a role and close the modal
        function selectRole(role) {
            var normalizedRole = role.toLowerCase();  // Pastikan role menggunakan huruf kecil
            document.getElementById('selectedRole').value = normalizedRole;  // Mengisi nilai role pada input tersembunyi
            document.getElementById('selectRoleBtn').innerText = role;  // Menampilkan role pada tombol
            modal.style.display = "none";  // Menutup modal
        }

        // Function to validate the form before submitting
        function validateForm() {
            var role = document.forms["signupForm"]["role"].value;
            if (role == "") {
                alert("Pilih role (jobseeker atau employer) sebelum mendaftar.");
                return false;
            }
            return true;
        }

        // Function to check if there's a signup message and display it as alert
        function checkSignupMessage() {
            var signupMessage = "<?php echo $signup_message; ?>";
            if (signupMessage !== "") {
                alert(signupMessage);
            }
        }
    </script>

</body>
</html>
