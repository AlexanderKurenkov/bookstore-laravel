<x-layout>
    <div class="d-flex justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify E-mail address') }}</div>

                <div class="card-body">
                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success" role="alert">
                            {{ __('Verification link has been sent to the e-mail') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        {{ __('Verify your e-mail address') }}
                    </div>

                    <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">
                            {{ __('Resend') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
