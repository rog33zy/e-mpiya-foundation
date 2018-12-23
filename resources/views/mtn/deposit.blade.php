@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row">

        <div class="large-12 medium-12 small-12 form-container columns">

            <div class="form-title text-center">
                Dashboard
            </div>
				<div class="contents">
					<div class="large-12 medium-12 small-12 column mtn-deposit-header my-callout">
						<h4>Deposit To MTN Account</h4>
					</div>
					<div class="large-12 medium-12 small-12 mtn-deposit column">
						@if (Session::get('server_error'))
							<div class="large-12 medium-12 small-12 column"><span class="label alert">{{ Session::get('server_error') }}</span></div>
						@endif
						<form action="{{ route('post_mtn_deposit') }}" method="POST">
							{{ csrf_field() }}
							<!-- From MTN Money Number -->
							<div class="large-12 medium-12 small-12 columns">
								<div class="row collapse prefix-radius">
									<div class="medium-3 columns">
										<span class="prefix"><strong>MTN Money Number*</strong></span>
									</div>
									<div class="medium-9 column">
										<input type="text" name="mtn_money_number" placeholder="MTN Money Number" value="{{ $user_mobile->mobile_number }}" />
									</div>
								</div>
								@if (count($errors) > 0)
									@foreach ($errors->get('mtn_money_number') as $error)
										<div class="large-12 medium-12 small-12 column"><span class="label alert">{{ $error }}</span></div>
									@endforeach
								@endif
								<span class="label success">(Required) MTN Money Mobile Number To Deposit To, e.g: 096x xxx xxx</span>
							</div>
							@if (isset($user_mobile))
								@if ($user_mobile->mobile_service_provider_id != 2)
									<p>Please add your MTN Mobile Money number to your profile: <a href="{{ route('edit-user-profile', Auth::user()->username_slug) }}" title="Add MTN Number">Add MTN Number</a> </p>
								@endif
							@endif
							<!-- Amount To Deposit From MTN Money -->
							<div class="large-12 medium-12 small-12 columns">
								<div class="row collapse prefix-radius">
									<div class="medium-3 columns">
										<span class="prefix"><strong>Amount*</strong></span>
									</div>
									<div class="medium-9 column">
										<input type="text" name="deposit_amount" placeholder="Amount" value="{{ old('deposit_amount') }}" />
									</div>
								</div>
								@if (count($errors) > 0)
									@foreach ($errors->get('deposit_amount') as $error)
										<div class="large-12 medium-12 small-12 column"><span class="label alert">{{ $error }}</span></div>
									@endforeach
								@endif
								<span class="label success">(Required) Amount To Deposit From MTN Money</span>
							</div>
							<div class="large-12 medium-12 small-12 columns">
								<div class="expanded button-group">
									<input type="submit" value="Deposit" class="button" />
									<a href="{{ url()->previous() }}" class="button">Cancel</a>
								</div>
							</div>
						</form>
					</div>
				</div>

        </div>

    </div>

</div>

@endsection
