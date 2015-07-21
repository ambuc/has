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
          	$array[] = array('name' => $row[0], 'kdr' => $row[1], 'kill' => $row[2], 'death' => $row[3], 'score' => $row[4], 'secret' => $row[5]);
      	}

      	return $array;	
	}
	
	
	function getFinds($query, $link){      	
      	$result = mysql_query($query, $link) or die("Errant query '$name': '$query'");  
      	$array = array();
      	
      	while ($row = mysql_fetch_array($result)){
          	$array[] = array('seeker' => $row[0], 'hider' => $row[1], 'death' => $row[2]);
      	}

      	return $array;	
	}
	
	function getSeeks($query, $link){      	
      	$result = mysql_query($query, $link) or die("Errant query '$name': '$query'");  
      	$array = array();
      	
      	while ($row = mysql_fetch_array($result)){
          	$array[] = array('secret' => $row[0], 'death' => $row[1]);
      	}

      	return $array;	
	}
	
	function rescore($link){
	   #reset all scores to zero
	   makeQuery("UPDATE `people` SET score = 0", $link);
	   
	   #get all players
       $players = getRows("SELECT firstname, kdr, k, d, score, secret FROM `people` ORDER BY kdr DESC", $link);

       #for each person playing
       foreach ($players as $player){
            
            #get my finds
            $finds = getFinds("SELECT seeker, hider, people.d FROM `finds`, `people` WHERE seeker = '" . $player['secret'] . "' AND people.secret = hider",$link);
            
            #give me a base score of zero
            $score = 0;
            
            #and give me points for everyone i find proportional to how often they are found
            foreach ($finds as $find){
                $score += 100 / ($find['death']);
            }

            #if i played at all,
            if ($player['kill'] != 0 and $player['death'] != 0){
                #find out how many times I was killed and give me credit for it
                $sought = getSeeks("SELECT secret, d FROM `people` WHERE secret='".$player['secret']."'", $link);
                $score += 100 / $sought[0]['death'];                
            } else {
                #do nothing, no points
            }

            #and push that survival score
            makeQuery("UPDATE `people` SET score = $score WHERE secret = '" . $player['secret'] . "'", $link);
       }
        
	}
	
	function display($link){
       $players = getRows("SELECT firstname, kdr, k, d, score FROM `people` ORDER BY score DESC", $link);
       echo "<table class='table table-striped table-bordered'>";
       echo "<thead> <tr> <th> score </th> <td> name </td> <td> # of people found </td>  <td> # of times found </td> <td> kdr </td> </tr> </thead> <tbody>";
       foreach($players as $player){
           echo "<tr>";
               echo "<th>" . $player['score'] . "</th> "; 
               echo "<td>" . $player['name'] . "</td> "; 
               echo "<td>" . $player['kill'] . "</td> "; 
               echo "<td>" . $player['death'] . "</td> "; 
               echo "<td>" . $player['kdr'] . "</td>"; 
           echo "</tr>";
        }
       echo "</tbody> </table>";
	}	
		
?>