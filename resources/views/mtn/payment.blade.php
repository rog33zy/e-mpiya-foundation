@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row">

        <div class="large-12 medium-12 small-12 form-container columns">

            <div class="form-title text-center">
                Dashboard
            </div>
				<div class="contents">
					<div class="large-12 medium-12 small-12 column mtn-payment-header my-callout">
						<h4>Bill Payment API initiated by Partner</h4>
					</div>
					<div class="large-12 medium-12 small-12 mtn-payment column">
						@if (Session::get('server_error'))
							<div class="large-12 medium-12 small-12 column"><span class="label alert">{{ Session::get('server_error') }}</span></div>
						@endif
						<form action="{{ route('post_mtn_payment') }}" method="POST">
							{{ csrf_field() }}
							<!-- From MTN Money Number -->
							<div class="large-12 medium-12 small-12 columns">
								<div class="row collapse prefix-radius">
									<div class="medium-3 columns">
										<span class="prefix"><strong>From MTN Money Number*</strong></span>
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
								<div class="large-12 medium-12 small-12 column"><span class="label success">(Required) MTN Money Mobile Number To Pay From, e.g: 096x xxx xxx</span></div>
							</div>
							<!-- Amount To Pay From MTN Money Account -->
							<div class="large-12 medium-12 small-12 columns">
								<div class="row collapse prefix-radius">
									<div class="medium-3 columns">
										<span class="prefix"><strong>Amount*</strong></span>
									</div>
									<div class="medium-9 column">
										<input type="text" name="pay_amount" placeholder="Amount" value="{{ old('pay_amount') }}" />
									</div>
								</div>
								@if (count($errors) > 0)
									@foreach ($errors->get('pay_amount') as $error)
										<div class="large-12 medium-12 small-12 column"><span class="label alert">{{ $error }}</span></div>
									@endforeach
								@endif
								<div class="large-12 medium-12 small-12 column"><span class="label success">(Required) Amount To Pay</span></div>
							</div>
							<div class="large-12 medium-12 small-12 columns">
								<div class="expanded button-group">
									<input type="submit" value="Pay Now" class="button" />
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
