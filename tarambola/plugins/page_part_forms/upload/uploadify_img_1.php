<?php
require_once("../../../../config.php");
$uploaddir = SERVER_URL."public/images/";

if (!empty($_FILES)) {
	$t = (string) microtime();
	$t2 = preg_split("/ /", $t);
	$t3 = implode("", $t2);
	$t4 = explode(".", $t3);
	$t5 = implode ("", $t4);
	
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_REQUEST['folder'] . '/';
        //$targetFile =  $uploaddir.$t5.$_FILES['Filedata']['name'];
        $file = $_FILES['Filedata']['name'];
        $final = $_FILES['Filedata']['name'];
        $targetFile =  $uploaddir.$_FILES['Filedata']['name'];
        $i=1;
        while(file_exists($targetFile))
            {
                $temp3 = preg_split("/\.([^\.]+)$/", $file);
                preg_match("/\.([^\.]+)$/", $file, $temp2);
                $targetFile =  $uploaddir.$temp3[0].'-'.$i.$temp2[0];
                $final = $temp3[0].'-'.$i.$temp2[0];
                $i++;
            }
	
		if(move_uploaded_file($tempFile,$targetFile))
			echo($final );
		else
			 echo "Erro ao introduzir Ficheiro!";
		//echo str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetFile);
	// } else {
	// 	echo 'Invalid file type.';
	// }
}
?>