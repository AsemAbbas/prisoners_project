<div class="col-lg-12">
    <div class="card m-b-30">
        <div class="card-body">
            <div class="accordion" id="accordionExample">
                <div class="alert bg-danger text-white text-center">تحذير هام جداً:(تم إضافة
                    الحقول
                    التي لم يظهر لها اي خطأ عليك إزالتها من الملف)
                </div>
                <table class="table text-center"
                       style="background-color: whitesmoke">
                    <tr>
                        <th>رقم السطر</th>
                        <th>العنوان</th>
                        <th>المشكلة</th>
                        <th>القيمة</th>
                    </tr>
                    @foreach($ImportErrors as $validation)
                        <tr>
                            <td>
                                <ul class="list-group p-0 m-0">
                                    <li class="list-group-item text-danger">{{ $validation['row'] }}</li>

                                </ul>
                            </td>
                            <td>
                                <ul class="list-group p-0 m-0">
                                    <li class="list-group-item text-danger">{{$validation['attribute']}}</li>
                                </ul>
                            </td>
                            <td>
                                <ul class="list-group p-0 m-0">
                                    @foreach($validation['errors'] as $e)
                                        <li class="list-group-item text-danger">{{$e}}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <ul class="list-group p-0 m-0">
                                    <li class="list-group-item text-danger">{{$validation['values'] [$validation['attribute']] }}</li>
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                    <div class="mb-3">
                    @if($totalFailures > $perPage)
                        <div class="pagination-container">
                            <button wire:click="previousPage" class="pagination-button">السابق</button>
                            <span class="pagination-current-page">الصحفة {{ $currentPage }}</span>
                            <button wire:click="nextPage" class="pagination-button">التالي</button>
                        </div>
                    @endif
                    </div>
                </table>
                <div class="mt-3">
                    @if($totalFailures > $perPage)
                        <div class="pagination-container">
                            <button wire:click="previousPage" class="pagination-button">السابق</button>
                            <span class="pagination-current-page">الصحفة {{ $currentPage }}</span>
                            <button wire:click="nextPage" class="pagination-button">التالي</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
