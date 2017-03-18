<?php
if($_GET['lastid']){
	if($posts = $db->get_results("SELECT id,text FROM posts WHERE id < ".$db->escape($_GET['lastid'])." ORDER BY id DESC LIMIT 6")) {
		foreach($posts as $post) {
			echo '<li class="postitem" id="'.$post->id.'">'.$post->text.'</li>';
		}
	}
}
?>