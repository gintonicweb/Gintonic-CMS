<?php
namespace GintonicCMS\Shell;

use Cake\Cache\Cache;
use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Composer\Console\Application as Composer;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * Update shell command.
 */
class GintonicShell extends Shell
{
    /**
     * main() method.
     *
     * @return bool|int Success or error code.
     */
    public function install()
    {
        $connections = ConnectionManager::configured();
        if (empty($connections)) {
            $this->out('Your database configuration was not found.');
            $this->out('Add your database connection information to config/app.php.');
            return false;
        }

        $this->migrate();
        $this->migrate('Users');
        $this->migrate('Permissions');

        $this->cleanup();
    }

    /**
     * Clears the cache
     *
     * @return void
     */
    public function cleanup()
    {
        Cache::clear(false, 'default');
        Cache::clear(false, '_cake_model_');
        Cache::clear(false, '_cake_core_');
        $this->dispatchShell('orm_cache clear');
        $this->dispatchShell('orm_cache build');
    }

    /**
     * Run the migration, optionally for a plugin
     *
     * @param string $plugin Plugin name (optional)
     * @return void
     */
    public function migrate($plugin = null)
    {
        $plugin = ($plugin === null)? '' : ' -p ' . $plugin;
        $this->dispatchShell('migrations migrate' . $plugin);
    }
}
