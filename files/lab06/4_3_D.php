<?php
$content = file_get_contents("http://thethao.vnexpress.net/");
$pattern = '/<div class="title_news">.*<\/div>/imsU';

preg_match_all($pattern, $content, $arr);

echo "<pre>";
print_r($arr);
echo "</pre>";
?>
