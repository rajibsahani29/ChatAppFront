<?php
include("connection.php");
if (CheckLogin()) {
   header('Location: chat.php');
}

if (isset($_POST["btnLogin"])) {
   $UserName = $_POST["txtUserName"];
   $Password = $_POST["txtPassword"];
   $q = "SELECT * FROM user u WHERE u.Email='$UserName'";


   $result = mysqli_query($con, $q);
   if ($row = mysqli_fetch_array($result)) {
      if ($row['Password'] == $Password) {
         $_SESSION['UserID'] = $row['UserID'];
         $_SESSION['Email'] = $row['Email'];
         $_SESSION['Name'] = $row['Name'];
         header('Location: chat.php');
      } else {
         $_SESSION['ftpmsg'] = "Username and Password is incorrect1.";
         header('Location: index.php');
      }
   } else {
      $_SESSION['ftpmsg'] = "Username and Password is incorrect2.";
      header('Location: index.php');
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1.0" />
   <meta http-equiv="X-UA-Compatible" content="ie=edge" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css" integrity="sha256-mmgLkCYLUQbXn0B1SRqzHar6dCnv9oZFPEC1g1cwlkk=" crossorigin="anonymous" />
   <link rel="stylesheet" href="css/style.css" />
   <title>ChatCord App</title>
</head>

<body>
   <div class="join-container">
      <header class="join-header">
         <h1><i class="fas fa-smile"></i> ChatCord</h1>
      </header>
      <main class="join-main">
         <form action="" method="POST">
            <div class="form-control">
               <label for="username">Username</label>
               <input type="text" name="txtUserName" id="txtUserName" placeholder="Enter username..." required />
            </div>
            <div class="form-control">
               <label for="password">Password</label>
               <input type="password" name="txtPassword" id="txtPassword" placeholder="Enter password" required />
            </div>
            <button name="btnLogin" type="submit" class="btn">Login</button>
            <div style="color:red;margin: 0;font-size: 16px;font-weight: bold;">
               <?php
               session_start();
               if (isset($_SESSION['ftpmsg'])) {
                  echo "<font>" . $_SESSION['ftpmsg'] . "</font>";
               } else {
                  echo "";
               }
               unset($_SESSION['ftpmsg']);
               //session_destroy(); 
               ?>
            </div>
         </form>
      </main>
   </div>
</body>

</html>