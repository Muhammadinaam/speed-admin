<?php

namespace MuhammadInaamMunir\SpeedAdmin;

class SpeedAdminMenu{

    private $menu_array = [];

    public function addMenu(string $menu_type, array $menu)
    {
        // check if id already exists
        foreach($this->menu_array as $menu_item) {
            if($menu_item['id'] == $menu['id']) {
                throw new \Exception("Duplicate menu id not allowed. id [".$menu['id']."] already exists", 1);
                
            }
        }

        if ($menu['before_id'] != null) {
            $before_id_index = null;
            for ($i = 0; $i < count($this->menu_array); $i++) {
                if($menu['before_id'] == $this->menu_array['id']) {
                    $before_id_index = $i;
                    break;
                }
            }

            if($before_id_index != null) {
                // insert at specific index
                // https://stackoverflow.com/a/3797526/5013099
                array_splice( $this->menu_array, $before_id_index, 0, $menu );
            }
        } else {
            array_push($this->menu_array, $menu);
        }
    }

    public function getMenu(string $menu_type)
    {
        $menu = [];

        foreach ($this->menu_array as $array_index => $menu_item) {
            if ($menu_item['parent_id'] == null) {
                unset($this->menu_array[$array_index]);
                $children = $this->getChildrenRecursively($menu_item['id']);
                if(count($children) > 0) {
                    $menu_item['children'] = $children;
                }
                array_push($menu, $menu_item);
            }
        }

        return $menu;
    }

    private function getChildrenRecursively(string $parent_id) {
        $children = [];
        foreach ($this->menu_array as $array_index => $menu_item) {
            if ($menu_item['parent_id'] == $parent_id) {
                unset($this->menu_array[$array_index]);
                $children = $this->getChildrenRecursively($menu_item['id']);
                if(count($children) > 0) {
                    $menu_item['children'] = $children;
                }
                array_push($children, $menu_item);
            }
        }
        return $children;
    }
}
