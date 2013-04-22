<?php
//include('search.php');
if($_POST['twitterq']){
$twitter_query = $_POST['twitterq'];
$results = TwitterSearch($twitter_query);
 
foreach($results as $result){
echo '<div class="twitter_status well well-small">';
echo '<img src="'.$result->profile_image_url.'" class="twitter_image">';
echo '<a href="http://www.twitter.com/'.$result->from_user.'">'.$result->from_user.'</a>: ';
echo $result->text;
//echo '<div class="twitter_small">';
//echo '<strong>From:</strong> <a href="http://www.twitter.com/'.$result->from_user.'">'.$result->from_user.'</a&glt;: ';
//echo '<strong>at:</strong> '.$result->created_at;
//echo '</div>';
echo '</div>';
}
}


function TwitterSearch($twitter_query)
{
$proxy_ip = '10.10.78.22'; //proxy IP here
	$proxy_port = 3128; //proxy port from your proxy list
	$curlhandle = curl_init();
	echo $curlhandle;
	curl_setopt($curlhandle, CURLOPT_URL, "http://search.twitter.com/search.json?q=$twitter_query&rpp=50");
    curl_setopt($curlhandle, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curlhandle, CURLOPT_PROXYPORT, $proxy_port);
	curl_setopt($curlhandle, CURLOPT_PROXYTYPE, 'HTTP');
	curl_setopt($curlhandle, CURLOPT_PROXY, $proxy_ip);
	
    $response = curl_exec($curlhandle);
    curl_close($curlhandle);

    $json = json_decode($response);
    return $json->results;
}
?>