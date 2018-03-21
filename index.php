<!--
테아밈 분석기

제작: 김경식
최근 업데이트: 15.12.08.
홈페이지: www.kimsbible.com
문의: kungsik@gmail.com


Teamim Analyzer

Created by Kim, Kyoungsik
Recent Update: Dec. 8th. 2015.
Homepage: www.kimsbible.com
Contact: kungsik@gmail.com
hi..
-->

<?
include "header.htm";
include "lib.php";
?>

  <div class="jumbotron">
	<h2><center>Teamim Analyzer (created by kimsbible.com)</center></h2>

<?

if(!$_POST['submit'] && !$_GET['chapter']) {

	echo "<br><h4>Select the verse that you want to analyze.<br><br>
  Chapters and Verses system is based on that of translated version(not Massora system).
  <br><br>Unfortunately, the current version does not still offer the analysis for אמ''ת(Job, Proverbs, Psalm).
  It will be updated soon.</h4><br>";

	include "biblelist.htm";

	echo "</div>";

//  아래 코드는 테아밈이 포함된 구절을 직접 입력 받기 위한 것임. 나중에 보완 예정
//	echo "Input one verse including Teamim<br><br>";

//	echo "<form method=post action=index.php>";
//	echo "<textarea rows=10 cols=40 name=verse></textarea><br><br>";
//	echo "<input type=submit name=submit value=Analyze!></form>";
}

