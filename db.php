<?
$conn = mysqli_connect ("localhost", "root", "apmsetup") or die ("failed.");
$mysql = mysqli_select_db($conn,"bible_parsing");

$que1 = "set names utf8";
mysqli_query($conn,$que1);

?>
