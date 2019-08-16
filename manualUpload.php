<?php
session_start();
include("include/db.php");
//    session_start();
?>

    <!DOCTYPE html>
    <html lang="en" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html"
          xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/checkbox.css">
    <link rel="stylesheet" href="css/button.css">
    <script src="./jquery-3.4.0.slim.min.js"></script>
    <script src="./bootstrap.js"></script>
    <script src="./popper.min.js"></script>
    <script src="./tooltip.min.js"></script>

    <title>Add Expense</title>
</head>
<body>
<!-- Page Content -->
<section class="py-5">
    <div class="container-fluid tcontainer">
        <form action="manualUpload.php" class="form-signin" method="post">
            <div class="row">
                <div class="col-md-4 col-lg-4">
                    <div class="col-sm-8 col-md-10 col-lg-12 mx-auto">
                        <div class="card card-signin my-5">
                            <div class="card-body">
                               <u><h5 class="card-title text-center"><u>Add Student Details</h5></u>

                                <div class="form-label-group">
                                    <input type="text" id="usn"  name="usn" class="form-control" placeholder="USN" readonly="readonly"  >
                                    <label for="usn">USN</label>
                                </div>
                                <div class="form-label-group">
                                    <input type="text" id="name"  name="name" class="form-control" placeholder="Name" required autofocus>
                                    <label for="name">Name</label>
                                </div>

                                <div class="form-label-group">
                                    <input type="email" id="email" name="email" class="form-control" placeholder="Email" required autofocus>
                                    <label for="email">Email</label>
                                </div>

                                <div class="form-label-group">
                                    <input type="text" id="contact" name="contact" class="form-control" placeholder="Contact" required autofocus>
                                    <label for="contact">Contact</label>
                                </div>

                                <div class="form-label-group">
                                    <input type="text" id="year"  name="year" class="" placeholder="year">
                                    <label for="year">Placed Year</label>
                                </div>

                                <div class="form-label-group">
                                    <input type="text" id="offer1"  name="offer1" class="form-control" placeholder="offer1">
                                    <label for="offer1">Offer First</label>
                                </div>

                                <div class="form-label-group">
                                    <input type="text" id="salary1"  name="salary1" class="form-control" placeholder="salary1">
                                    <label for="salary1">Salary First</label>
                                </div>

                                <div class="form-label-group">
                                    <input type="text" id="offer2"  name="offer2" class="form-control" placeholder="offer2">
                                    <label for="offer2">Offer Second</label>
                                </div>

                                <div class="form-label-group">
                                    <input type="text" id="salary2"  name="salary2" class="form-control" placeholder="salary2">
                                    <label for="salary2">Salary Second</label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-lg btn-primary btn-block text-uppercase" name="submit" type="submit">Upload</button>
        </form>
    </div>
</section>
<!-- /.row -->
<!-- /.container -->
<!-- database connectivity -->
<?php
include("./include/db.php");

//check karna
session_start();

//for validation taking the array
$output = ''; //already existing in database
$invalidusn = '';
$invalidrecord = ''; //valid usn
$temp = '';

//checking the form fields
if(isset($_POST['submit'])){
    $count = 0;
    $ct = 0;
    $ct1 = 0;
    $bool = true;
    $boolin=true;

//    Content to check with db copied from uploadStudent

    $output .= "<div class=\"card mb-3\">";
    $output .= "<div class=\"card-header\"> <i class=\"fas fa-table\"></i>
                Error's in the uploaded excel data
            </div><div class=\"card-body\">
                <div class=\"table-responsive\">";
    $invalidusn .= "<div class=\"table-responsive\">";
    $invalidrecord .= "<div class=\"table-responsive\">";
    $output .= "<table class=\"table table-bordered\" id=\"dataTable\" width=\"100%\" cellspacing=\"0\">
                        <thead>
                        <tr>
                            <th>USN</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Placed Year</th>
                            <th>Offer1</th>
                            <th>Salary</th>
                            <th>Offer2</th>
                            <th>Salary</th>
                            <th>Offer3</th>
                            <th>Salary3</th>
                        </tr>
                        </thead>";
            $output .= "<tr>";

            $usn = $_POST['usn'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $contact = $_POST['contact'];
            $year = $_POST['year'];
            $offer1 = $_POST['offer1'];
            $salary1 = $_POST['salary1'];
            $offer2 = $_POST['offer2'];
            $salary2 = $_POST['salary2'];

            if(!(preg_match("/@/",$email)) || !(preg_match("/\./",$email)) || !(preg_match("/com/",$email)) )
            {
                $boolin=false;
                echo "<script> alert('Invalid $email email-id') </script>";
            }

            else if(!is_numeric($contact) || !preg_match('/^\d{10}$/', $contact))
            {
                $boolin=false;
                echo "<script>alert('Invalid contact $contact')</script>";
            }

            $sql2 = "select usn from student";
            $data = mysqli_query($con, $sql2);
            $total = 0;
            $total = mysqli_num_rows($data);
            if ($total != 0) {
                $rows = 0;
                while ($rows = mysqli_fetch_assoc($data)) {
                    if ($rows['usn'] == $usn) {
                        $temp = $usn;

                        $output .= '<tr><td>' . $usn . '</td>';
                        $output .= '<td>' . $name . '</td>';
                        $output .= '<td>' . $email . '</td>';
                        $output .= '<td>' . $contact . '</td>';
                        $output .= '<td>' . $year . '</td>';
                        $output .= '<td>' . $offer1 . '</td>';
                        $output .= '<td>' . $salary1 . '</td>';
                        $output .= '<td>' . $offer2 . '</td>';
                        $output .= '<td>' . $salary2 . '</td>';
//                        $output .= '<td>' . $offer3 . '</td>';
//                        $output .= '<td>' . $salary3 . '</td>';
                        $output .= '</tr>';
                    }
                }
            }
            if (preg_match("/^[1][A-Z][A-Z][0-9][0-9][A-Z][A-Z][A-Z][0-9][0-9]$/", $usn) && $boolin) {
                $query = "INSERT INTO student (usn, name, email, contact, placed_year, offer1, salary1, offer2, salary2) VALUES
                            ('" . $usn . "', '" . $name . "','" . $email . "','" . $contact . "', '" . $year . "', '" . $offer1 . "', '" . $salary1 . "', '" . $offer2 . "','" . $salary2 . "')";
                $bool = mysqli_query($con, $query);
            } else {

                $invalidusn .= '<tr><td>' . $usn . '</td>';
                $invalidusn .= '<td>' . $name . '</td>';
                $invalidusn .= '<td>' . $email . '</td>';
                $invalidusn .= '<td>' . $contact . '</td>';
                $invalidusn .= '<td>' . $year . '</td>';
                $invalidusn .= '<td>' . $offer1 . '</td>';
                $invalidusn .= '<td>' . $salary1 . '</td>';
                $invalidusn .= '<td>' . $offer2 . '</td>';
                $invalidusn .= '<td>' . $salary2 . '</td>';
//                $invalidusn .= '<td>' . $offer3 . '</td>';
//                $invalidusn .= '<td>' . $salary3 . '</td>';
                $invalidusn .= '</tr>';
            }
            if (!$bool) {
                echo "<script>alert('Record not inserted');</script>";
                $invalidrecord .= '<tr><td>' . $usn . '</td></tr>';
            }

//        $output .= '</table>';
//        $invalidrecord .= '</table>';
//        $invalidusn .= '</table>';
          else
              {
                  echo "<script type='text/javascript'> alert('Records inserted successfully!');</script>";
              }
    }
?>

</body>
</html>
