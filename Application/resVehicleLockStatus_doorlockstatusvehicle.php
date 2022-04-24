<script>

	//	doorlockstatusvehicle	Vehicle lock status
	let doorlockstatusvehicle = [];
	doorlockstatusvehicle[0] = "0: vehicle unlocked";
	doorlockstatusvehicle[1] = "1: vehicle internal locked";
	doorlockstatusvehicle[2] = "2: vehicle external locked";
	doorlockstatusvehicle[3] = "3: vehicle selective unlocked";
	doorlockstatusvehicle[4] = "4: VEHICLELOCKSTATUS_DOORLOCKSTATUSVEHICLE NOT AVAILABLE";

    document.write("<b>DOORLOCKSTATUSVEHICLE=</b>");
	document.write(doorlockstatusvehicle[VEHICLELOCKSTATUS_DOORLOCKSTATUSVEHICLE] + "<br>");


</script>