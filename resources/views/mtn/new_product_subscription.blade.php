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
                        <form action="{{ route('post_new_mtn_api_product_subscription') }}" method="POST">
                            {{ csrf_field() }}
							<!-- Select API App -->
							<div class="large-12 medium-12 small-12 columns">
								<label>Select the registered API App to use:</label>
								<select name="api_provider">
									@foreach($api_providers as $api_provider)
										<option value="{{$api_provider->id}}">{{ $api_provider->provider }}</option>
									@endforeach
								</select>
								@if (count($errors) > 0)
									@foreach ($errors->get('api_provider') as $error)
										<span class="error">{{ $error }}</span>
									@endforeach
								@endif
								<span class="required">(Required) Select the registered MTN API App to use with this subscription</span>
							</div>
                            <!-- Product Subscription Name -->
                            <div class="large-12 medium-12 small-12 columns">
                                <div class="row collapse prefix-radius">
                                    <div class="medium-3 columns">
                                        <span class="prefix"><strong>Product Subscription Name</strong></span>
                                    </div>
                                    <div class="medium-9 column">
                                        <input type="text" name="product" placeholder="Product Subscription Name" value="{{ old('product') }}" />
                                    </div>
                                </div>
                                @if (count($errors) > 0)
                                    @foreach ($errors->get('product') as $error)
                                        <div class="large-12 medium-12 small-12 column"><span class="label alert">{{ $error }}</span></div>
                                    @endforeach
                                @endif
                                <span class="label success">(Required) Product Subscription Name</span>
                            </div>
                            <!-- Primary Key -->
                            <div class="large-12 medium-12 small-12 columns">
                                <div class="row collapse prefix-radius">
                                    <div class="medium-3 columns">
                                        <span class="prefix"><strong>Primary Key</strong></span>
                                    </div>
                                    <div class="medium-9 column">
                                        <input type="text" name="primary_key" placeholder="Primary Key" value="{{ old('primary_key') }}" />
                                    </div>
                                </div>
                                @if (count($errors) > 0)
                                    @foreach ($errors->get('primary_key') as $error)
                                        <div class="large-12 medium-12 small-12 column"><span class="label alert">{{ $error }}</span></div>
                                    @endforeach
                                @endif
                                <span class="label success">(Required) Provider Name</span>
                            </div>
                            <!-- Secondary Key -->
                            <div class="large-12 medium-12 small-12 columns">
                                <div class="row collapse prefix-radius">
                                    <div class="medium-3 columns">
                                        <span class="prefix"><strong>Secondary Key</strong></span>
                                    </div>
                                    <div class="medium-9 column">
                                        <input type="text" name="secondary_key" placeholder="Secondary Key" value="{{ old('secondary_key') }}" />
                                    </div>
                                </div>
                                @if (count($errors) > 0)
                                    @foreach ($errors->get('secondary_key') as $error)
                                        <div class="large-12 medium-12 small-12 column"><span class="label alert">{{ $error }}</span></div>
                                    @endforeach
                                @endif
                                <span class="label success">(Required) Provider Name</span>
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
