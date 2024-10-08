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
                            <h3 class="fs-4 mb-1">Users</h3>
                            <div class="table-responsive">
                                <table class="table ">
                                    <thead class="bg-light">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Mobile</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-0">

                                        @if (!empty($users))
                                            @foreach ($users as $user)
                                                <tr>
                                                    <td>
                                                        <div class="job-name fw-500">{{ $user->id }}</div>
                                                    </td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        <div class="job-status text-capitalize">
                                                            {{ $user->mobile }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="action-dots float-center">
                                                            <a href="#" class="" data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a class="dropdown-item"
                                                                        href="{{ route('admin.edit.user', $user->id) }}">
                                                                        <i class="fa fa-pencil-square"
                                                                            aria-hidden="true"></i>
                                                                        Edit</a></li>
                                                                <li><a class="dropdown-item" href="#"
                                                                        onclick="deleteUser({{ $user->id }})"><i
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
                                {{ $users->links('pagination::bootstrap-5') }}
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
        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this User?')) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('admin.delete.user') }}",
                    data: {
                        'id': id
                    },
                    success: function(response) {
                        if (response.status == 'true') {
                            window.location.href = "{{ route('admin.users') }}";
                        } else {
                            window.location.href = "{{ route('admin.users') }}";
                        }
                    }
                });
            }
        }
    </script>
@endsection
