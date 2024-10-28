<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background:url("Background.jpg") no-repeat;
        }

        .container {
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 90%;  /* Responsive width */
            max-width: 400px; /* Maximum width */
        }

        h1 {
            margin: 0; /* Remove default margin */
            padding: 20px 0; /* Optional: add some vertical padding */
            color: #333; /* Set text color */
            text-align: center; /* Align text to the left or center as needed */
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="text"],
        input[type="password"],
        input[type="submit"] {
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #007bff;
            outline: none;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        p {
            text-align: center;
            color: #666;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Modal styles */
        #errorModal {
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

        #modalContent {
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
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" name="submit" value="Login">
        </form>

        <?php
        session_start();
        require('functions.php');

        $errorMessage = ''; // Initialize error message variable

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = loginUser($username, $password);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                // Redirect to the appropriate page based on the user's role
                if ($_SESSION['role'] === 'admin') {
                    header("Location: admin.php"); // Redirect to admin page
                } else {
                    header("Location: profile.php"); // Redirect to user profile page
                }
                exit;
            } else {
                $errorMessage = "Invalid username or password. <a href='register.php'>Register here</a>";
            }
        }
        ?>

        <!-- Modal for error messages -->
        <div id="errorModal">
            <div id="modalContent">
                <span class="close">&times;</span>
                <p id="modalErrorMessage"><?php echo htmlspecialchars($errorMessage); ?></p>
            </div>
        </div>

        <script>
            // Show the modal with the error message if it exists
            const errorModal = document.getElementById("errorModal");
            const modalErrorMessage = document.getElementById("modalErrorMessage");
            const closeModal = document.querySelector('.close');

            // Show modal if there is an error message
            <?php if ($errorMessage): ?>
                modalErrorMessage.innerHTML = <?php echo json_encode($errorMessage); ?>;
                errorModal.style.display = "block";
            <?php endif; ?>

            // Close the modal when the user clicks on <span> (x)
            closeModal.onclick = function() {
                errorModal.style.display = "none";
            }

            // Close the modal when the user clicks outside of it
            window.onclick = function(event) {
                if (event.target === errorModal) {
                    errorModal.style.display = "none";
                }
            }
        </script>
    </div>
</body>
</html>