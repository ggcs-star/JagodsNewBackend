@extends('admin.setting.index')

@section('admin.setting.breadcrumbs')
<div class="row">
	<div class="col-12">
		<div class="custome-breadcrumb">
		{{ Breadcrumbs::render('settings/page/add') }}
		</div>
	</div>
</div>
@endsection

@section('admin.setting.layout')

    <div class="row">
        <div class="col-12">
            <div class="db-card">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ __('levels.pages') }}</h3>
                </div>
                <div class="db-card-body">
                    <form action="{{ route('admin.page.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required">{{ __('levels.title') }}</label>
                                <input type="text" name="title"
                                    class="db-field-control @error('title') invalid @enderror" value="{{ old('title') }}">
                                @error('title')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required">{{ __('levels.footer_menu_section') }}</label>
                                <div class="db-field-down-arrow">
                                    <select name="footer_menu_section_id"
                                        class="db-field-control appearance-none @error('footer_menu_section_id') invalid @enderror">
                                        <option value="">{{ __('levels.select_section') }}</option>
                                        @if (!blank($footer_menu_sections))
                                            @foreach ($footer_menu_sections as $footer_menu_section)
                                                <option value="{{ $footer_menu_section->id }}"
                                                    {{ old('footer_menu_section_id') == $footer_menu_section->id ? 'selected' : '' }}>
                                                    {{ $footer_menu_section->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                @error('footer_menu_section_id')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required">{{ __('levels.template') }}</label>
                                <div class="db-field-down-arrow">
                                    <select name="template_id"
                                        class="db-field-control appearance-none @error('template_id') invalid @enderror">
                                        @if (!blank($templates))
                                            @foreach ($templates as $template)
                                                <option value="{{ $template->id }}"
                                                    {{ old('template_id') == $template->id ? 'selected' : '' }}>
                                                    {{ ucfirst($template->name) }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                @error('template_id')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col-12 sm:form-col-6">
                                <label class="db-field-title required">{{ __('levels.status') }}</label>
                                <div class="db-field-down-arrow">
                                    <select name="status"
                                        class="db-field-control appearance-none @error('status') invalid @enderror">
                                        @foreach (trans('statuses') as $statuskey => $status)
                                            <option value="{{ $statuskey }}"
                                                {{ old('status') == $statuskey ? 'selected' : '' }}>
                                                {{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('status')
                                    <small class="db-field-alert">{{ $message }}</small>
                                @enderror
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
    </script>
@endpush
