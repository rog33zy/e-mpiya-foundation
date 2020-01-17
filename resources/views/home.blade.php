@extends('layouts.app')

@section('content')
	<div class="medium-8 small-12 columns">

		<div class="form-title text-center">
			Dashboard
		</div>

		<div class="callout medium-12 column">
			@if (Session::get('server_error'))
				<span class="label alert">{{ Session::get('server_error') }}</span>
			@endif
			@if (isset($string_response))
				<div class="medium-6 column">
					<div class="callout secondary medium-12 column">
						<h4>String Response</h4>
						<span class="label success">Transaction successful</span>
						<!-- string response -->
						<div>
							{!! $string_response !!}
						</div>
					</div>
				</div>
			@endif
			@if (isset($string_response))
				<div class="medium-6 column">
					<div class="callout secondary medium-12 column">
						<h4>JSON Response</h4>
						<span class="label success">Transaction successful</span>
						<!-- JSON response -->
						<div>
							{{ $json_response }}
						</div>
					</div>
				</div>
			@endif
			<div class="medium-12 column">
				<ul class="vertical menu">
					<h4>MTN Old SOAP API</h4>
					<li><a href="{{ route('mtn_deposit') }}">Deposit MTN Money</a></li>
					<li><a href="{{ route('mtn_payment') }}">MTN Bill Payment API initiated by Partner</a></li>
					<h4>MTN New MoMoS JSON API</h4>
					<li><a href="{{ route('mtn_collection_widget') }}">Collection Widget</a></li>
					<li><a href="{{ route('mtn_collections') }}">Collections</a></li>
					<li><a href="{{ route('mtn_disbursements') }}">Disbursements</a></li>
					<li><a href="{{ route('mtn_remittances') }}">Remittances</a></li>
					<h4>Flutterwave</h4>
					<li><a href="{{ route('rave_inline') }}">Rave Inline</a></li>
					<li><a href="{{ route('rave_standard') }}">Rave Standard</a></li>
				</ul>
			</div>
		</div>

	</div>
@endsection
