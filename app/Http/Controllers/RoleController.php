<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    private $path, $entities, $actions;

    public function __construct()
    {
        $this->path = 'app.tenant.role.';
        $this->entities = ['keuringen', 'type', 'client', 'status'];
        $this->actions = ['create', 'read', 'update', 'delete'];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Role::where('tenant_id', getTenantId())->get();
        return view($this->path . 'list', compact('data'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view($this->path . 'add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request data
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        // Create new role
        $role = Role::create([
            'tenant_id' => getTenantId(),
            'name' => $request->name,
            'guard_name' => 'employe'
        ]);

        // Assign permissions based on checkboxes
        foreach (['create', 'read', 'update', 'delete'] as $action) {
            foreach ($request->input($action, []) as $item) {
                $permissionName = $action . '_' . $item;

                try {
                    $permission = Permission::findByName($permissionName, 'employe');
                } catch (\Spatie\Permission\Exceptions\PermissionDoesNotExist $e) {
                    // Optionally create the permission if it does not exist
                    $permission = Permission::create([
                        'tenant_id' => getTenantId(),
                        'name' => $permissionName,
                        'guard_name' => 'employe'
                    ]);
                }

                $role->givePermissionTo($permission);
            }
        }

        return back()->with('msg', 'Role and permissions created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view($this->path . 'edit')->with(['role' => $role, 'actions' => $this->actions, 'entities' => $this->entities]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        // Validate request or handle validation elsewhere
        $role->name = $request->name; // If you're allowing the name to be changed
        $role->save();
    
        // Clear existing permissions
        $role->permissions()->detach();
    
        // Assign new permissions based on checkboxes
        foreach (['create', 'read', 'update', 'delete'] as $action) {
            foreach ($request->input($action, []) as $item) {
                $permissionName = $action . '_' . $item;
                // Check if permission exists before assigning it
                $permission = Permission::where('name', $permissionName)->where('guard_name', $role->guard_name)->first();
                if ($permission) {
                    $role->givePermissionTo($permission);
                } else {
                    // Optionally create the permission if it does not exist and assign it
                    $permission = Permission::create(['tenant_id' => getTenantId(), 'name' => $permissionName, 'guard_name' => $role->guard_name]);
                    $role->givePermissionTo($permission);
                }
            }
        }
    
        return back()->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
