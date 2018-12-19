@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row">

        <div class="form-container small-12 small-centered columns">

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
						<li><a href="{{ route('mtn_deposit') }}">Deposit MTN Money</a></li>
						<li><a href="{{ route('mtn_payment') }}">MTN Bill Payment API initiated by Partner</a></li>
					</ul>
				</div>
            </div>

        </div>

    </div>

</div>

@endsection
