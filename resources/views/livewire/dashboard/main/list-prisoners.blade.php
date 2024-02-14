@section('title')
    فجر الحرية | قائمة الأسرى
@endsection
@section('style')
    <link rel="stylesheet" href="{{asset('plugins-rtl/apex/apexcharts.css')}}">
    @vite(['resources/rtl/scss/light/assets/components/list-group.scss'])
    @vite(['resources/rtl/scss/light/assets/widgets/modules-widgets.scss'])

    @vite(['resources/rtl/scss/dark/assets/components/list-group.scss'])
    @vite(['resources/rtl/scss/dark/assets/widgets/modules-widgets.scss'])

    @vite(['resources/rtl/scss/light/assets/elements/alert.scss'])
    @vite(['resources/rtl/scss/dark/assets/elements/alert.scss'])

    <style>
        input[type=search] {
            box-sizing: border-box;
            font-size: 16px;
            background-size: 35px;
            background-position: 3px 5px;
            background-repeat: no-repeat;
            -webkit-transition: width 0.4s ease-in-out;
            transition: width 0.4s ease-in-out;
            width: 275px;
            padding: 10px 20px 5px 40px;
            margin-left: 10px;
            background-color: white;
            border: 1px solid grey;
            border-radius: 7px;
        }
    </style>
