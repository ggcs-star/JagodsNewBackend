@extends('admin.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('addons/add') }}
            </div>
        </div>
        <div class="col-12">
            <div class="db-card">
                <div class="db-card-header">
                    <h3 class="db-card-title">{{ __('addon.addons') }}</h3>
                </div>
                <div class="db-card-body">
                    <form action="{{ route('admin.addons.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-col-12 sm:form-col-6 md:form-col-4">
                                <label class="db-field-title" for="addon_file">{{ __('addon.file') }}</label>
                                <input name="addon_file" type="file"
                                    class="db-field-control @error('addon_file') invalid @enderror" id="addon_file">
                                @if ($errors->has('addon_file'))
                                    <small class="db-field-alert">{{ $errors->first('addon_file') }}</small>
                                @endif
                            </div>
                            <div class="form-col-12">
                                <button class="db-btn text-white bg-primary" type="submit">
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
