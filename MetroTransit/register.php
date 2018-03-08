<?php
    // include database connection
    include 'db.php';
    include 'settings.php';
    $mysqli = $con;

    $username = $password = $confirm_password = "";
    $username_err = $password_err = $confirm_password_err = "";
    
    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){
    
        // Validate username
        if(empty(trim($_POST["username"]))){
            $username_err = "Please enter a username.";
        } else{
            $param_username = trim($_POST["username"]);
            
            switch($ValidateUsernameSetting){
                case "Low":
                    $username = ValidateUsername_Low($param_username, $mysqli);
                    break;
                case "High":
                    $username = ValidateUsername_High($param_username, $mysqli);
                    break;
            }
            
            if($username == "This username is already taken."){
                $username_err = $username;
            }
        }
        
        // Validate password
        if(empty(trim($_POST['password']))){
            $password_err = "Please enter a password.";     
        } elseif(strlen(trim($_POST['password'])) < 6){
            $password_err = "Password must have atleast 6 characters.";
        } else{
            $password = trim($_POST['password']);
        }
    
        // Validate confirm password
        if(empty(trim($_POST["confirm_password"]))){
            $confirm_password_err = 'Please confirm password.';     
        } else{
            $confirm_password = trim($_POST['confirm_password']);
            if($password != $confirm_password){
                $confirm_password_err = 'Password did not match.';
            }
        }
        
        // Check input errors before inserting in database
        if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
            switch($RegisterSetting){
                case "Low":
                    Register_Low($username, $password, $mysqli);
                    break;
                case "High":
                    Register_High($username, $password, $mysqli);    
                    break;
            }
        }
        
    }

    function ValidateUsername_High($param_username, $mysqli){
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $username_err = "This username is already taken.";
                    $stmt->close();
                    return $username_err;
                } else{
                    $username = $param_username;
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        $stmt->close();

        return $username;    
    }

    function ValidateUsername_Low($param_username, $mysqli){
        $sql = "SELECT id FROM users WHERE username = '$param_username'";
        if($result = $mysqli->query($sql)){
            if($result->num_rows == 1){
                $username_err = "This username is already taken.";
                return $username_err;
            }
            else{
                $username = $param_username;
            }
        }
        else{
            echo "Something went wrong. Please try again later.";
            echo $mysqli->errno;
        }   
        return $username;
    }

    function Register_High($param_username, $param_password, $mysqli){
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, credits) VALUES (?, ?, '300.00')";

        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ss", $param_username, $param_password);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
        else{
            echo "Something went wrong. Please try again later.";
        }

        // Close connection
        $mysqli->close();
    }

    function Register_Low($param_username, $param_password, $mysqli){
        $sql = "INSERT INTO users (username, password, credits) VALUES ('$param_username', '$param_password', '300.00')";
        if($mysqli->query($sql)){
            header("location: login.php");
        }
        else{
            echo "Something went wrong. Please try again later.";
            echo $mysqli->errno;
        }     
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>


  <!-- CSS  -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
  <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
    <meta charset="UTF-8">
    <title>Registration</title>

    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
<nav class="white" role="navigation">
    <div class="nav-wrapper container">
      <a id="logo-container" href="/" class="brand-logo">MetroTransit</a>
      <ul class="right hide-on-med-and-down">
          <li><a href="tickets.php">Tickets</a></li>
          <li><a href="account.php">Account</a></li>
      </ul>

      <ul id="nav-mobile" class="side-nav">
        <li><a href="tickets.php">Tickets</a></li>
        <li><a href="account.php">Account</a></li>
      </ul>
      <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
    </div>
  </nav>
  
    <div class="wrapper">
        <h2>Register</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username"class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>