<?php

/**


/**
 * @package tarambola
 * @subpackage controllers
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Philippe Archambault, 2008
 */

/**
 * Class SnippetController
 *
 * @package tarambola
 * @subpackage controllers
 *
 * @since 0.1
 */
class SnippetController extends Controller
{

    public function __construct()
    {
        AuthUser::load();
        if ( ! AuthUser::isLoggedIn())
        {
            redirect(get_url('login'));
        }
        else if ( ! AuthUser::hasPermission('administrator') && ! AuthUser::hasPermission('developer'))
        {
            Flash::set('error', __('You do not have permission to access the requested page!'));

            if (Setting::get('default_tab') === 'snippet')
                redirect(get_url('page'));
            else
                redirect(get_url());
        }
        
        $this->setLayout('backend');
        $this->assignToLayout('sidebar', new View('snippet/sidebar'));
    }
    
    public function index()
    {
        $this->display('snippet/index', array(
            'snippets' => Record::findAllFrom('Snippet', '1=1 ORDER BY position')
        ));
    }
    
    public function add()
    {
        // check if trying to save
        if (get_request_method() == 'POST')
            return $this->_add();
        
        // check if user have already enter something
        $snippet = Flash::get('post_data');
        
        if (empty($snippet))
            $snippet = new Snippet;
        
        $this->display('snippet/edit', array(
            'action'  => 'add',
            'filters' => Filter::findAll(),
            'snippet' => $snippet
        ));
    }
    
    private function _add()
    {
        $data = $_POST['snippet'];
        Flash::set('post_data', (object) $data);
        
        $snippet = new Snippet($data);
        
        if ( ! $snippet->save())
        {
            Flash::set('error', __('Snippet has not been added. Name must be unique!'));
            redirect(get_url('snippet', 'add'));
        }
        else
        {
            Flash::set('success', __('Snippet has been added!'));
            Observer::notify('snippet_after_add', $snippet);
        }
        
        // save and quit or save and continue editing?
        if (isset($_POST['commit']))
            redirect(get_url('snippet'));
        else
            redirect(get_url('snippet/edit/'.$snippet->id));
    }
    
    public function edit($id)
    {
        if ( ! $snippet = Snippet::findById($id))
        {
            Flash::set('error', __('Snippet not found!'));
            redirect(get_url('snippet'));
        }
        
        // check if trying to save
        if (get_request_method() == 'POST')
            return $this->_edit($id);
        
        $this->display('snippet/edit', array(
            'action'  => 'edit',
            'filters' => Filter::findAll(),
            'snippet' => $snippet
        ));
    }
    
    private function _edit($id)
    {
        $data = $_POST['snippet'];
        
        $data['id'] = $id;
        
        $snippet = new Snippet($data);
        
        if ( ! $snippet->save())
        {
            Flash::set('error', __('Snippet :name has not been saved. Name must be unique!', array(':name'=>$snippet->name)));
            redirect(get_url('snippet/edit/'.$id));
        }
        else
        {
            Flash::set('success', __('Snippet :name has been saved!', array(':name'=>$snippet->name)));
            Observer::notify('snippet_after_edit', $snippet);
        }
        
        // save and quit or save and continue editing?
        if (isset($_POST['commit']))
            redirect(get_url('snippet'));
        else
            redirect(get_url('snippet/edit/'.$id));
    }
    
    public function delete($id)
    {
        // find the user to delete
        if ($snippet = Record::findByIdFrom('Snippet', $id))
        {
            if ($snippet->delete())
            {
                Flash::set('success', __('Snippet :name has been deleted!', array(':name'=>$snippet->name)));
                Observer::notify('snippet_after_delete', $snippet);
            }
            else
                Flash::set('error', __('Snippet :name has not been deleted!', array(':name'=>$snippet->name)));
        }
        else Flash::set('error', __('Snippet not found!'));
        
        redirect(get_url('snippet'));
    }
    
    public function reorder()
    {
        parse_str($_POST['data']);
        
        foreach ($snippets as $position => $snippet_id)
        {
            $snippet = Record::findByIdFrom('Snippet', $snippet_id);
            $snippet->position = (int) $position + 1;
            $snippet->save();
        }
    }

} // end SnippetController class
