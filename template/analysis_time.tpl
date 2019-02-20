<script type="text/javascript">
	$(document).ready(function(){
		google.charts.load('current', { 'packages':['corechart'] });
		google.charts.setOnLoadCallback(drawChart1);

		function drawChart1() {
			var data1 = google.visualization.arrayToDataTable([
				['Names', 'Time Spent (in Seconds)'],
				{foreach from=$analysis.time item=time}
				['{$time.user_name}', {$time.ts_spent}],
				{/foreach}
			]);

			var options1 = {
					'title': 'Time Spent in Seconds',
					'width': 600,
					'height': 400
			};

			var chart1 = new google.visualization.PieChart(document.getElementById('piechart-1'));

			chart1.draw(data1, options1);
		}
	});
</script>
<div class="modal fade" id="time-chart" tabindex="-1">
	<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-logo">
					<img src="/resource/img/elements/site-logo.svg" alt="">
				</div>
				<h2 align="center">Time Spent Analytics Report</h2>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-8 col-sm-offset-2">
						<div id="piechart-1"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>