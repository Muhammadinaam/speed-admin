<?php

namespace MuhammadInaamMunir\SpeedAdmin;

class Tree{
    private $item_array = [];

    public function removeItem($id)
    {
        $new_item_array = [];
        foreach($this->item_array as $item_item) {
            if($item_item['id'] != $id) {
                array_push($new_item_array, $item_item);
            }
        }
        $this->item_array = $new_item_array;
    }

    public function addItem(array $item)
    {
        // check if id already exists
        foreach($this->item_array as $item_item) {
            if($item_item['id'] == $item['id']) {
                throw new \Exception("Duplicate item id not allowed. id [".$item['id']."] already exists", 1);
            }
        }

        if( str_contains($item['id'], '.') )
        {
            throw new \Exception("dot [.] is not allowed in id", 1);     
        }

        $before_id = isset($item['before_id']) ? $item['before_id'] : null;
        if ($before_id != null) {
            $before_id_index = null;
            for ($i = 0; $i < count($this->item_array); $i++) {
                if($before_id == $this->item_array['id']) {
                    $before_id_index = $i;
                    break;
                }
            }

            if($before_id_index != null) {
                // insert at specific index
                // https://stackoverflow.com/a/3797526/5013099
                array_splice( $this->item_array, $before_id_index, 0, $item );
            }
        } else {
            array_push($this->item_array, $item);
        }
    }

    public function getFlatTreeArray()
    {
        return $this->item_array;
    }

    public function getTree()
    {
        $tree = [];

        $item_array = $this->item_array;

        foreach ($this->item_array as $array_index => $item_item) {
            $parent_id = isset($item_item['parent_id']) ? $item_item['parent_id'] : null;
            if ($parent_id == null) {
                unset($this->item_array[$array_index]);
                $children = $this->getChildrenRecursively($item_item['id']);
                if(count($children) > 0) {
                    $item_item['children'] = $children;
                }
                array_push($tree, $item_item);
            }
        }

        //reset item_array
        $this->item_array = $item_array;

        return $tree;
    }

    private function getChildrenRecursively(string $parent_id) {
        $children = [];
        foreach ($this->item_array as $array_index => $item_item) {
            if (isset($item_item['parent_id']) && $item_item['parent_id'] == $parent_id) {
                unset($this->item_array[$array_index]);
                $item_children = $this->getChildrenRecursively($item_item['id']);
                if(count($item_children) > 0) {
                    $item_item['children'] = $item_children;
                }
                array_push($children, $item_item);
            }
        }
        return $children;
    }
}