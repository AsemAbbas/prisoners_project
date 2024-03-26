@section('title')
    فجر الحرية | قائمة الفلتر والتقارير
@endsection
@section('style')
    <link rel="stylesheet" href="{{asset('plugins-rtl/apex/apexcharts.css')}}">
    @vite(['resources/rtl/scss/light/assets/components/list-group.scss'])
    @vite(['resources/rtl/scss/light/assets/widgets/modules-widgets.scss'])

    @vite(['resources/rtl/scss/dark/assets/components/list-group.scss'])
    @vite(['resources/rtl/scss/dark/assets/widgets/modules-widgets.scss'])

    @vite(['resources/rtl/scss/light/assets/elements/alert.scss'])
    @vite(['resources/rtl/scss/dark/assets/elements/alert.scss'])
@endsection
<div class="p-4">
    <div wire:loading.delay.longest>
        <div class="d-flex justify-content-center align-items-center"
             style=" background-color: black;opacity: .55 ;position: fixed; top: 0; left: 0; z-index: 9999;width: 100%;height: 100%">
            <div>
                <h1 class="text-center text-primary">جاري التحميل...</h1>
                <div class="spinner-grow text-secondary" style="width: 200px;height: 200px" role="status">
                </div>
            </div>
        </div>
    </div>
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">لوحة التحكم</a></li>
                <li class="breadcrumb-item active" aria-current="page">قائمة الفلتر والتقارير</li>
            </ol>
        </nav>
    </div>
    <div class="row mt-5">
        <div class="col-md-12">
            <div class="col-md-12">
                <div class="row">
                    <div class="my-2">
                        <div id="toggleFilter" class="PrisonerData">
                            <div class="card">
                                <div class="card-header bg-primary rounded-2" id="headingFilter"
                                     wire:ignore.self>
                                    <section class="mb-0 mt-0">
                                        <div role="menu"
                                             class="collapsed d-flex justify-content-between text-white"
                                             data-bs-toggle="collapse"
                                             data-bs-target="#defaultFilter"
                                             aria-expanded="false"
                                             aria-controls="defaultFilter">
                                            <p class="p-0 m-0 text-white" style="font-size: 18px">
                                                الفلتر
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                     fill="currentColor" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5z"/>
                                                </svg>
                                            </p>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                 height="24"
                                                 viewBox="0 0 24 24" fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="2" stroke-linecap="round"
                                                 stroke-linejoin="round"
                                                 class="feather feather-chevron-down">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
                                        </div>
                                    </section>
                                </div>
                                <div id="defaultFilter" class="collapse"
                                     aria-labelledby="headingFilter"
                                     wire:ignore.self
                                     data-bs-parent="#toggleFilter">
                                    <div class="card-body">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h3 class="my-3">المحصلات</h3>
                                                    <div class="row">
                                                        <div class="form-group col-xl-4 mb-4">
                                                            <label for="City">المحافظة</label>
                                                            @if(isset($AdvanceSearch) && isset($AdvanceSearch['city']))
                                                                <a class="btn btn-danger"
                                                                   wire:click="emptyField(['city'])">افراغ</a>
                                                            @endif
                                                            <div id="toggleCity" class="City">
                                                                <div class="card">
                                                                    <div class="card-header" id="headingCity"
                                                                         wire:ignore.self>
                                                                        <section class="mb-0 mt-0">
                                                                            <div role="menu"
                                                                                 class="collapsed d-flex justify-content-between"
                                                                                 data-bs-toggle="collapse"
                                                                                 data-bs-target="#defaultCity"
                                                                                 aria-expanded="false"
                                                                                 aria-controls="defaultCity">
                                                                                <p class="p-0 m-0">اختر...</p>
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     width="24"
                                                                                     height="24"
                                                                                     viewBox="0 0 24 24" fill="none"
                                                                                     stroke="currentColor"
                                                                                     stroke-width="2"
                                                                                     stroke-linecap="round"
                                                                                     stroke-linejoin="round"
                                                                                     class="feather feather-chevron-down">
                                                                                    <polyline
                                                                                        points="6 9 12 15 18 9"></polyline>
                                                                                </svg>
                                                                            </div>
                                                                        </section>
                                                                    </div>
                                                                    <div id="defaultCity" class="collapse"
                                                                         aria-labelledby="headingCity"
                                                                         wire:ignore.self
                                                                         data-bs-parent="#toggleCity">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-12 mb-2">
                                                                                    <div
                                                                                        class="form-check form-check-dark form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                               wire:model.live="AdvanceSearch.city"
                                                                                               type="radio"
                                                                                               name="city"
                                                                                               value="not_found"
                                                                                               id="city_not_found">
                                                                                        <label class="form-check-label"
                                                                                               for="city_not_found">
                                                                                            لا يوجد
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                                @foreach($Cities as $city)
                                                                                    <div class="col-md-12 mb-2">
                                                                                        <div
                                                                                            class="form-check form-check-dark form-check-inline">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                wire:model.live="AdvanceSearch.city"
                                                                                                type="radio"
                                                                                                name="city"
                                                                                                value="{{$city->id}}"
                                                                                                id="city_{{$city->id}}">
                                                                                            <label
                                                                                                class="form-check-label"
                                                                                                for="city_{{$city->id}}">
                                                                                                {{$city->city_name}}
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-xl-6 mb-4">
                                                            <label for="Town">البلدة</label>
                                                            <div id="toggleTown" class="Town">
                                                                <div class="card">
                                                                    <div class="card-header" id="headingTown"
                                                                         wire:ignore.self>
                                                                        <section class="mb-0 mt-0">
                                                                            <div role="menu"
                                                                                 class="collapsed d-flex justify-content-between"
                                                                                 data-bs-toggle="collapse"
                                                                                 data-bs-target="#defaultTown"
                                                                                 aria-expanded="false"
                                                                                 aria-controls="defaultTown">
                                                                                <p class="p-0 m-0">اختر...</p>
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     width="24"
                                                                                     height="24"
                                                                                     viewBox="0 0 24 24" fill="none"
                                                                                     stroke="currentColor"
                                                                                     stroke-width="2"
                                                                                     stroke-linecap="round"
                                                                                     stroke-linejoin="round"
                                                                                     class="feather feather-chevron-down">
                                                                                    <polyline
                                                                                        points="6 9 12 15 18 9"></polyline>
                                                                                </svg>
                                                                            </div>
                                                                        </section>
                                                                    </div>
                                                                    <div id="defaultTown" class="collapse"
                                                                         aria-labelledby="headingTown"
                                                                         wire:ignore.self
                                                                         data-bs-parent="#toggleTown">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-12 mb-4">
                                                                                    <input class="form-control"
                                                                                           type="text"
                                                                                           placeholder="بحث عن بلدة"
                                                                                           wire:model.live="town_search">
                                                                                </div>
                                                                                <div class="col-md-6 mb-2">
                                                                                    <div
                                                                                        class="form-check form-check-dark form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                               wire:model.live="AdvanceSearch.town.not_found"
                                                                                               type="checkbox"
                                                                                               id="form-check-dark_not_found">
                                                                                        <label class="form-check-label"
                                                                                               for="form-check-dark_not_found">
                                                                                            لا يوجد
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                                @foreach($Cities as $city)
                                                                                    @if(isset($this->AdvanceSearch['city']) && $city->id == $this->AdvanceSearch['city'])
                                                                                        <div class="col-md-12 mb-2">
                                                                                            <h5>{{$city->city_name}}</h5>
                                                                                        </div>
                                                                                        @foreach($city->Town as $key => $town)
                                                                                            <div class="col-md-6 mb-2" wire:key="{{$town->id}}">
                                                                                                <div
                                                                                                    class="form-check form-check-dark form-check-inline">
                                                                                                    <input
                                                                                                        class="form-check-input"
                                                                                                        wire:model.live="AdvanceSearch.town.{{$town->id}}"
                                                                                                        type="checkbox"
                                                                                                        id="form-check-dark_{{$key}}">
                                                                                                    <label
                                                                                                        class="form-check-label"
                                                                                                        for="form-check-dark_{{$key}}">
                                                                                                        {{$town->town_name}}
                                                                                                    </label>
                                                                                                </div>
                                                                                            </div>
                                                                                        @endforeach
                                                                                    @endif
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-xl-2 mb-4">
                                                        </div>
                                                        <div class="form-group col-xl-5 mb-4">
                                                            <label for="Belong">الإنتماء</label>
                                                            <div id="toggleBelong" class="Belong">
                                                                <div class="card">
                                                                    <div class="card-header" id="headingBelong"
                                                                         wire:ignore.self>
                                                                        <section class="mb-0 mt-0">
                                                                            <div role="menu"
                                                                                 class="collapsed d-flex justify-content-between"
                                                                                 data-bs-toggle="collapse"
                                                                                 data-bs-target="#defaultBelong"
                                                                                 aria-expanded="false"
                                                                                 aria-controls="defaultBelong">
                                                                                <p class="p-0 m-0">اختر...</p>
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     width="24"
                                                                                     height="24"
                                                                                     viewBox="0 0 24 24" fill="none"
                                                                                     stroke="currentColor"
                                                                                     stroke-width="2"
                                                                                     stroke-linecap="round"
                                                                                     stroke-linejoin="round"
                                                                                     class="feather feather-chevron-down">
                                                                                    <polyline
                                                                                        points="6 9 12 15 18 9"></polyline>
                                                                                </svg>
                                                                            </div>
                                                                        </section>
                                                                    </div>
                                                                    <div id="defaultBelong" class="collapse"
                                                                         aria-labelledby="headingBelong"
                                                                         wire:ignore.self
                                                                         data-bs-parent="#toggleBelong">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                @foreach($Belongs as $belong)
                                                                                    <div class="col-md-6 mb-4">
                                                                                        <div
                                                                                            class="form-check form-check-dark form-check-inline">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                wire:model.live="AdvanceSearch.belong.{{$belong->id}}"
                                                                                                type="checkbox"
                                                                                                id="form-check-dark">
                                                                                            <label
                                                                                                class="form-check-label"
                                                                                                for="form-check-dark">
                                                                                                {{$belong->belong_name}}
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-xl-5 mb-4">
                                                            <label for="PrisonerType">الحالات الخاصة والتصنيفات</label>
                                                            <div id="togglePrisonerType" class="PrisonerType">
                                                                <div class="card">
                                                                    <div class="card-header" id="headingPrisonerType"
                                                                         wire:ignore.self>
                                                                        <section class="mb-0 mt-0">
                                                                            <div role="menu"
                                                                                 class="collapsed d-flex justify-content-between"
                                                                                 data-bs-toggle="collapse"
                                                                                 data-bs-target="#defaultPrisonerType"
                                                                                 aria-expanded="false"
                                                                                 aria-controls="defaultPrisonerType">
                                                                                <p class="p-0 m-0">اختر...</p>
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     width="24"
                                                                                     height="24"
                                                                                     viewBox="0 0 24 24" fill="none"
                                                                                     stroke="currentColor"
                                                                                     stroke-width="2"
                                                                                     stroke-linecap="round"
                                                                                     stroke-linejoin="round"
                                                                                     class="feather feather-chevron-down">
                                                                                    <polyline
                                                                                        points="6 9 12 15 18 9"></polyline>
                                                                                </svg>
                                                                            </div>
                                                                        </section>
                                                                    </div>
                                                                    <div id="defaultPrisonerType" class="collapse"
                                                                         aria-labelledby="headingPrisonerType"
                                                                         wire:ignore.self
                                                                         data-bs-parent="#togglePrisonerType">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <h4 class="mb-4">الحالات الخاصة</h4>
                                                                                @foreach(\App\Enums\SpecialCase::cases() as $key => $row)
                                                                                    <div class="col-md-12 mb-4">
                                                                                        <div
                                                                                            class="form-check form-check-dark form-check-inline">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                wire:model.live="AdvanceSearch.special_case.{{$row->value}}"
                                                                                                type="checkbox"
                                                                                                id="form-check-dark_{{$key}}">
                                                                                            <label
                                                                                                class="form-check-label"
                                                                                                for="form-check-dark_{{$key}}">
                                                                                                {{$row->value}}
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                                <div class="col-md-12 mb-4">
                                                                                    <div
                                                                                        class="form-check form-check-dark form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                               wire:model.live="AdvanceSearch.is_released"
                                                                                               type="checkbox"
                                                                                               id="form-check-dark_is_released">
                                                                                        <label class="form-check-label"
                                                                                               for="form-check-dark_is_released">
                                                                                            مفرج عنه حالياً
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12 mb-4">
                                                                                    <div
                                                                                        class="form-check form-check-dark form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                               wire:model.live="AdvanceSearch.judgment_in_lifetime"
                                                                                               type="checkbox"
                                                                                               id="form-check-dark_is_released">
                                                                                        <label class="form-check-label"
                                                                                               for="form-check-dark_is_released">
                                                                                            المؤبدات
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                                <h4 class="mb-4">التصنيفات</h4>
                                                                                @foreach($PrisonerTypes as $prisoner_type)
                                                                                    <div class="col-md-12 mb-4">
                                                                                        <div
                                                                                            class="form-check form-check-dark form-check-inline">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                wire:model.live="AdvanceSearch.prisoner_type.{{$prisoner_type->id}}"
                                                                                                type="checkbox"
                                                                                                id="form-check-dark">
                                                                                            <label
                                                                                                class="form-check-label"
                                                                                                for="form-check-dark">
                                                                                                {{$prisoner_type->prisoner_type_name}}
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-xl-2 mb-4">
                                                        </div>
                                                        <div class="form-group col-xl-3 mb-4">
                                                            <label for="SocialType">الحالة الإجتماعية</label>
                                                            <div id="toggleSocialType" class="SocialType">
                                                                <div class="card">
                                                                    <div class="card-header" id="headingSocialType"
                                                                         wire:ignore.self>
                                                                        <section class="mb-0 mt-0">
                                                                            <div role="menu"
                                                                                 class="collapsed d-flex justify-content-between"
                                                                                 data-bs-toggle="collapse"
                                                                                 data-bs-target="#defaultSocialType"
                                                                                 aria-expanded="false"
                                                                                 aria-controls="defaultSocialType">
                                                                                <p class="p-0 m-0">اختر...</p>
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     width="24"
                                                                                     height="24"
                                                                                     viewBox="0 0 24 24" fill="none"
                                                                                     stroke="currentColor"
                                                                                     stroke-width="2"
                                                                                     stroke-linecap="round"
                                                                                     stroke-linejoin="round"
                                                                                     class="feather feather-chevron-down">
                                                                                    <polyline
                                                                                        points="6 9 12 15 18 9"></polyline>
                                                                                </svg>
                                                                            </div>
                                                                        </section>
                                                                    </div>
                                                                    <div id="defaultSocialType" class="collapse"
                                                                         aria-labelledby="headingSocialType"
                                                                         wire:ignore.self
                                                                         data-bs-parent="#toggleSocialType">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                @foreach(\App\Enums\SocialType::cases() as $row)
                                                                                    <div class="col-md-12 mb-4">
                                                                                        <div
                                                                                            class="form-check form-check-dark form-check-inline">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                wire:model.live="AdvanceSearch.social_type.{{$row->value}}"
                                                                                                type="checkbox"
                                                                                                id="form-check-dark">
                                                                                            <label
                                                                                                class="form-check-label"
                                                                                                for="form-check-dark">
                                                                                                {{$row->value}}
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-xl-3 mb-4">
                                                            <label for="Gender">الجنس</label>
                                                            <div id="toggleGender" class="Gender">
                                                                <div class="card">
                                                                    <div class="card-header" id="headingGender"
                                                                         wire:ignore.self>
                                                                        <section class="mb-0 mt-0">
                                                                            <div role="menu"
                                                                                 class="collapsed d-flex justify-content-between"
                                                                                 data-bs-toggle="collapse"
                                                                                 data-bs-target="#defaultGender"
                                                                                 aria-expanded="false"
                                                                                 aria-controls="defaultGender">
                                                                                <p class="p-0 m-0">اختر...</p>
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     width="24"
                                                                                     height="24"
                                                                                     viewBox="0 0 24 24" fill="none"
                                                                                     stroke="currentColor"
                                                                                     stroke-width="2"
                                                                                     stroke-linecap="round"
                                                                                     stroke-linejoin="round"
                                                                                     class="feather feather-chevron-down">
                                                                                    <polyline
                                                                                        points="6 9 12 15 18 9"></polyline>
                                                                                </svg>
                                                                            </div>
                                                                        </section>
                                                                    </div>
                                                                    <div id="defaultGender" class="collapse"
                                                                         aria-labelledby="headingGender"
                                                                         wire:ignore.self
                                                                         data-bs-parent="#toggleGender">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                @foreach(\App\Enums\Gender::cases() as $row)
                                                                                    <div class="col-md-12 mb-4">
                                                                                        <div
                                                                                            class="form-check form-check-dark form-check-inline">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                wire:model.live="AdvanceSearch.gender.{{$row->value}}"
                                                                                                type="checkbox"
                                                                                                id="form-check-dark">
                                                                                            <label
                                                                                                class="form-check-label"
                                                                                                for="form-check-dark">
                                                                                                {{$row->value}}
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-xl-4 mb-4">
                                                            <label for="ArrestType">حالة الاعتقال</label>
                                                            <div id="toggleArrestType" class="ArrestType">
                                                                <div class="card">
                                                                    <div class="card-header" id="headingArrestType"
                                                                         wire:ignore.self>
                                                                        <section class="mb-0 mt-0">
                                                                            <div role="menu"
                                                                                 class="collapsed d-flex justify-content-between"
                                                                                 data-bs-toggle="collapse"
                                                                                 data-bs-target="#defaultArrestType"
                                                                                 aria-expanded="false"
                                                                                 aria-controls="defaultArrestType">
                                                                                <p class="p-0 m-0">اختر...</p>
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     width="24"
                                                                                     height="24"
                                                                                     viewBox="0 0 24 24" fill="none"
                                                                                     stroke="currentColor"
                                                                                     stroke-width="2"
                                                                                     stroke-linecap="round"
                                                                                     stroke-linejoin="round"
                                                                                     class="feather feather-chevron-down">
                                                                                    <polyline
                                                                                        points="6 9 12 15 18 9"></polyline>
                                                                                </svg>
                                                                            </div>
                                                                        </section>
                                                                    </div>
                                                                    <div id="defaultArrestType" class="collapse"
                                                                         aria-labelledby="headingArrestType"
                                                                         wire:ignore.self
                                                                         data-bs-parent="#toggleArrestType">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                @foreach(\App\Enums\ArrestType::cases() as $type)
                                                                                    <div class="col-md-12 mb-4">
                                                                                        <div
                                                                                            class="form-check form-check-dark form-check-inline">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                wire:model.live="AdvanceSearch.arrest_type.{{$type->value}}"
                                                                                                type="checkbox"
                                                                                                id="form-check-dark">
                                                                                            <label
                                                                                                class="form-check-label"
                                                                                                for="form-check-dark">
                                                                                                {{$type->value}}
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-xl-2 mb-4">
                                                        </div>
                                                        <div class="form-group col-xl-5 mb-4">
                                                            <label for="Missing">النواقص</label>
                                                            <div id="toggleMissing" class="Missing">
                                                                <div class="card">
                                                                    <div class="card-header" id="headingMissing"
                                                                         wire:ignore.self>
                                                                        <section class="mb-0 mt-0">
                                                                            <div role="menu"
                                                                                 class="collapsed d-flex justify-content-between"
                                                                                 data-bs-toggle="collapse"
                                                                                 data-bs-target="#defaultMissing"
                                                                                 aria-expanded="false"
                                                                                 aria-controls="defaultMissing">
                                                                                <p class="p-0 m-0">اختر...</p>
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     width="24"
                                                                                     height="24"
                                                                                     viewBox="0 0 24 24" fill="none"
                                                                                     stroke="currentColor"
                                                                                     stroke-width="2"
                                                                                     stroke-linecap="round"
                                                                                     stroke-linejoin="round"
                                                                                     class="feather feather-chevron-down">
                                                                                    <polyline
                                                                                        points="6 9 12 15 18 9"></polyline>
                                                                                </svg>
                                                                            </div>
                                                                        </section>
                                                                    </div>
                                                                    <div id="defaultMissing" class="collapse"
                                                                         aria-labelledby="headingMissing"
                                                                         wire:ignore.self
                                                                         data-bs-parent="#toggleMissing">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <div class="col-md-12 mb-4">
                                                                                    <div
                                                                                        class="form-check form-check-dark form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                               wire:model.live="AdvanceSearch.missing.identification_number"
                                                                                               type="checkbox"
                                                                                               id="form-check-dark">
                                                                                        <label class="form-check-label"
                                                                                               for="form-check-dark">
                                                                                            رقم الهوية
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12 mb-4">
                                                                                    <div
                                                                                        class="form-check form-check-dark form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                               wire:model.live="AdvanceSearch.missing.dob"
                                                                                               type="checkbox"
                                                                                               id="form-check-dark">
                                                                                        <label class="form-check-label"
                                                                                               for="form-check-dark">
                                                                                            تاريخ الميلاد
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12 mb-4">
                                                                                    <div
                                                                                        class="form-check form-check-dark form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                               wire:model.live="AdvanceSearch.missing.doa"
                                                                                               type="checkbox"
                                                                                               id="form-check-dark">
                                                                                        <label class="form-check-label"
                                                                                               for="form-check-dark">
                                                                                            تاريخ الاعتقال
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12 mb-4">
                                                                                    <div
                                                                                        class="form-check form-check-dark form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                               wire:model.live="AdvanceSearch.missing.belong"
                                                                                               type="checkbox"
                                                                                               id="form-check-dark">
                                                                                        <label class="form-check-label"
                                                                                               for="form-check-dark">
                                                                                            الانتماء
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12 mb-4">
                                                                                    <div
                                                                                        class="form-check form-check-dark form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                               wire:model.live="AdvanceSearch.missing.city"
                                                                                               type="checkbox"
                                                                                               id="form-check-dark">
                                                                                        <label class="form-check-label"
                                                                                               for="form-check-dark">
                                                                                            المحافظة
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12 mb-4">
                                                                                    <div
                                                                                        class="form-check form-check-dark form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                               wire:model.live="AdvanceSearch.missing.town"
                                                                                               type="checkbox"
                                                                                               id="form-check-dark">
                                                                                        <label class="form-check-label"
                                                                                               for="form-check-dark">
                                                                                            البلدة
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-xl-7 mb-4">
                                                        </div>
                                                        <div class="form-group col-xl-12 mb-4">
                                                            <div class="row">
                                                                <div class="form-group col-xl-2 col-md-12 mb-4 mt-3">
                                                                    <h6 class="my-auto">تاريخ الميلاد</h6>
                                                                </div>
                                                                <div class="form-group col-xl-3 col-md-12 mb-4">
                                                                    <input wire:model.live="AdvanceSearch.dob_from"
                                                                           type="date"
                                                                           class="form-control"
                                                                           id="dob_from">
                                                                </div>
                                                                <div class="form-group col-xl-3 col-md-12 mb-4">
                                                                    <input wire:model.live="AdvanceSearch.dob_to"
                                                                           type="date"
                                                                           class="form-control"
                                                                           id="dob_to">
                                                                </div>
                                                                <div class="form-group col-xl-2 col-md-12 mb-4">
                                                                    <div style="text-align: start">
                                                                        <input wire:model.live="Cubs" id="19"
                                                                               type="checkbox">
                                                                        <label for="19">اشبال</label>
                                                                    </div>
                                                                    <div style="text-align: start">
                                                                        <input wire:model.live="Elderly" id="60"
                                                                               type="checkbox">
                                                                        <label for="60">كبار سن</label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group col-xl-2 col-md-12 mb-4">
                                                                    <div>
                                                                        @if(isset($AdvanceSearch['dob_from']) || isset($AdvanceSearch['dob_to']))
                                                                            <a class="btn btn-danger"
                                                                               wire:click="emptyField(['dob_from','dob_to'])">افراغ</a>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-xl-12 mb-4">
                                                            <div class="row">
                                                                <div class="form-group col-xl-2 col-md-12 mb-4 mt-3">
                                                                    <h6 class="my-auto">تاريخ الاعتقال</h6>
                                                                </div>
                                                                <div class="form-group col-xl-3 col-md-12 mb-4">
                                                                    <input wire:model.live="AdvanceSearch.doa_from"
                                                                           type="date"
                                                                           class="form-control"
                                                                           id="doa_from">
                                                                </div>
                                                                <div class="form-group col-xl-3 col-md-12 mb-4">
                                                                    <input wire:model.live="AdvanceSearch.doa_to"
                                                                           type="date"
                                                                           class="form-control"
                                                                           id="doa_to">
                                                                </div>
                                                                <div class="form-group col-xl-2 col-md-12 mb-4">
                                                                    <div>
                                                                        @if(isset($AdvanceSearch['doa_from']) || isset($AdvanceSearch['doa_to']))
                                                                            <a class="btn btn-danger"
                                                                               wire:click="emptyField(['doa_from','doa_to'])">افراغ</a>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-xl-12 mb-4">
                                                            <div class="row">
                                                                <div class="form-group col-xl-2 col-md-12 mb-4 mt-3">
                                                                    <h6 class="my-auto">الحكم سنوات</h6>
                                                                </div>
                                                                <div class="form-group col-xl-3 col-md-12 mb-4">
                                                                    <input
                                                                        wire:model.live="AdvanceSearch.judgment_in_years_from"
                                                                        type="number"
                                                                        placeholder="من"
                                                                        min="0"
                                                                        class="form-control"
                                                                        id="judgment_in_years_from">
                                                                </div>
                                                                <div class="form-group col-xl-3 col-md-12 mb-4">
                                                                    <input
                                                                        wire:model.live="AdvanceSearch.judgment_in_years_to"
                                                                        type="number"
                                                                        min="0"
                                                                        placeholder="إلى"
                                                                        class="form-control"
                                                                        id="judgment_in_years_to">
                                                                </div>
                                                                <div class="form-group col-xl-2 col-md-12 mb-4">
                                                                    <div>
                                                                        @if(isset($AdvanceSearch['judgment_in_years_from']) || isset($AdvanceSearch['judgment_in_years_to']))
                                                                            <a class="btn btn-danger"
                                                                               wire:click="emptyField(['judgment_in_years_from','judgment_in_years_to'])">افراغ</a>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-xl-12 mb-4">
                                                            <div class="row">
                                                                <div class="form-group col-xl-3 col-md-12 mb-4 mt-3">
                                                                    <h6 class="my-auto">تاريخ الافراج المتوقع</h6>
                                                                </div>
                                                                <div class="form-group col-xl-4 mb-4">
                                                                    <input wire:model.live="AdvanceSearch.dor"
                                                                           type="date"
                                                                           class="form-control"
                                                                           id="dor">
                                                                </div>
                                                                <div class="form-group col-xl-2 col-md-12 mb-4">
                                                                    <div>
                                                                        @if(isset($AdvanceSearch['dor']))
                                                                            <a class="btn btn-danger"
                                                                               wire:click="emptyField(['dor'])">افراغ</a>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h3 class="my-3">الاستثناءات</h3>
                                                    <div class="row">
                                                        <div class="form-group col-xl-5 mb-4">
                                                            <label for="NotBelong">الإنتماء</label>
                                                            <div id="toggleNotBelong" class="NotBelong">
                                                                <div class="card">
                                                                    <div class="card-header" id="headingNotBelong"
                                                                         wire:ignore.self>
                                                                        <section class="mb-0 mt-0">
                                                                            <div role="menu"
                                                                                 class="collapsed d-flex justify-content-between"
                                                                                 data-bs-toggle="collapse"
                                                                                 data-bs-target="#defaultNotBelong"
                                                                                 aria-expanded="false"
                                                                                 aria-controls="defaultNotBelong">
                                                                                <p class="p-0 m-0">اختر...</p>
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     width="24"
                                                                                     height="24"
                                                                                     viewBox="0 0 24 24" fill="none"
                                                                                     stroke="currentColor"
                                                                                     stroke-width="2"
                                                                                     stroke-linecap="round"
                                                                                     stroke-linejoin="round"
                                                                                     class="feather feather-chevron-down">
                                                                                    <polyline
                                                                                        points="6 9 12 15 18 9"></polyline>
                                                                                </svg>
                                                                            </div>
                                                                        </section>
                                                                    </div>
                                                                    <div id="defaultNotBelong" class="collapse"
                                                                         aria-labelledby="headingNotBelong"
                                                                         wire:ignore.self
                                                                         data-bs-parent="#toggleNotBelong">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                @foreach($Belongs as $belong)
                                                                                    <div class="col-md-6 mb-4">
                                                                                        <div
                                                                                            class="form-check form-check-dark form-check-inline">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                wire:model.live="AdvanceSearch.not_belong.{{$belong->id}}"
                                                                                                type="checkbox"
                                                                                                id="form-check-dark">
                                                                                            <label
                                                                                                class="form-check-label"
                                                                                                for="form-check-dark">
                                                                                                {{$belong->belong_name}}
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-xl-5 mb-4">
                                                            <label for="NotPrisonerType">الحالات الخاصة والتصنيفات</label>
                                                            <div id="toggleNotPrisonerType" class="NotPrisonerType">
                                                                <div class="card">
                                                                    <div class="card-header" id="headingNotPrisonerType"
                                                                         wire:ignore.self>
                                                                        <section class="mb-0 mt-0">
                                                                            <div role="menu"
                                                                                 class="collapsed d-flex justify-content-between"
                                                                                 data-bs-toggle="collapse"
                                                                                 data-bs-target="#defaultNotPrisonerType"
                                                                                 aria-expanded="false"
                                                                                 aria-controls="defaultNotPrisonerType">
                                                                                <p class="p-0 m-0">اختر...</p>
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     width="24"
                                                                                     height="24"
                                                                                     viewBox="0 0 24 24" fill="none"
                                                                                     stroke="currentColor"
                                                                                     stroke-width="2"
                                                                                     stroke-linecap="round"
                                                                                     stroke-linejoin="round"
                                                                                     class="feather feather-chevron-down">
                                                                                    <polyline
                                                                                        points="6 9 12 15 18 9"></polyline>
                                                                                </svg>
                                                                            </div>
                                                                        </section>
                                                                    </div>
                                                                    <div id="defaultNotPrisonerType" class="collapse"
                                                                         aria-labelledby="headingNotPrisonerType"
                                                                         wire:ignore.self
                                                                         data-bs-parent="#toggleNotPrisonerType">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                <h4 class="mb-4">الحالات الخاصة</h4>
                                                                                @foreach(\App\Enums\SpecialCase::cases() as $key => $row)
                                                                                    <div class="col-md-12 mb-4">
                                                                                        <div
                                                                                            class="form-check form-check-dark form-check-inline">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                wire:model.live="AdvanceSearch.not_special_case.{{$row->value}}"
                                                                                                type="checkbox"
                                                                                                id="form-check-dark_{{$key}}">
                                                                                            <label
                                                                                                class="form-check-label"
                                                                                                for="form-check-dark_{{$key}}">
                                                                                                {{$row->value}}
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                                <div class="col-md-12 mb-4">
                                                                                    <div
                                                                                        class="form-check form-check-dark form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                               wire:model.live="AdvanceSearch.not_is_released"
                                                                                               type="checkbox"
                                                                                               id="form-check-dark_is_released">
                                                                                        <label class="form-check-label"
                                                                                               for="form-check-dark_is_released">
                                                                                            مفرج عنه حالياً
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12 mb-4">
                                                                                    <div
                                                                                        class="form-check form-check-dark form-check-inline">
                                                                                        <input class="form-check-input"
                                                                                               wire:model.live="AdvanceSearch.not_judgment_in_lifetime"
                                                                                               type="checkbox"
                                                                                               id="form-check-dark_is_released">
                                                                                        <label class="form-check-label"
                                                                                               for="form-check-dark_is_released">
                                                                                            المؤبدات
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                                <h4 class="mb-4">التصنيفات</h4>
                                                                                @foreach($PrisonerTypes as $prisoner_type)
                                                                                    <div class="col-md-12 mb-4">
                                                                                        <div
                                                                                            class="form-check form-check-dark form-check-inline">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                wire:model.live="AdvanceSearch.not_prisoner_type.{{$prisoner_type->id}}"
                                                                                                type="checkbox"
                                                                                                id="form-check-dark">
                                                                                            <label
                                                                                                class="form-check-label"
                                                                                                for="form-check-dark">
                                                                                                {{$prisoner_type->prisoner_type_name}}
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-xl-2 mb-4">
                                                        </div>
                                                        <div class="form-group col-xl-3 mb-4">
                                                            <label for="NotSocialType">الحالة الإجتماعية</label>
                                                            <div id="toggleNotSocialType" class="NotSocialType">
                                                                <div class="card">
                                                                    <div class="card-header" id="headingNotSocialType"
                                                                         wire:ignore.self>
                                                                        <section class="mb-0 mt-0">
                                                                            <div role="menu"
                                                                                 class="collapsed d-flex justify-content-between"
                                                                                 data-bs-toggle="collapse"
                                                                                 data-bs-target="#defaultNotSocialType"
                                                                                 aria-expanded="false"
                                                                                 aria-controls="defaultNotSocialType">
                                                                                <p class="p-0 m-0">اختر...</p>
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     width="24"
                                                                                     height="24"
                                                                                     viewBox="0 0 24 24" fill="none"
                                                                                     stroke="currentColor"
                                                                                     stroke-width="2"
                                                                                     stroke-linecap="round"
                                                                                     stroke-linejoin="round"
                                                                                     class="feather feather-chevron-down">
                                                                                    <polyline
                                                                                        points="6 9 12 15 18 9"></polyline>
                                                                                </svg>
                                                                            </div>
                                                                        </section>
                                                                    </div>
                                                                    <div id="defaultNotSocialType" class="collapse"
                                                                         aria-labelledby="headingNotSocialType"
                                                                         wire:ignore.self
                                                                         data-bs-parent="#toggleNotSocialType">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                @foreach(\App\Enums\SocialType::cases() as $row)
                                                                                    <div class="col-md-12 mb-4">
                                                                                        <div
                                                                                            class="form-check form-check-dark form-check-inline">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                wire:model.live="AdvanceSearch.not_social_type.{{$row->value}}"
                                                                                                type="checkbox"
                                                                                                id="form-check-dark">
                                                                                            <label
                                                                                                class="form-check-label"
                                                                                                for="form-check-dark">
                                                                                                {{$row->value}}
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-xl-3 mb-4">
                                                            <label for="NotGender">الجنس</label>
                                                            <div id="toggleNotGender" class="NotGender">
                                                                <div class="card">
                                                                    <div class="card-header" id="headingNotGender"
                                                                         wire:ignore.self>
                                                                        <section class="mb-0 mt-0">
                                                                            <div role="menu"
                                                                                 class="collapsed d-flex justify-content-between"
                                                                                 data-bs-toggle="collapse"
                                                                                 data-bs-target="#defaultNotGender"
                                                                                 aria-expanded="false"
                                                                                 aria-controls="defaultNotGender">
                                                                                <p class="p-0 m-0">اختر...</p>
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     width="24"
                                                                                     height="24"
                                                                                     viewBox="0 0 24 24" fill="none"
                                                                                     stroke="currentColor"
                                                                                     stroke-width="2"
                                                                                     stroke-linecap="round"
                                                                                     stroke-linejoin="round"
                                                                                     class="feather feather-chevron-down">
                                                                                    <polyline
                                                                                        points="6 9 12 15 18 9"></polyline>
                                                                                </svg>
                                                                            </div>
                                                                        </section>
                                                                    </div>
                                                                    <div id="defaultNotGender" class="collapse"
                                                                         aria-labelledby="headingNotGender"
                                                                         wire:ignore.self
                                                                         data-bs-parent="#toggleNotGender">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                @foreach(\App\Enums\Gender::cases() as $row)
                                                                                    <div class="col-md-12 mb-4">
                                                                                        <div
                                                                                            class="form-check form-check-dark form-check-inline">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                wire:model.live="AdvanceSearch.not_gender.{{$row->value}}"
                                                                                                type="checkbox"
                                                                                                id="form-check-dark">
                                                                                            <label
                                                                                                class="form-check-label"
                                                                                                for="form-check-dark">
                                                                                                {{$row->value}}
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-xl-4 mb-4">
                                                            <label for="NotArrestType">حالة الاعتقال</label>
                                                            <div id="toggleNotArrestType" class="NotArrestType">
                                                                <div class="card">
                                                                    <div class="card-header" id="headingNotArrestType"
                                                                         wire:ignore.self>
                                                                        <section class="mb-0 mt-0">
                                                                            <div role="menu"
                                                                                 class="collapsed d-flex justify-content-between"
                                                                                 data-bs-toggle="collapse"
                                                                                 data-bs-target="#defaultNotArrestType"
                                                                                 aria-expanded="false"
                                                                                 aria-controls="defaultNotArrestType">
                                                                                <p class="p-0 m-0">اختر...</p>
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                     width="24"
                                                                                     height="24"
                                                                                     viewBox="0 0 24 24" fill="none"
                                                                                     stroke="currentColor"
                                                                                     stroke-width="2"
                                                                                     stroke-linecap="round"
                                                                                     stroke-linejoin="round"
                                                                                     class="feather feather-chevron-down">
                                                                                    <polyline
                                                                                        points="6 9 12 15 18 9"></polyline>
                                                                                </svg>
                                                                            </div>
                                                                        </section>
                                                                    </div>
                                                                    <div id="defaultNotArrestType" class="collapse"
                                                                         aria-labelledby="headingNotArrestType"
                                                                         wire:ignore.self
                                                                         data-bs-parent="#toggleNotArrestType">
                                                                        <div class="card-body">
                                                                            <div class="row">
                                                                                @foreach(\App\Enums\ArrestType::cases() as $type)
                                                                                    <div class="col-md-12 mb-4">
                                                                                        <div
                                                                                            class="form-check form-check-dark form-check-inline">
                                                                                            <input
                                                                                                class="form-check-input"
                                                                                                wire:model.live="AdvanceSearch.not_arrest_type.{{$type->value}}"
                                                                                                type="checkbox"
                                                                                                id="form-check-dark">
                                                                                            <label
                                                                                                class="form-check-label"
                                                                                                for="form-check-dark">
                                                                                                {{$type->value}}
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-3">
            <div class="table-responsive">
                <div>
                    <h5>
                        المجموع: {{$PrisonersCount ?? 0}}
                    </h5>
                </div>
                <div class="row my-2">
                    <div class="col-md-2">
                        <label for="Search">البحث</label>
                        <input wire:model.live="Search" class="form-control" type="search" id="Search"
                               placeholder="البحث في القائمة...">
                    </div>
                    <div class="col-md-2">
                        <label for="sortBy_">فرز حسب</label>
                        <select id="sortBy_" wire:model.live="sortBy_" class="form-select">
                            <option value="id">الرقم التسلسلي</option>
                            <option value="first_name">الاسم الاول</option>
                            <option value="date_of_birth">تاريخ الميلاد</option>
                            <option value="arrest_start_date">تاريخ الاعتقال</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="direction_">نوع الترتيب</label>
                        <select id="direction_" wire:model.live="direction_" class="form-select">
                            <option value="asc">تصاعدي</option>
                            <option value="desc">تنازلي</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label for="Take_">عرض</label>
                        <input wire:model.live="Take_" class="form-control" type="number" max="500" min="0" id="Take_">
                    </div>
                </div>
                <table class="table table-striped table-bordered text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>الرقم الأساسي</th>
                        <th>اسم الأسير</th>
                        <th>رقم الهوية</th>
                        <th>تاريخ الميلاد</th>
                        <th>الجنس</th>
                        <th>المحافظة</th>
                        <th>البلدة</th>
                        <th>تاريخ الاعتقال</th>
                        <th>حالة الاعتقال</th>
                        <th>مفرج عنه حاليا؟</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($Prisoners as $key => $row)
                        <tr>
                            @if(!empty($this->Take_))
                                <td>{{$key + 1}}</td>
                            @else
                                <td>{{$Prisoners->firstItem() + $key}}</td>
                            @endif
                            <td>{{$row->id}}</td>
                            <td>{{$row->full_name ?? 'لا يوجد'}}</td>
                            <td>{{$row->identification_number ?? 'لا يوجد'}}</td>
                            <td>{{$row->date_of_birth . ' (' . \Carbon\Carbon::parse($row->date_of_birth)->diffInYears() .' سنة)'  ?? 'لا يوجد'}}</td>
                            <td>{{$row->gender ?? 'لا يوجد'}}</td>
                            <td>{{$row->City->city_name ?? 'لا يوجد'}}</td>
                            <td>{{$row->Town->town_name ?? 'لا يوجد'}}</td>
                            <td>{{$row->Arrest->arrest_start_date ?? 'لا يوجد'}}</td>
                            <td>{{$row->Arrest->arrest_type ?? 'لا يوجد'}}</td>
                            <td>{{$row->Arrest->is_released ? 'نعم' : 'لا'}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    @if(empty($this->Take_))
                        {{$Prisoners->links()}}
                    @endif
                </div>
                <div>
                    @auth
                        @if(\Illuminate\Support\Facades\Auth::user()->user_status == "مسؤول")
                            <a wire:click="showAdminExport" class="btn btn-primary my-2">تصدير البيانات</a>
                        @else
                            <a wire:click="editorExport" class="btn btn-primary my-2">تصدير البيانات</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
        <!-- Export Modal -->
        <div class="modal modal-xl fade" id="Export" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog">
                <div class="modal-content bg-white">
                    <div class="modal-header bg-dark" style="margin: 5px;">
                        <h1 class="modal-title fs-5 text-white"
                            id="staticBackdropLabel">التصدير</h1>
                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                                wire:loading.class="disabled"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12 mb-4">
                                <h3 class="text-center mx-auto">الأعمدة</h3>
                            </div>
                            <div class="form-group col-md-12 mb-4">
                                <label for="PrisonerData">بيانات الأسير</label>
                                <div id="togglePrisonerData" class="PrisonerData">
                                    <div class="card">
                                        <div class="card-header" id="headingPrisonerData"
                                             wire:ignore.self>
                                            <section class="mb-0 mt-0">
                                                <div role="menu"
                                                     class="collapsed d-flex justify-content-between"
                                                     data-bs-toggle="collapse"
                                                     data-bs-target="#defaultPrisonerData"
                                                     aria-expanded="false"
                                                     aria-controls="defaultPrisonerData">
                                                    <p class="p-0 m-0">اختر...</p>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                         height="24"
                                                         viewBox="0 0 24 24" fill="none"
                                                         stroke="currentColor"
                                                         stroke-width="2" stroke-linecap="round"
                                                         stroke-linejoin="round"
                                                         class="feather feather-chevron-down">
                                                        <polyline points="6 9 12 15 18 9"></polyline>
                                                    </svg>
                                                </div>
                                            </section>
                                        </div>
                                        <div id="defaultPrisonerData" class="collapse"
                                             aria-labelledby="headingPrisonerData"
                                             wire:ignore.self
                                             data-bs-parent="#togglePrisonerData">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4 mb-4">
                                                        <div
                                                            class="form-check form-check-dark form-check-inline">
                                                            <input class="form-check-input"
                                                                   wire:model.live="SelectAllPrisoner"
                                                                   type="checkbox"
                                                                   id="form-check-dark">
                                                            <label class="form-check-label"
                                                                   for="form-check-dark">
                                                                تحديد الكل
                                                            </label>
                                                        </div>
                                                    </div>
                                                    @foreach($PrisonerColumn as $key => $row)
                                                        <div class="col-md-4 mb-4">
                                                            <div
                                                                class="form-check form-check-dark form-check-inline">
                                                                <input class="form-check-input"
                                                                       wire:model.live="ExportData.selectPrisoner.{{$key}}"
                                                                       type="checkbox"
                                                                       id="form-check-dark">
                                                                <label class="form-check-label"
                                                                       for="form-check-dark">
                                                                    {{$row}}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12 mb-4">
                                <label for="ArrestData">بيانات الاعتقال</label>
                                <div id="toggleArrestData" class="ArrestData">
                                    <div class="card">
                                        <div class="card-header" id="headingArrestData"
                                             wire:ignore.self>
                                            <section class="mb-0 mt-0">
                                                <div role="menu"
                                                     class="collapsed d-flex justify-content-between"
                                                     data-bs-toggle="collapse"
                                                     data-bs-target="#defaultArrestData"
                                                     aria-expanded="false"
                                                     aria-controls="defaultArrestData">
                                                    <p class="p-0 m-0">اختر...</p>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                         height="24"
                                                         viewBox="0 0 24 24" fill="none"
                                                         stroke="currentColor"
                                                         stroke-width="2" stroke-linecap="round"
                                                         stroke-linejoin="round"
                                                         class="feather feather-chevron-down">
                                                        <polyline points="6 9 12 15 18 9"></polyline>
                                                    </svg>
                                                </div>
                                            </section>
                                        </div>
                                        <div id="defaultArrestData" class="collapse"
                                             aria-labelledby="headingArrestData"
                                             wire:ignore.self
                                             data-bs-parent="#toggleArrestData">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4 mb-4">
                                                        <div
                                                            class="form-check form-check-dark form-check-inline">
                                                            <input class="form-check-input"
                                                                   wire:model.live="SelectAllArrest"
                                                                   type="checkbox"
                                                                   id="form-check-dark">
                                                            <label class="form-check-label"
                                                                   for="form-check-dark">
                                                                تحديد الكل
                                                            </label>
                                                        </div>
                                                    </div>
                                                    @foreach($ArrestColumn as $key => $row)
                                                        <div class="col-md-4 mb-4">
                                                            <div
                                                                class="form-check form-check-dark form-check-inline">
                                                                <input class="form-check-input"
                                                                       wire:model.live="ExportData.selectArrest.{{$key}}"
                                                                       type="checkbox"
                                                                       id="form-check-dark">
                                                                <label class="form-check-label"
                                                                       for="form-check-dark">
                                                                    {{$row}}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12 mb-4">
                                <a class="btn btn-outline-dark" wire:click="ExportFile_"
                                   wire:loading.class="disabled">
                                    <div wire:loading wire:target="ExportFile_"
                                         style="width: 20px;height: 20px"
                                         class="spinner-border text-dark align-self-center loader-xs"></div>
                                    إستخراج
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    <script src="{{asset('plugins-rtl/apex/apexcharts.min.js')}}"></script>
    @vite(['resources/rtl/assets/js/widgets/modules-widgets.js'])
    <script src="{{asset('plugins-rtl/global/vendors.min.js')}}"></script>
    @vite(['resources/rtl/assets/js/custom.js'])
    <script>
        window.addEventListener('Export', event => {
            $('#Export').modal('show');
        })

        window.addEventListener('hideExport', event => {
            $('#Export').modal('hide');
        })

        document.addEventListener('DOMContentLoaded', function () {
            document.body.addEventListener('hideExport', function () {
                Swal.fire(
                    {
                        title: 'نجاح',
                        text: 'تم تصدير البيانات',
                        icon: 'success',
                        confirmButtonText: 'تم'
                    }
                );
            });
        });
    </script>
@endsection
