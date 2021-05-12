<?php

namespace MuhammadInaamMunir\SpeedAdmin\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Events\RouteMatched;
use MuhammadInaamMunir\SpeedAdmin\Misc\GridHelper;

trait TenantOrganization
{
    protected static function booted()
    {
        parent::booted();

        static::addGlobalScope('belongs_to_tenant_organization', function (Builder $builder) {
            $user = \Auth::user();
            if (\SpeedAdminHelpers::userHasAccessToAllTenantOrganizations($user)) {
                return;
            }
            $builder->where('tenant_organization_id', '=', $current_user->tenant_organization_id);
        });
    }

    public function addTenantOrganizationGridColumn()
    {
        $this->addGridColumn([
            'id' => 'tenant_organization', 
            'title' => __('Tenant Organization'), 
            'order_by' => 'tenant_organizations.name', 
            'search_by' => 'tenant_organizations.name', 
            'render_function' => function ($obj) {
                return $obj->tenant_organization_name != null ? $obj->tenant_organization_name : '';
            }
        ]);
    }

    public function addTenantOrganizationSelectorFormItem($parent_id)
    {
        $this->addFormItem([
            'id' => 'tenant_organization',
            'parent_id' => $parent_id,
            'type' => 'belongsTo',
            'relation_name' => 'tenantOrganization',
            'model' => '\MuhammadInaamMunir\SpeedAdmin\Models\TenantOrganization',
            'where' => function($query){
                return $query->where('is_active', 1);
            },
            'show_add_new_button' => true,
            'validation_rules' => [],
            'label' => __('Tenant Organization'),
            'name' => 'tenant_organization',
            'is_visible' => function() {
                $user = \SpeedAdminHelpers::currentUser();
                return \SpeedAdminHelpers::userHasAccessToAllTenantOrganizations($user);
            }
        ]);
    }

    public function tenantOrganization()
    {
        return $this->belongsTo(\MuhammadInaamMunir\SpeedAdmin\Models\TenantOrganization::class);
    }

    public function addTenantOrganizationColumnToQuery($query)
    {
        $query->leftJoin('tenant_organizations', 'tenant_organizations.id', '=', 'users.tenant_organization_id')
            ->addSelect('tenant_organizations.name as tenant_organization_name');

        return $query;
    }
}