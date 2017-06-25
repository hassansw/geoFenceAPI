<?php


class GeoLocation {

	private $radLat;  // latitude in radians
	private $radLon;  // longitude in radians

	private $degLat;	 // latitude in degrees
	private $degLon;  // longitude in degrees

	private $angular; // angular radius

	const EARTHS_RADIUS_KM = 6371.01; //KM = kilo Meters
	const EARTHS_RADIUS_MI = 3958.762079; // MI = Miles

	protected static $MIN_LAT;  // -PI/2
	protected static $MAX_LAT;  //  PI/2
	protected static $MIN_LON;  // -PI
	protected static $MAX_LON;  //  PI

	public function __construct() {
		self::$MIN_LAT = deg2rad(-90);   // -PI/2
		self::$MAX_LAT = deg2rad(90);    //  PI/2
		self::$MIN_LON = deg2rad(-180);  // -PI
		self::$MAX_LON = deg2rad(180);   //  PI
	}

	public static function fromDegrees($latitude, $longitude) {
		$location = new GeoLocation();
		$location->radLat = deg2rad($latitude);
		$location->radLon = deg2rad($longitude);
		$location->degLat = $latitude;
		$location->degLon = $longitude;
		$location->checkBounds();
		return $location;
	}

	public static function fromRadians($latitude, $longitude) {
		$location = new GeoLocation();
		$location->radLat = $latitude;
		$location->radLon = $longitude;
		$location->degLat = rad2deg($latitude);
		$location->degLon = rad2deg($longitude);
		$location->checkBounds();
		return $location;
	}

	protected function checkBounds() {
		if ($this->radLat < self::$MIN_LAT || $this->radLat > self::$MAX_LAT ||
				$this->radLon < self::$MIN_LON || $this->radLon > self::$MAX_LON)
			throw new \Exception("Invalid Argument");
	}

	public function distanceTo(GeoLocation $location, $unit_of_measurement) {
		$radius = $this->getEarthsRadius($unit_of_measurement);

		return acos(sin($this->radLat) * sin($location->radLat) +
					cos($this->radLat) * cos($location->radLat) *
					cos($this->radLon - $location->radLon)) * $radius;
	}

	public function getLatitudeInDegrees() {
		return $this->degLat;
	}

	public function getLongitudeInDegrees() {
		return $this->degLon;
	}

	public function __toString() {
		return "(" . $this->degLat . ", " . $this->degLon . ") = (" .$this->radLat . " rad, " . $this->radLon . " rad";
	}



	public function boundingCoordinates($distance, $unit_of_measurement) {
		$radius = $this->getEarthsRadius($unit_of_measurement);
		if ($radius < 0 || $distance < 0) throw new \Exception('Arguments must be greater than 0.');

		// angular distance in radians on a great circle
		$this->angular = $distance / $radius;

		$minLat = $this->radLat - $this->angular;
		$maxLat = $this->radLat + $this->angular;

		$minLon = 0;
		$maxLon = 0;
		if ($minLat > self::$MIN_LAT && $maxLat < self::$MAX_LAT) {
			$deltaLon = asin(sin($this->angular) /
				cos($this->radLat));
			$minLon = $this->radLon - $deltaLon;
			if ($minLon < self::$MIN_LON) $minLon += 2 * pi();
			$maxLon = $this->radLon + $deltaLon;
			if ($maxLon > self::$MAX_LON) $maxLon -= 2 * pi();
		} else {
			// a pole is within the distance
			$minLat = max($minLat, self::$MIN_LAT);
			$maxLat = min($maxLat, self::$MAX_LAT);
			$minLon = self::$MIN_LON;
			$maxLon = self::$MAX_LON;
		}

		return array(
			GeoLocation::fromRadians($minLat, $minLon),
			GeoLocation::fromRadians($maxLat, $maxLon)
		);
	}

