<?php



$ffmpeg = 'E:\Development\path\ffmpeg\bin\ffmpeg.exe';

foreach ([4,5,6,7,8] as $key => $value) 
{
	$i = 'uploads/img/online-0'.$value.'.webp';
	$nm = 'online-0'.$value.'.webp';
	shell_exec($ffmpeg.' -i '.$i.' -vf scale="80:-1" '.$nm);
}

shell_exec($ffmpeg.' -i uploads/img/operator.webp -vf scale="80:-1" operator.webp');

return null;

?>