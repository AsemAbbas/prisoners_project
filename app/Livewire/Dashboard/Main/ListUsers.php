<?php

namespace App\Livewire\Dashboard\Main;

use App\Enums\UserStatus;
use App\Models\City;
use App\Models\User;
use App\Models\UserLog;
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
    public ?string $user_id = null;
    public array $state = [];
    public bool $ShowModal = false;

    public bool $AllCities = false;
    protected string $paginationTheme = 'bootstrap';

    public function addNew(): void
    {
        $this->ShowModal = false;
        $this->AllCities = false;
        $this->state = [];
        $this->dispatch('showForm');
    }

    public function logShow($user_id): void
    {
        $this->user_id = $user_id;
        $this->dispatch('show_log');
    }

    public function edit(User $user): void
    {
        $this->ShowModal = true;
        $this->Users_ = $user;

        $cities = $user->City->pluck('pivot')->toArray() ?? [];
        $city_values = [];

        if (!empty($cities)) {
            $cityIds = array_column($cities, 'city_id');
            $city_values = array_fill_keys($cityIds, true);
        }
        $this->state = [
            'id' => $user->id ?? null,
            'name' => $user->name ?? null,
            'user_status' => $user->user_status ?? null,
            'email' => $user->email ?? null,
            'cities' => $city_values,
        ];

        $this->dispatch('showForm');
    }

    /**
     * @throws ValidationException
     */
    public function createUser(): void
    {
        if (isset($this->state['cities'])) {
            $this->state['cities'] = array_filter($this->state['cities']);
        }
        $UserStatus = join(",", array_column(UserStatus::cases(), 'value'));

        $validation = Validator::make($this->state, [
            'name' => 'required',
            'user_status' => 'required|in:' . $UserStatus,
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'cities' => 'nullable|array'
        ])->validate();


        $validation['password'] = Hash::make($validation['password']);

        $user = User::query()->create($validation);

        if (isset($validation['cities']))
            $user->City()->attach(array_keys($validation['cities']));

        $this->dispatch('hideForm');
    }

    /**
     * @throws ValidationException
     */
    public function updateUser(): void
    {
        if (isset($this->state['cities'])) {
            $this->state['cities'] = array_filter($this->state['cities']);
        }

        $UserStatus = join(",", array_column(UserStatus::cases(), 'value'));

        $validation = Validator::make($this->state, [
            'name' => 'required',
            'user_status' => 'required|in:' . $UserStatus,
            'email' => 'required|email|unique:users,email,' . $this->state['id'],
            'password' => 'nullable|min:8',
            'cities' => 'nullable|array'
        ])->validate();

        if (isset($validation['password'])) {
            $validation['password'] = Hash::make($validation['password']);
        }
        $this->Users_->update($validation);

        if (isset($validation['cities']))
            $this->Users_->City()->sync(array_keys($validation['cities']));

        $this->dispatch('hideForm');
    }

    public function updatedAllCities(): void
    {
        if ($this->AllCities) {
            $cities_ids = City::query()->pluck('id')->toArray();
            $cities_true = array_fill_keys($cities_ids, true);
            $this->state['cities'] = $cities_true;
        } else $this->state['cities'] = [];
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
        $Users = $this->getUsersProperty()
            ->orderByDesc('user_status')
            ->paginate(15);

        $UserLogs = UserLog::query()
            ->when(isset($this->user_id), function ($q) {
                $q->where('user_id', $this->user_id);
            })->latest()->limit(10)->get();

        $Cities = City::all();

        if (isset($this->state['cities']))
            if (count(array_filter($this->state['cities'])) < $Cities->count())
                $this->AllCities = false;
            else $this->AllCities = true;

        return view('livewire.dashboard.main.list-users', compact('Users', 'Cities', 'UserLogs'));
    }

    public function getUsersProperty()
    {
        return User::query()
            ->when(isset($this->Search), function ($query) {
                $query->where('name', 'LIKE', '%' . $this->Search . '%');
            });
    }
}
