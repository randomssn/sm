<!--PHP Curl Part -->
<?php
    // Defining the basic scraping function
    function scrape_between($data, $start, $end){
        $data = stristr($data, $start); // Stripping all data from before $start
        $data = substr($data, strlen($start));  // Stripping $start
        $stop = stripos($data, $end);   // Getting the position of the $end of the data to scrape
        $data = substr($data, 0, $stop);    // Stripping all data from after and including the $end of the data to scrape
        return $data;   // Returning the scraped data from the function
}

// Defining the basic cURL function
    function curl($url) {
        // Assigning cURL options to an array
        $options = Array(
            CURLOPT_RETURNTRANSFER => TRUE,  // Setting cURL's option to return the webpage data
            CURLOPT_FOLLOWLOCATION => TRUE,  // Setting cURL to follow 'location' HTTP headers
            CURLOPT_FRESH_CONNECT => TRUE, //Setting a fresh connection for every read
            CURLOPT_AUTOREFERER => TRUE, // Automatically set the referer where following 'location' HTTP headers
            CURLOPT_CONNECTTIMEOUT => 120,   // Setting the amount of time (in seconds) before the request times out
            CURLOPT_TIMEOUT => 120,  // Setting the maximum amount of time for cURL to execute queries
            CURLOPT_MAXREDIRS => 10, // Setting the maximum number of redirections to follow
            CURLOPT_USERAGENT => "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1a2pre) Gecko/2008073000 Shredder/3.0a2pre ThunderBrowse/3.2.1.8",  // Setting the useragent
            CURLOPT_URL => $url, // Setting cURL's URL option with the $url variable passed into the function
        );
         
        $ch = curl_init();  // Initialising cURL
        curl_setopt_array($ch, $options);   // Setting cURL's options using the previously assigned array data in $options
        curl_setopt($ch, CURLOPT_PROXY, 'proxy.ssn.net:8080');
        $data = curl_exec($ch); // Executing the cURL request and assigning the returned data to the $data variable
        curl_close($ch);    // Closing cURL
        return $data;   // Returning the data from the function
    }
?>
<!--PHP- BSE/NSE Part-->
<?php
                $url = "http://money.rediff.com";    // Assigning the URL we want to scrape to the variable $url
                $results_page = curl($url); // Downloading the results page using our curl() funtion
                 
                 $bse_page = scrape_between($results_page, "<div id=\"sensTab1\"","<div id=");
                 $nse_page = scrape_between($results_page, "<div id=\"sensTab2\"","<div id=");
                 
                $bse_index_value = scrape_between($bse_page, "<span id=\"bseindex\" class=\"black\">", "</span>"); // Scraping out only the middle section of the results page that contains our results
                $bse_day_change = scrape_between($bse_page, "<span class=\"red\"", "/div>");
                    
                $bse_day_change_value = scrape_between($bse_day_change, ">", "</span>");
                $bse_day_change_percent = scrape_between($bse_day_change, "</span>", "<");

		$nse_index_value = scrape_between($nse_page, "<span id=\"nseindex\" class=\"black\">", "</span>"); // Scraping out only the middle section of the results page that contains our results
                $nse_day_change = scrape_between($nse_page, "<span class=\"red\"", "/div>");
                    
                $nse_day_change_value = scrape_between($nse_day_change, ">", "</span>");
                $nse_day_change_percent = scrape_between($nse_day_change, "</span>", "<");
?>
<!--PHP-DB Connection Part-->
<?php
    $con=mysqli_connect("","root","ssn","stockmarket");

    // Check connection
    if (mysqli_connect_errno($con))
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    //else
        //echo "Connection established";
    mysqli_query($con,"INSERT INTO bseupdates (stockvalue, dayvalue, percent)
VALUES ('$bse_index_value', '$bse_day_change_value','$bse_day_change_percent')");

    mysqli_close($con);
?>

<html>
  <head>
       <script>
           var c = window.setInterval(function(){document.location.reload(true);},3000);
       </script>
	<link href="css/main_page.css" type="text/css" rel="stylesheet" />
       
  </head>

<body>
<div id="content">
<table rules="all">
    <tr>
		<th>Market</th><th>Current Value</th><th>Daily Change</th><th>% Change</th>
	</tr>
	<tr>
		<td>BSE</td>
        <td>
	    <?php                
                echo $bse_index_value;
            ?>
        </td>
        <td>
            <?php
                echo $bse_day_change_value;
            ?>
        </td>
        <td>
            <?php
                echo $bse_day_change_percent;
            ?>
        </td>
    </tr>
    <tr>
 	<td>NSE</td>
	<td>
	    <?php
	    	echo $nse_index_value;
	    ?>
	</td>
        <td>
            <?php
                echo $nse_day_change_value;
            ?>
        </td>
        <td>
            <?php
                echo $nse_day_change_percent;
            ?>
        </td>
    </tr>
</table>
</div>
</body>
</html>
