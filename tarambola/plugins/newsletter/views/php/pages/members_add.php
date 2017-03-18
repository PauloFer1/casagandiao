<?php

$groups = $newsletter->get_groups($db);


if(isset($_REQUEST['csvsample'])){
	ob_end_clean();
	header("Content-type: text/csv");
	header('Content-Disposition: attachment; filename="sample_import.csv"');
	echo "Email,First Name,Last Name\n";
	echo "demo@demo.com,Bob,Smith\n";
	echo "demo@example.com,Jane,Doe\n";
	exit;
}

if(isset($_REQUEST['import'])){
	ob_end_clean();
	if(_DEMO_MODE){
		echo "CSV import not allowed in demo mode sorry";
		exit;
	}
	$filename = $_FILES['upload']['tmp_name'];
	if(is_uploaded_file($filename)){
		$fd = fopen($filename,"r");
		$csv_data = array();
		while($row = fgetcsv($fd)){
			$csv_data [] = $row;
		}
		// remove first row, it should be the headers
		$csv_headers = array_shift($csv_data);
		// pull out the defaults, first name, last name, emails
		$positions = array(
			"first_name" => 1,
			"last_name" => 1,
			"email" => 0,
		);
		$group_key = false;
		$custom_fields = array();
		foreach($csv_headers as $key=>$val){
			if(preg_match('/email/i',$val)){
				$positions['email'] = $key;
			}else if(preg_match('/first/i',$val)){
				$positions['first_name'] = $key;
			}else if(preg_match('/last/i',$val)){
				$positions['last_name'] = $key;
			}else if(!$group_key && preg_match('/Subscribed/i',$val)){
				$group_key = $key;
			}else{
				// make it a custom field.
				$custom_fields[$key] = $val;
			}
		}
		// process the others.. no error checking
		// their fault if they do it wrong :D
		// i warned them.
		
		$group_ids = (isset($_REQUEST['group_id']) && is_array($_REQUEST['group_id'])) ? $_REQUEST['group_id'] : array();
		// do the processing:
		$import_count=0;
		foreach($csv_data as $data){
			if(!trim($data[0]))continue;
			$custom = array();
			foreach($custom_fields as $key=>$val){
				$custom[$val] = trim($data[$key]);
			}
			$this_group_ids = $group_ids;
			if($group_key){
				// this import has a field which lists which groups they are a part of
				$this_group_ids=array();
				foreach($groups as $group){ 
					if(preg_match('#'.preg_quote($group['group_name'],'#').'#i',$data[$group_key])){
						$this_group_ids[] = $group['group_id'];
					}
				}
			}
			$fields = array(
				"first_name"=>trim($data[1]),
				"last_name"=>trim($data[2]),
				"email"=>trim($data[0]),
				"group_id"=>$this_group_ids,
				"custom" => $custom,
			);
			$member_id = $newsletter->save_member($db,"new",$fields);
			if($member_id){
				$import_count++;
			}
		}
		echo "Successfully imported $import_count members. Lets hope it worked! <a href='?p=members'>click here</a> to find out!";
		exit;
	}
	
}

if(isset($_REQUEST['save']) && isset($_REQUEST['member_id']) && isset($_REQUEST['mem_email'])){
	
	$group_ids = (isset($_REQUEST['group_id']) && is_array($_REQUEST['group_id'])) ? $_REQUEST['group_id'] : array();
	$campaign_ids = (isset($_REQUEST['campaign_id']) && is_array($_REQUEST['campaign_id'])) ? $_REQUEST['campaign_id'] : array();
	
	$fields = array(
		"first_name"=>htmlspecialchars($_REQUEST['mem_first_name']),
		"last_name"=>htmlspecialchars($_REQUEST['mem_last_name']),
		"email"=>htmlspecialchars($_REQUEST['mem_email']),
		"group_id"=>$group_ids,
		"campaign_id"=>$campaign_ids,
		"custom"=>$_REQUEST['mem_custom_val'],
	);
	$member_id = $newsletter->save_member($db,$_REQUEST['member_id'],$fields);
	if($member_id){
		
		// save custom fields
		if($_REQUEST['mem_custom_new_val'] && $_REQUEST['mem_custom_new_key']){
			$newsletter->save_member_custom($db,$member_id,$_REQUEST['mem_custom_new_key'],$_REQUEST['mem_custom_new_val'],true);
		}
		
		ob_end_clean();
		header("Location: ".URL_PUBLIC."tarabackend/plugin/newsletter?p=members&added=true");
		exit;
	}
	
}



?>

<h1>Adicionar novo membro.</h1>

<form action="" method="post" id="create_form">
<input type="hidden" name="member_id" value="new">
<input type="hidden" name="save" value="true">
<h2><span>Adicionar novo membro</span></h2>

<div class="box">
	<?php include("members_form.php"); ?>
	
</div>

</form>


<form action="plugin/newsletter?p=members_add&import=true" method="post" id="create_form" enctype="multipart/form-data">

<h2><span>Importar por ficheiro CSV (beta)</span></h2>

<div class="box">
	<p>
		<a href="plugin/newsletter?p=members_add&csvsample">Clicar aqui</a> para fazer download de um ficheiro CSV exemplo, usar este formato.
	</p>
	<p>
		<strong>BETA:</strong> Por favor <em>Verifique</em> se o ficheiro está no formato correcto.<br> Não há validador.
	</p>
	<table cellpadding="4">
		<tr>
			<td>Escolher ficheiro CSV</td>
			<td>
				<input type="file" name="upload">
			</td>
		</tr>
		<tr>
			<td>Importar membros nestes grupos</td>
			<td>
				<?php
				
				foreach($groups as $group){ ?>
				<input type="checkbox" name="group_id[]" value="<?php echo $group['group_id'];?>"> <?php echo $group['group_name'];?> <br>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="submit" name="upload_file" value="Upload CSV File">
			</td>
		</tr>
	</table>
	
</div>

</form>

