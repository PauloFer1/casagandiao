<? require_once '../../../../config.php'; ?>
<? require_once('../../../Framework.php'); ?>
<? require_once SERVER_URL . '/tarambola/app/shoppingcart/Cart.php'; ?>
<?
$__tarambola_CONN__ = new PDO(DB_DSN, DB_USER, DB_PASS);

if ($__tarambola_CONN__->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql')
    $__tarambola_CONN__->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

Record::connection($__tarambola_CONN__);
Record::getConnection()->exec("set names 'utf8'");
?>
<? if (isset($_SESSION['isLoggin']) && $_SESSION['isLoggin']): ?>
<? endif; ?>
<?
$cart = new Cart();
$cart->create();
$produtos = $cart->getItens();
?>
<li>
    <a id="total-cart" href="<?php echo URL_PUBLIC; ?>funcionalidades/carrinho">
        <i class="iconfont-shopping-cart round-icon"></i>
    </a>
    <div id="sub-cart" class="sub-header" style="right: 0px; display: none;">
        <div id="cartHolder">
            <div class="wish-cart-holder" id="cartHolder">
                <div class="top-cart-holder ic-sm-basket pull-right">
                    <a href="<?php if (sizeof($produtos) > 0) echo URL_PUBLIC; ?><?php
                    if (sizeof($produtos) > 0)
                        echo 'funcionalidades/carrinho';
                    else
                        echo '#'
                        ?>">
                        <span class="top-cart-tag"> Cesto de compras<?php if (sizeof($produtos) > 0) echo ':' ?></span>
                        <?php if (sizeof($produtos) > 0): ?>
                            <span class="top-cart-price"><? echo($cart->getTotalValue()); ?>€</span>
                        <?php endif; ?>
                        <div class="total-buble">
                            <span><? echo($cart->getTotalItens()); ?> artigos novo no cesto</span>
                        </div>
                    </a>
                        <? for ($i = 0; $i < sizeof($produtos); $i++): ?>
                    <div class="cart-header">
                    <ul class="basket-items">
                                                    <li class="row">
                                <div class="thumb col-xs-3">
                                    <span class="qtdItem" ><? echo($produtos[$i]->qtd); ?>x</span>
                                        <img alt="<? echo($produtos[$i]->title); ?>" src="<?php echo($produtos[$i]->thumb_path); ?>" />
                                </div>
                                <div class="body col-xs-9">
                                    <p><? echo($produtos[$i]->title); ?></p><div class="clearfix"></div>
                                    <div class="price">
                                        <span><? echo($produtos[$i]->value_unity); ?></span>
                                    </div>
                                    <a href="#close" class="remove-item" id="<? echo($produtos[$i]->id); ?>">x</a>
                                </div>
                            </li>
                                            </ul>
                </div>
                    <?endfor;?>
                    <div class="cart-footer hover-holder">
                    <div class="text-right">
                        <!-- :::::::::::::::::::: LISTAGEM ::::::::::::::::::::::: -->
                    
                        <?php if (sizeof($produtos) > 0): ?>
                            <a class="btn btn-default btn-round view-cart" href="<?php echo URL_PUBLIC; ?>funcionalidades/carrinho">comprar</a>
                        <?php endif; ?>
</div>
                    </div>
                </div><br><br><br>
            </div>
        </div>
    </div>
</li>