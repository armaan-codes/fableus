<script type="text/javascript">
	$(document).ready(function(){
		google.charts.load('current', { 'packages':['corechart'] });
		google.charts.setOnLoadCallback(drawChart);
		google.charts.setOnLoadCallback(drawChart1);

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
<div class="modal fade" id="story-analysis" data-keyboard="false" data-backdrop="static" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal">
				<i class="fa fa-times"></i>
			</button>
			<div class="modal-header">
				<div class="modal-logo" style="text-align: center;">
					<img src="/resource/img/elements/book-icons.png" alt="" style="width: 50%">
				</div>
				<h2 align="center">Story Analysis</h2>
			</div>
			<div class="modal-body">
				<ul class="nav nav-tabs">
					<li class="col-sm-4 col-sm-offset-2 active" style="text-align: center;">
						<a data-toggle="tab" href="#words-analysis" style="font-size: 17px;">Words</a>
					</li>
					<li class="col-sm-4" style="text-align: center;">
						<a data-toggle="tab" href="#time-analysis" style="font-size: 17px;">Time</a>
					</li>
				</ul>
				<div class="tab-content">
					<div id="words-analysis" class="tab-pane fade in active">
						{if $analysis.words}
						<div id="columnchart" style="overflow: hidden;"></div>
						{else}
						<p class="text-muted" align="center">Insufficent Data</p>
						{/if}
					</div>
					<div id="time-analysis" class="tab-pane fade">
						<div class="row">
							{if $analysis.time}
							<div class="col-sm-8 col-sm-offset-2">
								<div id="piechart-1" style="overflow: hidden;"></div>
							</div>
							{else}
							<p class="text-muted" align="center">Insufficent Data</p>
							{/if}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>