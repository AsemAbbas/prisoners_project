<?php

namespace App\Livewire\Dashboard\Main;

use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class ListUsers extends Component
{
    use WithPagination;

    public object $Users_;
    public ?object $UserStatus;
    public ?string $Search = null;
    public array $state = [];

    public ?string $user_status = null;

    protected string $paginationTheme = 'bootstrap';

    public function addNew(): void
    {
        $this->state = [];
        $this->dispatch('showForm');
    }

    /**
     * @throws ValidationException
     */
    public function createUser(): void
    {
        $validation = Validator::make($this->state, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ])->validate();

        $validation['password'] = Hash::make($validation['password']);
        $validation['isAdmin'] = false;

        User::query()->create($validation);

        $this->dispatch('hideForm');
    }


    public function ShowUserStatus($role, User $user): void
    {
        $this->UserStatus = $user;
        $this->user_status = $role;
        $this->dispatch('UserStatus');
    }

    public function changeUserStatus(): void
    {
        $UserStatus = join(",", array_column(UserStatus::cases(), 'value'));

        $validation = $this->validate(['user_status' => 'required|in:' . $UserStatus]);
        if ($validation){
            $this->UserStatus->update(['user_status' => $this->user_status]);
            $this->dispatch('hideUserStatus');
            $this->user_status = null;
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(): void
    {
        $this->Users_->delete();
        $this->dispatch('hide_delete_modal');
    }

    public function delete(User $city): void
    {
        $this->Users_ = $city;

        $this->dispatch('show_delete_modal');
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $Users = $this->getUsersProperty()->paginate(10);
        return view('livewire.dashboard.main.list-users', compact('Users'));
    }

    public function getUsersProperty()
    {
        return User::query()
            ->when(isset($this->Search), function ($query) {
                $query->where('name', 'LIKE', '%' . $this->Search . '%');
            });
    }
}
