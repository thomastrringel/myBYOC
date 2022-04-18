<script>

	document.getElementById("resRangeLiquid").innerHTML ="Hello JavaScript!";
	
	resRangeLiquid = document.getElementById('resRangeLiquid');

	var data = [
	  {
		domain: { x: [0, 1], y: [0, 1] },
		value: RANGELIQUID,
		title: { text: "rangeliquid [km]" },
		type: "indicator",
		mode: "gauge+number",
		delta: { reference: 400 },
		gauge: { axis: { range: [null, 700] } }
	  }
	];

	var layout = { width: 600, height: 400 };
	Plotly.newPlot('resRangeLiquid', data, layout);

</script>