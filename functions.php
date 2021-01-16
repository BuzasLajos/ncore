<?php

function cURL($url, $header=NULL, $cookie=NULL, $p=NULL){
	$ret = "";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, $header);
    curl_setopt($ch, CURLOPT_NOBODY, $header);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);	
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    if ($p) {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $p);
    }
    $result = curl_exec($ch);

    if ($result) {
        $ret = $result;
    } else {
        $ret = curl_error($ch);
    }
	
    curl_close($ch);
	
	return $ret;
}

function html_to_array($html){
	$hnrAll = array();
	
	$dom = new \DOMDocument('1.0', 'UTF-8');	// create new DOMDocument
	$internalErrors = libxml_use_internal_errors(true);	// set error level
	$dom->loadHTML($html);	// load HTML
	libxml_use_internal_errors($internalErrors);	// Restore error level

	$finder = new DomXPath($dom);
	$nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '),     'hnr_torrents')]");

	$tmp_dom = new DOMDocument(); 
	foreach ($nodes as $node){
		$tmp_dom->appendChild($tmp_dom->importNode($node,true));
	}
	$innerHTML=trim($tmp_dom->saveHTML()); 

	$buffdom = new DOMDocument();
	@$buffdom->loadHTML($innerHTML);
	$i=0;
	foreach($buffdom->getElementsByTagName('*') as $elem) {
		$class_name = "";
		if(gettype($elem)=="object"){ 
			$class = $elem->attributes->getNamedItem('class');
			if(gettype($elem->attributes->getNamedItem('class'))=="object"){
				$class_name = $elem->attributes->getNamedItem('class')->nodeValue;
			}
		}
		
		if( strlen($class_name)>0 ){
			if($class_name=="hnr_tname"){
				$i++;
				foreach($elem->getElementsByTagName('a') as $link){
					$link = "https://ncore.cc/".$link->attributes->getNamedItem('href')->nodeValue;
					$hnrAll[$i]['link'] = $link;
				}

			}
			$hnrAll[$i][$class_name] = $elem->nodeValue;			
		}
	}
	
	return $hnrAll;
}

function torrent_array_to_email_body($hnrAll){
	$email_body = "";
	if( !empty($hnrAll) ){
		foreach( $hnrAll as $torrent ){
			if(isset($torrent['hnr_tstart'])){
				if(strpos($torrent['hnr_tstart'],"napja")>0){
					$passed_days = str_replace(" napja", "", $torrent['hnr_tstart']);
					$style=""; if($passed_days>10){$style = "style=\"color:red;\"";}
					$email_body .= "<span {$style}>";				
					$email_body .= "<a href=\"".$torrent['link']."\">".$torrent['hnr_tname']."</a>";
					$email_body .= " Start: ".$torrent['hnr_tstart'];
					$email_body .= "</span>";
					$email_body .= "<br/>";
				}
			}
		}
	}
	return $email_body;
}
?>