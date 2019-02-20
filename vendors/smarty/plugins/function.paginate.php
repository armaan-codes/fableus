<?php
/**
 * Smarty paginate plugin
 * 
 * Example :
 * {paginate current=1 pages=6 link="index.php?page=[PAGE]"}
 */

function smarty_function_paginate($params, &$smarty)
{	
	if(!isset($params['pages']))
		$smarty->trigger_error("<b>pages</b> parameter was not provided.");
	if(!isset($params['link']))
		$smarty->trigger_error("<b>link</b> parameter was not provided.");
	
	$current = 1;
	
	if(isset($params['current']))
		$current = intval($params['current']);
	
	$pages = $params['pages'];
		
    $prev = $current - 1;
    $next = $current + 1;
    $beforeLast = $pages - 1;
    $siblings = 3;
	$link = $params['link'];
    
	
    $pagination = "";

    if($pages > 1)
    {
        $pagination .= "<div class=\"pagination\">\n<ul>\n";

        if ($current == 2)
            $pagination.= "<a href=\"". str_replace('[PAGE]', '1', $link) ."\">&laquo; Prev</a>";
        elseif ($current > 2)
            $pagination.= "<a href=\"". str_replace('[PAGE]', $prev, $link) ."\">&laquo; Prev</a>";
        else
            $pagination.= "<span class=\"disabled\">&laquo; Prev</span>";



        if ($pages < 7 + ($siblings * 2))
        {
            for ($i = 1; $i <= $pages; $i++)
            {
                if ($i == $current)
                    $pagination.= "<span class=\"current\">$i</span>";
                else
                    $pagination.= "<a href=\"". str_replace('[PAGE]', $i, $link) ."\">$i</a>";
            }
        }

        elseif($pages > 5 + ($siblings * 2))
        {
            if($current < 1 + ($siblings * 2))
            {
                for ($i = 1; $i < 4 + ($siblings * 2); $i++)
                {
                    if ($i == $current)
                        $pagination.= "<span class=\"current\">$i</span>";
                    else
                        $pagination.= "<a href=\"". str_replace('[PAGE]', $i, $link) ."\">$i</a>";
                }
                                
                $pagination.= " ... ";

                $pagination.= "<a href=\"". str_replace('[PAGE]', $beforeLast, $link) ."\">$beforeLast</a>";
                $pagination.= "<a href=\"". str_replace('[PAGE]', $pages, $link) ."\">$pages</a>";
            }

            elseif($pages - ($siblings * 2) > $current && $current > ($siblings * 2))
            {
                $pagination.= "<a href=\"". str_replace('[PAGE]', '1', $link) ."\">1</a>";
                $pagination.= "<a href=\"". str_replace('[PAGE]', '2', $link) ."\">2</a>";

                $pagination.= " ... ";

                for ($i = $current - $siblings; $i <= $current + $siblings; $i++)
                {
                    if ($i == $current)
                        $pagination.= "<span class=\"current\">$i</span>";
                    else
                        $pagination.= "<a href=\"". str_replace('[PAGE]', $i, $link) ."\">$i</a>";
                }

                $pagination.= " ... ";

                $pagination.= "<a href=\"". str_replace('[PAGE]', $beforeLast, $link) ."\">$beforeLast</a>";
                $pagination.= "<a href=\"". str_replace('[PAGE]', $pages, $link) ."\">$pages</a>";
            }

            else
            {
                $pagination.= "<a href=\"". str_replace('[PAGE]', '1', $link) ."\">1</a>";
                $pagination.= "<a href=\"". str_replace('[PAGE]', '2', $link) ."\">2</a>";

                $pagination.= " ... ";

                for ($i = $pages - (2 + ($siblings * 2)); $i <= $pages; $i++)
                {
                    if ($i == $current)
                        $pagination.= "<span class=\"current\">$i</span>";
                    else
                        $pagination.= "<a href=\"". str_replace('[PAGE]', $i, $link) ."\">$i</a>";
                }
            }
        }

        if ($current < $i - 1)
            $pagination.= "<a href=\"". str_replace('[PAGE]', $next, $link) ."\">Next &raquo;</a>\n";
        else
            $pagination.= "<span class=\"disabled\">Next &raquo;</span>\n";
        $pagination.= "</div>\n";
    }

    return ($pagination);
}

?>