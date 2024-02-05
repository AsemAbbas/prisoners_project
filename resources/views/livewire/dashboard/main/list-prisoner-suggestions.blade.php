@section('title')
    فجر الحرية | قائمة الاقتراحات
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

        .svg-body {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        svg {
            max-width: 100%;
            height: auto;
        }
    </style>
@endsection
<div class="p-4">
    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">لوحة التحكم</a></li>
                <li class="breadcrumb-item active" aria-current="page">قائمة الاقتراحات</li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between flex-wrap mt-2">
                <div>
                    <input wire:model.live="Search" class="form-input m-2" type="search" id="Search"
                           placeholder="البحث...">
                    <div class="btn-group mb-2 mx-2" role="group">
                        <button id="btndefault" type="button" class="btn btn-outline-dark dropdown-toggle"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">فرز حسب
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-chevron-down">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btndefault">
                            <a wire:click="SortBy('الكل')" class="btn dropdown-item"><i
                                        class="flaticon-home-fill-1 mr-1"></i>الكل</a>
                            <a wire:click="SortBy('التعديلات')" class="btn dropdown-item"><i
                                        class="flaticon-home-fill-1 mr-1"></i>التعديلات</a>
                            <a wire:click="SortBy('الإضافات')" class="btn dropdown-item"><i
                                        class="flaticon-gear-fill mr-1"></i>الاضافات</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-center">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>رقم الأسير</th>
                        <th>اسم الأسير</th>
                        <th>رقم الهوية</th>
                        <th>مقدم البيانات</th>
                        <th>صلة القرابة</th>
                        <th>حالة الطلب</th>
                        <th>تاريخ الطلب</th>
                        <th>الخيارات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($Suggestions as $key => $row)
                        <tr>
                            <td>{{$Suggestions->firstItem() + $key}}</td>
                            <td>
                                @if(isset($row->Prisoner->id))
                                    <a class="btn btn-link" wire:click="SearchFor({{$row->Prisoner->id}})">
                                        {{$row->Prisoner->id}}
                                    </a>
                                @else
                                    لا يوجد
                                @endif
                            </td>
                            <td>
                                @if(isset($row->Prisoner->full_name))
                                    <a class="btn" wire:click="SearchFor('{{$row->Prisoner->full_name}}')">
                                        {{$row->Prisoner->full_name}}
                                    </a>
                                @else
                                    <a class="btn" wire:click="SearchFor('{{$row->full_name}}')">
                                        {{$row->full_name}}
                                    </a>
                                @endif
                            </td>
                            <td>{{$row->Prisoner->identification_number ?? $row->identification_number}}</td>
                            <td>{{$row->suggester_name ?? 'لا يوجد'}}</td>
                            <td>{{$row->Relationship->relationship_name ?? 'لا يوجد'}}</td>
                            <td>
                                @if(isset($row->prisoner_id))
                                    <p class="text-warning">تعديل</p>
                                @else
                                    <p class="text-success">إضافة</p>
                                @endif
                            </td>
                            <td>
                                {{ \Illuminate\Support\Carbon::parse($row->created_at)->format('Y-m-d | h:i A') }}
                            </td>
                            <td>
                                <a wire:click="Accept({{$row}})"
                                   class="btn btn-outline-success @if($row->suggestion_status == "تم القبول") disabled @endif">
                                    @if($row->suggestion_status == "تم القبول")
                                        تم القبول
                                    @else
                                        مراجعة
                                    @endif
                                </a>
                                @if($row->suggestion_status == "يحتاج مراجعة")
                                    <a wire:click="delete({{$row}})" class="btn btn-danger">
                                        حذف
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{$Suggestions->links()}}
                </div>
            </div>
        </div>
    </div>

    <!-- Accept Modal -->
    <div class="modal @if(isset($Suggestions_->prisoner_id)) modal-fullscreen @else modal-lg @endif fade" id="accept"
         data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog @if(isset($Suggestions_->prisoner_id)) modal-fullscreen @else modal-lg @endif"
             role="document">
            <div class="modal-content bg-white">
                <div class="modal-header bg-success" style="margin: 5px;">
                    <h1 class="modal-title fs-5 text-white"
                        id="staticBackdropLabel">مراجعة البيانات</h1>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="prisoner_search">بحث عن أسير</label>
                                <input type="text" wire:model.live="prisoner_search" id="prisoner_search"
                                       class="form-control">
                            </div>
                            @if (!empty($PrisonerSearch))
                                <div class="col-md-8">
                                    @foreach ($PrisonerSearch as $key => $prisoner)
                                        <div class="row">
                                            <div class="col-md-8 mb-1">
                                                <input id="prisoner_radio_{{$key}}" class="form-inline" name="selected_prisoner" type="radio" wire:model.live="change_prisoner_id" value="{{$key}}">
                                                <label for="prisoner_radio_{{$key}}">{{$prisoner}}</label>
                                            </div>
                                            @if($key == $change_prisoner_id)
                                                <div class="col-md-4 mb-1">
                                                    <a class="btn" wire:click="makeItMain({{$change_prisoner_id}})">تحويل الطلب</a>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="col-lg-12 top-body mb-5 @if(isset($Suggestions_->prisoner_id)) text-center @endif">
                        <div class="row">
                            <div class="col-12 text-center">
                                <hr>
                                <h5>
                                    @if(isset($Suggestions_->prisoner_id))
                                        اقتراح تعديل
                                    @else
                                        @if($Exist)
                                            <p>اقتراح إضافة <span class="text-danger">(رقم الهوية موجود)</span></p>
                                        @else
                                            اقتراح إضافة (رقم الهوية جديد)
                                        @endif
                                    @endif
                                </h5>
                                <hr>
                            </div>
                            <div
                                    class="@if(isset($Suggestions_->prisoner_id)) col-md-3 mb-3 @else col-md-6 mb-3 @endif">
                                <h6>اسم مقدم البيانات: </h6>
                                <h5>{{$Suggestions_->suggester_name ?? 'لا يوجد'}}</h5>
                            </div>
                            <div
                                    class="@if(isset($Suggestions_->prisoner_id)) col-md-3 mb-3 @else col-md-6 mb-3 @endif">
                                <h6>رقم هوية مقدم البيانات: </h6>
                                <h5>{{$Suggestions_->suggester_identification_number ?? 'لا يوجد'}}</h5>
                            </div>
                            <div
                                    class="@if(isset($Suggestions_->prisoner_id)) col-md-3 mb-3 @else col-md-6 mb-3 @endif">
                                <h6>رقم هاتف مقدم البيانات: </h6>
                                <h5>{{$Suggestions_->suggester_phone_number ?? 'لا يوجد'}}</h5>
                            </div>
                            <div
                                    class="@if(isset($Suggestions_->prisoner_id)) col-md-3 mb-3 @else col-md-6 mb-3 @endif">
                                <h6>صلة قرابة مقدم البيانات: </h6>
                                <h5>{{$Suggestions_->Relationship->relationship_name ?? 'لا يوجد'}}</h5>
                            </div>
                        </div>
                    </div>
                    @if(isset($Suggestions_->prisoner_id))
                        <div class="col-lg-5 right-body text-center">
                            <div class="row">
                                <div class="col-12 text-center">
                                    <hr>
                                    <h5>بيانات الأسير الحالية</h5>
                                    <hr>
                                </div>
                                @if(isset($prisonerColumns))
                                    @foreach($prisonerColumns as $key => $col)
                                        <div class="col-md-6 mb-3">
                                            <h6>{{$key}}</h6>
                                            <h5>{{$col['prisoner']}}</h5>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-12 text-center">
                                    <hr>
                                    <h5>بيانات الاعتقال الحالية</h5>
                                    <hr>
                                </div>
                                @if(isset($arrestColumns))
                                    @foreach($arrestColumns as $key => $col)
                                        <div class="col-md-6 mb-3">
                                            <h6>{{$key}}</h6>
                                            <h5>{{$col['prisoner']}}</h5>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-2 svg-body">
                            <p class="text-center text-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-arrow-left-circle">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 8 8 12 12 16"></polyline>
                                    <line x1="16" y1="12" x2="8" y2="12"></line>
                                </svg>
                                <br>
                                <span>البيانات المختلفة لونها أحمر</span>
                            </p>
                        </div>
                        <div class="col-lg-5 left-body text-center">
                            <div class="row">
                                <div class="col-12 text-center">
                                    <hr>
                                    <input type="checkbox" id="selectAllCheckbox" name="selectAllCheckbox"
                                           wire:model.live="SelectAllPrisoners">
                                    <h5 class="d-inline">بيانات الأسير المقترح</h5>
                                    <hr>
                                </div>
                                @if(isset($prisonerColumns))
                                    @foreach($prisonerColumns as $key => $col)
                                        <div class="col-md-6 mb-3">
                                            <h6>
                                                <input type="checkbox"
                                                       id="{{$col['name']}}Checkbox"
                                                       name="{{$col['name']}}Checkbox"
                                                       wire:model.live="selectAccepted.{{$col['name']}}">
                                                {{$key}}
                                            </h6>

                                            <h5 class="@if($col['suggestion'] !== $col['prisoner']) text-danger @endif">{{$col['suggestion']}}</h5>
                                            @error($col['name'])
                                            <div class="text-danger" style="font-size: 15px">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="row">
                                <div class="col-12 text-center">
                                    <hr>
                                    <input type="checkbox" id="selectAllCheckbox" name="selectAllCheckbox"
                                           wire:model.live="SelectAllPrisonersArrest">
                                    <h5 class="d-inline">بيانات الاعتقال المقترح</h5>
                                    <hr>
                                </div>
                                @if(isset($arrestColumns))
                                    @foreach($arrestColumns as $key => $col)

                                        <div class="col-md-6 mb-3">
                                            <h6>
                                                <input type="checkbox"
                                                       id="{{$col['name']}}Checkbox"
                                                       name="{{$col['name']}}Checkbox"
                                                       wire:model.live="selectAcceptedArrest.{{$col['name']}}">
                                                {{$key}}
                                            </h6>
                                            <h5 class="@if($col['suggestion'] !== $col['prisoner']) text-danger @endif">{{$col['suggestion']}}</h5>
                                            @error($col['name'])
                                            <div class="text-danger" style="font-size: 15px">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @if(!empty($familyIDNumberColumns))
                            <div class="col-lg-8 mx-auto top-body mb-5 text-center">
                                <div class="row text-center">
                                    <div class="col-12 text-center">
                                        <hr>
                                        <h5>
                                            أقارب معتقلين
                                        </h5>
                                        <span class="text-danger" style="font-size: 17px">عليك تحديد الاقارب المقترحين الذين تريد أن يتم إضافتهم</span>
                                        <hr>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    @if(!empty($familyIDNumberColumns['prisoner']))
                                                        <div class="col-12 text-center">
                                                            <hr>
                                                            <h5 class="d-inline">
                                                                أقارب معتقلين حالية
                                                            </h5>
                                                            <hr>
                                                        </div>
                                                        @foreach($familyIDNumberColumns['prisoner'] as $index_idn => $row)
                                                            @if(!empty($row))
                                                                @foreach($row as $key_idn => $inside)
                                                                    <div class="col-md-4 mb-3">
                                                                        <h5 class="">{{ $inside['idn'] ?? 'لا يوجد' }}</h5>
                                                                    </div>
                                                                    <div class="col-md-4 mb-3">
                                                                        <h5 class="">{{ $inside['relationship_name'] ?? 'لا يوجد' }}</h5>
                                                                    </div>
                                                                    <div class="col-md-4 mb-3">
                                                                        <a wire:click="FamilyIdnPrisonerDeleted('{{ $index_idn }}','{{ $key_idn }}')"
                                                                           class="btn btn-danger">
                                                                            إزالة
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    @if(!empty($familyIDNumberColumns['prisoner_deleted']))
                                                        <div class="col-12 text-center">
                                                            <hr>
                                                            <h5 class="d-inline">
                                                                أقارب معتقلين حالية
                                                                <span
                                                                        class="text-danger d-block mt-2">(سيتم إزالتها)</span>
                                                            </h5>
                                                            <hr>
                                                        </div>
                                                        @foreach($familyIDNumberColumns['prisoner_deleted'] as $index_idn => $row)
                                                            @if(!empty($row))
                                                                @foreach($row as $key_idn => $inside)
                                                                    <div class="col-md-4 mb-3">
                                                                        <h5 class="">{{ $inside['idn'] ?? 'لا يوجد' }}</h5>
                                                                    </div>
                                                                    <div class="col-md-4 mb-3">
                                                                        <h5 class="">{{ $inside['relationship_name'] ?? 'لا يوجد' }}</h5>
                                                                    </div>
                                                                    <div class="col-md-4 mb-3">
                                                                        <a wire:click="FamilyIdnPrisonerRestore('{{ $index_idn }}','{{ $key_idn }}')"
                                                                           class="btn btn-warning">
                                                                            تراجع
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    @if(!empty($familyIDNumberColumns['suggestion']))
                                                        <div class="col-12 text-center">
                                                            <hr>
                                                            <h5 class="d-inline">
                                                                أقارب معتقلين مقترحة
                                                            </h5>
                                                            <hr>
                                                        </div>
                                                        @foreach($familyIDNumberColumns['suggestion'] as $index_idn => $row)
                                                            @if(!empty($row))
                                                                @foreach($row as $key_idn => $inside)
                                                                    <div class="col-md-4 mb-3">
                                                                        <h5 class="">{{ $inside['idn'] ?? 'لا يوجد' }}</h5>
                                                                    </div>
                                                                    <div class="col-md-4 mb-3">
                                                                        <h5 class="">{{ $inside['relationship_name'] ?? 'لا يوجد' }}</h5>
                                                                    </div>
                                                                    <div class="col-md-4 mb-3">
                                                                        <a wire:click="FamilyIdnSuggestionAccepted('{{ $index_idn }}','{{ $key_idn }}')"
                                                                           class="btn btn-success">
                                                                            إضافة
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    @if(!empty($familyIDNumberColumns['suggestion_accepted']))
                                                        <div class="col-12 text-center">
                                                            <hr>
                                                            <h5 class="d-inline">
                                                                أقارب معتقلين مقترحة
                                                                <span
                                                                        class="text-success d-block mt-2">(سيتم إضافتها)</span>
                                                            </h5>
                                                            <hr>
                                                        </div>
                                                        @foreach($familyIDNumberColumns['suggestion_accepted'] as $index_idn => $row)
                                                            @if(!empty($row))
                                                                @foreach($row as $key_idn => $inside)
                                                                    <div class="col-md-4 mb-3">
                                                                        <h5 class="">{{ $inside['idn'] ?? 'لا يوجد' }}</h5>
                                                                    </div>
                                                                    <div class="col-md-4 mb-3">
                                                                        <h5 class="">{{ $inside['relationship_name'] ?? 'لا يوجد' }}</h5>
                                                                    </div>
                                                                    <div class="col-md-4 mb-3">
                                                                        <a wire:click="FamilyIdnSuggestionUnaccepted('{{ $index_idn }}','{{ $key_idn }}')"
                                                                           class="btn btn-warning">
                                                                            تراجع
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-lg-8 mx-auto top-body mb-5 text-center">
                            <div class="row text-center">
                                <div class="col-12 text-center">
                                    <hr>
                                    <h5>
                                        الاعتقالات السابقة
                                    </h5>
                                    <span class="text-danger" style="font-size: 17px">عليك تحديد الاعتقالات المقترحة التي تريد أن يتم إضافتها</span>
                                    <hr>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        @if(!empty($oldArrestColumns['prisoner']))
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-12 text-center">
                                                        <hr>
                                                        <h5 class="d-inline">اعتقالات سابقة حالية</h5>
                                                        <hr>
                                                    </div>
                                                    @foreach($oldArrestColumns['prisoner'] as $index => $row)
                                                        <div class="col-md-3 mb-3">
                                                            <h5 class="">{{$row['old_arrest_start_date']}}</h5>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <h5 class="">{{$row['old_arrest_end_date']}}</h5>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <h5 class="">{{$row['arrested_side']}}</h5>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <a wire:click="removeFromPrisonerList({{$row['id']}})"
                                                               class="btn btn-danger">
                                                                إزالة
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        @if(!empty($oldArrestColumns['prisoner_deleted']))
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-12 text-center">
                                                        <hr>
                                                        <h5 class="d-inline">
                                                            اعتقالات سابقة حالية
                                                            <span class="text-danger d-block mt-2">(سيتم إزالتها)</span>
                                                        </h5>
                                                        <hr>
                                                    </div>
                                                    @foreach($oldArrestColumns['prisoner_deleted'] as $index => $row)
                                                        <div class="col-md-3 mb-3">
                                                            <h5 class="">{{$row['old_arrest_start_date']}}</h5>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <h5 class="">{{$row['old_arrest_end_date']}}</h5>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <h5 class="">{{$row['arrested_side']}}</h5>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <a wire:click="removeFromPrisonerDeletedList({{$row['id']}})"
                                                               class="btn btn-warning">
                                                                تراجع
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        @if(!empty($oldArrestColumns['suggestion']))
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-12 text-center">
                                                        <hr>
                                                        <h5 class="d-inline">اعتقالات سابقة مقترحة</h5>
                                                        <hr>
                                                    </div>
                                                    @foreach($oldArrestColumns['suggestion'] as $index => $row)
                                                        <div class="col-md-3 mb-3">
                                                            <h5 class="">{{$row['old_arrest_start_date']}}</h5>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <h5 class="">{{$row['old_arrest_end_date']}}</h5>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <h5 class="">{{$row['arrested_side']}}</h5>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <a wire:click="addToSuggestionAcceptedList({{$row['id']}})"
                                                               class="btn btn-success">
                                                                قبول
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        @if(!empty($oldArrestColumns['suggestion_accepted']))
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-12 text-center">
                                                        <hr>
                                                        <h5 class="d-inline">
                                                            اعتقالات سابقة مقترحة
                                                            <span
                                                                    class="text-success d-block mt-2">(سيتم إضافتها)</span>
                                                        </h5>
                                                        <hr>
                                                    </div>
                                                    @foreach($oldArrestColumns['suggestion_accepted'] as $index => $row)
                                                        <div class="col-md-3 mb-3">
                                                            <h5 class="">{{$row['old_arrest_start_date']}}</h5>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <h5 class="">{{$row['old_arrest_end_date']}}</h5>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <h5 class="">{{$row['arrested_side']}}</h5>
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <a wire:click="removeFromSuggestionAcceptedList({{$row['id']}})"
                                                               class="btn btn-warning">
                                                                تراجع
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-lg-12">
                            @if(isset($prisonerColumns))
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <hr>
                                        <h5>بيانات الأسير</h5>
                                        <input type="checkbox" id="selectAllCheckbox" name="selectAllCheckbox"
                                               wire:model.live="SelectAllPrisoners">
                                        <label for="selectAllCheckbox">تحديد الكل</label>
                                        <hr>
                                    </div>
                                    @foreach($prisonerColumns as $key => $col)
                                        <div class="col-md-6 mb-3">
                                            <h6>
                                                <input type="checkbox"
                                                       id="{{$col['name']}}Checkbox"
                                                       name="{{$col['name']}}Checkbox"
                                                       wire:model.live="selectAccepted.{{$col['name']}}">
                                                {{$key}}
                                            </h6>
                                            <h5>{{$col['suggestion']}}</h5>
                                            @error($col['name'])
                                            <div class="text-danger" style="font-size: 15px">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @if(isset($arrestColumns))
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <hr>
                                        <h5>بيانات الاعتقال</h5>
                                        <input type="checkbox" id="selectAllCheckbox" name="selectAllCheckbox"
                                               wire:model.live="SelectAllPrisonersArrest">
                                        <label for="selectAllCheckbox">تحديد الكل</label>
                                        <hr>
                                    </div>
                                    @foreach($arrestColumns as $key => $col)

                                        <div class="col-md-6 mb-3">
                                            <h6>
                                                <input type="checkbox"
                                                       id="{{$col['name']}}Checkbox"
                                                       name="{{$col['name']}}Checkbox"
                                                       wire:model.live="selectAcceptedArrest.{{$col['name']}}">
                                                {{$key}}
                                            </h6>
                                            <h5>{{$col['suggestion']}}</h5>
                                            @error($col['name'])
                                            <div class="text-danger" style="font-size: 15px">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            @if(!empty($familyIDNumberColumns))
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <hr>
                                        <h5>
                                            أقارب معتقلين
                                        </h5>
                                        <span class="text-danger" style="font-size: 17px">عليك تحديد الاقارب المقترحين الذين تريد أن يتم إضافتهم</span>
                                        <hr>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    @if(!empty($familyIDNumberColumns['suggestion']))
                                                        <div class="col-12 text-center">
                                                            <hr>
                                                            <h5 class="d-inline">
                                                                أقارب معتقلين مقترحة
                                                            </h5>
                                                            <hr>
                                                        </div>
                                                        @foreach($familyIDNumberColumns['suggestion'] as $index_idn => $row)
                                                            @if(!empty($row))
                                                                @foreach($row as $key_idn => $inside)
                                                                    <div class="col-md-4 mb-3">
                                                                        <h5 class="">{{ $inside['idn'] ?? 'لا يوجد' }}</h5>
                                                                    </div>
                                                                    <div class="col-md-4 mb-3">
                                                                        <h5 class="">{{ $inside['relationship_name'] ?? 'لا يوجد' }}</h5>
                                                                    </div>
                                                                    <div class="col-md-4 mb-3">
                                                                        <a wire:click="FamilyIdnSuggestionAccepted('{{ $index_idn }}','{{ $key_idn }}')"
                                                                           class="btn btn-success">
                                                                            إضافة
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    @if(!empty($familyIDNumberColumns['suggestion_accepted']))
                                                        <div class="col-12 text-center">
                                                            <hr>
                                                            <h5 class="d-inline">
                                                                أقارب معتقلين مقترحة
                                                                <span
                                                                        class="text-success d-block mt-2">(سيتم إضافتها)</span>
                                                            </h5>
                                                            <hr>
                                                        </div>
                                                        @foreach($familyIDNumberColumns['suggestion_accepted'] as $index_idn => $row)
                                                            @if(!empty($row))
                                                                @foreach($row as $key_idn => $inside)
                                                                    <div class="col-md-4 mb-3">
                                                                        <h5 class="">{{ $inside['idn'] ?? 'لا يوجد' }}</h5>
                                                                    </div>
                                                                    <div class="col-md-4 mb-3">
                                                                        <h5 class="">{{ $inside['relationship_name'] ?? 'لا يوجد' }}</h5>
                                                                    </div>
                                                                    <div class="col-md-4 mb-3">
                                                                        <a wire:click="FamilyIdnSuggestionUnaccepted('{{ $index_idn }}','{{ $key_idn }}')"
                                                                           class="btn btn-warning">
                                                                            تراجع
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="row">
                                @if(isset($oldArrestColumns['suggestion']))
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <hr>
                                                <h5 class="d-inline">اعتقالات سابقة مقترحة</h5>
                                                <hr>
                                            </div>
                                            @foreach($oldArrestColumns['suggestion'] as $index => $row)
                                                <div class="col-md-3 mb-3">
                                                    <h5 class="">{{$row['old_arrest_start_date']}}</h5>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <h5 class="">{{$row['old_arrest_end_date']}}</h5>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <h5 class="">{{$row['arrested_side']}}</h5>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <a wire:click="addToSuggestionAcceptedList({{$row['id']}})"
                                                       class="btn btn-success">
                                                        قبول
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                @if(isset($oldArrestColumns['suggestion_accepted']))
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-12 text-center">
                                                <hr>
                                                <h5 class="d-inline">
                                                    اعتقالات سابقة مقترحة
                                                    <span
                                                            class="text-success d-block mt-2">(سيتم إضافتها)</span>
                                                </h5>
                                                <hr>
                                            </div>
                                            @foreach($oldArrestColumns['suggestion_accepted'] as $index => $row)
                                                <div class="col-md-3 mb-3">
                                                    <h5 class="">{{$row['old_arrest_start_date']}}</h5>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <h5 class="">{{$row['old_arrest_end_date']}}</h5>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <h5 class="">{{$row['arrested_side']}}</h5>
                                                </div>
                                                <div class="col-md-3 mb-3">
                                                    <a wire:click="removeFromSuggestionAcceptedList({{$row['id']}})"
                                                       class="btn btn-warning">
                                                        تراجع
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer d-flex justify-content-start align-items-start">
                    <button type="submit"
                            @if(!empty(array_filter($selectAccepted)) || !empty(array_filter($selectAcceptedArrest)) || $APOAStatus || $ASOAStatus )@else disabled
                            @endif
                            wire:click="ConfirmAccept"
                            class="btn btn-success">
                        تأكيد
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-check-circle">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </button>
                    <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">إلغاء</button>

                    @if($Exist && !$Suggestions_->prisoner_id)
                        <p class="text-danger" style="margin-top: 11px">(رقم الهوية موجود مسبقاً)</p>
                    @endif
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
                        id="staticBackdropLabel">حذف الاقتراح</h1>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="text-danger m-3">هل أنت متأكد انك تريد حذف الاقتراح؟</h5>
                    <span class="text-danger m-3">
                    * تنبيه:
                        <span class="text-dark">
                        سيتم حذف {{$Suggestions_->full_name ?? null}}
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
                        text: 'تم حذف بيانات الاقتراح',
                        icon: 'success',
                        confirmButtonText: 'تم'
                    }
                );

            });
        });
        window.addEventListener('ShowAcceptModal', event => {
            $('#accept').modal('show');
        })
        window.addEventListener('HideAcceptModal', event => {
            $('#accept').modal('hide');
        })
        document.addEventListener('DOMContentLoaded', function () {
            document.body.addEventListener('create_massage', function () {
                Swal.fire({
                    title: 'نجاح',
                    text: 'تم قبول الاقتراح',
                    icon: 'success',
                    confirmButtonText: 'تم',
                });
            });
        });
        window.addEventListener('ReviewBefore', event => {
            $('#review').modal('show');
        })

    </script>
@endsection
