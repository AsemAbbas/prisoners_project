<?php

use App\Livewire\Dashboard\Main\ListPrisonerConfirms;
use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard\Auth\Login;
use App\Livewire\Dashboard\Main\CreateUpdateNews;
use App\Livewire\Dashboard\Main\CreateUpdatePrisoners;
use App\Livewire\Dashboard\Main\CreateUpdateSuggestions;
use App\Livewire\Dashboard\Main\ListNews;
use App\Livewire\Dashboard\Main\ListPrisoners;
use App\Livewire\Dashboard\Main\ListPrisonerSuggestions;
use App\Livewire\Dashboard\Main\ListUsers;
use App\Livewire\Dashboard\NotFound;
use App\Livewire\Dashboard\Sub\ListArrests;
use App\Livewire\Dashboard\Sub\ListBelongs;
use App\Livewire\Dashboard\Sub\ListCities;
use App\Livewire\Dashboard\Sub\ListNewsTypes;
use App\Livewire\Dashboard\Sub\ListPrisonerTypes;
use App\Livewire\Dashboard\Sub\ListRelationships;
use App\Livewire\Dashboard\Sub\ListRelativesPrisoners;
use App\Livewire\Dashboard\Sub\ListSocialMedia;
use App\Livewire\Dashboard\Sub\ListStatistics;
use App\Livewire\Main\IndexPage;
use App\Livewire\Main\NewsPage;
use App\Livewire\Main\NewsShowPage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', IndexPage::class)->name('main.index');


Route::get('/news/{news_type?}', NewsPage::class)->name('news.index');

Route::get("/news_show/{url}", NewsShowPage::class)->name("news_show.index");

Route::get('/page404', NotFound::class)->name('page404.index');

Route::prefix('dashboard')->group(function () {

    Route::get('/admin/login', Login::class)->name('dashboard.admin.login');

    Route::get('/users', ListUsers::class)->name('dashboard.users')->middleware(['auth', 'admin']);
    Route::get('/news', ListNews::class)->name('dashboard.news')->middleware(['auth', 'editor']);
    Route::get('/news/create', CreateUpdateNews::class)->name('dashboard.news.create')->middleware(['auth', 'editor']);
    Route::get('/news/update/{news?}', CreateUpdateNews::class)->name('dashboard.news.update')->middleware('auth', 'editor');

    Route::get('/prisoners', ListPrisoners::class)->name('dashboard.prisoners')->middleware(['auth', 'editor']);
    Route::get('/prisoners/create', CreateUpdatePrisoners::class)->name('dashboard.prisoners.create')->middleware(['auth', 'editor']);
    Route::get('/prisoners/update/{prisoner}', CreateUpdatePrisoners::class)->name('dashboard.prisoners.update')->middleware('auth', 'editor');
//    Route::get('/arrests/{prisoner_id?}', ListArrests::class)->name('dashboard.arrests')->middleware(['auth', 'editor']);

    Route::get('/confirms', ListPrisonerConfirms::class)->name('dashboard.confirms')->middleware('auth', 'editor');
    Route::get('/suggestions', ListPrisonerSuggestions::class)->name('dashboard.suggestions')->middleware('auth', 'reviewer');
    Route::get('/suggestions/create', CreateUpdateSuggestions::class)->name('dashboard.suggestions.create');
    Route::get('/suggestions/update/{suggestion}', CreateUpdateSuggestions::class)->name('dashboard.suggestions.update');

    Route::get('/relatives_prisoners/{prisoner_id?}', ListRelativesPrisoners::class)->name('dashboard.relatives_prisoners')->middleware(['auth', 'editor']);;
    Route::get('/cities', ListCities::class)->name('dashboard.cities')->middleware('auth', 'editor');
    Route::get('/belongs', ListBelongs::class)->name('dashboard.belongs')->middleware('auth', 'editor');
    Route::get('/prisoner_types', ListPrisonerTypes::class)->name('dashboard.prisoner_types')->middleware('auth', 'editor');
    Route::get('/news_types', ListNewsTypes::class)->name('dashboard.news_types')->middleware('auth', 'editor');
    Route::get('/relationships', ListRelationships::class)->name('dashboard.relationships')->middleware('auth', 'editor');
    Route::get('/statistics', ListStatistics::class)->name('dashboard.statistics')->middleware('auth', 'editor');
    Route::get('/social_media', ListSocialMedia::class)->name('dashboard.social_media')->middleware('auth', 'editor');
});
