<?php
//error_reporting(0);
include("./include/db.php");
session_start();
$output = ''; //already existing in database
$invalidusn = '';
$invalidrecord = ''; //valid usn
$temp = '';
if (isset($_POST["import"])) {
    $count = 0;
    $ct = 0;
    $ct1 = 0;
    $bool = true;
    $boolin=true;
    $takeExplode = explode(".", $_FILES["excel"]["name"]);
    $extension = end($takeExplode); // For getting Extension of selected file
    $allowed_extension = array("xls", "xlsx", "csv"); //allowed extension
    if (in_array($extension, $allowed_extension)) //check selected file extension is present in allowed extension array
    {
        $file = $_FILES["excel"]["tmp_name"]; // getting temporary source of excel file
        include("./PHPExcel/Classes/PHPExcel/IOFactory.php"); // Add PHPExcel Library in this code
        $objPHPExcel = PHPExcel_IOFactory::load($file); // create object of PHPExcel library by using load() method and in load method define path of selected file
//      starting of table ended in studentToDb.php page
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
                            <th>Salary</th>
                        </tr>
                        </thead>";
        foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
            $highestRow = $worksheet->getHighestRow();
            for ($row = 2; $row <= $highestRow; $row++) {
//                $output .= "<tr>";
                $usn = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(0, $row)->getValue());
                $name = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(1, $row)->getValue());
                $email = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(2, $row)->getValue());
                $contact = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(3, $row)->getValue());
                $placed_year = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(4, $row)->getValue());
                $offer1 = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(5, $row)->getValue());
                $salary1 = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(6, $row)->getValue());
                $offer2 = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(7, $row)->getValue());
                $salary2 = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(8, $row)->getValue());
                $offer3 = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(9, $row)->getValue());
                $salary3 = mysqli_real_escape_string($con, $worksheet->getCellByColumnAndRow(10, $row)->getValue());

                if(!(preg_match("/@/",$email)) || !(preg_match("/\./",$email)) || !(preg_match("/com/",$email)) )
                {
                    $boolin=false;
                    echo "<script> alert('Invalid $email email-id') </script>";
                }
//                if()
//                {
//                    $boolin=false;
//                    echo "<script>alert('Invalid $email email-id')</script>";
//                }
//                if()
//                {
//                    $boolin=false;
//                    echo "<script>alert('Invalid $email email-id')</script>";
//                }

                else if(!is_numeric($contact) || !preg_match('/^\d{10}$/', $contact))
                {
                    $boolin=false;
                    echo "<script>alert('Invalid contact $contact')</script>";
                }
