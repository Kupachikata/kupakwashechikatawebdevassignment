<?php
session_start();
require('functions.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$userRole = $_SESSION['role'];

// Fetch user info from database
$result = $con->query("SELECT * FROM users WHERE id='$userId'");
$user = $result->fetch_assoc();

$message = ''; // Initialize message variable
if (isset($_POST['update'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (updateUserProfile($userId, $email, $password)) {
        $message = "Profile updated successfully.";
    } else {
        $message = "Error updating profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        h1 {
            color: #4CAF50;
        }
        h2 {
            color: #333; 
            margin-top: 20px;
        }
        h3 {
            position: absolute;
            bottom: 10px; /* Distance from bottom */
            left: 0;
            right: 0;
            text-align: center; /* Center text */
            margin: 0; /* Remove margin */
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        input[type="email"],
        input[type="password"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        a {
            text-decoration: none;
            color: #4CAF50;
        }
        a:hover {
            text-decoration: underline;
        }
        .logout {
            margin-top: 20px;
        }
        /* Modal styles */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px; 
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
            max-width: 400px;
            text-align: center;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Profile of <?php echo htmlspecialchars($user['username']); ?></h1>
    <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
    <p>Role: <?php echo htmlspecialchars($userRole); ?></p>

    <?php if ($userRole === 'Admin'): ?>
        <h2>Welcome Admin</h2>
        <h3><a href="admin.php">Go to Dashboard</a></h3>
    <?php endif; ?>

    <h2>Update Profile</h2>
    <form action="" method="POST">
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <input type="password" name="password" placeholder="New Password (leave blank to keep current)">
        <input type="submit" name="update" value="Update">
    </form>

    <!-- Modal for messages -->
    <div id="messageModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modalMessage"><?php echo htmlspecialchars($message); ?></p>
        </div>
    </div>

    <script>
        // Show the modal with the message if it exists
        const messageModal = document.getElementById("messageModal");
        const modalMessage = document.getElementById("modalMessage");
        const closeModal = document.querySelector('.close');

        <?php if ($message): ?>
            modalMessage.innerText = <?php echo json_encode($message); ?>;
            messageModal.style.display = "block";
        <?php endif; ?>

        // Close the modal when the user clicks on <span> (x)
        closeModal.onclick = function() {
            messageModal.style.display = "none";
        }

        // Close the modal when the user clicks outside of it
        window.onclick = function(event) {
            if (event.target === messageModal) {
                messageModal.style.display = "none";
            }
        }
    </script>

    <h2 class="logout">Logout</h2>
    <a href="logout.php">Logout</a>
</body>
</html>