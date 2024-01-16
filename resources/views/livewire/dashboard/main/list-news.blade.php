@section('title')
    فجر الحرية | قائمة الأخبار
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

        .modal-body {
            max-height: 400px; /* Adjust this value as needed */
            overflow-y: auto; /* Enable vertical scrolling if content exceeds max height */
        }
    </style>
@endsection
<div class="p-4">
    <!-- BREADCRUMB -->
    <div class="page-meta">
        <nav class="breadcrumb-style-one" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">لوحة التحكم</a></li>
                <li class="breadcrumb-item active" aria-current="page">قائمة الأخبار</li>
            </ol>
        </nav>
    </div>
    <!-- /BREADCRUMB -->
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between flex-wrap mt-2">
                <div>
                    <a class="btn btn-primary mb-2" href="{{route('dashboard.news.create')}}">
                        إضافة خبر
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
                <div class="d-flex">
                    <div>
                        <input wire:model.live="Search" type="search" id="Search"
                               placeholder="البحث في قائمة الأخبار...">
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
                                    class="flaticon-home-fill-1 mr-1"></i>الكل ({{$NewsCount['all']}})</a>
                            <a wire:click="SortBy('شريط الأخبار')" class="btn dropdown-item"><i
                                    class="flaticon-home-fill-1 mr-1"></i>شريط الأخبار
                                ({{$NewsCount['on_slider']}})</a>
                        </div>
                    </div>
                </div>

            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-center">
                    <thead>
                    <tr style="font-size: 16px">
                        <th>#</th>
                        <th>عنوان الخبر</th>
                        <th>نوع الخبر</th>
                        <th>رابط الخبر</th>
                        <th>شريط الأخبار</th>
                        <th>ترتيب الخبر</th>
                        <th>صورة الخبر</th>
                        <th>الخيارات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($News as $key => $row)
                        <tr>
                            <td>{{$News->firstItem() + $key}}</td>
                            <td>{{$row->news_title ?? 'لا يوجد'}}</td>
                            <td>{{$row->NewsType->news_type_name ?? 'لا يوجد'}}</td>
                            <td>
                                @if(isset($row->news_url))
                                    <a href="{{ route('news_show.index', ['url' => $row->news_url]) }}" target="_blank"
                                       class="btn btn-link">
                                        رابط الخبر
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                             viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round" class="feather feather-link text-dark">
                                            <path
                                                d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                            <path
                                                d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                        </svg>
                                    </a
                                        @else
                                        لا يوجد
                                @endif
                            </td>
                            <td>
                                <div class="form-check form-check-success form-check-inline">
                                    @if($row->on_slider)
                                        <input class="form-check-input" type="checkbox" id="form-check-success"
                                               wire:click="onSlider({{$row}})" checked>
                                    @else
                                        <input class="form-check-input" type="checkbox" id="form-check-success"
                                               wire:click="onSlider({{$row}})">
                                    @endif

                                    <label
                                        class="form-check-label mx-1 @if($row->on_slider) text-success @else text-danger @endif"
                                        for="form-check-success">
                                        شريط الأخبار
                                    </label>
                                </div>
                            </td>
                            <td>
                                <a @if(isset($news_id) && isset($news_key) && $news_key === $key) hidden
                                   @endif
                                   wire:click="NewsOrderBy({{$row->id}},{{$key}})"
                                   class="btn btn-link">{{$row->order_by ?? 'لا يوجد'}}</a>
                                @if(isset($news_id))
                                    @if(isset($news_key) && $news_key === $key)
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
                                <img src="{{asset('storage/news_photo/'.$row->news_photo) ?? null}}"
                                     alt="news_photo" class="border border-dark rounded rounded-1" width="100">
                            </td>
                            <td>
                                <a wire:click="showNewsDescription({{$row}})" class="btn btn-primary"
                                   data-toggle="tooltip" data-placement="top" title="show">
                                    عرض الوصف
                                </a>
                                <a href="{{route('dashboard.news.update',$row)}}" class="btn btn-warning"
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
                    {{$News->links()}}
                </div>
            </div>
        </div>
    </div>
    <!-- Show News Description Modal -->
    <div class="modal modal-lg fade" id="description" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content bg-white">
                <div class="modal-header bg-primary" style="margin: 5px;">
                    <h1 class="modal-title fs-5 text-white" id="staticBackdropLabel">عرض وصف الخبر</h1>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {!! $News_->news_long_description ?? 'لا يوجد' !!}
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
                        id="staticBackdropLabel">حذف الخبر</h1>
                    <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="text-danger m-3">هل أنت متأكد انك تريد حذف الخبر؟</h5>
                    <span class="text-danger m-3">
                        * تنبية:
                        <span class="text-dark">
                            سيتم حذف {{$News_->news_title ?? null}}
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
        window.addEventListener('showNewsDescription', event => {
            $('#description').modal('show');
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
                        text: 'تم حذف بيانات الخبر',
                        icon: 'success',
                        confirmButtonText: 'تم'
                    }
                );

            });
        });
    </script>
@endsection
