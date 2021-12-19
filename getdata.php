
<?php
header('Content-Type: application/json');
header('Acess-Control-Allow-Origin: *');
include "dbconfig.php";
// function to get number of table in data base
function gettable($conn){
  $sql = "SHOW TABLES";
$result = mysqli_query($conn,$sql);
while($cRow = mysqli_fetch_array($result))
{
  $tableList[] = $cRow[0];
}
return count($tableList);
}

//function to get all column of table 
function get_column_names($conn,$table) {
  $sql = "DESC $table; ";
  $result = mysqli_query($conn, $sql);
  $rows = array();
  // print_r($result);
  // die();
  while($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row['Field'];
  }
  return $rows;
}

//function to search data in given table
function get_data($conn,$table,$data) {
$col_names =  get_column_names($conn,$table);
$length=sizeof($col_names);
$str="";
for($i=0;$i<$length;$i++){
  if($i!=$length-1)
  $str="$str$col_names[$i] =  '{$data}'||";
  else
  $str="$str$col_names[$i] = '{$data}'";
}
$sql = "SELECT * FROM $table WHERE $str;";
return( $conn->query($sql));
}

//recieving json data from api
$input = json_decode(file_get_contents("php://input"),true);
$check = false;
$data = $input['data'];
for ($i = 1; $i <= gettable($conn)  ; $i++){
  $result=get_data($conn,"data$i",$data);
  if ($result->num_rows > 0) {
    $output = $result->fetch_assoc();
       echo json_encode(array("status" => 1,"data"=>$output));
    $check=true;
    // echo ("breat at $i and data= $data");
    break;
  }
}
if($check==false)
echo json_encode(array("message" => "n000 data found"));
?>
