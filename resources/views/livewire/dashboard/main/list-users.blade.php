@section('title')
    فجر الحرية | قائمة المستخدمين
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
                <li class="breadcrumb-item active" aria-current="page">قائمة المستخدمين</li>
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
                        إضافة مستخدم
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
                           placeholder="البحث في قائمة المستخدمين...">
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
                        <th>اسم المستخدم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الحالة</th>
                        @if(\Illuminate\Support\Facades\Auth::user()->user_status === "مسؤول")
                            <th>الخيارات</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($Users as $key => $row)
                        <tr>
                            <td>{{$Users->firstItem() + $key}}</td>
                            <td>{{$row->name ?? 'لا يوجد'}}</td>
                            <td>{{$row->email ?? 'لا يوجد'}}</td>
                            <td>
                                <label for="user_status" style="width: 200px;">
                                    <select class="form-select" id="user_status"
                                            name="user_status"
                                            wire:change="ShowUserStatus($event.target.value,{{$row}})">
                                        <option value="مسؤول" @if($row->user_status === "مسؤول") selected @endif>مسؤول
                                        </option>
                                        <option value="مدخل بيانات"
                                                @if($row->user_status === "مدخل بيانات") selected @endif>مدخل بيانات
                                        </option>
                                        <option value="مراجع بيانات"
                                                @if($row->user_status === "مراجع بيانات") selected @endif>مراجع بيانات
                                        </option>
                                    </select>
                                </label>
                            </td>
                            <td>
                                @if(\Illuminate\Support\Facades\Auth::user()->user_status === "مسؤول")
                                    <a wire:click="edit({{$row}})" class="btn btn-warning"
                                       title="Edit">
                                        تعديل
                                    </a>
                                @endif
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
                    {{$Users->links()}}
                </div>
            </div>
        </div>
    </div>

    <!-- Form Modal -->
    <div class="modal modal-lg fade" id="form" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content bg-white">
                <div class="modal-header {{$ShowModal ? 'bg-warning' : 'bg-primary'}}" style="margin: 5px;">
                    <h1 class="modal-title fs-5 text-white"
                        id="staticBackdropLabel">إضافة مستخدم</h1>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="{{$ShowModal ? 'updateUser' : 'createUser'}}">
                        <div class="row">
                            <div class="form-group col-md-12 mb-3">
                                <label for="name">اسم المستخدم</label>
                                <input wire:model="state.name" type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       placeholder="اسم المستخدم">
                                @error('name')
                                <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group col-md-12 mb-3">
                                <label for="user_status">حالة المستخدم</label>
                                <select class="form-select" id="user_status"
                                        name="user_status"
                                        wire:model.live="state.user_status">
                                    <option>اختر...</option>
                                    @foreach(\App\Enums\UserStatus::cases() as $status)
                                        <option value="{{$status->value}}">{{$status->value}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-12 mb-3">
                                <label for="City">المحافظات</label>
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
                                        @php $city = $state['cities'] ?? [] @endphp
                                        <div id="defaultCity" class="collapse @if(isset($city)) show @endif"
                                             aria-labelledby="headingCity"

                                             wire:ignore.self
                                             data-bs-parent="#toggleCity">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6 mb-4">
                                                        <div class="form-check form-check-dark form-check-inline">
                                                            <input class="form-check-input"
                                                                   wire:model.live="AllCities"
                                                                   type="checkbox"
                                                                   id="form-check-dark">
                                                            <label class="form-check-label" for="form-check-dark">
                                                                جميع المحافظات
                                                            </label>
                                                        </div>
                                                    </div>
                                                    @foreach($Cities as $city)
                                                        <div class="col-md-6 mb-4">
                                                            <div class="form-check form-check-dark form-check-inline">
                                                                <input class="form-check-input"
                                                                       wire:model.live="state.cities.{{$city->id}}"
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

                            <div class="form-group col-md-12 mb-3">
                                <label for="email">البريد الإلكتروني</label>
                                <input wire:model="state.email" type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email">
                                @error('email')
                                <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-12 mb-3">
                                <label for="password">كلمة المرور</label>
                                <input wire:model="state.password" type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       id="password">
                                @error('password')
                                <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer d-flex justify-content-start align-items-start">
                    <button type="submit" wire:click="{{$ShowModal ? 'updateUser' : 'createUser'}}"
                            class="btn {{$ShowModal ? 'bg-warning' : 'bg-primary'}}">حفظ
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

    <!-- UserStatus Modal -->
    <div class="modal modal fade" id="UserStatus" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content bg-white">
                <div class="modal-header bg-warning" style="margin: 5px;">
                    <h1 class="modal-title fs-5 text-white"
                        id="staticBackdropLabel">تغير حالة المستخدم</h1>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="text-warning m-3">هل أنت متأكد انك تريد تغير حالة المستخدم؟</h5>
                    <span class="text-warning m-3">
                        * تنبية:
                        <span class="text-dark">
                            سيتم تغير حاله {{$UserStatus->name ?? null}}
                            إلى {{$user_status ?? null}}
                        </span>
                    </span>
                </div>
                <div class="modal-footer d-flex justify-content-start align-items-start">
                    <button type="submit" wire:click="changeUserStatus" class="btn btn-warning">
                        تأكيد
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
                        id="staticBackdropLabel">حذف المستخدم</h1>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="text-danger m-3">هل أنت متأكد انك تريد حذف المستخدم؟</h5>
                    <span class="text-danger m-3">
                        * تنبية:
                        <span class="text-dark">
                            سيتم حذف {{$Users_->name ?? null}}
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
        window.addEventListener('UserStatus', event => {
            $('#UserStatus').modal('show');
        })
        window.addEventListener('hideUserStatus', event => {
            $('#UserStatus').modal('hide');
            toastr.success(event.detail.message, 'تهانينا!');
        })
        document.addEventListener('DOMContentLoaded', function () {
            document.body.addEventListener('hideUserStatus', function () {
                Swal.fire(
                    {
                        title: 'نجاح',
                        text: 'تم تعديل حالة المستخدم',
                        icon: 'success',
                        confirmButtonText: 'تم'
                    })

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
                        text: 'تم حذف بيانات المستخدم',
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
                        text: 'تم حفظ بيانات المستخدم',
                        icon: 'success',
                        confirmButtonText: 'تم'
                    }
                );

            });
        });

    </script>
@endsection
