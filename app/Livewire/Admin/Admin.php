<?php

namespace App\Livewire\Admin;

use App\Enums\Status;
use App\Enums\AdminRoles;
use App\Http\Requests\Admin\ActionRequest;
use Livewire\Component;
use App\Models\Admin\Admin as AdminModel;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\AdminFormRequest;

class Admin extends Component
{
    use WithPagination;

    public $isModal = false, $isUpdate = false,$page=10,
    $action,$name,$searchAdmin, $email, $password, $role, $status, $adminId, $enumRoles, $enumStatus;

    public function mount()
    {
        $this->enumStatus = Status::cases();
        $this->enumRoles = AdminRoles::cases();
    }

   

    public function render()
    {

        $admins = AdminModel::
        when($this->searchAdmin,function($query){
            $query->where('name','Like',"%{$this->searchAdmin}%");
            $query->orWhere('email','Like',"%{$this->searchAdmin}%");
            $query->orWhere('role','Like',"%{$this->searchAdmin}%");
        })->
        paginate($this->page)->withQueryString();
        // $admins->setPath(route('admin.admin.index'));
        return view('admin.livewire.admin.index', [
            'admins' => $admins
        ])->layout('layouts.admin-livewire');
    }

    public function updatingSearch(){
        $this->resetPage();
    }

    public function resetInputFields()
    {
        $this->name = null;
        $this->email = null;
        $this->password = null;
        $this->role = null;
        $this->status = null;
        $this->adminId = null;
        $this->isUpdate = false;
        $this->resetErrorBag();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModal = true;
        $this->isUpdate = false;
    }

    public function close()
    {
        $this->isModal = false;
        $this->resetInputFields();
    }

    public function store()
    {
        $request=new AdminFormRequest($this->adminId);
        $this->validate($request->rules());
        try {
            AdminModel::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
                'status' => $this->status
            ]);
            $this->dispatch('success', message: 'Admin Created Successfully.');
            $this->resetInputFields();
            $this->isModal = false;
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Something went wrong. Please try again.');
        }
    }

    public function edit($id)
    {
        try {
            $this->resetInputFields();
            $admin = AdminModel::findOrFail($id);
            $this->adminId = $admin->id;
            $this->name = $admin->name;
            $this->email = $admin->email;
            $this->role = $admin->role;
            $this->status = $admin->status;
            $this->isModal = true;
            $this->isUpdate = true;
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'User not found.');
        }
    }

    public function update()
    {
        $request=new AdminFormRequest($this->adminId);
        $this->validate($request->rules());
        try {
            $admin = AdminModel::findOrFail($this->adminId);
            $admin->update([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password ? Hash::make($this->password) : $admin->password,
                'role' => $this->role,
                'status' => $this->status
            ]);
            $this->dispatch('success', message: 'Admin Updated Successfully.');
            $this->resetInputFields();
            $this->isModal = false;
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error while updating: ' . $e->getMessage());
        }
    }

    #[On('deleteAdmin')]
    public function deleteAdmin($id)
    {
        try {
            $admin = AdminModel::findOrFail($id);
            $admin->delete();
            $this->dispatch('success', message: 'Admin Deleted Successfully.');
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Something went wrong. Please try again.');
        }
    }

    #[On('activateAdmin')]
    public function activateSelected($values){
        $request = new ActionRequest(['values' => $values]);
        $validated = $request->validate();
        try{
            AdminModel::whereIn('id',$validated['values'])->update(['status'=>Status::ACTIVE->value]);
            $this->dispatch('success', message: 'Admins updated Successfully.');
        }catch(\Exception $e){
            $this->dispatch('error', message: 'Something went wrong. Please try again.');

        }
    }
    #[On('deactivateAdmin')]
    public function deactivateSelected($values){
        $request = new ActionRequest(['values' => $values]);
        $validated = $request->validate();
        try{
            AdminModel::whereIn('id',$validated['values'])->update(['status'=>Status::INACTIVE->value]);
            $this->dispatch('success', message: 'Admins updated Successfully.');
        }catch(\Exception $e){
            $this->dispatch('error', message: 'Something went wrong. Please try again.');

        }
    }

    #[On('deleteAdmin')]
    public function deleteSelected($values){
        $request = new ActionRequest(['values' => $values]);
        $validated = $request->validate();
        try{
            AdminModel::whereIn('id',$validated['values'])->delete();
            $this->dispatch('success', message: 'Admins updated Successfully.');
        }catch(\Exception $e){
            $this->dispatch('error', message: 'Something went wrong. Please try again.');

        }
    }
    
   
}