//                if ()
//                {
//                    $boolin=false;
//                    echo "<script>alert('Invalid contact $contact')</script>";
//                }

                $sql2 = "select usn from student";
                $data = mysqli_query($con, $sql2);
                $total = 0;
                $total = mysqli_num_rows($data);
                if ($total != 0) {
                    $rows = 0;
                    while ($rows = mysqli_fetch_assoc($data)) {
                        if ($rows['usn'] == $usn) {
                            $count = $count + 1;
                            $temp = $usn;

                            $output .= '<tr><td>' . $usn . '</td>';
                            $output .= '<td>' . $name . '</td>';
                            $output .= '<td>' . $email . '</td>';
                            $output .= '<td>' . $contact . '</td>';
                            $output .= '<td>' . $placed_year . '</td>';
                            $output .= '<td>' . $offer1 . '</td>';
                            $output .= '<td>' . $salary1 . '</td>';
                            $output .= '<td>' . $offer2 . '</td>';
                            $output .= '<td>' . $salary2 . '</td>';
                            $output .= '<td>' . $offer3 . '</td>';
                            $output .= '<td>' . $salary3 . '</td>';
                            $output .= '</tr>';
                        }
                    }
                }
                   if (preg_match("/^[1][A-Z][A-Z][0-9][0-9][A-Z][A-Z][A-Z][0-9][0-9]$/", $usn) && $boolin) {
                    $query = "INSERT INTO student (usn, name, email, contact, placed_year, offer1, salary1, offer2, salary2, offer3, salary3) VALUES
                            ('" . $usn . "', '" . $name . "','" . $email . "','" . $contact . "', '" . $placed_year . "', '" . $offer1 . "', '" . $salary1 . "', '" . $offer2 . "','" . $salary2 . "','" . $offer3 . "','" . $salary3 . "')";
                    $bool = mysqli_query($con, $query);
                } else {

                    $ct = $ct + 1; //counting invalid usn
//                    if($temp!=$usn) {
//                        $count = $count + 1;
//                    }
                    $invalidusn .= '<tr><td>' . $usn . '</td>';
                    $invalidusn .= '<td>' . $name . '</td>';
                    $invalidusn .= '<td>' . $email . '</td>';
                    $invalidusn .= '<td>' . $contact . '</td>';
                    $invalidusn .= '<td>' . $placed_year . '</td>';
                    $invalidusn .= '<td>' . $offer1 . '</td>';
                    $invalidusn .= '<td>' . $salary1 . '</td>';
                    $invalidusn .= '<td>' . $offer2 . '</td>';
                    $invalidusn .= '<td>' . $salary2 . '</td>';
                    $invalidusn .= '<td>' . $offer3 . '</td>';
                    $invalidusn .= '<td>' . $salary3 . '</td>';
                    $invalidusn .= '</tr>';
                }
                if (!$bool) {
//                    $ct1 = $ct1 + 1;  //valid usn but invalid  or null fields
                    if ($temp != $usn) {
                        $count = $count + 1;
                    }
                    $invalidrecord .= '<tr><td>' . $usn . '</td></tr>';
                }
            }
            if ($count > 0) {
                echo " <script> alert('$count records are not inserted!, in which $ct usn/email are invalid!');
                </script>";
            } else {
                echo " <script> alert('Records are inserted!');
                </script>";
            }
        }
//        $output .= '</table>';
//        $invalidrecord .= '</table>';
//        $invalidusn .= '</table>';
    } else {
        $output = '<label class="text-danger">Invalid File</label>'; //if non excel file then
    }
}
?>
<!--html file upload to student database -->
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title> Upload | Student</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <!-- Page level plugin CSS-->
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">

</head>

<body id="page-top">

<!--header start-->
<?php
include("./header.php");
?>

<div id="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="dashboard.php">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Upload Student Details</li>
        </ol>
        <!--     content add here -->
        <div class="container-fluid">
            <h3 align="center">Upload to Student database</h3><br/>
            <form method="post" enctype="multipart/form-data">
                <label>Select Excel File</label>
                <input type="file" name="excel"/>
                <br/>
                <input type="submit" name="import" class="btn btn-info" value="Import"/>
            </form>
            <br/>
            <hr>
            <br/>
        </div>
        <!-- DataTables Example -->
        <!--imported from uploadStudent.php -->
        <tbody>
        <?php
        //        printing the record which is already in the database
        echo $output;
        //    printing the invalid usn records
        echo $invalidusn;
        //        echo $invalidrecord;
        ?>
        </tbody>
        </table>

    </div>
</div>
</div>
<!--ending of table started in uploadStudent.php -->
</div>

<!--end  /.container-fluid -->
<!---------------------------------------------------------------------------------------------------------->
<!--    Sticky Footer -->
<!--    <footer class="sticky-footer">-->
<!--        <div class="container my-auto">-->
<!--            <div class="copyright text-center my-auto">-->
<!--                <span>Copyright © crackTheCode 2019</span>-->
<!--            </div>-->
<!--        </div>-->
<!--    </footer>-->
</div>
<!-- /.content-wrapper -->
</div>
<!-- /#wrapper -->
<!-------------------------------------------------------------------------------------------------------->
<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Page level plugin JavaScript-->
<script src="vendor/chart.js/Chart.min.js"></script>
<script src="vendor/datatables/jquery.dataTables.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin.min.js"></script>

<!-- Demo scripts for this page-->
<script src="js/demo/datatables-demo.js"></script>
<script src="js/demo/chart-area-demo.js"></script>


</body>

</html>

