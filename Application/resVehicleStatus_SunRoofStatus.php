<script>

	// document.getElementById("resVehicleStatus_SunRoofStatus").innerHTML ="SunRoofStatus";
	
	// resVehicleStatus_SunRoofStatus = document.getElementById('resVehicleStatus_SunRoofStatus');

	//	sunroofstatus	Status of the sunroof	
	let arr_sunroofstatus = [];
	arr_sunroofstatus[0] = "0: Tilt/slide sunroof is closed";
	arr_sunroofstatus[1] = "1: Tilt/slide sunroof is complete open";
	arr_sunroofstatus[2] = "2: Lifting roof is open";
	arr_sunroofstatus[3] = "3: Tilt/slide sunroof is running";
	arr_sunroofstatus[4] = "4: Tilt/slide sunroof in anti-booming position";
	arr_sunroofstatus[5] = "5: Sliding roof in intermediate position";
	arr_sunroofstatus[6] = "6: Lifting roof in intermediate position";
	arr_sunroofstatus[9] = "9: STATUS SUN ROOF NOT AVAILABLE";

    document.write("<b>SUNROOFSTATUS=</b>");
	document.write(arr_sunroofstatus[VEHICLESTATUS_SUNROOFSTATUS] + "<br>");

	// document.getElementById("resVehicleStatus_SunRoofStatus").value ="SUNROOFSTATUS";


</script>