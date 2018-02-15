<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
		
// vendor login
// refer API manual
// input password is md5 encrypted
// output is status(0/1) , token
$app->post('/login', function (Request $request, Response $response, array $args) {
  
  $data = json_decode($request->getBody());
  if (isset($data->username) && isset($data->password)) {  
		/*$response->getBody()->write("username is $data->username, password is $data->password");*/  	
		include 'database_connection.php';		
		$username=htmlspecialchars($data->username);
		$password=htmlspecialchars($data->password);	
		$query="
		select IFNULL( (select 1 from vendor where username='".$username."' and password='".$password."') ,'0') as loginStatus,
		(select 7876 from vendor) as loginToken;
		";
		$con = mysqli_connect($databasehost,$databaseusername,$databasepassword, $databasename);		
		if (mysqli_connect_errno())
		{
			$response->getBody()->write("Failed to connect to server : " . mysqli_connect_error());
		}					
		$result = mysqli_query($con ,$query);
		while ($row = mysqli_fetch_assoc($result)) {
			$array[] = $row;
		}
		header('Content-Type:Application/json');
		$response->getBody()->write(json_encode($array));
		mysqli_free_result($result);
		mysqli_close($con);	
  } else {
	  $response->getBody()->write("Failed to connect to server.");
  }
  return $response;
});

// Shop List
// refer API manual
// input -
// output is name, latitude, longitude
$app->get('/vendorShopList', function (Request $request, Response $response, array $args) {
  
  include 'database_connection.php';		
	$query="
	select name as vendorName,latitude as vendorLat,longitude as vendorLong from shop;
	";
	$con = mysqli_connect($databasehost,$databaseusername,$databasepassword, $databasename);		
	if (mysqli_connect_errno())
	{
		$response->getBody()->write("Failed to connect to server : " . mysqli_connect_error());
	}					
	$result = mysqli_query($con ,$query);
	while ($row = mysqli_fetch_assoc($result)) {
		$array[] = $row;
	}
	header('Content-Type:Application/json');
	$response->getBody()->write(json_encode($array));
	mysqli_free_result($result);
	mysqli_close($con);	
  return $response;
});

