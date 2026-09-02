<?php
$dst = $_POST["dst"];
//echo "dst:".$dst;
if(empty($dst)) {
    echo "IMGCP FAIL\n";
    exit();
}
//$dst = "/mnt/link/disk1";

//echo "Image copy start\n";
$fileName = "odd".date("ymdGis").".iso";

//echo "nas-storage odd_backup image ".$dst."/".$fileName."\n";
exec("sudo nas-storage odd_backup image ".$dst."/".$fileName, $op, $result);
//echo "op=".$op[0]."1=".$op[1];
// print_r($op);

//echo "result: ".$result;
echo $fileName;
if($result == 0)
    echo " OK\n";
else
    echo " FAIL\n";

ob_flush();
flush();
?>

