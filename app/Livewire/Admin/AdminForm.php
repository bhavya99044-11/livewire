<?php

namespace App\Livewire\Admin;

use App\Enums\AdminRoles;
use App\Enums\Status;
use App\Http\Requests\Admin\AdminFormRequest;
use App\Models\Admin\Admin as AdminModel;
use App\Models\Admin\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class AdminForm extends Component
{
    public $isModal = false;
    public $isUpdate = false;
    public $nextPage;
    public $userPermissions = [];
    public $permissionData;
    public $permission = [];
    public $name;
    public $email;
    public $password;   
    public $status;
    public $adminId;
    public $enumRoles;
    public $enumStatus;
    public $user;

    protected $listeners = ['openRolesModal'];

    public function mount()
    {
        $this->user = Auth::guard('admin')->user();
        $this->permissionData = Permission::whereNotIn('module', ['admin', 'permission'])->get();
        $this->enumStatus = Status::cases();
        $this->enumRoles = AdminRoles::cases();
    }

    public function render()
    {
        return view('admin.livewire.admin.form');
    }

    public function resetInputFields()
    {
        $this->name = null;
        $this->email = null;
        $this->password = null;
        $this->status = null;
        $this->adminId = null;
        $this->isUpdate = false;
        $this->nextPage = null;
        $this->permission = [];
        $this->resetErrorBag();
    }

    #[On('createAdmin')]
    public function create()
    {
        $this->resetInputFields();
        $this->isModal = true;
        $this->isUpdate = false;
    }

    #[On('editAdmin')]
    public function edit($id)
    {
        try {
            $this->resetInputFields();
            $admin = AdminModel::findOrFail($id);

            $this->adminId = $admin->id;
            $this->name = $admin->name;
            $this->email = $admin->email;
            $this->status = $admin->status;
            $this->permission = $admin->permissions?->pluck('id')->toArray();
            $this->isModal = true;
            $this->isUpdate = true;
        } catch (\Exception $e) {
            $this->dispatch('error', message: __('messages.admin.not_found'));
        }
    }

    public function close()
    {
        $this->isModal = false;
        $this->resetInputFields();
    }

    public function store()
    {
        try {
            DB::beginTransaction();

            $admin = AdminModel::create([
                'name'     => $this->name,
                'email'    => $this->email,
                'password' => Hash::make($this->password),
                'role'     => AdminRoles::ADMIN->value,
                'status'   => $this->status,
            ]);

            $admin->permissions()->attach($this->permission);

            $this->dispatch('success', message: __('messages.admin.created'));
            $this->resetInputFields();
            $this->isModal = false;

            DB::commit();
            $this->dispatch('adminList');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', message: __('messages.general.error_try_again'));
        }
    }

    public function update()
    {
        try {
            DB::beginTransaction();

            $admin = AdminModel::findOrFail($this->adminId);
            $admin->update([
                'name'     => $this->name,
                'email'    => $this->email,
                'password' => $this->password ? Hash::make($this->password) : $admin->password,
                'status'   => $this->status,
            ]);

            $admin->permissions()->sync($this->permission);

            DB::commit();
            $this->dispatch('success', message: __('messages.admin.updated'));
            $this->resetInputFields();
            $this->isModal = false;
            $this->dispatch('adminList');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', message: __('messages.admin.update_error') . ': ' . $e->getMessage());
        }
    }

    public function nextForm()
    {
        $request = new AdminFormRequest($this->adminId);
        $this->validate($request->rules());
        $this->nextPage = true;
    }
}
