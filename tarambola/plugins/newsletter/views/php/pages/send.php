<?php
$send_id = (int)$_REQUEST['send_id'];
if(!$send_id){
	// basic error checking.
	echo 'Por favor volte atrás e seleccione uma newsletter';
}


if(isset($_REQUEST['pause'])){
	$newsletter->pause_send($db,$send_id);
}
if(isset($_REQUEST['unpause'])){
	$newsletter->un_pause_send($db,$send_id);
}
$send_data = $newsletter->get_send($db,$send_id);
$newsletter_id = $send_data['newsletter_id'];
$newsletter_data = $newsletter->get_newsletter($db,$newsletter_id);


if(isset($_REQUEST['process'])){
	
	ob_end_clean();
	?>
	<html>
	<head>
	<title>A enviar...</title>
	<script language="javascript" src="<?php echo URL_PUBLIC; ?>tarambola/plugins/newsletter/views/layout/js/jquery.js"></script>
	</head>
	<body>
	<?php
	@set_time_limit(0);
	
	if($send_data['start_time'] > time()){
		?>
		<script language="javascript">
    	jQuery('#sent_to',window.parent.document).html('O Envio da newsletter foi agendado para: <?php echo date('d/m/Y',$send_data['start_time']);?>');
    	</script>
		<?php
		exit;
	}
	
	$batch_limit = (int)$newsletter->settings['burst_count'];
	if(!$batch_limit)$batch_limit = 10; // default 10.
	
		$result = array();
		$result['status'] = true;
		$sent_to = count($send_data['sent_members']);
		$batch_count = 0;
		foreach($send_data['unsent_members'] as $unsent_member){ 
			$result = $newsletter->send_out_newsletter($db,$send_id,$unsent_member['member_id']);
			
			if($result['status']){
				$batch_count++;
				$sent_to++;
			}else{
				$sent_to = $result['message'];
			}
			?>
			<script language="javascript">
	    	jQuery('#sent_to',window.parent.document).html('<?php echo $sent_to;?>');
	    	</script>
	    	
	    	<?php 
	    	ob_flush();
	    	flush();
	    	
	    	if(!$result['status']){
	    		// break on fail to send
	    		break;
	    	}
			
	    	if($batch_count >= $batch_limit){
	    		if(_DEMO_MODE)sleep(1);
				break;
			}
		
		} 
		
		if($result['status']){
		?>
	    
	    <script language="javascript">
	    
	    <?php
	    
	    $send_data = $newsletter->get_send($db,$send_id);
	    if(!count($send_data['unsent_members']) ){
	    	$newsletter->send_complete($db,$send_id);
	    	?>
	    // if complete.
	    	window.parent.location.href='plugin/newsletter?p=send&send_id=<?php echo $send_id;?>';
	    <?php }else{ ?>
		    setTimeout(function(){window.location.href='plugin/newsletter?p=send&send_id=<?php echo $send_id;?>&process=true';},1000);
	    <?php } ?>
		</script>
	
		<?php
		}
	?>
	</body>
	</html>
	<?php
		exit;
}else{

?>

<h1 class="news">Send</h1>


<h2 class="recent"><span>A Enviar Newsletter: <?php echo $newsletter_data['subject'];?></span></h2>

<p>Por favor não feche a janela enquanto não aparecer "COMPLETO" em baixo.</p>

<?php
if($send_data['start_time'] > time()){
	?>

	<div class="box">
	<div style="font-size:20px; padding:20px;"> 
	Esta newsletter foi agendada para <?php echo date('d/m/Y',$send_data['start_time']);?>
	</div>
	</div>
	<?php
		
}else{ ?>
		
	<div class="box">
		<?php if($send_data['status'] == '3'){ 
			$newsletter->send_complete($db,$send_id); // quick hack to fix a half completed send.
			?>
			<div style="font-size:20px; padding:20px;"> COMPLETO!</div>
		<?php }else{ ?>
			<?php if($send_data['status'] == '6'){ ?>
			<div style="font-size:20px; padding:20px;"> Pausa...</div>
			<?php }else{ ?>
			<div style="font-size:20px; padding:20px;"> A enviar...</div>
			<?php } ?>
		<?php } ?>
		
		<div style="font-size:20px; padding:20px;"> Sent to <span id="sent_to"><?php echo count($send_data['sent_members']);?></span> out of <span id="sent_total"><?php echo count($send_data['unsent_members']) + count($send_data['sent_members']);?></span> members</div>
		
		<div style="padding:20px;">
			<?php if($send_data['status'] == '3'){ ?>
			<?php }else{ ?>
				<?php if($send_data['status'] == '6'){ ?>
				<a href="plugin/newsletter?p=send&send_id=<?php echo $send_id;?>&unpause=true">Continuar a enviar</a>
				<?php }else{ ?>
				<a href="plugin/newsletter?p=send&send_id=<?php echo $send_id;?>&pause=true">Pausar envio</a>
				<?php } ?>
			<?php } ?>
		</div>
	</div>
	
	<?php if($send_data['status'] == '1'){ ?>
	<iframe src="about:blank" id="send_iframe" name="send_iframe" width="0" height="0" style="display:none"></iframe>
	<script language="javascript">
	
	function send_mailout(){
		jQuery('#send_iframe').attr('src','plugin/newsletter?p=send&send_id=<?php echo $send_id;?>&process=true');
		
	}
	jQuery(window).ready(function(){
		setTimeout(send_mailout,3000);
	});
	</script>

<?php } ?>
<?php } ?>
<?php } ?>