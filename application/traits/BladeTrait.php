<?php
namespace App\Traits;

use duncan3dc\Laravel\BladeInstance;

trait BladeTrait {
    public function view($layout = null, $params = array()) {
        $views = APPPATH . '/../resources/views';
        $cache = APPPATH . '/cache';
        $this->blade = new BladeInstance($views, $cache);

        echo $this->blade->render($layout, $params+array('monolith' => true));
    }
}