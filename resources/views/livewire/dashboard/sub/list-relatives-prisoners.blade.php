@section('title')
    فجر الحرية | قائمة الأقارب المعتقلون
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
                <li class="breadcrumb-item active" aria-current="page">قائمة الأقارب المعتقلون</li>
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
                                إضافة قريب
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
                    <input wire:model.live="Search" class="form-input m-2" type="search" id="Search" placeholder="البحث...">

                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-center">
                    <thead>
                    <tr style="font-size: 16px">
                        <th>#</th>
                        <th style="min-width: 180px">اسم الأسير</th>
                        <th style="min-width: 180px">اسم القريب</th>
                        <th style="min-width: 180px">رقم الهوية</th>
                        <th style="min-width: 180px">صلة القرابة</th>
                        @auth
                            <th>الخيارات</th>
                        @endauth

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($RelativesPrisoners as $key => $row)
                        <tr>
                            <td>{{$RelativesPrisoners->firstItem() + $key}}</td>
                            <td>{{$row->Prisoner->full_name ?? 'لا يوجد'}}</td>
                            <td>{{$row->full_name ?? 'لا يوجد'}}</td>
                            <td>{{$row->identification_number ?? 'لا يوجد'}}</td>
                            <td>{{$row->Relationship->relationship_name ?? 'لا يوجد'}}</td>
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
                    {{$RelativesPrisoners->links()}}
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
                        id="staticBackdropLabel">{{$showEdit ? 'تعديل القريب المعتقل':'إضافة قريب معتقل'}}</h1>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="{{$showEdit ? 'updateRelativesPrisoner':'createRelativesPrisoner'}}">
                        <div class="row">
                            <div class="form-group col-md-6 mb-4">
                                <label for="first_name">الاسم الأول</label>
                                <input wire:model="state.first_name" type="text"
                                       class="form-control @error('first_name') is-invalid @enderror"
                                       id="first_name"
                                       placeholder="الاسم الأول">
                                @error('first_name')
                                <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label for="second_name">اسم الأب</label>
                                <input wire:model="state.second_name" type="text"
                                       class="form-control @error('second_name') is-invalid @enderror"
                                       id="second_name"
                                       placeholder="الاسم الأب">
                                @error('second_name')
                                <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label for="third_name">اسم الجد</label>
                                <input wire:model="state.third_name" type="text"
                                       class="form-control @error('third_name') is-invalid @enderror"
                                       id="third_name"
                                       placeholder="الاسم الجد">
                                @error('third_name')
                                <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label for="last_name">اسم العائلة</label>
                                <input wire:model="state.last_name" type="text"
                                       class="form-control @error('last_name') is-invalid @enderror"
                                       id="last_name"
                                       placeholder="اسم العائلة">
                                @error('last_name')
                                <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label for="identification_number">رقم الهوية</label>
                                <input wire:model="state.identification_number" type="text"
                                       class="form-control @error('identification_number') is-invalid @enderror"
                                       id="identification_number"
                                       placeholder="رقم الهوية">
                                @error('identification_number')
                                <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 mb-4">
                                <label for="relationship_id">صلة القرابة</label>
                                <select wire:model.live="state.relationship_id"
                                        class="form-select @error('relationship_id') is-invalid @enderror"
                                        id="relationship_id">
                                    <option>اختر...</option>
                                    @foreach($Relationships as $relationship)
                                        <option value="{{$relationship->id}}">{{$relationship->relationship_name}}</option>
                                    @endforeach
                                </select>
                                @error('relationship_id')
                                <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-start align-items-start">
                    <button type="submit"
                            wire:click="{{$showEdit ? 'updateRelativesPrisoner':'createRelativesPrisoner'}}"
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
                        id="staticBackdropLabel">حذف القريب المعتقل</h1>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="text-danger m-3">هل أنت متأكد انك تريد حذف القريب المعتقل؟</h5>
                    <span class="text-danger m-3">
                        * تنبيه:
                        <span class="text-dark">سيتم حذف القريب المعتقل</span>
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
                        text: 'تم حذف بيانات القريب المعتقل',
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
                        text: 'تم حفظ بيانات القريب المعتقل',
                        icon: 'success',
                        confirmButtonText: 'تم'
                    }
                );

            });
        });

    </script>
@endsection
