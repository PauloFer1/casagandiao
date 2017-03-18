<?php



?>

<h1 class="news"><? echo __('Newsletters Sent');?></h1>


<h2 class="recent"><span><? echo __('List of all newsletters');?></span></h2>


<div class="box">
	<table cellpadding="5">
		<tr>
			<th><? echo __('Subject of Email');?>:</th>
			<th><? echo __('Envoy');?>:</th>
			<th><? echo __('Sent to');?>:</th>
			<th><? echo __('Viewed by');?>:</th>
			<th><? echo __('UnSubscribed')?>:</th>
			<th><? echo __('Bounces');?>:</th>
			<th><? echo __('Actions');?>:</th>
		</tr>
		<?php
		$newsletters = $newsletter->get_newsletters($db);
		foreach($newsletters as $n){ 
			$n = $newsletter->get_newsletter($db,$n['newsletter_id']);
			$sends = $newsletter->get_newsletter_sends($db,$n['newsletter_id']);
			?>
		<tr>
			<td>
				<?php echo $n['subject'];?>
			</td>
			<td>
				&lt;<?php echo $n['from_name'];?>&gt; <?php echo $n['from_email'];?> 
			</td>
			<td>
				<?php
				foreach($sends as $send){ 
					$send = $newsletter->get_send($db,$send['send_id']);
					?>
					
					<?php echo count($send['sent_members']);?> membros on <?php echo date("Y-m-d",$send['start_time']);?> <br>
					
				<?php } ?>
			</td>
			<td>
				<?php
				foreach($sends as $send){ 
					$send = $newsletter->get_send($db,$send['send_id']);
					?>
					
					<?php echo count($send['opened_members']);?> membros <br>
					
				<?php } ?>
			</td>
			<td>
				<?php
				foreach($sends as $send){ 
					$send = $newsletter->get_send($db,$send['send_id']);
					?>
					
					<?php echo count($send['unsub_members']);?> membros <br>
					
				<?php } ?>
			</td>
			<td>
				<?php
				foreach($sends as $send){ 
					$send = $newsletter->get_send($db,$send['send_id']);
					?>
					
					<?php echo count($send['bounce_members']);?> membros <br>
					
				<?php } ?>
			</td>
			<td>
			<!--	<a href="plugin/newsletter?p=open&newsletter_id=<?php echo $n['newsletter_id'];?>">Stats/Send</a>-->
				<a href="plugin/newsletter?p=create&newsletter_id=<?php echo $n['newsletter_id'];?>"><? echo __('Edit');?></a>
			</td>
		</tr>
		<?php } ?>
		
	</table>
</div>


