<?php

	$itemsStore = [];
	$fp = fopen("items.txt","r");
		if (!$fp){
		die('Error!');
}
	$data = file_get_contents("items.txt");
		while (feof($fp)) {
	$data = explode( " " , fgets($fp));
	$itemsStore[] = ["Name" => $data[0] ,"price" => $data[1] , "img" => $data[2]];
}
	//echo "<pre>";
	//print_r($data);
	//echo"</pre>";
	


?>