<?php  
include("dbconfig.php"); 
function gettable($conn){
    $sql = "SHOW TABLES";
$result = mysqli_query($conn,$sql);
while($cRow = mysqli_fetch_array($result))
  {
    $tableList[] = $cRow[0];
  }
  return($tableList);
}
$tabledata=gettable($conn);
// print_r($tabledata);
$length=count($tabledata)+1;
$sql="create table data$length( id int , name varchar(24));";
$result=mysqli_query($conn,$sql);
if($result)
echo "success";
else
echo "failed";
die();
?>