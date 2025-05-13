<?php
const VALID = true;
include 'lib/mysql.php';
include 'lib/config.php';
include 'lib/tools.php';
if (!isset($_GET['cid'])){
	header("location:?cid=1");
	exit;
}
$cid = $_GET['cid'];
if (!is_numeric($cid) || $cid < 1 || floor($cid) != ceil($cid)){
	$filename = basename(__FILE__);
	if ($filename == 'index.php'){
		header("location:?cid=1");
	}
	else if ($filename == "index_.php"){
		header("location:".basename(__FILE__)."?cid=1");
	}
	exit;
}
$mysql = connect();
$query_content = "SELECT * FROM `content` WHERE `chapter_id` = $cid;";
$result_content = $mysql->query($query_content);
if ($result_content->num_rows != 1){
	header("location:".basename(__FILE__)."?cid=1");
	exit;
}
$data_content = $result_content->fetch_assoc();
$chapter_id = $data_content['chapter_id'];
$chapter = $data_content['chapter'];
$chapter_title = $data_content['chapter_title'];
$chapter_content = $data_content['chapter_content'];
$section_id = $data_content['section_id'];
unset($query_content, $result_content, $data_content);
$query_section = "SELECT * FROM `section` WHERE `section_id` = $section_id;";
$result_section = $mysql->query($query_section);
$data_section = $result_section->fetch_assoc();
$section = $data_section['section'];
$section_title = $data_section['section_title'];
$section_title_chs = $data_section['section_title_chs'];
$volume_id = $data_section['volume_id'];
$num_chapters = $data_section['num_chapters'];
unset($query_section, $result_section, $data_section);
$query_volume = "SELECT * FROM `volume` WHERE `volume_id` = $volume_id";
$result_volume = $mysql->query($query_volume);
$data_volume = $result_volume->fetch_assoc();
$volume = $data_volume['volume'];
$volume_title = $data_volume['volume_title'];
$volume_title_chs = $data_volume['volume_title_chs'];
$book_id = $data_volume['book_id'];
$num_sections = $data_volume['num_sections'];
$volume_style = $data_volume['volume_style'];
unset($query_volume, $result_volume, $data_volume);
$query_book = "SELECT * FROM `book` WHERE `book_id` = $book_id;";
$result_book = $mysql->query($query_book);
$data_book = $result_book->fetch_assoc();
$book_name = $data_book['book_name'];
$book_name_short = $data_book['book_name_short'];
$book_name_full = $data_book['book_name_full'];
$book_name_chs = $data_book['book_name_chs'];
$volume_name = $data_book['volume_name'];
$section_name = $data_book['section_name'];
$chapter_name = $data_book['chapter_name'];
unset($query_book, $result_book, $data_book);

// other info
$query_max_cid = "SELECT MAX(`chapter_id`) FROM `content`;";
$result_max_cid = $mysql->query($query_max_cid);
$data_max_id = $result_max_cid->fetch_array(MYSQLI_NUM);
$max_cid = $data_max_id[0];
if ($cid > $max_cid){
	header("location:./".basename(__FILE__)."?cid=1");
	exit;
}
unset($query_max_cid, $result_max_cid, $data_max_id);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta http-equiv="content-type" content="text/html">
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="Expires" content="0">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<title><?php echo "《$book_name_short {$volume}》第 $chapter $chapter_name";?></title>
	<script src="js/index.js" defer></script>
</head>
<body>
<div class="header-wrap">
	<div class="title">打字游戏</div>
</div>
<div class="select-wrap">
	<div class="select select-book"><?php echo '《'.$book_name_chs.'》';?></div>
	<div class="dropdown dropdown-book">
	<?php
	$query_book_set = "SELECT `book_id`, `book_name_chs`, `done` FROM `book`;";
	$result_book_set = execute($mysql, $query_book_set);
	$book_set = [];
	while($row = mysqli_fetch_assoc($result_book_set)){
		if ($row['done']){
			$query_start_cid = "SELECT MIN(`chapter_id`) FROM `content` WHERE `section_id` = (
				SELECT MIN(`section_id`) FROM `section` WHERE `volume_id` = (
					SELECT MIN(`volume_id`) FROM `volume` WHERE `book_id` = {$row['book_id']}
				)
			);";
			$result_start_cid = $mysql->query($query_start_cid);
			$start_cid = $result_start_cid->fetch_row()[0];	
			$book_info = [
				'book_name_chs' => $row['book_name_chs'],
				'start_cid' => $start_cid,
			];
			array_push($book_set, $book_info);
		}
	}
	foreach ($book_set as $value){
	?>
		<a href="<?php if (basename(__FILE__) != 'index.php')echo basename(__FILE__);echo '?cid='.$value['start_cid'];?>" class="book"><?php echo $value['book_name_chs'];?></a>
	<?php
	}
	?>
	</div>
	<?php
	$query_volume_set = "SELECT `volume_id`, `volume`, `volume_title_chs`, `volume_style`, `volume_cover` FROM `volume` WHERE `book_id` = $book_id;";
	$result_volume_set = $mysql->query($query_volume_set);
	$data_volume_set = $result_volume_set->fetch_all(MYSQLI_ASSOC);
	?>
	<div class="select select-volume" <?php echo $volume_style;?>><?php echo '第 '.$volume.' '.$volume_name;if ($volume_title_chs) echo '《<b>'.$volume_title_chs.'</b>》';?></div>
	<div class="dropdown dropdown-volume" <?php echo $volume_style;?>>
	<?php
	foreach ($data_volume_set as $value){
		$query_start_cid_of_volume = "SELECT MIN(`chapter_id`) FROM `content` WHERE `section_id` = (
			SELECT MIN(`section_id`) FROM `section` WHERE `volume_id` = {$value['volume_id']}
		);";
		$result_start_cid_of_volume = $mysql->query($query_start_cid_of_volume);
		$start_cid_of_volume = $result_start_cid_of_volume->fetch_row()[0];
	?>
		<a class="volume" href="?cid=<?php echo $start_cid_of_volume;?>" style="background-image: url(img/<?php echo $value['volume_cover'];?>)"></a>
	<?php
	}
	?>
	</div>
