@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row">

        <div class="form-container small-6 small-centered columns">

            <div class="form-title text-center">
                Dashboard
            </div>

            <div class="callout">
				<ul class="vertical menu">
					<div>
						@if (Session::get('server_error'))
							<span class="label alert">{{ Session::get('server_error') }}</span>
						@endif
						@if (isset($raw_description))
							<div class="callout secondary">
								<span class="label success">Transaction successful</span>
								<!-- raw description -->
								<div>
									{!! $raw_description !!}
								</div>
							</div>
						@endif
					</div>
					<li><a href="{{ route('mtn_deposit') }}">Deposit MTN Money</a></li>
					<li><a href="{{ route('mtn_payment') }}">MTN Bill Payment API initiated by Partner</a></li>
				</ul>
            </div>

        </div>

    </div>

</div>

@endsection
