@extends('admin.app')

@push('css')
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
@endpush

@push('js')
    <!-- include FilePond library -->
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>

    <!-- include FilePond jQuery adapter -->
    <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>
    <!-- Qr Code Module Js -->
    <script src="{{ asset('js/qrBuilder/index.js') }}"></script>
@endpush

@section('content')

	<div class="row">
        <div class="col-12">
            <div class="custome-breadcrumb">
                {{ Breadcrumbs::render('qr-code') }}
            </div>
        </div>


        <div class="col-8 md:col-8 sm:col-12">
            <div class="db-card">
                <div class="db-card-body">
                    <form id="qrCodeFrom" action="{{ route('admin.qr-code.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                            <div class="">
                                <label class="custom-form-label">{{ __("qr_builder.qr_block_style") }}</label>
                                <div class="row custom-form-group">
                                    <div class="col-4 sm:col-3 mre-3">
                                        <label class="imagecheck mb-4">
                                        <input name="style" type="radio" value="square" class="imagecheck-input qr-input" {{ $restaurant->qrCode->style == "square" ? 'checked=""' : "" }}>
                                        <figure class="imagecheck-figure">
                                            <img src="{{ asset('images/qr/200-pixels.png') }}" alt="}" class="imagecheck-image">
                                        </figure>
                                        </label>
                                    </div>
                                    <div class="col-4 sm:col-3 mre-3">
                                        <label class="imagecheck mb-4">
                                        <input name="style" type="radio" value="dot" class="imagecheck-input qr-input" {{ $restaurant->qrCode->style == "dot" ? 'checked=""' : "" }}>
                                        <figure class="imagecheck-figure">
                                            <img src="{{ asset('images/qr/dot.png') }}" alt="}" class="imagecheck-image">
                                        </figure>
                                        </label>
                                    </div>
                                    <div class="col-4 sm:col-3 mre-3">
                                        <label class="imagecheck mb-4">
                                        <input name="style" type="radio" value="round" class="imagecheck-input qr-input" {{ $restaurant->qrCode->style == "round" ? 'checked=""' : "" }}>
                                        <figure class="imagecheck-figure">
                                            <img src="{{ asset('images/qr/round.png') }}" alt="}" class="imagecheck-image">
                                        </figure>
                                        </label>
                                    </div>
                                </div>

                                <label class="custom-form-label">{{ __("qr_builder.eye_style") }}</label>
                                <div class="row custom-form-group">
                                    <div class="col-4 sm:col-3 mre-3">
                                        <label class="imagecheck mb-4">
                                        <input name="eye_style" type="radio" value="square" class="imagecheck-input qr-input" {{ $restaurant->qrCode->eye_style == "square" ? 'checked=""' : "" }}>
                                        <figure class="imagecheck-figure">
                                            <img src="{{ asset('images/qr/200-pixels.png') }}" alt="}" class="imagecheck-image">
                                        </figure>
                                        </label>
                                    </div>
                                    <div class="col-4 sm:col-3 mre-3">
                                        <label class="imagecheck mb-4">
                                        <input name="eye_style" type="radio" value="circle" class="imagecheck-input qr-input" {{ $restaurant->qrCode->eye_style == "circle" ? 'checked=""' : "" }}>
                                        <figure class="imagecheck-figure">
                                            <img src="{{ asset('images/qr/circle-eye.png') }}" alt="}" class="imagecheck-image">
                                        </figure>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="flex">
                                <div class="md:col-6">
                                    <label class="custom-form-label">{{ __("qr_builder.color") }}</label>
                                    <div class="row custom-form-group">
                                    <div class="col-auto mre-2">
                                        <label class="colorinput">
                                        <input name="color" type="radio" value="0, 0, 0"  class="colorinput-input qr-input" {{ $restaurant->qrCode->color == "0, 0, 0" ? 'checked=""' : "" }}>
                                        <span class="colorinput-color bg-black" style="background-color: rgb(0, 0, 0)"></span>
                                        </label>
                                    </div>
                                    <div class="col-auto mre-2">
                                        <label class="colorinput">
                                        <input name="color" type="radio" value="255, 255, 255" class="colorinput-input qr-input" {{ $restaurant->qrCode->color == "255, 255, 255" ? 'checked=""' : "" }}>
                                        <span class="colorinput-color bg-white" style="background-color: rgb(255, 255, 255)"></span>
                                        </label>
                                    </div>
                                    <div class="col-auto mre-2">
                                        <label class="colorinput">
                                        <input name="color" type="radio" value="205, 211, 216" class="colorinput-input qr-input" {{ $restaurant->qrCode->color == "205, 211, 216" ? 'checked=""' : "" }}>
                                        <span class="colorinput-color bg-secondary" style="background-color: rgb(205, 211, 216)"></span>
                                        </label>
                                    </div>
                                    <div class="col-auto mre-2">
                                        <label class="colorinput">
                                        <input name="color" type="radio" value="255, 164, 38" class="colorinput-input qr-input" {{ $restaurant->qrCode->color == "255, 164, 38" ? 'checked=""' : "" }}>
                                        <span class="colorinput-color bg-warning" style="background-color: rgb(255, 164, 38)"></span>
                                        </label>
                                    </div>
                                    <div class="col-auto mre-2">
                                        <label class="colorinput">
                                        <input name="color" type="radio" value="255, 193, 7" class="colorinput-input qr-input" {{ $restaurant->qrCode->color == "255, 193, 7" ? 'checked=""' : "" }}>
                                        <span class="colorinput-color bg-yellow" style="background-color: rgb(255, 193, 7)"></span>
                                        </label>
                                    </div>
                                    </div>
                                    <div class="row custom-form-group">
                                    <div class="col-auto mre-2">
                                        <label class="colorinput">
                                        <input name="color" type="radio" value="71, 195, 99" class="colorinput-input qr-input" {{ $restaurant->qrCode->color == "71, 195, 99" ? 'checked=""' : "" }}>
                                        <span class="colorinput-color bg-success" style="background-color: rgb(71, 195, 99)"></span>
                                        </label>
                                    </div>
                                    <div class="col-auto mre-2">
                                        <label class="colorinput">
                                        <input name="color" type="radio" value="103, 119, 239" class="colorinput-input qr-input" {{ $restaurant->qrCode->color == "103, 119, 239" ? 'checked=""' : "" }}>
                                        <span class="colorinput-color bg-primary" style="background-color: rgb(103, 119, 239)"></span>
                                        </label>
                                    </div>

                                    <div class="col-auto mre-2">
                                        <label class="colorinput">
                                        <input name="color" type="radio" value="58, 186, 244" class="colorinput-input qr-input" {{ $restaurant->qrCode->color == "58, 186, 244" ? 'checked=""' : "" }}>
                                        <span class="colorinput-color bg-info" style="background-color: rgb(58, 186, 244)"></span>
                                        </label>
                                    </div>
                                    <div class="col-auto mre-2">
                                        <label class="colorinput">
                                        <input name="color" type="radio" value="232, 62, 140" class="colorinput-input qr-input" {{ $restaurant->qrCode->color == "232, 62, 140" ? 'checked=""' : "" }}>
                                        <span class="colorinput-color bg-pink" style="background-color: rgb(232, 62, 140)"></span>
                                        </label>
                                    </div>
                                    <div class="col-auto mre-2">
                                        <label class="colorinput">
                                        <input name="color" type="radio" value="252, 84, 75" class="colorinput-input qr-input" {{ $restaurant->qrCode->color == "252, 84, 75" ? 'checked=""' : "" }}>
                                        <span class="colorinput-color bg-danger" style="background-color: rgb(252, 84, 75)"></span>
                                        </label>
                                    </div>
                                    </div>
                                </div>
                                <div class="md:col-6">
                                    <label class="custom-form-label">{{ __("qr_builder.background_color") }}</label>
                                    <div class="row custom-form-group">
                                    <div class="col-auto mre-2">
                                        <label class="colorinput">
                                        <input name="background_color" type="radio" value="255, 255, 255" class="colorinput-input qr-input"  {{ $restaurant->qrCode->color == "252, 84, 75" ? 'checked=""' : "" }}>
                                        <span class="colorinput-color bg-white" style="background-color: rgb(255, 255, 255)"></span>
                                        </label>
                                    </div>
                                    <div class="col-auto mre-2">
                                        <label class="colorinput">
                                        <input name="background_color" type="radio" value="0, 0, 0"  class="colorinput-input qr-input"
                                            {{ $restaurant->qrCode->background_color == "0, 0, 0" ? 'checked=""' : "" }}>
                                        <span class="colorinput-color bg-black" style="background-color: rgb(0, 0, 0)"></span>
                                        </label>
                                    </div>
                                    <div class="col-auto mre-2">
                                        <label class="colorinput">
                                        <input name="background_color" type="radio" value="205, 211, 216" class="colorinput-input qr-input"
                                            {{ $restaurant->qrCode->background_color == "205, 211, 216" ? 'checked=""' : "" }}>
                                        <span class="colorinput-color bg-secondary" style="background-color: rgb(205, 211, 216)"></span>
                                        </label>
                                    </div>
                                    <div class="col-auto mre-2">
                                        <label class="colorinput">
                                        <input name="background_color" type="radio" value="255, 164, 38" class="colorinput-input qr-input"
                                            {{ $restaurant->qrCode->background_color == "255, 164, 38" ? 'checked=""' : "" }}>
                                        <span class="colorinput-color bg-warning" style="background-color: rgb(255, 164, 38)"></span>
                                        </label>
                                    </div>
                                    <div class="col-auto mre-2">
                                        <label class="colorinput">
                                        <input name="background_color" type="radio" value="255, 193, 7" class="colorinput-input qr-input"
                                            {{ $restaurant->qrCode->background_color == "255, 193, 7" ? 'checked=""' : "" }}>
                                        <span class="colorinput-color bg-yellow" style="background-color: rgb(255, 193, 7)"></span>
                                        </label>
                                    </div>
                                    </div>
                                    <div class="row custom-form-group">
                                    <div class="col-auto mre-2">
                                        <label class="colorinput">
                                        <input name="background_color" type="radio" value="71, 195, 99" class="colorinput-input qr-input"
                                            {{ $restaurant->qrCode->background_color == "71, 195, 99" ? 'checked=""' : "" }}>
                                        <span class="colorinput-color bg-success" style="background-color: rgb(71, 195, 99)"></span>
                                        </label>
                                    </div>
                                    <div class="col-auto mre-2">
                                        <label class="colorinput">
                                        <input name="background_color" type="radio" value="103, 119, 239" class="colorinput-input qr-input"
                                            {{ $restaurant->qrCode->background_color == "103, 119, 239" ? 'checked=""' : "" }}>
                                        <span class="colorinput-color bg-primary" style="background-color: rgb(103, 119, 239)"></span>
                                        </label>
                                    </div>

                                    <div class="col-auto mre-2">
                                        <label class="colorinput">
                                        <input name="background_color" type="radio" value="58, 186, 244" class="colorinput-input qr-input"
                                            {{ $restaurant->qrCode->background_color == "58, 186, 244" ? 'checked=""' : "" }}>
                                        <span class="colorinput-color bg-info" style="background-color: rgb(58, 186, 244)"></span>
                                        </label>
                                    </div>
                                    <div class="col-auto mre-2">
                                        <label class="colorinput">
                                        <input name="background_color" type="radio" value="232, 62, 140" class="colorinput-input qr-input"
                                            {{ $restaurant->qrCode->background_color == "232, 62, 140" ? 'checked=""' : "" }}>
                                        <span class="colorinput-color bg-pink" style="background-color: rgb(232, 62, 140)"></span>
                                        </label>
                                    </div>
                                    <div class="col-auto mre-2">
                                        <label class="colorinput">
                                        <input name="background_color" type="radio" value="252, 84, 75" class="colorinput-input qr-input"
                                            {{ $restaurant->qrCode->background_color == "252, 84, 75" ? 'checked=""' : "" }}>
                                        <span class="colorinput-color bg-danger" style="background-color: rgb(252, 84, 75)"></span>
                                        </label>
                                    </div>
                                    </div>
                                </div>
                            </div>
    
                            <div class="">
                                <label class="custom-form-label mrb-2" for="seeAnotherField">{{ __("qr_builder.qr_code_mode") }}</label>
                                <div class="db-field-down-arrow">
                                    <select class="db-field-control appearance-none qr-input" id="seeAnotherField" name="mode">
                                        <option value="none">{{ __("qr_builder.none") }}</option>
                                        <option value="logo" {{ $restaurant->qrCode->mode == "logo" ? 'selected' : "" }}>{{ __("Logo") }}</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Hidden fields -->
                            <div class="" id="qrCodeLogoDiv">
                                <label class="custom-form-label mrb-2 mrt-2" for="filepond">{{ __("qr_builder.logo") }}</label>
                                <div class="row gutters-sm">
                                    <div class="col-6 col-sm-9">
                                        <input id="qrCodeLogo" type="file" class="my-pond" name="file"/>
                                    </div>
                                </div>
                            </div>
                            <!-- /Hidden fields -->
                            <button class="db-btn text-white bg-primary mrt-3" type="submit">{{ __('qr_builder.save_changes') }}</button>
                            
                        
                    </form>
                </div>
            </div>
        </div>

        <div class="col-4 md:col-4 sm:col-12">
            <div class="db-card shadow-sm" id="qrPreview">
                <div class="db-card-body m-0">
                    <img id="qrImage" width="100%" class="bd-placeholder-img card-img-top img-fluid" src="data:image/png;base64, {!! $qrCode !!} ">
                    <div class="mrt-3">
                        <a href="data:image/png;base64, {!! $qrCode !!}" class="db-btn btn-sm btn-outline-secondary bg-primary text-white" id="download" download>
                            {{ __("levels.download") }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
