<?php

namespace MuhammadInaamMunir\SpeedAdmin\Http\Controllers;

use Illuminate\Http\Request;

class TenantOrganizationController extends SpeedAdminBaseController
{
    protected $model = \MuhammadInaamMunir\SpeedAdmin\Models\TenantOrganization::class;
    protected $index_url = 'admin/tenant-organizations';
}