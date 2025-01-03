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
                                <a wire:click="edit({{$row->id}})" class="btn btn-warning">
                                    تعديل
                                </a>
                                @if($row->suggestion_status == "يحتاج مراجعة")
                                    <a wire:click="delete({{$row}})" class="btn btn-danger">
                                        حذف
                                    </a>
                                @endif
                                {{--متوقف للصيانة--}}
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
                            wire:loading.class="disabled"
                            class="btn btn-success" style="padding: 13px;">
                        تأكيد
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-check-circle">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </button>
                    <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal" style="padding: 13px;">
                        إلغاء
                    </button>
                    <div class="d-flex justify-content-center" style="width: 500px;">
                        <div>
                            <select wire:model.live="switch_city_id" class="form-select" style="width: 190px;">
                                <option>تحويل الطلب إلى...</option>
                                @foreach($Cities as $city)
                                    <option value="{{$city->id}}">{{$city->city_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @if(!empty($switch_city_id) && $switch_city_id != "تحويل الطلب إلى...")
                            <div>
                                <a wire:click="switchCity" wire:confirm="هل أنت متأكد أنك تريد تحويل الطلب؟"
                                   class="btn btn-success mx-1" style="padding: 13px;">تحويل الطلب</a>
                            </div>
                        @endif
                        <div>
                            <a wire:click="showNumberConverter" class="btn btn-secondary mx-1" style="padding: 13px;">مُحوّل
                                الأرقام</a>
                        </div>
                    </div>
                    @if($Exist && !$Suggestions_->prisoner_id)
                        <p class="text-danger" style="margin-top: 11px">(رقم الهوية موجود مسبقاً)</p>
                    @endif
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
                    <span class="text-dark">لضمان تحويل صحيح للرقم عليك وضع الرقم مبدوء بـ 5</span>
                    <input type="text" class="form-control" wire:model.live="convert_number"
                           placeholder="عليك وضع الرقم يبدأ بـ 5">
                    @if(!empty($convert_number))
                        @if(strpos($convert_number, '5') === 0)
                            <div>
                                <p class="mt-2">
                                    <!-- Web WhatsApp -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                         class="bi bi-whatsapp" viewBox="0 0 16 16">
                                        <path
                                            d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                                    </svg>
                                    <a href="https://wa.me/970{{$convert_number}}" style="text-align: left"
                                       target="_blank">({{$convert_number}})<span>970+</span></a><br>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                         class="bi bi-whatsapp" viewBox="0 0 16 16">
                                        <path
                                            d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                                    </svg>
                                    <a href="https://wa.me/972{{$convert_number}}" style="text-align: left"
                                       target="_blank">({{$convert_number}})<span>972+</span></a>
                                </p>
                            </div>
                        @else
                            <p class="text-danger">الرجاء التأكد من أن الرقم يبدأ بـ 5</p>
                        @endif
                    @endif
                </div>
                <div class="modal-footer d-flex justify-content-start align-items-start">
                    <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">إلغاء</button>
                </div>
            </div>
        </div>
    </div>

    <!-- CreateUpdate Modal -->
    <div class="modal modal-xl fade" id="CreateUpdate" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content bg-white">
                <div class="modal-header {{$showEditModel ? 'bg-warning' : 'bg-primary'}}" style="margin: 5px;">
                    <h1 class="modal-title fs-5 text-white"
                        id="staticBackdropLabel">{{$showEditModel ? 'تعديل افتراح' : 'إنشاء افتراح'}}</h1>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form
                        wire:submit.prevent="{{$showEditModel ? 'UpdateAcceptAdmin' : 'CreateAcceptAdmin'}}">
                        <div class="row">
                            <div class="form-group col-md-12 mb-4 border rounded-2 p-3">
                                <div class="form-group col-md-12 mb-4">
                                    <div class="row">
                                        <div class="form-group col-md-4 mb-4">
                                            <label for="identification_number">رقم هوية الأسير</label>
                                            <input wire:model.live="state.identification_number" type="text"
                                                   class="form-control @error('identification_number') is-invalid @enderror"
                                                   id="identification_number"
                                                   maxlength="9"
                                                   placeholder="رقم هوية الأسير">
                                            @error('identification_number')
                                            <div class="error-message invalid-feedback"
                                                 style="font-size: 15px">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        @php
                                            if (!empty($state['identification_number'])) {
                                                $prisoner_idn = \App\Models\Prisoner::query()
                                                    ->where('identification_number', $state['identification_number'])
                                                    ->first();
                                            }
                                        @endphp
                                        @if(!empty($prisoner_idn) && !isset($Prisoners_))
                                            <span class="text-danger">رقم الهوية لهذا الاسير مسجل سابقاً، يرجى إعادة البحث بواسطة رقم الهوية</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-3 mb-4">
                                        <label for="first_name">الاسم الأول</label>
                                        <input wire:model.live="state.first_name" type="text"
                                               class="form-control @error('first_name') is-invalid @enderror"
                                               id="first_name"
                                               placeholder="الاسم الأول">
                                        @error('first_name')
                                        <div class="error-message invalid-feedback"
                                             style="font-size: 15px">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 mb-4">
                                        <label for="second_name">اسم الأب</label>
                                        <input wire:model.live="state.second_name" type="text"
                                               class="form-control @error('second_name') is-invalid @enderror"
                                               id="second_name"
                                               placeholder="الاسم الأب">
                                        @error('second_name')
                                        <div class="error-message invalid-feedback"
                                             style="font-size: 15px">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 mb-4">
                                        <label for="third_name">اسم الجد</label>
                                        <input wire:model.live="state.third_name" type="text"
                                               class="form-control @error('third_name') is-invalid @enderror"
                                               id="third_name"
                                               placeholder="الاسم الجد">
                                        @error('third_name')
                                        <div class="error-message invalid-feedback"
                                             style="font-size: 15px">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 mb-4">
                                        <label for="last_name">اسم العائلة</label>
                                        <input wire:model.live="state.last_name" type="text"
                                               class="form-control @error('last_name') is-invalid @enderror"
                                               id="last_name"
                                               placeholder="اسم العائلة">
                                        @error('last_name')
                                        <div class="error-message invalid-feedback"
                                             style="font-size: 15px">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 mb-4">
                                        <label for="nick_name">اسم آخر للعائلة</label>
                                        <input wire:model="state.nick_name" type="text"
                                               class="form-control @error('nick_name') is-invalid @enderror"
                                               id="nick_name"
                                               placeholder="اسم آخر للعائلة">
                                        @error('nick_name')
                                        <div class="error-message invalid-feedback"
                                             style="font-size: 15px">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 mb-4">
                                        <label for="mother_name">اسم الأم</label>
                                        <input wire:model="state.mother_name" type="text"
                                               class="form-control @error('mother_name') is-invalid @enderror"
                                               id="mother_name"
                                               placeholder="اسم الأم">
                                        @error('mother_name')
                                        <div class="error-message invalid-feedback"
                                             style="font-size: 15px">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                @php
                                    if (!empty($state['first_name']) && !empty($state['second_name']) && !empty($state['third_name']) && !empty($state['last_name'])) {
                                        $prisoner_name = \App\Models\Prisoner::query()
                                            ->where('first_name', $state['first_name'])
                                            ->where('second_name', $state['second_name'])
                                            ->where('third_name', $state['third_name'])
                                            ->where('last_name', $state['last_name'])
                                            ->first();
                                    }
                                @endphp
                                @if(!empty($prisoner_name) && !isset($Prisoners_))
                                    <span class="text-danger">يوجد اسم مشابه سابقاً، يرجى التأكد من عدم التكرار</span>
                                @endif
                            </div>
                            <div class="form-group col-md-12 mb-4 border rounded-2 p-3">
                                <div class="row">
                                    <div class="form-group col-md-3 mb-4">
                                        <label for="date_of_birth">تاريخ الميلاد</label>

                                        <input wire:model="state.date_of_birth"
                                               type="text"
                                               class="form-control @error('date_of_birth') is-invalid @enderror"
                                               placeholder="سنة - شهر - يوم"
                                               oninput="formatDate(this)">
                                        @error('date_of_birth')
                                        <div class="error-message invalid-feedback"
                                             style="font-size: 15px">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 mb-4">
                                        <label for="gender">الجنس</label>
                                        <select wire:model.live="state.gender"
                                                class="form-select @error('gender') is-invalid @enderror"
                                                id="gender">
                                            <option>اختر...</option>
                                            @foreach(\App\Enums\Gender::cases() as $row)
                                                <option value="{{$row->value}}">{{$row->value}}</option>
                                            @endforeach
                                        </select>
                                        @error('gender')
                                        <div class="error-message invalid-feedback"
                                             style="font-size: 15px">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 mb-4">
                                        <label for="education_level">المستوى التعليمي</label>
                                        <select wire:model.live="state.education_level"
                                                class="form-select @error('education_level') is-invalid @enderror"
                                                id="education_level">
                                            <option>اختر...</option>
                                            @foreach(\App\Enums\EducationLevel::cases() as $row)
                                                <option value="{{$row->value}}">{{$row->value}}</option>
                                            @endforeach
                                        </select>
                                        @error('education_level')
                                        <div class="error-message invalid-feedback"
                                             style="font-size: 15px">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 mb-4">
                                    </div>
                                    <div class="form-group col-md-3 mb-4">
                                        <label for="city_id">المحافظة</label>
                                        <select wire:model.live="state.city_id"
                                                class="form-select @error('city_id') is-invalid @enderror"
                                                id="city_id">
                                            <option>اختر...</option>
                                            @foreach($Cities as $city)
                                                <option value="{{$city->id}}">{{$city->city_name}}</option>
                                            @endforeach
                                        </select>
                                        @error('city_id')
                                        <div class="error-message invalid-feedback"
                                             style="font-size: 15px">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 mb-4">
                                        <label for="town_id">البلدة</label>
                                        <span style="color: black">
                                            @php
                                                if (isset($state['town_id']))
                                                    $town_name = \App\Models\Town::query()->where('id',$state['town_id'])->pluck('town_name')->first();
                                                else $town_name = null;
                                            @endphp

                                            @if($town_name)
                                                الحالية:
                                                <span class="text-danger">({{$town_name}})</span>
                                            @endif
                                        </span>
                                        <select wire:model.live="state.town_id"
                                                @if(empty($state['city_id'])) disabled
                                                @endif
                                                class="form-select @error('town_id') is-invalid @enderror"
                                                id="town_id">
                                            <option>اختر...</option>
                                            @foreach($Towns as $town)
                                                <option value="{{$town->id}}">{{$town->town_name}}</option>
                                            @endforeach
                                            @if(isset($state) && isset($state['city_id']) && ($state['city_id'] == "20" || $state['city_id'] == "21"))
                                                <option value="إضافة بلدة جديدة">إضافة بلدة جديدة</option>
                                            @endif
                                        </select>
                                        @error('town_id')
                                        <div class="error-message invalid-feedback"
                                             style="font-size: 15px">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @if(isset($state['town_id']) && $state['town_id'] == "إضافة بلدة جديدة" && isset($state['city_id']) && ($state['city_id'] == "20" || $state['city_id'] == "21"))
                                        <div class="form-group col-md-3 mb-4">
                                            <label for="new_town_name">اسم البلدة</label>
                                            <input wire:model="new_town_name" type="text"
                                                   class="form-control @error('new_town_name') is-invalid @enderror"
                                                   id="new_town_name"
                                                   placeholder="اسم البلدة">
                                            <a class="btn btn-success" wire:click="addNewTown({{$state['city_id']}})">إضافة</a>
                                            @error('new_town_name')
                                            <div class="error-message invalid-feedback"
                                                 style="font-size: 15px">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-12 mb-4 border rounded-2 p-3">
                                <div class="row">
                                    <div class="form-group col-md-3 mb-4">
                                        <label for="social_type">الحالة الإجتماعية</label>
                                        <select wire:model.live="state.social_type"
                                                class="form-select @error('social_type') is-invalid @enderror"
                                                id="social_type">
                                            <option>اختر...</option>
                                            @foreach(\App\Enums\SocialType::cases() as $row)
                                                <option value="{{$row->value}}">{{$row->value}}</option>
                                            @endforeach
                                        </select>
                                        @error('social_type')
                                        <div class="error-message invalid-feedback"
                                             style="font-size: 15px">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @if(isset($state['social_type']) && !in_array($state['social_type'],['أعزب','اختر...']))
                                        @if($state['social_type'] !== "مطلق")
                                            @if(isset($state['gender']) && $state['gender'] === 'ذكر')
                                                <div class="form-group col-md-4 mb-4">
                                                    <label for="wife_type" class="d-block">عدد الزوجات</label>
                                                    <div class="bg-white p-2 mt-1 border rounded-2">
                                                        @foreach(\App\Enums\WifeType::cases() as $row)
                                                            <div
                                                                class="form-check form-check-primary form-check-inline">
                                                                <input class="form-check-input" type="radio"
                                                                       name="wife_type"
                                                                       wire:model="state.wife_type"
                                                                       value="{{$row->value}}" id="wife_type">
                                                                <label class="form-check-label" for="wife_type">
                                                                    {{$row->value}}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                        @error('wife_type')
                                                        <div class="error-message invalid-feedback"
                                                             style="font-size: 15px">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                        <div class="form-group col-md-3 mb-4">
                                            <label for="number_of_children">عدد الأبناء</label>
                                            <input wire:model="state.number_of_children" type="number"
                                                   style="text-align: right"
                                                   class="form-control @error('number_of_children') is-invalid @enderror"
                                                   id="number_of_children"
                                                   placeholder="عدد الأبناء">
                                            @error('number_of_children')
                                            <div class="error-message invalid-feedback"
                                                 style="font-size: 15px">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-12 mb-4 border rounded-2 p-3">
                                <div class="row">
                                    <div class="form-group col-md-3 mb-4">
                                        <label for="arrest_start_date">تاريخ الاعتقال</label>
                                        <input wire:model="state.arrest_start_date"
                                               type="text"
                                               class="form-control @error('arrest_start_date') is-invalid @enderror"

                                               placeholder="سنة - شهر - يوم"
                                               oninput="formatDate(this)">
                                        @error('arrest_start_date')
                                        <div class="error-message invalid-feedback"
                                             style="font-size: 15px">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 mb-4">
                                        <label for="belong_id">الإنتماء</label>
                                        <select wire:model.live="state.belong_id"
                                                class="form-select @error('belong_id') is-invalid @enderror"
                                                id="belong_id">
                                            <option>اختر...</option>
                                            @foreach($Belongs as $row)
                                                <option value="{{$row->id}}">{{$row->belong_name}}</option>
                                            @endforeach
                                        </select>
                                        @error('belong_id')
                                        <div class="error-message invalid-feedback"
                                             style="font-size: 15px">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 mb-4">
                                        <label for="arrest_type">نوع الاعتقال</label>
                                        <select wire:model.live="state.arrest_type"
                                                class="form-select @error('arrest_type') is-invalid @enderror"
                                                id="arrest_type">
                                            <option>اختر...</option>
                                            @foreach(\App\Enums\ArrestType::cases() as $row)
                                                <option value="{{$row->value}}">{{$row->value}}</option>
                                            @endforeach
                                        </select>
                                        @error('arrest_type')
                                        <div class="error-message invalid-feedback"
                                             style="font-size: 15px">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 mb-4">
                                    </div>
                                    @if(isset($state['arrest_type']) &&  in_array($state['arrest_type'],['محكوم','موقوف']))
                                        <div class="form-group col-md-3 mb-4">
                                            <label for="judgment_in_lifetime">
                                                الحكم
                                                @if($state['arrest_type'] === 'موقوف')
                                                    المتوقع
                                                @endif
                                                بالمؤبدات
                                            </label>
                                            <input wire:model="state.judgment_in_lifetime"
                                                   type="number"
                                                   class="form-control @error('judgment_in_lifetime') is-invalid @enderror"
                                                   style="text-align: right"
                                                   min="0"
                                                   placeholder="أرقام (اختياري)"
                                                   id="judgment_in_lifetime">
                                            @error('judgment_in_lifetime')
                                            <div class="error-message invalid-feedback"
                                                 style="font-size: 15px">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-3 mb-4">
                                            <label for="judgment_in_years">
                                                الحكم
                                                @if($state['arrest_type'] === 'موقوف')
                                                    المتوقع
                                                @endif
                                                بالسنوات
                                            </label>
                                            <input wire:model="state.judgment_in_years" type="number"
                                                   class="form-control @error('judgment_in_years') is-invalid @enderror"
                                                   placeholder="أرقام (اختياري)"
                                                   style="text-align: right"
                                                   min="0"
                                                   id="judgment_in_years">
                                            @error('judgment_in_years')
                                            <div class="error-message invalid-feedback"
                                                 style="font-size: 15px">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-3 mb-4">
                                            <label for="judgment_in_months">
                                                الحكم
                                                @if($state['arrest_type'] === 'موقوف')
                                                    المتوقع
                                                @endif
                                                بالأشهر
                                            </label>
                                            <input wire:model="state.judgment_in_months" type="number"
                                                   class="form-control @error('judgment_in_months') is-invalid @enderror"
                                                   placeholder="أرقام (اختياري)"
                                                   style="text-align: right"
                                                   min="0"
                                                   id="judgment_in_months">
                                            @error('judgment_in_months')
                                            <div class="error-message invalid-feedback"
                                                 style="font-size: 15px">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-12 mb-4 border rounded-2 p-3">
                                <div class="row">
                                    <div class="form-group col-md-5 mb-4">
                                        <label for="SpecialCase">حالة خاصة</label>
                                        <div id="toggleSpecialCase" class="SpecialCase">
                                            <div class="card">
                                                <div class="card-header" id="headingSpecialCase" wire:ignore.self>
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
                                                @php
                                                    // Assuming $state['special_case'] is an array
                                                    $keyToDelete = ''; // Set the key you want to delete

                                                    if (isset($state['special_case'][$keyToDelete])) {
                                                        unset($state['special_case'][$keyToDelete]);
                                                    }
                                                @endphp
                                                <div id="defaultSpecialCase"
                                                     class="collapse show"
                                                     aria-labelledby="headingSpecialCase"
                                                     wire:ignore.self
                                                     data-bs-parent="#toggleSpecialCase">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            @foreach(\App\Enums\SpecialCase::cases() as $index => $row)
                                                                @if($row->value == 'حامل')
                                                                    @if(isset($state['gender']) && $state['gender'] == "انثى")
                                                                        <div class="col-md-6">
                                                                            <div
                                                                                class="form-check form-check-dark form-check-inline">
                                                                                <input class="form-check-input"
                                                                                       wire:model.live="state.special_case.{{$row->value}}"
                                                                                       type="checkbox"
                                                                                       id="form-check-dark">
                                                                                <label class="form-check-label"
                                                                                       for="form-check-dark">
                                                                                    {{$row->value}}
                                                                                </label>
                                                                            </div>
                                                                            @error('state.special_case.' . $index)
                                                                            <div class="error-message invalid-feedback"
                                                                                 style="font-size: 15px">{{ $message }}</div>
                                                                            @enderror
                                                                        </div>
                                                                    @endif
                                                                @else
                                                                    <div class="col-md-6">
                                                                        <div
                                                                            class="form-check form-check-dark form-check-inline">
                                                                            <input class="form-check-input"
                                                                                   wire:model.live="state.special_case.{{$row->value}}"
                                                                                   type="checkbox"
                                                                                   id="form-check-dark">
                                                                            <label class="form-check-label"
                                                                                   for="form-check-dark">
                                                                                {{$row->value}}
                                                                            </label>
                                                                        </div>
                                                                        @error('state.special_case.' . $index)
                                                                        <div class="error-message invalid-feedback"
                                                                             style="font-size: 15px">{{ $message }}</div>
                                                                        @enderror
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if(isset($state['special_case']) && !empty(array_filter($state['special_case'])))
                                        @php
                                            $data = array_filter($state['special_case']);
                                        @endphp
                                        @if(in_array("مريض / جريح",array_filter(array_keys($data))))
                                            <div class="form-group col-md-3 mb-4">
                                                <label for="health_note">وصف الحالة المرضية</label>
                                                <textarea id="health_note" rows="8"
                                                          class="form-control @error('health_note') is-invalid @enderror"
                                                          placeholder="اكتب وصف الحالة المرضية..."
                                                          wire:model="state.health_note"></textarea>
                                                @error('health_note')
                                                <div class="error-message invalid-feedback"
                                                     style="font-size: 15px">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        @endif
                                        @if(in_array("أقارب معتقلين",array_filter(array_keys($data))))
                                            <div class="form-group col-md-10 mb-4">
                                                <label for="FamilyArrested">أقارب معتقلين</label>
                                                <div id="toggleFamilyArrested" class="FamilyArrested">
                                                    <div class="card">
                                                        <div class="card-header" id="headingFamilyArrested"
                                                             wire:ignore.self>
                                                            <section class="mb-0 mt-0">
                                                                <div role="menu"
                                                                     class="collapsed d-flex justify-content-between"
                                                                     data-bs-toggle="collapse"
                                                                     data-bs-target="#defaultFamilyArrested"
                                                                     aria-expanded="false"
                                                                     aria-controls="defaultFamilyArrested">
                                                                    <p class="p-0 m-0 text-danger">
                                                                        عليك اختيار المعتقلين من الأقارب ووضع أرقام
                                                                        هوياتهم
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
                                                        <div id="defaultFamilyArrested" class="collapse show"
                                                             aria-labelledby="headingFamilyArrested"
                                                             wire:ignore.self
                                                             data-bs-parent="#toggleFamilyArrested">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-6 mb-4">
                                                                        <div
                                                                            class="form-check form-check-dark form-check-inline">
                                                                            <input class="form-check-input"
                                                                                   wire:model.live="state.father_arrested"
                                                                                   type="checkbox"
                                                                                   id="form-check-dark">
                                                                            <label class="form-check-label"
                                                                                   for="form-check-dark">
                                                                                الأب
                                                                            </label>
                                                                        </div>
                                                                        @if(isset($state) && isset($state['father_arrested']) &&  $state['father_arrested'] === true)
                                                                            <div>
                                                                                <label>
                                                                                    <input
                                                                                        class="form-input border rounded-2 p-2"
                                                                                        wire:model.live="state.father_arrested_id"
                                                                                        maxlength="9"
                                                                                        placeholder="رقم هوية الاب"
                                                                                        type="text">
                                                                                </label>
                                                                                @php
                                                                                    if (isset($state) && isset($state['father_arrested_id']) && strlen($state['father_arrested_id']) == 9)
                                                                                        $father_arrested_id_check = (boolean)\App\Models\Prisoner::query()->where('identification_number' ,$state['father_arrested_id'])->first()
                                                                                @endphp
                                                                                @if(isset($state['father_arrested_id']) && strlen($state['father_arrested_id']) == 9)
                                                                                    @if(isset($father_arrested_id_check))
                                                                                        @if($father_arrested_id_check)
                                                                                            <span
                                                                                                class="text-success">موجود</span>
                                                                                        @else
                                                                                            <span class="text-danger">غير موجود (يرجى الإضافة)</span>
                                                                                        @endif
                                                                                    @endif
                                                                                @endif
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-md-6 mb-4">
                                                                        <div
                                                                            class="form-check form-check-dark form-check-inline">
                                                                            <input class="form-check-input"
                                                                                   wire:model.live="state.mother_arrested"
                                                                                   type="checkbox"
                                                                                   id="form-check-dark">
                                                                            <label class="form-check-label"
                                                                                   for="form-check-dark">
                                                                                الأم
                                                                            </label>
                                                                        </div>
                                                                        @if(isset($state) && isset($state['mother_arrested']) &&  $state['mother_arrested'] === true)
                                                                            <div>
                                                                                <label>
                                                                                    <input
                                                                                        class="form-input border rounded-2 p-2"
                                                                                        wire:model.live="state.mother_arrested_id"
                                                                                        maxlength="9"
                                                                                        placeholder="رقم هوية الأم"
                                                                                        type="text">
                                                                                </label>
                                                                                @php
                                                                                    if (isset($state) && isset($state['mother_arrested_id']) && strlen($state['mother_arrested_id']) == 9)
                                                                                        $mother_arrested_id_check = (boolean)\App\Models\Prisoner::query()->where('identification_number' ,$state['mother_arrested_id'])->first()
                                                                                @endphp
                                                                                @if(isset($state['mother_arrested_id']) && strlen($state['mother_arrested_id']) == 9)
                                                                                    @if(isset($mother_arrested_id_check))
                                                                                        @if($mother_arrested_id_check)
                                                                                            <span
                                                                                                class="text-success">موجود</span>
                                                                                        @else
                                                                                            <span class="text-danger">غير موجود (يرجى الإضافة)</span>
                                                                                        @endif
                                                                                    @endif
                                                                                @endif
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-md-6 mb-4">
                                                                        <div
                                                                            class="form-check form-check-dark form-check-inline">
                                                                            <input class="form-check-input"
                                                                                   wire:model.live="state.husband_arrested"
                                                                                   type="checkbox"
                                                                                   id="form-check-dark">
                                                                            <label class="form-check-label"
                                                                                   for="form-check-dark">
                                                                                الزوج
                                                                            </label>
                                                                        </div>
                                                                        @if(isset($state) && isset($state['husband_arrested']) &&  $state['husband_arrested'] === true)
                                                                            <div>
                                                                                <label>
                                                                                    <input
                                                                                        class="form-input border rounded-2 p-2"
                                                                                        wire:model.live="state.husband_arrested_id"
                                                                                        maxlength="9"
                                                                                        placeholder="رقم هوية الزوج"
                                                                                        type="text">
                                                                                </label>
                                                                                @php
                                                                                    if (isset($state) && isset($state['husband_arrested_id']) && strlen($state['husband_arrested_id']) == 9)
                                                                                        $husband_arrested_id_check = (boolean)\App\Models\Prisoner::query()->where('identification_number' ,$state['husband_arrested_id'])->first()
                                                                                @endphp
                                                                                @if(isset($state['husband_arrested_id']) && strlen($state['husband_arrested_id']) == 9)
                                                                                    @if(isset($husband_arrested_id_check))
                                                                                        @if($husband_arrested_id_check)
                                                                                            <span
                                                                                                class="text-success">موجود</span>
                                                                                        @else
                                                                                            <span class="text-danger">غير موجود (يرجى الإضافة)</span>
                                                                                        @endif
                                                                                    @endif
                                                                                @endif
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-md-6 mb-4">
                                                                        <div
                                                                            class="form-check form-check-dark form-check-inline">
                                                                            <input class="form-check-input"
                                                                                   wire:model.live="state.wife_arrested"
                                                                                   type="checkbox"
                                                                                   id="form-check-dark">
                                                                            <label class="form-check-label"
                                                                                   for="form-check-dark">
                                                                                الزوجة
                                                                            </label>
                                                                        </div>
                                                                        @if(isset($state) && isset($state['wife_arrested']) &&  $state['wife_arrested'] === true)
                                                                            <div>
                                                                                <label>
                                                                                    <input
                                                                                        class="form-input border rounded-2 p-2"
                                                                                        wire:model.live="state.wife_arrested_id"
                                                                                        placeholder="رقم هوية الزوجة"
                                                                                        maxlength="9"
                                                                                        type="text">
                                                                                </label>
                                                                                @php
                                                                                    if (isset($state) && isset($state['wife_arrested_id']) && strlen($state['wife_arrested_id']) == 9)
                                                                                        $wife_arrested_id_check = (boolean)\App\Models\Prisoner::query()->where('identification_number' ,$state['wife_arrested_id'])->first()
                                                                                @endphp
                                                                                @if(isset($state['wife_arrested_id']) && strlen($state['wife_arrested_id']) == 9)
                                                                                    @if(isset($wife_arrested_id_check))
                                                                                        @if($wife_arrested_id_check)
                                                                                            <span
                                                                                                class="text-success">موجود</span>
                                                                                        @else
                                                                                            <span class="text-danger">غير موجود (يرجى الإضافة)</span>
                                                                                        @endif
                                                                                    @endif
                                                                                @endif
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-md-6 mb-4">
                                                                        <div class="form-group mb-2">
                                                                            <label for="brother_arrested"
                                                                                   style="display: inline-block;">
                                                                                أخ
                                                                            </label>
                                                                            <input class="form-control form-control-sm"
                                                                                   wire:model.live="state.brother_arrested"
                                                                                   type="number"
                                                                                   max="10"
                                                                                   min="0"
                                                                                   id="brother_arrested"
                                                                                   style="width: 80px; display: inline-block;">
                                                                        </div>
                                                                        @if(isset($state) && isset($state['brother_arrested']) &&  $state['brother_arrested'] > 0)
                                                                            @for($i = 1; $i <= min(10, $state['brother_arrested']); $i++)
                                                                                <div>
                                                                                    <label>
                                                                                        <input
                                                                                            class="form-input border rounded-2 p-2"
                                                                                            wire:model.live="state.brother_arrested_id.{{$i}}"
                                                                                            placeholder="رقم هوية الأخ {{$i}}"
                                                                                            maxlength="9"
                                                                                            type="text">
                                                                                    </label>
                                                                                    @php
                                                                                        if (isset($state) && isset($state['brother_arrested_id']) && isset($state['brother_arrested_id'][$i]) && strlen($state['brother_arrested_id'][$i]) == 9)
                                                                                            $brother_arrested_id_check[$i] = (boolean)\App\Models\Prisoner::query()->where('identification_number' ,$state['brother_arrested_id'][$i])->first()
                                                                                    @endphp
                                                                                    @if(isset($state['brother_arrested_id'][$i]) && strlen($state['brother_arrested_id'][$i]) == 9)
                                                                                        @if(isset($brother_arrested_id_check[$i]))
                                                                                            @if($brother_arrested_id_check[$i])
                                                                                                <span
                                                                                                    class="text-success">موجود</span>
                                                                                            @else
                                                                                                <span
                                                                                                    class="text-danger">غير موجود (يرجى الإضافة)</span>
                                                                                            @endif
                                                                                        @endif
                                                                                    @endif
                                                                                </div>
                                                                            @endfor
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-md-6 mb-4">
                                                                        <div class="form-group mb-2">
                                                                            <label for="brother_arrested"
                                                                                   style="display: inline-block;">
                                                                                أخت
                                                                            </label>
                                                                            <input class="form-control form-control-sm"
                                                                                   wire:model.live="state.sister_arrested"
                                                                                   type="number"
                                                                                   max="10"
                                                                                   min="0"
                                                                                   id="brother_arrested"
                                                                                   style="width: 80px; display: inline-block;">
                                                                        </div>
                                                                        @if(isset($state) && isset($state['sister_arrested']) &&  $state['sister_arrested'] > 0)
                                                                            @for($i = 1; $i <= min(10, $state['sister_arrested']); $i++)
                                                                                <div>
                                                                                    <label>
                                                                                        <input
                                                                                            class="form-input border rounded-2 p-2"
                                                                                            wire:model.live="state.sister_arrested_id.{{$i}}"
                                                                                            placeholder="رقم هوية الأخت {{$i}}"
                                                                                            maxlength="9"
                                                                                            type="text">
                                                                                    </label>
                                                                                    @php
                                                                                        if (isset($state) && isset($state['sister_arrested_id']) && isset($state['sister_arrested_id'][$i]) && strlen($state['sister_arrested_id'][$i]) == 9)
                                                                                            $sister_arrested_id_check[$i] = (boolean)\App\Models\Prisoner::query()->where('identification_number' ,$state['sister_arrested_id'][$i])->first()
                                                                                    @endphp
                                                                                    @if(isset($state['sister_arrested_id'][$i]) && strlen($state['sister_arrested_id'][$i]) == 9)
                                                                                        @if(isset($sister_arrested_id_check[$i]))
                                                                                            @if($sister_arrested_id_check[$i])
                                                                                                <span
                                                                                                    class="text-success">موجود</span>
                                                                                            @else
                                                                                                <span
                                                                                                    class="text-danger">غير موجود (يرجى الإضافة)</span>
                                                                                            @endif
                                                                                        @endif
                                                                                    @endif
                                                                                </div>
                                                                            @endfor

                                                                        @endif
                                                                    </div>
                                                                    <div class="col-md-6 mb-4">
                                                                        <div class="form-group mb-2">
                                                                            <label for="son_arrested"
                                                                                   style="display: inline-block;">
                                                                                ابن
                                                                            </label>
                                                                            <input class="form-control form-control-sm"
                                                                                   wire:model.live="state.son_arrested"
                                                                                   type="number"
                                                                                   max="10"
                                                                                   min="0"
                                                                                   id="son_arrested"
                                                                                   style="width: 80px; display: inline-block;">
                                                                        </div>
                                                                        @if(isset($state) && isset($state['son_arrested']) &&  $state['son_arrested'] > 0)
                                                                            @for($i = 1; $i <= min(10, $state['son_arrested']); $i++)
                                                                                <div>
                                                                                    <label>
                                                                                        <input
                                                                                            class="form-input border rounded-2 p-2"
                                                                                            wire:model.live="state.son_arrested_id.{{$i}}"
                                                                                            placeholder="رقم هوية الابن {{$i}}"
                                                                                            maxlength="9"
                                                                                            type="text">
                                                                                    </label>
                                                                                    @php
                                                                                        if (isset($state) && isset($state['son_arrested_id']) && isset($state['son_arrested_id'][$i]) && strlen($state['son_arrested_id'][$i]) == 9)
                                                                                            $son_arrested_id_check[$i] = (boolean)\App\Models\Prisoner::query()->where('identification_number' ,$state['son_arrested_id'][$i])->first()
                                                                                    @endphp
                                                                                    @if(isset($state['son_arrested_id'][$i]) && strlen($state['son_arrested_id'][$i]) == 9)
                                                                                        @if(isset($son_arrested_id_check[$i]))
                                                                                            @if($son_arrested_id_check[$i])
                                                                                                <span
                                                                                                    class="text-success">موجود</span>
                                                                                            @else
                                                                                                <span
                                                                                                    class="text-danger">غير موجود (يرجى الإضافة)</span>
                                                                                            @endif
                                                                                        @endif
                                                                                    @endif
                                                                                </div>
                                                                            @endfor
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-md-6 mb-4">
                                                                        <div class="form-group mb-2">
                                                                            <label for="daughter_arrested"
                                                                                   style="display: inline-block;">
                                                                                ابنه
                                                                            </label>
                                                                            <input class="form-control form-control-sm"
                                                                                   wire:model.live="state.daughter_arrested"
                                                                                   type="number"
                                                                                   max="10"
                                                                                   min="0"
                                                                                   id="daughter_arrested"
                                                                                   style="width: 80px; display: inline-block;">
                                                                        </div>
                                                                        @if(isset($state) && isset($state['daughter_arrested']) && $state['daughter_arrested'] > 0)
                                                                            @for($i = 1; $i <= min(10, $state['daughter_arrested']); $i++)
                                                                                <div>
                                                                                    <label>
                                                                                        <input
                                                                                            class="form-input border rounded-2 p-2"
                                                                                            wire:model.live="state.daughter_arrested_id.{{$i}}"
                                                                                            maxlength="9"
                                                                                            placeholder="رقم هوية الابنه {{$i}}"
                                                                                            type="text">
                                                                                    </label>
                                                                                    @php
                                                                                        if (isset($state) && isset($state['daughter_arrested_id']) && isset($state['daughter_arrested_id'][$i]) && strlen($state['daughter_arrested_id'][$i]) == 9)
                                                                                            $daughter_arrested_id_check[$i] = (boolean)\App\Models\Prisoner::query()->where('identification_number' ,$state['daughter_arrested_id'][$i])->first()
                                                                                    @endphp
                                                                                    @if(isset($daughter_arrested_id_check[$i]))
                                                                                        @if(isset($state['daughter_arrested_id'][$i]) && strlen($state['daughter_arrested_id'][$i]) == 9)
                                                                                            @if($daughter_arrested_id_check[$i])
                                                                                                <span
                                                                                                    class="text-success">موجود</span>
                                                                                            @else
                                                                                                <span
                                                                                                    class="text-danger">غير موجود (يرجى الإضافة)</span>
                                                                                            @endif
                                                                                        @endif
                                                                                    @endif
                                                                                </div>
                                                                            @endfor
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-md-12 mb-4 border rounded-2 p-3">
                                <div class="row">
                                    <div class="form-group col-md-3 mb-4">
                                        <label for="first_phone_number">رقم التواصل مع الأهل</label>
                                        <input wire:model="state.first_phone_number" type="number"
                                               class="form-control @error('first_phone_number') is-invalid @enderror"
                                               id="first_phone_number"
                                               maxlength="14"
                                               min="0"
                                               inputmode="numeric"
                                               placeholder="رقم التواصل مع الأهل">
                                        @error('first_phone_number')
                                        <div class="error-message invalid-feedback"
                                             style="font-size: 15px">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-3 mb-4">
                                        <label for="first_phone_owner">اسم صاحب الرقم</label>
                                        <input wire:model="state.first_phone_owner" type="text"
                                               class="form-control @error('first_phone_owner') is-invalid @enderror"
                                               id="first_phone_owner"
                                               placeholder="اسم صاحب الرقم">
                                        @error('first_phone_owner')
                                        <div class="error-message invalid-feedback"
                                             style="font-size: 15px">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-md-12 mb-4">
                                    <div class="row">
                                        <div class="form-group col-md-3 mb-4">
                                            <label for="second_phone_number">رقم التواصل الإضافي</label>
                                            <input wire:model="state.second_phone_number" type="number"
                                                   class="form-control @error('second_phone_number') is-invalid @enderror"
                                                   id="second_phone_number"
                                                   maxlength="14"
                                                   min="0"
                                                   inputmode="numeric"
                                                   placeholder="رقم التواصل الإضافي">
                                            @error('second_phone_number')
                                            <div class="error-message invalid-feedback"
                                                 style="font-size: 15px">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-3 mb-4">
                                            <label for="second_phone_owner">اسم صاحب الرقم</label>
                                            <input wire:model="state.second_phone_owner" type="text"
                                                   class="form-control @error('second_phone_owner') is-invalid @enderror"
                                                   id="second_phone_owner"
                                                   placeholder="اسم صاحب الرقم">
                                            @error('second_phone_owner')
                                            <div class="error-message invalid-feedback"
                                                 style="font-size: 15px">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-12 mb-4">
                                    <div class="row">
                                        <div class="form-group col-md-6 mb-4">
                                            <label for="email">البريد الإلكتروني</label>
                                            <input wire:model="state.email" type="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   id="email"
                                                   placeholder="البريد الإلكتروني">
                                            @error('email')
                                            <div class="error-message invalid-feedback"
                                                 style="font-size: 15px">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @php
                                if (isset($state['first_name'], $state['second_name'], $state['third_name'], $state['last_name'])) {
                                    $full_name = $state['first_name'] . ' ' . $state['second_name'] . ' ' . $state['third_name'] . ' ' . $state['last_name'];
                                }else $full_name = null;

                                if (isset($state['identification_number'])) {
                                    $identification_number = $state['identification_number'];
                                }else $identification_number = null;
                            @endphp

                            <div class="form-group col-md-12 mb-4 border rounded-2 p-3">
                                <div class="row">
                                    {{--                                <div class="form-group col-md-6 mb-4">--}}
                                    {{--                                    <label>المستندات والوثائق</label>--}}
                                    {{--                                    @if(isset($full_name) && isset($identification_number))--}}
                                    {{--                                        <a class="btn btn-link d-block" style="padding:12px 0;"--}}
                                    {{--                                           wire:click="openFileFormModal('{{$full_name}}','{{$identification_number}}')">--}}
                                    {{--                                            اضغط هنا لإرفاق الملفات--}}
                                    {{--                                        </a>--}}
                                    {{--                                    @else--}}
                                    {{--                                        <a class="btn btn-link d-block" style="padding:12px 0;cursor: not-allowed;">--}}
                                    {{--                                            اضغط هنا لإرفاق الملفات--}}
                                    {{--                                        </a>--}}
                                    {{--                                        <span class="text-danger">عليك تعبئة الاسم رباعي و رقم هوية الأسير ليعمل الرابط</span>--}}
                                    {{--                                    @endif--}}
                                    {{--                                </div>--}}
                                    <div class="form-group col-md-6 mb-4">
                                        <label>المستندات والوثائق</label>
                                        @if(isset($full_name) && isset($identification_number))
                                            <a class="btn btn-link d-block" style="padding:12px 0;"
                                               wire:click="openGoogleModal('{{$full_name}}','{{$identification_number}}')">
                                                اضغط هنا لإرفاق الملفات
                                            </a>
                                        @else
                                            <a class="btn btn-link d-block" style="padding:12px 0;cursor: not-allowed;">
                                                اضغط هنا لإرفاق الملفات
                                            </a>
                                            <span
                                                class="text-danger">عليك تعبئة الاسم رباعي و رقم هوية الأسير ليعمل الرابط</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group col-sm-12 mb-4">
                                    <div class="row">
                                        <div class="form-group col-md-6 mb-4">
                                            <label for="is-released">مفرج عنه حاليا؟</label>
                                            <select id="is-released" class="form-select"
                                                    wire:model.live="state.is_released">
                                                <option>اختر...</option>
                                                <option value="1">نعم, مفرج عنه</option>
                                                <option value="0">لا, في السجن حالياً</option>
                                            </select>
                                            @error('is_released')
                                            <div class="error-message invalid-feedback"
                                                 style="font-size: 15px">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-6 mb-4">
                                    <label for="notes">ملاحظات</label>
                                    <textarea id="notes" rows="3"
                                              class="form-control @error('notes') is-invalid @enderror"
                                              placeholder="اكتب ملاحظاتك..."
                                              wire:model="state.notes"></textarea>
                                    @error('notes')
                                    <div class="error-message invalid-feedback"
                                         style="font-size: 15px">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3 text-center">
                            <hr>
                            <h1>اعتقالات سابقة (إن وجد)</h1>
                            <hr>
                        </div>
                        @foreach($old_arrests as $key => $old_arrest)
                            <div class="col-md-12">
                                <div class="accordion-item col-md-6 mx-auto mb-4 p-0" wire:key="{{$key}}_"
                                     wire:ignore.self>
                                    <h2 class="accordion-header"
                                        style="background-color:rgba(227,227,227,0.52);margin-bottom: 6px;padding: 10px;border-radius: 3px"
                                        id="panelsStayOpen-heading_{{$key}}">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#panelsStayOpen-collapse_{{$key}}"
                                                aria-expanded="true"
                                                aria-controls="panelsStayOpen-collapse_{{$key}}">
                                            اعتقال سابق {{$key + 1}}
                                            <a class="btn btn-danger mx-4"
                                               wire:click="removeOldArrest({{ $key }})">إزالة</a>
                                        </button>
                                    </h2>
                                    <div id="panelsStayOpen-collapse_{{$key}}"
                                         class="accordion-collapse collapse show"
                                         wire:ignore.self
                                         aria-labelledby="panelsStayOpen-heading_{{$key}}">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="form-group col-md-4 mb-4">
                                                    <label style="font-weight: bold" for="old_arrest_start_date">بداية
                                                        الاعتقال</label>
                                                    <input wire:model="old_arrests.{{$key}}.old_arrest_start_date"
                                                           type="text"
                                                           class="form-control"

                                                           placeholder="سنة - شهر - يوم"
                                                           oninput="formatDate(this)">
                                                    @if(isset($old_errors) && isset($old_errors[$key.".old_arrest_start_date"]))
                                                        <h6 class="text-danger error-message">{{ $old_errors[$key.".old_arrest_start_date"][0] }}</h6>
                                                    @endif
                                                </div>
                                                <div class="form-group col-md-4 mb-4">
                                                    <label style="font-weight: bold" for="old_arrest_end_date">نهاية
                                                        الاعتقال</label>
                                                    <input wire:model="old_arrests.{{$key}}.old_arrest_end_date"
                                                           type="text"
                                                           class="form-control"
                                                           placeholder="سنة - شهر - يوم"
                                                           oninput="formatDate(this)">


                                                    @if(isset($old_errors) && isset($old_errors[$key.".old_arrest_end_date"]))
                                                        <h6 class="text-danger error-message">{{ $old_errors[$key.".old_arrest_end_date"][0] }}</h6>
                                                    @endif
                                                </div>
                                                <div class="form-group col-md-4 mb-4">
                                                    <label style="font-weight: bold" for="arrested_side">جهة
                                                        الاعتقال</label>
                                                    <select id="arrested_side"
                                                            wire:model="old_arrests.{{$key}}.arrested_side"
                                                            class="form-select">
                                                        <option>اختر...</option>
                                                        @foreach(\App\Enums\ArrestedSide::cases() as $row)
                                                            <option value="{{$row->value}}">{{$row->value}}</option>
                                                        @endforeach
                                                    </select>
                                                    @if(isset($old_errors) && isset($old_errors[$key.".arrested_side"]))
                                                        <h6 class="text-danger error-message">{{ $old_errors[$key.".arrested_side"][0] }}</h6>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="col-md-12">
                            <div class="col-md-6 text-center mx-auto">
                                <a class="btn btn-primary mx-auto" wire:click="addOldArrest">إضافة اعتقال سابق
                                    آخر</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-start align-items-start">
                    <button type="submit"
                            wire:click="{{$showEditModel ? 'UpdateAcceptAdmin' : 'CreateAcceptAdmin'}}"
                            class="btn {{$showEditModel ? 'bg-warning' : 'bg-primary'}}">
                        حفظ
                    </button>
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

        function formatDate(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length > 4) {
                value = value.substring(0, 2) + '-' + value.substring(2, 4) + '-' + value.substring(4, 8);
            } else if (value.length > 2) {
                value = value.substring(0, 2) + '-' + value.substring(2, 4);
            }
            input.value = value;
        }

        window.addEventListener('showNumberConverter', event => {
            $('#showNumberConverter').modal('show');
        })

        window.addEventListener('show_create_update_modal', event => {
            $('#CreateUpdate').modal('show');
        })
        window.addEventListener('hide_create_update_modal', event => {
            $('#CreateUpdate').modal('hide');
            toastr.success(event.detail.message, 'تهانينا!');
        })
        document.addEventListener('DOMContentLoaded', function () {
            document.body.addEventListener('show_admin_update_massage', function () {
                Swal.fire({
                    title: 'نجاح',
                    text: 'تم تعديل الاقتراح عليك مراجعته',
                    icon: 'success',
                    confirmButtonText: 'تم',
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            document.body.addEventListener('show_admin_create_massage', function () {
                Swal.fire({
                    title: 'نجاح',
                    text: 'تم الارسال الى الاقتراحات المؤكدة',
                    icon: 'success',
                    confirmButtonText: 'تم',
                });
            });
        });

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

        window.addEventListener('open_fileForm_modal', event => {
            $('#fileFormModal').modal('show');
        })

        window.addEventListener('open_google_modal', event => {
            $('#googleForm').modal('show');
        })

        window.addEventListener('open_go_to_index_modal', event => {
            $('#goToIndex').modal('show');
        })

        document.body.addEventListener('scroll-to-top', function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });


        Livewire.hook('commit', ({succeed}) => {
            succeed(() => {
                setTimeout(() => {
                    const firstErrorMessage = document.querySelector('.error-message')

                    if (firstErrorMessage !== null) {
                        firstErrorMessage.scrollIntoView({block: 'center', inline: 'center'})
                    }
                }, 0)
            })
        })

    </script>
@endsection
