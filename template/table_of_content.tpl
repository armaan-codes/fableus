<ul class="parent_part_id_{$parent_part_id}" style="list-style-type:none">
	{foreach from=$parts item=part key=k}
		{if isset($part.__data__) && isset($part.__data__.title) }
			<li id="part_id_{$part.__data__.part_id}" class="Collapsable_not part_order_id_{$part.__data__.part_id}">
				<span>
				{if $callback_type eq 'edit'}
					{assign var="callback" value="/story/edit/`$story.slug`/`$part.__data__.part_id`/`$parent_part_id`"}
				{else}
					{assign var="callback" value="/story/view/`$story.slug`/`$part.__data__.part_id`"}
				{/if}
					<p class="lead text-info">
						<a href="{$callback}">{$part.__data__.title}</a>
				{if !empty($part.__children__)}
					</p>
				</span>
					{include file='table_of_content.tpl' parts=$part.__children__ callback_type=$callback_type story_id=$story_id parent_part_id=$part.__data__.part_id}
				{else}
					{if $callback_type eq 'edit'}
						<a style="float:right; font-size: 12px;" class="text-muted" href="/story/edit/{$story.slug}/0/{$part.__data__.part_id}" alt="Add Section">Add Section</a>
					{/if}
					</p>
				</span>
				{/if}
			</li>
		{/if}
	{/foreach}
	{if $callback_type eq 'edit'}
	<span>
		<a href="/story/edit/{$story.slug}/0/{$parent_part_id}" class="text-muted" alt="Add Chapter">Add Chapter</a>
	</span>	
	{/if}
</ul>