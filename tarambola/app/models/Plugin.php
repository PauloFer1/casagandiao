<?php




/**
 * @package tarambola
 * @subpackage models
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @version 0.9.5
 * @license http://www.gnu.org/licenses/gpl.html GPL License
 * @copyright Philippe Archambault, Martijn van der Kleijn 2008
 */

/**
 * class Plugin 
 *
 * Provide a Plugin API to make tarambola more flexible
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since tarambola version 0.9
 */
class Plugin
{
	static $plugins = array();
	static $plugins_infos = array();
    static $updatefile_cache = array();

	static $controllers = array();
    static $javascripts = array();

	/**
	 * Initialize all activated plugin by including is index.php file
	 */
	static function init()
	{
		self::$plugins = unserialize(Setting::get('plugins'));
		foreach (self::$plugins as $plugin_id => $tmp)
		{
			$file = CORE_ROOT.'/plugins/'.$plugin_id.'/index.php';
			if (file_exists($file))
				include $file;
			
			$file = CORE_ROOT.'/plugins/'.$plugin_id.'/i18n/'.I18n::getLocale().'-message.php';
			if (file_exists($file))
			{
				$array = include $file;
				I18n::add($array);
			}
		}
	}

	/**
	 * Set plugin informations (id, title, description, version and website)
	 *
	 * @param infos array Assoc array with plugin informations
	 */
	static function setInfos($infos)
	{
		self::$plugins_infos[$infos['id']] = (object) $infos;
	}

	/**
	 * Activate a plugin. This will execute the enable.php file of the plugin
     * when found.
	 *
	 * @param plugin_id string	The plugin name to activate
	 */
	static function activate($plugin_id)
	{
		self::$plugins[$plugin_id] = 1;
		self::save();

		$file = CORE_ROOT.'/plugins/'.$plugin_id.'/enable.php';
		if (file_exists($file))
			include $file;
        
        $class_name = Inflector::camelize($plugin_id).'Controller';        
        AutoLoader::addFile($class_name, self::$controllers[$plugin_id]->file);
	}
	
	/**
	 * Deactivate a plugin
	 *
	 * @param plugin_id string	The plugin name to deactivate
	 */
	static function deactivate($plugin_id)
	{
		if (isset(self::$plugins[$plugin_id]))
		{
			unset(self::$plugins[$plugin_id]);
			self::save();

			$file = CORE_ROOT.'/plugins/'.$plugin_id.'/disable.php';
			if (file_exists($file))
				include $file;
		}
	}

	/**
	 * Save activated plugins to the setting 'plugins'
	 */
	static function save()
	{
		Setting::saveFromData(array('plugins' => serialize(self::$plugins)));
	}

	/**
	 * Find all plugins installed in the plugin folder
	 *
	 * @return array
	 */
	static function findAll()
	{
		$dir = CORE_ROOT.'/plugins/';

		if ($handle = opendir($dir))
		{
			while (false !== ($plugin_id = readdir($handle)))
			{
				if ( ! isset(self::$plugins[$plugin_id]) && is_dir($dir.$plugin_id) && strpos($plugin_id, '.') !== 0)
				{
					$file = CORE_ROOT.'/plugins/'.$plugin_id.'/index.php';
					if (file_exists($file))
						include $file;
				}
			}
			closedir($handle);
		}

		ksort(self::$plugins_infos);
		return self::$plugins_infos;
	}

