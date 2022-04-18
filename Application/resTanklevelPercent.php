<script>

  document.getElementById("resTanklevelPercent").innerHTML ="Hello JavaScript!";
	
	resTanklevelPercent = document.getElementById('resTanklevelPercent');

	var data = [
	  {
		domain: { x: [0, 1], y: [0, 1] },
		value: TANKLEVELPERCENT,
		title: { text: "tanklevelpercent [%]" },
		type: "indicator",
		mode: "gauge+number",
		delta: { reference: 50 },
		gauge: { axis: { range: [null, 100] } }
	  }
	];

	var layout = { width: 600, height: 400 };
	Plotly.newPlot('resTanklevelPercent', data, layout);


</script>