@section('title')
    فجر الحرية | الرئيسية
@endsection
@section('style')
    <style>
        /* Add this CSS to your stylesheet */
        .card-bg-scale {
            overflow: hidden;
            position: relative;
        }

        .bg-gif {
            {{--background-image:url('{{asset('main/images/palestine_flag.gif')}}'), url('{{asset('main/images/palestine_flag_2.gif')}}');--}}
                   background-color: #117b5d;
            background-size: contain;
            background-position: -100px -150px, 800px -150px; /* Adjust positions for each image */
            background-repeat: no-repeat, no-repeat;
            z-index: 2;
            /*border-left: 8px solid black;*/
            /*border-right: 8px solid black;*/
        }

        .text-input::placeholder {
            font-weight: bold !important;
            opacity: 0.7;
            color: black !important;
        }

    </style>
@endsection
<div id="Search">
    <section class="pb-0 pt-3 mt-1 mb-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="bg-gif p-3 p-sm-4 rounded-3 position-relative overflow-hidden">
                        <div class="row">
                            <div class="col-md-8 col-lg-6 mx-auto text-center py-5 position-relative"
                                 style="direction: rtl">
                                <figure class="position-absolute translate-middle"
                                        @if(isset($error_ms)) style="top: -130px;right: 300px"
                                        @else style="top: -130px;right: 300px" @endif>
                                    <svg width="1848" height="481" viewBox="0 0 1848.9 481.8"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path class="fill-success"
                                              d="m779.4 251c-10.3-11.5-19.9-23.8-29.4-36.1-9-11.6-18.4-22.8-27.1-34.7-15.3-21.2-30.2-45.8-54.8-53.3-10.5-3.2-21.6-3.2-30.6 2.5-7.6 4.8-13 12.6-17.3 20.9-10.8 20.6-16.1 44.7-24.6 66.7-7.9 20.2-19.4 38.6-33.8 54.3-14.7 16.2-31.7 30-50.4 41-15.9 9.4-33.4 17.2-52 19.3-18.4 2-38-2.5-56.5-6.2-22.4-4.4-45.1-9.7-67.6-10.9-9.8-0.5-19.8-0.3-29.1 2.3-9.8 2.8-18.7 8.6-26.6 15.2-17.3 14.5-30.2 34.4-43.7 52.9-12.9 17.6-26.8 34.9-45.4 45.4-19.5 11-42.6 12.1-65 6.6-52.3-13.1-93.8-56.5-127.9-101.5-8.8-11.6-17.3-23.4-25.6-35.4-0.6-0.9-1.1-1.8-1.6-2.7-1.1-2.4-0.9-2.6 0.6-1.2 1 0.9 1.9 1.9 2.7 3 35.3 47.4 71.5 98.5 123.2 123.9 22.8 11.2 48.2 17.2 71.7 12.2 23-5 40.6-21.2 55.3-39.7 24.5-30.7 46.5-75.6 87.1-83 19.5-3.5 40.7 0.1 60.6 3.7 21.2 3.9 42.3 9.1 63.6 11.7 17.8 2.3 35.8-0.1 52.2-7 20-8.1 38.4-20.2 54.8-34.6 16.2-14.1 31-30.7 41.8-50.4 11.1-20.2 17-43.7 24.9-65.7 6.1-16.9 13.8-36.2 29.3-44.5 16.1-8.6 37.3-1.9 52.3 10.6 18.7 15.6 31.2 39.2 46.7 58.2"></path>
                                        <path class="fill-warning"
                                              d="m1157.9 344.9c9.8 7.6 18.9 15.8 28.1 24 8.6 7.7 17.6 15.2 26 23.2 14.8 14.2 29.5 30.9 51.2 34.7 9.3 1.6 18.8 0.9 26.1-3.8 6.1-3.9 10.2-9.9 13.2-16.2 7.6-15.6 10.3-33.2 15.8-49.6 5.2-15.1 13.6-29 24.7-41.3 11.4-12.6 24.8-23.6 40-32.8 12.9-7.8 27.3-14.6 43.1-17.3 15.6-2.6 32.8-0.7 49 0.7 19.6 1.7 39.4 4 58.8 3.4 8.4-0.3 17-1.1 24.8-3.6 8.2-2.7 15.4-7.4 21.6-12.7 13.7-11.6 23.1-26.7 33.3-40.9 9.6-13.5 20.2-26.9 35.3-35.6 15.8-9.2 35.6-11.6 55.2-9.1 45.7 5.8 84.8 34.3 117.6 64.4 8.7 8 17.2 16.2 25.6 24.6 2.5 3.2 1.9 3-1.2 1-34.3-32-69.7-66.9-116.5-81.9-20.5-6.5-42.7-9.2-62.4-4-19.3 5.1-33.1 17.9-44.3 32.2-18.5 23.7-33.9 57.5-68.1 65.5-16.5 3.8-34.9 2.6-52.3 1.3-18.5-1.4-37-3.7-55.4-4.2-15.5-0.5-30.7 2.5-44.2 8.5-16.5 7.2-31.3 17.1-44.3 28.5-12.8 11.2-24.1 24.1-31.9 39-7.9 15.3-11.1 32.5-16.2 48.9-3.9 12.6-9 26.9-21.6 33.9-13.1 7.3-31.9 3.8-45.7-4.1-17.2-10-29.9-26.1-44.6-38.8"></path>
                                        <path class="fill-warning opacity-6"
                                              d="m1840.8 379c-8.8 40.3-167.8 79.9-300.2 45.3-42.5-11.1-91.4-32-138.7-11.6-38.7 16.7-55 66-90.8 67.4-25.1 1-48.6-20.3-58.1-39.8-31-63.3 50.7-179.9 155.7-208.1 50.4-13.5 97.3-3.2 116.1 1.6 36.3 9.3 328.6 87.4 316 145.2z"></path>
                                        <path class="fill-success opacity-6"
                                              d="M368.3,247.3C265.6,257.2,134,226,110.9,141.5C85,47.2,272.5-9.4,355.5-30.7s182.6-31.1,240.8-18.6    C677.6-31.8,671.5,53.9,627,102C582.6,150.2,470.9,237.5,368.3,247.3z"></path>
                                    </svg>
                                </figure>
                                <!-- Title -->
                                <h2 class="display-6 text-white"
                                    style="font-family: 'Changa', sans-serif !important;font-size: 30px">الإستعلام عن
                                    أسير</h2>
                                <p class="text-white">البحث عن طريق الاسم حسب الهوية أو رقم الهوية</p>
                                <!-- Form -->
                                <form wire:submit.prevent="SearchPrisoners"
                                      class="row g-2 mt-3">
                                    <div class="col-sm-12 col-md-4">
                                        <input type="text" wire:model="search.first_name"
                                               class="form-control text-input"
                                               style="background-color:#fff;color: black"
                                               placeholder="الاسم الأول">
                                    </div>
                                    <div class="col-sm-12 col-md-4">
                                        <input type="text" wire:model="search.second_name"
                                               class="form-control text-input"
                                               style="background-color:#fff;color: black"
                                               placeholder="اسم الأب">
                                    </div>
                                    <div class="col-sm-12 col-md-4">
                                        <input type="text" wire:model="search.last_name" class="form-control text-input"
                                               style="background-color:#fff;color: black"
                                               placeholder="اسم العائلة">
                                    </div>
                                    <div class="col-sm-12 mt-3 text-white">
                                        <p>أو</p>
                                    </div>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control text-input"
                                               style="background-color:#fff;color: black"
                                               wire:model="search.identification_number" placeholder="رقم الهوية">
                                    </div>
                                    <div class="col-sm-12 mt-4" id="Statistics">
                                        <button type="submit" style="background-color:#fff;color: black"
                                                class="btn m-0">بحث
                                        </button>
                                    </div>
                                    @if(isset($error_ms))
                                        <div class="col-6 mt-3 text-white mx-auto">
                                            <span class="bg-dark p-2 rounded d-block" style="font-size: 17px">
                                                {{$error_ms}}
                                            </span>
                                            @if($error_ms === "لا يوجد بيانات مشابهة")
                                                <a href="{{route('dashboard.suggestions.create')}}"
                                                   class="btn btn-danger mt-2"
                                                   style="font-size: 18px">إقتراح إضافة</a>
                                            @endif
                                        </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-0 pt-3 mt-1 mb-3" dir="rtl">
        <div class="container-fluid">
            <div class="row">
                <div>
                    <h4 class="mb-3 text-center"
                        style="font-family: 'Changa', sans-serif !important;font-size: 30px">الإحصائيات</h4>
                    <!-- Category item -->
                    <div class="row">
                        @foreach($Statistics as $row)
                            <div
                                class="@if($row->statistic_type === "الأسرى الإجمالي") col-md-12 @endif col-md-4 mx-auto">
                                <div class="text-center mb-3 card-bg-scale position-relative overflow-hidden"
                                     style="border-radius: 10px!important;background-color:#117b5d;">
                                    <div class="p-3">
                                        <figure class="position-absolute top-50 start-50 translate-middle">
                                            <svg width="1848" height="481" viewBox="0 0 1848.9 481.8"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path class="fill-success"
                                                      d="m779.4 251c-10.3-11.5-19.9-23.8-29.4-36.1-9-11.6-18.4-22.8-27.1-34.7-15.3-21.2-30.2-45.8-54.8-53.3-10.5-3.2-21.6-3.2-30.6 2.5-7.6 4.8-13 12.6-17.3 20.9-10.8 20.6-16.1 44.7-24.6 66.7-7.9 20.2-19.4 38.6-33.8 54.3-14.7 16.2-31.7 30-50.4 41-15.9 9.4-33.4 17.2-52 19.3-18.4 2-38-2.5-56.5-6.2-22.4-4.4-45.1-9.7-67.6-10.9-9.8-0.5-19.8-0.3-29.1 2.3-9.8 2.8-18.7 8.6-26.6 15.2-17.3 14.5-30.2 34.4-43.7 52.9-12.9 17.6-26.8 34.9-45.4 45.4-19.5 11-42.6 12.1-65 6.6-52.3-13.1-93.8-56.5-127.9-101.5-8.8-11.6-17.3-23.4-25.6-35.4-0.6-0.9-1.1-1.8-1.6-2.7-1.1-2.4-0.9-2.6 0.6-1.2 1 0.9 1.9 1.9 2.7 3 35.3 47.4 71.5 98.5 123.2 123.9 22.8 11.2 48.2 17.2 71.7 12.2 23-5 40.6-21.2 55.3-39.7 24.5-30.7 46.5-75.6 87.1-83 19.5-3.5 40.7 0.1 60.6 3.7 21.2 3.9 42.3 9.1 63.6 11.7 17.8 2.3 35.8-0.1 52.2-7 20-8.1 38.4-20.2 54.8-34.6 16.2-14.1 31-30.7 41.8-50.4 11.1-20.2 17-43.7 24.9-65.7 6.1-16.9 13.8-36.2 29.3-44.5 16.1-8.6 37.3-1.9 52.3 10.6 18.7 15.6 31.2 39.2 46.7 58.2"></path>
                                                <path class="fill-warning"
                                                      d="m1157.9 344.9c9.8 7.6 18.9 15.8 28.1 24 8.6 7.7 17.6 15.2 26 23.2 14.8 14.2 29.5 30.9 51.2 34.7 9.3 1.6 18.8 0.9 26.1-3.8 6.1-3.9 10.2-9.9 13.2-16.2 7.6-15.6 10.3-33.2 15.8-49.6 5.2-15.1 13.6-29 24.7-41.3 11.4-12.6 24.8-23.6 40-32.8 12.9-7.8 27.3-14.6 43.1-17.3 15.6-2.6 32.8-0.7 49 0.7 19.6 1.7 39.4 4 58.8 3.4 8.4-0.3 17-1.1 24.8-3.6 8.2-2.7 15.4-7.4 21.6-12.7 13.7-11.6 23.1-26.7 33.3-40.9 9.6-13.5 20.2-26.9 35.3-35.6 15.8-9.2 35.6-11.6 55.2-9.1 45.7 5.8 84.8 34.3 117.6 64.4 8.7 8 17.2 16.2 25.6 24.6 2.5 3.2 1.9 3-1.2 1-34.3-32-69.7-66.9-116.5-81.9-20.5-6.5-42.7-9.2-62.4-4-19.3 5.1-33.1 17.9-44.3 32.2-18.5 23.7-33.9 57.5-68.1 65.5-16.5 3.8-34.9 2.6-52.3 1.3-18.5-1.4-37-3.7-55.4-4.2-15.5-0.5-30.7 2.5-44.2 8.5-16.5 7.2-31.3 17.1-44.3 28.5-12.8 11.2-24.1 24.1-31.9 39-7.9 15.3-11.1 32.5-16.2 48.9-3.9 12.6-9 26.9-21.6 33.9-13.1 7.3-31.9 3.8-45.7-4.1-17.2-10-29.9-26.1-44.6-38.8"></path>
                                                <path class="fill-warning opacity-6"
                                                      d="m1840.8 379c-8.8 40.3-167.8 79.9-300.2 45.3-42.5-11.1-91.4-32-138.7-11.6-38.7 16.7-55 66-90.8 67.4-25.1 1-48.6-20.3-58.1-39.8-31-63.3 50.7-179.9 155.7-208.1 50.4-13.5 97.3-3.2 116.1 1.6 36.3 9.3 328.6 87.4 316 145.2z"></path>
                                                <path class="fill-success opacity-6"
                                                      d="M368.3,247.3C265.6,257.2,134,226,110.9,141.5C85,47.2,272.5-9.4,355.5-30.7s182.6-31.1,240.8-18.6    C677.6-31.8,671.5,53.9,627,102C582.6,150.2,470.9,237.5,368.3,247.3z"></path>
                                            </svg>
                                        </figure>
                                        <p class="stretched-link btn-link fw-bold h5 text-white"
                                           style="font-family: 'Changa', sans-serif !important;font-size: 18px">
                                            {{$row->statistic_type}}
                                        </p>
                                        <p class="stretched-link btn-link fw-bold text-white counter"
                                           data-target="{{$row->statistic_number}}"
                                           style="font-family: 'Changa', sans-serif !important;font-size: 25px;color: rgb(255,196,62)!important ;@if($row->statistic_type === "الأسرى الإجمالي") font-size: 35px ;color: rgb(255,255,255)!important @endif">
                                            {{$row->statistic_number}}
                                        </p>
                                    </div>
                                </div>
                                <div id="News"></div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </section>

    @if(count($News->where('on_slider')->take(2)) > 0)
        <section class="pt-0">
            <div class="container-fluid">
                <div class="row">
                    <h4 class="mb-3 text-center"
                        style="font-family: 'Changa', sans-serif !important;font-size: 30px">الأخبار</h4>
                    <!-- Category item -->
                    <div class="col-12">
                        <!-- Card item START -->
                        @foreach($News->where('on_slider')->take(2) as $row)
                            <div class="card border rounded-3 up-hover p-4 mb-4" style="direction: rtl">
                                <div class="row g-3">
                                    <div class="col-lg-5 mt-5">
                                        <!-- Categories -->
                                        <a href="{{ route('news.index',$row->NewsType->news_type_name)}}"
                                           class="badge text-bg-danger mb-2"
                                           style="background-color:{{$row->NewsType->news_type_color}}!important;"><i
                                                class="fas fa-circle me-2 small fw-bold"></i>{{$row->NewsType->news_type_name}}
                                        </a>
                                        <!-- Title -->
                                        <h2 class="card-title"
                                            style="font-family: 'Changa', sans-serif !important;font-size: 25px">
                                            <a href="{{ route('news_show.index', ['url' => $row->news_url]) }}"
                                               class="btn-link text-reset stretched-link">
                                                {{$row->news_title}}
                                            </a>
                                        </h2>
                                        <!-- Author info -->
                                        <div class="d-flex align-items-center position-relative mt-3">
                                            <div>
                                                <ul class="nav align-items-center small">
                                                    <li class="nav-item">{{\Illuminate\Support\Carbon::parse($row->created_at)->isoFormat('D MMMM، YYYY')}}</li>
                                                    <li class="nav-item mx-1">{{\Illuminate\Support\Carbon::parse($row->created_at)->isoFormat('dddd')}}</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Detail -->
                                    @if(isset($row->news_short_description))
                                        <div class="col-md-6 col-lg-4">
                                            <p class="description" id="newsDescription">
                                                    <?php
                                                    $description = $row->news_short_description; // Assuming $row contains the news data
                                                    if (strlen($description) > 1500 && (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Tablet') !== false)) {
                                                        echo substr($description, 0, 500) . '...'; // Show 500 characters for smaller screens
                                                    } else {
                                                        echo substr($description, 0, 800) . '...'; // Show 1500 characters for medium/large screens
                                                    }
                                                    ?>
                                            </p>
                                        </div>
                                    @endif
                                    <!-- Image -->
                                    <div class="col-md-6 col-lg-3">
                                        <img class="rounded-3" src="{{asset('storage/news_photo/'.$row->news_photo)}}"
                                             alt="Card image">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <a href="{{route('news.index')}}" style="font-size: 20px;background-color:#117b5d;color: white"
                           class="btn w-100 mt-4">
                            عرض جميع الأخبار
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <div class="modal fade" dir="rtl" id="searchPrisonersModal" tabindex="-1" aria-hidden="false" wire:ignore.self>
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <!-- Modal header -->
                <div class="modal-header border-0 pt-sm-5 pe-sm-5">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-center mb-4">
                        <img class="navbar-brand-item light-mode-item"
                             style="width: 300px!important; height: 100%!important;"
                             src="{{asset('assets/images/logo.png')}}" alt="logo">
                        <img class="navbar-brand-item dark-mode-item"
                             style="width: 300px!important; height: 100%!important;"
                             src="{{asset('assets/images/light-logo.png')}}" alt="logo">
                    </div>
                    <div class="d-flex justify-content-center mb-4">
                        <h1 style="font-family: 'Changa', sans-serif !important;font-size: 35px">
                            نتائج البحث
                        </h1>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-8 mx-auto mb-5">
                            @if($Prisoners)
                                @forelse($Prisoners as $prisoner)
                                    <div class="card border rounded-3 up-hover p-4 mb-4" style="direction: rtl">
                                        <div class="row g-3">
                                            <div class="col-md-8 col-sm-12 text-center">
                                                <h2 class="card-title"
                                                    style="font-family: 'Changa', sans-serif !important;font-size: 25px">
                                                    <a wire:click="{{$show ? 'hideDetails' : 'showDetails'}}"
                                                       class="btn-link text-reset stretched-link">
                                                        {{$prisoner->full_name}}
                                                    </a>
                                                </h2>
                                            </div>
                                            <div class="col-md-3 col-sm-12 text-center">
                                                <a wire:click="{{$show ? 'hideDetails' : 'showDetails'}}"
                                                   class="btn btn-primary px-5">
                                                    {{$show ? 'إخفاء' : 'عرض التفاصيل'}}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    @if($show)
                                        <ul class="nav nav-tabs nav-justified" dir="rtl">
                                            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab"
                                                                    href="#tab-2-1"
                                                                    style=" font-size: 18px!important;font-weight: bold;color:#ffffff;">
                                                    البيانات الأساسية </a></li>
                                        </ul>
                                        <div class="tab-content" dir="rtl">
                                            <div class="tab-pane show active" id="tab-2-1">
                                                <div class="col-12">
                                                    <!-- Blog list table START -->
                                                    <div class="card border bg-transparent rounded-3">
                                                        <!-- Card body START -->
                                                        <div class="card-body">
                                                            <!-- Blog list table START -->
                                                            <div class="table-responsive border-0 mb-4">
                                                                <table
                                                                    class="table align-middle p-4 mb-0 table-hover table-shrink">
                                                                    <!-- Table head -->
                                                                    <thead class="table-primary">
                                                                    <tr class="text-center" style="font-weight: bold">
                                                                        <th style="width: 180px;" scope="col"
                                                                            class="border-0 rounded-start">
                                                                            اسم
                                                                            الاسير
                                                                        </th>
                                                                        <th style="width: 180px;" scope="col"
                                                                            class="border-0">رقم الهوية
                                                                        </th>
                                                                        <th style="width: 180px;" scope="col"
                                                                            class="border-0">تاريخ الميلاد
                                                                        </th>
                                                                        <th style="width: 180px;" scope="col"
                                                                            class="border-0 rounded-end">
                                                                            المحافظة
                                                                        </th>
                                                                    </tr>
                                                                    </thead>

                                                                    <!-- Table body START -->
                                                                    <tbody class="border-top-0">
                                                                    <!-- Table item -->
                                                                    <tr class="text-center">
                                                                        <!-- Table data -->
                                                                        <td style="font-size: 18px!important;font-weight: bold;color:#000;">
                                                                            {{$prisoner->full_name ?? 'لا يوجد'}}
                                                                        </td>
                                                                        <!-- Table data -->
                                                                        <td style="font-size: 18px!important;font-weight: bold;color:#000;">
                                                                            @php
                                                                                $firstTwo = substr($prisoner->identification_number, 0, 2);
                                                                                $lastTwo = substr($prisoner->identification_number, -2);
                                                                                $hiddenPart = str_repeat('*', strlen($prisoner->identification_number) - 4);
                                                                                $identification_number = $lastTwo.$hiddenPart.$firstTwo
                                                                            @endphp
                                                                            {{$identification_number ?? 'لا يوجد'}}
                                                                        </td>
                                                                        <!-- Table data -->
                                                                        <td style="font-size: 18px!important;font-weight: bold;color:#000;">
                                                                            {{$prisoner->date_of_birth ?? 'لا يوجد'}}
                                                                        </td>
                                                                        <!-- Table data -->
                                                                        <td style="font-size: 18px!important;font-weight: bold;color:#000;">
                                                                            {{$prisoner->City->city_name ?? 'لا يوجد'}}
                                                                        </td>
                                                                        <!-- Table data -->
                                                                    </tr>

                                                                    </tbody>
                                                                    <!-- Table body END -->
                                                                </table>
                                                            </div>
                                                            <div class="table-responsive border-0">
                                                                <table
                                                                    class="table align-middle p-4 mb-0 table-hover table-shrink">
                                                                    <!-- Table head -->
                                                                    <thead class="table-primary">
                                                                    <tr class="text-center">
                                                                        <th style="width: 180px;" scope="col"
                                                                            class="border-0 rounded-start">
                                                                            تاريخ الإعتقال
                                                                        </th>
                                                                        <th style="width: 180px;" scope="col"
                                                                            class="border-0">
                                                                            نوع الإعتقال
                                                                        </th>
                                                                        <th style="width: 180px;" scope="col"
                                                                            class="border-0">
                                                                            الحكم
                                                                        </th>
                                                                    </tr>
                                                                    </thead>
                                                                    <!-- Table body START -->
                                                                    <tbody class="border-top-0">
                                                                    <!-- Table item -->
                                                                    <tr class="text-center">
                                                                        <!-- Table data -->
                                                                        <td style="font-size: 18px!important;font-weight: bold;color:#000;">
                                                                            {{$prisoner->Arrest->arrest_start_date ?? 'لا يوجد'}}
                                                                        </td>
                                                                        <!-- Table data -->
                                                                        <td style="font-size: 18px!important;font-weight: bold;color:#000;">
                                                                            {{$prisoner->Arrest->arrest_type ?? 'لا يوجد'}}
                                                                        </td>
                                                                        <!-- Table data -->
                                                                        <td style="font-size: 18px!important;font-weight: bold;color:#000;">
                                                                            {{$prisoner->Arrest->judgment ?? 'لا يوجد'}}
                                                                        </td>
                                                                        <!-- Table data -->
                                                                    </tr>

                                                                    </tbody>
                                                                    <!-- Table body END -->
                                                                </table>
                                                            </div>

                                                            <!-- Blog list table END -->
                                                        </div>
                                                    </div>
                                                    <!-- Blog list table END -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <a href="{{route('dashboard.suggestions.update',$prisoner->id)}}"
                                               class="btn btn-danger">إقترح تعديل</a>
                                        </div>
                                    @endif
                                @empty
                                    <p>لا يوجد بيانات</p>
                                @endforelse
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('script')
    <script>
        window.addEventListener('show_prisoners_modal', event => {
            $('#searchPrisonersModal').modal('show');
        })
        $(document).ready(function () {
            var counterStarted = false;
            $(window).scroll(function () {
                $('.counter').each(function () {
                    var target = $(this);
                    var targetPosition = target.offset().top;
                    var windowHeight = $(window).height();
                    var scrollPosition = $(window).scrollTop();

                    if (scrollPosition > targetPosition - windowHeight && !target.hasClass('counted')) {
                        target.addClass('counted'); // تجنب إعادة تشغيل العداد مرة أخرى
                        startCounting(target);
                    }
                });
            });

            function startCounting(target) {
                var targetNumber = parseInt(target.attr('data-target'));
                var duration = 3000;
                var start = 0;
                var increment = targetNumber / (duration / 10);

                var timer = setInterval(function () {
                    start += increment;
                    target.text(Math.floor(start));

                    if (start >= targetNumber) {
                        clearInterval(timer);
                        target.text(targetNumber);
                    }
                }, 0);
            }
        });
    </script>
@endsection
