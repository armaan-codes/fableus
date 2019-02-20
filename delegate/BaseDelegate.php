<?php
class BaseDelegate
{	
	function __load_models($model_objs)
	{
	
		foreach ($model_objs as $model => $model_obj)
			$this->{$model} = $model_obj;
	
	}

	function random_password()
	{
	
		$alphabet = 'abcdefghijklmnopqrstuvwxyz1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	
		$pass = array();
	
		$alphaLength = strlen($alphabet) - 1;
	
		for ($i = 0; $i < 12; $i++) {
	
			$n = rand(0, $alphaLength);
	
			$pass[] = $alphabet[$n];
	
		}
	
		return implode($pass);
	
	}

	function upload_image($image, $location = false)
	{

		if($image['error'] == 0){
		
			$name = str_shuffle(substr(md5($image['name']), 0, 10));
		
			$ext = strtolower(substr($image['name'], strpos($image['name'], '.') + 1));
		
			$name = $name.'.'.$ext;
		
			$type = $image['type'];
		
			$size = $image['size'];
		
			$tmp_name = $image['tmp_name'];
			
			if ($location) {
		
				$target_folder = "resource/img/" . $location . "/" . $name;
		
			} else {
		
				$target_folder = "resource/img/".$name;
		
			}

			if(move_uploaded_file($tmp_name, $target_folder)) {
		
				return $name;
		
			}
		
		}
		
		return false;
	
	}
}