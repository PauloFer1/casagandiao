<? 
include_once("../../../../config.php");
require_once('../../../Framework.php');

$url = explode("/",($_SERVER["REQUEST_URI"])); 

require_once('Uploader.php');

$upload_dir = SERVER_URL.'public/images/';
$valid_extensions = array('gif', 'png', 'jpeg', 'jpg');

    $Upload = new FileUpload('uploadfile');
    $result = $Upload->handleUpload($upload_dir, $valid_extensions);
    if (!$result) 
    {
        echo json_encode(array('success' => false, 'msg' => $Upload->getErrorMsg(), 'file' => $Upload->getFileName()));   
    } 
    else 
    {
        $nomeFile = $Upload->getFileName();
        echo json_encode(array('success' => true, 'file' => $nomeFile, 'up'=>$upload_dir));
    }

?>