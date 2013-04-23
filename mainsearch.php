<?php
	
	// PHP5 Implementation - uses MySQLi.
	// mysqli('localhost', 'yourUsername', 'yourPassword', 'yourDatabase');
	$db = pg_connect('host=localhost port=5433 dbname=project_kyl user=postgres password=1h@veacardreader');
	
	if(!$db) {
		// Show error if we cannot connect.
		echo 'ERROR: Could not connect to the database.';
	} 
		// Is there a posted query string?
		
			$queryString = $_POST['queryString'];
			
			
			$query = "SELECT * FROM all_ls where candidate ilike '$queryString%' limit 7";
			$q2 = "SELECT * FROM usexecutives where firstname ilike '$queryString%' or lastname ilike '$queryString%' limit 7";

			$result = pg_query($db,$query);
			$r2 = pg_query($db,$q2);
			if (!$result && !$r2) {
				echo "s0mething";
			}


while( $DataArr = pg_fetch_row($result) ){
// Printing results in HTML
	echo "<li><a tabindex=\"-1\" href=\"http://localhost/kyl/india_candidate.php?name=".$DataArr[0]."&cons=".$DataArr[1]."&party=".$DataArr[2]."&year=".$DataArr[14]."\">".ucwords(strtolower($DataArr[0]))."</a></li>";
	
}
while( $da2 = pg_fetch_row($r2)){
	echo "<li><a tabindex=\"-1\" href= \"http://localhost/kyl/uspresidents_profile.php?term1start=".$da2[5]."\">".$da2[0]." ".$da2[1]."</a></li>";
}

// Free resultset
	pg_free_result($result);
	pg_free_result($r2);
	// Closing connection
	pg_close($db);
?>