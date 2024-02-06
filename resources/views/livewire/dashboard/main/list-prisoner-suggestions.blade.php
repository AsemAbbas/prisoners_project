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
                                                <input id="prisoner_radio_{{$key}}" class="form-inline"
                                                       name="selected_prisoner" type="radio"
                                                       wire:model.live="change_prisoner_id" value="{{$key}}">
                                                <label for="prisoner_radio_{{$key}}">{{$prisoner}}</label>
                                            </div>
                                            @if($key == $change_prisoner_id)
                                                <div class="col-md-4 mb-1">
                                                    <a class="btn"
                                                       wire:confirm="هل أنت متأكد أنك تريد تحويل الطلب إلى {{$prisoner}}؟"
                                                       wire:click="makeItMain({{$change_prisoner_id}})">تحويل
                                                        الطلب</a>
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
                                                @if($key !== "ملاحظات النظام:")
                                                    <input type="checkbox"
                                                           id="{{$col['name']}}Checkbox"
                                                           name="{{$col['name']}}Checkbox"
                                                           wire:model.live="selectAccepted.{{$col['name']}}">
                                                @endif
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
                                                @if($key !== "ملاحظات النظام:")
                                                    <input type="checkbox"
                                                           id="{{$col['name']}}Checkbox"
                                                           name="{{$col['name']}}Checkbox"
                                                           wire:model.live="selectAccepted.{{$col['name']}}">
                                                @endif
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
                            class="btn btn-success" style="padding: 13px;">
                        تأكيد
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-check-circle">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </button>
                    <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal" style="padding: 13px;">إلغاء</button>
                    @if($Exist && !$Suggestions_->prisoner_id)
                        <p class="text-danger" style="margin-top: 11px">(رقم الهوية موجود مسبقاً)</p>
                    @endif

                    <div class="d-flex justify-content-center" style="width: 500px;">
                        <div>
                            <select wire:model.live="switch_city_id" class="form-select" style="width: 160px;">
                                <option>تحويل إلى...</option>
                                @foreach($Cities as $city)
                                    <option value="{{$city->id}}">{{$city->city_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @if(!empty($switch_city_id) && $switch_city_id != "تحويل إلى...")
                            <div>
                                <a wire:click="switchCity" wire:confirm="هل أنت متأكد أنك تريد تحويل الطلب؟"
                                   class="btn btn-success mx-1" style="padding: 13px;">تحويل الطلب</a>
                            </div>
                        @endif
                        <div>
                            <a wire:click="showNumberConverter" class="btn btn-secondary mx-1" style="padding: 13px;">محول الأرقام</a>
                        </div>
                    </div>
                    <div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Number Converter Modal -->
    <div class="modal modal fade" id="showNumberConverter" data-bs-backdrop="static" data-bs-keyboard="true"
         tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content bg-white">
                <div class="modal-header bg-secondary" style="margin: 5px;">
                    <h1 class="modal-title fs-5 text-white"
                        id="staticBackdropLabel">محول الارقام إلى واتس/تيليجرام</h1>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span class="text-dark">لضمان تحويل صحيح للرقم عليك وضع الرقم مبدوء ب 59</span>
                    <input type="text" class="form-control" wire:model.live="convert_number" placeholder="عليك وضع الرقم يبدأ ب 59">
                    @if(!empty($convert_number))
                        @if(strpos($convert_number, '59') === 0)
                        <div>
{{--                            <p class="mt-2">--}}
{{--                                <!-- Web Telegram -->--}}
{{--                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telegram" viewBox="0 0 16 16">--}}
{{--                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.287 5.906q-1.168.486-4.666 2.01-.567.225-.595.442c-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294q.39.01.868-.32 3.269-2.206 3.374-2.23c.05-.012.12-.026.166.016s.042.12.037.141c-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8 8 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629q.14.092.27.187c.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.4 1.4 0 0 0-.013-.315.34.34 0 0 0-.114-.217.53.53 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09"/>--}}
{{--                                </svg>--}}
{{--                                <a href="https://t.me/970{{$convert_number}}" target="_blank">({{$convert_number}})<span>970+</span></a><br>--}}
{{--                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telegram" viewBox="0 0 16 16">--}}
{{--                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.287 5.906q-1.168.486-4.666 2.01-.567.225-.595.442c-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294q.39.01.868-.32 3.269-2.206 3.374-2.23c.05-.012.12-.026.166.016s.042.12.037.141c-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8 8 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629q.14.092.27.187c.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.4 1.4 0 0 0-.013-.315.34.34 0 0 0-.114-.217.53.53 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09"/>--}}
{{--                                </svg>--}}
{{--                                <a href="https://t.me/972{{$convert_number}}" target="_blank">({{$convert_number}})<span>972+</span></a>--}}
{{--                            </p>--}}
                            <p class="mt-2">
                                <!-- Web WhatsApp -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                                    <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                                </svg>
                                <a href="https://wa.me/970{{$convert_number}}" style="text-align: left" target="_blank">({{$convert_number}})<span>970+</span></a><br>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                                    <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                                </svg>
                                <a href="https://wa.me/972{{$convert_number}}" style="text-align: left" target="_blank">({{$convert_number}})<span>972+</span></a>
                            </p>
                        </div>
                    @else
                        <p class="text-danger">الرجاء التأكد من أن الرقم يبدأ بـ 59</p>
                    @endif
                    @endif
                </div>
                <div class="modal-footer d-flex justify-content-start align-items-start">
                    <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">إلغاء</button>
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
        window.addEventListener('showNumberConverter', event => {
            $('#showNumberConverter').modal('show');
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
