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

                    <div class="card border-0 shadow mb-4 p-3">
                        <div class="card-body card-form">
                            <h3 class="fs-4 mb-1">Jobs</h3>
                            <div class="table-responsive">
                                <table class="table ">
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Applied By</th>
                                            <th scope="col">Posted By</th>
                                            <th scope="col">Applied Date</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-0">

                                        @if (!empty($jobApplications))
                                            @foreach ($jobApplications as $jobApplication)
                                                <tr>
                                                    <td>
                                                        <div class="job-name fw-500">{{ $jobApplication->id }}</div>
                                                    </td>
                                                    <td>{{ $jobApplication->job->title }}</td>
                                                    <td>{{ $jobApplication->user->name }}</td>
                                                    <td>
                                                        <div class="job-status text-capitalize">
                                                            {{ $jobApplication->employer->name }}
                                                        </div>
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($jobApplication->created_at)->diffForHumans() }}
                                                    </td>
                                                    <td>
                                                        <div class="action-dots float-center">
                                                            <a href="#" class="" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a class="dropdown-item" href="#"
                                                                        onclick="deleteJobApplication({{ $jobApplication->id }})"><i
                                                                            class="fa fa-trash" aria-hidden="true"></i>
                                                                        Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                            <div>
                                {{ $jobApplications->links('pagination::bootstrap-5') }}
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
        function deleteJobApplication(id) {
            if (confirm('Are you sure you want to delete this Job Application?')) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('admin.delete.job_application') }}",
                    data: {
                        'id': id
                    },
                    success: function(response) {
                        if (response.status == 'true') {
                            window.location.href = "{{ route('admin.job_applications') }}";
                        } else {
                            window.location.href = "{{ route('admin.job_applications') }}";
                        }
                    }
                });
            }
        }
    </script>
@endsection
