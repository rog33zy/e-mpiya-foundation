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
                        @if($api_data->count() > 0)
							<p><a href="{{ route('new_mtn_app') }}" class="small button">Add New MTN API App</a></p>
							<table class="stack hover">
								<thead>
									<tr>
										<th>#</th>
										<th>Provider</th>
										<th>API User</th>
										<th>API Key</th>
										<th>Basic Auth</th>
										<th>Callback URL</th>
										<th>Created</th>
									</tr>
								</thead>
								<tbody>
									<?php $count = 0; ?>
									@foreach($api_data as $data)
										<tr>
											<td>#{{ ++$count }}</td>
											<td>{{ $data->provider }}</td>
											<td>{{ $data->api_user }}</td>
											<td style="white-space: nowrap;">{{ $data->api_key }}</td>
											<td style="white-space: nowrap;">{{ $data->basic_auth }}</td>
											<td>{{ $data->callback_url }}</td>
											<td>{{ $data->created_at }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>
                        @else
                            <p>
								<a href="{{ route('new_mtn_app') }}" class="small button">Add New MTN API App</a>
							</p>
                        @endif
					</div>
				</div>

        </div>

    </div>

</div>

@endsection
