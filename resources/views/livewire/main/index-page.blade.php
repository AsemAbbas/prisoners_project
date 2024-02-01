@section('title')
    فجر الحرية
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
                                         background-color: #022d4f;
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

        .text-shadow {
            text-shadow: 4px 4px 6px rgba(0, 0, 0, 0.5); /* Change values as needed */
        }

    </style>
@endsection
<div id="Search">
    <section class="pb-0 pt-3 mt-1 mb-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="p-3 p-sm-4 rounded-3 position-relative overflow-hidden"
                         style="background-image:url('{{asset('main/images/search_bg.webp')}}');background-repeat: no-repeat;background-size:cover;background-position:center;">
                        <div class="row">
                            <div class="col-md-8 col-lg-6 mx-auto text-center py-5 position-relative"
                                 style="direction: rtl">
                                <!-- Title -->
                                <h2 class="display-6 text-white"
                                    style="font-family: 'Changa', sans-serif !important;font-size: 30px">الاستعلام عن
                                    أسير</h2>
                                <p class="text-white">البحث عن طريق رقم الهوية أو الاسم</p>
                                <!-- Form -->
                                <form wire:submit.prevent="SearchPrisoners"
                                      class="row g-2 mt-3">
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control text-input"
                                               style="background-color:#fff;color: black"
                                               maxlength="9"
                                               wire:model="search.identification_number" placeholder="رقم الهوية">
                                    </div>
                                    <div class="col-sm-12 mt-3 text-white">
                                        <p>أو</p>
                                    </div>
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
                                    <div class="col-sm-12 mt-4" id="Statistics">
                                        <button type="submit" style="background-color:#fff;color: black;width: 200px"
                                                class="btn m-0">بحث
                                        </button>
                                    </div>
                                    <div class="col-6 mt-3 text-white mx-auto">
                                        @if($errors->any())
                                            <span class="bg-danger p-2 rounded d-block mb-2" style="font-size: 17px">
                                                {{$errors->first()}}
                                            </span>
                                            @if($errors->first() === "لا يوجد بيانات مشابهة")
                                                <a href="{{route('dashboard.suggestions.create')}}"
                                                   class="btn btn-warning mt-2"
                                                   style="font-size: 18px">إقتراح إضافة</a>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col-sm-12 mt-4">
                                        <a style="background-color:#fff;color: black;width: 200px;"
                                           wire:click="showSearchCityPrisoners"
                                           class="btn m-0 d-block mx-auto">بحث عن أسماء مناطق
                                        </a>
                                    </div>
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
                        style="font-family: 'Changa', sans-serif !important;font-size: 30px;font-weight: bolder">
                        الاحصائيات</h4>
                    <!-- Category item -->
                    <div class="row">
                        @foreach($Statistics as $row)
                            <div
                                class="@if($row->statistic_type === "إجمالي الأسرى في سجون الإحتلال") col-lg-12 @endif col-lg-4 mx-auto">
                                <div class="text-center mb-3 position-relative overflow-hidden"
                                     style="border-radius: 10px!important;@if($row->statistic_type === "إجمالي الأسرى في سجون الإحتلال") background-image:url('{{asset('main/images/total_statistic_bg.webp')}}'); @else background-image:url('{{asset('main/images/statistic_bg.webp')}}'); @endif background-repeat: no-repeat;background-size:cover;background-position:center;">
                                    <div class="p-3">
                                        <p class="fw-bold h5 text-white"
                                           style="font-weight:50!important;font-family: 'Changa', sans-serif !important; font-size: 17px; @if($row->statistic_type === 'إجمالي الأسرى في سجون الإحتلال') color: rgba(9,30,70,0.81)!important;font-weight:bold!important;font-size: 18px; @endif">
                                            {{$row->statistic_type}}
                                        </p>
                                        <p class="fw-bold text-white counter text-shadow"
                                           data-target="{{$row->statistic_number}}"
                                           style="font-weight: bold!important;font-family: 'Changa', sans-serif !important; font-size: 40px; color: rgb(255,196,62)!important; @if($row->statistic_type === 'إجمالي الأسرى في سجون الإحتلال')color: rgba(9,30,70,0.81)!important;  font-size: 80px; @endif">
                                            {{$row->statistic_number}}
                                        </p>
                                        @if($row->statistic_type !== "إجمالي الأسرى في سجون الإحتلال")
                                            <img width="80"
                                                 style="opacity: 0.6;position: fixed;top: 35px;left: 30px;z-index: -1;background-color:#fff;border-radius: 20px;padding: 15px"
                                                 src="{{asset('storage/statistic_photo/'.$row->statistic_photo)}}"
                                                 alt="statistic_photo">
                                        @endif
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
            <div class="container">
                <div class="row">
                    <h4 class="mb-3 text-center"
                        style="font-family: 'Changa', sans-serif !important;font-size: 30px;font-weight: bolder">
                        الأخبار</h4>
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
                                        <div class="col-md-6 col-lg-3">
                                            <p class="description" id="newsDescription">
                                                {{ mb_substr($row->news_short_description, 0, 380, 'UTF-8') }}
                                                @if(mb_strlen($row->news_short_description, 'UTF-8') > 380)
                                                    ...
                                                @endif
                                            </p>
                                        </div>
                                    @endif
                                    <!-- Image -->
                                    <div class="col-md-6 col-lg-4">
                                        <img class="rounded-3" width="100%"
                                             src="{{asset('storage/news_photo/'.$row->news_photo)}}"
                                             alt="Card image">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <a href="{{route('news.index')}}" style="font-size: 20px;background-color:#022d4f;color: white"
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
                             src="{{asset('assets/images/logo.webp')}}" alt="logo">
                        <img class="navbar-brand-item dark-mode-item"
                             style="width: 300px!important; height: 100%!important;"
                             src="{{asset('assets/images/light-logo.webp')}}" alt="logo">
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
                                                        {{$prisoner->full_name}} @if(!empty($prisoner->nick_name))
                                                            ({{$prisoner->nick_name}})
                                                        @endif
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
                                                                        <td style="font-size: 18px!important;font-weight: bold;">
                                                                            {{$prisoner->full_name ?? 'لا يوجد'}}
                                                                        </td>
                                                                        <!-- Table data -->
                                                                        <td style="font-size: 18px!important;font-weight: bold;">
                                                                            @php
                                                                                if (isset($prisoner->identification_number) && strlen($prisoner->identification_number) == 9){
                                                                                     $firstTwo = substr($prisoner->identification_number, 0, 2);
                                                                                    $lastTwo = substr($prisoner->identification_number, -2);
                                                                                    $hiddenPart = str_repeat('*', strlen($prisoner->identification_number) - 4);
                                                                                    $identification_number = $lastTwo.$hiddenPart.$firstTwo;
                                                                                }
                                                                            @endphp
                                                                            {{$identification_number ?? $prisoner->identification_number ?? 'لا يوجد'}}
                                                                        </td>
                                                                        <!-- Table data -->
                                                                        <td style="font-size: 18px!important;font-weight: bold;">
                                                                            {{$prisoner->date_of_birth ?? 'لا يوجد'}}
                                                                        </td>
                                                                        <!-- Table data -->
                                                                        <td style="font-size: 18px!important;font-weight: bold;">
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
                                                                        <th style="width: 180px;" scope="col"
                                                                            class="border-0">
                                                                            مفرج عنه حالياً؟
                                                                        </th>
                                                                    </tr>
                                                                    </thead>
                                                                    <!-- Table body START -->
                                                                    <tbody class="border-top-0">
                                                                    <!-- Table item -->
                                                                    <tr class="text-center">
                                                                        <!-- Table data -->
                                                                        <td style="font-size: 18px!important;font-weight: bold;">
                                                                            {{$prisoner->Arrest->arrest_start_date ?? 'لا يوجد'}}
                                                                        </td>
                                                                        <!-- Table data -->
                                                                        <td style="font-size: 18px!important;font-weight: bold;">
                                                                            {{$prisoner->Arrest->arrest_type ?? 'لا يوجد'}}
                                                                        </td>
                                                                        <!-- Table data -->
                                                                        <td style="font-size: 18px!important;font-weight: bold;">
                                                                            {{$prisoner->Arrest->judgment ?? 'لا يوجد'}}
                                                                        </td>
                                                                        <td style="font-size: 18px!important;font-weight: bold;">
                                                                            @if(isset($prisoner->Arrest->IsReleased) && $prisoner->Arrest->IsReleased == 1)
                                                                                نعم
                                                                            @else
                                                                                لا
                                                                            @endif
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

    <div class="modal fade" dir="rtl" id="searchCityPrisoners" tabindex="-1" aria-hidden="false" wire:ignore.self>
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
                             src="{{asset('assets/images/logo.webp')}}" alt="logo">
                        <img class="navbar-brand-item dark-mode-item"
                             style="width: 300px!important; height: 100%!important;"
                             src="{{asset('assets/images/light-logo.webp')}}" alt="logo">
                    </div>
                    <div class="col mb-3">
                        <h4 class="mb-3 text-center"
                            style="font-family: 'Changa', sans-serif !important;font-size: 30px;font-weight: bolder">
                            بحث في المناطق</h4>
                        <div class="col-lg-8 col-md-9 col-sm-12 mx-auto">
                            <label for="city_id">بحث حسب المحاظة</label>
                            <select class="form-select" id="city_id" wire:model.live="CitySearch.city_id">
                                <option>اختر...</option>
                                @foreach($Cities as $city)
                                    <option value="{{$city->id}}">{{$city->city_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-8 col-md-9 col-sm-12 mx-auto">
                            <label for="town_id">بحث حسب البلدة</label>
                            <select class="form-select" id="town_id" @if(count($Towns) == 0) disabled
                                    @endif wire:model.live="CitySearch.town_id">
                                <option>اختر...</option>
                                @foreach($Towns as $town)
                                    <option value="{{$town->id}}">{{$town->town_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-lg-8 col-md-9 col-sm-12 mx-auto mb-5">
                            @if($CityPrisoners)
                                <div class="table-responsive border-0 mb-4">
                                    <table class="table align-middle p-4 mb-0 table-hover table-shrink">
                                        <!-- Table head -->
                                        <thead class="table-primary">
                                        <tr class="text-center" style="font-weight: bold">
                                            <th style="width: 180px;" scope="col" class="border-0">
                                                اسم الاسير
                                            </th>
                                            <th style="width: 180px;" scope="col" class="border-0">
                                                حالة البيانات
                                            </th>
                                        </tr>
                                        </thead>

                                        <tbody class="border-top-0">
                                        @forelse($CityPrisoners as $prisoner)

                                            @php
                                                $check_one = (bool)$prisoner->Arrest->arrest_start_date;
                                                $check_two = (bool)$prisoner->identification_number;
                                                $check_three = !($prisoner->Arrest->arrest_type == "محكوم" && $prisoner->Arrest->judgment == null);
                                                $check_four = (bool)$prisoner->first_name;
                                                $check_six = (bool)$prisoner->second_name;
                                                $check_seven = (bool)$prisoner->last_name;

                                                if (!$check_one || !$check_two || !$check_three || !$check_four || !$check_six || !$check_seven) {
                                                    $data_status = "غير مكتمل";
                                                } else {
                                                    $data_status = "مكتمل";
                                                }
                                            @endphp
                                            <tr class="text-center">
                                                <td style="font-size: 18px!important;font-weight: bold;">
                                                    {{$prisoner->full_name ?? 'لا يوجد'}}
                                                </td>
                                                <td style="font-size: 18px!important;font-weight: bold;">
                                                    <span
                                                        class="@if($data_status === "غير مكتمل") text-danger @else text-success @endif">{{$data_status}}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="text-center">
                                                <td colspan="2">عليك اختيار منطقة وبلدة</td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                    <div class="d-flex justify-content-center mt-3"> <!-- Add text-center class here -->
                                        {{$CityPrisoners->links()}}
                                    </div>
                                </div>
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

        window.addEventListener('show_city_prisoners_modal', event => {
            $('#searchCityPrisoners').modal('show');
        })

        $(document).ready(function () {
            var isMobile = window.innerWidth <= 768;

            $('.counter').each(function () {
                var target = $(this);
                var targetPosition = target.offset().top;
                var windowHeight = $(window).height();
                var initialNumber = isMobile ? 0 : parseInt(target.attr('data-target'));
                var scrollPosition = $(window).scrollTop();

                if (scrollPosition > targetPosition - windowHeight && !target.hasClass('counted')) {
                    target.addClass('counted');
                    startCounting(target, initialNumber);
                }
            });

            $(window).on('scroll', function () {
                $('.counter').each(function () {
                    var target = $(this);
                    var targetPosition = target.offset().top;
                    var windowHeight = $(window).height();
                    var scrollPosition = $(window).scrollTop();

                    if (scrollPosition > targetPosition - windowHeight && !target.hasClass('counted')) {
                        target.addClass('counted');
                        startCounting(target, 0);
                    }
                });
            });
        });

        function startCounting(target, initialNumber) {
            var targetNumber = parseInt(target.attr('data-target'));
            var duration = 3000;
            var start = initialNumber;
            var increment = (targetNumber - initialNumber) / (duration / 10);

            var timer = setInterval(function () {
                start += increment;
                target.text(Math.floor(start));

                if ((increment > 0 && start >= targetNumber) || (increment < 0 && start <= targetNumber)) {
                    clearInterval(timer);
                    target.text(targetNumber);
                }
            }, 0);
        }
    </script>
@endsection