	protected function getEarthsRadius($unit_of_measurement) {
		$u = $unit_of_measurement;
		if($u == 'miles' || $u == 'mi')
			return $radius = self::EARTHS_RADIUS_MI;
		elseif($u == 'kilometers' || $u == 'km')
			return $radius = self::EARTHS_RADIUS_KM;

		else throw new \Exception('You must supply a valid unit of measurement');
	}


}



function isWithinCircle(GeoLocation $location, GeoLocation $center, $distance, $unitOfMeasurement = 'km') {
        $range = $center->distanceTo($location, $unitOfMeasurement);
        return $range < $distance;

}


function isWithinTheFence(GeoLocation $location, GeoLocation $center, $distance, $unitOfMeasurement = 'km', $name) {
    $inRange = isWithinCircle($location, $center, $distance, $unitOfMeasurement);
		// if(isset($inRange)){echo "The value is set as:";}
		if($inRange == 1 ){echo "\nThe location $name is within the fence...\n<br>";}
		else { echo "\n$name Not in the fence";}

    // need to check the bounds to make the region "square"
    if(!$inRange) {
        list($min, $max) = $center->boundingCoordinates($distance, $unitOfMeasurement);
        $inRange = $min -> getLatitudeInDegrees() <= $location->getLatitudeInDegrees();
        $inRange = $inRange && $min->getLongitudeInDegrees() <= $location->getLongitudeInDegrees();
        $inRange = $inRange && $max->getLatitudeInDegrees() >= $location->getLatitudeInDegrees();
        $inRange = $inRange && $min->getLongitudeInDegrees() >= $location->getLongitudeInDegrees();
    }

    return $inRange ;
}

//open connection to mysql db
$connection = mysqli_connect("localhost","hannu","classw","ais") or die("Error " . mysqli_error($connection));

//fetch table rows from mysql db
$sql_outPost = "SELECT out_name, out_latitude, out_longitude from Outposts";
//Now execution
$Outposts = mysqli_query($connection, $sql_outPost) or die("Error in Selecting " . mysqli_error($connection));

//close the db connection
//mysqli_close($connection);

while($row = mysqli_fetch_assoc($Outposts)) { $test[] = $row; }

// $array = json_encode($test);
// echo $array;

// echo $test[0]['out_latitude'];
// echo $test[0]['out_longitude'];
$numPlate=$_GET['plate'];
$lat=$_GET['lati'];
$long=$_GET['longi'];

// $userAccident = GeoLocation::fromDegrees(24.890964, 67.073732);

$userAccident = GeoLocation::fromDegrees($lat, $long);
// $outpostLocations = GeoLocation::fromDegrees(24.955112, 67.071418);
// $inRange = isWithinTheFence($outpostLocations, $userAccident, 1, 'km', '');

foreach ($test as $temp) {
	// echo $temp['out_name'];
	$outpostLocations = GeoLocation::fromDegrees($temp['out_latitude'], $temp['out_longitude']);
	$inRange = isWithinTheFence($outpostLocations, $userAccident, 1, 'km', $temp['out_name']);
}


$userDetail_sql = "SELECT user_name, user_numPlate,user_id from Users where user_numPlate = '$numPlate' ";
//Now execution
$userDetail = mysqli_query($connection, $userDetail_sql) or die("Error in Selecting " . mysqli_error($connection));

while($row1 = mysqli_fetch_assoc($userDetail)) { $user[] = $row1; }
// $us1 = $user[0]['user_name'];
$us = $user[0]['user_id'];
// echo "<br> Username: $us ";

$fam_sql = "SELECT fam_name from FamilyMembers where user_id = '$us' ";
//Now execution
$fam = mysqli_query($connection, $fam_sql) or die("Error in Selecting " . mysqli_error($connection));

while($row2 = mysqli_fetch_assoc($fam)) { $fam1[] = $row2; }

foreach ($fam1 as $Val) {
	echo "<br> $Val[fam_name]";
}


?>
