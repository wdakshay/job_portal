@extends('front.layouts.app')

@section('main')
    <section class="section-3 py-5 bg-2 ">
        <div class="container">
            <div class="row">
                <div class="col-6 col-md-10 ">
                    <h2>Find Jobs</h2>
                </div>
                <div class="col-6 col-md-2">
                    <div class="align-end">
                        <select name="sort" id="sort" class="form-control">
                            <option {{ Request::get('sort') == '1' ? 'selected' : '' }} value="1">Latest</option>
                            <option {{ Request::get('sort') == '0' ? 'selected' : '' }} value="0">Oldest</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row pt-5">
                <div class="col-md-4 col-lg-3 sidebar mb-4">
                    <form action="" name="searchform" id="searchform">

                        <div class="card border-0 shadow p-4">
                            <div class="mb-4">
                                <h2>Keywords</h2>
                                <input type="text" id="keywords" name="keywords" value="{{ Request::get('keywords') }}"
                                    placeholder="Keywords" class="form-control">
                            </div>

                            <div class="mb-4">
                                <h2>Location</h2>
                                <input type="text" id="location" name="location" value="{{ Request::get('location') }}"
                                    placeholder="Location" class="form-control">
                            </div>

                            <div class="mb-4">
                                <h2>Category</h2>
                                <select name="category" id="category" class="form-control">
                                    <option value="">Select a Category</option>
                                    @if ($categories->isNotEmpty())
                                        @foreach ($categories as $category)
                                            <option {{ Request::get('category') == $category->id ? 'selected' : '' }}
                                                value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="mb-4">
                                <h2>Job Type</h2>
                                @if ($jobTypes->isNotEmpty())
                                    @foreach ($jobTypes as $jobType)
                                        <div class="form-check mb-2">
                                            <input {{ in_array($jobType->id, $jobTypeArray) ? 'checked' : '' }}
                                                class="form-check-input " name="job_type" type="checkbox"
                                                value="{{ $jobType->id }}" id="{{ $jobType->id }}">
                                            <label class="form-check-label "
                                                for="{{ $jobType->id }}">{{ $jobType->name }}</label>
                                        </div>
                                    @endforeach
                                @endif

                            </div>

                            <div class="mb-4">
                                <h2>Experience</h2>
                                <select name="experience" id="experience" class="form-control">
                                    <option value="">Select Experience</option>
                                    @for ($i = 1; $i <= 10; $i++)
                                        <option {{ Request::get('experience') == $i ? 'selected' : '' }}
                                            value="{{ $i }}">{{ $i }} Year</option>
                                    @endfor
                                    <option {{ Request::get('experience') == '10_plus' ? 'selected' : '' }}
                                        value="10_plus">10+ Years</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="{{ route('jobs') }}" type="button" class="btn btn-secondary mt-2">Reset</a>
                        </div>

                    </form>
                </div>
                <div class="col-md-8 col-lg-9 ">
                    <div class="job_listing_area">
                        <div class="job_lists">
                            <div class="row">
                                @if ($jobs->isNotEmpty())
                                    @foreach ($jobs as $job)
                                        <div class="col-md-4">
                                            <div class="card border-0 p-3 shadow mb-4">
                                                <div class="card-body">
                                                    <h3 class="border-0 fs-5 pb-2 mb-0">{{ $job->title }}</h3>
                                                    <p>{{ Str::words($job->description, 4) }}</p>
                                                    <div class="bg-light p-3 border">
                                                        <p class="mb-0">
                                                            <span class="fw-bolder"><i class="fa fa-map-marker"></i></span>
                                                            <span class="ps-1">{{ $job->location }}</span>
                                                        </p>
                                                        <p class="mb-0">
                                                            <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                                            <span class="ps-1">{{ $job->jobType->name }}</span>
                                                        </p>
                                                        @if (!is_Null($job->salary))
                                                            <p class="mb-0">
                                                                <span class="fw-bolder"><i class="fa fa-usd"></i></span>
                                                                <span class="ps-1">{{ $job->salary }}</span>
                                                            </p>
                                                        @endif

                                                    </div>

                                                    <div class="d-grid mt-3">
                                                        <a href="{{ route('jobs.details', $job->id) }}"
                                                            class="btn btn-primary btn-lg">Details</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-md-12">Jobs Not Found</div>
                                @endif

                            </div>
                        </div>
                        <div>
                            {{ $jobs->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@section('customjs')
    <script>
        $('#searchform').on('submit', function(e) {
            e.preventDefault();

            var url = '{{ route('jobs') }}?';
            var keywords = $('#keywords').val();
            var location = $('#location').val();
            var category = $('#category').val();
            var experience = $('#experience').val();
            var sort = $('#sort').val();

            var checkedJobType = $('input:checkbox[name="job_type"]:checked').map(function() {
                return $(this).val();
            }).get();


            //if keywords have a value
            if (keywords != '') {
                url += '&keywords=' + keywords;
            }

            //if location have a value
            if (location != '') {
                url += '&location=' + location;
            }

            //if category have a value
            if (category != '') {
                url += '&category=' + category;
            }

            //if experience have a value
            if (experience != '') {
                url += '&experience=' + experience;
            }

            //if job type have a value
            if (checkedJobType.length > 0) {
                url += '&job_type=' + checkedJobType;
            }

            url += '&sort=' + sort;
            window.location.href = url;
        });

        $('#sort').on('change', function() {
            $('#searchform').submit();
        });
    </script>
@endsection
