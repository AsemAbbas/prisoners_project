@section('title')
    فجر الحرية | {{$showEdit ? 'تعديل خبر' : 'إضافة خبر'}}
@endsection
@section('style')
    <link rel="stylesheet" href="{{asset('plugins-rtl/apex/apexcharts.css')}}">
    @vite(['resources/rtl/scss/light/assets/components/list-group.scss'])
    @vite(['resources/rtl/scss/light/assets/widgets/modules-widgets.scss'])
    @vite(['resources/rtl/scss/dark/assets/components/list-group.scss'])
    @vite(['resources/rtl/scss/dark/assets/widgets/modules-widgets.scss'])
    @vite(['resources/rtl/scss/light/assets/elements/alert.scss'])
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endsection

<div id="target-element">
    <div class="d-flex justify-content-center align-content-center">
        <img src="{{asset('assets/images/logo.png')}}" width="300px" alt="logo">
    </div>
    <div class="p-5">
        <h1 class="text-center mb-5">{{$showEdit ? 'تعديل خبر' : 'إضافة خبر'}}</h1>
        <form wire:submit.prevent="{{$showEdit ? 'updateNews' : 'createNews'}}">
            <div class="row">
                <div class="col-md-10 mx-auto">
                    <div class="form-group col-md-12 mb-4">
                        <label for="news_title">عنوان الخبر</label>
                        <input wire:model="state.news_title" type="text"
                               class="form-control @error('news_title') is-invalid @enderror"
                               id="news_title"
                               placeholder="عنوان الخبر">
                        @error('news_title')
                        <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-12 mb-4">
                        <label for="news_photo">صورة الخبر</label>
                        <input wire:model.live="state.news_photo" type="file"
                               class="form-control @error('news_photo') is-invalid @enderror"
                               id="news_photo"
                               placeholder="صورة الخبر">

                        @if(isset($state['news_photo']))
                            @if(gettype($state['news_photo']) !== "string")
                                <img src="{{ $state['news_photo']->temporaryUrl() }}" alt="Preview"
                                     style="max-width: 300px;"/>
                            @else
                                <img src="{{ asset('storage/news_photo/'.$state['news_photo']) }}"
                                     alt="Preview"
                                     style="max-width: 300px;"/>
                            @endif
                        @endif

                        @error('news_photo')
                        <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-12 mb-4">
                        <label for="news_url">رابط الخبر</label>
                        <input wire:model="state.news_url" type="text"
                               class="form-control @error('news_url') is-invalid @enderror"
                               id="news_url"
                               placeholder="رابط الخبر">
                        @error('news_url')
                        <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group col-md-12 mb-4">
                        <label for="news_type_id">تصنيف الخبر</label>
                        <select wire:model="state.news_type_id"
                                class="form-select @error('news_type_id') is-invalid @enderror"
                                id="news_type_id">
                            <option>إختر...</option>
                            @foreach($NewsTypes as $NewsType)
                                <option value="{{$NewsType->id}}">{{$NewsType->news_type_name}}</option>
                            @endforeach
                        </select>
                        @error('news_type_id')
                        <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-12 mb-4">
                        <label for="news_short_description">وصف الخبر القصير</label>
                        <textarea id="news_short_description" wire:model="state.news_short_description"
                                  rows="4"
                                  class="form-control"></textarea>
                        @error('news_short_description')
                        <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-12 mb-4" wire:ignore dir="ltr">
                        <label for="news_long_description">وصف الخبر الكامل</label>
                        <textarea id="news_long_description" wire:model="state.news_long_description"
                                  class="form-control"></textarea>
                        @error('news_long_description')
                        <div class="invalid-feedback" style="font-size: 15px">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="modal-footer d-flex justify-content-start">
                        <button type="submit" class="btn {{$showEdit ? 'btn-warning' : 'btn-primary'}}">حفظ
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-save">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                <polyline points="7 3 7 8 15 8"></polyline>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@section('script')
    <script src="{{asset('plugins-rtl/apex/apexcharts.min.js')}}"></script>
    @vite(['resources/rtl/assets/js/widgets/modules-widgets.js'])
    <script src="{{asset('plugins-rtl/global/vendors.min.js')}}"></script>
    @vite(['resources/rtl/assets/js/custom.js'])
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script>

        document.addEventListener('DOMContentLoaded', function () {
            document.body.addEventListener('create_massage', function () {
                Swal.fire({
                    title: 'نجاح',
                    text: 'تم إضافة الخبر بنجاح',
                    icon: 'success',
                    confirmButtonText: 'تم',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/dashboard/news';
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            document.body.addEventListener('update_massage', function () {
                Swal.fire({
                    title: 'نجاح',
                    text: 'تم تعديل الخبر بنجاح',
                    icon: 'success',
                    confirmButtonText: 'تم',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/dashboard/news';
                    }
                });
            });
        });

        $(function () {
            $('#news_long_description').summernote({
                placeholder: 'اكتب وصف الخبر...',
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'fontname', 'fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['codeview', 'help']],
                ],
                callbacks: {
                    onChange: function (contents, $editable) {
                    @this.set('state.news_long_description', contents);
                    }
                },
                fontNames: ['Changa', 'Arial', 'Times New Roman', 'Courier New', 'Tahoma'],
                fontNamesIgnoreCheck: ['Changa'], // Ignore Changa from system font check
                fontNamesFallback: ['Changa, sans-serif'] // Fallback font for Changa

            });
        })
    </script>
@endsection
