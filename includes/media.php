<?php
	/**
	 * Overfør et enkelt billece til serveren
	 *
	 * @param mixed $inputFieldName
	 * @param string $folder default '../media'
	 * @param array $mimeType default ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/bmp']
	 * @return array
	 */
	function mediaImageUploader($inputFieldName, $mimeType = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/bmp'], $folder = '../assets/images'){
		$uploadError = array(
			1 => 'Filens størrelse overskrider \'upload_max_filesize\' directivet i php.ini.',
			2 => 'Filen størrelse overskride \'MAX_FILE_SIZE\' directivet i HTML formen.',
			3 => 'File blev kun delvis uploadet.',
			4 => 'Der blev ikke uploadet et billede!',
			6 => 'Kunne ikke finde \'tmp\' mappen.',
			7 => 'Kunne ikke gemme filen på disken.',
			8 => 'A PHP extension stopped the file upload.'
		); 

		if($_FILES[$inputFieldName]['error'] === 0){
			$image = $_FILES[$inputFieldName];
			if(!in_array($image['type'], $mimeType)){
				return [
					'code' => false,
					'msg' => 'Ikke tiladt filtype'
				];
			}
			if (!file_exists($folder)) {
				mkdir($folder, 0755, true);
			}
			$imageName = time() . '_' . $image['name'];
			if(move_uploaded_file($image['tmp_name'], $folder . '/' . $imageName)){
				return [
					'code' => true,
					'type' => $image['type'],
					'name' => $imageName
				];
			}
		} else {
			return [
				'code' => false,
				'msg' => $uploadError[$_FILES[$inputFieldName]['error']]
			];
		}
	}






		/**
	 * Overfør flere billeder til serveren
	 *
	 * @param mixed $inputFieldName
	 * @param string $folder default '../media'
	 * @param array $mimeType default ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/bmp']
	 * @return array
	 */
	function multiImageUploader($inputFieldName, $mimeType = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/bmp'], $folder = '../media'){
		$uploadError = array(
			1 => 'Filens størrelse overskrider \'upload_max_filesize\' directivet i php.ini.',
			2 => 'Filen størrelse overskride \'MAX_FILE_SIZE\' directivet i HTML formen.',
			3 => 'File blev kun delvis uploadet.',
			4 => 'Filen blev ikke uploaded.',
			6 => 'Kunne ikke finde \'tmp\' mappen.',
			7 => 'Kunne ikke gemme filen på disken.',
			8 => 'A PHP extension stopped the file upload.'
		); 
		if (!file_exists($folder)) {
			mkdir($folder, 0755, true);
		}
		$image = $_FILES[$inputFieldName];
		if(!empty($image)) {
			$image_desc = reArrayFiles($image);
			// print_r($image_desc);
			
			foreach($image_desc as $val) {
				if($val['error'] === 0){

					if(!in_array($val['type'], $mimeType)){
						$return[] = [
							'code' => false,
							'msg' => $val['name'] . ' har en ikke tiladt filtype'
						];
						// print_r('msg');
						// return $return;
						// break;
					} else {
					
						$imageName = time() . '_' . $val['name'];
						// move_uploaded_file($val['tmp_name'], $folder . '/' . $imageName);
						if(move_uploaded_file($val['tmp_name'], $folder . '/' . $imageName)){
							$return[] = [
								'code' => true,
								'type' => $val['type'],
								'name' => $imageName
							];

						}
					}
				} else {
					$return[] = [
						'code' => false,
						'msg' => $uploadError[$val['error']]
					];
					return $return;
					
				}
			} return $return;
		}
	}

function reArrayFiles($file)
{
	$file_ary = array();
	$file_count = count($file['name']);
	$file_key = array_keys($file);
	
	for($i=0;$i<$file_count;$i++)
	{
		foreach($file_key as $val)
		{
			$file_ary[$i][$val] = $file[$val][$i];
		}
	}
	return $file_ary;
}

/**
 * This function resizes an image to $fitInWidth and $fitInHeight, but doesn't distort the image proportions
 * 
 * $path: The full path to the image. Ex.: "/images/my_image.jpg"
 * 
 * $newName: If you don't want to override the original image, you can specify a new name.
 * 
 * $fitInWidth:The height in pixels that you want to fit the image in
 * 
 * $fitInHeight: The height in pixels that you want to fit the image in
 * 
 * $jpegQuality: If the image is a jpeg, the function will save 
 * the resized jpeg with the specified quality from 1 to 100
 */
// if (!file_exists($folder)) {
//     mkdir($folder, 0755, true);
// }

function resize_image($path, $fitInWidth, $fitInHeight, $newName = '', $jpegQuality = 100)
{
	list($width, $height, $type) = getimagesize($path);//Getting image information
 
	$scaleW = $fitInWidth/$width;
	$scaleH = $fitInHeight/$height;
	if($scaleH > $scaleW)
	{
		$new_width = $fitInWidth;
		$new_height = floor($height * $scaleW);
	}
	else
	{
		$new_height = $fitInHeight;			
		$new_width = floor($width * $scaleH);
	}
	$new_path = $newName == '' ? $path : dirname($path) . '/' . $newName;
    // $new_path = '../media/' . $newName;
    if (!file_exists(dirname($new_path))) {
        mkdir(dirname($new_path), 0755, true);
    }
    
	if($type == IMAGETYPE_JPEG)//If image is jpeg
	{
		$image_now = imagecreatefromjpeg($path);//Get image from path
		$image_new = imagecreatetruecolor($new_width, $new_height);//Create new image from scratch
		//Copy image from path into new image ($image_new) with new sizes
		imagecopyresampled($image_new, $image_now, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		imagejpeg($image_new, $new_path, $jpegQuality);
	}
	else if($type == IMAGETYPE_GIF)//If image is gif
	{
		$image_now = imagecreatefromgif($path);
		$image_new = imagecreatetruecolor($new_width, $new_height);
		imagecopyresampled($image_new, $image_now, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		imagegif($image_new, $new_path);
	}
	else if($type == IMAGETYPE_PNG)//If image is png
	{
	    $image_now = imagecreatefrompng($path);
	    $image_new = imagecreatetruecolor($new_width, $new_height);
	    //Setting black color as transparent because image is png
	    imagecolortransparent($image_new, imagecolorallocate($image_new, 0, 0, 0));
		imagecopyresampled($image_new, $image_now, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	    imagepng($image_new, $new_path);
	}
	else
	{
		//Image type is not jpeg, gif or png.
	}
	imagedestroy($image_now);
	imagedestroy($image_new);
}