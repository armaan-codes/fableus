<?php
/**
 * Smarty js block
 * 
 * Example :
 * {js}
 * 		alert('Hello world');
 * {/js}
 */

function smarty_block_js($params, $content, &$smarty, &$repeat)
{
	if($repeat)
	{
		$output = '<script type="text/javascript"';
	
		foreach ($params as $k => $arg)
		{
			$output .= " $k=\"$arg\"";
		}
		
		$output .= '>';
	
		echo $output;
	}
	else 
	{
		return $content . '</script>';
	}
}


?>