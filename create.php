<?php

	$question=htmlspecialchars($_POST['question'],ENT_QUOTES);
	$options=$_POST['options'];

	$servername = "SQL SERVER ADDRESS";
	$username = "USERNAME";
	$password = "PASSWORD";
	$dbname = "DB NAME";

	try 
	{
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO poll (question) VALUES ('". $question ."')";
		$conn->exec($sql);
		$poll_id=$conn->lastInsertId();
		
		$stmt = $conn->prepare("INSERT INTO poll_options (option_id, option_string, poll_id) VALUES (?, ?, ?)");
		$stmt->bindParam(1, $opt_id);
		$stmt->bindParam(2, $opt_string);
		$stmt->bindParam(3, $poll_id);
		
		for($i=0;$i<count($options);++$i)
		{
			$opt_id=$i+1;
			$opt_string=htmlspecialchars($options[$i],ENT_QUOTES);
			$stmt->execute();
		}
		
		echo $poll_id;
		
    }
	catch(PDOException $e)
    {
		echo $sql . "<br>" . $e->getMessage();
    }

	$conn = null;




?>
