<?php 
   // connecting to the database
   $servername="localhost";
   $username="root";
   $password="123456";
   $database="shubham";

   // create a connection
   $conn=mysqli_connect($servername,$username,$password,$database);

   // die if connection is not found
   if (!$conn) {
       die("connection was not found due to this eror :".mysqli_connect_error());
   }else {
       echo "connection was successful<br>";
   }

    // Usase of clause to update table data
//    $sql="UPDATE `phptrip` SET `sno` = '1' WHERE `phptrip`.`sno` = 3";
   $sql2="UPDATE `phptrip` SET `name` = 'Shubham1' WHERE `phptrip`.`sno` = 7;";
   $result=mysqli_query($conn,$sql2);
   
    // to print the no of affected rows we use --->
    $aff=mysqli_affected_rows($conn);
    echo "No of affected rows : $aff<br>";

    // To print the updated record using clause where.
   $no=0;       // initialize index no.
   if (!$result) {
    echo "We are unable to update .";
   }
   else {

    $sql3="SELECT * FROM `phptrip` WHERE `dest`='UP'";
    $result2=mysqli_query($conn,$sql3);

    $num=mysqli_num_rows($result2);
    echo $num;
    echo " record found in the database <br>";

        while ($row = mysqli_fetch_assoc($result2)) {
        // echo var_dump($row);
        echo $no.". Hello ".$row['name']." welcome to ".$row['dest'];
        echo "<br>";
        $no++;  // incrementing index no.
    }
   }
?>