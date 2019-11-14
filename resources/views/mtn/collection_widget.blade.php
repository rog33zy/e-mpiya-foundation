@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row">

        <div class="large-12 medium-12 small-12 form-container columns">

            <div class="form-title text-center">
                {{ $header }}
            </div>
				<div class="contents">
                    <div
                        class="mobile-money-qr-payment"
                        data-api-user-id="c810c9e6-cd40-4f85-a831-d04acce7279e"
                        data-amount="9"
                        data-currency="EUR"
                        data-external-id="R12112019"
                        >
                    </div>
				</div>

        </div>

    </div>

</div>

@endsection
