<div class="modal fade" id="project-award" tabindex="-1">
	<div class="modal-dialog modal-lg" style="width: 1000px;">
		<div class="modal-content">
			<button type="button" class="close" data-dismiss="modal">
				<i class="fa fa-times"></i>
			</button>
			<div class="modal-header">
				<div class="modal-logo">
					<img src="/resource/img/elements/site-logo.svg" alt="logo">
				</div>
				<h2 align="center">
					<i>One step more before proceeding...</i>
				</h2>
			</div>
			<div class="modal-body">
				<div class="final-message"></div>
				<div class="final-container">
					<form action="/projects/award/{$project.id}" id="award-project-form">
						<div class="row">
							<h2 align="center"><i>{$project.title}</i></h2>
						</div>
						<div class="row">
							<div class="col-md-2 col-md-offset-10">
								<button class="add-task"><i class="fa fa-plus" aria-hidden="true"></i> Add Task</button>
							</div>
						</div>
						<div class="task-container">
							<table id="task-table">
								<thead>
									<tr>
										<th width="8%">Task Id</th>
										<th width="35%">Task</th>
										<th width="35%">Author</th>
										<th width="10%">Time</th>
										<th width="10%">Amount</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
						</div>
						<div class="row" style="margin: 10px auto;">
							<div class="col-sm-3 pull-right">
								<button type="submit" class="btn btn-info btn-block text-uppercase">
									Create Story
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	var row_id = 1;
	$("#task-table tbody").html('<tr id="task-row-"' + row_id + '"><td>' + row_id + '</td><td><input type="text" class="form-control" name="name-' + row_id + '" placeholder="Task Name..." required></td><td><select class="form-control" name="author-' + row_id + '" required><option value="">Select Author...</option>{foreach from=$bids item=bid}<option value="{$bid.bidder_id}">{$bid.bidder_name}</option>{/foreach}</select></td><td><input type="date" name="days-' + row_id + '" class="form-control" min="1" placeholder="Payment Schdule..." required></td><td><input type="number" class="form-control" name="amount-' + row_id + '" min="{$project.range_from}" max="{$project.range_to}" step="0.01" placeholder="$$$" required></td></tr>');
	
	$(".add-task").on("click", function(event){
		row_id++;
		$("#task-table tbody").append('<tr id="task-row-"' + row_id + '"><td>' + row_id + '</td><td><input type="text" class="form-control" name="name-' + row_id + '" placeholder="Task Name..." required></td><td><select class="form-control" name="author-' + row_id + '" required><option value="">Select Author...</option>{foreach from=$bids item=bid}<option value="{$bid.bidder_id}">{$bid.bidder_name}</option>{/foreach}</select></td><td><input type="date" name="days-' + row_id + '" class="form-control" min="1" placeholder="Days..." required></td><td><input type="number" class="form-control" name="amount-' + row_id + '" min="{$project.range_from}" max="{$project.range_to}" step="0.01" placeholder="$$$" required></td><td><a href="#" class="delete-task"><i class="fa fa-trash" aria-hidden="true"></i></a></td></tr>');
		event.preventDefault();
	});

	$("#task-table tbody").on("click", ".delete-task", function(event){
		$(this).closest("tr").remove();
		event.preventDefault();
	});


	$("#award-project-form").submit(function(event){
		var input = $(this).serialize();
		var url = $(this).attr("action");
		
		$.ajax({
			url: url,
			type: "POST",
			data: input,
			success: function(data) {
				console.log(data);
				var response = JSON.parse(data);

				if (response.status == "Success") {
					$(".final-message").html(response.data);
					$(".final-container").fadeOut();
					$(".final-message").fadeIn();
					setTimeout(function(){
						window.location = response.url;
					}, 2500);
				}
				
				if (response.status == "Error") {

				}

				if (response.status == "Failure") {
					location.reload();
				}
			}
		});
		event.preventDefault();
	});
</script>