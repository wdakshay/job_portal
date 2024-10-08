@extends('front.layouts.app')

@section('main')
    <section class="section-5">
        <div class="container my-5">
            <div class="py-lg-2">&nbsp;</div>

            @if (Session::has('success'))
                <div class="alert alert-success">
                    <p class="mb-0 ph-0">{{ Session::get('success') }}</p>
                </div>
            @endif

            @if (Session::has('error'))
                <div class="alert alert-danger">
                    <p class="mb-0 ph-0">{{ Session::get('error') }}</p>
                </div>
            @endif

            <div class="row d-flex justify-content-center">
                <div class="col-md-5">
                    <div class="card shadow border-0 p-5">
                        <h1 class="h3">Reset Password</h1>
                        <form action="{{ route('account.process.resetPassword') }}" method="post" id="resetPassword"
                            name="resetPassword">
                            @csrf
                            <input type="hidden" name="token" id="token" value="{{ $token }}">
                            <div class="mb-3">
                                <label for="" class="mb-2">New Password*</label>
                                <input type="password" name="new_password" id="new_password"
                                    class="form-control @error('new_password') is-invalid @enderror "
                                    placeholder="Enter New Password">
                                @error('new_password')
                                    <p class="invlid-feedback" style="color: red">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="" class="mb-2">Confirm Password*</label>
                                <input type="password" name="confirm_password" id="confirm_password"
                                    class="form-control @error('confirm_password') is-invalid @enderror "
                                    placeholder="Confirm Your Password">
                                @error('confirm_password')
                                    <p class="invlid-feedback" style="color: red">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="justify-content-between d-flex">
                                <button type="submit" class="btn btn-primary mt-2">submit</button>
                                <a href="{{ route('account.forgot.password') }}" class="mt-3">Forgot Password?</a>
                            </div>
                        </form>
                    </div>
                    <div class="mt-4 text-center">
                        <p>Do not have an account? <a href="{{ route('account.register') }}">Register</a></p>
                    </div>
                </div>
            </div>
            <div class="py-lg-5">&nbsp;</div>
        </div>
    </section>
@endsection
