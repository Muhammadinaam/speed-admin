<?php

namespace MuhammadInaamMunir\SpeedAdmin;

use MuhammadInaamMunir\SpeedAdmin\Tree;

class SpeedAdminMenu{

    private $menus = [];

    public function __construct() {
        
    }

    public function addMenu(string $menu_type, array $menu)
    {
        if(!isset($this->menus[$menu_type]))
        {
            $this->menus[$menu_type] = new Tree();
        }

        $this->menus[$menu_type]->addItem($menu);
    }

    public function getMenu(string $menu_type)
    {
        return $this->menus[$menu_type]->getTree();
    }
}
