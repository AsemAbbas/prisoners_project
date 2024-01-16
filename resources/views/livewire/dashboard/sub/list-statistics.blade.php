@section('title')
    فجر الحرية | قائمة الإحصائيات
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
                <li class="breadcrumb-item active" aria-current="page">قائمة الإحصائيات</li>
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
                        إضافة إحصائية
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
                    <input wire:model.live="Search" type="search" id="Search"
                           placeholder="البحث في قائمة الإحصائيات...">
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
                        <th>نوع الإحصائية</th>
                        <th>مجموع الإحصائية</th>
                        <th>ترتيب الإحصائية</th>
                        <th>صورة الإحصائية</th>
                        <th>الخيارات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($Statistics as $key => $row)
                        <tr>
                            <td>{{$Statistics->firstItem() + $key}}</td>
                            <td>{{$row->statistic_type ?? 'لا يوجد'}}</td>
                            <td>{{$row->statistic_number ?? 'لا يوجد'}}</td>
                            <td>
                                <a @if(isset($statistics_id) && isset($statistics_key) && $statistics_key === $key) hidden
                                   @endif
                                   wire:click="StatisticOrderBy({{$row->id}},{{$key}})"
                                   class="btn btn-link">{{$row->order_by ?? 'لا يوجد'}}</a>
                                @if(isset($statistics_id))
                                    @if(isset($statistics_key) && $statistics_key === $key)
                                        <label>
                                            <input style="width: 60px;" class="form-control m-0 p-2" type="number"
                                                   wire:model="order_by" wire:keydown.enter="ChangeOrderBy">
                                        </label>
                                        <br>
                                        اضغط Enter
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                             style="margin-top: 4px;"
                                             fill="currentColor" class="bi bi-arrow-return-left text-black" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                  d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5"/>
                                        </svg>
                                        للتأكيد
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if(!empty($row->statistic_photo))
                                    <img src="{{asset('storage/statistic_photo/'.$row->statistic_photo) ?? null}}"
                                         alt="news_photo" class="border border-dark rounded rounded-1" width="100">
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
                    {{$Statistics->links()}}
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
                        id="staticBackdropLabel">{{$showEdit ? 'تعديل الإحصائية':'إضافة إحصائية'}}</h1>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="{{$showEdit ? 'updateStatistic':'createStatistic'}}">
                        <div class="row">
                            <div class="form-group col-md-12 mb-3">
                                <label for="statistic_name">نوع الإحصائية</label>
                                <input wire:model="state.statistic_type" type="text"
                                       class="form-control @error('statistic_type') is-invalid @enderror"
                                       id="statistic_type"
                                       placeholder="نوع الإحصائية">
                                @error('statistic_type')
                                <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12 mb-3">
                                <label for="statistic_number">مجموع الإحصائية</label>
                                <input wire:model="state.statistic_number" type="text"
                                       class="form-control @error('statistic_number') is-invalid @enderror"
                                       id="statistic_number"
                                       placeholder="مجموع الإحصائية">
                                @error('statistic_number')
                                <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12 mb-3">
                                <label for="statistic_photo">صورة الإحصائية</label>
                                <input wire:model.live="state.statistic_photo" type="file"
                                       class="form-control @error('statistic_photo') is-invalid @enderror"
                                       id="statistic_photo"
                                       placeholder="صورة الإحصائية">

                                @if(isset($state['statistic_photo']))
                                    @if(gettype($state['statistic_photo']) !== "string")
                                        <img src="{{ $state['statistic_photo']->temporaryUrl() }}" alt="Preview"
                                             style="max-width: 300px;"/>
                                    @else
                                        <img src="{{ asset('storage/statistic_photo/'.$state['statistic_photo']) }}"
                                             alt="Preview"
                                             style="max-width: 300px;"/>
                                    @endif
                                @endif

                                @error('statistic_photo')
                                <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-start align-items-start">
                    <button type="submit" wire:click="{{$showEdit ? 'updateStatistic':'createStatistic'}}"
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
                        id="staticBackdropLabel">حذف الإحصائية</h1>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="text-danger m-3">هل أنت متأكد انك تريد حذف الإحصائية؟</h5>
                    <span class="text-danger m-3">
                        * تنبية:
                        <span class="text-dark">
                            سيتم حذف {{$Statistics_->statistic_type ?? null}}
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
                        text: 'تم حذف بيانات الإحصائية',
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
                        text: 'تم حفظ بيانات الإحصائية',
                        icon: 'success',
                        confirmButtonText: 'تم'
                    }
                );

            });
        });

    </script>
@endsection
