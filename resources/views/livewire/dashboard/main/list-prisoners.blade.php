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
            width: 1px;
            box-sizing: border-box;
            border: 0;
            font-size: 16px;
            background-color: #fafafa;
            background-size: 35px;
            background-position: 3px 5px;
            background-repeat: no-repeat;
            padding: 0;
            margin-left: 0;
            -webkit-transition: width 0.4s ease-in-out;
            transition: width 0.4s ease-in-out;
        }

        /* When the input field gets focus, change its width to 100% */
        input[type=search]:focus {
            width: 275px;
            padding: 10px 20px 5px 40px;
            margin-left: 10px;
            background-color: white;
            border: 1px solid grey;
            border-radius: 7px;
        }

        .pagination-container {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
        }

        .pagination-button {
            padding: 8px 12px;
            margin: 0 5px;
            background-color: #4361ee;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-family: 'Changa', sans-serif;
            transition: background-color 0.3s ease;
        }

        .pagination-button:hover {
            background-color: #4361ee;
        }

        .pagination-current-page {
            padding: 8px 15px;
            font-weight: bold;
            font-size: 14px;
            font-family: 'Changa', sans-serif;
            border: 1px solid grey;
            border-radius: 5px;
        }
    </style>
@endsection
<div class="p-4">
    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('main.index')}}">الصفحة الرئيسية</a></li>
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
                        @if(in_array(\Illuminate\Support\Facades\Auth::user()->user_status,['مدخل بيانات','مسؤول']))
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
                    @guest
                        <a class="btn btn-primary mb-2" href="{{route('dashboard.suggestions.create')}}">
                            إقتراح إضافة
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-plus-circle">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="16"></line>
                                <line x1="8" y1="12" x2="16" y2="12"></line>
                            </svg>
                        </a>
                    @endguest
                </div>
                <div>
                    <input wire:model.live="Search" type="search" id="Search"
                           placeholder="البحث في قائمة الأسرى...">
                    <label class="btn btn-info" for="Search">
                        البحث
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-search">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </label>
                    @auth
                        @if(in_array(\Illuminate\Support\Facades\Auth::user()->user_status,['مدخل بيانات','مسؤول']))
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
                    <tr style="font-size: 16px">
                        <th>#</th>
                        <th style="min-width: 180px;font-weight: bold">اسم الأسير</th>
                        <th style="min-width: 180px;font-weight: bold">رقم الهوية</th>
                        <th style="min-width: 180px;font-weight: bold">تاريخ الميلاد</th>
                        <th style="min-width: 180px;font-weight: bold">الجنس</th>
                        <th style="min-width: 180px;font-weight: bold">المحافظة</th>
                        @auth
                            @if(in_array(\Illuminate\Support\Facades\Auth::user()->user_status,['مدخل بيانات','مسؤول']))
                                <th style="min-width: 250px;font-weight: bold">أقارب معتقلون</th>
                            @endif
                        @endauth
                        <th style="min-width: 180px;font-weight: bold">الخيارات</th>
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
                            <td>{{$row->full_name ?? 'لا يوجد'}}</td>
                            <td>{{$row->identification_number ?? 'لا يوجد'}}</td>
                            <td>{{$row->date_of_birth . ' (' . \Carbon\Carbon::parse($row->date_of_birth)->diffInYears() .' سنة)'  ?? 'لا يوجد'}}</td>
                            <td>{{$row->gender ?? 'لا يوجد'}}</td>
                            <td>{{$row->City->city_name ?? 'لا يوجد'}}</td>
                            @auth
                                {{--                                <td>--}}
                                {{--                                    <a href="{{route('dashboard.arrests',$row)}}" class="btn btn-dark-soft"--}}
                                {{--                                       data-toggle="tooltip" data-placement="top" title="show">--}}
                                {{--                                        @if(count($row->Arrest) > 0)--}}
                                {{--                                            الاعتقال ({{count($row->Arrest)}})--}}
                                {{--                                        @else--}}
                                {{--                                            أضف إعتقال--}}
                                {{--                                        @endif--}}
                                {{--                                    </a>--}}
                                {{--                                </td>--}}
                                @if(in_array(\Illuminate\Support\Facades\Auth::user()->user_status,['مدخل بيانات','مسؤول']))
                                    <td>
                                        <a href="{{route('dashboard.relatives_prisoners',$row)}}"
                                           class="btn btn-dark-soft"
                                           data-toggle="tooltip" data-placement="top" title="show">
                                            @if(count($row->RelativesPrisoner) > 0)
                                                أقارب معتقلون ({{count($row->RelativesPrisoner)}})
                                            @else
                                                أضف قريب
                                            @endif
                                        </a>
                                    </td>
                                @else
                                    <td>
                                        <a wire:click="show({{$row}})" class="btn btn-info">
                                            عرض المزيد
                                        </a>
                                    </td>
                                @endif
                            @endauth
                            @guest
                                <td>
                                    <a wire:click="show({{$row}})" class="btn btn-info">
                                        عرض المزيد
                                    </a>
                                    <a href="{{route('dashboard.suggestions.update',$row)}}" class="btn btn-warning"
                                       data-toggle="tooltip" data-placement="top" title="update">
                                        إقتراح تعديل
                                    </a>
                                </td>
                            @endguest
                            @auth
                                @if(in_array(\Illuminate\Support\Facades\Auth::user()->user_status,['مدخل بيانات','مسؤول']))
                                    <td>
                                        <a href="{{route('dashboard.prisoners.update',$row)}}" class="btn btn-warning"
                                           data-toggle="tooltip" data-placement="top" title="Edit">
                                            تعديل
                                        </a>
                                    </td>
                                @endif
                                @if(\Illuminate\Support\Facades\Auth::user()->user_status === "مسؤول")
                                    <td>
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
                                <h3>تاريخ الإعتقال <span class="text-secondary" style="font-size: 18px">(إختياري)</span>
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
                                                    <p class="p-0 m-0">إختر...</p>
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
                                                    <p class="p-0 m-0">إختر...</p>
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
                                                    <p class="p-0 m-0">إختر...</p>
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
                                                    <p class="p-0 m-0">إختر...</p>
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
                                                <div role="menu" class="collapsed d-flex justify-content-between"
                                                     data-bs-toggle="collapse"
                                                     data-bs-target="#defaultBelong" aria-expanded="false"
                                                     aria-controls="defaultBelong">
                                                    <p class="p-0 m-0">إختر...</p>
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
                                                    <p class="p-0 m-0">إختر...</p>
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
                                                    <p class="p-0 m-0">إختر...</p>
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
                                <h6>إسم الأسير:</h6>
                                <h4>{{$Prisoners_->full_name ?? 'لا يوجد'}}</h4>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6>رقم الهوية:</h6>
                                <h4>{{$Prisoners_->identification_number ?? 'لا يوجد'}}</h4>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6>تاريخ الميلاد:</h6>
                                <h4>{{$Prisoners_->date_of_birth . ' (' . \Carbon\Carbon::parse($Prisoners_->date_of_birth)->diffInYears() .' سنة)'  ?? 'لا يوجد'}}</h4>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6>الجنس:</h6>
                                <h4>{{$Prisoners_->gender ?? 'لا يوجد'}}</h4>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6>المحافظة:</h6>
                                <h4>{{$Prisoners_->City->city_name ?? 'لا يوجد'}}</h4>
                            </div>
                            <div class="col-md-6 mb-3">
                            </div>
                            @if(isset($Prisoners_->Arrest))
                                <div class="col-md-6 mb-3">
                                    <h6>تاريخ الإعتقال:</h6>
                                    <h4>{{$Prisoners_->Arrest->arrest_start_date ?? 'لا يوجد'}}</h4>
                                </div>
                                @if(isset($Prisoners_->Arrest->arrest_start_date))
                                    <div class="col-md-6 mb-3">
                                        <h6>تاريخ الإفراج المتوقع:</h6>
                                        <h4>
                                            @if(!empty($Prisoners_->Arrest->judgment_in_lifetime))
                                                {{\Illuminate\Support\Carbon::parse($Prisoners_->Arrest->arrest_start_date)->addYears($Prisoners_->Arrest->judgment_in_lifetime * 99)->format('Y-m-d')}}
                                            @elseif(!empty($Prisoners_->Arrest->judgment_in_years))
                                                {{ \Illuminate\Support\Carbon::parse($Prisoners_->Arrest->arrest_start_date)->addMonths($Prisoners_->Arrest->judgment_in_months)->addYears($Prisoners_->Arrest->judgment_in_years)->format('Y-m-d') }}
                                            @else
                                                لا يوجد
                                            @endif
                                        </h4>
                                    </div>
                                @endif
                                <div class="col-md-6 mb-3">
                                    <h6>نوع الإعتقال:</h6>
                                    <h4>{{$Prisoners_->Arrest->arrest_type ?? 'لا يوجد'}}</h4>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h6>الحكم:</h6>
                                    <h4>
                                        @if(isset($Prisoners_->Arrest->arrest_type) && $Prisoners_->Arrest->arrest_type == "محكوم")
                                            {{$Prisoners_->ArrestJudgment()}}
                                        @else
                                            لا يوجد
                                        @endif
                                    </h4>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h6>الإنتماء:</h6>
                                    <h4>{{$Prisoners_->Arrest->Belong->belong_name ?? 'لا يوجد'}}</h4>
                                </div>
                                <div class="col-md-12 mb-3">
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
                                    <h6>الحالة الخاصة:</h6>
                                    <h4>{{$Prisoners_->Arrest->special_case ?? 'لا يوجد'}}</h4>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <h6>أقارب معتقلين:</h6>
                                    <h4>
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
                                    <h4>
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
                                    <h6>الحالة الإجتماعية:</h6>
                                    <h4>{{$Prisoners_->Arrest->social_type ?? 'لا يوجد'}}</h4>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <h6>عدد الزوجات:</h6>
                                    <h4>{{$Prisoners_->Arrest->wife_type ?? 'لا يوجد'}}</h4>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <h6>عدد الأبناء:</h6>
                                    <h4>{{$Prisoners_->Arrest->number_of_children ?? 'لا يوجد'}}</h4>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h6>رقم التواصل (واتس/تلجرام):</h6>
                                    <h4>{{$Prisoners_->Arrest->first_phone_number ?? 'لا يوجد'}}</h4>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h6>صاحب الرقم الأساسي:</h6>
                                    <h4>{{$Prisoners_->Arrest->first_phone_owner ?? 'لا يوجد'}}</h4>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h6>رقم التواصل الإضافي:</h6>
                                    <h4>{{$Prisoners_->Arrest->second_phone_number ?? 'لا يوجد'}}</h4>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <h6>صاحب الرقم الإضافي:</h6>
                                    <h4>{{$Prisoners_->Arrest->second_phone_owner ?? 'لا يوجد'}}</h4>
                                </div>
                            @endif
                            <div class="col-md-6 mb-3">
                                <h6>البريد الإلكتروني:</h6>
                                <h4>{{$Prisoners_->email ?? 'لا يوجد'}}</h4>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h6>الملاحظات:</h6>
                                <h4>{{$Prisoners_->notes ?? 'لا يوجد'}}</h4>
                            </div>
                            <div class="col-md-12 mb-3 text-center">
                                <hr>
                                <h3>الإعتقالات السابقة</h3>
                                <hr>
                            </div>
                            @if(count($Prisoners_->OldArrest) > 0)
                                @foreach($Prisoners_->OldArrest->sortBy('old_arrest_start_date') as $key => $arrest)
                                    <div class="col-md-12 mb-3 text-center">
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6>بداية الإعتقال:</h6>
                                                <h4>{{$arrest->old_arrest_start_date ?? 'لا يوجد'}}</h4>
                                            </div>
                                            <div class="col-md-6">
                                                <h6>نهاية الإعتقال:</h6>
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
                                                <h3>تاريخ الإعتقال <span class="text-danger" style="font-size: 18px">(إختياري)</span>
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
                                                                    <p class="p-0 m-0">إختر...</p>
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
                                                                    <p class="p-0 m-0">إختر...</p>
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
                                                                    <p class="p-0 m-0">إختر...</p>
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
                                                                    <p class="p-0 m-0">إختر...</p>
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
                                                                    <p class="p-0 m-0">إختر...</p>
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
                                                                    <p class="p-0 m-0">إختر...</p>
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
                                                                    <p class="p-0 m-0">إختر...</p>
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
                                                                    <p class="p-0 m-0">إختر...</p>
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
                                                <label for="ArrestData">بيانات الإعتقال</label>
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
                                                                    <p class="p-0 m-0">إختر...</p>
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
                        * تنبية:
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
