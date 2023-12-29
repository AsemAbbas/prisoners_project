<?php

namespace App\Livewire\Dashboard\Sub;

use App\Models\SocialMedia;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ListSocialMedia extends Component
{
    use WithPagination, WithFileUploads;

    public object $SocialMedia_;
    public ?string $Search = null;
    public array $state = [];
    public bool $showEdit = false;

    protected string $paginationTheme = 'bootstrap';

    public function addNew(): void
    {
        $this->showEdit = false;
        $this->state = [];
        $this->dispatch('showForm');
    }

    /**
     * @throws ValidationException
     */
    public function createSocialMedia(): void
    {
        $validation = Validator::make($this->state, [
            "social_name" => 'required',
            "social_link" => 'required',
            "social_photo" => 'required|image',
        ])->validate();

        $validation['social_photo'] = $validation['social_photo']->store('/', 'social_photo');

        SocialMedia::query()->create($validation);

        $this->dispatch('hideForm');
    }

    public function edit(SocialMedia $social): void
    {
        $this->showEdit = true;
        $this->SocialMedia_ = $social;
        $this->state = $social->toArray();
        $this->dispatch('showForm');
    }

    /**
     * @throws ValidationException
     */
    public function updateSocialMedia(): void
    {
        $rules = [
            "social_name" => 'required',
            "social_link" => 'required',
        ];

        if ($this->state['social_photo'] && $this->state['social_photo'] !== $this->SocialMedia_->social_photo) {
            $rules['social_photo'] = 'required|image';
        }

        $validation = Validator::make($this->state, $rules)->validate();

        if ($this->state['social_photo'] && $this->state['social_photo'] !== $this->SocialMedia_->social_photo) {
            $validation['social_photo'] = $this->state['social_photo']->store('/', 'social_photo');
        }

        $this->SocialMedia_->update($validation);

        $this->dispatch('hideForm');
    }


    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(): void
    {
        $this->SocialMedia_->delete();
        $this->dispatch('hide_delete_modal');
    }

    public function delete(SocialMedia $social): void
    {
        $this->SocialMedia_ = $social;

        $this->dispatch('show_delete_modal');
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $SocialMedia = $this->getSocialMediaProperty()->paginate(10);
        return view('livewire.dashboard.sub.list-social-media', compact('SocialMedia'));
    }

    public function getSocialMediaProperty()
    {
        return SocialMedia::query()
            ->when(isset($this->Search), function ($query) {
                $query->where('social_name', 'LIKE', '%' . $this->Search . '%');
            });
    }
}
