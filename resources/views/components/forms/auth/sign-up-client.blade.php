<form method="post" class="@section('form-class') auth @show ">
    @csrf
    @section('auth-form-header')
        <h2>{{ trans('texts.auth.sign-up') }}</h2>
        <a href="{{ route('freelancer.auth.register') }}" class="d-block mt-0">{{ trans('texts.auth.are-you-freelancer') }}</a>
    @show

    <x-forms.auth.elements.login-input/>

    <x-forms.auth.elements.password-input name="password"
                                          :label="trans('texts.auth.password')"/>

    <x-forms.auth.elements.password-input name="password_confirmation"
                                          :label="trans('texts.auth.password-confirmation')"/>

    <br>
    <x-forms.auth.elements.submit-button/>
</form>
