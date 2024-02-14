@section('title')
    فجر الحرية | قائمة المحافظات
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
                <li class="breadcrumb-item active" aria-current="page">قائمة المحافظات</li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between flex-wrap mt-2">
                <div>
                    <a class="btn btn-primary mb-2" wire:click="addNew"
                       target="_blank">
                        إضافة محافظة
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-plus-circle">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                    </a>
                </div>
                <div>
                    <input wire:model.live="Search" class="form-input m-2" type="search" id="Search"
                           placeholder="البحث...">

                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-center">
                    <thead>
                    <tr style="font-size: 16px">
                        <th>#</th>
                        <th>اسم المحافظة</th>
                        <th>البلدات</th>
                        <th>الخيارات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($Cities as $key => $row)
                        <tr>
                            <td>{{$Cities->firstItem() + $key}}</td>
                            <td>{{$row->city_name ?? 'لا يوجد'}}</td>
                            <td>
                                @if(count($row->Town) > 0)
                                    <a wire:click="showTowns({{$row->id}})" class="btn btn-link">
                                        عرض {{count($row->Town)}}
                                    </a>
                                @else
                                    لا يوجد
                                @endif
                            </td>
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
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{$Cities->links()}}
                </div>
            </div>
        </div>
    </div>

    <!-- Form Modal -->
    <div class="modal modal fade" id="form" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content bg-white">
                <div class="modal-header {{$showEdit ? 'bg-warning': 'bg-primary'}}" style="margin: 5px;">
                    <h1 class="modal-title fs-5 text-white"
                        id="staticBackdropLabel">{{$showEdit ? 'تعديل المحافظة':'إضافة محافظة'}}</h1>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="{{$showEdit ? 'updateCity':'createCity'}}">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="city_name">اسم المحافظة</label>
                                <input wire:model="state.city_name" type="text"
                                       class="form-control @error('city_name') is-invalid @enderror"
                                       id="city_name"
                                       placeholder="اسم المحافظة">
                                @error('city_name')
                                <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-start align-items-start">
                    <button type="submit" wire:click="{{$showEdit ? 'updateCity':'createCity'}}"
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
    <!-- showTowns Modal -->
    @if(!empty($Towns))
        <div class="modal modal-lg fade" id="towns" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog">
                <div class="modal-content bg-white">
                    <div class="modal-header bg-info" style="margin: 5px;">
                        <h1 class="modal-title fs-5 text-white"
                            id="staticBackdropLabel">عرض البلدات</h1>
                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input class="form-control mb-3" placeholder="بحث عن بلدة..." type="text"
                                   wire:model.live="TownSearch">
                            @if($showEditTown)
                                <div class="col-md-12 my-4">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input class="form-control @error('newTownName') is-invalid @enderror"
                                                   placeholder="تعديل اسم البلدة"
                                                   type="text" wire:model.live="newTownName">
                                            @error('newTownName')
                                            <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-2 p-1">
                                            <a wire:click="updateTown"
                                               wire:confirm="هل أنت متأكد انك تريد تعديل اسم البلدة؟"
                                               class="btn btn-success">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                     height="16"
                                                     fill="currentColor" class="bi bi-check-circle"
                                                     viewBox="0 0 16 16">
                                                    <path
                                                        d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                                    <path
                                                        d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @foreach($Towns as $key => $town)
                                <div class="col-md-6 mb-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="text-info d-inline">
                                                {{$town->town_name}}
                                            </h5>
                                        </div>
                                        <div class="col-md-6">
                                            <a wire:click="editTown('{{$town->id}}')"
                                               class="btn btn-warning">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                     fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                                                    <path
                                                        d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001m-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708z"/>
                                                </svg>
                                            </a>
                                            <a wire:click="deleteTown('{{$town->id}}','{{$town->city_id}}')"
                                               wire:confirm="هل أنت متأكد أنك تريد حذف البلدة؟" class="btn btn-danger">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                     fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                                    <path
                                                        d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-start align-items-start">
                        <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">إلغاء</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Delete Modal -->
    <div class="modal modal fade" id="delete" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content bg-white">
                <div class="modal-header bg-danger" style="margin: 5px;">
                    <h1 class="modal-title fs-5 text-white"
                        id="staticBackdropLabel">حذف المحافظة</h1>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="text-danger m-3">هل أنت متأكد انك تريد حذف المحافظة؟</h5>
                    <span class="text-danger m-3">
                        * تنبيه:
                        <span class="text-dark">
                            سيتم حذف {{$Cities_->city_name ?? null}}
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
        window.addEventListener('showTowns', event => {
            $('#towns').modal('show');
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
                        text: 'تم حذف بيانات المحافظة',
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
                        text: 'تم حفظ بيانات المحافظة',
                        icon: 'success',
                        confirmButtonText: 'تم'
                    }
                );

            });
        });

    </script>
@endsection
