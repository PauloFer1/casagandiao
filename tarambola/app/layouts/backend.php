<?php
if (!AuthUser::hasPermission('administrator,developer,editor')) {
    header('Location: ' . URL_PUBLIC . ' ');
    exit();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <title><?php
//use_helper('Kses');
//echo kses(Setting::get('admin_title'), array()) . ' - ' . ucfirst($ctrl = Dispatcher::getController(Setting::get('default_tab')));
       ucfirst($ctrl = Dispatcher::getController(Setting::get('default_tab')));
?>
         tarabackend  
        </title>

        <base href="<?php echo trim(BASE_URL, '?/') . '/'; ?>" />

        <link rel="favourites icon" href="<?php echo URL_PUBLIC; ?>favicon.ico"/>
        <link href="stylesheets/admin.css" media="screen" rel="Stylesheet" type="text/css" />
        <link href="stylesheets/toolbar.css" media="screen" rel="Stylesheet" type="text/css" />
        <link href="themes/<?php echo Setting::get('theme'); ?>/styles.css" id="css_theme" media="screen" rel="Stylesheet" type="text/css" />
        <link rel="stylesheet" href="stylesheets/queryLoader.css" type="text/css" />
        <link href="<?php echo(URL_PUBLIC) ?>tarambola/plugins/page_part_forms/upload/uploadify.css" type="text/css" rel="stylesheet" />
        <link href="stylesheets/codemirror.css" media="screen" rel="Stylesheet" type="text/css" />
        <link href="stylesheets/show-hint.css" media="screen" rel="Stylesheet" type="text/css" />
        <link href="stylesheets/fullscreen.css" media="screen" rel="Stylesheet" type="text/css" />
        <link href="stylesheets/analytics.css" media="screen" rel="Stylesheet" type="text/css" />
        
        <script type="text/javascript" charset="utf-8" src="javascripts/pace.min.js"></script>
        <script type="text/javascript" charset="utf-8" src="javascripts/prototype.js"></script>
        <script type="text/javascript" charset="utf-8" src="javascripts/effects.js"></script>
        <script type="text/javascript" charset="utf-8" src="javascripts/dragdrop.js"></script>
        <script type="text/javascript" charset="utf-8" src="javascripts/cp-datepicker.js"></script>
        <script type="text/javascript" charset="utf-8" src="javascripts/tarambola.js"></script>
        <script type="text/javascript" charset="utf-8" src="javascripts/control.textarea.js"></script>
        <script type="text/javascript" charset="utf-8" src="javascripts/codemirror.js"></script>
        <script type="text/javascript" charset="utf-8" src="addon/hint/show-hint.js"></script>
        <script type="text/javascript" charset="utf-8" src="addon/hint/javascript-hint.js"></script>
        <script type="text/javascript" charset="utf-8" src="addon/display/fullscreen.js"></script>
        <script type="text/javascript" charset="utf-8" src="mode/javascript/javascript.js"></script>
        

        <!-- Load the Client Library. Use the onload parameter to specify a callback function -->
        <script src="https://apis.google.com/js/client.js?onload=handleClientLoad"></script>

        <?php foreach (Plugin::$plugins as $plugin_id => $plugin): ?>
            <?php if (file_exists(CORE_ROOT . '/plugins/' . $plugin_id . '/' . $plugin_id . '.js')): ?>
                <script type="text/javascript" charset="utf-8" src="../tarambola/plugins/<?php echo $plugin_id . '/' . $plugin_id; ?>.js"></script>
            <?php endif; ?>
            <?php if (file_exists(CORE_ROOT . '/plugins/' . $plugin_id . '/' . $plugin_id . '.css')): ?>
                <link href="../tarambola/plugins/<?php echo $plugin_id . '/' . $plugin_id; ?>.css" media="screen" rel="Stylesheet" type="text/css" />
            <?php endif; ?>
        <?php endforeach; ?>
        <?php foreach (Plugin::$javascripts as $jscript_plugin_id => $javascript): ?>
            <script type="text/javascript" charset="utf-8" src="../tarambola/plugins/<?php echo $javascript; ?>"></script>
        <?php endforeach; ?>
        <?php $action = Dispatcher::getAction(); ?>
    </head>
    <body id="body_<?php echo $ctrl . '_' . Dispatcher::getAction(); ?>">
        <div id="header">
            <div id="site-title"><a href="<?php echo get_url(); ?>"><?php echo Setting::get('admin_title'); ?></a>
                <ul id="site-links">
                    <li><?php echo __('You are currently logged in as'); ?> <a href="<?php echo get_url('user/edit/' . AuthUser::getId()); ?>"><?php echo AuthUser::getRecord()->name; ?><img src ="../tarabackend/images/user.png"/></a><br/></li>
                    <li><a href="<?php echo get_url('login/logout'); ?>"><?php echo __('Log Out'); ?><img src ="../tarabackend/images/logout.png"/></a><br/></li>
                    <li><a href="<?php echo URL_PUBLIC . (USE_MOD_REWRITE ? '' : '?/'); ?>" target="_blank"><?php echo __('View Site'); ?><img src ="../tarabackend/images/globe.png"/></a></li>
                </ul>
            </div>


        </div>
        <div id="mainTabs">
            <ul id="apps">
                <li><img src="../tarabackend/images/home.png"/><a href="<?php echo get_url('page'); ?>"<?php if ($ctrl == 'page') echo ' class="current"'; ?>><?php echo __('Tree'); ?></a></li>
                <?php foreach (Plugin::$controllers as $plugin_name => $plugin): ?>
                    <?php if ($plugin->show_tab && (AuthUser::hasPermission($plugin->permissions) || AuthUser::hasPermission('administrator')) && $plugin_name != 'page_part_forms'): ?>
                        <?php Observer::notify('view_backend_list_plugin', $plugin_name, $plugin); ?>
                        <li class="plugin li_<? echo($plugin_name); ?>" ><img src="<?php echo get_url('../tarabackend/images/' . $plugin_name . '_icon.png'); ?>"/><a href="<?php echo get_url('plugin/' . $plugin_name); ?>"<?php if ($ctrl == 'plugin' && $action == $plugin_name) echo ' class="current"'; ?>><?php echo __($plugin->label); ?></a></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
            <ul id="settings">
                <img id="expand" class="svg" src="../tarabackend/images/settings.png" alt="Settings"/>
                <span id="config" style="display: none;"><? echo __('Settings'); ?></span><div id="aasa">
                    <?php foreach (Plugin::$controllers as $plugin_name => $plugin): ?>
                        <?php if ($plugin->show_tab && (AuthUser::hasPermission($plugin->permissions) || AuthUser::hasPermission('administrator')) && $plugin_name == 'page_part_forms'): ?>
                            <?php Observer::notify('view_backend_list_plugin', $plugin_name, $plugin); ?>
                            <li class="plugin right"><img src="../tarabackend/images/apps.png"/><a href="<?php echo get_url('plugin/' . $plugin_name); ?>"<?php if ($ctrl == 'plugin' && $action == $plugin_name) echo ' class="current"'; ?>><?php echo __($plugin->label); ?></a><?
                    if ($plugin_name == "modulos") {
                        echo("<ul class='modSubMenu'>");
                        echo($mod->getListMod());
                        echo("</ul>");
                    }
                            ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>


                    <?php if (AuthUser::hasPermission('administrator,developer')): ?>
                        <li class="right"><img src="../tarabackend/images/file.png"/><a href="<?php echo get_url('snippet'); ?>"<?php if ($ctrl == 'snippet') echo ' class="current"'; ?>><?php echo __('Snippets'); ?></a></li>
                        <li class="right"><img src="../tarabackend/images/picture.png"/><a href="<?php echo get_url('layout'); ?>"<?php if ($ctrl == 'layout') echo ' class="current"'; ?>><?php echo __('Layouts'); ?></a></li>
                    <?php endif; ?>
                    <?php if (AuthUser::hasPermission('administrator')): ?> 
                        <li class="right"><img src="../tarabackend/images/admin.png"/><a href="<?php echo get_url('setting'); ?>"<?php if ($ctrl == 'setting') echo ' class="current"'; ?>><?php echo __('Administration'); ?></a></li>
                        <li class="right"><img src="../tarabackend/images/shared.png"/><a href="<?php echo get_url('user'); ?>"<?php if ($ctrl == 'user') echo ' class="current"'; ?>><?php echo __('Users'); ?></a></li>
                    <?php endif; ?></div>
            </ul>
        </div>

        <div id="tara_content">

            <div id="main">
                
                <div id="content-wrapper"><div id="content">
                        <?php if (Flash::get('error') !== null): ?>
                            <div id="error" style="display:block;" ><?php echo Flash::get('error'); ?></div>
                            <script type="text/javascript" language="javascript">
                                // <![CDATA[
                                //Effect.Appear('error', {duration:.5});
                                // ]]>
                            </script>
                        <?php endif; ?>
                        <?php if (Flash::get('success') !== null): ?>
                            <div id="success" style="display:block;"><?php echo Flash::get('success'); ?></div>
                            <script type="text/javascript" language="javascript">
                                // <![CDATA[
                                //Effect.Appear('success', {duration:.5});
                                // ]]>
                            </script>

                        <?php endif; ?>
                        <!-- content -->
                        <?php echo $content_for_layout; ?>
                        <!-- end content -->

                    </div>

                </div> 
                
                <div id="footer">
                    <div id="footerLeft"><p>
                            <?php echo __('Thank you for using'); ?>  <a href="http://www.tarambola.pt/" target="_blank">Tarabackend (por: tarambola)</a> 1.2.2
                        </p><br/>   
                        <?php if (DEBUG): ?>
                            <p class="stats"><?php echo __('Page rendered in'); ?><?php echo execution_time(); ?> <?php echo __('seconds'); ?>
                                | <?php echo __('Memory usage:'); ?> <?php echo memory_usage(); ?></p>
                        <?php endif; ?></div>
                    <div id="footerRight">
                        <p><?php echo __('Optimized for Mozilla Firefox 3.0 and above'); ?></p><br/>
                        <p><?php echo __('Requires Flash Player 9.0.24 or higher'); ?></p></div>   
                </div>
           
            </div>
            <div id="sidebar-wrapper">
                <div id="sidebar">
                    <!-- sidebar -->
                    <?php echo isset($sidebar) ? $sidebar : '&nbsp;'; ?>
                    <!-- end sidebar -->
                </div>
            </div>
        </div>
        <hr class="hidden" />
        <script type='text/javascript'>
            jQuery(document).ready(function() {
                jQuery("#settings").mouseenter(
                    
                function() {
                    var jQueryli = jQuery(this);
                        
                    jQueryli.find("#config").css("display","inline");
                        
                }).mouseleave(
                    
                function() {
                    jQuery(this).find("#config").css("display","none");
                });
            });
        </script>
        <script type='text/javascript'>
            jQuery(document).ready(function(){
                jQuery('#expand').click
                (
                function(){
                    jQuery('#settings').css("bottom","100px");
                    event.stopPropagation();
                }
            )

            });
            
        </script>
        <script type='text/javascript'>
            jQuery(document).ready(function(){
                if(jQuery('#listaMsgNovas').css("display","none")){
                    jQuery('#mens').click
                    (
                    function(){
                        $('#listaMsgNovas').slideDown("fast");
                        return false;
                    })
                }
            });
            
        </script>
                <script type='text/javascript'>
            jQuery(function(){
                if(jQuery("#sidebar-wrapper").css('display')== 'none'){
                        jQuery("#main").css({'margin-top':'0'})

                    } else {
                         jQuery("#main").css({'margin-top':'30'})
                    }
            });
        </script>
        <script type='text/javascript'>
            jQuery(function(){
                var $ = jQuery;
                positionFooter();
                function positionFooter(){
                    if($(document).height() <= $(window).height()){
                        
                       
                        jQuery("#footer").css({'bottom':'0','position': "fixed", "width":"90%"})
                        
                        jQuery("#main").css({'height': $(window).height()-132})
                        
                    } else {
                        jQuery("#footer").css({'position': "relative","width":"100%"})
                    }
                }
                jQuery(window)
                .scroll(positionFooter)
                .resize(positionFooter);
            });
        </script>
        <script type='text/javascript' src='javascripts/queryLoader.js'></script>
        <script type='text/javascript'>
            //QueryLoader.selectorPreload = "body";
            //QueryLoader.init();
            jQuery(document).ready(function(){
              jQuery('#content').show(100);
            });
        </script>
    </body>
</html>