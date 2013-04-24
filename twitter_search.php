<!DOCTYPE html>
<html>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
<?php
function TwitterTrends(){
	$proxy_ip = '10.10.78.22'; //proxy IP here
	$proxy_port = 3128; //proxy port from your proxy list
	$curlhandle = curl_init();
	curl_setopt($curlhandle, CURLOPT_URL, "http://api.twitter.com/1/trends/2295420.json");
    curl_setopt($curlhandle, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curlhandle, CURLOPT_PROXYPORT, $proxy_port);
	curl_setopt($curlhandle, CURLOPT_PROXYTYPE, 'HTTP');
	curl_setopt($curlhandle, CURLOPT_PROXY, $proxy_ip);
	
    $response = curl_exec($curlhandle);
    curl_close($curlhandle);

    $json = json_decode($response);
    return $json[0]->trends;
}
$trends = TwitterTrends();

?>
<head>
	<title>Twitter Search</title>
	<link rel="stylesheet" href="bootstrap.css">
	<style type='text/css'>
	
	body {
        padding-top: 60px;
        padding-bottom: 40px;
		<!--background-image: url('twitter_img.jpg');-->
      }
	#twitterq{
		margin-left: 500px;
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
			  <li class="active"><a href="#">Twitter Search</a></li>              
			  <li><a href="#contact">About</a></li>
			  <li><a href="#contact">Admin Panel</a></li>

            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
	
	<div class="container-fluid">
	<div class="row-fluid">
		<div class="span9">
			<form id="twittersearch" name="twittersearch" class="form-search" method="post" action="">
			<div class="input-append">
				<input name="twitterq" class="search-query span6" type="text" id="twitterq" autocomplete="off"/>
				<button type="submit"  class="btn btn-success">Search</button>
			</div>	
			</form>
			<div id="twitter-results" class="media-list"></div>
		</div>
		<div class="span3">
			<div class="well sidebar-nav">
				<li class="nav-header">Trending Topics: India</li>
				<?php
					foreach($trends as $trend){
				?>
				<li><a tabindex="-1" href="<?php echo $trend->url ?>"><?php echo $trend->name ?></a>  </li>
				<?php
				}
				?>
			</div
		</div>
	</div>
	</div>
	<!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	<script src="http://localhost/kyl/jquery-1.9.1.min.js" type="text/javascript">
	</script>
	
	<script type="text/javascript">
	$(document).ready(function(){
	var twitterq = '';

		function displayTweet(){
			var i = 0;
			var limit = $("#twitter-results > div").size();
			var myInterval = window.setInterval(function () {
			var element = $("#twitter-results div:last-child");
			$("#twitter-results").prepend(element);
			element.fadeIn("slow");
			i++;
			if(i==limit){
				window.setTimeout(function () {
				clearInterval(myInterval);
				});
			}
			},10000);
		}

		$("form#twittersearch").submit(function() {
			twitterq = $('#twitterq').val();
			$.ajax({
			type: "POST",
			url: "search.php",
			cache: false,
			data: "twitterq="+ twitterq,
			success: function(html){
			$("#twitter-results").html(html);
			displayTweet();
			}
			});
			return false;
		});
	});
	</script>
</body>
</html>