<?php

class MY_Cache extends CI_Cache {

    public function get_adapter()
    {
        return $this->_adapter;
    }

}