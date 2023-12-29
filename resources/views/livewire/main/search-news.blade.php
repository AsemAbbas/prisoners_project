<div>
    <div class="modal fade" dir="rtl" id="searchModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <!-- Modal header -->
                <div class="modal-header border-0 pt-sm-5 pe-sm-5">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row align-items-center">
                        <div class="col-lg-5 mx-auto mb-5">
                            <div class="d-flex justify-content-center mb-4" href="#">
                                <img class="navbar-brand-item light-mode-item"
                                     style="width: 300px!important; height: 100%!important;"
                                     src="{{asset('assets/images/logo.png')}}" alt="logo">
                                <img class="navbar-brand-item dark-mode-item"
                                     style="width: 300px!important; height: 100%!important;"
                                     src="{{asset('assets/images/light-logo.png')}}" alt="logo">
                            </div>
                            <h2 class="mb-4" style="font-family: 'Changa', sans-serif!important;">البحث في الأخبار</h2>
                            <!-- Search form START -->
                            <form class="position-relative w-100">
                                <div class="mb-2 input-group-lg mb-0">
                                    <!-- Search input -->
                                    <input class="form-control mb-0 pe-6" wire:model.live="searchTerm" type="text"
                                           name="search"
                                           placeholder="عن أي خبر تبحث؟">
                                </div>
                                <!-- Search button -->
                                <button type="button" class="position-absolute end-0 top-0 btn h-100 border-0">
                                    <i class="bi bi-search display-8"></i>
                                </button>
                            </form>
                            @if($News)
                                @foreach($News as $row)
                                    <div class="card border rounded-3 up-hover p-4 mb-4" style="direction: rtl">
                                        <div class="row g-3">
                                            <div class="col-lg-5">
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
                                                            <li class="nav-item">{{\Illuminate\Support\Carbon::parse($row->created_at)->format('M d, Y')}}</li>
                                                            <li class="nav-item mx-1">{{\Illuminate\Support\Carbon::parse($row->created_at)->format('D')}}</li>
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
                                                <img class="rounded-3"
                                                     src="{{asset('storage/news_photo/'.$row->news_photo)}}"
                                                     alt="Card image">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="col-12 d-flex justify-content-center">
                                    {{ $News->links() }}
                                </div>
                            @endif
                            <!-- Search form END -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
