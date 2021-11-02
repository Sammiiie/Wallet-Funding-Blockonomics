<?php

include('functions/methods.php');
session_start();
if (isset($_SESSION['userid'])) {
    $userId = $_SESSION['userid'];

    $findAccount =  selectOne('accounts', ['users_id' => $userId]);
    if ($findAccount['uuid_status'] == 0) {
        include('fund_account.php');
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fund Wallet</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- style -->
    <link rel="stylesheet" href="style.css">

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <!-- sweet alert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- feedback to customer using sweet alert -->
    <?php
    $exp_error = "";
    $message = $_SESSION['feedback'];
    if ($message != "") {
    ?>
        <input type="text" value="<?php echo $message ?>" id="feedback" hidden>
    <?php
    }
    // feedback messages 0 for success and 1 for errors

    if (isset($_GET["message0"])) {
        $key = $_GET["message0"];
        $tt = 0;

        if ($tt !== $_SESSION["lack_of_intfund_$key"]) {
            echo '<script type="text/javascript">
          $(document).ready(function(){
            let feedback =  document.getElementById("feedback").value;
              swal({
                  type: "success",
                  title: "Success",
                  text: feedback,
                  showConfirmButton: true,
                  timer: 7000
              })
          });
          </script>
          ';
            $_SESSION["lack_of_intfund_$key"] = 0;
        }
    } else if (isset($_GET["message1"])) {
        $key = $_GET["message1"];
        $tt = 0;
        if ($tt !== $_SESSION["lack_of_intfund_$key"]) {
            echo '<script type="text/javascript">
          $(document).ready(function(){
            let feedback =  document.getElementById("feedback").value;
              swal({
                  type: "error",
                  title: "Error",
                  text: feedback,
                  showConfirmButton: true,
                  timer: 7000
              })
          });
          </script>
          ';
            $_SESSION["lack_of_intfund_$key"] = 0;
        }
    }
    ?>

</head>

<body style="font-family: 'Montserrat', sans-serif; letter-spacing: 0.5px;">

    <?php
    if (isset($_SESSION['userid'])) {
        $findAccount = selectOne('accounts', ['users_id' => $userId]);
    ?>
        <a href="#" style="text-decoration:none"><b>Balance:</b> <span style="background-color:red; color:white; font-weight:bold">$ <?php echo number_format($findAccount['balance'], 2); ?></span></a> || 
        <a href="#" style="text-decoration:none"><span class="material-icons">account_circle</span>: <?php echo $_SESSION['username'] ?> </a>
    <?php
    }
    ?>
    <div class="container-fluid">
        <script>
            /* Toggle between adding and removing the "responsive" class to topnav when the user clicks on the icon */
            function myFunction() {
                var x = document.getElementById("myTopnav");
                if (x.className === "topnav") {
                    x.className += " responsive";
                } else {
                    x.className = "topnav";
                }
            }
        </script>
        <div class="topnav" id="myTopnav">
            <a href="index.php" class="<?php if ($page == "home") {
                                            echo 'active';
                                        } ?>">Home</a>
            <?php
            if (isset($_SESSION["loggedin"]) == True) {
            ?>
                <a href="purchased.php" class="<?php if ($page == "purchased") {
                                                    echo 'active';
                                                } ?>">Purchased</a>
                <div class="dropdown2">
                    <button class="dropbtn">Account
                        <i class="fa fa-caret-down"></i>
                    </button>
                    <div class="dropdown2-content">
                        <a href="wallet.php">Wallet</a>
                        <a href="#"> ME</a>
                    </div>
                </div>
                <a href="functions/logout.php">Logout</a>
            <?php
            } else {
            ?>
                <a href="login.php">Login</a>
            <?php
            }
            ?>
            <!-- js for resposive dropdown -->
            <a href="javascript:void(0);" class="icon" onclick="myFunction()">&#9776;</a>
        </div>
    </div>