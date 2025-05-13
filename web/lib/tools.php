<?php
function paragraphing($text, $max_line_len){
	$res_lines = array();
	$text = str_replace("\r\n", "\n", $text);
	$paras = explode(PHP_EOL, $text);
	foreach ($paras as $para){
		if (strlen($para) <= $max_line_len){
			array_push($res_lines, $para.'#');
			continue;
		}
		else{
			while ($para != ''){
				if (strlen($para) <= $max_line_len){
					array_push($res_lines, $para.'#');
					$para = substr($para, strlen($para));
					continue;
				}
				if ($para[$max_line_len - 1] == ' '){
					$cur_line_len = $max_line_len;
				}
				else{
					$cur_line_len = strrpos(substr($para, 0, $max_line_len), ' ') + 1;
				}
				array_push($res_lines, substr($para, 0, $cur_line_len));
				$para = substr($para, $cur_line_len);
			}
		}
	}
	// $res_lines[count($res_lines)] = substr($res_lines[count($res_lines)], 0, -1);
	// $res_lines[count($res_lines) - 1] = substr($res_lines[count($res_lines) - 1], 0, -1);
	$res_lines[count($res_lines) - 1] = substr_replace($res_lines[count($res_lines) - 1], '&', -1);
	return $res_lines;
}
function rand_str($min = 4, $max = 20, $count = 10){
	$pattern = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789 ';
	$arr_res = [];
	for ($i = 0; $i < $count; $i++){
		$str = '';
		$len = mt_rand($min, $max);
		for ($j = 0; $j < $len; $j++){
			$str .= $pattern[mt_rand(0, 62)];
		}
		array_push($arr_res, $str);
		$str = '';
	}
	return $arr_res;
}