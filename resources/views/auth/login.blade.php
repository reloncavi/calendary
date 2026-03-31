@extends('layouts.app')

@section('content')
<div class="auth-card">
    <div class="auth-logo">
        <i class="fas fa-calendar-alt"></i>
    </div>

    <h1>{{ trans('panel.site_title') }}</h1>
    <p class="auth-subtitle">{{ trans('global.login') }}</p>

    @if(session('status'))
        <div class="alert alert-success mb-4" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="form-label">
                <i class="fas fa-envelope me-1" style="color: var(--text-muted);"></i>
                {{ trans('global.login_email') }}
            </label>
            <input id="email" name="email" type="email"
                   class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                   required autocomplete="email" autofocus
                   placeholder="{{ trans('global.login_email') }}"
                   value="{{ old('email') }}">
            @if($errors->has('email'))
                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
            @endif
        </div>

        <div class="mb-4">
            <label for="password" class="form-label">
                <i class="fas fa-lock me-1" style="color: var(--text-muted);"></i>
                {{ trans('global.login_password') }}
            </label>
            <input id="password" name="password" type="password"
                   class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                   required placeholder="{{ trans('global.login_password') }}">
            @if($errors->has('password'))
                <div class="invalid-feedback">{{ $errors->first('password') }}</div>
            @endif
        </div>

        <div class="mb-4 d-flex align-items-center justify-content-between">
            <div class="form-check">
                <input class="form-check-input" name="remember" type="checkbox" id="remember">
                <label class="form-check-label" for="remember" style="font-size:.875rem; color: var(--text-muted);">
                    {{ trans('global.remember_me') }}
                </label>
            </div>
            @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-size:.875rem; color: var(--primary); text-decoration:none; font-weight:500;">
                    {{ trans('global.forgot_password') }}
                </a>
            @endif
        </div>

        <button type="submit" class="btn btn-primary w-100" style="padding:.75rem; font-size:.9rem;">
            <i class="fas fa-sign-in-alt me-2"></i>{{ trans('global.login') }}
        </button>
    </form>
</div>
@endsection

