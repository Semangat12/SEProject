<?php

require_once "../functions.php";

StartLoginSession();
$username = $_SESSION["username"];
$userdata = GetUserData($username);
$accountID = $userdata["accountID"];
$unopenedNotifsSize = GetUnopenedNotifsSize($accountID);


?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Boostrap Icons  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- My CSS -->
    <style>
    .content {
        margin-top: 10vh;
        /* height: 26rem; */
    }
    </style>

    <title>Assigner</title>
</head>

<body>

    <?php include "../header.php"; ?>

    <!-- Contents -->

    <div class="container">
        
        <?php 
            if(isset($_POST["login-btn"])) {

                $newFirstName = $_POST["input-firstname"];
                $newLastName = $_POST["input-lastname"];
                $newUsername = $_POST["input-username"];
                
                mysqli_query($connectionID, "UPDATE accounts SET firstname = '$newFirstName', lastname = '$newLastName' WHERE accountID = $accountID");

                Refresh();
            }

        ?>
        

        <div class="row mt-5 p-3 border-bottom">
            <div class="col  col-2 d-flex justify-content-center">
                <img src="../images/default.jpeg" class="img-thumbnail border border-primary rounded-circle width"
                    alt="..." style="max-height:20vh;">
            </div>
            <div class="col col-8 p-3 d-flex align-items-center">
                <h1><?= $userdata["username"] ?></h1>
            </div>
        </div>

        <div class="row mt-5">
            <form method="post">
                <!-- <div class="row mb-3">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Username</label>
                    <div class="col-sm-10">
                        <input type="name" name = "input-username" class="form-control" id="inputEmail3" value="<?= $userdata["username"] ?>">
                    </div>
                </div> -->

                <!-- <div class="row mb-3">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="inputEmail"
                                value="<?= $userdata["username"] ?>">
                        </div>
                    </div> -->


                <div class="row mb-3">
                    <div class="col">
                        <label for="inputfn" class="col-sm-2 col-form-label">First Name</label>
                        <input type="text" class="form-control" aria-label="First name" name="input-firstname"
                            id="inputfn" value="<?= $userdata["firstname"] ?>">
                    </div>
                    <div class="col">
                        <label for="inputln" class="col-sm-2 col-form-label">Last name</label>
                        <input type="text" class="form-control" aria-label="Last name" name="input-lastname"
                            id="inputln" value="<?= $userdata["lastname"]?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="formFile" class="form-label">Profile Picture </label>
                    <input class="form-control" type="file" id="formFile">
                </div>

                <button type="submit" class="btn btn-primary" name="login-btn">Update</button>
            </form>

        </div>

    </div>




    <!-- End of contents -->

    <svg xmlns=" http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#212529" fill-opacity="1"
            d="M0,128L34.3,133.3C68.6,139,137,149,206,176C274.3,203,343,245,411,234.7C480,224,549,160,617,149.3C685.7,139,754,181,823,186.7C891.4,192,960,160,1029,160C1097.1,160,1166,192,1234,197.3C1302.9,203,1371,181,1406,170.7L1440,160L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"
            data-darkreader-inline-fill="" style="--darkreader-inline-fill:#007acc;">
        </path>
    </svg>
    <!-- Footer -->
    <footer class="bg-dark text-white  pb-5">
        <p class="font-weight-bold text-center fs-5">Created by : Tesla Team</p>
    </footer>


    <!-- End of Footer -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <script src="script.js"></script>

</body>

</html>