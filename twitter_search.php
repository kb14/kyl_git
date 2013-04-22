<!DOCTYPE html>
<html>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
<head>
	<title>Twitter Search</title>
	<link rel="stylesheet" href="bootstrap.css">
	<style type='text/css'>
	
	body {
        padding-top: 60px;
        padding-bottom: 40px;
		background-image: url('twitter_img.jpg');
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
			  <li><a href="#">Twitter Search</a></li>              
			  <li><a href="#contact">About</a></li>
			  <li><a href="#contact">Admin Panel</a></li>

            </ul>
			<form class="navbar-search pull-right">  
			<input type="text" class="search-query" placeholder="Search">  
			</form>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
	
	<form id="twittersearch" name="twittersearch" method="post" action="">
		<input name="twitterq" type="text" id="twitterq"/>
		<button type="submit"  class="btn btn-large btn-success">Search</button>
	</form>
	<div id="twitter-results"></div>
	
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
			},20000);
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