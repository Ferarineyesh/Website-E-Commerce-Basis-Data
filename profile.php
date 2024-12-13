<?php
// Start the session
session_start();

// Include the database connection file
include("conn.php");

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "
    <script>
        alert('You must log in first!');
        window.location.href = 'login.html';
    </script>
    ";
    exit;
}

// Get the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Fetch user data from the database
$query = "SELECT * FROM users WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "User not found.";
    exit;
}

// If the form is submitted, update the user profile data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = mysqli_real_escape_string($conn, $_POST['fullname']);
    $last_name = mysqli_real_escape_string($conn, $_POST['lastname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    $update_query = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', email = '$email', phone = '$phone', address = '$address', updated_at = CURRENT_TIMESTAMP WHERE user_id = $user_id";
    if (mysqli_query($conn, $update_query)) {
        echo "
        <script>
            alert('Profile updated successfully');
            window.location.href = 'profile.php';
        </script>
        ";
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="./css/profile.css">
</head>
<body>

<header class="header">
    <div class="logo-container">
        <img src="./assets/logo.png" alt="Logo" class="logo">
        <h1 class="name">SIMPLE MART</h1>
    </div>
    <nav class="nav-links">
        <a href="index.php" class="nav-link">Home</a>
        <a href="index.php#categories" class="nav-link">Categories</a>
        <a href="index.php#products" class="nav-link">Products</a>
        <a href="cart.php" class="nav-link">Carts</a>
        <a href="index.php#contact" class="nav-link">Contact Us</a>
        <a href="/profile.php" class="profile-link"><img src="./assets/profil.png" alt="Profile" class="profile-icon"></a>
    </nav>
</header>
    
    <div class="profile-container">

        <div class="profile-box">
            
            <!-- Profile Image -->
            <img src="./assets/profile/Avatar.png" alt="User Avatar">

            <!-- Displaying User Information (Read-Only initially) -->
            <h2 id="user-name"><?php echo $user['first_name']; ?> <?php echo $user['last_name']; ?></h2>
            <p>
                <span id="user-email"><?php echo $user['email']; ?></span>
                |
                <span id="user-phone"><?php echo $user['phone']; ?></span>
            </p>
            <p id="user-address"><?php echo $user['address']; ?></p>

            <!-- Edit Profile Button -->
            <button id="edit-btn" class="edit-btn" onclick="toggleEditMode()">Edit Profile</button>

            <!-- Form to Edit Profile (Initially Hidden) -->
            <form class="form-container" method="POST" id="edit-form" style="display: none;">
                <div class="form-group">
                    <label for="fullname">First Name</label>
                    <input type="text" id="fullname" name="fullname" value="<?php echo $user['first_name']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="lastname">Last Name</label>
                    <input type="text" id="lastname" name="lastname" value="<?php echo $user['last_name']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone number</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo $user['phone']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" value="<?php echo $user['address']; ?>" required>
                </div>
                <button type="submit" class="submit-btn">Submit</button>
            </form>
        </div>

    </div>

    <script>
        // Function to toggle between edit and view mode
        function toggleEditMode() {
            var editForm = document.getElementById('edit-form');
            var userInfo = document.getElementById('user-name').parentElement;
            var editBtn = document.getElementById('edit-btn');
            
            // Toggle visibility of edit form and read-only fields
            if (editForm.style.display === 'none') {
                editForm.style.display = 'block';  // Show the edit form
                editBtn.textContent = 'Cancel';   // Change button text to "Cancel"
                
                // Hide user information (display as editable)
                document.getElementById('user-name').style.display = 'none';
                document.getElementById('user-email').style.display = 'none';
                document.getElementById('user-phone').style.display = 'none';
                document.getElementById('user-address').style.display = 'none';
            } else {
                editForm.style.display = 'none';   // Hide the edit form
                editBtn.textContent = 'Edit Profile';  // Change button text back to "Edit Profile"
                
                // Show user information in read-only state
                document.getElementById('user-name').style.display = 'block';
                document.getElementById('user-email').style.display = 'block';
                document.getElementById('user-phone').style.display = 'block';
                document.getElementById('user-address').style.display = 'block';
            }
        }
    </script>

</body>
</html>