</div>
<div class="section-wrap">
	<?php
	$query_section_in_volume = "SELECT `section_id`, `section`, `section_title_chs` FROM `section` WHERE `volume_id` = $volume_id;";
	$result_section_in_volume = $mysql->query($query_section_in_volume);
	$data_siv = $result_section_in_volume->fetch_all(MYSQLI_ASSOC);
	foreach ($data_siv as $value){
		$query_start_cid_of_section = "SELECT MIN(`chapter_id`) FROM `content` WHERE `section_id` = {$value['section_id']};";
		$result_start_cid_of_section = $mysql->query($query_start_cid_of_section);
		$data_scof = $result_start_cid_of_section->fetch_row()[0];
	?>
	<a class="section" href="?cid=<?php echo $data_scof;?>">
		<div class="section-text">第<?php echo ' '.$value['section'].' '.$section_name;?></div>
		<?php
		if ($value['section_title_chs']){
		?>
		<div class="section-title"><?php echo $value['section_title_chs'];?></div>
		<?php
		}
		?>
	</a>
	<?php
	}
	?>
	<p style="clear:both"></p>
</div>
<div class="main">
	<div class="chapter-title-wrap">
		<a href="<?php if (basename(__FILE__) != 'index.php')echo basename(__FILE__);?>?cid=<?php if ($cid == 1) echo $cid; else echo $cid - 1;?>" class="nav nav-prev" <?php if($cid == 1)echo 'style="visibility:hidden"';?>><< 上一<?php echo $chapter_name;?></a>
		<div class="chapter-title"><?php echo '<b style="color:rgba(184, 184, 164, 1);">'.$chapter.'</b>.《<span style="color:rgba(255, 180, 50, 1);">'.str_replace(PHP_EOL, '<br>', $chapter_title).'</span>》';?></div>
		<a href="<?php if (basename(__FILE__) != 'index.php')echo basename(__FILE__);?>?cid=<?php if ($cid == $max_cid) echo $cid;else echo $cid + 1;?>" class="nav nav-next" <?php if($cid == $max_cid)echo 'style="visibility:hidden"'?>>下一<?php echo $chapter_name;?> >></a>
	</div>
	<div class="game-setting">
		<label class="label-hide label-hide-space"><input class="hide hide-space" type="checkbox"> 隐藏空格标记</label>
		&nbsp;&nbsp;&nbsp;
		<label class="label-hide label-hide-enter"><input class="hide hide-enter" type="checkbox"> 隐藏换行标记</label>
	</div>
	<div class="typing-area">
		<?php
		$lines = paragraphing($chapter_content, 79);
		$num_lines = count($lines);
		foreach ($lines as $i => $line){
		?>
		<div class="line-wrap <?php if ($i == 0)echo 'active';?>">
			<div class="origin origin-<?php echo $i;?>"><?php
			for ($pos = 0; $pos < strlen($line); $pos++){
				if ($line[$pos] == ' '){
					echo '<span class="chars space show"> </span>';
				}
				else if ($line[$pos] == '#'){
					echo '<span class="end-of-line show">#</span>';
				}
				else if ($line[$pos] == '"'){
					echo '<span class="chars">&quot;</span>';
				}
				else if ($line[$pos] == "&"){
					echo '<span class="end-of-text">&</span>';
				}
				else{
					echo '<span class="chars">'.$line[$pos].'</span>';
				}
			}
			?></div>
			<input type="hidden" class="line-origin" value="<?php echo str_replace('"', '&quot;', $line);?>">
			<input type="text" onpaste="return false" class="answer" maxlength="<?php
			if (strpos($line, '#') !== false || strpos($line, '&') !== false){
				echo strlen($line) - 1;
			}
			else{
				echo strlen($line);
			}
			?>" <?php
			if ($i != 0){
				echo 'disabled';
			}
			?>>
		</div>
		<?php
		}
		?>
	</div>
	<div class="chapter-title-wrap">
	<a href="<?php if (basename(__FILE__) != 'index.php')echo basename(__FILE__);?>?cid=<?php if ($cid == 1) echo 1; else echo $cid - 1;?>" class="nav nav-prev" <?php if($cid == 1)echo 'style="visibility:hidden"';?>><< 上一<?php echo $chapter_name;?></a>
	<button class="debug">调 试</button>	
	<a href="<?php if (basename(__FILE__) != 'index.php')echo basename(__FILE__);?>?cid=<?php if ($cid == $max_cid) echo 1;else echo $cid + 1;?>" class="nav nav-next" <?php if($cid == $max_cid)echo 'style="visibility:hidden"'?>>下一<?php echo $chapter_name;?> >></a>
	</div>
</div>
</body>
</html>