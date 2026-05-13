@extends('admin.setting.index')

@section('admin.setting.breadcrumbs')
<div class="row">
	<div class="col-12">
		<div class="custome-breadcrumb">
		{{ Breadcrumbs::render('settings/role/add') }}
		</div>
	</div>
</div>
@endsection

@section('admin.setting.layout')
	
	<div class="row">


		<div class="col-12">
			<div class="db-card">
				<div class="db-card-header">
					<h3 class="db-card-title">{{ __('levels.roles') }}</h3>
				</div>
				<div class="db-card-body">
					<form action="{{ route('admin.role.store') }}" method="POST">
						@csrf
						<div class="row">
							<div class="form-col-12 sm:form-col-6 md:form-col-4">
								<label class="db-field-title required">{{ __('levels.name') }}</label> 
								<input type="text" name="name" class="db-field-control @error('name') invalid @enderror" value="{{ old('name') }}">
								@error('name')
									<small class="db-field-alert">
										{{ $message }}
									</small>
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
