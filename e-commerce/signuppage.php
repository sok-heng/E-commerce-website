<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('./server/connection.php');

if (isset($_POST['signup'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate password length
    if (strlen($password) < 8) {
        header('Location: signuppage.php?error=Password must be at least 8 characters');
        exit();
    }

    // Check if email already exists
    $stmt1 = $conn->prepare("SELECT COUNT(*) FROM users WHERE user_email = ?");
    $stmt1->bind_param('s', $email);
    $stmt1->execute();
    $stmt1->bind_result($num_rows);
    $stmt1->fetch();
    $stmt1->close();

    if ($num_rows != 0) {
        header('Location: signuppage.php?error=User with this email already exists');
        exit();
    } else {
        // Secure password hashing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $stmt = $conn->prepare("INSERT INTO users (user_name, user_email, user_password, user_role) VALUES (?, ?, ?, 'user')");
        $stmt->bind_param('sss', $name, $email, $hashed_password);

        if ($stmt->execute()) {
            // ✅ Store necessary session variables for first login
            $_SESSION['user_id'] = $conn->insert_id; // this was missing
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = 'user';
            $_SESSION['logged_in'] = true;

            // ✅ Redirect directly to profile page
            header('Location: homepage.php');
            exit();
        } else {
            header('Location: signuppage.php?error=Could not create account at the moment');
            exit();
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign Up</title>
  <link rel="stylesheet" href="./css/output.css" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
  <div class="flex h-screen w-full items-center justify-center bg-gray-900 bg-cover bg-no-repeat" style="background-image:url('https://i.pinimg.com/originals/85/e3/7f/85e37f679fc831f77076fc85ce9630c5.gif')">
    <div class="rounded-xl bg-white bg-opacity-50 px-16 py-10 shadow-lg backdrop-blur-md max-sm:px-8">
      <div class="text-white">
        <div class="mb-8 flex flex-col items-center">
          <img src="./picture/fulllogo_transparent.png" width="150" alt="Instagram Logo" />
          <h1 class="mb-2 text-2xl">bloomskin</h1>
          <span class="text-pink-300">Enter Sign up Details</span>
        </div>

        <form method="POST" action="signuppage.php" id="signup-form">
          <p class="text-red-500 mb-4">
            <?php if (isset($_GET['error'])) echo htmlspecialchars($_GET['error']); ?>
          </p>

          <div class="mb-4 text-lg">
            <input class="rounded-3xl border-none bg-pink-400 bg-opacity-50 px-6 py-2 text-center placeholder-slate-200 shadow-lg outline-none backdrop-blur-md" type="text" name="name" placeholder="Username" required />
          </div>

          <div class="mb-4 text-lg">
            <input class="rounded-3xl border-none bg-pink-400 bg-opacity-50 px-6 py-2 text-center placeholder-slate-200 shadow-lg outline-none backdrop-blur-md" type="email" name="email" placeholder="Email" required />
          </div>

          <div class="mb-4 text-lg">
            <input class="rounded-3xl border-none bg-pink-400 bg-opacity-50 px-6 py-2 text-center placeholder-slate-200 shadow-lg outline-none backdrop-blur-md" type="password" name="password" placeholder="Password" required />
          </div>

          <div class="mt-8 flex justify-center text-lg text-black">
            <button type="submit" name="signup" class="rounded-3xl bg-pink-400 bg-opacity-50 px-10 py-2 text-white shadow-xl backdrop-blur-md transition-colors duration-300 hover:bg-pink-600">Sign up</button>
          </div>

          <div class="pt-5 pb-5 text-center">
            <p>-------------- login --------------</p>
            <p class="hover:text-blue-400"><a href="./loginpage.php">Back to login page</a></p>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
