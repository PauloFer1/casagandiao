<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NewsletterController
 *
 * @author paulofernandes   
 */
class NewsletterController extends PluginController
{
    const PLUGIN_ID = "newsletter";
   function __construct()
    {
        AuthUser::load();
        if ( ! AuthUser::isLoggedIn())
            redirect(get_url('login'));

        $this->setLayout('backend');
       // $this->assignToLayout('sidebar', new View('../../../plugins/newsletter/views/sidebar'));
    }

    function index()
    {
        $this->display('newsletter/views/index');
         Plugin::addJavascript(self::PLUGIN_ID, "jquery-1.4.2.min.js");
    }
}
?>
