<?php

	$pid=$_POST["pId"];
	$option=htmlspecialchars($_POST["option"],ENT_QUOTES);
	$ip_addr=ip2long($_SERVER["REMOTE_ADDR"]);

	$servername = "SQL SERVER ADDRESS";
	$username = "USERNAME";
	$password = "PASSWORD";
	$dbname = "DB NAME";

	try 
	{
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$nRows = $conn->query("SELECT count(*) FROM poll_responses WHERE ip_addr='$ip_addr' AND poll_id='$pid' ")->fetchColumn();
		if($nRows==0 OR $ip_addr==0)
		{
			$sql = "INSERT INTO poll_responses (poll_id, option_id, ip_addr) VALUES ('$pid', '$option', '$ip_addr')";
			$conn->exec($sql);
			echo "Voted";
		}
		else
		{
			echo "You have already voted on this poll";
		}
    }
	catch(PDOException $e)
    {
		echo " Error:" . $e->getMessage()." ".$pid."boo";
    }

	$conn = null;




?>
