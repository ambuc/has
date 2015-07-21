<?php
	$user = 'root';
	$pw = '';
	$link = mysql_connect('localhost',$user,$pw) or die('Cannot connect to the DB');

	mysql_select_db('default_db',$link) or die('Cannot select the DB');

	function makeQuery($query, $link){      	
      	$result = mysql_query($query, $link) or die("Errant query '$name': '$query'");  
      	return mysql_fetch_assoc($result);
	}
	function getRows($query, $link){      	
      	$result = mysql_query($query, $link) or die("Errant query '$name': '$query'");  

      	$array = array();

      	while ($row = mysql_fetch_array($result)){
          	$array[] = $row[0];
      	}

      	return $array;
      	
	}
	
	
	function score($myCode, $myBase, $numbers, $link){
    	#SANITIZE INPUTS
    	$myCode = mysql_real_escape_string($myCode);
    	$myBase = mysql_real_escape_string($myBase);
    	foreach ($numbers as $num) {
        	$num = mysql_real_escape_string($num);
    	}
    	
    	#PROCESS INCOMING NUMBERS
    	$numbers = array_filter($numbers); #removes empty strings
    	$numbers = array_unique($numbers); #removes duplicate findings
        
        $allSecrets = getRows("SELECT secret FROM `people`",$link);
        
        $getBase = makeQuery("SELECT base from `people` WHERE secret='$myCode'",$link)['base'];
       
        if ($getBase != $myBase){
            echo "<h3> Sorry, your secret code doesn't match your base. Check your email for a message concerning Hide and Seek in Gamla Stan. <small>We want to make sure it's really you ;)</small>";
        } else {
                                
            if (!in_array($myCode, $allSecrets)){
            	echo "<h3>Sorry, your secret code doesn't seem to be valid :( Try again?";
        	} else {    	   
                            
            	foreach ($numbers as $num) {
            	   if (in_array($num, $allSecrets)){
                       $exist = makeQuery("SELECT id FROM `finds` WHERE seeker='$myCode' AND hider='$num'", $link);
                       if (empty($exist)){
                           makeQuery("INSERT INTO `finds` (seeker, hider) VALUES ('$myCode','$num')", $link); //document kill
                           makeQuery("UPDATE `people` SET d=d+1 WHERE secret = '$num'", $link); //update their death
                           makeQuery("UPDATE `people` SET k=k+1 WHERE secret = '$myCode'", $link); //update my kills
                           makeQuery("UPDATE `people` SET kdr=k/d WHERE secret = '$myCode'", $link); //update my KDR
                           makeQuery("UPDATE `people` SET kdr=k/d WHERE secret = '$num'", $link); // update theirs
                       }
                   }
            	}
            	
                $firstname = makeQuery("SELECT firstname from `people` WHERE secret='$mycode'",$link);
                $firstname = $firstname['firstname'];
                echo "<h3>Congrats, $firstname! You found: </h3><ol>";
                $allFinds = getRows("SELECT concat(firstname, ' ', lastname) FROM `people`, `finds` WHERE people.secret = finds.hider AND finds.seeker = '$myCode'",$link);
                foreach ($allFinds as $name){
                    echo "<li> $name </li>";
                }
    
            	echo "</ol><h3>Great job!</h3>";
    
                $kills = getRows("SELECT k FROM `people` WHERE secret = '$myCode'",$link)[0];
                $deaths = getRows("SELECT d FROM `people` WHERE secret = '$myCode'",$link)[0];
                $kdr = getRows("SELECT kdr FROM `people` WHERE secret = '$myCode'",$link)[0];
     
                echo "<h2><i>So far</i> you've found $kills people and <i>been</i> found $deaths time(s), with a hide/seek ratio of $kdr.</h2>";
          	
            	echo "<h3>You can check your global ranking <a href='/rankings.html'>here</a>.</h3>";
            	echo "<h4>We'll tally the points and post the winners online in a few days.</h4>";
    
        	}
        }

	}	
	
		
?>