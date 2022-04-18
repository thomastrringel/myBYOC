<script>

	document.getElementById("resRangeElectric").innerHTML ="Hello JavaScript!";
	
	resRangeLiquid = document.getElementById('resRangeElectric');

	var data = [
	  {
		domain: { x: [0, 1], y: [0, 1] },
		value: RANGEELECTRIC,
		title: { text: "Range Electric [km]" },
		type: "indicator",
		mode: "gauge+number",
		delta: { reference: 400 },
		gauge: { axis: { range: [null, 50] } }
	  }
	];

	var layout = { width: 600, height: 400 };
	Plotly.newPlot('resRangeElectric', data, layout);

</script>