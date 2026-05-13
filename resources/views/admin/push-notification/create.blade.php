@extends('admin.app')

@section('content')
    <div class="col-12">
        <div class="custome-breadcrumb">
            {{ Breadcrumbs::render('push-notification/add') }}
        </div>
    </div>
    <div class="col-12">
        <div class="db-card">
            <div class="db-card-header">
                <h3 class="db-card-title">{{ __('push_notification.push_notification') }}</h3>
            </div>
            <div class="db-card-body">
                <form action="{{ route('admin.push-notification.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="form-col-12 sm:form-col-6">
                            @if (auth()->user()->myrole != App\Enums\UserRole::RESTAURANTOWNER)
                                <label class="db-field-title" for="restaurant_id">{{ __('levels.restaurant') }}</label>
                                <div class="db-field-down-arrow">
                                    <select name="restaurant_id" id="restaurant_id"
                                        class="db-field-control appearance-none @error('restaurant_id') invalid @enderror">
                                        <option value="">{{ __('Select Restaurant') }}</option>
                                        @if (!blank($restaturants))
                                            @foreach ($restaturants as $restaturant)
                                                <option value="{{ $restaturant->id }}"
                                                    {{ old('restaturant_id') == $restaturant->id ? 'selected' : '' }}>
                                                    {{ $restaturant->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                @error('restaturant_id')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            @else
                                <input type="hidden" name="restaurant_id"
                                    value="{{ auth()->user()->restaurant->id ?? 0 }}">
                            @endif
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title required">{{ __('levels.title') }}</label>
                            <input type="text" name="title" class="db-field-control @error('title') invalid @enderror"
                                value="{{ old('title') }}">
                            @error('title')
                                <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title">{{ __('Select Customer') }}</label>
                            <div class="db-field-down-arrow">
                                <select name="customer_id" id="area"
                                    class="db-field-control appearance-none @error('customer_id') invalid @enderror">
                                    <option value="0">{{ __('All Customer') }}</option>
                                    @if (!blank($customers))
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}"
                                                {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            @error('customer_id')
                                <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-col-12 sm:form-col-6">
                            <label class="db-field-title" for="customFile">{{ __('levels.image') }}</label>
                            <input name="image" type="file" class="db-field-control @error('image') invalid @enderror"
                                id="customFile" onchange="readURL(this,'previewImage');">
                            @if ($errors->has('image'))
                                <small class="db-field-alert">{{ $errors->first('image') }}</small>
                            @endif
                        </div>
                        <div class="form-col-12">
                            <label class="db-field-title required">{{ __('levels.description') }}</label>
                            <textarea name="description" class="db-field-control @error('description') invalid @enderror" id="editor"
                                cols="30" rows="10">{{ old('description') }}</textarea>
                            @error('description')
                                <small class="db-field-alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-col-12">
                            <button class="db-btn text-white bg-primary">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>{{ __('levels.save') }}</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

@endsection

@push('js')
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'), {
                editorContainer: {
                    height: '500px',
                    width: '100%',
                }
            })
            .then(editor => {
                editor.ui.view.editable.element.style.height = "130px";
                editor.ui.view.editable.element.style.overflow = "auto";
            })
            .catch(error => {
                console.error(error);
            });
        // Add the following code if you want the name of the file appear on select
        $(".custom-file-input").on("change", function() {
            let fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    </script>
@endpush
