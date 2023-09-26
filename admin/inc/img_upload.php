<?php


 $time=time();
class img_upload {
var $_path;
var $_filename;
var $_thumbs = array();
var $_name;
var $_type;
var $_width;
var $_height;
var $_imageTypes = array("1"=>"gif", "2"=>"jpg", "3"=>"png", "4"=>"jpeg"); //, 4 = SWF, 5 = PSD, 6 = BMP, 7 = TIFF(intel byte order), 8 = TIFF(motorola byte order), 9 = JPC, 10 = JP2, 11 = JPX, 12 = JB2, 13 = SWC, 14 = IFF, 15 = WBMP, 16 = XBM);
var $_error = FALSE;
var $_errMsg = array();

	// set original file parameters
	function img_upload($path,$filename) {
		$this->_path=$path;
		$this->_filename=$filename;
	}
	
	// set original file parameters after uploading
	function setParams($name,$type,$width,$height) {
		if(!empty($name))
			$this->_name = $name;
		if(!empty($type))
			$this->_type = $type;
		if(!empty($width))
			$this->_width = (float)$width;
		if(!empty($height))
			$this->_height = (float)$height;
	}

	// set thumb parameters
	function setThumb($prefix,$wdth,$hght,$ratio=NULL) { // $ratio is width/height from 0.1 to 1
		$wdth = (float)$wdth;
		$hght = (float)$hght;
		$ratio = !empty($ratio)?(float)($ratio/100):NULL;
		$prefix = stripslashes($prefix);
		if(!empty($prefix) && !empty($ratio))
			$this->_thumbs[] = array("prefix"=>$prefix,"ratio"=>$ratio);
		if(!empty($prefix) && !empty($wdth) && !empty($hght))
			$this->_thumbs[] = array("prefix"=>$prefix,"width"=>$wdth,"height"=>$hght);
	}

	// make new image
	function genThumb($newname,$wdth,$hght,$ratio) {
		if(empty($ratio))
		{
			$dims = $this->getNewBounds($this->_width, $this->_height,$wdth,$hght);
			$newh2=$dims['height'];
			$neww2=$dims['width'];
			if($newh2 > $hght || $neww2 > $wdth) {
				$dims = $this->getNewBounds($neww2, $newh2,$wdth,$hght);
				$newh2=$dims['height'];
				$neww2=$dims['width'];
			}
		}
		else
		{
			$neww2=$this->_width*$ratio;
			$newh2=$neww2*($this->_height/$this->_width);
		}

		if(($this->_type==1)||($this->_type==2)){
			if($this->_type==1){
				$srcImg =imagecreatefromgif($this->_name);
				
				$dstImg2 =imagecreatetruecolor($neww2, $newh2);
				imagecopyresampled($dstImg2, $srcImg, 0, 0, 0, 0,$neww2, $newh2,$this->_width, $this->_height);
				imagegif($dstImg2,$this->_path.$newname.".gif");
				
				imagedestroy($srcImg);
				imagedestroy($dstImg2);
			}
			if($this->_type==2){
				$srcImg = imagecreatefromjpeg($this->_name);
				
				$dstImg2 = imagecreatetruecolor($neww2, $newh2);
				imagecopyresampled($dstImg2, $srcImg, 0, 0, 0, 0,$neww2, $newh2,$this->_width, $this->_height);
				imagejpeg($dstImg2,$this->_path.$newname.".jpg");
				
				imagedestroy($srcImg);
				imagedestroy($dstImg2);
		  }
		  if($this->_type==4){
				$srcImg = imagecreatefromjpeg($this->_name);
				
				$dstImg2 = imagecreatetruecolor($neww2, $newh2);
				imagecopyresampled($dstImg2, $srcImg, 0, 0, 0, 0,$neww2, $newh2,$this->_width, $this->_height);
				imagejpeg($dstImg2,$this->_path.$newname.".jpeg");
				
				imagedestroy($srcImg);
				imagedestroy($dstImg2);
		  }
		}
	}
	
