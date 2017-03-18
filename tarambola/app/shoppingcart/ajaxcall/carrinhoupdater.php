    <? require_once '../../../../config.php'; ?>
    <? require_once('../../../Framework.php'); ?>   
    <? require_once SERVER_URL.'/tarambola/app/shoppingcart/Encomendas.php'; ?>
    <? require_once SERVER_URL.'/tarambola/app/shoppingcart/ItemClass.php'; ?>
    <? require_once SERVER_URL.'/tarambola/app/shoppingcart/Cart.php'; ?>
    <? require_once SERVER_URL.'/tarambola/app/shoppingcart/Atributos/Atributos.php'; ?>
    <?
        $__tarambola_CONN__ = new PDO(DB_DSN, DB_USER, DB_PASS);

    if ($__tarambola_CONN__->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql')
        $__tarambola_CONN__->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

    Record::connection($__tarambola_CONN__);
    Record::getConnection()->exec("set names 'utf8'");

        $ivaObj = Encomendas::getVat();
        $useIva = $ivaObj->use;
        $valueIva = $ivaObj->value;
        $cart = new Cart();
        $cart->create();
        $itens = $cart->getItens();
        $portes = Encomendas::getPortesByPais("PT");
        $atributosBO = Encomendas::getAtributos();
        if($portes->valor==0)
            $usePortesPerUnit=true;
        else
            $usePortesPerUnit=false;
        if($portes->valor_gratis>0)
            $hasPortesGratis=true;
        else
            $hasPortesGratis=false;
        $calculoPortes=0;
        $total=0;
    ?>
    <br><br><section class="section-shopping-cart-page">
                <div class="container">
            <table class="tbl-cart">
                <thead>
                    <tr>
                        <th>Artigos</th>
                        <th style="width: 15%;">Atributos</th>
                        <th style="width: 10%;">Preço unitário</th>
                        <th style="width: 15%;">Quantidade</th>
                        <th class="hidden-xs" style="width: 10%;">Sub-Total</th>
                        <th class="hidden-xs" style="width: 5%;"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hide empty-cart">
                        <td colspan="5">
                            O seu carrinho está vazio de momento, dê uma vista de olhos nos nossos <a href="produtos.php">produtos</a>.
                        </td>
                    </tr>
                    <? for($i=0; $i<sizeof($itens); $i++): ?>
                    <? 
                        $atributos = $cart->getItemAttributesValues($itens[$i]->id);
                        $produto = new ItemClass(); 
                        $produto = $produto->getPageById($itens[$i]->page_id); 
                        $atributosModel = new Atributos();
                        $atributosModel->decodeJSon($produto->content('atributos'));
                        $attributesValues = array();
                        for($k=0; $k<sizeof($atributos); $k++)
                        {
                            array_push($attributesValues, $atributos[$k]->valor);
                        }
                        $maxQtd = $atributosModel->getQtdByAtributos($attributesValues);
                        if($maxQtd==-1) $maxQtd=1000;
                       // var_dump($itens);
                       // var_dump('<br><br><br>');
                       // var_dump($atributos);
                       // var_dump('<br><br><br>');
                       // var_dump($attributesValues);
                    ?>
                    <tr>
                        <td>
                            <a class="entry-thumbnail" href="images/women/legging/104331-0014_1.jpg" data-toggle="lightbox">
                                <img src="<?php echo($itens[$i]->thumb_path); ?>" alt="<? echo($itens[$i]->title); ?>" />
                            </a>
                            <a class="entry-title" href="produto.php"><? echo($itens[$i]->title); ?></a>
                             <!-- ######################## ATRIBUTOS ########################### -->
                             <td>
                            <? for($it=0; $it<sizeof($atributosBO); $it++): ?>
                              <small><span class="attributeDesc" ></span><span class="attributeValue" ><? echo($cart->getItemAttributesById($itens[$i]->id, $atributosBO[$it]->id)->descricao); ?></span></small>
                            <? endfor; ?>
                             </td>
                            <!-- ######################## END ATRIBUTOS ########################### -->
                        </td>
                        <? $valor = ($itens[$i]->value_unity - $itens[$i]->discount_unity); ?>
                        <td><span class="unit-price"><? echo(money_format('%.2n', $valor)); ?>€</span></td>
                        <td>
                            <!-- ######################## QTD ########################### -->
                            <div class="quantity">
                                <div class="qtdSpinner">
                                    <select class="md-select quantity qtdSelect" id="qtdSelect" >
                                    <?php for($k=1; $k<=$maxQtd; $k++): ?>
                                        <option value="<? echo($k); ?>" <? echo((($k)==$itens[$i]->qtd)?'selected':''); ?> ><? echo($k); ?></option>
                                    <?php endfor; ?>
                                    </select> 
                                    <span class="increment">+</span><span class="decrement">-</span>
                                    <input type="hidden" class="itemId" value="<? echo($itens[$i]->id); ?>"></input>
                                    <input type="hidden" class="pageId" value="<? echo($itens[$i]->page_id); ?>"></input>
                                </div>
                            </div>
                            <!-- ######################## END QTD ########################### -->
                        </td>
                             
                        <td class="hidden-xs"><strong class="text-bold row-total"><? echo(money_format('%.2n', $itens[$i]->qtd*$valor)); ?>€</strong></td>
                        <td class="hidden-xs"><button type="button" class="close-btn" aria-hidden="true" id="<? echo($itens[$i]->id); ?>">×</button></td>
                    </tr>
                    <? $total+= (($itens[$i]->value_total-$itens[$i]->discount_unity)*$itens[$i]->qtd); ?>
                    <?php endfor; ?>
                </tbody>
            </table>
            <? $totalIva = $total*$valueIva; ?>
            <div class="shopcart-total pull-right clearfix">
                <div class="text-xs m-b-sm clearfix">
                    <span class="pull-left">Sub-Total s/ IVA:</span>
                    <span class="pull-right"><? echo($total - $totalIva);?></span>
                </div>
                <div class="text-xs m-b-sm clearfix">
                    <span class="pull-left">IVA:</span>
                    <span class="pull-right"><? echo(money_format('%.2n', $totalIva)); ?>€</span>
                </div>
                <div class="cart-total text-bold m-b-lg clearfix">
                    <span class="pull-left">Total:</span>
                    <span class="pull-right"><? echo(money_format('%.2n', $total)/*+$portesNumber*/); ?>€</span>
                </div>
                <div class="text-center">
                    <?php // var_dump(sizeof($itens)); ?>
                    <a class="btn btn-round btn-default uppercase" href="<?php if (sizeof($itens)==0) echo '#'; else echo URL_PUBLIC.'funcionalidades/checkout'; ?>">Proceder para checkout</a>
                </div>
            </div>
        </div><br/><br/>
    </section>