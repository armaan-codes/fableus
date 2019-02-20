<main>
	<section class="section">
		<div class="container">
			<div class="row project-option">
				<div class="col-md-8 col-md-offset-2">
					<div id="custom-project-search">
						<form method="POST" id="search-project-form" action="/projects/search">
							<div class="input-group col-md-12">
								<input type="text" class="form-control input-lg" id="search-input" placeholder="Search My projects..." name="search" required>
								<span class="input-group-btn" style="padding-left: 1%;">
									<button type="Submit"><i class="fa fa-search"></i></button>
								</span>
							</div>
						</form>
					</div>
					<div class="col-md-12">
						<h4 align="center" class="text-muted">
							<a href="#" data-toggle="modal" data-target="#new-project" data-backdrop="static">
								<i class="fa fa-plus"></i> Submit New Project
							</a>
						</h4>
					</div>
				</div>
			</div>
		</div>
	</section>
	<section class="section" id="section-anchor">
		<div class="container">
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1 search-result"></div>
				<div class="col-sm-10 col-sm-offset-1 project-area" style="min-height: 205px;">
					<ul class="nav nav-tabs">
						<li class="col-sm-4 col-sm-offset-2 active" style="text-align: center;">
							<a data-toggle="tab" href="#open-projects" style="font-size: 17px;">Open</a>
						</li>
						<li class="col-sm-4" style="text-align: center;">
							<a data-toggle="tab" href="#award-projects" style="font-size: 17px;">Awarded</a>
						</li>
					</ul>
					<div class="tab-content">
						<div id="open-projects" class="tab-pane fade in active">
							{if !empty($projects.open.projects)}
							<div id="market-container" class="card-story-container">
								{foreach from=$projects.open.projects item=project}
								<div class="card-story">
									<h2>
										<a href="/projects/view/{$project.id}">
											{$project.title}
										</a>
										<span class="text-muted" style="float:right; font-size:18px;">
											${$project.range_from} - ${$project.range_to}
										</span>
									</h2>
									<div class="media media-card">
										<div class="media-left">
											<img src="/resource/img/user/{$project.owner_image}" class="media-object">
										</div>
										<div class="media-body">
											<ul class="list-story">
												<span class="list-dotted"></span>
												<li>
													by <a href="/user/view/{$project.owner_id}">{$project.owner_name}</a>
												</li>
												<span class="list-dotted"></span>
												<li class="mobile-block">
													Created {$project.time}
												</li>
												<span class="list-dotted"></span>
												<li>
													<i class="fa fa-gavel" aria-hidden="true"></i> <a>{$project.stats.bids}</a>
												</li>
											</ul>
											<hr>
											{$project.50_words}
										</div>
									</div>
								</div>
								{/foreach}
							</div>
							<a href="/projects/load_open_projects/" data-id="{$projects.open.last_project_id}" data-for="market-container" class="btn btn-info btn-load-project btn-block text-uppercase">Load More</a>
							{else}
							<div class="card-story-container">
								<a disabled class="btn btn-info btn-block text-uppercase">No projects</a>
							</div>
							{/if}
						</div>
						<div id="award-projects" class="tab-pane fade">
							{if !empty($projects.award.projects)}
							<div id="market-award-container" class="card-story-container">
								{foreach from=$projects.award.projects item=project}
								<div class="card-story">
									<h2>
										<a href="/market/view/{$project.id}">
											{$project.title}
										</a>
										<span class="text-muted" style="float:right; font-size:18px;">
											${$project.range_from} - ${$project.range_to}
										</span>
									</h2>
									<div class="media media-card">
										<div class="media-left">
											<img src="/resource/img/user/{$project.owner_image}" class="media-object">
										</div>
										<div class="media-body">
											<ul class="list-story">
												<span class="list-dotted"></span>
												<li>
													by <a href="/user/view/{$project.owner_id}">{$project.owner_name}</a>
												</li>
												<span class="list-dotted"></span>
												<li class="mobile-block">
													Created {$project.time}
												</li>
												<span class="list-dotted"></span>
												<li>
													<i class="fa fa-gavel" aria-hidden="true"></i> <a>{$project.stats.bids}</a>
												</li>
												<span class="list-dotted"></span>
												<li class="mobile-block" style="color: red">
													<strong>Awarded</strong>
												</li>
											</ul>
											<hr>
											{$project.50_words}
										</div>
									</div>
								</div>
								{/foreach}
							</div>
							<a href="/projects/load_award_projects/" data-id="{$projects.award.last_project_id}" data-for="market-award-container" class="btn btn-info btn-block text-uppercase btn-load-project">Load More</a>
							{else}
							<div class="card-story-container">
								<a disabled class="btn btn-info btn-block text-uppercase">No projects</a>
							</div>
							{/if}
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>
{include file="market_place_new.tpl"}