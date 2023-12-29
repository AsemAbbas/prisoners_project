<div class="sidebar-wrapper sidebar-theme">
    <nav id="sidebar">
        <div class="navbar-nav theme-brand flex-row  text-center">
            <div class="nav-logo">
                <div class="nav-item theme-logo">
                    <a href="{{route('main.index')}}">
                        <img src="{{asset('assets/images/logo.png')}}"
                             class="navbar-logo logo-light" alt="logo">
                    </a>
                </div>
                <div class="nav-item theme-text">
                    <a style="font-size: 21px!important;" href="{{route('main.index')}}" class="nav-link">بوابة
                        الحرية</a>
                </div>
            </div>
            <div class="nav-item sidebar-toggle">
                <div class="btn-toggle sidebarCollapse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-chevrons-left">
                        <polyline points="11 17 6 12 11 7"></polyline>
                        <polyline points="18 17 13 12 18 7"></polyline>
                    </svg>
                </div>
            </div>
        </div>
        <div class="profile-info">
            <div class="user-info">
                <div class="profile-img">
                    <img src="{{asset('assets/images/admin.png')}}" alt="avatar">
                </div>
                <div class="profile-content">
                    @guest
                        <h6>زائـــر كـــريم</h6>
                        <p>مـــرحبا بك!</p>
                    @endguest
                    @auth
                        <h6>{{\Illuminate\Support\Facades\Auth::user()->name}}</h6>
                        <p>
                            @if(\Illuminate\Support\Facades\Auth::user()->user_status === "مدخل بيانات")
                                مدخل بيانات
                            @elseif(\Illuminate\Support\Facades\Auth::user()->user_status === "مسؤول")
                                مسؤول النظام
                            @else
                                مراجع بيانات
                            @endif
                        </p>
                    @endauth
                </div>
            </div>
        </div>
        <div class="shadow-bottom border border-success"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            @guest
                <li class="menu {{ in_array(Route::getCurrentRoute()->getName(),['dashboard.prisoners','dashboard.suggestions.create','dashboard.suggestions.update']) ? "active" : "" }}">
                    <a href="{{route('dashboard.prisoners')}}" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-list">
                                <line x1="8" y1="6" x2="21" y2="6"></line>
                                <line x1="8" y1="12" x2="21" y2="12"></line>
                                <line x1="8" y1="18" x2="21" y2="18"></line>
                                <line x1="3" y1="6" x2="3.01" y2="6"></line>
                                <line x1="3" y1="12" x2="3.01" y2="12"></line>
                                <line x1="3" y1="18" x2="3.01" y2="18"></line>
                            </svg>
                            <span>قائمة الأسرى</span>
                        </div>
                    </a>
                </li>
            @endguest

            @auth
                <li class="menu {{ in_array(Route::getCurrentRoute()->getName(),['dashboard.prisoners','dashboard.prisoners.create','dashboard.prisoners.update','dashboard.suggestions','dashboard.suggestions.create','dashboard.suggestions.update','dashboard.users','dashboard.news','dashboard.news.create','dashboard.news.update','dashboard.arrests','dashboard.relatives_prisoners']) ? "active" : "" }}">
                    <a href="#dashboard" data-bs-toggle="collapse"
                       aria-expanded="{{ in_array(Route::getCurrentRoute()->getName(),['dashboard.prisoners','dashboard.prisoners.create','dashboard.prisoners.update','dashboard.suggestions','dashboard.suggestions.create','dashboard.suggestions.update','dashboard.users','dashboard.news','dashboard.news.create','dashboard.news.update','dashboard.arrests','dashboard.relatives_prisoners']) ? "true" : "false" }}"
                       class="dropdown-toggle">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-list">
                                <line x1="8" y1="6" x2="21" y2="6"></line>
                                <line x1="8" y1="12" x2="21" y2="12"></line>
                                <line x1="8" y1="18" x2="21" y2="18"></line>
                                <line x1="3" y1="6" x2="3.01" y2="6"></line>
                                <line x1="3" y1="12" x2="3.01" y2="12"></line>
                                <line x1="3" y1="18" x2="3.01" y2="18"></line>
                            </svg>
                            <span>القوائم الرئيسية</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{ in_array(Route::getCurrentRoute()->getName(),['dashboard.prisoners','dashboard.prisoners.create','dashboard.prisoners.update','dashboard.suggestions','dashboard.suggestions.create','dashboard.suggestions.update','dashboard.users','dashboard.news','dashboard.news.create','dashboard.news.update','dashboard.arrests','dashboard.relatives_prisoners']) ? "show" : "" }}"
                        id="dashboard" data-bs-parent="#accordionExample">
                        @if(\Illuminate\Support\Facades\Auth::user()->user_status !== "مراجع بيانات")
                            <li class="{{ in_array(Route::getCurrentRoute()->getName(),['dashboard.prisoners','dashboard.prisoners.create','dashboard.prisoners.update','dashboard.arrests','dashboard.relatives_prisoners']) ? "active" : "" }}">
                                <a href="#level-three" data-bs-toggle="collapse"
                                   aria-expanded="{{ in_array(Route::getCurrentRoute()->getName(),['dashboard.prisoners','dashboard.prisoners.create','dashboard.prisoners.update','dashboard.arrests','dashboard.relatives_prisoners']) ? "true" : "false" }}"
                                   class="dropdown-toggle collapsed"> قائمة الأسرى
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-chevron-right">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </a>
                                <ul class="list-unstyled sub-submenu collapse {{ in_array(Route::getCurrentRoute()->getName(),['dashboard.prisoners','dashboard.prisoners.create','dashboard.prisoners.update','dashboard.arrests','dashboard.relatives_prisoners']) ? "show" : "" }}"
                                    id="level-three" data-bs-parent="#pages">
                                    <li>
                                        <a class="{{Route::getCurrentRoute()->getName() === 'dashboard.prisoners' ? '':'text-dark'}}"
                                           href="{{route('dashboard.prisoners')}}">
                                            عرض الأسرى </a>
                                    </li>
                                    {{--                                <li>--}}
                                    {{--                                    <a class="{{Route::getCurrentRoute()->getName() === 'dashboard.prisoners.create' ? '':'text-dark'}}"--}}
                                    {{--                                       href="{{route('dashboard.prisoners.create')}}">--}}
                                    {{--                                        إضافة أسير </a>--}}
                                    {{--                                </li>--}}
{{--                                    <li>--}}
{{--                                        <a class="{{Route::getCurrentRoute()->getName() === 'dashboard.arrests' ? '':'text-dark'}}"--}}
{{--                                           href="{{route('dashboard.arrests')}}">--}}
{{--                                            عرض الاعتقال </a>--}}
{{--                                    </li>--}}
                                    <li>
                                        <a class="{{Route::getCurrentRoute()->getName() === 'dashboard.relatives_prisoners' ? '':'text-dark'}}"
                                           href="{{route('dashboard.relatives_prisoners')}}">
                                            عرض الأقارب </a>
                                    </li>

                                </ul>
                            </li>
                        @endif
                        <li class="{{ Route::getCurrentRoute()->getName()== 'dashboard.suggestions' ? 'active' : '' }}">
                            <a href="{{route('dashboard.suggestions')}}">قائمة الإقتراحات</a>
                        </li>
                        @if(\Illuminate\Support\Facades\Auth::user()->user_status === "مسؤول")
                            <li class="{{ Route::getCurrentRoute()->getName()== 'dashboard.news' ? 'active' : '' }}">
                                <a href="{{route('dashboard.news')}}">قائمة الأخبار</a>
                            </li>
                            <li class="{{ Route::getCurrentRoute()->getName()== 'dashboard.users' ? 'active' : '' }}">
                                <a href="{{route('dashboard.users')}}">قائمة المستخدمين</a>
                            </li>
                        @endif
                    </ul>

                </li>
                @if(\Illuminate\Support\Facades\Auth::user()->user_status !== "مراجع بيانات")
                    <li class="menu {{ in_array(Route::getCurrentRoute()->getName(),['dashboard.cities','dashboard.belongs','dashboard.news_types','dashboard.prisoner_types','dashboard.news_types','dashboard.statistics','dashboard.social_media','dashboard.relationships','dashboard.healths']) ? "active" : "" }}">
                        <a href="#dashboardSub" data-bs-toggle="collapse"
                           aria-expanded="{{ in_array(Route::getCurrentRoute()->getName(),['dashboard.cities','dashboard.belongs','dashboard.news_types','dashboard.prisoner_types','dashboard.news_types','dashboard.statistics','dashboard.social_media','dashboard.relationships','dashboard.healths']) ? "true" : "false" }}"
                           class="dropdown-toggle">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-list">
                                    <line x1="8" y1="6" x2="21" y2="6"></line>
                                    <line x1="8" y1="12" x2="21" y2="12"></line>
                                    <line x1="8" y1="18" x2="21" y2="18"></line>
                                    <line x1="3" y1="6" x2="3.01" y2="6"></line>
                                    <line x1="3" y1="12" x2="3.01" y2="12"></line>
                                    <line x1="3" y1="18" x2="3.01" y2="18"></line>
                                </svg>
                                <span>القوائم الفرعية</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled {{ in_array(Route::getCurrentRoute()->getName(),['dashboard.cities','dashboard.belongs','dashboard.news_types','dashboard.prisoner_types','dashboard.statistics','dashboard.social_media','dashboard.relationships','dashboard.healths']) ? "show" : "" }}"
                            id="dashboardSub" data-bs-parent="#accordionSub">
                            <li class="{{ Route::getCurrentRoute()->getName()== 'dashboard.cities' ? 'active' : '' }}">
                                <a href="{{route('dashboard.cities')}}">قائمة المحافظات</a>
                            </li>
                            <li class="{{ Route::getCurrentRoute()->getName()== 'dashboard.healths' ? 'active' : '' }}">
                                <a href="{{route('dashboard.healths')}}">قائمة الحالة الصحية</a>
                            </li>
                            <li class="{{ Route::getCurrentRoute()->getName()== 'dashboard.prisoner_types' ? 'active' : '' }}">
                                <a href="{{route('dashboard.prisoner_types')}}">قائمة تصنيفات الأسرى</a>
                            </li>
                            <li class="{{ Route::getCurrentRoute()->getName()== 'dashboard.news_types' ? 'active' : '' }}">
                                <a href="{{route('dashboard.news_types')}}">قائمة تصنيفات الأخبار</a>
                            </li>
                            <li class="{{ Route::getCurrentRoute()->getName()== 'dashboard.relationships' ? 'active' : '' }}">
                                <a href="{{route('dashboard.relationships')}}">قائمة صلة القرابة</a>
                            </li>
                            <li class="{{ Route::getCurrentRoute()->getName()== 'dashboard.belongs' ? 'active' : '' }}">
                                <a href="{{route('dashboard.belongs')}}">قائمة الفصائل</a>
                            </li>
                            <li class="{{ Route::getCurrentRoute()->getName()== 'dashboard.statistics' ? 'active' : '' }}">
                                <a href="{{route('dashboard.statistics')}}">قائمة الإحصائيات</a>
                            </li>
                            <li class="{{ Route::getCurrentRoute()->getName()== 'dashboard.social_media' ? 'active' : '' }}">
                                <a href="{{route('dashboard.social_media')}}">قائمة التواصل الإجتماعي</a>
                            </li>
                        </ul>
                    </li>
                @endif
                <div class="text-center mt-5" id="logoutButton">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-log-out">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                        </button>
                    </form>
                </div>
            @endauth
        </ul>
    </nav>

</div>
