@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row">

        <div class="large-12 medium-12 small-12 form-container columns">

            <div class="form-title text-center">
                <h4>{{ $header }}</h4>
            </div>
				<div class="contents">
					<div class="large-12 medium-12 small-12 column mtn-deposit-header my-callout">
						<h4>API App Settings</h4>
					</div>
					<div class="large-12 medium-12 small-12 mtn-deposit column">
						@if (Session::get('server_error'))
							<div class="large-12 medium-12 small-12 column"><span class="label alert">{{ Session::get('server_error') }}</span></div>
						@endif
                        @if(isset($api_data))
						<form action="{{ route('post_mtn_api_settings') }}" method="POST">
							{{ csrf_field() }}
							<!-- Provider Name -->
							<div class="large-12 medium-12 small-12 columns">
								<div class="row collapse prefix-radius">
									<div class="medium-3 columns">
										<span class="prefix"><strong>Provider Name</strong></span>
									</div>
									<div class="medium-9 column">
										<input type="text" name="provider" placeholder="Provider Name" value="{{ $api_data->provider }}" />
									</div>
								</div>
								@if (count($errors) > 0)
									@foreach ($errors->get('provider') as $error)
										<div class="large-12 medium-12 small-12 column"><span class="label alert">{{ $error }}</span></div>
									@endforeach
								@endif
								<span class="label success">(Required) Provider Name</span>
							</div>
							<!-- API User -->
							<div class="large-12 medium-12 small-12 columns">
								<div class="row collapse prefix-radius">
									<div class="medium-3 columns">
										<span class="prefix"><strong>API User</strong></span>
									</div>
									<div class="medium-9 column">
										<input type="text" name="api_user" placeholder="API User" value="{{ $api_data->api_user }}" />
									</div>
								</div>
								@if (count($errors) > 0)
									@foreach ($errors->get('api_user') as $error)
										<div class="large-12 medium-12 small-12 column"><span class="label alert">{{ $error }}</span></div>
									@endforeach
								@endif
								<span class="label success">(Required) API User</span>
							</div>
							<!-- API Key -->
							<div class="large-12 medium-12 small-12 columns">
								<div class="row collapse prefix-radius">
									<div class="medium-3 columns">
										<span class="prefix"><strong>API Key</strong></span>
									</div>
									<div class="medium-9 column">
										<input type="text" name="api_key" placeholder="API Key" value="{{ $api_data->api_key }}" />
									</div>
								</div>
								@if (count($errors) > 0)
									@foreach ($errors->get('api_key') as $error)
										<div class="large-12 medium-12 small-12 column"><span class="label alert">{{ $error }}</span></div>
									@endforeach
								@endif
								<span class="label success">(Required) API Key</span>
							</div>
							<!-- Basic Auth -->
							<div class="large-12 medium-12 small-12 columns">
								<div class="row collapse prefix-radius">
									<div class="medium-3 columns">
										<span class="prefix"><strong>Basic Auth Token</strong></span>
									</div>
									<div class="medium-9 column">
										<input type="text" name="basic_auth" placeholder="API Key" value="{{ $api_data->basic_auth }}" />
									</div>
								</div>
								@if (count($errors) > 0)
									@foreach ($errors->get('basic_auth') as $error)
										<div class="large-12 medium-12 small-12 column"><span class="label alert">{{ $error }}</span></div>
									@endforeach
								@endif
								<span class="label success">(Required) Basic Auth</span>
							</div>
							<!-- Callback URL -->
							<div class="large-12 medium-12 small-12 columns">
								<div class="row collapse prefix-radius">
									<div class="medium-3 columns">
										<span class="prefix"><strong>Callback URL</strong></span>
									</div>
									<div class="medium-9 column">
										<input type="text" name="callback_url" placeholder="API Key" value="{{ $api_data->callback_url }}" />
									</div>
								</div>
								@if (count($errors) > 0)
									@foreach ($errors->get('callback_url') as $error)
										<div class="large-12 medium-12 small-12 column"><span class="label alert">{{ $error }}</span></div>
									@endforeach
								@endif
								<span class="label success">(Optional) Callback URL</span>
							</div>
							<div class="large-12 medium-12 small-12 columns">
								<div class="expanded button-group">
									<input type="submit" value="Update" class="button" />
									<a href="{{ url()->previous() }}" class="button">Cancel</a>
								</div>
							</div>
						</form>
                        @else
                            <p>Generate App Settings</p>
                        @endif
					</div>
				</div>

        </div>

    </div>

</div>

@endsection
