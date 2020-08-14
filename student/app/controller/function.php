<?php
function mb_str_word_count($string, $format = 0, $charlist = '[]') {
    $string=trim($string);
    if(empty($string))
        $words = array();
    else
        $words = preg_split('~[^\p{L}\p{N}\']+~u',$string);
    switch ($format) {
        case 0:
            return count($words);
            break;
        case 1:
        case 2:
            return $words;
            break;
        default:
            return $words;
            break;
    }
}
function getClientIP(){
  if (!empty($_SERVER['HTTP_CLIENT_IP'])){
      $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
      $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else {
      $ip_address = $_SERVER['REMOTE_ADDR'];
    }
    return $ip_address;
}
