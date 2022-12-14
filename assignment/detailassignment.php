<?php
    require_once "../functions.php";
    StartLoginSession();
    
    $username = $_SESSION["username"];
    $userdata = GetUserData($username);
    $asgid = $_GET["asgid"];
    $groupid = $_GET["groupid"];
    $accountID = $userdata["accountID"];
    ValidateAsgLink($accountID, $asgid, $groupid, "../index.php");
    $unopenedNotifsSize = GetUnopenedNotifsSize($accountID);

    $assignments = Query("SELECT * FROM assignments WHERE assignmentID = $asgid");
    $asgdata = $assignments[0];

    if(isset($_POST["update-progress-btn"])) {
        $progress = $_POST["update-progress"];
        mysqli_query($connectionID, "UPDATE asg_member SET asgMemberProgress = $progress WHERE asgMemberAccountID = $accountID AND assignmentID = $asgid");
    }

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

        <div class="row ">

            <div class="card mt-4 mb-4">
                <div class="card-body">
                    <dl class="row">
                        <h4 class="mt-4 mb-4">Assignment's Detail</h4>
                        <dt class="col-sm-3">Title</dt>
                        <dd class="col-sm-9"><?= $asgdata["assignmentTitle"] ?></dd>

                        <dt class="col-sm-3">Description</dt>
                        <dd class="col-sm-9" style="white-space: pre-line">
                            <samp><?= $asgdata["assignmentDescription"] ?></samp>
                        </dd>

                        <dt class="col-sm-3">Created On</dt>
                        <dd class="col-sm-9">
                            <?= date_format(date_create($asgdata["assignmentCreated"]),"D, d M Y"); ?></dd>

                        <dt class="col-sm-3">Deadline</dt>
                        <dd class="col-sm-9"><?= date_format(date_create($asgdata["assignmentDeadline"]),"D, d M Y"); ?>
                        </dd>

                        <?php $tmpProcessStr = "width: " . CountTotalAssignmentProgress($asgid). "%"; ?>

                        <dt class="col-sm-3">Total Progress</dt>
                        <dd class="col-sm-9">
                            <div class="progress">
                                <div class="progress-bar bg-primary" role="progressbar" style="<?= $tmpProcessStr; ?>"
                                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </dd>

                        <?php if(!$asgdata["assignmentIsGroup"]): ?>
                            <?php if(IsAsgAssignedToID($accountID, $asgid)) :?>
                            <dt class="col-sm-3">Update Progress</dt>
                            <dd class="col-sm-9">
                                <form action="detailassignment.php?groupid=<?=$groupid . "&asgid=" . $asgid?>"
                                    method="post">
                                    <label for="customRange1" class="form-label">Progress</label>
                                    <input type="range" class="form-range" id="customRange1" name="update-progress">
                                    <button type=" submit" class="btn btn-success"
                                        name="update-progress-btn">Update</button>
                                </form>
                            </dd>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if($asgdata["assignmentIsGroup"]): ?>
                        <?php $asg_members = GetAssignmentMembers($asgid, true); ?>

                        <h4 class="mb-4 mt-4">Members Progress</h4>
                        <?php foreach($asg_members as $am): ?>
                        <?php $tmpProcessStr_Member = "width: ". $am["asgMemberProgress"] . "%"; ?>

                        <dt class="col-sm-3 mb-3 mt-3"><?= GetUserFullName($am["asgMemberAccountID"]) ?></dt>
                        <dd class="col-sm-9 mt-3 mb-3">
                            <div class="progress">
                                <div class="progress-bar bg-primary" role="progressbar"
                                    style="<?= $tmpProcessStr_Member; ?>" aria-valuenow="0" aria-valuemin="0"
                                    aria-valuemax="100">
                                </div>
                            </div>
                            <?php if($am["asgMemberAccountID"] == $accountID) : ?>
                            <form action="detailassignment.php?groupid=<?=$groupid . "&asgid=" . $asgid?>"
                                method="post">
                                <label for="customRange1" class="form-label mt-2 mb-2">Progress</label>
                                <input type="range" class="form-range mt-2 mb-2" id="customRange1"
                                    name="update-progress">
                                <button type=" submit" class="btn btn-success mt-2 mb-2 "
                                    name="update-progress-btn">Update</button>
                            </form>
                            <?php endif; ?>
                        </dd>
                        <?php endforeach; ?>



                        <?php endif; ?>


                    </dl>
                </div>
            </div>

        </div>
    </div>

    <!-- End of contents -->

    <svg xmlns=" http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#212529" fill-opacity="1"
            d="M0,128L34.3,133.3C68.6,139,137,149,206,176C274.3,203,343,245,411,234.7C480,224,549,160,617,149.3C685.7,139,754,181,823,186.7C891.4,192,960,160,1029,160C1097.1,160,1166,192,1234,197.3C1302.9,203,1371,181,1406,170.7L1440,160L1440,320L1405.7,320C1371.4,320,1303,320,1234,320C1165.7,320,1097,320,1029,320C960,320,891,320,823,320C754.3,320,686,320,617,320C548.6,320,480,320,411,320C342.9,320,274,320,206,320C137.1,320,69,320,34,320L0,320Z"
            data-darkreader-inline-fill="" style="--darkreader-inline-fill:#007acc;"></path>
    </svg>
    <!-- Footer -->
    <footer class="bg-dark text-white  pb-5">
        <p class="font-weight-bold text-center fs-5">Created by : Tesla Team</p>
    </footer>


    <!-- End of Footer -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</body>

</html>