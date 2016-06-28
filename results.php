<!DOCTYPE html>
<html lang="en">
	<head>
	  <title>Poll me down</title>
	  <meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link href="css.css" rel="stylesheet" type="text/css">
	  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	  <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
	  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	  <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.js"></script>
	  <script src="js.js"></script>
	 
	  
	</head>
	
	<body>
	
		 
		
		<nav class="navbar navbar-inverse">
			<div class="container-fluid">
				<div class="navbar-header">
				  <a href='index.html' class="navbar-brand">Poll me down</a>
				</div>
			</div>
		</nav>
		
		<?php
			if(isset($_GET['p'])&&!empty($_GET['p'])&&is_numeric($_GET['p']))
		{
			$pid=$_GET['p'];
			$servername = "SQL SERVER ADDRESS";
			$username = "USERNAME";
			$password = "PASSWORD";
			$dbname = "DB NAME";
			$totalvotes=0;
			$totalvotesfordivision=0;

			try 
			{
				$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt = $conn->prepare("SELECT question FROM poll WHERE poll_id=$pid");
				$stmt->execute();
				$question = $stmt->setFetchMode(PDO::FETCH_OBJ); 
				$question= $stmt->fetch();
				if(empty($question))
				{
					echo '<div ng-show="err" class="well-lg text-danger bg-danger text-center lead">Poll does not exist.</div></body></html>';
					die();
				}
				$stmt = $conn->prepare("SELECT option_id AS oid, option_string AS ostr, (SELECT count(option_id) FROM poll_responses WHERE option_id=oid AND poll_id=$pid ) AS count FROM poll_options WHERE poll_id=$pid");
				$stmt->execute();
				$options = $stmt->setFetchMode(PDO::FETCH_OBJ); 
				$options= $stmt->fetchAll();
				foreach($options as $x)
					$totalvotes+=$x->count;
				if($totalvotes==0)
					$totalvotesfordivision=1;
				else
					$totalvotesfordivision=$totalvotes;
			}
			catch(PDOException $e)
			{
				return "Error: " . $e->getMessage();
			}

			$conn = null;
		}
		else
			{
				echo '<div ng-show="err" class="well-lg text-danger bg-danger text-center lead">Poll does not exist.</div></body></html>';
				die();
			}	
		function getVoteCount($num)
		{
			if($num==1)
				return $num." vote";
			else
				return $num." votes";
		}
		
		function getTotalVoteCount($num)
		{
			if($num==1)
				return $num." total vote.";
			else
				return $num." total votes.";
		}
		
	  
		?>
		
		<div class="container-fluid">
			<div class="app-container" ng-app="pollApp" ng-controller="pollController">
				<p ng-init="pId=<?php echo $pid; ?>">
				<div class="well well-lg">
					
					<div class="question text-center">
						<h3><?php echo($question->question); ?></h3>
					</div>
					<div class="options top-gutter"  >
						<?php
						foreach($options as $x){
						echo '<div style="">
							
							<div class="row">
								<div class="col-md-1"><strong>'.$x->oid.'.</strong></div>
								<div class="col-md-11"><strong>'.$x->ostr.'</strong></div>
							</div>
							<div class="row">
								<div class="col-md-1"></div>
								<div class="col-md-7">
									<div class="progress">
									  <div class="progress-bar progress-bar-info" role="progressbar" style="width:'.round(($x->count/$totalvotesfordivision)*100).'%"></div>
									</div>
								</div>
								<div class="col-md-4">
									'.getVoteCount($x->count).' <small>('.round(($x->count/$totalvotesfordivision)*100).'%)</small>
								</div>
							</div>
						</div><hr>';}
						?>
					</div>
					<div class="row top-gutter">
						<div class="text-left text-success col-md-6 lead ">
							<strong><?php echo getTotalVoteCount($totalvotes) ?></strong>
						</div>
						<div class="text-right col-md-6">
							<a href="poll.php?p={{pId}}" >Vote on this poll</a>
						</div>
					</div>
				
				</div>
				
			</div>
		</div>
		
	</body>
</html>