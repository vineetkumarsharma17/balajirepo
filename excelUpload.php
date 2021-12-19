<?php  
include("dbconfig.php"); 
$uploaddir = 'uploads/';
$uploadfile = $uploaddir . basename($_FILES['file']['name']);
function gettable($conn){
  $sql = "SHOW TABLES";
$result = mysqli_query($conn,$sql);
if(!mysqli_num_rows($result))
return 0;
while($cRow = mysqli_fetch_array($result))
{
  $tableList[] = $cRow[0];
}
return count($tableList);
}
$title = $_POST["title"];
$sql[] = array();
$val=gettable($conn)+1;
if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
  //  $delete= mysqli_query($conn,"DROP TABLE data$val");
$handle = fopen($uploadfile, "r");
$header = fgetcsv($handle,1000,",");
if($header){
    $header_sql = array();
    foreach($header as $h){
        $header_sql[] = '`'.$h.'` VARCHAR(255)';
    }
    $sql[] = 'CREATE TABLE data'.$val.' ('.implode(',',$header_sql).')';
    while($data = fgetcsv($handle,1000,",")){   
        $sql[] = "INSERT INTO data$val VALUES ('".implode("','",$data)."')";
    }
     $sql[] = "ALTER TABLE data$val ADD data$val VARCHAR(255)";
}        
foreach($sql as $s){
  if($s!=null)
    mysqli_query($conn,$s);
}

echo '<script>alert("Successfully uploaded")</script>';

}else
echo '<script>alert("Not uploaded")</script>';
echo '<script>window.location.replace("http://localhost/balajirepo/")</script>'
?>