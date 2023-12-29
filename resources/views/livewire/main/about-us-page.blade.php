@section('title')
    بوابة الحرية | من نحن
@endsection
@section('style')
    <style>
        .card-bg-scale {
            position: relative;
        }

        .card-bg-scale::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('{{asset('main/images/about_us.jpg')}}');
            background-position: center left;
            background-size: cover;
            filter: blur(5px);
            z-index: -1;
        }
    </style>
@endsection
<div>

    <section class="pt-4">
        <div class="container">
            <div class="row">
                <div class="col-12" style="direction: rtl">
                    <div class="card bg-dark-overlay-4 overflow-hidden card-bg-scale h-400 text-center">
                        <!-- Card Image overlay -->
                        <div class="card-img-overlay d-flex align-items-center p-3 p-sm-4">
                            <div class="w-100 my-auto">
                                <h1 class="display-4"
                                    style="font-family: 'Changa', sans-serif !important;font-size: 50px">من نحن</h1>
                                <!-- breadcrumb -->
                                <nav class="d-flex justify-content-center" aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-dark breadcrumb-dots mb-0 text-bg-dark p-2 rounded">
                                        <li class="breadcrumb-item">
                                            <a href="{{route('main.index')}}">
                                                <i class="bi bi-house me-1"></i>الرئيسية
                                            </a>
                                        </li>
                                        <li class="breadcrumb-item active">من نحن</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="pt-4 pb-0">
        <div class="container">
            <div class="row">
                <div class="col-xl-9 mx-auto" style="direction: rtl">
                    <h2 style="font-family: 'Changa', sans-serif !important;font-size: 25px">من نحن</h2>
                    <p style="font-family: 'Changa', sans-serif !important;font-size: 20px">
                        منصة إعلامية فلسطينية, تتبع مكتب الأسرى والشهداء والجرحى في حركة المقاومة الإسلامية حماس.
                        تهتم بقضايا الأسرى في سجون الإحتلال وتحديث بياناتهم في سياق العمل على تحريرهم.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="pt-4 pb-0" dir="rtl">
        <div class="container">
            <div class="row">
                <div class="col-xl-9 mx-auto">
                    <!-- Service START -->
                    <div class="d-flex justify-content-around align-content-center">
                        <!-- Service item-->
                        <div>
                            <img class="rounded" width="250" style="border-radius: 20px"
                                 src="{{asset('main/images/hamas.png')}}" alt="Card image">
                        </div>
                    </div>
                    <!-- Service END -->
                </div>  <!-- Col END -->
            </div>
        </div>
    </section>


</div>
@section('script')

@endsection
