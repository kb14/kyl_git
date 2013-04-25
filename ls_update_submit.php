<!DOCTYPE html>

<html>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<?php
	require_once __DIR__ . '/db_config.php';
		
		// open a connection to the database server
		$connection = pg_connect ("host=$host port=$port dbname=$db user=$user
		password=$password");
		if (!$connection)
		{
		die("Could not open connection to database server");
		}
		
		if(isset($_GET['candidate']) && isset($_GET['party']) && isset($_GET['constituency']) && isset($_GET['year']) ){
			$cand_or= $_GET['cand_or'];
			$cons_or= $_GET['cons_or'];
			$party_or= $_GET['party_or'];
			$candidate = $_GET['candidate'];
			$cons = $_GET['constituency'];
			$party=$_GET['party'];
			$cc=$_GET['criminal_cases'];
			$cdc=$_GET['cdc'];
			$cda=$_GET['cda'];
			$sid=$_GET['sid'];
			$oid=$_GET['oid'];
			$edu=$_GET['education'];
			$ma=$_GET['ma'];
			$ima=$_GET['ima'];
			$ta=$_GET['ta'];
			$lia=$_GET['liabilities'];
			$year=$_GET['year'];
		}
		
		$query = "UPDATE all_ls SET candidate='$candidate',constituency='$cons',party='$party',criminal_cases='$cc',cases_details_convicted='$cdc',
		cases_details_accused='$cda',serious_ipc_detail='$sid',other_ipc_detail='$oid',education='$edu',movable_assets='$ma',immovable_assets='$ima', 
		total_assets='$ta',liabilities='$lia',year='$year' WHERE  candidate='$cand_or' and constituency='$cons_or' and party='$party_or' and year='$year'"; 
		$result = pg_query($connection, $query) or die("Error in query:
		$query. " .
		pg_last_error($connection));
		
?>
<head>
	<title>USA submit</title>
	<link rel="stylesheet" href="bootstrap.css">
	<style type='text/css'>
	
	
	  body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
	  .jumbotron {
        margin: 80px 0;
        text-align: center;
     }
    .jumbotron .lead {
        font-size: 24px;
        line-height: 1.25;
    }
	</style>  
</head>

<body>
	<div class='container-fluid'>
	<?php
		if($result){
	?>	
		<!-- Jumbotron -->
      <div class="jumbotron">
        <p class="lead">Data Updated Successfully</p>
      </div>
	  <div class="span4 offset6">
	  <a href="./loksabha_crime.php?constituency=<?php echo strtolower($cons) ?>" class="btn btn-large btn-block btn-success ">Go Back</a>	
	  </div>
	  <?php
	  }else{
	 ?> 
	 
	 <?php
	 }
	 ?>
	</div>
</body>		

</html>		