    /**
     * Check the file mentioned as update_url for the latest plugin version available.
     * Messages that can be returned:
     * unknown - returned if the plugin doesn't provide an update url
     * latest  - returned if the plugin version matches the version number registerd at the url
     * error   - returned if the update url could not be reached or for any other reason
     *
     * @param plugin     object A plugin object.
     *
     * @return           string The latest version number or a localized message.
     */
    static function checkLatest($plugin)
    {
        $data = null;

        if (!defined('CHECK_UPDATES') || !CHECK_UPDATES)
            return __('unknown');

        // Check if plugin has update file url set
        if ( ! isset($plugin->update_url) )
            return __('unknown');

        // Check if update file was already cached and is no older than 30 minutes
        if (array_key_exists($plugin->update_url, Plugin::$updatefile_cache) && (Plugin::$updatefile_cache[$plugin->update_url]['time'] + 30 * 60) < time()) {
            unset(Plugin::$updatefile_cache[$plugin->update_url]);
        }

        if (!array_key_exists($plugin->update_url, Plugin::$updatefile_cache)) {
            // Read and cache the update file
            if (!defined('CHECK_TIMEOUT')) define('CHECK_TIMEOUT', 5);
            $ctx = stream_context_create(array('http' => array('timeout' => CHECK_TIMEOUT)));

            if ( ! $data = file_get_contents($plugin->update_url, 0, $ctx)) {
                return __('error');
            }
            Plugin::$updatefile_cache[$plugin->update_url] = array('time' => time(), 'data' => $data);
        }

        $xml = simplexml_load_string(Plugin::$updatefile_cache[$plugin->update_url]['data']);

        foreach($xml as $node) {
            if ($plugin->id == $node->id)
                if ($plugin->version == $node->version)
                    return __('latest');
                else
                    return (string) $node->version;
        }

        return __('error');
    }


	/**
	 * Add a controller (tab) to the administration
	 *
	 * @param plugin_id     string  The folder name of the plugin
	 * @param label         string  The tab label
	 * @param permissions   string  List of roles that will have the tab displayed
	 *                              separate by coma ie: 'administrator,developer'
     * @param show_tab      boolean Either 'true' or 'false'. Defaults to true.
	 *
	 * @return void
	 */
	static function addController($plugin_id, $label, $permissions=false, $show_tab=true)
	{
		$class_name = Inflector::camelize($plugin_id).'Controller';

		self::$controllers[$plugin_id] = (object) array(
			'label' => ucfirst($label),
			'class_name' => $class_name,
			'file'	=> CORE_ROOT.'/plugins/'.$plugin_id.'/'.$class_name.'.php',
			'permissions' => $permissions,
            'show_tab' => $show_tab
		);
        
        AutoLoader::addFile($class_name, self::$controllers[$plugin_id]->file);
	}
    

    /**
     * Add a javascript file to be added to the html page for a plugin.
     * Backend only right now.
     *
     * @param $plugin_id    string  The folder name of the plugin
     * @param $file         string  The path to the javascript file relative to plugin root
     */
    static function addJavascript($plugin_id, $file)
    {
        if (file_exists(CORE_ROOT . '/plugins/' . $plugin_id . '/' . $file))
        {
            self::$javascripts[] = $plugin_id.'/'.$file;
        }
    }

    
    static function hasSettingsPage($plugin_id)
    {
        $class_name = Inflector::camelize($plugin_id).'Controller';
        
        return (array_key_exists($plugin_id, Plugin::$controllers) && method_exists($class_name, 'settings'));
    }
    
    
    static function hasDocumentationPage($plugin_id)
    {
        $class_name = Inflector::camelize($plugin_id).'Controller';
        
        return (array_key_exists($plugin_id, Plugin::$controllers) && method_exists($class_name, 'documentation'));
    }

    /**
     * Returns true if a plugin is enabled for use.
     *
     * @param string $plugin_id
     */
    static function isEnabled($plugin_id)
    {
        if (array_key_exists($plugin_id, Plugin::$plugins) && Plugin::$plugins[$plugin_id] == 1)
            return true;
        else
            return 0;
    }

