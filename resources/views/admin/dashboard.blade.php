@extends('front.layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row ">
                @include('admin.sidebar')
                <div class="col-lg-9">
                    @include('front.message')

                    <div class="card-body ">
                        <h2>Welcome to Dashboard</h2>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('customjs')
    <script tryp="text/javascript"></script>
@endsection
