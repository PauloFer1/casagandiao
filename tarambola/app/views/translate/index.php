<?php
/**
 
/**
 * @package tarambola
 * @subpackage views
 *
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @version 0.1
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Martijn van der Kleijn, 2008
 */
?>

<h1>Translating tarambola</h1>
<p>
    This will attempt to give you some information on how you can help out by translating
    tarambola or one of its plugins into your native language. Below are easy-to-follow steps
    for translating tarambola or a plugin.
</p>
<h2>For plugin developers</h2>
<p>
    You can provide support for translations easily.
    Any string that you output to the screen should be printed as in the following examples:
</p>
<pre>
&lt;?php echo __('A translatable string.'); ?&gt;

&lt;?php echo __('A translatable string with a %param% system.', array('%param%' => 'parameter')); ?&gt;
</pre>
<p><em>
    Please see the I18n.php helper class file for a more detailed description of the __($string, $args) function.
</em></p>
<p>
    Lastly, just add a directory called "i18n" to the root of your plugin.<br/>
    Translators can place their translation files in that directory.<br/>
    The format for the filenames is: "en-message.php" where "en" is the iso_639_1 language code for the translation.
</p>
<h2>For translators</h2>
<p>
    <strong>Before you begin, please read this...</strong> The option "<?php echo __('Create Plugin templates'); ?>"
    in the sidebar will only generate translation file templates for the plugins which are installed <strong>on disk</strong>.
    The plugins <strong>do not have to be enabled or even working</strong>, they just have to be present on disk in the
    plugins directory.
</p>
<p>
    Just follow these easy steps to create a translations.
</p>
<p><strong>To translate tarambola Core:</strong></p>
<ol style="list-style-position: inside;">
    <li>Create a new translation file called "nl-message.php" where "nl" is the iso_639_1 language code for your language.</li>
    <li>Click on "<?php echo __('Create Core template'); ?>" in the sidebar.</li>
    <li>Copy the generated output.</li>
    <li>Paste it into your newly created translation file.</li>
    <li>Translate every string by filling the empty strings on the right of the => mark.</li>
    <li>Place the newly created translation file in tarambola's i18n directory. (This is: .../tarambola/app/i18n)</li>
    <li>Go to the "Administration" section of tarambola.</li>
    <li>On the "Settings" tab, select your newly added language from the dropdown box and click Save.</li>
    <li>View and test your new translation of the tarambola interface.</li>
    <li>Go to http://forum.madebytarambola.com/ let us know about your translation!</li>
    <li>Thanks for helping out...</li>
</ol>
<p><em>Important notes:</em> please do <strong>not</strong> translate variable names like ":name".</p>

<p><strong>To translate a tarambola plugin:</strong></p>
<ol style="list-style-position: inside;">
    <li>Create a new translation file called "nl-message.php" where "nl" is the iso_639_1 language code for your language.</li>
    <li>Click on "<?php echo __('Create Plugin templates'); ?>" in the sidebar.</li>
    <li>Copy the piece of generated output for the plugin you want to translate.</li>
    <li>Paste it into your newly created translation file for the plugin you want to translate.</li>
    <li>Translate every string by filling the empty strings on the right of the => mark.</li>
    <li>Place the newly created translation file in the plugin's i18n directory. (This is: .../tarambola/plugins/&lt;pluginname&gt;/i18n)</li>
    <li>Go to the "Administration" section of tarambola.</li>
    <li>On the "Settings" tab, select your newly added language from the dropdown box and click Save.</li>
    <li>View and test your new translation of the tarambola interface.</li>
    <li>Go to http://forum.madebytarambola.com/ let us know about your translation!</li>
    <li>Thanks for helping out...</li>
</ol>
<p><em>Important notes:</em> please do <strong>not</strong> translate variable names like ":name".</p>

<p><strong>To update an earlier created translation:</strong></p>
<p>
    These steps assume the earlier translation was created with this tool. If it wasn't, you'll have slightly more work to do.
</p>
<ol style="list-style-position: inside;">
    <li>On the OLD tarambola installation, generate a blank template.</li>
    <li>Update tarambola and/or its plugins to the latest release.</li>
    <li>On the NEW tarambola installation, generate a blank template.</li>
    <li>Use a tool like diff to spot any changes that were made.</li>
    <li>Based on the diff output, update your old translation file so it conforms to the new blank template.</li>
</ol>