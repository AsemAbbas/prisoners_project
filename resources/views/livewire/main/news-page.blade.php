@section('title')
    فجر الحرية | الأخبار
@endsection
@section('style')
@endsection
<div>
    <section class="pt-0" dir="rtl">
        <div class="container">
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="bg-light p-4 p-md-5 rounded-3 text-center">
                        <nav class="d-flex justify-content-center" aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-dots m-0">
                                <li class="breadcrumb-item"><a href="{{route('main.index')}}"><i
                                            class="bi bi-house me-1"></i> الرئيسية</a></li>
                                <li class="breadcrumb-item active">الأخبار</li>
                            </ol>
                        </nav>
                        <div class="card-body mt-3">
                            <!-- Search and select START -->
                            <div class="row g-3 align-items-center justify-content-between">
                                <!-- Search -->
                                <div class="col-md-6">
                                    <form class="rounded position-relative">
                                        <input class="form-control pe-5 bg-transparent" wire:model.live="Search"
                                               type="search"
                                               placeholder="بحث عن خبر..." aria-label="Search">
                                        <button
                                            class="btn bg-transparent border-0 px-2 py-0 position-absolute top-50 end-0 translate-middle-y"
                                            type="submit"><i class="fas fa-search fs-6 "></i></button>
                                    </form>
                                </div>

                                <!-- Select option -->
                                <div class="col-md-6">
                                    <!-- Short by filter -->
                                    <form>
                                        <select class="form-select z-index-9 bg-transparent" wire:model.live="NewsType"
                                                aria-label=".form-select-sm">
                                            <option value="">فرز حسب</option>
                                            @foreach($NewsTypes as $newsType)
                                                <option style="color:#000;"
                                                        value="{{$newsType->id}}">{{$newsType->news_type_name}}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                            </div>
                            <!-- Search and select END -->
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <!-- Card item START -->
                    @foreach($News->where('on_slider',true) as $row)
                        <div class="card border rounded-3 up-hover p-4 mb-4" style="direction: rtl">
                            <div class="row g-3">
                                <div class="col-lg-5">
                                    <!-- Categories -->
                                    <a href="#" class="badge text-bg-danger mb-2"
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
                                        <p>
                                            {{ strlen($row->news_short_description) > 250 ? substr($row->news_short_description, 0, 250) . '...' : $row->news_short_description}}
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

                    <div class="col-12 d-flex justify-content-center">
                        {{ $News->links() }}
                    </div>
                    <!-- Card item END -->
                </div>
            </div>
        </div>
    </section>
</div>
