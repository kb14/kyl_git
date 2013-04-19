<!DOCTYPE html>

<html>

<!-- There are still issues with this file while filtering -->
<head>

    <title>President Profile</title>
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
        font-size: 48px;
        line-height: 1.25;
    }
	.jumbotron .non-lead {
        font-size: 24px;
        line-height: 1.25;
    }
	.socials {  
		padding: 10px;  
	}  
	</style>	
</head>

<body>
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<!-- Navigation bar at top -->
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
              <li><a href="#about">Twitter Search</a></li>
              <li><a href="#contact">About</a></li>
			  <li><a href="#contact">Admin Panel</a></li>

            </ul>
			<form class="navbar-search pull-right">  
			<input type="text" class="search-query" placeholder="Search">  
			</form>
			<ul class="nav pull-left">  
			  <li class="dropdown">  
				<a href="#"  
					  class="dropdown-toggle"  
					  data-toggle="dropdown">  
					  Share  
					  <b class="caret"></b>  
				</a>  
				<ul class="dropdown-menu">  
				 <li class="socials"><!-- Place this tag where you want the +1 button to render -->  
			<g:plusone annotation="inline" width="150"></g:plusone>  
			</li>  
			  <li class="socials"><div class="fb-like" data-send="false" data-width="150" data-show-faces="true"></div></li>  
			  <li class="socials"><a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>  
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></li>  
				</ul>  
			  </li>  
			</ul>  
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
	
	<?php
		require_once __DIR__ . '/db_config.php';
		
		// open a connection to the database server
		$connection = pg_connect ("host=$host port=$port dbname=$db user=$user
		password=$password");
		if (!$connection)
		{
		die("Could not open connection to database server");
		}
		
		if(isset($_GET['term1start'])){
			$t1s = $_GET['term1start'];
			// generate and execute a query
			$query = "SELECT * FROM usexecutives WHERE term1start='$t1s'"; 
			$result = pg_query($connection, $query) or die("Error in query:
			$query. " .
			pg_last_error($connection));
			
			$row = pg_fetch_object($result, 0);
			$not = $row->numofterms;
		}
	?>

	<div class="container-fluid">
	<div class="row-fluid">
		<div class="jumbotron">
			<div class="span2 offset1">
				<img src="./us_president.png" class="img-rounded">
			</div>
			<div class="span4 offset2">
				<p class="lead"><?php echo $row->firstname." ".$row->lastname ?></p><br>
				<p class="non-lead">Birthdate: <?php echo $row->birtddate ?></p>
			</div>
		</div>
	</div>
	
	<hr>	
	
	<div class="row-fluid">
		<div class="span8 offset2">
			<table class="table">
			<thead>  
			  <tr>  
				<th>Term#</th>  
				<th>Started</th>  
				<th>Ended</th>
				<th>Party</th>
			  </tr>  
			</thead> 
			<tbody>
			<?php
				// iterate through resultset
				for ($i=1; $i<=$not; $i++){	
					$tstart = "term".$i."start";
					$tend = "term".$i."end";
					$tparty = "term".$i."party";
			?>
			<tr>
			<td><?php echo $i ?></td>
			<td><?php echo $row->$tstart ?></td>
			<td><?php echo $row->$tend ?></td>  
            <td><?php echo $row->$tparty ?></td>
			</tr>
			<?php
			}
			?>
			</tbody>
			</table>
		</div>
	</div>
	</div>
	
	
	<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	
    <script type="text/javascript" src="http://localhost/kyl/jquery.js"></script>
	<script type="text/javascript" src="http://localhost/kyl/bootstrap-dropdown.js"></script>
	<script type="text/javascript" src="http://localhost/kyl/bootstrap-button.js"></script>
	<script type="text/javascript">  
	  (function() {  
		var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;  
		po.src = 'https://apis.google.com/js/plusone.js';  
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);  
	  })();  
	</script>
</body>
</html>	  