<?php

namespace App\Livewire\Dashboard\Main;

use App\Models\News;
use App\Models\NewsType;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateUpdateNews extends Component
{
    use WithFileUploads;


    public array $state = [];
    public object $News_;
    public bool $showEdit = false;


    public function mount($news = null): void
    {
        if ($news) {
            $News = News::query()->with('NewsType')->where('id', $news)->get()[0] ?? null;
            $this->edit($News);
        }
    }

    public function edit($news = null): void
    {
        $this->News_ = $news;

        $this->state = $news->toArray();

        $this->showEdit = true;
    }

    /**
     * @throws ValidationException
     */
    public function updateNews(): void
    {

        $NewsTypes = NewsType::query()->pluck('id')->implode(',');


        $rules = [
            'news_title' => "required",
            'news_photo' => "required",
            'news_url' => [
                "unique:news,news_url,{$this->state['id']},id",
                'required',
                'regex:/^[\p{Arabic}]+(-[\p{Arabic}]+)+$/u',
                'not_regex:/\s/u', // Ensures there are no spaces
            ],
            'news_type_id' => "required|in:" . $NewsTypes,
            'news_short_description' => "required",
            'news_long_description' => "required",
        ];

        if ($this->state['news_photo'] && $this->state['news_photo'] !== $this->News_->news_photo) {
            $rules['news_photo'] = 'required|image';
        }

        $validation = Validator::make($this->state, $rules)->validate();

        if ($this->state['news_photo'] && $this->state['news_photo'] !== $this->News_->news_photo) {
            $validation['news_photo'] = $this->state['news_photo']->store('/', 'news_photo');
        }

        $this->News_->update($validation);

        $this->dispatch('update_massage');
    }

    /**
     * @throws ValidationException
     */
    public function createNews(): void
    {
        $NewsTypes = NewsType::query()->pluck('id')->implode(',');
        $validation = Validator::make($this->state, [
            'news_title' => "required",
            'news_photo' => "required",
            'news_url' => [
                'unique:news,news_url',
                'required',
                'regex:/^[\p{Arabic}]+(-[\p{Arabic}]+)+$/u',
                'not_regex:/\s/u',
            ],
            'news_type_id' => "required|in:" . $NewsTypes,
            'news_short_description' => "required",
            'news_long_description' => "required",
        ])->validate();

        $validation['news_photo'] = $validation['news_photo']->store('/', 'news_photo');

        News::query()->create($validation);
        $this->state = [];
        $this->dispatch('create_massage');
    }

    public function render(): View|\Illuminate\Foundation\Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        $NewsTypes = NewsType::all();
        return view('livewire.dashboard.main.create-update-news', compact('NewsTypes'));
    }
}
