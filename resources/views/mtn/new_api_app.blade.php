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
						@if (Session::get('create_error'))
							<div class="large-12 medium-12 small-12 column"><span class="label alert">{{ Session::get('create_error') }}</span></div>
						@endif
                        <form action="{{ route('post_new_mtn_app') }}" method="POST">
                            {{ csrf_field() }}
                            <!-- Provider Name -->
                            <div class="large-12 medium-12 small-12 columns">
                                <div class="row collapse prefix-radius">
                                    <div class="medium-3 columns">
                                        <span class="prefix"><strong>Provider Name</strong></span>
                                    </div>
                                    <div class="medium-9 column">
                                        <input type="text" name="provider" placeholder="Provider Name" value="{{ old('provider') }}" />
                                    </div>
                                </div>
                                @if (count($errors) > 0)
                                    @foreach ($errors->get('provider') as $error)
                                        <div class="large-12 medium-12 small-12 column"><span class="label alert">{{ $error }}</span></div>
                                    @endforeach
                                @endif
                                <span class="label success">(Required) Provider Name</span>
                            </div>
                            <!-- Callback URL -->
                            <div class="large-12 medium-12 small-12 columns">
                                <div class="row collapse prefix-radius">
                                    <div class="medium-3 columns">
                                        <span class="prefix"><strong>Callback URL</strong></span>
                                    </div>
                                    <div class="medium-9 column">
                                        <input type="text" name="callback_url" placeholder="Callback URL" value="{{ old('callback_url') }}" />
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
                                    <input type="submit" value="Create" class="button" />
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
