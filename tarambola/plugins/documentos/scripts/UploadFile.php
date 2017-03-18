<? 
include_once("../../../../config.php");
require_once('../../../Framework.php');

$url = explode("/",($_SERVER["REQUEST_URI"])); 

    $__tarambola_CONN__ = new PDO(DB_DSN, DB_USER, DB_PASS);

    if ($__tarambola_CONN__->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql')
        $__tarambola_CONN__->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

    Record::connection($__tarambola_CONN__);
    Record::getConnection()->exec("set names 'utf8'");

require_once('Uploader.php');

$upload_dir = SERVER_URL.'public/documents/';
$valid_extensions = array('gif', 'png', 'jpeg', 'jpg', 'pdf', 'doc', 'docx', 'xlsx', 'xls', 'txt', 'rar', 'zip');

$Upload = new FileUpload('uploadfile');
$result = $Upload->handleUpload($upload_dir, $valid_extensions);

if (!$result) 
{
    echo json_encode(array('success' => false, 'msg' => $Upload->getErrorMsg()));   
} 
else 
{
    $nomeFile = $Upload->getFileName();
    insertFile($nomeFile);
    echo json_encode(array('success' => true, 'file' => $nomeFile));
}

function insertFile($nome)
{
     global $__tarambola_CONN__;
        
            $sql="INSERT INTO ".TABLE_PREFIX."documentos (nome)
                    VALUES ('".$nome."' )";

            if ($stmt = $__tarambola_CONN__->prepare($sql))
            {

                if($stmt->execute())
                    $flag=   $__tarambola_CONN__->lastInsertId ();
                else
                {
                    $flag=0;
                }

            }
            return($flag);
}

?>