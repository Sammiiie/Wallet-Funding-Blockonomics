<?php
$page = "login";

include('header.php');


$_SESSION['timestamp'] = time();
// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php");
    exit;
}else{
    session_destroy();
}

// Define variables and initialize with empty values
$username = $password = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $errors = array();

    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        array_push($errors, "Please enter username");
    } else {
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["passkey"]))) {
        array_push($errors, "Please enter your password");
    } else {
        $password = trim($_POST["passkey"]);
    }

    // Validate credentials
    if (count($errors) == 0) {
        // Prepare a select statement
        $sql = "SELECT id, username, email ,password, usertype FROM users WHERE status = 'ACTIVE' AND username = ?";

        if ($stmt = mysqli_prepare($connection, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $email, $hashed_password, $usertype);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            session_regenerate_id();
                            $_SESSION["loggedin"] = true;
                            $_SESSION["userid"] = $id;
                            $_SESSION['username'] = $username;
                            $_SESSION["designation"] = $usertype;
                            $_SESSION["email"] = $username;
                            session_write_close();

                            // Redirect user to welcome page
                            if ($stmt->num_rows == 1 && $_SESSION["loggedin"] == True) {
                                header("location: index.php");
                            }
                        } else {
                            // Display an error message if password is not valid
                            array_push($errors, "Sorry, you entered an incorrect username or password");
                        }
                    }
                } else {
                    // Display an error message if username doesn't exist
                    array_push($errors, "Sorry, you entered an incorrect username or password");
                }
            } else {
                array_push($errors, "Oops! Something went wrong. Please try again later");
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($connection);
}

?>
<?php if (isset($errors)) : ?>
    <?php if (count($errors) > 0) : ?>
        <?php foreach ($errors as $error) : ?>
            <div class="alert alert-danger"><?php echo $error ?></div>
        <?php endforeach ?>
    <?php endif ?>
<?php endif ?>
<!-- content begins here -->
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Login into your account</div>
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label for="">username</label>
                            <input type="text" name="username" id="username" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" name="passkey" id="passkey" class="form-control">
                        </div>
                        <button class="btn btn-danger" type="reset">Reset</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- content ends here -->

<?php

include('footer.php');

?>