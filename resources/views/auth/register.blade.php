@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row">

        <div class="form-container small-6 small-centered columns">

            <div class="form-title text-center">
                Register
            </div>

            <form class="register-form" method="POST" action="{{ route('register') }}">

                {{ csrf_field() }}

                <div class="name">
                    <label for="email">Name</label>

                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" aria-describedby="nameHelpText" required autofocus>

                    @if ($errors->has('name'))
                        <span class="help-text" id="nameHelpText">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="email">
                    <label for="email">E-Mail Address</label>

                    <input id="email" type="email" name="email" value="{{ old('email') }}" aria-describedby="emailHelpText" required>

                    @if ($errors->has('email'))
                        <span class="help-text" id="emailHelpText">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
				
				<!-- Mobile Network Operator -->
				<div class="large-12 medium-12 small-12 columns">
					<span class="prefix"><strong>Mobile Network</strong></span>
					<div>
						Airtel <input type="radio" name="mobile_network" id="mobile-network" class="mobile-network" value="Airtel" />
						MTN <input type="radio" name="mobile_network" id="mobile-network" class="mobile-network" value="MTN" />
						Zamtel <input type="radio" name="mobile_network" id="mobile-network" class="mobile-network" value="Zamtel" />
						Other <input type="radio" name="mobile_network" id="mobile-network" class="mobile-network" value="Other" />
					</div>
					@if (count($errors) > 0)
						@foreach ($errors->get('mobile_network') as $error)
							<span class="error">{{ $error }}</span>
						@endforeach
					@endif
					<span class="optional">(Required) Your Mobile Network Provider</span>
				</div>
				<!-- Mobile Number -->
				<div class="large-12 medium-12 small-12 columns">
					<div class="row collapse prefix-radius">
						<div class="medium-3 columns">
							<span id="mobile-number" class="prefix"><strong>Mobile Number</strong></span>
						</div>
						<div class="medium-9 column">
							<input id="user-mobile" type="text" name="Number" placeholder="Mobile Number" />
							<input type="hidden" name="MobileNetwork" />
						</div>
					</div>
					@if (count($errors) > 0)
						@foreach ($errors->get('Airtel') as $error)
							<span class="error">{{ $error }}</span>
						@endforeach
					@endif
					@if (count($errors) > 0)
						@foreach ($errors->get('MTN') as $error)
							<span class="error">{{ $error }}</span>
						@endforeach
					@endif
					@if (count($errors) > 0)
						@foreach ($errors->get('Zamtel') as $error)
							<span class="error">{{ $error }}</span>
						@endforeach
					@endif
					@if (count($errors) > 0)
						@foreach ($errors->get('Other') as $error)
							<span class="error">{{ $error }}</span>
						@endforeach
					@endif
					@if (count($errors) > 0)
						@foreach ($errors->get('MobileNetwork') as $error)
							<span class="error">{{ $error }}</span>
						@endforeach
					@endif
					<span class="optional">(Optional) Your Mobile Number, e.g. 095xxxxxxx or 096xxxxxxx or 097xxxxxxx</span>
				</div>

                <div class="password">
                    <label for="password">Password</label>

                    <input id="password" type="password" name="password" aria-describedby="passwordHelpText" required>

                    @if ($errors->has('password'))
                        <span class="help-text" id="passwordHelpText">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="password-confirm">
                    <label for="password-confirm">Confirm Password</label>
                    <input id="password-confirm" type="password" name="password_confirmation" required>
                </div>

                <div class="register_button">
                    <button type="submit" class="button">
                        Register
                    </button>
                </div>


            </form>

        </div>

    </div>

</div>

@endsection
