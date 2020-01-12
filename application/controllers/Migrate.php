<?php defined('BASEPATH') || exit('No direct script access allowed');

class Migrate extends MX_Controller
{
    public function __construct()
    {
        if (ENVIRONMENT != 'development') {
            show_404();
        }

        $this->load->library('migration');
        $this->config->load('migration');
    }

    /**
     * Migrate Database
     *
     * @param  int $version
     * @return mixed
     */
    public function do($version = null)
    {
        if ( ! is_null($version)) {
            $version = $this->migration->version($version);
        } else {
            $version = $this->migration->latest();
        }

        if ($version === FALSE) {
            show_error($this->migration->error_string());
        }
        elseif ($version === TRUE) {
            show_error('Migration version "' . $version . '" not found.');
        }

        echo 'Migrated to version: ' . $version . PHP_EOL;
    }

    /**
     * Make Migration File
     *
     * @param  string $name
     * @return mixed
     */
    public function make($name)
    {
        $skeleton_file = config_item('migration_path').'00000000000000_create_some_table.php.example';
        $migration_file = date('YmdHis').'_'.strtolower($name).'.php';
        $migration_filepath = config_item('migration_path').$migration_file;

        $skeleton = read_file($skeleton_file);

        $skeleton = str_replace('Create_some_table', ucfirst($name), $skeleton);

        file_put_contents($migration_filepath, $skeleton);

        echo 'New Migration File "'.$migration_file.'" created.'.PHP_EOL;
    }

}