@endsection
<div class="p-4">
    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">لوحة التحكم</a></li>
                <li class="breadcrumb-item active" aria-current="page">قائمة الأسرى</li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between flex-wrap mt-2">
                <div>
                    @auth
                        @if(\Illuminate\Support\Facades\Auth::user()->user_status === "مسؤول")
                            <a class="btn btn-primary mb-2" href="{{route('dashboard.prisoners.create')}}">
                                إضافة أسير
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-plus-circle">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                </svg>
                            </a>
                        @endif
                    @endauth
                </div>
                <div>
                    <div class="d-inline">
                        <input type="checkbox" name="IsReleased" id="IsReleased" wire:model.live="IsReleased">
                        <label for="IsReleased">يشمل المفرج عنهم</label>
                    </div>
                    <input wire:model.live="Search" class="form-input m-2" type="search" id="Search"
                           placeholder="البحث...">
                    @auth
                        @if(in_array(\Illuminate\Support\Facades\Auth::user()->user_status,['مراجع منطقة','مسؤول']))
                            <a class="btn btn-outline-secondary mb-2" wire:click="showAdvanceSearch">
                                البحث المتقدم
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-search">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </a>
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->user_status === "مسؤول")
                            <a class="btn btn-outline-dark mb-2" wire:click="ImportExport">
                                الإستيراد والتصدير
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-file-text">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
            <div class="table-responsive">
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
                        <th>مفرج عنه حالياً؟</th>
                        @auth
                            @if(\Illuminate\Support\Facades\Auth::user()->user_status === "مسؤول")
                                <th>الخيارات</th>
                            @endif
                        @endauth
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($Prisoners as $key => $row)
                        <tr>
                            <td>
                                <a wire:click="show({{$row}})" class="btn btn-outline-info">
                                    {{$Prisoners->firstItem() + $key}}
                                </a>
                            </td>
                            <td>{{$row->id}}</td>
                            <td>{{$row->full_name ?? 'لا يوجد'}}</td>
                            <td>{{$row->identification_number ?? 'لا يوجد'}}</td>
                            <td>{{$row->date_of_birth . ' (' . \Carbon\Carbon::parse($row->date_of_birth)->diffInYears() .' سنة)'  ?? 'لا يوجد'}}</td>
                            <td>{{$row->gender ?? 'لا يوجد'}}</td>
                            <td>{{$row->City->city_name ?? 'لا يوجد'}}</td>
                            <td>{{$row->Town->town_name ?? 'لا يوجد'}}</td>
                            <td>
                                @if($row->Arrest->is_released)
                                    نعم
                                @else
                                    لا
                                @endif
                            </td>
                            @auth
                                @if(\Illuminate\Support\Facades\Auth::user()->user_status === "مسؤول")
                                    <td>
                                        <a href="{{route('dashboard.prisoners.update',$row)}}" class="btn btn-warning"
                                           data-toggle="tooltip" data-placement="top" title="Edit">
                                            تعديل
                                        </a>
                                        <a wire:click="delete({{$row}})" class="btn btn-danger"
                                           title="Delete">
                                            حذف
                                        </a>
                                    </td>
                                @endif
                            @endauth
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{$Prisoners->links()}}
                </div>
            </div>
        </div>
    </div>

    <!-- Advance Search Modal -->
    <div class="modal modal-xl fade" id="AdvanceSearch" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content bg-white">
                <div class="modal-header bg-secondary" style="margin: 5px;">
                    <h1 class="modal-title fs-5 text-white"
                        id="staticBackdropLabel">بحث متقدم</h1>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="hideAdvanceSearch">
                        <div class="row">
                            <div class="form-group col-md-12 mb-4 text-center">
                                <h3>تاريخ الميلاد <span class="text-secondary" style="font-size: 18px">(إختياري)</span>
                                </h3>
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label for="dob_from">من</label>
                                <input wire:model="AdvanceSearch.dob_from" type="date"
                                       class="form-control"
                                       id="dob_from">
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label for="dob_to">إلى</label>
                                <input wire:model="AdvanceSearch.dob_to" type="date"
                                       class="form-control"
                                       id="dob_to">
                            </div>
                            <div class="form-group col-md-12 mb-4 text-center">
                                <hr>
                            </div>
                            <div class="form-group col-md-12 mb-4 text-center">
                                <h3>تاريخ الاعتقال <span class="text-secondary" style="font-size: 18px">(إختياري)</span>
                                </h3>
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label for="doa_from">من</label>
                                <input wire:model="AdvanceSearch.doa_from" type="date"
                                       class="form-control"
                                       id="doa_from">
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label for="doa_to">إلى</label>
                                <input wire:model="AdvanceSearch.doa_to" type="date"
                                       class="form-control"
                                       id="doa_to">
                            </div>
                            <div class="form-group col-md-12 mb-4 text-center">
                                <hr>
                            </div>
                            <div class="form-group col-md-12 mb-4 text-center">
                                <h3>الحكم مؤبدات<span class="text-secondary" style="font-size: 18px">(إختياري)</span>
                                </h3>
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label for="judgment_in_lifetime_from">من</label>
                                <input wire:model="AdvanceSearch.judgment_in_lifetime_from" type="number"
                                       class="form-control"
                                       id="judgment_in_lifetime_from">
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label for="judgment_in_lifetime_to">إلى</label>
                                <input wire:model="AdvanceSearch.judgment_in_lifetime_to" type="number"
                                       class="form-control"
                                       id="judgment_in_lifetime_to">
                            </div>
                            <div class="form-group col-md-12 mb-4 text-center">
                                <hr>
                            </div>
                            <div class="form-group col-md-12 mb-4 text-center">
                                <h3>الحكم سنوات<span class="text-secondary" style="font-size: 18px">(إختياري)</span>
                                </h3>
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label for="judgment_in_years_from">من</label>
                                <input wire:model="AdvanceSearch.judgment_in_years_from" type="number"
                                       class="form-control"
                                       id="judgment_in_years_from">
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label for="judgment_in_years_to">إلى</label>
                                <input wire:model="AdvanceSearch.judgment_in_years_to" type="number"
                                       class="form-control"
                                       id="judgment_in_years_to">
                            </div>
                            <div class="form-group col-md-12 mb-4 text-center">
                                <hr>
                            </div>
                            <div class="form-group col-md-12 mb-4 text-center">
                                <h3>بيانات اخرى <span class="text-secondary" style="font-size: 18px">(إختياري)</span>
                                </h3>
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label for="Gender">الجنس</label>
                                <div id="toggleGender" class="Gender">
                                    <div class="card">
                                        <div class="card-header" id="headingGender" wire:ignore.self>
                                            <section class="mb-0 mt-0">
                                                <div role="menu" class="collapsed d-flex justify-content-between"
                                                     data-bs-toggle="collapse"
                                                     data-bs-target="#defaultGender" aria-expanded="false"
                                                     aria-controls="defaultGender">
                                                    <p class="p-0 m-0">اختر...</p>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                         stroke-width="2" stroke-linecap="round"
                                                         stroke-linejoin="round"
                                                         class="feather feather-chevron-down">
                                                        <polyline points="6 9 12 15 18 9"></polyline>
                                                    </svg>
                                                </div>
                                            </section>
                                        </div>
                                        <div id="defaultGender" class="collapse" aria-labelledby="headingGender"
                                             wire:ignore.self
                                             data-bs-parent="#toggleGender">
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach(\App\Enums\Gender::cases() as $row)
                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-check form-check-dark form-check-inline">
                                                                <input class="form-check-input"
                                                                       wire:model.live="AdvanceSearch.gender.{{$row->value}}"
                                                                       type="checkbox"
                                                                       id="form-check-dark">
                                                                <label class="form-check-label" for="form-check-dark">
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

                            <div class="form-group col-md-6 mb-4">
                                <label for="SocialType">الحالة الإجتماعية</label>
                                <div id="toggleSocialType" class="SocialType">
                                    <div class="card">
                                        <div class="card-header" id="headingSocialType" wire:ignore.self>
                                            <section class="mb-0 mt-0">
                                                <div role="menu" class="collapsed d-flex justify-content-between"
                                                     data-bs-toggle="collapse"
                                                     data-bs-target="#defaultSocialType" aria-expanded="false"
                                                     aria-controls="defaultSocialType">
                                                    <p class="p-0 m-0">اختر...</p>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                         stroke-width="2" stroke-linecap="round"
                                                         stroke-linejoin="round"
                                                         class="feather feather-chevron-down">
                                                        <polyline points="6 9 12 15 18 9"></polyline>
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
                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-check form-check-dark form-check-inline">
                                                                <input class="form-check-input"
                                                                       wire:model.live="AdvanceSearch.social_type.{{$row->value}}"
                                                                       type="checkbox"
                                                                       id="form-check-dark">
                                                                <label class="form-check-label" for="form-check-dark">
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

                            <div class="form-group col-md-12 mb-4">
                                <label for="City">المحافظة</label>
                                <div id="toggleCity" class="City">
                                    <div class="card">
                                        <div class="card-header" id="headingCity" wire:ignore.self>
                                            <section class="mb-0 mt-0">
                                                <div role="menu" class="collapsed d-flex justify-content-between"
                                                     data-bs-toggle="collapse"
                                                     data-bs-target="#defaultCity" aria-expanded="false"
                                                     aria-controls="defaultCity">
                                                    <p class="p-0 m-0">اختر...</p>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                         stroke-width="2" stroke-linecap="round"
                                                         stroke-linejoin="round"
                                                         class="feather feather-chevron-down">
                                                        <polyline points="6 9 12 15 18 9"></polyline>
                                                    </svg>
                                                </div>
                                            </section>
                                        </div>
                                        <div id="defaultCity" class="collapse" aria-labelledby="headingCity"
                                             wire:ignore.self
                                             data-bs-parent="#toggleCity">
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach($Cities as $city)
                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-check form-check-dark form-check-inline">
                                                                <input class="form-check-input"
                                                                       wire:model.live="AdvanceSearch.city.{{$city->id}}"
                                                                       type="checkbox"
                                                                       id="form-check-dark">
                                                                <label class="form-check-label" for="form-check-dark">
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

                            <div class="form-group col-md-12 mb-4">
                                <label for="Town">البلدة</label>
                                <div id="toggleTown" class="Town">
                                    <div class="card">
                                        <div class="card-header" id="headingTown" wire:ignore.self>
                                            <section class="mb-0 mt-0">
                                                <div role="menu"
                                                     class="collapsed d-flex justify-content-between"
                                                     data-bs-toggle="collapse"
                                                     data-bs-target="#defaultTown" aria-expanded="false"
                                                     aria-controls="defaultTown">
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
                                        <div id="defaultTown" class="collapse"
                                             aria-labelledby="headingTown"
                                             wire:ignore.self
                                             data-bs-parent="#toggleTown">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12 mb-4">
                                                        <input class="form-control" type="text"
                                                               placeholder="بحث عن بلدة"
                                                               wire:model.live="town_search">
                                                    </div>
                                                    @foreach($Towns as $key => $town)
                                                        <div class="col-md-4 mb-4">
                                                            <div
                                                                class="form-check form-check-dark form-check-inline">
                                                                <input class="form-check-input"
                                                                       wire:model.live="AdvanceSearch.town.{{$town->id}}"
                                                                       type="checkbox"
                                                                       id="form-check-dark_{{$key}}">
                                                                <label class="form-check-label"
                                                                       for="form-check-dark_{{$key}}">
                                                                    {{$town->town_name}}
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
                                <label for="Belong">الإنتماء</label>
                                <div id="toggleBelong" class="Belong">
                                    <div class="card">
                                        <div class="card-header" id="headingBelong" wire:ignore.self>
                                            <section class="mb-0 mt-0">
                                                <div role="menu" class="collapsed d-flex justify-content-between"
                                                     data-bs-toggle="collapse"
                                                     data-bs-target="#defaultBelong" aria-expanded="false"
                                                     aria-controls="defaultBelong">
                                                    <p class="p-0 m-0">اختر...</p>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                         stroke-width="2" stroke-linecap="round"
                                                         stroke-linejoin="round"
                                                         class="feather feather-chevron-down">
                                                        <polyline points="6 9 12 15 18 9"></polyline>
                                                    </svg>
                                                </div>
                                            </section>
                                        </div>
                                        <div id="defaultBelong" class="collapse" aria-labelledby="headingBelong"
                                             wire:ignore.self
                                             data-bs-parent="#toggleBelong">
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach($Belongs as $belong)
                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-check form-check-dark form-check-inline">
                                                                <input class="form-check-input"
                                                                       wire:model.live="AdvanceSearch.belong.{{$belong->id}}"
                                                                       type="checkbox"
                                                                       id="form-check-dark">
                                                                <label class="form-check-label" for="form-check-dark">
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

                            <div class="form-group col-md-12 mb-4">
                                <label for="PrisonerType">التصنيف</label>
                                <div id="togglePrisonerType" class="PrisonerType">
                                    <div class="card">
                                        <div class="card-header" id="headingPrisonerType" wire:ignore.self>
                                            <section class="mb-0 mt-0">
                                                <div role="menu" class="collapsed d-flex justify-content-between"
                                                     data-bs-toggle="collapse"
                                                     data-bs-target="#defaultPrisonerType" aria-expanded="false"
                                                     aria-controls="defaultPrisonerType">
                                                    <p class="p-0 m-0">اختر...</p>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                         stroke-width="2" stroke-linecap="round"
                                                         stroke-linejoin="round"
                                                         class="feather feather-chevron-down">
                                                        <polyline points="6 9 12 15 18 9"></polyline>
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
                                                    @foreach($PrisonerTypes as $prisoner_type)
                                                        <div class="col-md-4 mb-4">
                                                            <div class="form-check form-check-dark form-check-inline">
                                                                <input class="form-check-input"
                                                                       wire:model.live="AdvanceSearch.prisoner_type.{{$prisoner_type->id}}"
                                                                       type="checkbox"
                                                                       id="form-check-dark">
                                                                <label class="form-check-label" for="form-check-dark">
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

                            <div class="form-group col-md-12 mb-4">
                                <label for="SpecialCase">الحالات الخاصة</label>
                                <div id="toggleSpecialCase" class="SpecialCase">
                                    <div class="card">
                                        <div class="card-header" id="headingSpecialCase"
                                             wire:ignore.self>
                                            <section class="mb-0 mt-0">
                                                <div role="menu"
                                                     class="collapsed d-flex justify-content-between"
                                                     data-bs-toggle="collapse"
                                                     data-bs-target="#defaultSpecialCase"
                                                     aria-expanded="false"
                                                     aria-controls="defaultSpecialCase">
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
                                        <div id="defaultSpecialCase" class="collapse"
                                             aria-labelledby="headingSpecialCase"
                                             wire:ignore.self
                                             data-bs-parent="#toggleSpecialCase">
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach(\App\Enums\SpecialCase::cases() as $key => $row)
                                                        <div class="col-md-4 mb-4">
                                                            <div
                                                                class="form-check form-check-dark form-check-inline">
                                                                <input class="form-check-input"
                                                                       wire:model.live="AdvanceSearch.special_case.{{$row->value}}"
                                                                       type="checkbox"
                                                                       id="form-check-dark_{{$key}}">
                                                                <label class="form-check-label"
                                                                       for="form-check-dark_{{$key}}">
                                                                    {{$row->value}}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    <div class="col-md-4 mb-4">
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
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-start align-items-start">
                            <button type="submit" class="btn btn-secondary">بحث
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round" class="feather feather-search">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </button>
                            <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">إلغاء</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Show Modal -->
    @if($Prisoners_)
        <div class="modal modal-lg fade" id="show" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog">
                <div class="modal-content bg-white">
                    <div class="modal-header bg-info" style="margin: 5px;">
                        <h1 class="modal-title fs-5 text-white"
                            id="staticBackdropLabel">بيانات الأسير</h1>
                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3 text-center">
                                <hr>
                                <h3>بيانات الأسير</h3>
                                <hr>
                            </div>
                            <div class="col-md-6 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                     class="bi bi-person-bounding-box text-danger" viewBox="0 0 16 16">
                                    <path
                                        d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5M.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5"/>
                                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                </svg>
                                <h6>إسم الأسير:</h6>
                                <h4>{{$Prisoners_->full_name ?? 'لا يوجد'}}</h4>
                            </div>
                            <div class="col-md-6 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                     class="bi bi-person-vcard text-danger" viewBox="0 0 16 16">
                                    <path
                                        d="M5 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4m4-2.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5M9 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4A.5.5 0 0 1 9 8m1 2.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5"/>
                                    <path
                                        d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zM1 4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H8.96c.026-.163.04-.33.04-.5C9 10.567 7.21 9 5 9c-2.086 0-3.8 1.398-3.984 3.181A1.006 1.006 0 0 1 1 12z"/>
                                </svg>
                                <h6>رقم الهوية:</h6>
                                <h4>{{$Prisoners_->identification_number ?? 'لا يوجد'}}</h4>
                            </div>
                            <div class="col-md-6 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                     class="bi bi-calendar2-heart-fill text-danger" viewBox="0 0 16 16">
                                    <path
                                        d="M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zm-2 4v-1c0-.276.244-.5.545-.5h10.91c.3 0 .545.224.545.5v1c0 .276-.244.5-.546.5H2.545C2.245 5 2 4.776 2 4.5m6 3.493c1.664-1.711 5.825 1.283 0 5.132-5.825-3.85-1.664-6.843 0-5.132"/>
                                </svg>
                                <h6>تاريخ الميلاد:</h6>
                                <h4>{{$Prisoners_->date_of_birth . ' (' . \Carbon\Carbon::parse($Prisoners_->date_of_birth)->diffInYears() .' سنة)'  ?? 'لا يوجد'}}</h4>
                            </div>
                            <div class="col-md-6 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                     class="bi bi-gender-ambiguous text-danger" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                          d="M11.5 1a.5.5 0 0 1 0-1h4a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-1 0V1.707l-3.45 3.45A4 4 0 0 1 8.5 10.97V13H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V14H6a.5.5 0 0 1 0-1h1.5v-2.03a4 4 0 1 1 3.471-6.648L14.293 1zm-.997 4.346a3 3 0 1 0-5.006 3.309 3 3 0 0 0 5.006-3.31z"/>
                                </svg>
                                <h6>الجنس:</h6>
                                <h4>{{$Prisoners_->gender ?? 'لا يوجد'}}</h4>
                            </div>
                            <div class="col-md-6 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                     class="bi bi-geo-alt text-danger" viewBox="0 0 16 16">
                                    <path
                                        d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A31.493 31.493 0 0 1 8 14.58a31.481 31.481 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10"/>
                                    <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                                </svg>
                                <h6>المحافظة:</h6>
                                <h4>{{$Prisoners_->City->city_name ?? 'لا يوجد'}}</h4>
                            </div>
                            <div class="col-md-6 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                     class="bi bi-pin-angle text-danger" viewBox="0 0 16 16">
                                    <path
                                        d="M9.828.722a.5.5 0 0 1 .354.146l4.95 4.95a.5.5 0 0 1 0 .707c-.48.48-1.072.588-1.503.588-.177 0-.335-.018-.46-.039l-3.134 3.134a5.927 5.927 0 0 1 .16 1.013c.046.702-.032 1.687-.72 2.375a.5.5 0 0 1-.707 0l-2.829-2.828-3.182 3.182c-.195.195-1.219.902-1.414.707-.195-.195.512-1.22.707-1.414l3.182-3.182-2.828-2.829a.5.5 0 0 1 0-.707c.688-.688 1.673-.767 2.375-.72a5.922 5.922 0 0 1 1.013.16l3.134-3.133a2.772 2.772 0 0 1-.04-.461c0-.43.108-1.022.589-1.503a.5.5 0 0 1 .353-.146zm.122 2.112v-.002zm0-.002v.002a.5.5 0 0 1-.122.51L6.293 6.878a.5.5 0 0 1-.511.12H5.78l-.014-.004a4.507 4.507 0 0 0-.288-.076 4.922 4.922 0 0 0-.765-.116c-.422-.028-.836.008-1.175.15l5.51 5.509c.141-.34.177-.753.149-1.175a4.924 4.924 0 0 0-.192-1.054l-.004-.013v-.001a.5.5 0 0 1 .12-.512l3.536-3.535a.5.5 0 0 1 .532-.115l.096.022c.087.017.208.034.344.034.114 0 .23-.011.343-.04L9.927 2.028c-.029.113-.04.23-.04.343a1.779 1.779 0 0 0 .062.46z"/>
                                </svg>
                                <h6>البلدة:</h6>
                                <h4>{{$Prisoners_->Town->town_name ?? 'لا يوجد'}}</h4>
                            </div>
                            @if(isset($Prisoners_->Arrest))
                                <div class="col-md-6 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-lock text-danger" viewBox="0 0 16 16">
                                        <path
                                            d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2M5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1"/>
                                    </svg>
                                    <h6>تاريخ الاعتقال:</h6>
                                    <h4>{{$Prisoners_->Arrest->arrest_start_date ?? 'لا يوجد'}}</h4>
                                </div>
                                @if(isset($Prisoners_->Arrest->arrest_start_date))
                                    <div class="col-md-6 mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                             fill="currentColor" class="bi bi-unlock text-danger" viewBox="0 0 16 16">
                                            <path
                                                d="M11 1a2 2 0 0 0-2 2v4a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2h5V3a3 3 0 0 1 6 0v4a.5.5 0 0 1-1 0V3a2 2 0 0 0-2-2M3 8a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1z"/>
                                        </svg>
                                        <h6>تاريخ الإفراج المتوقع:</h6>
                                        @php
                                            $arrest_start_date = $Prisoners_->Arrest->arrest_start_date;
                                            $judgment_in_lifetime = $Prisoners_->Arrest->judgment_in_lifetime;
                                            $judgment_in_years = $Prisoners_->Arrest->judgment_in_years;
                                            $judgment_in_months = $Prisoners_->Arrest->judgment_in_months;

                                            $data = \Illuminate\Support\Carbon::parse($arrest_start_date)->addYears(($judgment_in_lifetime * 99) + $judgment_in_years)->addMonths($judgment_in_months)->format('Y-m-d');
                                        @endphp
                                        <h4>
                                            @if($Prisoners_->ArrestJudgment())
                                                {{$data ?? null}}
                                            @else
                                                لا يوجد
                                            @endif
                                        </h4>
                                    </div>
                                @endif
                                <div class="col-md-6 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-braces-asterisk text-danger" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                              d="M1.114 8.063V7.9c1.005-.102 1.497-.615 1.497-1.6V4.503c0-1.094.39-1.538 1.354-1.538h.273V2h-.376C2.25 2 1.49 2.759 1.49 4.352v1.524c0 1.094-.376 1.456-1.49 1.456v1.299c1.114 0 1.49.362 1.49 1.456v1.524c0 1.593.759 2.352 2.372 2.352h.376v-.964h-.273c-.964 0-1.354-.444-1.354-1.538V9.663c0-.984-.492-1.497-1.497-1.6ZM14.886 7.9v.164c-1.005.103-1.497.616-1.497 1.6v1.798c0 1.094-.39 1.538-1.354 1.538h-.273v.964h.376c1.613 0 2.372-.759 2.372-2.352v-1.524c0-1.094.376-1.456 1.49-1.456v-1.3c-1.114 0-1.49-.362-1.49-1.456V4.352C14.51 2.759 13.75 2 12.138 2h-.376v.964h.273c.964 0 1.354.444 1.354 1.538V6.3c0 .984.492 1.497 1.497 1.6M7.5 11.5V9.207l-1.621 1.621-.707-.707L6.792 8.5H4.5v-1h2.293L5.172 5.879l.707-.707L7.5 6.792V4.5h1v2.293l1.621-1.621.707.707L9.208 7.5H11.5v1H9.207l1.621 1.621-.707.707L8.5 9.208V11.5z"/>
                                    </svg>
                                    <h6>نوع الاعتقال:</h6>
                                    <h4>{{$Prisoners_->Arrest->arrest_type ?? 'لا يوجد'}}</h4>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-vector-pen text-danger" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd"
                                              d="M10.646.646a.5.5 0 0 1 .708 0l4 4a.5.5 0 0 1 0 .708l-1.902 1.902-.829 3.313a1.5 1.5 0 0 1-1.024 1.073L1.254 14.746 4.358 4.4A1.5 1.5 0 0 1 5.43 3.377l3.313-.828L10.646.646zm-1.8 2.908-3.173.793a.5.5 0 0 0-.358.342l-2.57 8.565 8.567-2.57a.5.5 0 0 0 .34-.357l.794-3.174-3.6-3.6z"/>
                                        <path fill-rule="evenodd"
                                              d="M2.832 13.228 8 9a1 1 0 1 0-1-1l-4.228 5.168-.026.086.086-.026z"/>
                                    </svg>
                                    @php $text = $Prisoners_->Arrest->arrest_type == "موقوف" ? 'الحكم المتوقع' : 'الحكم' @endphp
                                    <h6>{{$text}}:</h6>
                                    <h4>
                                        @if(isset($Prisoners_->Arrest->arrest_type))
                                            {{$Prisoners_->ArrestJudgment() ?? 'لا يوجد'}}
                                        @endif
                                    </h4>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-link-45deg text-danger" viewBox="0 0 16 16">
                                        <path
                                            d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.002 1.002 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z"/>
                                        <path
                                            d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243z"/>
                                    </svg>
                                    <h6>الإنتماء:</h6>
                                    <h4>{{$Prisoners_->Arrest->Belong->belong_name ?? 'لا يوجد'}}</h4>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-link-45deg text-danger" viewBox="0 0 16 16">
                                        <path
                                            d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.002 1.002 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z"/>
                                        <path
                                            d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243z"/>
                                    </svg>
                                    <h6>مفرج عنه حالياً؟ :</h6>
                                    <h4>{{(boolean)$Prisoners_->Arrest->is_released === true ? 'نعم' : 'لا'}}</h4>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-text-indent-right text-danger" viewBox="0 0 16 16">
                                        <path
                                            d="M2 3.5a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5m10.646 2.146a.5.5 0 0 1 .708.708L11.707 8l1.647 1.646a.5.5 0 0 1-.708.708l-2-2a.5.5 0 0 1 0-.708l2-2zM2 6.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m0 3a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m0 3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5"/>
                                    </svg>
                                    <h6>تصنيف الأسير:</h6>
                                    @if(count($Prisoners_->PrisonerType) > 0)
                                        <h4>
                                            @foreach($Prisoners_->PrisonerType as $type)
                                                {{$type->prisoner_type_name . ',' ?? 'لا يوجد'}}
                                            @endforeach
                                        </h4>
                                    @else
                                        <h4>
                                            لا يوجد
                                        </h4>
                                    @endif
                                </div>
                                <div class="col-md-12 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-filter-circle text-danger" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                        <path
                                            d="M7 11.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-2-3a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5"/>
                                    </svg>
                                    <h6>الحالة الخاصة:</h6>
                                    <h4>{{$Prisoners_->Arrest->special_case ?? 'لا يوجد'}}</h4>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-people text-danger" viewBox="0 0 16 16">
                                        <path
                                            d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>
                                    </svg>
                                    <h6>أقارب معتقلين:</h6>
                                    <h4 class="d-flex justify-content-between">
                                    <span class="me-2">
                                    الاب: {{$Prisoners_->Arrest->father_arrested ? 'نعم' : 'لا'}}
                                    </span>
                                        <span class="me-2">
                                    الأم: {{$Prisoners_->Arrest->mother_arrested ? 'نعم' : 'لا'}}
                                    </span>
                                        <span class="me-2">
                                    أخ: {{$Prisoners_->Arrest->brother_arrested ?? 0}}
                                    </span>
                                        <span class="me-2">
                                    أخت: {{$Prisoners_->Arrest->sister_arrested ?? 0}}
                                    </span>
                                    </h4>
                                    <h4 class="d-flex justify-content-between">
                                    <span class="me-2">
                                    الزوج: {{$Prisoners_->Arrest->husband_arrested ? 'نعم' : 'لا'}}
                                    </span>

                                        <span class="me-2">
                                    الزوجة: {{$Prisoners_->Arrest->wife_arrested ? 'نعم' : 'لا'}}
                                    </span>
                                        <span class="me-2">
                                    ابن:{{$Prisoners_->Arrest->son_arrested ?? 0}}
                                    </span>
                                        <span class="me-2">
                                    ابنه: {{$Prisoners_->Arrest->daughter_arrested ?? 0}}
                                    </span>
                                    </h4>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-chat-right-heart text-danger" viewBox="0 0 16 16">
                                        <path
                                            d="M2 1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h9.586a2 2 0 0 1 1.414.586l2 2V2a1 1 0 0 0-1-1zm12-1a2 2 0 0 1 2 2v12.793a.5.5 0 0 1-.854.353l-2.853-2.853a1 1 0 0 0-.707-.293H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2z"/>
                                        <path
                                            d="M8 3.993c1.664-1.711 5.825 1.283 0 5.132-5.825-3.85-1.664-6.843 0-5.132Z"/>
                                    </svg>
                                    <h6>الحالة الإجتماعية:</h6>
                                    <h4>{{$Prisoners_->Arrest->social_type ?? 'لا يوجد'}}</h4>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-123 text-danger" viewBox="0 0 16 16">
                                        <path
                                            d="M2.873 11.297V4.142H1.699L0 5.379v1.137l1.64-1.18h.06v5.961h1.174Zm3.213-5.09v-.063c0-.618.44-1.169 1.196-1.169.676 0 1.174.44 1.174 1.106 0 .624-.42 1.101-.807 1.526L4.99 10.553v.744h4.78v-.99H6.643v-.069L8.41 8.252c.65-.724 1.237-1.332 1.237-2.27C9.646 4.849 8.723 4 7.308 4c-1.573 0-2.36 1.064-2.36 2.15v.057h1.138m6.559 1.883h.786c.823 0 1.374.481 1.379 1.179.01.707-.55 1.216-1.421 1.21-.77-.005-1.326-.419-1.379-.953h-1.095c.042 1.053.938 1.918 2.464 1.918 1.478 0 2.642-.839 2.62-2.144-.02-1.143-.922-1.651-1.551-1.714v-.063c.535-.09 1.347-.66 1.326-1.678-.026-1.053-.933-1.855-2.359-1.845-1.5.005-2.317.88-2.348 1.898h1.116c.032-.498.498-.944 1.206-.944.703 0 1.206.435 1.206 1.07.005.64-.504 1.106-1.2 1.106h-.75z"/>
                                    </svg>
                                    <h6>عدد الزوجات:</h6>
                                    <h4>{{$Prisoners_->Arrest->wife_type ?? 'لا يوجد'}}</h4>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-person-up text-danger" viewBox="0 0 16 16">
                                        <path
                                            d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.354-5.854 1.5 1.5a.5.5 0 0 1-.708.708L13 11.707V14.5a.5.5 0 0 1-1 0v-2.793l-.646.647a.5.5 0 0 1-.708-.708l1.5-1.5a.5.5 0 0 1 .708 0M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/>
                                        <path
                                            d="M8.256 14a4.474 4.474 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10c.26 0 .507.009.74.025.226-.341.496-.65.804-.918C9.077 9.038 8.564 9 8 9c-5 0-6 3-6 4s1 1 1 1z"/>
                                    </svg>
                                    <h6>عدد الأبناء:</h6>
                                    <h4>{{$Prisoners_->Arrest->number_of_children ?? 'لا يوجد'}}</h4>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-chat-quote-fill text-danger" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8c0 3.866-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7M7.194 6.766a1.688 1.688 0 0 0-.227-.272 1.467 1.467 0 0 0-.469-.324l-.008-.004A1.785 1.785 0 0 0 5.734 6C4.776 6 4 6.746 4 7.667c0 .92.776 1.666 1.734 1.666.343 0 .662-.095.931-.26-.137.389-.39.804-.81 1.22a.405.405 0 0 0 .011.59c.173.16.447.155.614-.01 1.334-1.329 1.37-2.758.941-3.706a2.461 2.461 0 0 0-.227-.4zM11 9.073c-.136.389-.39.804-.81 1.22a.405.405 0 0 0 .012.59c.172.16.446.155.613-.01 1.334-1.329 1.37-2.758.942-3.706a2.466 2.466 0 0 0-.228-.4 1.686 1.686 0 0 0-.227-.273 1.466 1.466 0 0 0-.469-.324l-.008-.004A1.785 1.785 0 0 0 10.07 6c-.957 0-1.734.746-1.734 1.667 0 .92.777 1.666 1.734 1.666.343 0 .662-.095.931-.26z"/>
                                    </svg>
                                    <h6>رقم التواصل (واتس/تلجرام):</h6>
                                    <h4>{{$Prisoners_->Arrest->first_phone_number ?? 'لا يوجد'}}</h4>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-person-badge text-danger" viewBox="0 0 16 16">
                                        <path
                                            d="M6.5 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1zM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                        <path
                                            d="M4.5 0A2.5 2.5 0 0 0 2 2.5V14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2.5A2.5 2.5 0 0 0 11.5 0zM3 2.5A1.5 1.5 0 0 1 4.5 1h7A1.5 1.5 0 0 1 13 2.5v10.795a4.2 4.2 0 0 0-.776-.492C11.392 12.387 10.063 12 8 12s-3.392.387-4.224.803a4.2 4.2 0 0 0-.776.492z"/>
                                    </svg>
                                    <h6>صاحب الرقم (واتس/تلجرام):</h6>
                                    <h4>{{$Prisoners_->Arrest->first_phone_owner ?? 'لا يوجد'}}</h4>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-phone-vibrate text-danger" viewBox="0 0 16 16">
                                        <path
                                            d="M10 3a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zM6 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z"/>
                                        <path
                                            d="M8 12a1 1 0 1 0 0-2 1 1 0 0 0 0 2M1.599 4.058a.5.5 0 0 1 .208.676A6.967 6.967 0 0 0 1 8c0 1.18.292 2.292.807 3.266a.5.5 0 0 1-.884.468A7.968 7.968 0 0 1 0 8c0-1.347.334-2.619.923-3.734a.5.5 0 0 1 .676-.208m12.802 0a.5.5 0 0 1 .676.208A7.967 7.967 0 0 1 16 8a7.967 7.967 0 0 1-.923 3.734.5.5 0 0 1-.884-.468A6.967 6.967 0 0 0 15 8c0-1.18-.292-2.292-.807-3.266a.5.5 0 0 1 .208-.676M3.057 5.534a.5.5 0 0 1 .284.648A4.986 4.986 0 0 0 3 8c0 .642.12 1.255.34 1.818a.5.5 0 1 1-.93.364A5.986 5.986 0 0 1 2 8c0-.769.145-1.505.41-2.182a.5.5 0 0 1 .647-.284m9.886 0a.5.5 0 0 1 .648.284C13.855 6.495 14 7.231 14 8c0 .769-.145 1.505-.41 2.182a.5.5 0 0 1-.93-.364C12.88 9.255 13 8.642 13 8c0-.642-.12-1.255-.34-1.818a.5.5 0 0 1 .283-.648z"/>
                                    </svg>
                                    <h6>رقم التواصل الإضافي:</h6>
                                    <h4>{{$Prisoners_->Arrest->second_phone_number ?? 'لا يوجد'}}</h4>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-person-badge text-danger" viewBox="0 0 16 16">
                                        <path
                                            d="M6.5 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1zM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                        <path
                                            d="M4.5 0A2.5 2.5 0 0 0 2 2.5V14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2.5A2.5 2.5 0 0 0 11.5 0zM3 2.5A1.5 1.5 0 0 1 4.5 1h7A1.5 1.5 0 0 1 13 2.5v10.795a4.2 4.2 0 0 0-.776-.492C11.392 12.387 10.063 12 8 12s-3.392.387-4.224.803a4.2 4.2 0 0 0-.776.492z"/>
                                    </svg>
                                    <h6>صاحب الرقم الإضافي:</h6>
                                    <h4>{{$Prisoners_->Arrest->second_phone_owner ?? 'لا يوجد'}}</h4>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                         class="bi bi-envelope-at-fill text-danger" viewBox="0 0 16 16">
                                        <path
                                            d="M2 2A2 2 0 0 0 .05 3.555L8 8.414l7.95-4.859A2 2 0 0 0 14 2zm-2 9.8V4.698l5.803 3.546L0 11.801Zm6.761-2.97-6.57 4.026A2 2 0 0 0 2 14h6.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.606-3.446l-.367-.225L8 9.586l-1.239-.757ZM16 9.671V4.697l-5.803 3.546.338.208A4.482 4.482 0 0 1 12.5 8c1.414 0 2.675.652 3.5 1.671"/>
                                        <path
                                            d="M15.834 12.244c0 1.168-.577 2.025-1.587 2.025-.503 0-1.002-.228-1.12-.648h-.043c-.118.416-.543.643-1.015.643-.77 0-1.259-.542-1.259-1.434v-.529c0-.844.481-1.4 1.26-1.4.585 0 .87.333.953.63h.03v-.568h.905v2.19c0 .272.18.42.411.42.315 0 .639-.415.639-1.39v-.118c0-1.277-.95-2.326-2.484-2.326h-.04c-1.582 0-2.64 1.067-2.64 2.724v.157c0 1.867 1.237 2.654 2.57 2.654h.045c.507 0 .935-.07 1.18-.18v.731c-.219.1-.643.175-1.237.175h-.044C10.438 16 9 14.82 9 12.646v-.214C9 10.36 10.421 9 12.485 9h.035c2.12 0 3.314 1.43 3.314 3.034zm-4.04.21v.227c0 .586.227.8.581.8.31 0 .564-.17.564-.743v-.367c0-.516-.275-.708-.572-.708-.346 0-.573.245-.573.791Z"/>
                                    </svg>
                                    <h6>البريد الإلكتروني:</h6>
                                    <h4>{{$Prisoners_->Arrest->email ?? 'لا يوجد'}}</h4>
                                </div>
                            @endif

                            <div class="col-md-12 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                     class="bi bi-journal-bookmark-fill text-danger" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                          d="M6 1h6v7a.5.5 0 0 1-.757.429L9 7.083 6.757 8.43A.5.5 0 0 1 6 8z"/>
                                    <path
                                        d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H1V2a2 2 0 0 1 2-2"/>
                                    <path
                                        d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                                </svg>
                                <h6>الملاحظات:</h6>
                                <h4>{{$Prisoners_->notes ?? 'لا يوجد'}}</h4>
                            </div>
                            <div class="col-md-12 mb-3 text-center">
                                <hr>
                                <h3>الاعتقالات السابقة</h3>
                                <hr>
                            </div>
                            @if(count($Prisoners_->OldArrest) > 0)
                                @foreach($Prisoners_->OldArrest->sortBy('old_arrest_start_date') as $key => $arrest)
                                    <div class="col-md-12 mb-3 text-center">
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>بداية الاعتقال:</h6>
                                                <h4>{{$arrest->old_arrest_start_date ?? 'لا يوجد'}}</h4>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>نهاية الاعتقال:</h6>
                                                <h4>{{$arrest->old_arrest_end_date ?? 'لا يوجد'}}</h4>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                @endforeach
                            @else
                                <h4 class="text-center">
                                    لا يوجد
                                </h4>
                            @endif
                            <div class="col-md-12 mb-3 text-center">
                                <hr>
                                <h3>أقارب معتقلين</h3>
                                <hr>
                            </div>
                            @if(count($Prisoners_->FamilyIDNumber) > 0)
                                @foreach($Prisoners_->FamilyIDNumber as $key => $arrested)
                                    <div class="col-md-12 mb-3 text-center">
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>رقم الهوية:</h6>
                                                <h4>{{$arrested->id_number ?? 'لا يوجد'}}</h4>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>صلة القرابة:</h6>
                                                <h4>{{$arrested->relationship_name ?? 'لا يوجد'}}</h4>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                @endforeach
                            @else
                                <h4 class="text-center">
                                    لا يوجد
                                </h4>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-start align-items-start">
                        <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">إغلاق</button>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <!-- ImportExport Modal -->
    <div class="modal modal-xl fade" id="ImportExport" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content bg-white">
                <div class="modal-header bg-dark" style="margin: 5px;">
                    <h1 class="modal-title fs-5 text-white"
                        id="staticBackdropLabel">الإستيراد والتصدير</h1>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                            wire:loading.class="disabled"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group col-md-12 mb-4">
                        <div id="toggleImport" class="Import">
                            <div class="card">
                                <div class="card-header" id="headingImport" wire:ignore.self>
                                    <section class="mb-0 mt-0">
                                        <div role="menu" class="collapsed d-flex justify-content-between"
                                             data-bs-toggle="collapse"
                                             data-bs-target="#defaultImport" aria-expanded="false"
                                             aria-controls="defaultImport">
                                            <h6 class="p-0 m-0">الإستيراد</h6>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                 stroke-width="2" stroke-linecap="round"
                                                 stroke-linejoin="round"
                                                 class="feather feather-chevron-down">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
                                        </div>
                                    </section>
                                </div>
                                <div id="defaultImport" class="collapse" aria-labelledby="headingImport"
                                     wire:ignore.self
                                     data-bs-parent="#toggleImport">
                                    <div class="card-body">
                                        <div
                                            class="d-flex flex-column flex-wrap justify-content-around align-items-start">
                                            <div class="mb-3">
                                                <label>إضافة ملف</label>
                                                <input type="file" wire:model="ImportFile" class="form-control">
                                                @error("ImportFile")
                                                <div class="text-danger" style="font-size: 15px">{{ $message }}</div>
                                                @enderror
                                                <span><span class="text-danger">*</span> يجب ان يكون الملف من نوع (xlsx, xls).</span>
                                            </div>
                                            <div class="mb-4">
                                                <a class="btn btn-outline-dark" wire:click="ImportFile_"
                                                   wire:loading.class="disabled">
                                                    <div wire:loading wire:target="ImportFile_"
                                                         style="width: 20px;height: 20px"
                                                         class="spinner-border text-dark align-self-center loader-xs"></div>
                                                    إستيراد
                                                </a>
                                            </div>
                                            <div class="mb-3">
                                                <h6>ملف Excel:</h6>
                                                <a href="{{asset('assets/files/ImportExcel.xlsx')}}" class="d-block"
                                                   download>
                                                    <img width="100" src="{{asset('assets/images/ExcelFile.svg')}}"
                                                         alt="ExcelFile">
                                                </a>
                                                <span><span class="text-danger">*</span>هذا الملف مجهز بالعناوين.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if(session()->has('failures'))
                                    <div class="card">
                                        <div class="card-header bg-danger" id="headingOne3">
                                            <section class="mb-0 mt-0">
                                                <div
                                                    role="menu" class="collapsed" data-bs-toggle="collapse"
                                                    data-bs-target="#iconAccordionOne" aria-expanded="false"
                                                    aria-controls="iconAccordionOne">
                                                    <div class="accordion-icon text-white">
                                                        الأخطاء
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                        <div id="iconAccordionOne" class="collapse" aria-labelledby="headingOne3"
                                             data-bs-parent="#iconsAccordion" style="">
                                            <div class="card-body">
                                                <livewire:failures-table :failures="session()->get('failures')"/>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12 mb-4">
                        <div id="toggleExport" class="Export">
                            <div class="card">
                                <div class="card-header" id="headingExport" wire:ignore.self>
                                    <section class="mb-0 mt-0">
                                        <div role="menu" class="collapsed d-flex justify-content-between"
                                             data-bs-toggle="collapse"
                                             data-bs-target="#defaultExport" aria-expanded="false"
                                             aria-controls="defaultExport">
                                            <h6 class="p-0 m-0">التصدير</h6>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                 stroke-width="2" stroke-linecap="round"
                                                 stroke-linejoin="round"
                                                 class="feather feather-chevron-down">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
                                        </div>
                                    </section>
                                </div>
                                <div id="defaultExport" class="collapse" aria-labelledby="headingExport"
                                     wire:ignore.self
                                     data-bs-parent="#toggleExport">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="form-group col-md-12 mb-4 text-center">
                                                <h3>تاريخ الميلاد <span class="text-danger" style="font-size: 18px">(إختياري)</span>
                                                </h3>
                                            </div>
                                            <div class="form-group col-md-6 mb-4">
                                                <label for="dob_from">من</label>
                                                <input wire:model="ExportData.dob_from" type="date"
                                                       class="form-control"
                                                       id="dob_from">
                                            </div>
                                            <div class="form-group col-md-6 mb-4">
                                                <label for="dob_to">إلى</label>
                                                <input wire:model="ExportData.dob_to" type="date"
                                                       class="form-control"
                                                       id="dob_to">
                                            </div>
                                            <div class="form-group col-md-12 mb-4 text-center">
                                                <hr>
                                            </div>
                                            <div class="form-group col-md-12 mb-4 text-center">
                                                <h3>تاريخ الاعتقال <span class="text-danger" style="font-size: 18px">(إختياري)</span>
                                                </h3>
                                            </div>
                                            <div class="form-group col-md-6 mb-4">
                                                <label for="doa_from">من</label>
                                                <input wire:model="ExportData.doa_from" type="date"
                                                       class="form-control"
                                                       id="doa_from">
                                            </div>
                                            <div class="form-group col-md-6 mb-4">
                                                <label for="doa_to">إلى</label>
                                                <input wire:model="ExportData.doa_to" type="date"
                                                       class="form-control"
                                                       id="doa_to">
                                            </div>
                                            <div class="form-group col-md-12 mb-4 text-center">
                                                <hr>
                                            </div>
                                            <div class="form-group col-md-12 mb-4 text-center">
                                                <h3>الحكم مؤبدات<span class="text-danger" style="font-size: 18px">(إختياري)</span>
                                                </h3>
                                            </div>
                                            <div class="form-group col-md-6 mb-4">
                                                <label for="judgment_in_lifetime_from">من</label>
                                                <input wire:model="ExportData.judgment_in_lifetime_from" type="number"
                                                       class="form-control"
                                                       id="judgment_in_lifetime_from">
                                            </div>
                                            <div class="form-group col-md-6 mb-4">
                                                <label for="judgment_in_lifetime_to">إلى</label>
                                                <input wire:model="ExportData.judgment_in_lifetime_to" type="number"
                                                       class="form-control"
                                                       id="judgment_in_lifetime_to">
                                            </div>
                                            <div class="form-group col-md-12 mb-4 text-center">
                                                <hr>
                                            </div>
                                            <div class="form-group col-md-12 mb-4 text-center">
                                                <h3>الحكم سنوات<span class="text-danger" style="font-size: 18px">(إختياري)</span>
                                                </h3>
                                            </div>
                                            <div class="form-group col-md-6 mb-4">
                                                <label for="judgment_in_years_from">من</label>
                                                <input wire:model="ExportData.judgment_in_years_from" type="number"
                                                       class="form-control"
                                                       id="judgment_in_years_from">
                                            </div>
                                            <div class="form-group col-md-6 mb-4">
                                                <label for="judgment_in_years_to">إلى</label>
                                                <input wire:model="ExportData.judgment_in_years_to" type="number"
                                                       class="form-control"
                                                       id="judgment_in_years_to">
                                            </div>
                                            <div class="form-group col-md-12 mb-4 text-center">
                                                <hr>
                                            </div>
                                            <div class="form-group col-md-12 mb-4 text-center">
                                                <h3>بيانات اخرى <span class="text-danger" style="font-size: 18px">(إختياري)</span>
                                                </h3>
                                            </div>
                                            <div class="form-group col-md-6 mb-4">
                                                <label for="Gender">الجنس</label>
                                                <div id="toggleGender" class="Gender">
                                                    <div class="card">
                                                        <div class="card-header" id="headingGender" wire:ignore.self>
                                                            <section class="mb-0 mt-0">
                                                                <div role="menu"
                                                                     class="collapsed d-flex justify-content-between"
                                                                     data-bs-toggle="collapse"
                                                                     data-bs-target="#defaultGender"
                                                                     aria-expanded="false"
                                                                     aria-controls="defaultGender">
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
                                                        <div id="defaultGender" class="collapse"
                                                             aria-labelledby="headingGender"
                                                             wire:ignore.self
                                                             data-bs-parent="#toggleGender">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    @foreach(\App\Enums\Gender::cases() as $row)
                                                                        <div class="col-md-4 mb-4">
                                                                            <div
                                                                                class="form-check form-check-dark form-check-inline">
                                                                                <input class="form-check-input"
                                                                                       wire:model.live="ExportData.gender.{{$row->value}}"
                                                                                       type="checkbox"
                                                                                       id="form-check-dark">
                                                                                <label class="form-check-label"
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
                                            <div class="form-group col-md-6 mb-4">
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
                                                        <div id="defaultSocialType" class="collapse"
                                                             aria-labelledby="headingSocialType"
                                                             wire:ignore.self
                                                             data-bs-parent="#toggleSocialType">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    @foreach(\App\Enums\SocialType::cases() as $row)
                                                                        <div class="col-md-4 mb-4">
                                                                            <div
                                                                                class="form-check form-check-dark form-check-inline">
                                                                                <input class="form-check-input"
                                                                                       wire:model.live="ExportData.social_type.{{$row->value}}"
                                                                                       type="checkbox"
                                                                                       id="form-check-dark">
                                                                                <label class="form-check-label"
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

                                            <div class="form-group col-md-12 mb-4">
                                                <label for="City">المحافظة</label>
                                                <div id="toggleCity" class="City">
                                                    <div class="card">
                                                        <div class="card-header" id="headingCity" wire:ignore.self>
                                                            <section class="mb-0 mt-0">
                                                                <div role="menu"
                                                                     class="collapsed d-flex justify-content-between"
                                                                     data-bs-toggle="collapse"
                                                                     data-bs-target="#defaultCity" aria-expanded="false"
                                                                     aria-controls="defaultCity">
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
                                                        <div id="defaultCity" class="collapse"
                                                             aria-labelledby="headingCity"
                                                             wire:ignore.self
                                                             data-bs-parent="#toggleCity">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    @foreach($Cities as $city)
                                                                        <div class="col-md-4 mb-4">
                                                                            <div
                                                                                class="form-check form-check-dark form-check-inline">
                                                                                <input class="form-check-input"
                                                                                       wire:model.live="ExportData.city.{{$city->id}}"
                                                                                       type="checkbox"
                                                                                       id="form-check-dark">
                                                                                <label class="form-check-label"
                                                                                       for="form-check-dark">
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
                                            <div class="form-group col-md-12 mb-4">
                                                <label for="Town">البلدة</label>
                                                <div id="toggleTown" class="Town">
                                                    <div class="card">
                                                        <div class="card-header" id="headingTown" wire:ignore.self>
                                                            <section class="mb-0 mt-0">
                                                                <div role="menu"
                                                                     class="collapsed d-flex justify-content-between"
                                                                     data-bs-toggle="collapse"
                                                                     data-bs-target="#defaultTown" aria-expanded="false"
                                                                     aria-controls="defaultTown">
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
                                                        <div id="defaultTown" class="collapse"
                                                             aria-labelledby="headingTown"
                                                             wire:ignore.self
                                                             data-bs-parent="#toggleTown">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-12 mb-4">
                                                                        <input class="form-control" type="text"
                                                                               placeholder="بحث عن بلدة"
                                                                               wire:model.live="town_search">
                                                                    </div>
                                                                    @foreach($Towns as $key => $town)
                                                                        <div class="col-md-4 mb-4">
                                                                            <div
                                                                                class="form-check form-check-dark form-check-inline">
                                                                                <input class="form-check-input"
                                                                                       wire:model.live="ExportData.town.{{$town->id}}"
                                                                                       type="checkbox"
                                                                                       id="form-check-dark_{{$key}}">
                                                                                <label class="form-check-label"
                                                                                       for="form-check-dark_{{$key}}">
                                                                                    {{$town->town_name}}
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
                                                <label for="Belong">الإنتماء</label>
                                                <div id="toggleBelong" class="Belong">
                                                    <div class="card">
                                                        <div class="card-header" id="headingBelong" wire:ignore.self>
                                                            <section class="mb-0 mt-0">
                                                                <div role="menu"
                                                                     class="collapsed d-flex justify-content-between"
                                                                     data-bs-toggle="collapse"
                                                                     data-bs-target="#defaultBelong"
                                                                     aria-expanded="false"
                                                                     aria-controls="defaultBelong">
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
                                                        <div id="defaultBelong" class="collapse"
                                                             aria-labelledby="headingBelong"
                                                             wire:ignore.self
                                                             data-bs-parent="#toggleBelong">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    @foreach($Belongs as $belong)
                                                                        <div class="col-md-4 mb-4">
                                                                            <div
                                                                                class="form-check form-check-dark form-check-inline">
                                                                                <input class="form-check-input"
                                                                                       wire:model.live="ExportData.belong.{{$belong->id}}"
                                                                                       type="checkbox"
                                                                                       id="form-check-dark">
                                                                                <label class="form-check-label"
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
                                            <div class="form-group col-md-12 mb-4">
                                                <label for="PrisonerType">التصنيف</label>
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
                                                        <div id="defaultPrisonerType" class="collapse"
                                                             aria-labelledby="headingPrisonerType"
                                                             wire:ignore.self
                                                             data-bs-parent="#togglePrisonerType">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    @foreach($PrisonerTypes as $prisoner_type)
                                                                        <div class="col-md-4 mb-4">
                                                                            <div
                                                                                class="form-check form-check-dark form-check-inline">
                                                                                <input class="form-check-input"
                                                                                       wire:model.live="ExportData.prisoner_type.{{$prisoner_type->id}}"
                                                                                       type="checkbox"
                                                                                       id="form-check-dark">
                                                                                <label class="form-check-label"
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
                                            <div class="form-group col-md-12 mb-4">
                                                <label for="SpecialCase">الحالات الخاصة</label>
                                                <div id="toggleSpecialCase" class="SpecialCase">
                                                    <div class="card">
                                                        <div class="card-header" id="headingSpecialCase"
                                                             wire:ignore.self>
                                                            <section class="mb-0 mt-0">
                                                                <div role="menu"
                                                                     class="collapsed d-flex justify-content-between"
                                                                     data-bs-toggle="collapse"
                                                                     data-bs-target="#defaultSpecialCase"
                                                                     aria-expanded="false"
                                                                     aria-controls="defaultSpecialCase">
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
                                                        <div id="defaultSpecialCase" class="collapse"
                                                             aria-labelledby="headingSpecialCase"
                                                             wire:ignore.self
                                                             data-bs-parent="#toggleSpecialCase">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    @foreach(\App\Enums\SpecialCase::cases() as $key => $row)
                                                                        <div class="col-md-4 mb-4">
                                                                            <div
                                                                                class="form-check form-check-dark form-check-inline">
                                                                                <input class="form-check-input"
                                                                                       wire:model.live="ExportData.special_case.{{$row->value}}"
                                                                                       type="checkbox"
                                                                                       id="form-check-dark_{{$key}}">
                                                                                <label class="form-check-label"
                                                                                       for="form-check-dark_{{$key}}">
                                                                                    {{$row->value}}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                    <div class="col-md-4 mb-4">
                                                                        <div
                                                                            class="form-check form-check-dark form-check-inline">
                                                                            <input class="form-check-input"
                                                                                   wire:model.live="ExportData.is_released"
                                                                                   type="checkbox"
                                                                                   id="form-check-dark_is_released">
                                                                            <label class="form-check-label"
                                                                                   for="form-check-dark_is_released">
                                                                                مفرج عنه حالياً
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-12 mb-4 text-center">
                                                <hr>
                                            </div>
                                            <div class="form-group col-md-12 mb-4 text-center">
                                                <h3>الأعمدة <span class="text-danger"
                                                                  style="font-size: 18px">(إجباري)</span>
                                                </h3>
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
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal modal fade" id="delete" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content bg-white">
                <div class="modal-header bg-danger" style="margin: 5px;">
                    <h1 class="modal-title fs-5 text-white"
                        id="staticBackdropLabel">حذف الأسير</h1>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="text-danger m-3">هل أنت متأكد انك تريد حذف الأسير؟</h5>
                    <span class="text-danger m-3">
                        * تنبيه:
                        <span class="text-dark">
                            سيتم حذف {{$Prisoners_->full_name ?? null}}
                        </span>
                    </span>
                </div>
                <div class="modal-footer d-flex justify-content-start align-items-start">
                    <button type="submit" wire:click="confirmDelete" class="btn btn-danger">
                        تأكيد الحذف
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-trash-2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path
                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                    </button>
                    <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">إلغاء</button>
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

        window.addEventListener('showPrisoner', event => {
            $('#show').modal('show');
        })

        window.addEventListener('ImportExport', event => {
            $('#ImportExport').modal('show');
        })

        window.addEventListener('hideImportExport', event => {
            $('#ImportExport').modal('hide');
        })

        window.addEventListener('show_delete_modal', event => {
            $('#delete').modal('show');
        })
        window.addEventListener('hide_delete_modal', event => {
            $('#delete').modal('hide');
            toastr.success(event.detail.message, 'تهانينا!');
        })
        document.addEventListener('DOMContentLoaded', function () {
            document.body.addEventListener('hide_delete_modal', function () {
                Swal.fire(
                    {
                        title: 'نجاح',
                        text: 'تم حذف بيانات الأسير',
                        icon: 'success',
                        confirmButtonText: 'تم'
                    }
                );

            });
        });

        window.addEventListener('showAdvanceSearch', event => {
            $('#AdvanceSearch').modal('show');
        })
        window.addEventListener('hideAdvanceSearch', event => {
            $('#AdvanceSearch').modal('hide');
        })
    </script>
@endsection
