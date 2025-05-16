<?php

namespace App\Livewire\Admin;

use App\Enums\AdminRoles;
use App\Enums\Status;
use App\Http\Requests\Admin\ActionRequest;
use App\Models\Admin\Admin as AdminModel;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class AdminList extends Component
{
    use WithPagination;

    public $searchAdmin;

    public $perPage = 10;

    public $statusActiveInactive = null;

    public $selectRole = null;

    public $enumRoles;

    public $enumStatus;

    public function mount()
    {
        $this->enumStatus = Status::cases();
        $this->enumRoles = AdminRoles::cases();
    }

    #[on('adminList')]
    public function render()
    {
        $admins = AdminModel::when($this->searchAdmin, function ($query) {
            $query->where(function ($q) {
                $q->where('name', 'LIKE', "%{$this->searchAdmin}%")
                    ->orWhere('email', 'LIKE', "%{$this->searchAdmin}%");
            });
        })
            ->when($this->statusActiveInactive, function ($query) {
                $query->where('status', $this->statusActiveInactive);
            })
            ->when($this->selectRole, function ($query) {
                $query->where('role', $this->selectRole);
            })
            ->orderBy('created_at', 'DESC')->paginate($this->perPage)->withQueryString();

        return view('admin.livewire.admin.index', [
            'admins' => $admins,
        ])->layout('layouts.admin-livewire');
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['searchAdmin', 'selectRole', 'statusActiveInactive', 'perPage'])) {
            $this->resetPage();
        }
    }

    #[On('activateAdmin')]
    public function activateSelected($values)
    {
        $request = new ActionRequest(['values' => $values]);
        $request->validate($request->rules());
        try {
            AdminModel::whereIn('id', $values)->update(['status' => Status::ACTIVE->value]);
            $this->dispatch('success', message: 'Admins activated successfully.');
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Something went wrong. Please try again.');
        }
    }

    #[On('deactivateAdmin')]
    public function deactivateSelected($values)
    {
        $request = new ActionRequest(['values' => $values]);
        $request->validate($request->rules());
        try {
            AdminModel::whereIn('id', $values)->update(['status' => Status::INACTIVE->value]);
            $this->dispatch('success', message: 'Admins deactivated successfully.');
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Something went wrong. Please try again.');
        }
    }

    #[On('deleteSelectedAdmin')]
    public function deleteSelected($values)
    {
        $request = new ActionRequest(['values' => $values]);
        $request->validate($request->rules());
        try {
            AdminModel::whereIn('id', $values)->delete();
            $this->dispatch('success', message: 'Admins deleted successfully.');
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Something went wrong. Please try again.');
        }
    }

    #[On('deleteAdmin')]
    public function deleteAdmin($id)
    {
        try {
            $admin = AdminModel::findOrFail($id);
            if ($admin->role == AdminRoles::SUPER_ADMIN->value) {
                return $this->dispatch('success', message: "Super admin can't be deleted.");
            }
            $admin->delete();
            $this->dispatch('success', message: 'Admin deleted successfully.');
            $this->resetPage();
        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Something went wrong. Please try again.');
        }
    }

    public function rolesEdit($id)
    {
        $this->dispatch('openRolesModal', $id);
    }
}