	// generate unique file name
	function makeCode() {
		$pars = "13579";
		$impars = "24680";
		for ($x=0; $x < 6; $x++) {
			mt_srand ((double) microtime() * 1000000);
			$pars[$x] = substr($pars, mt_rand(0, strlen($pars)-1), 1);
			$impars[$x] = substr($impars, mt_rand(0, strlen($impars)-1), 1);
		}
		$coded = $pars[0] . $impars[0] .$pars[2] . $pars[1] . $impars[1] . $pars[3] . $impars[3] . $pars[4];
		//die($coded);
		return($coded);
	}

	// calculate newDimensions for new thumb
	function getNewBounds($origWidth,$origHeight,$targWidth,$targHeight) {
		$width2HtRatio = $origWidth/$origHeight;
		$Ht2widthRatio = $origHeight/$origWidth;

		if($origWidth < $targWidth && $origHeight < $targHeight){
			$useWidth =$origWidth;
			$useHeight =$origHeight;
		}
		else if($origWidth > $targWidth){
			$useWidth = $targWidth;
			$useHeight = $useWidth * $Ht2widthRatio;								
		}
		else if($origHeight > $targHeight){			
			$useHeight = $targHeight;											
			$useWidth = $useHeight * $width2HtRatio;
		}
		else if($origWidth < $targWidth){
			$useWidth = $targWidth;
			$useHeight = $useWidth * $Ht2widthRatio;								
		}
		else if($origHeight < $targHeight){			
			$useHeight = $targHeight;											
			$useWidth = $useHeight * $width2HtRatio;
		}
		else{
			$useWidth = $targWidth;
			$useHeight = $targHeight;												
		}
		
		$newDimensions = array (
			'width' => $useWidth,
			'height'=> $useHeight
		);
		return $newDimensions;
	}

	// set error message
	function setErrMsg($errMsg) {
		$this->_errMsg[] = stripslashes($errMsg);
	}
	
	// get error message
	function getErrMsg() {
		return $this->_errMsg;
	}
	
	// process image
	function process($errMsg="") {
		$newname = FALSE;
		if(empty($_FILES["{$this->_filename}"]["name"]) || empty($_FILES["{$this->_filename}"]["tmp_name"]))
		{
			$this->_error = TRUE;
			$this->setErrMsg("Wrong field name or file is not uploaded");
		}
		else
		{
			if(!file_exists($this->_path))
			{
				$this->_error = TRUE;
				$this->setErrMsg("Wrong upload path or directory does not exist");
			}
			else
			{
				$name = $this->_path.$_FILES["{$this->_filename}"]["name"];
				if(!move_uploaded_file($_FILES["{$this->_filename}"]["tmp_name"], $name))
				{
					$this->_error = TRUE;
					$this->setErrMsg("Uploaded file is invalid");
				}
				else
				{
					$imgAttribs = getimagesize($name);
					if(!$imgAttribs)
					{
						$this->_error = TRUE;
						$this->setErrMsg("Uploaded file is invalid or cannot access file");
					}
					else
					{
						list($width, $height, $type, $attr) = $imgAttribs;
						if(!array_key_exists($type,$this->_imageTypes))
						{
							$this->_error = TRUE;
							$this->setErrMsg("Invalid image type");
						}
						else
						{
							$nume=$this->makeCode();
							$nume.= rand(0,1000);
							//$nume.=$i
							$ext = $this->_imageTypes[$type];
							$newname = $nume.".".$ext;
							if(!rename($name,$this->_path.$newname))
							{
								$this->_error = TRUE;
								$this->setErrMsg("Cannot rename file");
							}
							else
							{
								$this->setParams($this->_path.$newname,$type,$width,$height);
								
								foreach($this->_thumbs as $thumb)
								{
									$prefix = $thumb["prefix"];
									$thumbName = $prefix.$nume;
									$ratio = $thumb["ratio"];
									$wdth = $thumb["width"];
									$hght = $thumb["height"];
									$this->genThumb($thumbName,$wdth,$hght,$ratio);
								}
							}
						}
					}
				}
			}
		}

		if($this->_error)
			@unlink($name);
		$errMsg = $this->getErrMsg();
		return $newname;
	}
}
?>