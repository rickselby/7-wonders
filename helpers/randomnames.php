<?php

class RandomNames {

	private static $firstName = ['AARON','ADAM','ALAN','ALBERT','ALEX','ALEXANDER',
		'ALFRED','ALLEN','ALVIN','ANDREW','ANTHONY','ANTONIO','ARTHUR','BARRY',
		'BENJAMIN','BERNARD','BILL','BILLY','BOBBY','BRADLEY','BRANDON','BRENT',
		'BRIAN','BRUCE','BRYAN','CALVIN','CARL','CARLOS','CHAD','CHARLES',
		'CHARLIE','CHRIS','CHRISTOPHER','CLARENCE','CLIFFORD','CLYDE','COREY',
		'CRAIG','CURTIS','DALE','DAN','DANIEL','DANNY','DARRELL','DAVID','DEAN',
		'DENNIS','DEREK','DERRICK','DON','DONALD','DOUGLAS','DUSTIN','EARL',
		'EDDIE','EDWARD','EDWIN','ERIC','ERNEST','EUGENE','FLOYD','FRANCIS',
		'FRANCISCO','FRANK','FRED','FREDERICK','GARY','GENE','GEORGE','GERALD',
		'GILBERT','GLEN','GLENN','GORDON','GREG','GREGORY','HAROLD','HARRY',
		'HECTOR','HENRY','HERBERT','HERMAN','HOWARD','JACK','JACOB','JAMES',
		'JASON','JAY','JEFF','JEFFERY','JEFFREY','JEREMY','JEROME','JERRY',
		'JESSE','JESUS','JIM','JIMMY','JOE','JOEL','JOHN','JOHNNY','JON',
		'JONATHAN','JORGE','JOSE','JOSEPH','JOSHUA','JUAN','JUSTIN','KEITH',
		'KENNETH','KEVIN','KYLE','LARRY','LAWRENCE','LEE','LEO','LEON','LEONARD',
		'LEROY','LESTER','LEWIS','LLOYD','LOUIS','LUIS','MANUEL','MARCUS',
		'MARIO','MARK','MARTIN','MARVIN','MATTHEW','MAURICE','MELVIN','MICHAEL',
		'MICHEAL','MIGUEL','MIKE','NATHAN','NICHOLAS','NORMAN','OSCAR','PATRICK',
		'PAUL','PEDRO','PETER','PHILIP','PHILLIP','RALPH','RAMON','RANDALL',
		'RANDY','RAY','RAYMOND','RICARDO','RICHARD','RICK','RICKY','ROBERT',
		'ROBERTO','RODNEY','ROGER','RONALD','RONNIE','ROY','RUSSELL','RYAN',
		'SAM','SAMUEL','SCOTT','SEAN','SHANE','SHAWN','STANLEY','STEPHEN',
		'STEVE','STEVEN','TERRY','THEODORE','THOMAS','TIM','TIMOTHY','TODD',
		'TOM','TOMMY','TONY','TRAVIS','TROY','TYLER','VERNON','VICTOR','VINCENT',
		'WALTER','WARREN','WAYNE','WESLEY','WILLIAM','WILLIE','ZACHARY'];

	private static $lastNames = ['ADAMS','ALEXANDER','ALLEN','ANDERSON','ANDREWS',
		'ARMSTRONG','ARNOLD','AUSTIN7','BAILEY','BAKER','BARNES','BELL',
		'BENNETT','BERRY','BLACK','BOYD','BRADLEY','BROOKS','BROWN','BRYANT',
		'BURNS','BUTLER','CAMPBELL','CARPENTER','CARROLL','CARTER','CHAVEZ',
		'CLARK','COLE','COLEMAN','COLLINS','COOK','COOPER','COX','CRAWFORD',
		'CRUZ','CUNNINGHAM','DANIELS','DAVIS','DIAZ','DIXON','DUNCAN','DUNN',
		'EDWARDS','ELLIOTT','ELLIS','EVANS','FERGUSON','FISHER','FLORES','FORD',
		'FOSTER','FOX','FRANKLIN','FREEMAN','GARCIA','GARDNER','GIBSON','GOMEZ',
		'GONZALES','GONZALEZ','GORDON','GRAHAM','GRANT','GRAY','GREEN','GREENE',
		'GRIFFIN','HALL','HAMILTON','HARPER','HARRIS','HARRISON','HART',
		'HAWKINS','HAYES','HENDERSON','HENRY','HERNANDEZ','HICKS','HILL',
		'HOLMES','HOWARD','HUDSON','HUGHES','HUNT','HUNTER','JACKSON','JAMES',
		'JENKINS','JOHNSON','JONES','JORDAN','KELLEY','KELLY','KENNEDY','KING',
		'KNIGHT','LANE','LAWRENCE','LAWSON','LEE','LEWIS','LONG','LOPEZ',
		'MARSHALL','MARTIN','MARTINEZ','MASON','MATTHEWS','MCDONALD','MILLER',
		'MILLS','MITCHELL','MOORE','MORALES','MORGAN','MORRIS','MURPHY','MURRAY',
		'MYERS','NELSON','NICHOLS','OLSON','ORTIZ','OWENS','PALMER','PARKER',
		'PATTERSON','PAYNE','PEREZ','PERKINS','PERRY','PETERS','PETERSON',
		'PHILLIPS','PIERCE','PORTER','POWELL','PRICE','RAMIREZ','RAMOS','RAY',
		'REED','REYES','REYNOLDS','RICE','RICHARDSON','RILEY','RIVERA','ROBERTS',
		'ROBERTSON','ROBINSON','RODRIGUEZ','ROGERS','ROSE','ROSS','RUIZ',
		'RUSSELL','SANCHEZ','SANDERS','SCOTT','SHAW','SIMMONS','SIMPSON','SIMS',
		'SMITH','SNYDER','SPENCER','STEPHENS','STEVENS','STEWART','STONE',
		'SULLIVAN','TAYLOR','THOMAS','THOMPSON','TORRES','TUCKER','TURNER',
		'WAGNER','WALKER','WALLACE','WARD','WARREN','WASHINGTON','WATKINS',
		'WATSON','WEAVER','WEBB','WELLS','WEST','WHITE','WILLIAMS','WILLIS',
		'WILSON','WOOD','WOODS','WRIGHT','YOUNG'];

	public static function getName()
	{
		return array(
			'FirstName' => ucfirst(strtolower(
					self::$firstName[array_rand(self::$firstName)])),
			'LastName' => ucfirst(strtolower(
					self::$lastNames[array_rand(self::$lastNames)]))
				);
	}

}