<?php
session_start();
require('functions.php');

// Redirect if not logged in or not an admin
// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
//     header("Location: login.php");
//     exit;
// }

// Fetch all users from the database
$result = $con->query("SELECT * FROM users WHERE role='user'");

$message = '';
if (isset($_GET['delete'])) {
    $userIdToDelete = intval($_GET['delete']);
    if (deleteUser($userIdToDelete)) {
        $message = "User removed successfully.";
    } else {
        $message = "Error removing user.";
    }
}

// Function to delete a user
function deleteUser($userId) {
    global $con;
    $stmt = $con->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    return $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ccc;
        }
        th {
            background-color: #f2f2f2;
        }
        a {
            color: #d9534f;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
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
    <h1>Manage Users</h1>
    
    <!-- Modal for messages -->
    <div id="messageModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modalMessage"></p>
        </div>
    </div>

    <script>
        // Show the modal with the message
        function showMessage(message) {
            document.getElementById("modalMessage").innerText = message;
            document.getElementById("messageModal").style.display = "block";
        }

        // Close the modal when the user clicks on <span> (x)
        document.querySelector('.close').onclick = function() {
            document.getElementById("messageModal").style.display = "none";
        }

        // Close the modal when the user clicks outside of it
        window.onclick = function(event) {
            if (event.target === document.getElementById("messageModal")) {
                document.getElementById("messageModal").style.display = "none";
            }
        }

        // Show message if it exists
        <?php if ($message): ?>
            showMessage(<?php echo json_encode($message); ?>);
        <?php endif; ?>
    </script>

    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($user = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <a href="?delete=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Remove</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h2><a href="logout.php">Logout</a></h2>
</body>
</html>