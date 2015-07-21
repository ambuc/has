<?php
	$user = 'root';
	$pw = '';
	$link = mysql_connect('localhost',$user,$pw) or die('Cannot connect to the DB');

	mysql_select_db('default_db',$link) or die('Cannot select the DB');

	function makeQuery($query, $link){      	
      	$result = mysql_query($query, $link) or die('Errant query ' . $name . ': ' . $query);  	
      	return mysql_fetch_assoc($result);
	}
	
	function unique($email, $link){
    	$query = makeQuery("SELECT * from `people` WHERE email='".$email."'",$link);
    	if ($query==""){
        	return true;
    	} else {
        	return false;
    	}
	}
	
	function addperson($firstname, $lastname, $email, $origin, $link){
	   $firstname = mysql_real_escape_string($firstname);
	   $lastname = mysql_real_escape_string($lastname);
	   $email = mysql_real_escape_string($email);
	   $origin = mysql_real_escape_string($origin);
	   
	   if (empty($firstname) or empty($lastname) or empty($email)) {
    	   echo "<h3>You gotta fill out all the forms ;)</h3>";
	   } else {	   
    	   if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        	   if (unique($email, $link)){
                	makeQuery("INSERT INTO `people` (firstname, lastname, email, origin) VALUES ('$firstname','$lastname','$email','$origin')", $link);
                	$id = mysql_insert_id($link);
                	$secretcode = substr($email, 0, 1) . str_pad(substr(dechex($id),-3), 3, "0", STR_PAD_LEFT);
                	$secretcode = substr($email, 0, 1) . dechex($id);
                	$secretcode = strtoupper($secretcode);
                	if ($id%2==0){
                    	$team = 'A';
                	} else {
                    	$team = 'B';
                	}
                	$base = rand(1,24);
                	makeQuery("UPDATE `people` SET secret = '".$secretcode."' WHERE id = ".$id,$link);
                	makeQuery("UPDATE `people` SET team = '".$team."' WHERE id = ".$id,$link);
                	makeQuery("UPDATE `people` SET base = '".$base."' WHERE id = ".$id,$link);
                	printSuccess($firstname, $email, $secretcode, $team, $base);
                	$query = makeQuery("SELECT * from `people` WHERE email='".$email."'",$link);
                	if ($team == 'A') {
                    	$which = 'hide';
                	} else {
                    	$which = 'search';
                	}
                	sendEmail($firstname, $lastname, $email, $origin, $query['id'], $secretcode, $team, $which, $base);
                	followUp($email);
        	   } else {
                	$query = makeQuery("SELECT * from `people` WHERE email='".$email."'",$link);
            	    echo "<h2>You've already registered with this email, ".$query['firstname']."</h2>";
            	    echo "<br/> <br/>";
            	    echo "<h3>Your Secret Code is <span id='box'>".strtoupper($query['secret'])."</span></h3>";
            	    echo "<h3>Your Team is <span id='box'>".strtoupper($query['team'])."</span></h3>";
            	    echo "<h3>Your Base is #<span id='box'>".$query['base']."</span></h3>";
        	   }
    	   } else {
        	   echo "<h3>'<u>" . $email . "</u>' is not a valid email. :( Try again? </h3>";
    	   }
	   }
	}
	
	function sendEmail($firstname, $lastname, $email, $origin, $id, $secretcode, $team, $which, $base){
    	$message = "HIDE AND SEEK in GAMLA STAN // Registration Details";
    	$body = "Thanks for signing up for <a href='http://hideandseek.ninja'>Hide and Seek in Gamla Stan</a>, <b>" . $firstname . " " .$lastname . "</b>. We're glad you decided to join us. :) 
    	<br/> <br/> 
    	Just in case you forget, your <b>secret code is " . strtoupper($secretcode) . ".</b> 
    	<br/> <br/> 
    	You're on <b>Team $team</b>, which means you'll <b>$which</b> first. Meet your teammates in <b>base # $base</b> -- don't worry, we'll send out a detailed map with more instructions and directions to the bases the night before the game. 
        <br/> <br/> 
        See you all <b>16 May at 14:00</b>! :) Please remember your secret code, your team, and your base #. You'll also need to bring a pen and paper to write down the codes of those you find, and an extra sock, so that people know you're hiding.
        <br/> <br/> 
        If you have any questions, please ask us on the <a href='https://www.facebook.com/events/1568336823455138/'>Facebook Page</a>, or check the website, <a href='http://hideandseek.ninja'>http://hideandseek.ninja</a> .
        <br/> <br/> 
        Sincerely, 
        <br/> 
        <b>The Gamlasquad</b>";
        $headers = 'Content-type: text/html; charset=utf-8' . "\r\n";
        mail($email, $message, $body, $headers);
	}
	
	function followUp($email){
    	$message = "HIDE AND SEEK in GAMLA STAN // FINAL INSTRUCTIONS";
    	$body = "Hello everyone! Hide and Seek is coming up this Saturday, and we want to make sure you know where you're going. The weather looks great, so get ready to HIDE :P
<br/> 
WHERE you go depends on your Base. If you don't know that, check the bottom of this email for instructions. Meet at your base between 13:45 — 14:00 and find your Teammates.
<br/> <br/> 
BASES (ZONES):
<br/> 
Bases 1 and 2 — meet by the triangle by the Stenbock Palaces in Riddarholmen <br/> 
Bases 3 and 4 — meet in the center of Stortorget in Gamla Stan <br/> 
Bases 5 and 6 — meet at the Kornhamnstorg public square in Gamla Stan <br/> 
Bases 7 and 8 — meet in Strömparterren, the park by the Museum of Medieval Stockholm <br/> 
Bases 9 and 10 — meet at Fjäderholmslinjen, the fishing pier <br/> 
Bases 11 and 12 — meet at the western exit of the Gamla Stan metro, along Munkbrohamnen <br/> 
Bases 13 and 14 — meet at the Stockholm Strömkajen ferry dock <br/> 
Bases 15 and 16 — meet at the statue in Gustav Adolfs torg <br/> 
Bases 17 and 18 — meet at the docked ship along Västra Brobänken in Skeppsholmen <br/> 
Bases 19 and 20 — meet under the spider at Moderna Museet <br/> 
Bases 21 and 22 — meet at the statue of Charles XIII in Kungsträdgården (the skating Zones rink) <br/> 
Bases 23 and 24 — meet outside Kulturhuset main entrance in Sergels Torg <br/> 
If you don't know where these places are, try searching them in Google Maps tongue emoticon <br/> 
<br/> <br/> 
FAIR GAME AREA:
<br/> 
We want you to have the most fun -- and find lots of people! Don't hide in buildings, metro stations, or on private property.
PLAY FAIR!!!
<br/> 
Don't go near the Royal Palace or the Parliament -- they're super off-limits. The guards don't want to help you play Hide and Seek. <br/> 
Fair game is most of Gamla Stan, Skeppsholmen, around Kungsträdgården and south of Sergels Torg. Check the map: http://i.imgur.com/vHU88j0.png for details.
<br/> <br/> 
SCHEDULE:
<br/> 
13:45—14:00 Before the Game: Locate your Base and meet your teammates.  <br/> 
14:00—14:15 Team A hides! <br/> 
14:15—14:45 Team B searches! <br/> 
14:45—15:00 Reset! Everyone goes back to their Base. <br/> 
15:00—15:14 Team B hides! <br/> 
15:15—15:45 Team A seeks! <br/> 
15:45 —> Game's Over! Go to Kungsträdgården, for the... <br/> 
16:00 —> Afterparty! Meet by the statue of Charles XIII in Kungsträdgården.
<br/> <br/> 
DON'T FORGET: <br/> 
Your secret code!!! <br/> 
An extra sock (to wear on your hand) <br/> 
A pen and paper (for writing down codes) <br/> 
A watch or phone (for keeping time) <br/> 
 <br/>  <br/> 
IF YOU DON'T KNOW YOUR BASE, CODE, OR TEAM <br/> 
Check the first email you received from us. (Subject header 'HIDE AND SEEK in GAMLA STAN // Registration Details). It will have your code, team, and base information! If you cannot find the email, go to http://hideandseek.ninja/register.html and retype your address (the address this message was sent to) to get your information again. <br/> 
See you all THIS SATURDAY at 13:45!!!!! <br/> 
—The Gamlasquad <br/> 
http://hideandseek.ninja";
        $headers = 'Content-type: text/html; charset=utf-8' . "\r\n";
        mail($email, $message, $body, $headers);
	}
	
	function printSuccess($firstname,$email,$secretcode,$team,$base){
    	echo "  <h1>You're Registered!</h1>
                <p> Thanks for signing up, " . $firstname . "!
                <br/>
                Details have been sent to <span id='box'>" . $email . "</span>. You are on <span id='box'>Team $team</span> and you'll meet your teammates at <span id='box'>Base #$base</span> -- don't worry, we'll post a map of the bases online before the game.
                <br/>
                <h3>Your <i>Secret Code</i> is <span id='box'>" . $secretcode . "</span>.</h3></p>";	
	}
		
?>