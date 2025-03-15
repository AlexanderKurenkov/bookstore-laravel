<x-layout>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-5">{{ __('mesages.') }}</h2>
            <div class="mb-4">
                @include('profile.partials.update-profile-information-form')
            </div>
            <div class="mb-4">
                @include('profile.partials.update-password-form')
            </div>
            <div>
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
<x-layout>
