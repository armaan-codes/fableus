<script type="text/javascript">
	$(document).ready(function(){
		google.charts.load('current', { 'packages':['corechart'] });
		google.charts.setOnLoadCallback(drawChart);

		function drawChart() {
			var data = google.visualization.arrayToDataTable([
				['Names', 'Words Witten'],
				{foreach from=$analysis.words item=word}
				['{$word.user_name}', {$word.no_words}],
				{/foreach}
			]);

			var options = {
					title: 'No. of words written by each contributor',
					'width': 700,
					'height': 400,
					bar: { groupWidth: "25%" }
			};

			var chart = new google.visualization.ColumnChart(document.getElementById('columnchart'));

			chart.draw(data, options);
		}
	});
</script>
<div class="modal fade" id="words-chart" tabindex="-1">
	<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-logo">
					<img src="/resource/img/elements/site-logo.svg" alt="">
				</div>
				<h2 align="center">Words Wise Analytics Report</h2>
			</div>
			<div class="modal-body">
				<div id="columnchart"></div>
			</div>
		</div>
	</div>
</div>