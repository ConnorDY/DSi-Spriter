<?php
//Settings
$directory = 'sprites';

//Functions
function hex2RGB($hexStr, $returnAsString = false, $seperator = ',')
{
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
    $rgbArray = array();
    if (strlen($hexStr) == 6)
	{
		//If a proper hex code, convert using bitwise operation. No overhead... faster
        $colorVal = hexdec($hexStr);
        $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
        $rgbArray['blue'] = 0xFF & $colorVal;
    }
	elseif (strlen($hexStr) == 3)
	{
		//if shorthand notation, need some string manipulations
        $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
        $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
        $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    }
	else
	{
        return false; //Invalid hex color code
    }
    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
}

function saveAvatar($a,$f,$directory)
{
	//Save PNG
	imagepng($a, $directory.'/'.$f.'.png');
	
	//Free Memory
	imagedestroy($a);
}

//Create Image
$avatar = imagecreate(16,16);

$i = 1;

//Loop through Pixels
for ($y = 1; $y <= 16; $y += 1)
{
	for ($x = 1; $x <= 16; $x += 1)
	{
		//Get RGB String
		$rgb = $_POST['p'.$i];
		
		$rgb = hex2RGB($rgb,true);
		
		//Split RGB into individual variables
		list($r, $g, $b) = explode(',',$rgb);
		
		//Get Color
		$c[$i] = imagecolorallocate($avatar, $r, $g, $b);
		
		//Set Pixel
		imagesetpixel($avatar, $x-1, $y-1, $c[$i]);
		
		$i += 1;
	}
}

//Get Filename
$fname = $_POST['nameR'];

//Check if the filename is available
if (file_exists($directory.'/'.$fname.'.png'))
{	
	$r = rand();
	
	while (file_exists($directory.'/'.$r.'.png'))
	{
		$r = rand();
	}
	
	saveAvatar($avatar,$r,$directory);
	
	die('
		That filename was already taken so your file was saved as '.$r.'.png instead.<br/>
		<img src="'.$directory.'/'.$r.'.png" />
	');
}
//Check if the filename is alphanumeric
else if (!ctype_alnum($fname))
{
	$r = rand();
	
	while (file_exists($directory.'/'.$r.'.png'))
	{
		$r = rand();
	}
	
	saveAvatar($avatar,$r,$directory);
	
	die('
		That filename is unacceptable so your file was saved as '.$r.'.png instead.<br/>
		<img src="'.$directory.'/'.$r.'.png" />
	');
}
//If all is good
else
{
	saveAvatar($avatar,$fname,$directory);
	
	die('
		<img src="'.$directory.'/'.$fname.'.png" />
	');
}
?>