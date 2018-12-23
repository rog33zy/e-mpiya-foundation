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

                <div class="username">
                    <label for="username">Username</label>

                    <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" aria-describedby="nameHelpText" required autofocus>

                    @if ($errors->has('username'))
                        <span class="help-text" id="nameHelpText">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                    @endif
                </div>

      					<div>
      						<span class="prefix"><strong>User Type</strong></span>
      						<div>
      							Company <input type="radio" name="user_type_id" value="1" />
      							Group <input type="radio" name="user_type_id" value="2" />
      							Individual <input type="radio" name="user_type_id" value="3" />
      						</div>
      						@if (count($errors) > 0)
      							@foreach ($errors->get('user_type_id') as $error)
      								<span class="label alert">{{ $error }}</span>
      							@endforeach
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
