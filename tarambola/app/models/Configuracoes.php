<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Configuracoes
 *
 * @author paulofernandes
 */
class Configuracoes extends Record
{
    const TABLE_NAME = 'configuracoes';

    public $nome;
    public $funcao;
    public $campo;

    public function saveConf($nome, $funcao, $campo)
    {
        $this->nome=$nome;
        $this->funcao=$funcao;
        $this->campo=$campo;
        $this->save();
    }
}
?>
