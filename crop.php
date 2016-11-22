<?php
/**
*	@Author Jacoob
*   @Time 2016-11-22
*   @Desc 对图片进行两次裁剪，第一次按比例缩放，第二次在缩放的基础上截取从头部开始固定宽高的图片生成缩列图
*    基于php-GD库
**/
	error_reporting(0);
	ini_set('memory_limit','500M');
	//header('Content-Type: image/jpeg');

	$dir = __DIR__.DIRECTORY_SEPARATOR ."images";

	//读取文件夹下所有的图片
	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				if($file != '.' && $file != '..' ){
					$tmp = explode('.', $file);
					$dst_name = $dir.DIRECTORY_SEPARATOR.$tmp[0].'.thumb.'.$tmp[1];

					if(in_array( $tmp[1], array('jpg','png','gif') )){
						create_thumb( $dir.DIRECTORY_SEPARATOR.$file, $dst_name );
					}
				}
			} 
			closedir($dh);
		}
	}
	
	/**
	 * 按比例生成缩略图
	**/
	function create_thumb($src_name,$dst_name){
		// 打开文件
		$src_img = open_image($src_name);
		// 计算新尺寸
		$thumb_h=0;
		$thumb_w=0;
		$old_x=imageSX($src_img);
		$old_y=imageSY($src_img);

		$new_w = 250;
		$new_h = ceil($new_w * ($old_y/$old_x));
		// 创建画板
		$dst_img=imagecreatetruecolor($new_w,$new_h);
		
		// 新尺寸拷贝图片到画板
		imagecopyresampled($dst_img,$src_img,0,0,0,0,$new_w,$new_h,$old_x,$old_y);
		imagefilter($dst_img,IMG_FILTER_SMOOTH,-27.9);	

		if($new_h < 250){
			$new_dst_img=imagecreatetruecolor(250,$new_h);
			imagecopyresampled($new_dst_img,$dst_img,0,0,0,0,250,$new_h,250,$new_h);
		}else{
			$new_dst_img=imagecreatetruecolor(250,250);
			imagecopyresampled($new_dst_img,$dst_img,0,0,0,0,250,250,250,250);
		}
		// 保存缩略图为图片
		imagejpeg($new_dst_img,$dst_name);

		// 释放内存
		imagedestroy($dst_img);
		imagedestroy($src_img); 
		imagedestroy($new_dst_img);                                                                                                                                                
	}

	/**
	 * 从文件路径中读取文件返回一个资源
	**/
	function open_image ($file) {
	  	$size=getimagesize($file);
	    switch($size["mime"]){
	        case "image/jpeg":
	        	$im = imagecreatefromjpeg($file); //jpeg file
	        	break;
	        case "image/gif":
	        	$im = imagecreatefromgif($file); //gif file
	        	break;
	        case "image/png":
	        	$im = imagecreatefrompng($file); //png file
	        	break;
	        default:
	           $im=false;
	           break;
	   }
	   return $im;
	}
?>