else {

	if(!$_POST['chapter'] && !$_POST['vrs'] && !$_GET['chapter'] && !$_GET['vrs']) { error('You did not put the chapter or verse!'); }

	// change color를 실행했을 경우 get값을 post로 치환해 줌.
	if($_GET['chapter']) { $_POST['bible'] = $_GET['bible']; $_POST['chapter'] = $_GET['chapter']; $_POST['vrs'] = $_GET['vrs']; $_POST['color'] = $_GET['color']; }

	//db 검색을 요청했을 경우
	if(strlen($_POST['chapter']) == 1) { $chp = '00'.$_POST['chapter']; }
	elseif(strlen($_POST['chapter']) == 2) { $chp = '0'.$_POST['chapter']; }
	else { $chp = $_POST['chapter']; }

	if(strlen($_POST['vrs']) == 1) { $vrs = '00'.$_POST['vrs']; }
	elseif(strlen($_POST['vrs']) == 2) { $vrs = '0'.$_POST['vrs']; }
	else { $vrs = $_POST['chapter']; }

	$verse_num = $_POST['bible'].$chp.$vrs;

	include "db.php";

	$que = "select word, connected from bible_original where verseID = $verse_num order by orig_order asc";
	$result=mysqli_query($conn,$que);

	while($data=mysqli_fetch_array($result)) {
		if($data['connected']) { $verse .= $data['word'].'־'; }
		else { $verse .= $data['word'].' '; }
	}

	$verse = trim($verse);

	include "biblelist.php";

	echo "<br><center><font size=5>".$bible_name.' '.$_POST['chapter'].':'.$_POST['vrs']."</font><br><br>";

	echo "<center><font size=5>".$verse."</font></center>";

	$array_verse = explode(" ", $verse);

	$count = count($array_verse);

	include "accent.php";

	// 액센트를 찾고 그에 해당하는 계급을 부여함
	for($i=0; $i<$count; $i++) {
		if($i==$count-1) { $teamim[$i] = 'סילוק'; $rank[$i] = 1;  break; }
		if(strpos($array_verse[$i], $ethnahta)){ $teamim[$i] = 'אתנחתא'; $rank[$i] = 1;  continue; }
		elseif(strpos($array_verse[$i], $zaqef_qaton)){ $teamim[$i] = 'זקף קטן'; $rank[$i] = 2;  continue; }
		elseif(strpos($array_verse[$i], $zaqef_gadol)){ $teamim[$i] = 'זקף גדול'; $rank[$i] = 2;  continue; }
		elseif(strpos($array_verse[$i], $shalshelet)){ $teamim[$i] = 'שלשלת'; $rank[$i] = 2;  continue; }
		elseif(strpos($array_verse[$i], $tipha)){ $teamim[$i] = 'טפחא'; $rank[$i] = 2;  continue; }
		elseif(strpos($array_verse[$i], $segol)){ $teamim[$i] = 'סגול'; $rank[$i] = 2;  continue; }
		elseif(strpos($array_verse[$i], $yetiv)){ $teamim[$i] = 'יתיב'; $rank[$i] = 3; continue; }
		elseif(strpos($array_verse[$i], $pashta)){ $teamim[$i] = 'פשטא'; $rank[$i] = 3; continue; }
		elseif(strpos($array_verse[$i], $tvir)){ $teamim[$i] = 'תביר'; $rank[$i] = 3; continue; }
		elseif(strpos($array_verse[$i], $zarqa)){ $teamim[$i] = 'זרקא'; $rank[$i] = 3; continue; }
		elseif(strpos($array_verse[$i], $revia)){ $teamim[$i] = 'רביע'; $rank[$i] = 3; continue; }
		elseif(strpos($array_verse[$i], $telisha_gedola)){ $teamim[$i] = 'תלישא גדולה'; $rank[$i] = 4; continue; }
		elseif(strpos($array_verse[$i], $qarnei_para)){ $teamim[$i] = 'קרני פרה'; $rank[$i] = 4; continue; }
		elseif(strpos($array_verse[$i], $pazer)){ $teamim[$i] = 'פזר'; $rank[$i] = 4; continue; }
		elseif(strpos($array_verse[$i], $geresh)){ $teamim[$i] = 'גרש'; $rank[$i] = 4; continue; }
		elseif(strpos($array_verse[$i], $gershaim)){ $teamim[$i] = 'גרשיים'; $rank[$i] = 4; continue; }
		elseif(strpos($array_verse[$i], $munah_legarme)){ $teamim[$i] = 'מונח לגרמיה'; $rank[$i] = 4;}
	}

	//연결 악센트에 이전 테아밈의 계급을 적용시킴. 테아밈 이름은 ◀로 표현
	for($i=$count-1; $i>-1; $i--) { if(!$rank[$i]) { $rank[$i] = $rank[$i+1]; $teamim[$i] = '◀'; } }

	//가장 낮은 계급값(높은 수일수록 낮은 계급) 구하기
	$max_rank = max($rank);

	echo "</div></div><div class='col-md-1'></div><div class='col-md-10'><table class='table'>";
	for($n=0; $n<$max_rank; $n++) {

		//계급에 맞는 칸에 단어를 출력함. 계급이 낮을수록 아래칸으로 이동
		echo "<tr>";
		for($i=$count-1; $i>-1; $i--) {
			//해당하는 칸의 수와 계급이 맞으면 단어를 칸에 출력함.
			if($rank[$i]==$n+1) {
				//색 구분 지정을 할 경우에 색을 랜덤으로 지정함.
				if($_POST['color']) {
					//연결 테아밈인 경우 색 변경하지 않음
					if($teamim[$i] == '◀') { }

					//분절 테아밈인 경우 새로운 색을 랜점으로 생성. 색값을 10진수로 변경한 값 추가(색 편차값을 구하기 위해)
					else {
						if(!$c) {
							$rand_color[$c] = random_color();
							$color_dec[$c] = hexdec($rand_color[$c]);
							$color = ' color='.$rand_color[$c];
							$c++;
						}
						//색의 편차값을 크게 주어 되도록 색 구별이 명확하도록 만듬. 색의 편차값을 더 주면 색 구별이 더 명확해지나 로딩이 느릴 수 있음.
						else {
							$color_gap = 99; //while문이 돌도록 임의 숫자를 줌.
							while($color_gap < 2000000) {
								$rand_color[$c] = random_color();
								$color_dec[$c] = hexdec($rand_color[$c]);
								$color_gap = abs($color_dec[$c] - $color_dec[$c-1]);
							}
							$color = ' color='.$rand_color[$c];
							$c++;
						}
					}
				}
				echo "<td align=left><font size=5".$color.">$array_verse[$i]</font></td>";
			}
			else { echo "<td></td>"; }
		}

		//계급명을 제일 오른쪽 열에 출력
		if($n+1 == 1) { echo "<td rowspan=2><font size=5>קיסר</font></td>";}
		elseif($n+1 == 2) { echo "<td rowspan=2><font size=5>מלך</font></td>";}
		elseif($n+1 == 3) { echo "<td rowspan=2><font size=5>משנה</font></td>";}
		elseif($n+1 == 4) { echo "<td rowspan=2><font size=5>שליש</font></td>";}

		echo "</tr>";

		//테아밈 이름 표시, 색 옵션이 있을 경우 단어의 색과 동기화 됨.
		echo "<tr>";
		for($i=$count-1; $i>-1; $i--) {
			if($rank[$i]==$n+1) {
				// 색 옵션이 없을 경우 테아밈 이름은 파랑색으로 표시
				if(!$_POST['color']) { $color = ' color= blue'; }

				// 색 옵션이 있을 경우, 단어의 색과 동기화
				else {
					$color = ' color='.$rand_color[$h];
					if($teamim[$i-1] == '◀') {  }
					else { $h++; }
				}
				echo "<td align=left><font size=5".$color.">$teamim[$i]</font></td>";
				$color = '';
			}
			else { echo "<td></td>"; }
		}
	}

	echo "</table><br><center>";

	//컬러 옵션이 있을 경우 color change 메뉴 생성
	if($_POST['color']) {
		echo "<a class='btn btn-xs btn-primary' href=index.php?bible=".$_POST['bible']."&chapter=".$_POST['chapter']."&vrs=".$_POST['vrs']."&color=1>Change color set</a> ";
		echo "<a class='btn btn-xs btn-danger' href=index.php?bible=".$_POST['bible']."&chapter=".$_POST['chapter']."&vrs=".$_POST['vrs'].">Remove color set</a><br><br>";
	}
	else {
		echo "<a class='btn btn-xs btn-primary' href=index.php?bible=".$_POST['bible']."&chapter=".$_POST['chapter']."&vrs=".$_POST['vrs']."&color=1>Add color set</a><br><br>";
	}


	//앞,뒤 장절 이동
	$next_vrs = $_POST['vrs'] + 1;
	$next_chk = check_bible($_POST['bible'], $_POST['chapter'], $next_vrs);
	//db 에러를 방지하기 위해 성경의 마지막 절이면 아무 작업도 하지 않고 넘어감.
	if($next_vrs == 7 && $_POST['bible'] == 39 && $_POST['chapter'] == 4) {    }
	elseif($next_chk) {
		$next_bible = $_POST['bible'];
		$next_chp = $_POST['chapter'];
	}
	else {
		$next_chp = $_POST['chapter'] + 1;
		$next_chk = check_bible($_POST['bible'], $next_chp, 1);
		if($next_chk) {
			$next_bible = $_POST['bible'];
			$next_vrs = 1;
		}
		else {
			$next_bible = $_POST['bible'] + 1;
			$next_chp = 1;
			$next_vrs = 1;
		}
	}

	$prev_vrs = $_POST['vrs'] - 1;
	$prev_chk = check_bible($_POST['bible'], $_POST['chapter'], $prev_vrs);
	//db 에러를 방지하기 위해 성경의 첫 절이면 아무 작업도 하지 않고 넘어감.
	if($prev_vrs == 0 && $_POST['bible'] == 1 && $_POST['chapter'] == 1) {    }
	elseif($prev_chk) {
		$prev_bible = $_POST['bible'];
		$prev_chp = $_POST['chapter'];
		$prev_vrs = $_POST['vrs'] - 1;
	}
	else {
		$prev_chp = $_POST['chapter'] - 1;
		$prev_chk = check_bible($_POST['bible'], $prev_chp, 1);
		if($prev_chk) {
			$prev_bible = $_POST['bible'];
			$prev_vrs = last_verse($prev_bible, $prev_chp);
		}
		else {
			$prev_bible = $_POST['bible'] - 1;
			$prev_chp = last_chp($prev_bible);
			$prev_vrs = last_verse($prev_bible, $prev_chp);
		}
	}

	if($_POST['color']) { $col_opt = '&color=1'; }

	//성경의 처음이면 prev verse 출력하지 않음.
	if($prev_vrs != 0 || $_POST['bible'] != 1 || $_POST['chapter'] != 1) {
		echo "<a class='btn btn-success' href=index.php?bible=".$prev_bible."&chapter=".$prev_chp."&vrs=".$prev_vrs.$col_opt.">Previous Verse</a> ";
	}

	//성경의 마지막 절이면 next verse 출력 하지 않음.
	if($_POST['vrs'] != 6 || $_POST['bible'] != 39 || $_POST['chapter'] != 4) {
		echo " <a class='btn btn-warning' href=index.php?bible=".$next_bible."&chapter=".$next_chp."&vrs=".$next_vrs.$col_opt.">Next Verse</a>";
	}

	echo "<br><br><a class='btn btn-info' href=index.php>Back to index</center></a><br><br>";

}

?>

</body>
</html>
