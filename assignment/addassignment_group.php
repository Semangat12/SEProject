<?php
    require_once "../functions.php";
    StartLoginSession();
    
    $username = $_SESSION["username"];
    $userdata = GetUserData($username);
    $groupid = $_GET["groupid"];
    $accountID = $userdata["accountID"];
    ValidateGroupLink($accountID, $groupid, "../index.php", true);
    $members = GetMemberListByGroupID($groupid);
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

    #assignment-list {
        width: 50%;
    }

    #add-asg-btn,
    #add-memlist-btn {
        display: flex;
        justify-content: flex-end;
        /* margin: 1rem; */
    }
    </style>

    <title>Assigner</title>
</head>

<body>


    <!-- Navbar -->
    <?php include "../header.php" ?>
    <!-- End of Navbar -->

    <!-- Contents -->
    <div class="container">

        <div class="row">
            <h4 class="mt-4 mb-4">Add Assignment to <?= GetGroupNameByID($groupid) ?></h4>
        </div>

        <div class="row">
            <?php 
                if(isset($_POST["add-asg-button"])){
                    $asgTitle = mysqli_real_escape_string($connectionID, $_POST["asg-title"]);
                    $asgDetails = mysqli_real_escape_string($connectionID, $_POST["asg-details"]);
                    $asgDeadline = $_POST["asg-deadline"];
                    
                    $today = date("Y-m-d");

                    if(empty($asgTitle) || empty($asgDeadline) || !isset($_POST["row-check"])) {
                        echo 
                        '<div class="alert alert-danger" role="alert" id="success-message">
                        Failed to add an assignment. You have to fill the required form.
                        </div>';  
                    } 

                    else if($asgDeadline < $today)
                        echo 
                        '<div class="alert alert-danger" role="alert" id="success-message">
                        Failed to add an assignment. Assignment date must be greater than today.
                        </div>'; 
                        
                    
                    else {

                        $members_checked = $_POST["row-check"];

                        AddAssignment($members_checked, $groupid, $asgTitle, $asgDetails, $today, $asgDeadline, true);
                        
                        echo 
                        '<div class="alert alert-success" role="alert" id="success-message">
                        Successfully added an assignment!
                        </div>';  

                        
                    }
                }
            ?>
        </div>

        <form action="" method="post">
            <div class="row">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1"><i class="bi bi-clipboard-check"></i></span>
                    <input type="text" class="form-control" placeholder="Assignment's Title (*)" name="asg-title">
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text">Description</span>
                    <textarea class="form-control" aria-label="With textarea" name="asg-details" style="white-space: pre-line"></textarea>
                </div>

            </div>

            <div class="col-md mb-3">
                <div class="form-floating">
                    <input type="date" class="form-control" id="floatingInputGrid" placeholder=""
                        value="mdo@example.com" name="asg-deadline">
                    <label for="floatingInputGrid">Deadline Date (*)</label>
                </div>
            </div>

            <h5>Members to be assigned</h5>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>
                            <div>
                                <input class="form-check-input" type="checkbox" id="check-all" value="" aria-label="..."
                                    onclick=checkAllGroupMembersAsg();>
                            </div></button>
                        </th>
                        <th scope="col">Username</th>
                        <th scope="col">Name</th>
                        <th scope="col">Position</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($members as $member):  ?>
                    <tr>
                        <th scope="row">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="<?= $member["accountID"]; ?>"
                                    id="row-check" name="<?="row-check[" . $member["accountID"] . "]"?>">
                            </div>
                        </th>
                        <td><?= "@" . $member["username"]; ?></td>
                        <td><?= GetUserFullName($member["accountID"]) ?></td>
                        <td><?= GetUserPositionInGroup($member["accountID"], $groupid) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>


            <button button type=" submit" class="btn btn-success" name="add-asg-button">Add Assignment</button>

        </form>

    </div>

    </div>
    <!-- End of contents -->

    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#212529" fill-opacity="1"
            d="M0,128L34.3,133.3C68.6,139,137,149,206,176C274.3,203,343,245,411,234.7C480,224,549,160,617,149.3C685.7,139,754,181,823,186.7C891.4,192,960,160,1029,160C1097.1,160,1166,192,1234,197.3C1302.9,203,1371,181,1406,170.7L1440,160L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"
            data-darkreader-inline-fill="" style="--darkreader-inline-fill:#007acc;"></path>
    </svg>
    <!-- Footer -->
    <footer class="bg-dark text-white  pb-5">
        <p class="font-weight-bold text-center fs-5">Created by : Tesla Team</p>
    </footer>

    <script src="../script.js"></script>
    <!-- End of Footer -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>



</body>

<input type="datetime-local" class="form-control" placeholder="Server" aria-label="Server">

</html>