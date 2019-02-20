<?php
/**
 * Smarty css function
 * 
 * Example :
 * {css file="css/style.css"}
 */

function smarty_function_css($params, &$smarty)
{	
	$output = '<link rel="stylesheet"';
	
	foreach ($params as $k => $arg)
	{
		$output .= " $k=\"$arg\"";
	}
	
	$output .= ' />';

	return $output;
}

?>