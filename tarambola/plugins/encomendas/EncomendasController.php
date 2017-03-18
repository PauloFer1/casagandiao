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
class EncomendasController extends PluginController {

    public function __construct() {
        $this->setLayout('backend');
        $this->assignToLayout('sidebar', new View('../../plugins/encomendas/views/sidebar'));
    }

    public function index() {
        $this->_checkPermission();
        $this->display('encomendas/views/index');
    }
    public function encomendas(){
        $this->_checkPermission();
        
        require_once SERVER_URL.'tarambola/app/shoppingcart/Encomendas.php';
        
        $encomendas = Encomendas::getEncomendas();
        $this->display('encomendas/views/encomendas', array('encomendas'=>$encomendas));
    }
     public function envios(){
        $this->_checkPermission();
        
        require_once SERVER_URL.'tarambola/app/shoppingcart/Encomendas.php';
        
        $encomendas = Encomendas::getEncomendasEnvio();
        $this->display('encomendas/views/envios', array('encomendas'=>$encomendas));
    }
     public function arquivo(){
        $this->_checkPermission();
        
        require_once SERVER_URL.'tarambola/app/shoppingcart/Encomendas.php';
        
        $encomendas = Encomendas::getEncomendasArquivo();
        $this->display('encomendas/views/arquivo', array('encomendas'=>$encomendas));
    }
    public function atributos(){
        $this->_checkPermission();
        
        require_once SERVER_URL.'tarambola/app/shoppingcart/Encomendas.php';
        
        $atributos = Encomendas::getAtributos();
        $this->display('encomendas/views/atributos', array('atributos'=>$atributos));
    }
    public function add_atributo(){
        $this->_checkPermission();
        
        require_once SERVER_URL.'tarambola/app/shoppingcart/Encomendas.php';
        
        $this->display('encomendas/views/add_atributo');
    }
    public function view_atributo($id){
        $this->_checkPermission();
        
        require_once SERVER_URL.'tarambola/app/shoppingcart/Encomendas.php';
        
        $atributos = Encomendas::getAtributosValoresById($id);
        $atributo = Encomendas::getAtributoById($id);
      
        $this->display('encomendas/views/view_atributo', array('valores'=>$atributos, 'atributo'=>$atributo));
    }
    public function add_atributo_valor($id){
        $this->_checkPermission();
        
        require_once SERVER_URL.'tarambola/app/shoppingcart/Encomendas.php';
        
        $atributo = Encomendas::getAtributoById($id);
        
        $this->display('encomendas/views/add_atributo_valor', array('atributo'=>$atributo));
    }
    public function configuracoes(){
        $this->_checkPermission();
        
        require_once SERVER_URL.'tarambola/app/shoppingcart/Encomendas.php';
        
        $this->display('encomendas/views/configuracoes');
    }
    public function iva(){
        $this->_checkPermission();
        
        require_once SERVER_URL.'tarambola/app/shoppingcart/Encomendas.php';
        
        $vat = Encomendas::getVat();
        $this->display('encomendas/views/iva', array('vat'=>$vat));
    }
    public function portes(){
        $this->_checkPermission();
        
        require_once SERVER_URL.'tarambola/app/shoppingcart/Encomendas.php';
        
        $portes = Encomendas::getPortes();
        
        $this->display('encomendas/views/portes', array('portes'=>$portes));
    }
    public function add_portes(){
        $this->_checkPermission();
        
        require_once SERVER_URL.'tarambola/app/shoppingcart/Encomendas.php';
        
        $paises = Encomendas::getPaises();
        
        $this->display('encomendas/views/add_portes', array('paises'=>$paises));
    }
    public function view_portes($id){
        require_once SERVER_URL.'tarambola/app/shoppingcart/Encomendas.php';
        
        $paises = Encomendas::getPaises();
        $portes = Encomendas::getPortesById($id);
        $assocs = Encomendas::getPortesAssocById($id);
        
        $this->display('encomendas/views/view_portes', array('portes'=>$portes, 'assocs'=>$assocs, 'paises'=>$paises));
    }
    public function add_portes_assoc($id){
        require_once SERVER_URL.'tarambola/app/shoppingcart/Encomendas.php';
        
        $portes = Encomendas::getPortesById($id);
        
        $this->display('encomendas/views/add_portes_assoc', array('portesId'=>$id));
    }
    public function edit_portes_assoc($id){
        require_once SERVER_URL.'tarambola/app/shoppingcart/Encomendas.php';
        
        $portes = Encomendas::getPortesValorById($id);
        
        $this->display('encomendas/views/edit_portes_assoc', array('portes'=>$portes));
    }
    public function view_encomenda($id){
        require_once SERVER_URL.'tarambola/app/shoppingcart/Encomendas.php';
        
        $produtos = Encomendas::getProdutosByEncomenda($id);
        $encomenda = Encomendas::getEncomendaById($id);
        $user = Encomendas::getUserByEncomenda($id);
        $estados = Encomendas::getEstados();
        $ivaObj = Encomendas::getVat();
        $useIva = $ivaObj->use;
        $valueIva = $ivaObj->value;
        $metodoPagamento = Encomendas::getMetodoPagamentoById($encomenda->metodo_pagamento);
        $atributosBO = Encomendas::getAtributos();
    
        
        $this->display('encomendas/views/view_encomenda', array('produtos'=>$produtos, 'encomenda'=>$encomenda, 'user'=>$user, 'estados'=>$estados, 'useIva'=>$useIva, 'valueIva'=>$valueIva, 'metodoPagamento'=>$metodoPagamento, 'atributosBO'=>$atributosBO));
    }
    public function edit_atributo($id)
    {
        require_once SERVER_URL.'tarambola/app/shoppingcart/Encomendas.php';
        
        $atributo = Encomendas::getValorById($id);
        $this->display('encomendas/views/edit_atributo', array('atributo'=>$atributo));
    }
    public function documentation() {
        $this->display('encomendas/views/documentation');
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

        $this->display('encomendas/views/settings', Plugin::getAllSettings('encomendas'));
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
}