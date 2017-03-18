<?php




if(version_compare(PHP_VERSION, '5.0.0', '<')){
	echo "I'm sorry, PHP version 5 is needed to run this website. <br>";
	echo "The current PHP version is: ". phpversion() . "<br>";
	echo "Your web hosting provider can usually push a button to upgrade you, please contact them.";
	exit;
}


define("_NEWSLETTER_VERSION",1.8);
define(SERVER_URL, $_SERVER['DOCUMENT_ROOT'].'/montanea/' );
/*
session_start();

header('Content-Type: text/html; charset=UTF-8');
*/
ob_start();// so we can header:redirect later on

//if(is_file("config.php"))
{
	require_once(SERVER_URL.'tarambola/plugins/newsletter/views/config.php');
}
require_once(SERVER_URL.'tarambola/plugins/newsletter/views/php/functions.php');
require_once(SERVER_URL.'tarambola/plugins/newsletter/views/php/class.newsletter.php');
$newsletter = new newsletter();

if(defined("_DB_NAME")){
	
	require_once(SERVER_URL.'tarambola/plugins/newsletter/views/php/database.php');
	
	$db = db_connect();
        if(isset($_REQUEST['p']))
            {
            if($_REQUEST['p']!='setup'){
		$newsletter->init();}
            }

}

$show_menu = (isset($_REQUEST['hide_menu'])) ? false : true;

ob_start();
if(defined("_DB_NAME") && $show_menu){ ?>
<!--	<div id="loggedin">
		 <?php echo _l('Welcome');?> | <a href="?logout"><?php echo _l('logout');?></a> <br>
		<iframe src="<?php echo $newsletter->version_url();?>" frameborder="0" style="overflow:none; width:150px; height:45px; background-color:transparent;" scrolling="No"></iframe>
	</div>
        -->
<?php } ?>
        <script language="javascript" src="<?php echo URL_PUBLIC; ?>tarambola/plugins/newsletter/views/layout/js/jquery.js"></script>
	<script language="javascript" src="<?php echo URL_PUBLIC; ?>tarambola/plugins/newsletter/views/layout/js/jquery.flot.js"></script>
	<script language="javascript" src="<?php echo URL_PUBLIC; ?>tarambola/plugins/newsletter/views/layout/js/javascript.js"></script>
	<!--[if IE]><script language="javascript" type="text/javascript" src="<?php echo URL_PUBLIC; ?>tarambola/plugins/newsletter/views/layout/js/excanvas.js"></script><![endif]-->
	<link rel="stylesheet" href="<?php echo URL_PUBLIC; ?>tarambola/plugins/newsletter/views/layout/css/styles.css" type="text/css" />
        <script>
            jQuery.noConflict();
        </script>
	<div id="wrapper">
	<?php if(defined("_DB_NAME")){ ?>
		<?php if($show_menu){ ?>
			<div id="menu">
			<a href="plugin/newsletter?p=home"><?php echo __('Dashboard');?></a> |
			<a href="plugin/newsletter?p=create"><?php echo __('Create Newsletter');?></a> |
			<a href="plugin/newsletter?p=past"><?php echo __('Newsletters Sent');?></a> |
		<!--	<a href="plugin/newsletter?p=campaign"><?php //echo __('Campaigns');?></a> | -->
			<a href="plugin/newsletter?p=groups"><?php echo __('Groups');?></a> |
			<a href="plugin/newsletter?p=settings"><?php echo __('Settings');?></a> |
			<a href="plugin/newsletter?p=members"><?php echo __('List of Members');?></a> |
			<a href="plugin/newsletter?p=members_add"><?php echo __('Add Members');?></a>
			</div>
		<?php
		}
		$page=false;
		if(isset($_REQUEST['p'])){
			$page = basename($_REQUEST['p']);
		}
		if(!$page || !is_file(SERVER_URL.'tarambola/plugins/newsletter/views/php/pages/'.$page.'.php')){
			$page = "home";
		}
                    include(SERVER_URL.'tarambola/plugins/newsletter/views/php/pages/'.$page.'.php');
                }else
                    {
                        include(SERVER_URL.'tarambola/plugins/newsletter/views/php/pages/setup.php');
                    }
	?>
	</div>
<?php
$inner_content = ob_get_clean();


// basic header split out so people can keep configuration between versions
//include(SERVER_URL.'tarambola/plugins/newsletter/views/layout/system_header.php');
echo $inner_content;
//include(SERVER_URL.'tarambola/plugins/newsletter/views/layout/system_footer.php');

?>
