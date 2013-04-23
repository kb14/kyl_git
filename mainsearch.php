<?php
	
	$db = pg_connect('host=localhost port=5433 dbname=project_kyl user=postgres password=1h@veacardreader');
	
	if(!$db) {
		// Show error if we cannot connect.
		echo 'ERROR: Could not connect to the database.';
	} 
		// Is there a posted query string?
		
			$queryString = $_POST['queryString'];
			
			
			$query = "SELECT * FROM all_ls where candidate ilike '$queryString%' limit 7";
			$q2 = "SELECT * FROM usexecutives where firstname ilike '$queryString%' or lastname ilike '$queryString%' limit 7";
			$q3 = "SELECT * FROM all_assembly where candidate ilike '$queryString%' limit 7";
			$q4 = "SELECT * FROM win_ls where constituency ilike '$queryString%' limit 7";
			
			$result = pg_query($db,$query);
			$r2 = pg_query($db,$q2);
			$r3 = pg_query($db,$q3);
			$r4 = pg_query($db,$q4);
			
			if (!$result && !$r2) {
				echo "s0mething";
			}


while( $DataArr = pg_fetch_row($result) ){
	$cand1 = "nothing";
	$tq1 = "SELECT candidate from win_ls where constituency='$DataArr[1]' and year= '$DataArr[14]'";
	$tr1 =  pg_query($db,$tq1);
	$rows1 = pg_num_rows($tr1);
	if($rows1 > 0){
		$row1 = pg_fetch_object($tr1, 0);
		$cand1 = $row1->candidate;
	}
	echo "<li><a tabindex=\"-1\" href=\"http://localhost/kyl/lscandidate_profile.php?candidate=".$DataArr[0]."&constituency=".$DataArr[1]."&winner=".$cand1."&year=".$DataArr[14]."\">".ucwords(strtolower($DataArr[0]))."</a></li>";
	
}

while( $da3 = pg_fetch_row($r3) ){
	
	$cand2 = "nothing";
	$tq2 = "SELECT candidate from win_assembly where constituency='$da3[1]' and year= '$da3[16]' and state = '$da3[17]'";
	$tr2 =  pg_query($db,$tq2);
	$rows2 = pg_num_rows($tr2);
	if($rows2 > 0){
		$row2 = pg_fetch_object($tr2, 0);
		$cand2 = $row2->candidate;
	}
	echo "<li><a tabindex=\"-1\" href=\"http://localhost/kyl/lacandidate_profile.php?candidate=".$da3[0]."&constituency=".$da3[1]."&winner=".$cand2."&year=".$da3[16]."&state=".$da3[17]."\">".ucwords(strtolower($da3[0]))."</a></li>";
}

while( $da2 = pg_fetch_row($r2)){
	echo "<li><a tabindex=\"-1\" href= \"http://localhost/kyl/uspresidents_profile.php?term1start=".$da2[5]."\">".$da2[0]." ".$da2[1]."</a></li>";
}

while( $da4 = pg_fetch_row($r4)){
	echo "<li><a tabindex=\"-1\" href= \"http://localhost/kyl/lselection_region.php?year=".$da4[3]."&constituency=".$da4[1]."&winner=".$da4[0]."\">".$da4[1]." (".$da4[3].")"."</a></li>";
}

// Free resultset
	pg_free_result($result);
	pg_free_result($r2);
	pg_free_result($r3);
	pg_free_result($r4);
	// Closing connection
	pg_close($db);
?>