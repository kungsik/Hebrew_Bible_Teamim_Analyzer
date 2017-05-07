<?

//Teamim Library

//Error Message
function error($message) { 
	echo"<script language=javascript>
		alert('$message');
		history.go(-1);
		</script>";
	exit;
}

//테아밈에 랜덤 색상 부여
function random_color(){
    mt_srand((double)microtime()*1000000);
    $c = '';
    while(strlen($c)<6){
        $c .= sprintf("%02X", mt_rand(0, 255));
    }
    return $c;
}


//해당 성서 장/절의 단어 갯수 구함 
function check_bible($bible, $chp, $vrs) {
	include "db.php";
	$que = "select count(*) from bible_original where book = $bible and chapter = $chp and verse = $vrs limit 1";
	$result=mysqli_query($conn,$que);
	$data=mysqli_fetch_array($result);
	return $data[0];
}

//해상 성서의 가장 마지막 절 구하기 
function last_verse($bible, $chp) {
	include "db.php";
	$que = "select verse from bible_original where book = $bible and chapter = $chp order by verse desc";
	$result=mysqli_query($conn,$que);
	$data=mysqli_fetch_array($result);
	return $data[0];
}

//해당 성서의 가장 마지막 장 구하기
function last_chp($bible) {
	include "db.php";
	$que = "select chapter from bible_original where book = $bible order by chapter desc";
	$result=mysqli_query($conn,$que);
	$data=mysqli_fetch_array($result);
	return $data[0];
}


?>
