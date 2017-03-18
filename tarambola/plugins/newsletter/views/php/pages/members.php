<?php


if(isset($_REQUEST['save']) && isset($_REQUEST['member_id']) && isset($_REQUEST['mem_email'])){
	
	$fields = array(
		"first_name"=>htmlspecialchars($_REQUEST['mem_first_name']),
		"last_name"=>htmlspecialchars($_REQUEST['mem_last_name']),
		"email"=>htmlspecialchars($_REQUEST['mem_email']),
		"group_id"=>$_REQUEST['group_id'],
		"campaign_id"=>$_REQUEST['campaign_id'],
		"custom"=>$_REQUEST['mem_custom_val'],
	);
	$member_id = $newsletter->save_member($db,$_REQUEST['member_id'],$fields);
	if($member_id){
		
		// save custom fields
		if($_REQUEST['mem_custom_new_val'] && $_REQUEST['mem_custom_new_key']){
			$newsletter->save_member_custom($db,$member_id,$_REQUEST['mem_custom_new_key'],$_REQUEST['mem_custom_new_val'],true);
		}
		ob_end_clean();
		header("Location: plugin/newsletter?p=members&edit_member_id=$member_id");
		exit;
	}
	
}

if(isset($_REQUEST['delete_member_id'])){
	if(_DEMO_MODE){
		echo "DELETE DISABLED IN DEMO MODE SORRY.... email me if you REALLY want something deleted, or just unsubscribe from an email that gets sent to you :) ";
		exit;
	}
	$newsletter->delete_member($db,$_REQUEST['delete_member_id']);
	ob_end_clean();
	header("Location: ".URL_PUBLIC."tarabackend/plugin/newsletter?p=members");
	exit;
}

if(isset($_REQUEST['export'])){
	ob_end_clean();
	header("Content-type: text/csv");
	header('Content-Disposition: attachment; filename="newsletter-members.csv"');
	$all_members = $newsletter->get_members($db);
	echo "Email,First Name,Last Name,\n";
	//foreach($all_members as $member){
	while($member = mysql_fetch_assoc($all_members)){
		$member = $newsletter->get_member($db, $member['member_id']);
		echo $member['email'].','.$member['first_name'].','.$member['last_name'].',';
		echo "\n";
	}
	exit;
}


?>

<h1 class="news">Membros</h1>




