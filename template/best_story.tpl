<main>
	
	<section>
		
		<div class="container">
			
			<br>
			
			<div class="row">

				<div class="col-md-4">

					<h3 align="center" class="text-muted">Best Story of Month</h3>

					<div class="card-story">
						
						<p align="center" class="text-muted">({Carbon\Carbon::now()->format('F, Y')})</p>
						
						<form method="POST" id="best-story-form" action="/story/best_story_of_month">
						
							<div class="form-group">
						
								<select class="form-control" name="story" required>
						
									<option value="">Choose from stories...</option>
						
									{foreach $stories as $story}
					
										<option value="{$story.id}">{$story.title} by {$story.owner_name}</option>
					
									{/foreach}
					
								</select>
					
							</div>
					
							<button type="submit" class="btn btn-primary pull-right">Submit</button>
					
						</form>

					</div>
				
				</div>
				
				<div class="col-md-7 col-md-offset-1">

					{foreach $best_stories as $y_key => $y_value}

						<h3 class="text-muted" align="right">{$y_key}</h2>

						{foreach $y_value as $m_key => $m_value}

							<div class="card-story">

								<h4 class="text-muted" style="text-decoration: underline;">{$m_key}</h4>

								<table class="table table-hover">
									
									<thead>
									
										<th width="25%">Rank</th>
									
										<th width="75%">Story</th>
									
									</thead>

									<tbody>
										
										{foreach $m_value as $s_value}

											<tr>
												
												<td align="center">{$s_value['rank']}</td>

												<td align="center">
													
													<a href="/story/view/{$s_value['slug']}" style="font-weight: 1000">

														{$s_value['title']}

													</a> by {$s_value['owner_name']}

												</td>

											</tr>

										{/foreach}

									</tbody>

								</table>

							</div>

						{/foreach}

					{/foreach}

				</div>

			</div>
		
		</div>

	</section>

</main>