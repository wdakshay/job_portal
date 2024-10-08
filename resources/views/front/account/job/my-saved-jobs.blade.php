@extends('front.layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Jobs Applied</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">
                @include('front.account.sidebar')
                <div class="col-lg-9">
                    @include('front.message')
                    <div class="card border-0 shadow mb-4 p-3">
                        <div class="card-body card-form">
                            <h3 class="fs-4 mb-1">Saved Jobs</h3>
                            <div class="table-responsive">
                                @if (!empty($savedJobs))
                                    <table class="table ">
                                        <thead class="bg-light">
                                            <tr>
                                                <th scope="col">Title</th>
                                                <th scope="col">Applied Date</th>
                                                <th scope="col">Applicants</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="border-0">


                                            @foreach ($savedJobs as $savedJob)
                                                <tr>
                                                    <td>
                                                        <div class="job-name fw-500">{{ $savedJob->job->title }}</div>
                                                        <div class="info1">{{ $savedJob->job->jobType->name }} .
                                                            {{ $savedJob->job->location }}</div>
                                                    </td>
                                                    <td>{{ date('d M, Y', strtotime($savedJob->created_at)) }}</td>
                                                    <td>{{ $savedJob->job->applications->count() }} Applications</td>
                                                    <td>
                                                        <div class="job-status text-capitalize">
                                                            {{ $savedJob->job->status ? 'Active' : 'inactive' }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="action-dots float-end">
                                                            <a href="#" class="" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a class="dropdown-item"
                                                                        href="{{ route('jobs.details', $savedJob->job->id) }}">
                                                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                                                        View</a></li>
                                                                <li><a class="dropdown-item" href="#"
                                                                        onclick="deleteSavedJob({{ $savedJob->id }})"><i
                                                                            class="fa fa-trash" aria-hidden="true"></i>
                                                                        Remove</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                @else
                                    <div>
                                        <div class="text-center">There is no saved job. Go to <a
                                                href="{{ route('jobs') }}">Jobs</a> Page to saved the job</div>
                                    </div>
                                @endif
                            </div>
                            <div>
                                {{ $savedJobs->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customjs')
    <script>
        function deleteSavedJob(id) {
            if (confirm('Are you sure you want to remove this saved job?')) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('account.remove.savedJob') }}",
                    data: {
                        'id': id
                    },
                    success: function(response) {
                        if (response.status == 'true') {
                            window.location.href = "{{ route('account.my.saved.jobs') }}";
                        } else {
                            alert(response.message);
                            window.location.href = "{{ route('account.my.saved.jobs') }}";
                        }
                    }
                });
            }
        }
    </script>
@endsection
