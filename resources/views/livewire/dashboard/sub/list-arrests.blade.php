@section('title')
    بوابة الحرية | قائمة الإعتقاللات
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
    </style>
@endsection
<div class="p-4">
    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">لوحة التحكم</a></li>
                <li class="breadcrumb-item active" aria-current="page">قائمة الإعتقاللات</li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between flex-wrap mt-2">
                @auth
                    @if($prisoner_id)
                        <div>
                            <a class="btn btn-primary mb-2" wire:click="addNew"
                               target="_blank">
                                إضافة إعتقال
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
                        </div>
                    @endif
                @endauth
                <div>
                    <input wire:model.live="Search" type="search" id="Search"
                           placeholder="البحث في قائمة الإعتقاللات...">
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
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-center">
                    <thead>
                    <tr style="font-size: 16px">
                        <th>#</th>
                        <th style="min-width: 180px">اسم الأسير</th>
                        <th style="min-width: 180px">بداية الإعتقال</th>
{{--                        <th style="min-width: 180px">نهاية الإعتقال</th>--}}
                        <th style="min-width: 180px">نوع الإعتقال</th>
                        <th style="min-width: 180px">الحكم</th>
                        <th style="min-width: 180px">الإنتماء</th>
                        <th style="min-width: 180px">الحالة الصحية</th>
                        <th style="min-width: 180px">الحالة الإجتماعية</th>
                        <th style="min-width: 180px">عدد الزوجات</th>
                        <th style="min-width: 180px">عدد الأبناء</th>
                        <th style="min-width: 230px">رقم التواصل (واتس/تلجرام)</th>
                        <th style="min-width: 230px">رقم التواصل الإضافي</th>
{{--                        <th>الإفراج</th>--}}
{{--                        <th style="min-width: 180px">الملاحظات</th>--}}
                        @auth
                            <th>الخيارات</th>
                        @endauth

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($Arrests as $key => $row)
                        <tr>
                            <td>{{$Arrests->firstItem() + $key}}</td>
                            <td>{{$row->Prisoner->full_name ?? 'لا يوجد'}}</td>
                            <td>{{$row->arrest_start_date ?? 'لا يوجد'}}</td>
{{--                            <td>{{$row->arrest_end_date ?? 'لا يوجد'}}</td>--}}
                            <td>{{$row->arrest_type ?? 'لا يوجد'}}</td>
                            <td>
                                @if(isset($row->arrest_type) && $row->arrest_type == "محكوم")
                                    {{$row->judgment ?? 'لا يوجد'}}
                                @else
                                    لا يوجد
                                @endif
                            </td>
                            <td>{{$row->Belong->belong_name ?? 'لا يوجد'}}</td>
                            <td>{{$row->Health->health_name ?? 'لا يوجد'}}</td>
                            <td>{{$row->social_type ?? 'لا يوجد'}}</td>
                            <td>{{$row->wife_type ?? 'لا يوجد'}}</td>
                            <td>{{$row->number_of_children ?? 'لا يوجد'}}</td>
                            <td>{{$row->first_phone_number ?? 'لا يوجد'}}</td>
                            <td>{{$row->second_phone_number ?? 'لا يوجد'}}</td>
{{--                            <td>{{$row->isReleased ? 'نعم' : 'لا'}}</td>--}}
{{--                            <td>{{$row->notes ?? 'لا يوجد'}}</td>--}}
                            @auth
                                <td>
                                    <a wire:click="edit({{$row}})" class="btn btn-warning"
                                       data-toggle="tooltip" data-placement="top" title="Edit">
                                        تعديل
                                    </a>
                                    @if(\Illuminate\Support\Facades\Auth::user()->user_status === "مسؤول")
                                        <a wire:click="delete({{$row}})" class="btn btn-danger"
                                           title="Delete">
                                            حذف
                                        </a>
                                    @endif

                                </td>
                            @endauth

                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{$Arrests->links()}}
                </div>
            </div>
        </div>
    </div>

    <!-- Form Modal -->
    <div class="modal modal-lg fade" id="form" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content bg-white">
                <div class="modal-header {{$showEdit ? 'bg-warning': 'bg-primary'}}" style="margin: 5px;">
                    <h1 class="modal-title fs-5 text-white"
                        id="staticBackdropLabel">{{$showEdit ? 'تعديل الإعتقال':'إضافة إعتقال'}}</h1>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="{{$showEdit ? 'updateArrest':'createArrest'}}">
                        <div class="row">
                            <div class="form-group col-md-12 mb-4">
                                <label for="arrest_start_date">بداية الإعتقال</label>
                                <input wire:model="state.arrest_start_date" type="date"
                                       class="form-control @error('arrest_start_date') is-invalid @enderror"
                                       id="arrest_start_date">
                                @error('arrest_start_date')
                                <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                @enderror
                            </div>
{{--                            <div class="form-group col-md-12 mb-4">--}}
{{--                                <label for="arrest_end_date">نهاية الإعتقال</label>--}}
{{--                                <input wire:model="state.arrest_end_date" type="date"--}}
{{--                                       class="form-control @error('arrest_end_date') is-invalid @enderror"--}}
{{--                                       id="arrest_end_date">--}}
{{--                                @error('arrest_end_date')--}}
{{--                                <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
                            <div class="form-group col-md-12 mb-4">
                                <label for="arrest_type">نوع الإعتقال</label>
                                <select wire:model.live="state.arrest_type"
                                        class="form-select @error('arrest_type') is-invalid @enderror"
                                        id="arrest_type">
                                    <option>إختر...</option>
                                    @foreach(\App\Enums\ArrestType::cases() as $row)
                                        <option value="{{$row->value}}">{{$row->value}}</option>
                                    @endforeach
                                </select>
                                @error('arrest_type')
                                <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                @enderror
                            </div>
                            @if(isset($state['arrest_type']) && $state['arrest_type'] == "محكوم")
                                <div class="form-group col-md-4 mb-4">
                                    <label for="judgment_in_lifetime">الحكم بالمؤبدات</label>
                                    <input wire:model="state.judgment_in_lifetime" type="number"
                                           class="form-control @error('judgment_in_lifetime') is-invalid @enderror"
                                           placeholder="أرقام (اختياري)"
                                           id="judgment_in_lifetime">
                                    @error('judgment_in_lifetime')
                                    <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4 mb-4">
                                    <label for="judgment_in_years">الحكم بالسنوات</label>
                                    <input wire:model="state.judgment_in_years" type="number"
                                           class="form-control @error('judgment_in_years') is-invalid @enderror"
                                           placeholder="أرقام (اختياري)"
                                           id="judgment_in_years">
                                    @error('judgment_in_years')
                                    <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4 mb-4">
                                    <label for="judgment_in_months">الحكم بالأشهر</label>
                                    <input wire:model="state.judgment_in_months" type="number"
                                           class="form-control @error('judgment_in_months') is-invalid @enderror"
                                           placeholder="أرقام (اختياري)"
                                           id="judgment_in_months">
                                    @error('judgment_in_months')
                                    <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                            <div class="form-group col-md-12 mb-4">
                                <label for="belong_id">الفصيل</label>
                                <select wire:model.live="state.belong_id"
                                        class="form-select @error('belong_id') is-invalid @enderror"
                                        id="belong_id">
                                    <option>إختر...</option>
                                    @foreach($Belongs as $row)
                                        <option value="{{$row->id}}">{{$row->belong_name}}</option>
                                    @endforeach
                                </select>
                                @error('belong_id')
                                <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12 mb-4">
                                <label for="health_id">الحالة الصحية</label>
                                <select wire:model.live="state.health_id"
                                        class="form-select @error('health_id') is-invalid @enderror"
                                        id="health_id">
                                    <option>إختر...</option>
                                    @foreach($Healths as $row)
                                        <option value="{{$row->id}}">{{$row->health_name}}</option>
                                    @endforeach
                                </select>
                                @error('health_id')
                                <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12 mb-4">
                                <label for="social_type">الحالة الإجتماعية</label>
                                <select wire:model.live="state.social_type"
                                        class="form-select @error('social_type') is-invalid @enderror"
                                        id="social_type">
                                    <option>إختر...</option>
                                    @foreach(\App\Enums\SocialType::cases() as $row)
                                        <option value="{{$row->value}}">{{$row->value}}</option>
                                    @endforeach
                                </select>
                                @error('social_type')
                                <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                @enderror
                            </div>
                            @if(isset($state['social_type']) && !in_array($state['social_type'],['أعزب','إختر...']))
                                <div class="form-group col-md-12 mb-4">
                                    <label for="number_of_children">عدد الأبناء</label>
                                    <input wire:model="state.number_of_children" type="text"
                                           class="form-control @error('number_of_children') is-invalid @enderror"
                                           id="number_of_children"
                                           placeholder="عدد الأبناء">
                                    @error('number_of_children')
                                    <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                    @enderror
                                </div>
                                @if($state['social_type'] !== "مطلق")
                                    <div class="form-group col-md-12 mb-4">
                                        <label for="wife_type" class="d-block">عدد الزوجات</label>
                                        <div class="bg-white p-2">
                                            @foreach(\App\Enums\WifeType::cases() as $row)
                                                <div class="form-check form-check-primary form-check-inline">
                                                    <input class="form-check-input" type="radio" name="wife_type"
                                                           wire:model="state.wife_type"
                                                           value="{{$row->value}}" id="wife_type">
                                                    <label class="form-check-label" for="wife_type">
                                                        {{$row->value}}
                                                    </label>
                                                </div>
                                            @endforeach
                                            @error('wife_type')
                                            <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
                            @endif
                            <div class="form-group col-md-6 mb-4">
                                <label for="first_phone_number">رقم التواصل (واتس/تلجرام)</label>
                                <input wire:model="state.first_phone_number" type="text"
                                       class="form-control @error('first_phone_number') is-invalid @enderror"
                                       id="first_phone_number"
                                       placeholder="رقم التواصل مع الأهل">
                                @error('first_phone_number')
                                <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label for="second_phone_number">رقم التواصل الإضافي</label>
                                <input wire:model="state.second_phone_number" type="text"
                                       class="form-control @error('second_phone_number') is-invalid @enderror"
                                       id="second_phone_number"
                                       placeholder="رقم التواصل مع الأهل">
                                @error('second_phone_number')
                                <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                @enderror
                            </div>
{{--                            <div class="form-group col-md-12 mb-4">--}}
{{--                                <label for="notes">ملاحظات</label>--}}
{{--                                <textarea id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror"--}}
{{--                                          placeholder="إكتب الملاحظات..."--}}
{{--                                          wire:model="state.notes"></textarea>--}}
{{--                                @error('notes')--}}
{{--                                <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                            <div class="form-group col-md-12 mb-4">--}}
{{--                                <label for="isReleased" class="d-block">تم الإفراج</label>--}}
{{--                                <div class="bg-white p-2">--}}
{{--                                    @foreach(\App\Enums\DefaultEnum::cases() as $row)--}}
{{--                                        <div class="form-check form-check-primary form-check-inline">--}}
{{--                                            <input class="form-check-input" type="radio" name="isReleased"--}}
{{--                                                   wire:model="state.isReleased"--}}
{{--                                                   value="{{$row->value}}" id="isReleased">--}}
{{--                                            <label class="form-check-label" for="isReleased">--}}
{{--                                                {{$row->value}}--}}
{{--                                            </label>--}}
{{--                                        </div>--}}
{{--                                        @error('isReleased')--}}
{{--                                        <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>--}}
{{--                                        @enderror--}}
{{--                                    @endforeach--}}
{{--                                </div>--}}
{{--                            </div>--}}
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-start align-items-start">
                    <button type="submit" wire:click="{{$showEdit ? 'updateArrest':'createArrest'}}"
                            class="btn {{$showEdit ? 'btn-warning' : 'btn-primary'}}">حفظ
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="feather feather-save">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
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
                        id="staticBackdropLabel">حذف الإعتقال</h1>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="text-danger m-3">هل أنت متأكد انك تريد حذف الإعتقال؟</h5>
                    <span class="text-danger m-3">
                        * تنبية:
                        <span class="text-dark">سيتم حذف الإعتقال</span>
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
                        text: 'تم حذف بيانات الإعتقال',
                        icon: 'success',
                        confirmButtonText: 'تم'
                    }
                );

            });
        });

        window.addEventListener('showForm', event => {
            $('#form').modal('show');
        })
        window.addEventListener('hideForm', event => {
            $('#form').modal('hide');
        })
        document.addEventListener('DOMContentLoaded', function () {
            document.body.addEventListener('hideForm', function () {
                Swal.fire(
                    {
                        title: 'نجاح',
                        text: 'تم حفظ بيانات الإعتقال',
                        icon: 'success',
                        confirmButtonText: 'تم'
                    }
                );

            });
        });

    </script>
@endsection
