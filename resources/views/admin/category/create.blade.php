@extends('admin.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('backend/lib/summernote/summernote-bs4.css') }}">
@endpush

@section('content')
	
	<div class="row">
        <div class="col-12">
			<div class="custome-breadcrumb">
            {{ Breadcrumbs::render('categories/add') }}
			</div>
        </div>

		<div class="col-12">
			<div class="db-card">
				<div class="db-card-header">
					<h3 class="db-card-title">{{ __('restaurant.categories') }}</h3>
				</div>
				<div class="db-card-body">
					<form action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data">
						@csrf
						<div class="row">
							<div class="form-col-12 sm:form-col-6 md:form-col-4">
								<label class="db-field-title required">{{ __('levels.name') }}</label>
								<input type="text" name="name" class="db-field-control @error('name') invalid @enderror" value="{{ old('name') }}">
	
								@error('name')
								<small class="db-field-alert">{{ $message }}</small>
								@enderror
							</div>
							
							@if(auth()->user()->myrole == 1)
							<div class="form-col-12 sm:form-col-6 md:form-col-4">
								<label class="db-field-title required">{{ __('levels.status') }}</label>
								<div class="db-field-down-arrow">
									<select name="status" class="db-field-control appearance-none @error('status') invalid @enderror">
										<option value="">---</option>
										@foreach(trans('statuses') as $key => $status)
											<option value="{{ $key }}" {{ (old('status') == $key) ? 'selected' : '' }}>{{ $status }}</option>
										@endforeach
									</select>
								</div>
	
								@error('status')
								<small class="db-field-alert">{{ $message }}</small>
								@enderror
							</div>
							@endif

							<div class="form-col-12 sm:form-col-6 md:form-col-4">
								<label class="db-field-title" for="customFile">{{ __('restaurant.category_image') }}</label>
	
								<input type="file" name="image" id="customFile" class="db-field-control @error('image') invalid @enderror">
	
								@if ($errors->has('image'))
								<small class="db-field-alert">{{ $errors->first('image') }}</small>
								@endif
							</div>

							<div class="form-col-12">
								<label class="db-field-title">{{ __('levels.description') }}</label>
								<textarea name="description"
									class="db-field-control @error('description') invalid @enderror"
									id="editor" cols="30" rows="10">{{ old('description') }}</textarea>
								@error('description')
									<small class="db-field-alert">{{ $message }}</small>
								@enderror
							</div>
	
							<div class="col-12">
								<button type="submit" class="db-btn text-white bg-primary">
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
</script>
@endpush
