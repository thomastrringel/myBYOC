<script>


	//	sunroofstatus	Status of the sunroof	
	let arr_windowstatusfrontleft = [];
	arr_windowstatusfrontleft[0] = "0: window in intermediate position";
	arr_windowstatusfrontleft[1] = "1: window completely opened";
	arr_windowstatusfrontleft[2] = "2: window completely closed";
	arr_windowstatusfrontleft[3] = "3: window airing position";
	arr_windowstatusfrontleft[4] = "4: window intermediate airing position";
	arr_windowstatusfrontleft[5] = "5: window currently running";
	arr_windowstatusfrontleft[9] = "9: STATUS WINDOWSFRONTLEFT NOT AVAILABLE";

	document.write("<b>WINDOWSTATUSFRONTLEFT=</b>");
	document.write(arr_windowstatusfrontleft[VEHICLESTATUS_WINDOWSTATUSFRONTLEFT] + "<br>");

</script>