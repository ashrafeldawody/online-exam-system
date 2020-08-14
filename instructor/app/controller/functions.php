<?php
include_once 'autoloader.inc.php';

function correctAnswer($answer)
{
  if (substr($answer, 0, 2) === '#!')
    return 1;
  else
    return 0;
}

function time_elapsed_string($ptime)
{
    $etime = time() - $ptime;

    if ($etime < 1)
    {
        return '0 seconds';
    }

    $a = array( 365 * 24 * 60 * 60  =>  'year',
                 30 * 24 * 60 * 60  =>  'month',
                      24 * 60 * 60  =>  'day',
                           60 * 60  =>  'hour',
                                60  =>  'minute',
                                 1  =>  'second'
                );
    $a_plural = array( 'year'   => 'years',
                       'month'  => 'months',
                       'day'    => 'days',
                       'hour'   => 'hours',
                       'minute' => 'minutes',
                       'second' => 'seconds'
                );

    foreach ($a as $secs => $str)
    {
        $d = $etime / $secs;
        if ($d >= 1)
        {
            $r = round($d);
            return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
        }
    }
}

function deleteImage($url) {
    $path = '../../../style/images/uploads/' . basename($url);
    @unlink($path);
}
function uploadFile($tmpName) {
  	$imageTypes = ["image/jpeg", "image/jpg", "image/png", "image/gif"];
    $info = getimagesize($tmpName);
    if(!in_array($info['mime'], $imageTypes)){
        return false;
    }
  $pictureName = time(). rand(0,999999999);
	$location = "../../../style/images/uploads/";
	compressImage($tmpName,$location.$pictureName.'.jpg',30);
	return $pictureName;
}
function compressImage($source, $destination, $quality) {
  $info = getimagesize($source);
  if ($info['mime'] == 'image/jpeg')
    $image = imagecreatefromjpeg($source);

  elseif ($info['mime'] == 'image/gif')
    $image = imagecreatefromgif($source);

  elseif ($info['mime'] == 'image/png')
    $image = imagecreatefrompng($source);
  imagejpeg($image, $destination, $quality);
}



 ?>
