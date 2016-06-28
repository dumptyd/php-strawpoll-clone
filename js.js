var app = angular.module('pollApp', []);
			
app.controller('pollController', function($scope,$http) 
{
	$scope.err=false;
	$scope.errorString='';
	$scope.inputs=[{index:1,val:''},{index:2,val:''},{index:3,val:''},{index:4,val:''}];
	$scope.currIndex=4;
	$scope.question='';
	$scope.sOpt='';
	$scope.pId='';
	$scope.addOption = function(){
		++$scope.currIndex;
		$scope.inputs.push({index:$scope.currIndex,val:''});
		
	}
	$scope.submit = function() {
		
		var checkIfEmpty=0;
		for(index in $scope.inputs)
		{
			if($scope.inputs[index].val.length<=0)
				continue;
			else
				++checkIfEmpty;
		}
		if($scope.question.length<3)
		{
			$scope.err=true;
			$scope.errorString="Question cannot be empty and must be at least 3 characters long.";
		}
		else if(checkIfEmpty<2)
		{
			$scope.err=true;
			$scope.errorString="Poll must have at least 2 options.";
		}
		else
		{
			$scope.err=false;
			var arr=[];
			for(index in $scope.inputs)
			{
				if($scope.inputs[index].val.length<=0)
					continue;
				else
					arr.push($scope.inputs[index].val)
			}
			
			
			
			$http({
			  method: 'POST',
			  url: 'create.php',
			  data: $.param({'question' : $scope.question,'options': arr}),
			  headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			}).
			success(function(response) {
				//$scope.err=true;
				//$scope.errorString=response;
				window.location.href = 'poll.php?p=' + encodeURIComponent(response);
			}).
			error(function(response) {
				$scope.err=true;
				$scope.errorString="Something went wrong.";
			});
			
		}
		
	}
	
	$scope.vote = function(){
		
		if($scope.sOpt=='')
		{
			$scope.err=true;
			$scope.errorString="Select an option first.";
		}
		else
		{
			$scope.err=false;
			$http({
			  method: 'POST',
			  url: 'vote.php',
			  data: $.param({'pId' : $scope.pId,'option': $scope.sOpt}),
			  headers: {'Content-Type': 'application/x-www-form-urlencoded'}
			}).
			success(function(response) {
				
				if(response=='Voted')
					window.location.href = 'results.php?p='+$scope.pId;
				else
				{
					$scope.err=true;
					$scope.errorString=response;
				}
			}).
			error(function(response) {
				$scope.errorString="Something went wrong on the server.";
				$scope.err=true;
			});
		}
		
	}
});