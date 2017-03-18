<?php

/**


/**
 * The skeleton plugin serves as a basic plugin template.
 *
 * This skeleton plugin makes use/provides the following features:
 * - A controller without a tab
 * - Three views (sidebar, documentation and settings)
 * - A documentation page
 * - A sidebar
 * - A settings page (that does nothing except display some text)
 * - Code that gets run when the plugin is enabled (enable.php)
 *
 * Note: to use the settings and documentation pages, you will first need to enable
 * the plugin!
 *
 * @package tarambola
 * @subpackage plugin.skeleton
 *
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @version 1.0.0
 * @since tarambola version 0.9.5
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Martijn van der Kleijn, 2008
 */

/**
 * Use this SkeletonController and this skeleton plugin as the basis for your
 * new plugins if you want.
 */
class DocumentosController extends PluginController {

    public function __construct() {
        $this->setLayout('backend');
        $this->assignToLayout('sidebar', new View('../../plugins/documentos/views/sidebar'));
    }
     public static function _checkPermission()
    {
        AuthUser::load();
        if ( ! AuthUser::isLoggedIn())
        {
            redirect(get_url('login'));
        }
        else if ( ! AuthUser::hasPermission('administrator,developer,editor'))
        {
            Flash::set('error', __('You do not have permission to access the requested page!'));
            redirect(get_url());
        }
    }
    public function index() {
        $this->_checkPermission();
        $this->browse();
    }

    public function documentation() {
        $this->display('documentos/views/documentation');
    }

    function settings() {
        /** You can do this...
        $tmp = Plugin::getAllSettings('skeleton');
        $settings = array('my_setting1' => $tmp['setting1'],
                          'setting2' => $tmp['setting2'],
                          'a_setting3' => $tmp['setting3']
                         );
        $this->display('comment/views/settings', $settings);
         *
         * Or even this...
         */

        $this->display('documentos/views/settings', Plugin::getAllSettings('documentos'));
    }
     public function browse()
    {
        $this->_checkPermission();
        $params = func_get_args();
        
        $this->path = join('/', $params);
        // make sure there's a / at the end
        if (substr($this->path, -1, 1) != '/') $this->path .= '/';
        
        //security
        
        // we dont allow back link
        if (strpos($this->path, '..') !== false)
        {
            if (Plugin::isEnabled('statistics_api'))
            {
                $user = null;
                if (AuthUser::isLoggedIn())
                    $user = AuthUser::getUserName();
                $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR']:($_SERVER['REMOTE_ADDR']);
                $event = array('event_type'  => 'hack_attempt',            // simple event type identifier
                               'description' => __('A possible hack attempt was detected.'), // translatable description
                               'ipaddress'   => $ip,
                               'username'    => $user);
                Observer::notify('stats_file_manager_hack_attempt', $event);
            }
        }
        $this->path = str_replace('..', '', $this->path);
        
        // clean up nicely
        $this->path = str_replace('//', '', $this->path);
        
        // we dont allow leading slashes
        $this->path = preg_replace('/^\//', '', $this->path); 
        
        $this->fullpath = DOCUMENTS_DIR.'/'.$this->path;
        
        // clean up nicely
        $this->fullpath = preg_replace('/\/\//', '/', $this->fullpath);
        
        
        $this->display('documentos/views/index', array(
            'dir'   => $this->path,
            'files' => $this->_getListFiles(),
            'users' => $this->getFrontUsers(),
            'cats'  => $this->getCategorias()
        ));
    } // browse
  
