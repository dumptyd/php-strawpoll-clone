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
				$stmt = $conn->prepare("SELECT option_id as oid, option_string as ostr FROM poll_options WHERE poll_id=$pid");
				$stmt->execute();
				$options = $stmt->setFetchMode(PDO::FETCH_OBJ); 
				$options= $stmt->fetchAll();
			}
			catch(PDOException $e)
			{
				return "Error: " . $e->getMessage();
			}

			$conn = null;
		}
		else
			{
				echo '<div ng-show="err" class="well-lg text-danger bg-danger text-center lead">Bad link. Poll does not exist.</div></body></html>';
				die();
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
						echo '<div class="form-group" >
							<label><input type="radio" value="'.$x->oid .'" ng-model="sOpt">
							<span class="radio-btn">'.$x->ostr.'</span></label>
							<!--div class="progress">
							  <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" style="width:70%"></div>
							</div-->
						</div>';}
						?>
					</div>
					<div class="text-center">
						
							<button class="btn btn-primary btn-block" ng-click="vote()">Vote</button>
						
						
							<a href="results.php?p={{pId}}" class='top-gutter' >View results</a>
						
					</div>
				
				</div>
				
				<div ng-show="err" class="well-sm text-danger bg-danger">{{errorString}}</div>
				
			</div>
		</div>
		
	</body>
</html>