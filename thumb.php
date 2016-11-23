<?php
/**
*	@Author Jacoob
*   @Time 2016-11-22
*   @Desc 生成缩略图
*   基于php-GD库
**/
	error_reporting(0);
	ini_set('memory_limit','500M');
	//header('Content-Type: image/jpeg');
	$dir = __DIR__.DIRECTORY_SEPARATOR ."images";

	//读取文件夹下所有的图片
	$file = isset($_GET['f']) ? $_GET['f'] : false;
	if( preg_match('/.thumb.(jpg|png|gif)/i', $file)){
		create_thumb( $dir.DIRECTORY_SEPARATOR.$file, 200);
	}
				
	
	/**
	 * 按比例生成缩略图
	**/
	function create_thumb($src_name,$width){
		// 打开文件
		$src_img = open_image($src_name);
		// 计算新尺寸
		$thumb_h=0;
		$thumb_w=0;
		$old_x=imageSX($src_img);
		$old_y=imageSY($src_img);

		$new_w = $width;
		$new_h = ceil($new_w * ($old_y/$old_x));
		// 创建画板
		$dst_img=imagecreatetruecolor($new_w,$new_h);
		
		// 新尺寸拷贝图片到画板
		imagecopyresampled($dst_img,$src_img,0,0,0,0,$new_w,$new_h,$old_x,$old_y);
		imagefilter($dst_img,IMG_FILTER_SMOOTH,-27.9);	

		// 保存缩略图为图片
		imagejpeg($dst_img,$src_name);

		// 释放内存
		imagedestroy($dst_img);
		imagedestroy($src_img);                                                                                                                                     
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