    public function view()
    {
        $this->_checkPermission();
        $params = func_get_args();
        $content = '';

        $filename = urldecode(join('/', $params));

        // Sanitize filename for securtiy

        // We don't allow backlinks
        if (strpos($filename, '..') !== false)
        {
            if (Plugin::isEnabled('statistics_api'))
            {
                $user = null;
                if (AuthUser::isLoggedIn())
                    $user = AuthUser::getUserName();
                $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR']:($_SERVER['REMOTE_ADDR']);
                $event = array('event_type'  => 'hack_attempt',            // simple event type identifier
                               'description' => __('A possible hack attempt was detected.'), // translatable description
                               'ipaddress'   => $ip,
                               'username'    => $user);
                Observer::notify('stats_file_manager_hack_attempt', $event);
            }
        }
        $filename = str_replace('..', '', $filename);

        // Clean up nicely
        $filename = str_replace('//', '', $filename);

        // We don't allow leading slashes
        $filename = preg_replace('/^\//', '', $filename); 

        $file = FILES_DIR.'/'.$filename;
        if ( ! $this->_isImage($file) && file_exists($file))
        {
            $content = file_get_contents($file);
        }
        
        $this->display('file_manager/views/view', array(
            'is_image' => $this->_isImage($file),
            'filename' => $filename,
            'content'  => $content,
            'users'    => $this->getFrontUsers(),
            'cats'  => $this->getCategorias()
        ));
    }
    public function getIdDoc($file, $docs)
    {
        if(!$docs)
            $docs = array();
        foreach($docs as $d)
        {
            if($d->nome==$file)
                return($d);
        }
        return(0);
    }
     public function _getListFiles()
    {
        $files = array();
        
        $docs = $this->getDocumentsBD($this->path);
        
        if (is_dir($this->fullpath) && $handle = opendir($this->fullpath))
        {
            $i = 0;
            // check each files ...
            while (false !== ($file = readdir($handle)))
            {
                // do not display . and the root ..
                if ($file == '.' || $file == '..')
                    continue;
                
                $object = new stdClass;
                $file_stat = stat($this->fullpath.$file);
                
                // make the link depending on if it's a file or a dir
                if (is_dir($this->fullpath.$file))
                {
                    $object->is_dir = true;
                    $object->is_file = false;
                    $object->link = '<a href="'.get_url('plugin/documentos/browse/'.$this->path.$file).'">'.$file.'</a>';
                    $object->id=$i;
                    $object->name = $file;
                    $object->users = array();
                    $object->cats = array();
                    $object->descricao = "";
                }
                else
                {
                    $d = $this->getIdDoc($file, $docs);
                    $object->is_dir = false;
                    $object->is_file = true;
                    $object->link = '<a href="'.get_url('plugin/file_manager/view/'.$this->path.$file).'">'.$file.'</a>';
                    $object->id = $d->id;
                    $object->name = $file;
                    $object->users = $this->getUsersByDoc($object->id);
                    $object->cats = $this->getCategoriasByDoc($object->id);
                    $object->descricao = $d->descricao;
                }
                
                $object->name = $file;
                // humain size
                $object->size = convert_size($file_stat['size']);
                // permission
                list($object->perms, $object->chmod) = $this->_getPermissions($this->fullpath.$file);
                // date modification
                $object->mtime = date('D, j M, Y', $file_stat['mtime']);
                
                $files[$object->name] = $object;
                
                $i++;
            } // while
            closedir($handle);
        }
        
        uksort($files, 'strnatcmp');
        return $files;
    } // _getListFiles
     public function _getPermissions($file)
    {
        $perms = fileperms($file);

        if (($perms & 0xC000) == 0xC000) {
            // Socket
            $info = 's';
        } elseif (($perms & 0xA000) == 0xA000) {
            // Symbolic Link
            $info = 'l';
        } elseif (($perms & 0x8000) == 0x8000) {
            // Regular
            $info = '-';
        } elseif (($perms & 0x6000) == 0x6000) {
            // Block special
            $info = 'b';
        } elseif (($perms & 0x4000) == 0x4000) {
            // Directory
            $info = 'd';
        } elseif (($perms & 0x2000) == 0x2000) {
            // Character special
            $info = 'c';
        } elseif (($perms & 0x1000) == 0x1000) {
            // FIFO pipe
            $info = 'p';
        } else {
            // Unknown
            $info = 'u';
        }

        // Owner
        $info .= (($perms & 0x0100) ? 'r' : '-');
        $info .= (($perms & 0x0080) ? 'w' : '-');
        $info .= (($perms & 0x0040) ?
                 (($perms & 0x0800) ? 's' : 'x' ) :
                 (($perms & 0x0800) ? 'S' : '-'));

        // Group
        $info .= (($perms & 0x0020) ? 'r' : '-');
        $info .= (($perms & 0x0010) ? 'w' : '-');
        $info .= (($perms & 0x0008) ?
                 (($perms & 0x0400) ? 's' : 'x' ) :
                 (($perms & 0x0400) ? 'S' : '-'));

        // World
        $info .= (($perms & 0x0004) ? 'r' : '-');
        $info .= (($perms & 0x0002) ? 'w' : '-');
        $info .= (($perms & 0x0001) ?
                 (($perms & 0x0200) ? 't' : 'x' ) :
                 (($perms & 0x0200) ? 'T' : '-'));

        return array($info, substr(sprintf('%o', $perms), -4, 4)); // (perm, chmod)
    } // _getPermissions
     public function delete()
    {
        $this->_checkPermission();
        $paths = func_get_args();
        $name = $paths;
        $file = urldecode(join('/', $paths));
        
        $file = DOCUMENTS_DIR.'/'.str_replace('..', '', $file);
        $filename = array_pop($paths);
        $paths = join('/', $paths);
       
        if (is_file($file))
        {
            if ( ! unlink($file))
                Flash::set('error', __('Permission denied!'));
            else
                $this->deleteDocument($name[0]);
        }
        else
        {
            if ( ! $this->rrmdir($file))
                Flash::set('error', __('Permission denied!'));
        }
        
       redirect(get_url('plugin/documentos/browse/'.$paths));
    }
    public function create_directory()
    {
        $this->_checkPermission();
        $data = $_POST['directory'];
        
        $path = str_replace('..', '', $data['path']);
        $dirname = str_replace('..', '', $data['name']);
        $dir = FILES_DIR."/{$path}/{$dirname}";
        
        if (mkdir($dir))
        {
            chmod($dir, 0755);
        }
        else
        {
            Flash::set('error', __('Directory :name has not been created!', array(':name'=>$dirname)));
        }
        redirect(get_url('plugin/file_manager/browse/'.$path));
    }
     public function create_category()
    {
         $this->_checkPermission();
        $data = $_POST['category'];
        
         $path = str_replace('..', '', $data['path']);
        $dirname = str_replace('..', '', $data['name']);
        $dir = FILES_DIR."/{$path}/{$dirname}";
        $nome = $data['name'];
        
        global $__tarambola_CONN__;

        
        $sql="INSERT INTO ".TABLE_PREFIX."documentos_categorias (nome) VALUES ('".$nome."')";

                if ($stmt = $__tarambola_CONN__->prepare($sql))
                {

                    if($stmt->execute())
                        Flash::set('success', __('Categoria :name criada com sucesso!', array(':name'=>$data['name'])));
                    else
                        Flash::set('error', __('Categoria :name não foi criada!', array(':name'=>$data['name'])));

                }
                
        redirect(get_url('plugin/documentos/browse/'.$path));
    }

function rrmdir($dirname)
{
    FileManagerController::_checkPermission();
    if (is_dir($dirname))
    {
        // Append slash if necessary
        if (substr($dirname,-1)!='/')
            $dirname.='/';
        
        $handle = opendir($dirname);
        while (false !== ($file = readdir($handle)))
        {
            if ($file != '.' && $file != '..')
            {
                $path = $dirname.$file;
                if (is_dir($path))
                {
                    rrmdir($path);
                }
                else
                {
                    unlink($path);
                }
            }
        }
        closedir($handle);
        rmdir($dirname); // Remove dir
        return true; // Return array of deleted items
    }
    else
    {
        return false; // Return false if attempting to operate on a file
    }
} 
    //******************** BD *************************//
    public function getDocumentsBD($path="")
    {
        global $__tarambola_CONN__;
        $docs=null;

        $sql="SELECT * From ".TABLE_PREFIX."documentos WHERE caminho='".$path."'";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $docs[] = $object;
            }
        }
        return $docs;
    }
    public function getFrontUsers()
    {
        global $__tarambola_CONN__;
        $users=null;

        $sql="SELECT * From ".TABLE_PREFIX."front_users";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $users[] = $object;
            }
        }
        return($users);         
    }
    public function getUsersByDoc($id)
    {
        global $__tarambola_CONN__;
        $users=array();

        $sql="SELECT dfu.id, dfu.documentos_id AS id_documento, fu.id AS id_user, fu.nome 
            From ".TABLE_PREFIX."documentos_front_users AS dfu 
            INNER JOIN ".TABLE_PREFIX."front_users AS fu ON dfu.front_users_id = fu.id
            WHERE documentos_id = '".$id."'";
        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {

                $users[] = $object;
            }
        }
        return($users);  
    }
    public function getCategorias()
    {
        global $__tarambola_CONN__;
        $cats=null;

        $sql="SELECT * From ".TABLE_PREFIX."documentos_categorias";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {
                $cats[] = $object;
            }
        }
        return($cats);        
    }
    public function getCategoriasByDoc($id)
    {
        global $__tarambola_CONN__;
        $cats=array();

        $sql="SELECT c.id, c.nome From ".TABLE_PREFIX."documentos_categorias_assoc AS d
                INNER JOIN ".TABLE_PREFIX."documentos_categorias as c ON c.id = d.categoria_id
                WHERE d.documento_id = '".$id."'";

        if ($stmt = $__tarambola_CONN__->prepare($sql))
        {
            $stmt->execute();

            while ($object = $stmt->fetchObject())
            {
                $cats[] = $object;
            }
        }
        return($cats);        
    }
    public function deleteDocument($name)
    {
         global $__tarambola_CONN__;

            $sql="DELETE FROM ".TABLE_PREFIX."documentos WHERE nome='".$name."'";
            if ($stmt = $__tarambola_CONN__->prepare($sql))
            {

                if($stmt->execute())
                    $flag=1;
                else
                    $flag=0;

            }
            return($flag);
    }
   //******************** @BD *************************//
}