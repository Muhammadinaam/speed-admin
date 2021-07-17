<?php

namespace MuhammadInaamMunir\SpeedAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use MuhammadInaamMunir\SpeedAdmin\Traits\Crud;
use MuhammadInaamMunir\SpeedAdmin\Misc\GridHelper;
use MuhammadInaamMunir\SpeedAdmin\Traits\UsesUuid;

class Setting extends Model{
    
    use Crud, UsesUuid;

    protected $appends = ['text'];
    public $timestamps = false;

    public function __construct()
    {
        parent::__construct();

        $this->setSingularTitle('Setting');
        $this->setPluralTitle('Settings');
        $this->setPermissionId('setting');
    }

    public function getTextAttribute()
    {
        return 'settings';
    }

    public function getGridQuery($request)
    {
        return $this;
    }
}