    /**
     * Stores all settings from a name<->value pair array in the database.
     *
     * @param array $settings Array of name-value pairs
     * @param string $plugin_id     The folder name of the plugin
     */
    static function setAllSettings($array=null, $plugin_id=null)
    {
        if ($array == null || $plugin_id == null) return false;

        global $__tarambola_CONN__;
        $tablename = TABLE_PREFIX.'plugin_settings';
        $plugin_id = $__tarambola_CONN__->quote($plugin_id);

        $existingSettings = array();

        $sql = "SELECT name FROM $tablename WHERE plugin_id=$plugin_id";
        $stmt = $__tarambola_CONN__->prepare($sql);
        $stmt->execute();

        while ($settingname = $stmt->fetchColumn())
            $existingSettings[$settingname] = $settingname;

        $ret = false;

        foreach ($array as $name => $value)
        {
            if (array_key_exists($name, $existingSettings))
            {
                $name = $__tarambola_CONN__->quote($name);
                $value = $__tarambola_CONN__->quote($value);
                $sql = "UPDATE $tablename SET value=$value WHERE name=$name AND plugin_id=$plugin_id";
            }
            else
            {
                $name = $__tarambola_CONN__->quote($name);
                $value = $__tarambola_CONN__->quote($value);
                $sql = "INSERT INTO $tablename (value, name, plugin_id) VALUES ($value, $name, $plugin_id)";
            }

            $stmt = $__tarambola_CONN__->prepare($sql);
            $ret = $stmt->execute();
        }

        return $ret;
    }

    /**
     * Allows you to store a single setting in the database.
     *
     * @param string $name          Setting name
     * @param string $value         Setting value
     * @param string $plugin_id     Plugin folder name
     */
    static function setSetting($name=null, $value=null, $plugin_id=null)
    {
        if ($name == null || $value == null || $plugin_id == null) return false;

        global $__tarambola_CONN__;
        $tablename = TABLE_PREFIX.'plugin_settings';
        $plugin_id = $__tarambola_CONN__->quote($plugin_id);

        $existingSettings = array();

        $sql = "SELECT name FROM $tablename WHERE plugin_id=$plugin_id";
        $stmt = $__tarambola_CONN__->prepare($sql);
        $stmt->execute(array($plugin_id));

        while ($settingname = $stmt->fetchColumn())
            $existingSettings[$settingname] = $settingname;

        if (in_array($name, $existingSettings))
        {
            $name = $__tarambola_CONN__->quote($name);
            $value = $__tarambola_CONN__->quote($value);
            $sql = "UPDATE $tablename SET value=$value WHERE name=$name AND plugin_id=$plugin_id";
        }
        else
        {
            $name = $__tarambola_CONN__->quote($name);
            $value = $__tarambola_CONN__->quote($value);
            $sql = "INSERT INTO $tablename (value, name, plugin_id) VALUES ($value, $name, $plugin_id)";
        }

        $stmt = $__tarambola_CONN__->prepare($sql);
        $stmt->execute();
    }

    /**
     * Retrieves all settings for a plugin and returns an array of name-value pairs.
     * Returns empty array when unsuccessful in retrieving the settings.
     *
     * @param <type> $plugin_id
     */
    static function getAllSettings($plugin_id=null)
    {
        if ($plugin_id == null) return false;

        global $__tarambola_CONN__;
        $tablename = TABLE_PREFIX.'plugin_settings';
        $plugin_id = $__tarambola_CONN__->quote($plugin_id);

        $settings = array();

        $sql = "SELECT name,value FROM $tablename WHERE plugin_id=$plugin_id";
        $stmt = $__tarambola_CONN__->prepare($sql);
        $stmt->execute();

        while ($obj = $stmt->fetchObject()) {
            $settings[$obj->name] = $obj->value;
        }

        return $settings;
    }

    /**
     * Returns the value for a specified setting.
     * Returns false when unsuccessful in retrieving the setting.
     *
     * @param <type> $name
     * @param <type> $plugin_id
     */
    static function getSetting($name=null, $plugin_id=null)
    {
        if ($name == null || $plugin_id == null) return false;

        global $__tarambola_CONN__;
        $tablename = TABLE_PREFIX.'plugin_settings';
        $plugin_id = $__tarambola_CONN__->quote($plugin_id);
        $name = $__tarambola_CONN__->quote($name);

        $existingSettings = array();

        $sql = "SELECT value FROM $tablename WHERE plugin_id=$plugin_id AND name=$name LIMIT 1";
        $stmt = $__tarambola_CONN__->prepare($sql);
        $stmt->execute();

        if ($value = $stmt->fetchColumn()) return $value;
        else return false;

    }
} // end Plugin class
