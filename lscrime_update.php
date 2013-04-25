<!DOCTYPE html>

<html>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<?php
	if(isset($_GET['candidate']) && isset($_GET['party']) && isset($_GET['constituency']) && isset($_GET['year'])){
		$candidate = $_GET['candidate'];
		$party = $_GET['party'];
		$constituency = $_GET['constituency'];
		$year = $_GET['year'];
		}
		
		require_once __DIR__ . '/db_config.php';
	// open a connection to the database server
	$connection = pg_connect ("host=$host port=$port dbname=$db user=$user
	password=$password");
	if (!$connection)
	{
	die("Could not open connection to database server");
	}
	
	// generate and execute a query
			$query = "SELECT * FROM all_ls WHERE candidate='$candidate' AND party='$party' AND constituency='$constituency' AND year='$year'"; 
			$result = pg_query($connection, $query) or die("Error in query:
			$query. " .
			pg_last_error($connection));
			
			$row = pg_fetch_object($result, 0);

?>
<head>

    <title>Lok Sabha Update</title>
	<link rel="stylesheet" href="bootstrap.css">
	<script type="text/javascript" src="http://localhost/kyl/jquery.js"></script>
	<script type="text/javascript">
	function lookup(inputString) {
		if(inputString.length == 0) {
			// Hide the suggestion box.
			$('#suggestions').hide();
		} else {
			$.post("mainsearch.php", {queryString: ""+inputString+""}, function(data){
				if(data.length >0) {
					$('#suggestions').show();
					$('#autoSuggestionsList').html(data);
				}
			});
		}
	} // lookup
	
	function fill(thisValue) {
		$('#inputString').val(thisValue);
		setTimeout("$('#suggestions').hide();", 200);
	}
	</script>	
	
	<style type='text/css'>
	
	body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
	
	  .suggestionsBox {
		position: absolute;
		left: 5px;
		top: 25px;
		margin: 10px 0px 0px 0px;
		width: 225px;
		background-color: #ffffff;
		-moz-border-radius: 6px;
		-webkit-border-radius: 6px;
		  border-radius: 6px;
		-webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
		-moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
          box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
		-webkit-background-clip: padding-box;
		-moz-background-clip: padding;
          background-clip: padding-box;
		border: 1px solid #ccc;	
		color: transparent;
	}
	.sugglist a{
		display: block;
		padding: 3px 0px;
	}
	.sugglist a:hover{
	  color: #ffffff;
	  text-decoration: none;
	  background-color: #0081c2;
	  background-image: -moz-linear-gradient(top, #0088cc, #0077b3);
	  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#0088cc), to(#0077b3));
	  background-image: -webkit-linear-gradient(top, #0088cc, #0077b3);
	  background-image: -o-linear-gradient(top, #0088cc, #0077b3);
	  background-image: linear-gradient(to bottom, #0088cc, #0077b3);
	  background-repeat: repeat-x;
	  outline: 0;
	  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff0088cc', endColorstr='#ff0077b3', GradientType=0);

	}
	
	</style>  
</head>

<body>
	<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="./index.html">kyl</a>
          <div class="nav-collapse collapse">
            <ul class="nav">
              <li><a href="./index.html">Home</a></li>
              <li><a href="./twitter_search.php">Twitter Search</a></li>
              <li><a href="./india_elections_crime.html">Crime Data</a></li>
			  <li><a href="./admin_signin.php">Admin Panel</a></li>
            </ul>
			<form class="navbar-search pull-right">  
			<input type="text" class="search-query" placeholder="Search" onkeyup="lookup(this.value);" onblur="fill();"> 
			<div class="suggestionsBox" id="suggestions" style="display: none;">

				<div class="sugglist" id="autoSuggestionsList">
				</div>
			</div>	
			</form>  
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
	<div class='container-fluid'>
		<div class="row-fluid">
		<div class="span5 offset3">
			<form class="form-horizontal" method="get" action="/kyl/ls_update_submit.php">
			  <div class="control-group">
				<label class="control-label" for="candidate">Name</label>
				<div class="controls">
				  <input class="span10" type="text" id="candidate" name="candidate" value="<?php echo ucwords(strtolower($candidate)) ?>">
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="constituency">Constituency</label>
				<div class="controls">
				  <input class="span10" type="text" id="constituency" name="constituency" value="<?php echo ucwords(strtolower($constituency)) ?>" >
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="party">Party</label>
				<div class="controls">
				  <input class="span10" type="text" id="party" name="party" value="<?php echo $party ?>">
				</div>
			  </div>
			  <div class="control-group">
			  <label for="criminal_cases" class="control-label">Criminal Cases</label>  
			  <div class="controls">
				<input type="number" name="criminal_cases" id="criminal_cases" min="0" step="1" value="<?php echo $row->criminal_cases ?>">
			  </div>	 
			  </div>
			  <div class="control-group">
				<label class="control-label" for="cdc">Convicted Case Details</label>
				<div class="controls">
				  <input class="span10" type="text" id="cdc" name="cdc" value="<?php echo $row->cases_details_convicted ?>">
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="cda">Accused Case Details</label>
				<div class="controls">
				  <textarea class="span10" type="text" rows="3" id="cda" name="cda" ><?php echo $row->cases_details_accused ?></textarea>
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="sid">Serious IPC Details</label>
				<div class="controls">
				  <textarea class="span10" type="text" rows="4" id="sid" name="sid" ><?php echo $row->serious_ipc_detail ?></textarea>
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="oid">Other IPC Details</label>
				<div class="controls">
				  <textarea class="span10" type="text" rows="4" id="oid" name="oid" ><?php echo $row->other_ipc_detail ?></textarea>
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="education">Education</label>
				<div class="controls">
				  <input class="span10" type="text" id="education" name="education" value="<?php echo $row->education ?>">
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="ma">Movable Assets</label>
				<div class="controls">
				  <input class="span10" type="text" id="ma" name="ma" value="<?php echo $row->movable_assets ?>">
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="ima">Immovable Assets</label>
				<div class="controls">
				  <input class="span10" type="text" id="ima" name="ima" value="<?php echo $row->immovable_assets ?>">
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="ta">Total Assets</label>
				<div class="controls">
				  <input class="span10" type="text" id="ta" name="ta" value="<?php echo $row->total_assets ?>">
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="liabilities">Liabilities</label>
				<div class="controls">
				  <input class="span10" type="text" id="liabilities" name="liabilities" value="<?php echo $row->liabilities ?>">
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="liabilities">Liabilities</label>
				<div class="controls">
				  <input class="span10" type="text" id="liabilities" name="liabilities" value="<?php echo $row->liabilities ?>">
				</div>
			  </div>
			  <div class="control-group">
				<label class="control-label" for="year">Year</label>
				<div class="controls">
				  <input class="span10" type="text" id="year" name="year" value="<?php echo $year ?>" disabled>
				</div>
			  </div>
			  <input type="hidden" name="year" value="<?php echo $year ?>" />
			  <input type="hidden" name="cand_or" value="<?php echo $candidate ?>" />
			  <input type="hidden" name="cons_or" value="<?php echo $constituency ?>" />
			  <input type="hidden" name="party_or" value="<?php echo $party ?>" />
				<div class="controls">
				  <a href="./loksabha_crime.php?constituency=<?php echo strtolower($constituency) ?>" class="btn btn-large btn-success">Go Back</a>
				  <button type="submit" class="btn btn-large btn-success">Update</button>
				</div>
			  </div>
			</form>
		</div>
		</div>
	</div>
</body>

</html>