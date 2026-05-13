@extends('admin.app')

@section('content')

<div class="row">
	<div class="col-12">
		<div class="custome-breadcrumb">
		{{ Breadcrumbs::render('expense/edit') }}
		</div>
	</div>

    <div class="col-12">
		<div class="db-card">
			<div class="db-card-header">
				<h3 class="db-card-title">{{ __('user.expense') }}</h3>
			</div>
			<div class="db-card-body">
				<form action="{{ route('admin.expense.update', $expense) }}" method="POST" enctype="multipart/form-data">
					@csrf
                    @method('PUT')
					<div class="row">
						<div class="form-col-12 sm:form-col-6 md:form-col-4">
							<label class="db-field-title required">{{ __('levels.title') }}</label>
							<input type="text" name="title" class="db-field-control @error('title') invalid @enderror" value="{{ old('title', $expense->title) }}">

							@error('title')
							<small class="db-field-alert">{{ $message }}</small>
							@enderror
						</div>

						<div class="form-col-12 sm:form-col-6 md:form-col-4">
							<label class="db-field-title required">{{ __('levels.amount') }}</label>
							<input type="text" name="amount" class="db-field-control @error('amount') invalid @enderror" value="{{ old('amount', $expense->amount) }}">

							@error('amount')
							<small class="db-field-alert">{{ $message }}</small>
							@enderror
						</div>

						<div class="form-col-12 sm:form-col-6 md:form-col-4">
							<label class="db-field-title required">{{ __('levels.date') }}</label>
							<input type="date" name="date" class="db-field-control @error('date') invalid @enderror" value="{{ old('date', date('Y-m-d', strtotime($expense->expense_date))) }}">

							@error('date')
							<small class="db-field-alert">{{ $message }}</small>
							@enderror
						</div>

						

						<div class="form-col-12 sm:form-col-6 md:form-col-4">
							<label class="db-field-title" for="customFile">{{ __('levels.attachment') }}</label>

							<input type="file" name="attachment" id="customFile" class="db-field-control custom-file-input @error('attachment') invalid @enderror" onchange="readURL(this);">

							@if ($errors->has('attachment'))
							<small class="db-field-alert">{{ $errors->first('attachment') }}</small>
							@endif
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
<script src="{{ asset('js/expense/create.js') }}"></script>
@endpush