<?php
if(isset($_REQUEST['edit_member_id'])){
	$member_id = (int)$_REQUEST['edit_member_id'];
	$member_data = $newsletter->get_member($db,$member_id);
	?>

	<form action="" method="post" id="create_form">
	<input type="hidden" name="save" value="true">
	<input type="hidden" name="member_id" value="<?php echo $member_id;?>">
	<h2><span>Editar Membro</span></h2>
	
	<div class="box">
		<?php include("members_form.php"); ?>
	</div>
	
	</form>
	<h2 class="recent"><span>Actividade recente dos membros</span></h2>
	
	<div class="box">
	
	<table cellpadding="5">
		<tr>
			<th>Send Date</th>
			<th>Email Subject</th>
			<th>Sent From</th>
			<th>Opened</th>
			<th>Bounced</th>
			<th>Actions</th>
		</tr>
		<?php 
		foreach($member_data['sent'] as $send){
			$n = $newsletter->get_newsletter($db,$send['newsletter_id']);
			//$send_data = $newsletter->get_send($db,$send['send_id']);
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
					<?php echo ($send['open_time']>0)?'YES: '.date('Y-m-d H:i:s',$send['open_time']):'NO';?>
				</td>
				<td>
					<?php echo ($send['bounce_time']>0)?'YES: '.date('Y-m-d H:i:s',$send['bounce_time']):'NO';?>
				</td>
				<td>
					<a href="plugin/newsletter?p=open&newsletter_id=<?php echo $n['newsletter_id'];?>">Open Newsletter</a> |
					<a href="plugin/newsletter?p=stats&newsletter_id=<?php echo $n['newsletter_id'];?>&send_id=<?php echo $send['send_id'];?>">View Full Stats</a>
				</td>
			</tr>
			<?php } ?>
			
		</table>
	</div>
<?php
}else{ 
	
	$groups = $newsletter->get_groups($db);
	
	// pull out data ready for processing
	$per_page_limit = (isset($newsletter->settings['per_page']) && (int)$newsletter->settings['per_page']) ? (int)$newsletter->settings['per_page'] : 30;
	$search = array();
	
	/*if(isset($_REQUEST['searchsession'])){
		$search = $_SESSION['_mem_search'];
	}*/
	// so we can add other fields later easier.
	if(isset($_REQUEST['search_fields']) && is_array($_REQUEST['search_fields'])){
		$search = array_merge($search,$_REQUEST['search_fields']);
		//$_SESSION['_mem_search'] = $search;
	}
	
	if(isset($_REQUEST['current-start-letter']) && $_REQUEST['current-start-letter'] == $search['start-letter']){
		// clicked it again twice. 
		unset($search['start-letter']);
	}
	
	/*if(!$search){
		$_SESSION['_mem_search'] = array();
	}*/
	
	//$members = $newsletter->get_members($db,false,false,$per_page_limit,$search); // display onscreen.
	$pagable_members = $newsletter->get_members($db,false,false,false,$search); // used to work out how many pages
	$page_count = ceil( mysql_num_rows($pagable_members) / $per_page_limit) ;
	$all_search = $search;
	unset($all_search['start-letter']);
	$all_members = $newsletter->get_members($db,false,false,false,$all_search); // used for buttons highlight and total mem count
	$member_fields = $newsletter->get_member_fields($db);
?>


	<form action="plugin/newsletter?p=members&search=true" method="post" id="search_form">


<h2 class="recent"><span><? echo __('Search Members');?></span></h2>



<div class="box">
	<input type="hidden" name="ps" value="0" id="ps">
	<table cellpadding="5">
		<tr>
			<td valign="middle">
				<? echo __('Email');?>:
			</td>
			<td valign="middle">
				<input type="text" name="search_fields[email]" id="email" value="" size="12">
			</td>
			<td valign="middle">
				<? echo __('Name')?>:
			</td>
			<td valign="middle">
				<input type="text" name="search_fields[name]" id="namesearch" value="" size="12">
			</td>
			<td valign="middle">
				<? echo __('Groups');?>:
			</td>
			<td valign="middle">
				<?php
				foreach($groups as $group){ ?>
				<input type="checkbox" name="search_fields[group_id][<?php echo $group['group_id'];?>]" <?php echo (isset($search['group_id'][$group['group_id']])) ? 'checked' : '';?> value="true"> <?php echo $group['group_name'];?> <br>
				<?php } ?>
			</td>
			<td valign="middle">
				 <input type="submit" name="search" value="<? echo __('Search');?>!">
			</td>
		</tr>
	</table>
	
</div>

<h2 class="recent"><span><? echo __('List of Members');?> (<?php echo mysql_num_rows($all_members);?>)</span></h2>


<div class="box">
	<table cellpadding="5">
		<thead>
			<tr>
				<td colspan="<?php echo 10+count($member_fields);?>" style="text-align:center">
					
						<?php 
						// we want to group the all_members by first letter.
						$alpha_members = array();
						//foreach($all_members as $mem){
						while($mem = mysql_fetch_assoc($all_members)){
							//$mem = $newsletter->get_member($db,$mem['member_id']);
							$email = trim($mem['email']);
							$alpha_members[strtoupper($email[0])] = true;
						}
						if(mysql_num_rows($all_members) > 0){
							mysql_data_seek($all_members,0);
						}
						for($x=65;$x<=90;$x++){ 
							// find out how many members are in this category.
							?>
							<input type="submit" name="search_fields[start-letter]" style="<?php echo ($search['start-letter'] == chr($x))?'background-color:#FF0000;':'';?> <?php echo (!$alpha_members[chr($x)])?'color:#CCC;':'';?>" value="<?php echo chr($x);?>">
						<?php 
							unset($alpha_members[chr($x)]);
						} 
	?>
						<input type="submit" name="search_fields[start-letter]" style="<?php echo ($search['start-letter'] == '#')?'background-color:#FF0000;':'';?><?php echo (!$alpha_members)?'color:#CCC;':'';?>" value="#">
						<input type="hidden" name="current-start-letter" id="current_start-letter" value="<?php echo $search['start-letter'];?>">
				</td>
			</tr>
			<tr>
				<th><? echo __('Email');?> </th>
				<th><? echo __('First Name')?></th>
				<th><? echo __('Last Name')?></th>
				<?php
				foreach($member_fields as $member_field){
				?> <th><?php echo $member_field['field_name'];?></th> 
				<?php
				}
				?>
				<th><? echo __('Data Adhesion');?></th>
				<th><? echo __('Number of Sent');?></th>
				<th><? echo __('Number views'); ?></th>
				<th><? echo __('Bounces');?></th>
				<th><? echo __('Group Newsletter');?></th>
				<th><? echo __('Campaigns');?></th>
				<th><? echo __('Sync');?></th>
				<th><? echo ('Actions');?></th>
			</tr>
		</thead>
		<tbody>
		<?php
		
		if(isset($_REQUEST['download_as_csv'])){
			// quick hack for csv download..
			// good ol output buffering :D
			ob_end_clean();
			
			header("Content-type: text/csv");
			header('Content-Disposition: attachment; filename="newsletter-export.csv"');
			
			echo '"Email","First Name","Last Name"';
			$member_fields = $newsletter->get_member_fields($db);
			foreach($member_fields as $member_field){
				echo ',"'.$member_field['field_name'].'"';
			}
			echo  ',"Join Date","Number Sent","Number Opened","Bounces",';
			foreach($groups as $group){ 
				echo '"'.$group['group_name'] . '",';
			}
			echo "\n";
			//foreach($all_members as $member){ 
			while($member = mysql_fetch_assoc($all_members)){
				$member = $newsletter->get_member($db,$member['member_id']);
				echo '"'.$member['email'] . '","' . $member['first_name'] . '","' . $member['last_name'] .'"';
				foreach($member_fields as $member_field){
					echo ',"'.$member['custom'][$member_field['member_field_id']]['value'].'"';
				}
				echo ',"' . $member['join_date'] . '","' .count($member['sent']) . '","' . count($member['opened']) . '","' . count($member['bounces']) . '",';
				foreach($groups as $group){ 
					$has = false;
					// EWW SLOW!! oh well, will fix later... in a hurry.
					foreach($member['groups'] as $group_id){
						if($group_id == $group['group_id']){
							$has = true;
							break;
						}else{
						}
					}
					echo ($has)?'YES':'NO';
					echo ',';
				}
				// TODO - export campaiangs. eg: foreach($campaigns....
				echo "\n";
			}
			exit;
		}
		if(!mysql_num_rows($pagable_members)){
			?>
			<tr>
				<td colspan="<?php echo 10+count($member_fields);?>" style="text-align:center;">
					on noes!! no members found! you can <a href="?p=members_add">add some</a> if you want.
				</td>
			</tr>
			<?php
		}
		$page_number = (isset($_REQUEST['ps'])) ? (int)$_REQUEST['ps'] : 0;
		$limit_start = $page_number * $per_page_limit;
		if(mysql_num_rows($pagable_members)>0&&mysql_num_rows($pagable_members)>=$limit_start){
			mysql_data_seek($pagable_members,$limit_start);
		}
		$x=0;
		while( $x++ < $per_page_limit && $member = mysql_fetch_assoc($pagable_members)){
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
				<?php
				foreach($member_fields as $member_field){
				?> <td>
					<?php echo $member['custom'][$member_field['member_field_id']]['value'];?> 
				</td> 
				<?php
				}
				?> 
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
					<?php
					$print = '';
					foreach($member['sync'] as $sync){
						$print .=  '<a href="plugin/newsletter?p=members_sync&sync_id='.$sync['sync_id'].'">';
						$print .= $sync['sync_name']."";
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
		</tbody>
		<?php
		// really quick hacky way of doing pagination... should really use mysql count instead of php.. oh well. ill do it better next round.
		$current_page = isset($_REQUEST['ps']) ? (int)$_REQUEST['ps'] : 0;
		
		// group into ranges with cute little .... around the numbers if there's too many.
		$rangestart = max(0,$current_page-5);
		$rangeend = min($page_count-1,$current_page+5);
		if($page_count>1){
		?>
		<tfoot>
			<tr>
				<th align="center" colspan="<?php echo 10+count($member_fields);?>" style="text-align:center;">
					Page: 
					<?php
					
					
					if($rangestart>0){
						?> <a href="#" onclick="return page(0);return(false); ">1</a> <?php
						if($rangestart>1)echo ' ... ';
					}
					
					for($x=$rangestart;$x<=$rangeend;$x++){
						if($x == $current_page){ 
							?>
							<?php echo ($x+1);?>
							<?php
						}else{
							?>
							<a a href="#" onclick="return page(<?php echo $x;?>);" style="cursor:pointer"><?php echo ($x+1);?></a>
							<?php
						}
					}
					if($rangeend < ($page_count-1)){
						if($rangeend < ($page_count-2))echo ' ... ';
						?> <a a href="#" onclick="return page(<?php echo ($page_count-1);?>);" style="cursor:pointer"><?php echo ($page_count);?></a> <?php
					}
					?>
					<script language="javascript">
					function page(id){
						jQuery('#ps').val(id);
						if(jQuery('#current_start-letter').val() != ''){
							jQuery('#current_start-letter').attr('name','search_fields[start-letter]');
						}
						jQuery('#search_form')[0].submit();
						return false;
					}
					</script>
				</th>
			</tr>
		</tfoot>
		<?php } ?>
	</table>
	
	
	<br/>
	
	<input type="submit" name="download_as_csv" value="<? echo __('Download above as CSV');?>">
	
</div>

</form>


<div class="box">
	<a href="plugin/newsletter?p=members&export=true"><? echo __('Export all members to CSV');?></a>
</div>

<?php } ?>



