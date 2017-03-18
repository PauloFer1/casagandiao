<?php
$page_title = "Dashboard";
?>

<h1 class="news"><? echo __('Newsletter Dashboard'); ?></h1>


<h2 class="recent"><span><? echo __('Recent Send');?>:</span></h2>

<div class="box">
	<table cellpadding="5">
		<tr>
			<th><? echo __('Date of Submission');?></th>
			<th><? echo __('Subject of Email');?></th>
			<th><? echo __('Envoy');?></th>
			<th><? echo __('Sent to');?></th>
			<th><? echo __('Opened by');?></th>
			<th><? echo __('Subscribed')?></th>
			<th><? echo __('Bounces');?></th>
			<th><? echo __('Actions');?></th>
		</tr>
		<?php
		$past_sends = $newsletter->get_past_sends($db);
		$x=0;
		foreach($past_sends as $send){ 
			if($x++>5)break;
			$n = $newsletter->get_newsletter($db,$send['newsletter_id']);
			$send = $newsletter->get_send($db,$send['send_id']);
			?>
		<tr>
			<td>
				<?php echo date("Y-m-d H:i:s",$send['start_time']);?>
			</td>
			<td>
				<?php echo $n['subject'];?>
			</td>
			<td>
				&lt;<?php echo $n['from_name'];?>&gt; <?php echo $n['from_email'];?> 
			</td>
			<td>
				<?php echo count($send['sent_members']);?> membros
			</td>
			<td>
				<?php echo count($send['opened_members']);?> membros
			</td>
			<td>
				<?php echo count($send['unsub_members']);?> membros
			</td>
			<td>
				<?php echo count($send['bounce_members']);?> membros
			</td>
			<td>
				<a href="plugin/newsletter?p=open&newsletter_id=<?php echo $n['newsletter_id'];?>">Abrir Newsletter</a> |
				<!--<a href="plugin/newsletter?p=stats&newsletter_id=<?php echo $n['newsletter_id'];?>&send_id=<?php echo $send['send_id'];?>">Ver Estatísticas</a> -->
			</td>
		</tr>
		<?php } ?>
		
	</table>
</div>


<h2 class="recent"><span><? echo __('Recent Members');?>:</span></h2>

<div class="box">
	<table cellpadding="5">
		<tr>
			<th><? echo __('Email');?></th>
			<th><? echo __('First Mame');?></th>
			<th><? echo __('Last Mame');?></th>
			<th><? echo __('Data Adhesion');?></th>
			<th><? echo __('Number of Sent');?></th>
			<th><? echo __('Number of Open');?></th>
			<th><? echo __('Number of  Bounces');?></th>
			<th><? echo __('Groups');?></th>
			<th><? echo __('Campaigns');?></th>
			<th><? echo ('Actions');?></th>
		</tr>
		<?php
		$groups = $newsletter->get_groups($db);
		$members = $newsletter->get_members($db,false,true,5);
		//foreach($members as $member){ 
		while($member = mysql_fetch_assoc($members)){ 
			$member = $newsletter->get_member($db,$member['member_id']);
			?>
		<tr>
				<td>
					<?php echo $member['email'];?>
				</td>
				<td>
					<?php echo $member['first_name'];?>
				</td>
				<td>
					<?php echo $member['last_name'];?>
				</td>
				<td>
					<?php echo $member['join_date'];?>
				</td>
				<td>
					<?php echo count($member['sent']);?> newsletters
				</td>
				<td>
					<?php echo count($member['opened']);?> newsletters
				</td>
				<td>
					<?php echo count($member['bounces']);?> times
				</td>
				<td>
					<?php
					$print = '';
					foreach($member['groups'] as $group_id){
						$print .=  '<a href="plugin/newsletter?p=groups&edit_group_id='.$group_id.'">';
						if(isset($search['group_id'][$group_id])){
							$print .= '<span style="background-color:#FFFF66">';
							$print .= $groups[$group_id]['group_name'];
							$print .= '</span>';
						}else{
							$print .= $groups[$group_id]['group_name']."";
						}
						$print .= '</a>,';
					}
					echo rtrim($print,",");
					?>
				</td>
				<td>
					<?php
					$print = '';
					foreach($member['campaigns'] as $campaign){
						$print .=  '<a href="plugin/newsletter?p=campaign_open&campaign_id='.$campaign['campaign_id'].'">';
						$print .= $campaign['campaign_name']."";
						$print .= '</a>,';
					}
					echo rtrim($print,",");
					?>
				</td>
				<td>
					<a href="plugin/newsletter?p=members&edit_member_id=<?php echo $member['member_id'];?>"><? echo __('Edit Member');?></a> |
					<a href="plugin/newsletter?p=members&delete_member_id=<?php echo $member['member_id'];?>" onclick="if(confirm('Really delete this member?'))return true;else return false;" style="color:#FF0000;"><? echo __('Delete');?></a>
				</td>
			</tr>
		<?php } ?>
		
	</table>
</div>



<h2 class="recent"><span><? echo __('Shipping Pending');?>:</span></h2>

<div class="box">
	<table cellpadding="5">
		<tr>
			<th><? echo __('Newsletter'); ?></th>
			<th><? echo __('Date of Submission');?></th>
			<th><? echo __('Progress');?></th>
			<th><? echo __('Actions');?></th>
		</tr>
		<?php
		$sends = $newsletter->get_pending_sends($db);
		foreach($sends as $send){
			?>
			<tr>
				<td><?php echo $send['subject'];?></td>
				<td><?php echo $send['start_date'];?></td>
				<td><?php echo $send['progress'];?></td>
				<td><a href="plugin/newsletter?p=send&send_id=<?php echo $send['send_id'];?>"><? echo __('Continue to send');?></a></td>
			</tr>
			<?php
		}
		?>
	</table>
		
</div>