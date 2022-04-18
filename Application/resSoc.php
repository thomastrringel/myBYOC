<script>

	document.getElementById("resSoc").innerHTML ="Hello JavaScript!";
	
	resRangeLiquid = document.getElementById('resSoc');

	var data = [
	  {
		domain: { x: [0, 1], y: [0, 1] },
		value: SOC,
		title: { text: "SOC [%]" },
		type: "indicator",
		mode: "gauge+number",
		delta: { reference: 400 },
		gauge: { axis: { range: [null, 100] } }
	  }
	];

	var layout = { width: 600, height: 400 };
	Plotly.newPlot('resSoc', data, layout);

</script>