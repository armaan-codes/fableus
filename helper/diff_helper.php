<?php

function string_to_array($string){
	return preg_split('/(<\/?\w+[^<>]*>)|\s+/', $string, null, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
}

function array_sort($array, $on, $order=SORT_ASC) {
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

function index_diff($old, $new) {
	$matrix = array();
	$maxlen = 0;
	foreach($old as $oindex => $ovalue){
		$nkeys = array_keys($new, $ovalue);
		foreach($nkeys as $nindex){
			$matrix[$oindex][$nindex] = isset($matrix[$oindex - 1][$nindex - 1]) ?
			$matrix[$oindex - 1][$nindex - 1] + 1 : 1;
			if($matrix[$oindex][$nindex] > $maxlen){
				$maxlen = $matrix[$oindex][$nindex];
				$omax = $oindex + 1 - $maxlen;
				$nmax = $nindex + 1 - $maxlen;
			}
		}
	}
	if($maxlen == 0) return array(array(DELETE_IDENTIFIER=>$old, INSERT_IDENTIFIER=>$new));
	return array_merge(
			index_diff(array_slice($old, 0, $omax), array_slice($new, 0, $nmax)),
			array_slice($new, $nmax, $maxlen),
			index_diff(array_slice($old, $omax + $maxlen), array_slice($new, $nmax + $maxlen)));
}

function get_story_part_meta($toc, $part_id, $story_id) {
	//First create a flat array with part id as the key
	$raw_parts = array();
	recreateRawTOCParts($toc, $raw_parts);
	$max_order = 0;
	$children = array();
	if($part_id == $story_id) {
		$children = $toc;
	} else if(isset($raw_parts[$part_id]) && isset($raw_parts[$part_id][TOC_CHILDREN_KEY])) {
		$children = $raw_parts[$part_id][TOC_CHILDREN_KEY];
	}
	foreach ($children as $ch) {
		if(isset($ch[TOC_DATA_KEY]['display_order']) && $max_order < (int) $ch[TOC_DATA_KEY]['display_order']) {
			$max_order = (int) $ch[TOC_DATA_KEY]['display_order'];
		}
	}
	$path=array();
	$tmp_part = $part_id;
	while($tmp_part && $tmp_part != $story_id){
		array_unshift($path, $raw_parts[$tmp_part][TOC_DATA_KEY]['title']);
		$tmp_part = $raw_parts[$tmp_part][TOC_DATA_KEY]['parent_part_id'];
	}
	$parent_part_id =false;
	if(isset($raw_parts[$part_id][TOC_DATA_KEY]['parent_part_id'])) {
		$parent_part_id = $raw_parts[$part_id][TOC_DATA_KEY]['parent_part_id'];
	}
	
	return array($path, $max_order+1, $parent_part_id);
}

function recreateRawTOCParts($tocNodes, &$raw_parts) {
	if(empty($tocNodes) || !is_array($tocNodes)) {
		return;
	}
	foreach ($tocNodes as $ch) {
		$raw_parts[$ch[TOC_DATA_KEY]['part_id']] = $ch;
		recreateRawTOCParts($ch[TOC_CHILDREN_KEY], $raw_parts);
	}
}

function utf8ize( $mixed ) {
		
	if (is_array($mixed)) {
	
		foreach ($mixed as $key => $value) {
	
			$mixed[$key] = utf8ize($value);
	
		}
	
	} elseif (is_string($mixed)) {
	
		return mb_convert_encoding($mixed, "UTF-8", "UTF-8");
	
	}

	return $mixed;

}