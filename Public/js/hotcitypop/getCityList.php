<?php

$url="http://autocomplete.ufeifan.com/AutoComplete/s.htm?lan=cn&k=".  urlencode($_REQUEST['k']);


//%25B9%E3
//%25E5%25B9%25BF
if(stripos($url, "%")>10){
   $url = str_replace("%", "%25", $url);
    ////%E6%B8%85%E8%BF%88
}

$html=file_get_contents($url);  
echo $html;
?>