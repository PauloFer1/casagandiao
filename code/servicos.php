<!-- Banner Section Begin -->
    <section class="banner banner-small" id="banner" style="background: url(<?php echo URL_PUBLIC; ?>public/themes/default/images/bg3.jpg) center; background-size: cover;">
      <div class="overlay"></div>
      <div class="banner-content">
        <div class="container">
          <div class="col-md-8 col-md-offset-2">
              <br><br>
            <div class="title">
              <h1 id="typed">Serviços</h1>
            </div>
          </div>
        </div><!-- /.container -->

        <div class="scroll-info scroll-to">
          <a href="#first-content">
            <div class="mouse-wheel-icon">
              <div class="wheel"></div>
            </div>
          </a>
        </div><!--/.scroll-info -->
      </div><!-- /.banner-content -->
    </section><!-- /.banner -->
    <!-- Banner Section End -->
    
    <!-- Shop Detail Begin -->
    <section class="shop-detail" id="first-content">
      <div class="container">
        
        <div class="product-description col-md-12 no-padding">
          <div class="col-md-6">
            <div class="item-image">
              <img src="assets/images/shop-detail.jpg" alt="">
            </div><!--/.item-image -->
          </div>
          <div class="col-md-6">
            <div class="desc-row top-desc">
              <div class="col-md-6 no-padding">
                <div class="price"><?php echo $this->title(); ?></div>
              </div>
            </div><!--/.desc-row -->
            <div class="desc-row">
              <div class="amount-wrap">
                <div class="input-group amount">
                  <a href="javascript:void(0)" class="input-group-addon minus amount-minus">-</a>
                  <input type="text" class="form-control amount-value" placeholder="e.g 2" value="2">
                  <a href="javascript:void(0)" class="input-group-addon plus amount-plus">+</a>
                </div>
              </div>
              <a href="#" class="def-btn">Add Cart</a>
            </div><!--/.desc-row -->
            
            <div class="desc-row">
              <div class="description"><?php $this->pContent('texto'); ?></div>
            </div><!--/.desc-row -->

          </div><!--/.col-md-6 -->
        </div><!-- /.product-description -->
        
             </div><!-- /.container -->
    </section><!--/.shop-detail -->
    <!-- Shop Detail End -->