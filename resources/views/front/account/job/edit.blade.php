@extends('front.layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                            <li class="breadcrumb-item active">Edit a Job</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                @include('front.account.sidebar')
                <div class="col-lg-9">
                    @include('front.message')

                    <form action="" name="updatejobForm" id="updatejobForm">
                        <div class="card border-0 shadow mb-4 ">
                            <div class="card-body card-form p-4">
                                <h3 class="fs-4 mb-1">Job Details</h3>
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="" class="mb-2">Title<span class="req">*</span></label>
                                        <input type="text" value="{{ $job->title }}" id="title" name="title"
                                            class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6  mb-4">
                                        <label for="" class="mb-2">Category<span class="req">*</span></label>
                                        <select name="category" id="category" class="form-control">
                                            <option value="">Select a Category</option>
                                            @if ($categories->isNotEmpty())
                                                @foreach ($categories as $category)
                                                    <option {{ $job->category->id == $category->id ? 'selected' : '' }}
                                                        value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            @endif

                                        </select>
                                        <p></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="" class="mb-2">Job Type<span class="req">*</span></label>
                                        <select class="form-select" name="job_type" id="job_type">
                                            @if ($jobTypes->isNotEmpty())
                                                @foreach ($jobTypes as $jobType)
                                                    <option {{ $job->jobType->id == $jobType->id ? 'selected' : '' }}
                                                        value="{{ $jobType->id }}">{{ $jobType->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p></p>
                                    </div>
                                    <div class="col-md-6  mb-4">
                                        <label for="" class="mb-2">Vacancy<span class="req">*</span></label>
                                        <input type="number" min="1" value="{{ $job->vacancy }}"
                                            placeholder="Vacancy" id="vacancy" name="vacancy" class="form-control">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="mb-4 col-md-6">
                                        <label for="" class="mb-2">Salary</label>
                                        <input type="text" placeholder="Salary" id="salary" name="salary"
                                            class="form-control" value="{{ $job->salary }}">
                                    </div>

                                    <div class="mb-4 col-md-6">
                                        <label for="" class="mb-2">Location<span class="req">*</span></label>
                                        <input type="text" placeholder="Location" id="location" name="location"
                                            class="form-control" value="{{ $job->location }}">
                                        <p></p>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="" class="mb-2">Description<span class="req">*</span></label>
                                    <textarea class="form-control" name="description" id="description" cols="5" rows="5"
                                        placeholder="Description">{{ $job->description }}</textarea>
                                    <p></p>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Benefits</label>
                                    <textarea class="form-control" name="benefits" id="benefits" cols="5" rows="5" placeholder="Benefits">{{ $job->benefits }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Qualifications</label>
                                    <textarea class="form-control" name="qualifications" id="qualifications" cols="5" rows="5"
                                        placeholder="Qualifications">{{ $job->qualifications }}</textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="" class="mb-2">Experience<span class="req">*</span></label>
                                    <select class="form-select" name="experience" id="experience">
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option {{ $job->experience == $i ? 'selected' : '' }}
                                                value="{{ $i }}">{{ $i }} Year</option>
                                        @endfor
                                        <option {{ $job->experience == '10_pluse' ? 'selected' : '' }} value="10_pluse">
                                            10+ Year</option>
                                    </select>
                                    <p></p>
                                </div>



                                <div class="mb-4">
                                    <label for="" class="mb-2">Keywords</label>
                                    <input type="text" placeholder="keywords" id="keywords" name="keywords"
                                        class="form-control" value="{{ $job->keywords }}">
                                </div>

                                <h3 class="fs-4 mb-1 mt-5 border-top pt-5">Company Details</h3>

                                <div class="row">
                                    <div class="mb-4 col-md-6">
                                        <label for="" class="mb-2">Name<span class="req">*</span></label>
                                        <input type="text" placeholder="Company Name" id="company_name"
                                            name="company_name" class="form-control" value="{{ $job->company_name }}">
                                        <p></p>
                                    </div>

                                    <div class="mb-4 col-md-6">
                                        <label for="" class="mb-2">Location</label>
                                        <input type="text" placeholder="Location" id="company_location"
                                            name="company_location" class="form-control"
                                            value="{{ $job->company_location }}">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="" class="mb-2">Website</label>
                                    <input type="text" placeholder="Website" id="company_website"
                                        name="company_website" class="form-control" value="{{ $job->company_website }}">
                                </div>
                            </div>
                            <div class="card-footer  p-4">
                                <button type="submit" class="btn btn-primary">Update Job</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customjs')
    <script tryp="text/javascript">
        $('#updatejobForm').on('submit', function(e) {
            e.preventDefault();

            $("button[type='submit']").prop('disabled', true);

            $.ajax({
                type: "POST",
                url: "{{ route('account.update.job', $job->id) }}",
                data: $('#updatejobForm').serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type='submit']").prop('disabled', false);
                    if (response.status == 'true') {

                        $('#title').removeClass('is-invalid').siblings(p).removeClass(
                            'invalid-feedback').html('');

                        $('#category').removeClass('is-invalid').siblings(p).removeClass(
                            'invalid-feedback').html('');

                        $('#job_type').removeClass('is-invalid').siblings(p).removeClass(
                            'invalid-feedback').html('');

                        $('#vacancy').removeClass('is-invalid').siblings(p).removeClass(
                            'invalid-feedback').html('');

                        $('#location').removeClass('is-invalid').siblings(p).removeClass(
                            'invalid-feedback').html('');

                        $('#description').removeClass('is-invalid').siblings(p).removeClass(
                            'invalid-feedback').html('');

                        $('#company_name').removeClass('is-invalid').siblings(p).removeClass(
                            'invalid-feedback').html('');

                        window.location.href = "{{ route('account.my.jobs') }}";


                    } else {
                        var errors = response.errors;
                        if (errors.title) {
                            $('#title').addClass('is-invalid').siblings(p).addClass('invalid-feedback')
                                .html(errors.title);
                        } else {
                            $('#title').removeClass('is-invalid').siblings(p).removeClass(
                                'invalid-feedback').html('');
                        }

                        if (errors.category) {
                            $('#category').addClass('is-invalid').siblings(p).addClass(
                                    'invalid-feedback')
                                .html(errors.category);
                        } else {
                            $('#category').removeClass('is-invalid').siblings(p).removeClass(
                                'invalid-feedback').html('');
                        }
                        if (errors.job_type) {
                            $('#job_type').addClass('is-invalid').siblings(p).addClass(
                                    'invalid-feedback')
                                .html(errors.job_type);
                        } else {
                            $('#job_type').removeClass('is-invalid').siblings(p).removeClass(
                                'invalid-feedback').html('');
                        }

                        if (errors.vacancy) {
                            $('#vacancy').addClass('is-invalid').siblings(p).addClass(
                                    'invalid-feedback')
                                .html(errors.vacancy);
                        } else {
                            $('#vacancy').removeClass('is-invalid').siblings(p).removeClass(
                                'invalid-feedback').html('');
                        }

                        if (errors.location) {
                            $('#location').addClass('is-invalid').siblings(p).addClass(
                                    'invalid-feedback')
                                .html(errors.location);
                        } else {
                            $('#location').removeClass('is-invalid').siblings(p).removeClass(
                                'invalid-feedback').html('');
                        }

                        if (errors.description) {
                            $('#description').addClass('is-invalid').siblings(p).addClass(
                                    'invalid-feedback')
                                .html(errors.description);
                        } else {
                            $('#description').removeClass('is-invalid').siblings(p).removeClass(
                                'invalid-feedback').html('');
                        }

                        if (errors.experience) {
                            $('#experience').addClass('is-invalid').siblings(p).addClass(
                                    'invalid-feedback')
                                .html(errors.experience);
                        } else {
                            $('#experience').removeClass('is-invalid').siblings(p).removeClass(
                                'invalid-feedback').html('');
                        }

                        if (errors.company_name) {
                            $('#company_name').addClass('is-invalid').siblings(p).addClass(
                                    'invalid-feedback')
                                .html(errors.company_name);
                        } else {
                            $('#company_name').removeClass('is-invalid').siblings(p).removeClass(
                                'invalid-feedback').html('');
                        }
                    }
                }
            })
        })
    </script>
@endsection
