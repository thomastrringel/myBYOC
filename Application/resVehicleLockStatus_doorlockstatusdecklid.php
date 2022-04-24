<script>

	//	doorlockstatusdecklid	Lock status of the deck lid
	let doorlockstatusdecklid = [];
	doorlockstatusdecklid[0] = "0: false: locked";
	doorlockstatusdecklid[1] = "1: true: unlocked";
	doorlockstatusdecklid[2] = "2: STATUS NOT AVAILABLE";

	doorlockstatusdecklidvalue = 2; // default = undefined

	if ( VEHICLELOCKSTATUS_DOORLOCKSTATUSDECKLID == "false" ) { doorlockstatusdecklidvalue = 0; };
	if ( VEHICLELOCKSTATUS_DOORLOCKSTATUSDECKLID == "true" ) { doorlockstatusdecklidvalue = 1; };

    document.write("<b>DOORLOCKSTATUSDECKLID=</b>");
	// document.write(typeof(VEHICLELOCKSTATUS_DOORLOCKSTATUSDECKLID));
	document.write(doorlockstatusdecklid[doorlockstatusdecklidvalue] + "<br>");


</script>