@section('title')
    فجر الحرية | الأخبار
@endsection
@section('style')
@endsection
<section class="pt-4" dir="rtl">
    <div class="container position-relative" data-sticky-container>
        <div class="row">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item" style="font-size: 15px"><a href="{{route('news.index')}}">الأخبار</a></li>
                    <li class="breadcrumb-item" style="font-size: 15px"><a href="{{ route('news.index',$News->NewsType->news_type_name)}}">{{$News->NewsType->news_type_name}}</a></li>
                    <li class="breadcrumb-item active" aria-current="page" style="font-size: 15px">{{$News->news_title}}</li>
                </ol>
            </nav>
            <div class="col-12">
                <h1 style="font-family: 'Changa', sans-serif !important">{{$News->news_title}}</h1>

                <!-- Podcast image -->
                <div class="mb-3 text-center">
                    <img class="rounded" width="100%" height="100%"
                         src="{{asset('storage/news_photo/'.$News->news_photo)}}" alt="news_photo">
                </div>
                <!-- Podcast title -->
                <!-- Podcast avatar -->
                <div class="row align-items-center mb-2">
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center" style="font-size: 16px">
                            <span> <i class="bi bi-calendar-month-fill me-2"></i>{{\Illuminate\Support\Carbon::parse($News->created_at)->format('Y/m/d')}}</span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <!-- Podcast listen -->
                        <ul class="list-unstyled d-flex justify-content-md-end gap-1 gap-sm-2 align-items-center mt-4 mb-sm-4">
                            <li class="mb-0" style="font-size: 16px">تابعنا على:</li>
                            @foreach($SocialMedia as $social)
                                <li class="ms-2"><a target="_blank" href="{{$social->social_link}}"> <img width="20"
                                                src="{{asset('storage/social_photo/'.$social->social_photo)}}"
                                                alt="{{$social->social_name}}" title="{{$social->social_name}}"> </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!-- Podcast short description -->
                <p class="lead" style="">
                    {!! $News->news_long_description !!}
                </p>
            </div>
        </div>
    </div>
</section>
