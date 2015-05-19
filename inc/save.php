<?php


//Start the session
session_start();

//Process and sort the data
$data = json_decode(stripslashes($_POST['data']));

usort($data,function($a, $b){
	
	//$a[0] = name
	//$a[1] = instruction
	//$a[2] = day
	//$a[3] = month
	
	//get today's day and month
	$day = intval(date('j'));
	$mon = intval(date('n'));
	
	$c = 0;
	$d = 0;
	
	//if today----------------------------------------------
	if($a[2]==$b[2]&&$a[3]==$b[3]&&$a[2]==$day&&$a[3]==$mon){
		return 0;
	}
	
	if(!empty($a[2])&&!empty($a[3])){
		if($a[2]==$day&&$a[3]==$mon){
			return -1;
		}
	}
	
	if(!empty($b[2])&&!empty($b[3])){
		if($b[2]==$day&&$b[3]==$mon){
			return 1;
		}
	}
	
	//then sort by date-------------------------------------
	if(!empty($a[2])&&!empty($a[3])&&!empty($b[2])&&!empty($b[3])){
		//if both dates are set
		if($a[3]!=$b[3]){
			//if they're not the same month and a>b
			return (intval($a[3])>intval($b[3])) ? -1 : 1;
		} else {
			if($a[2]!=$b[2]){
				//if they're the same month and not the same day and a>b
				return (intval($a[2])>intval($b[2])) ? -1 : 1;
			}
		}
	} else {
		//if one date is set
		if((!empty($a[2])&&!empty($a[3]))||(!empty($b[2])&&!empty($b[3]))){
			//if a is set
			return !empty($a[2])&&!empty($a[3]) ? -1 : 1;
		}
	}
	
	//then sort by instruction/name-------------------------
	if(!empty($a[1])&&!empty($b[1])&&$a[1]!=$b[1]){
		//if they both have different instructions, compare inst string
		return strcmp($a[1],$b[1]);
	} else {
		if(empty($a[1])||empty($b[1])){
			//if one of them doesn't have instruction, place it higher
			return empty($a[1]) ? -1 : 1;
		} else {
			//if they have same instructions, compare name string
			return strcmp($a[0],$b[0]);
		}
	}
});

//Show data in console / add data to session (debug)
#print_r($data); $_SESSION['plan'] = $data;

//Check if session code exists and, if not, generate one
(isset($_SESSION['code'])) ? $code = $_SESSION['code'] : $code = fiveString();

// save data to that code
file_put_contents('../sessions/'.$code.'.txt',serialize($data));
$_SESSION['code']=$code;

exit();


function sortThis($a,$b){
    return strcmp($a[1],$b[1]);
}

function fiveString(){
    return substr(md5(microtime()),rand(0,26),5);
}