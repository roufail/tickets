@extends('layouts.admin.template')
@section('title',__('users.users'))

@push('css-stack')
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
        <div class="clearfix">
            <h5 class="card-title">
                {{ __('users.users') }}
            </h5>
            <div class="float-right">
            <a href="{{ route('admin.users.create') }}">
                    {{ __('users.create_new_user') }}
                </a>
            </div>
        </div>

        @include('components.feedback')
        @if($users->count() > 0)
            <div class="table-responsive">
            <table id="users" class="table table-bordered table-hover ">
                <thead>
                <tr>
                <th>{{ __('users.table.name') }}</th>
                <th>{{ __('users.table.email') }}</th>
                <th>{{ __('users.table.role') }}</th>
                <th>{{ __('users.table.actions') }}</th>
                </tr>
                </thead>
                <tbody>

                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->roles->first() ? $user->roles->first()->name  : '' }}</td>

                        <td>

                            <div class="form-group">

                                @if($user->id == auth()->user()->id || auth()->user()->id == 1)
                                        <a href="{{ route('admin.users.edit',$user->id)}}" class="float-left btn btn-primary mr-2 white-text">
                                            <i class="fas fa-edit"></i>
                                            {{  __('users.table.edit') }}
                                        </a>
                                @endif

                                @if($user->id != 1)
                                    <form method="POST" action="{{ route('admin.users.destroy',$user->id)}}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="delete_user float-left btn btn-danger mr-2">
                                            <i class="fas fa-trash-alt"></i>
                                            {{  __('users.table.delete') }}
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>{{ __('users.table.name') }}</th>
                    <th>{{ __('users.table.email') }}</th>
                    <th>{{ __('users.table.role') }}</th>
                    <th>{{ __('users.table.actions') }}</th>
                </tr>
                </tfoot>
            </table>
            </div>
        @endif

        </div>
    </div>
@endsection

@push('js-stack')
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
@endpush

@section('extra-scripts')
    <script>
    $('#users').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "pageLength": 10,
    });

    $('#users').on('click','.delete_user',function(e){
        e.preventDefault() // Don't post the form, unless confirmed

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
            if (result.value) {
                $(e.target).closest('form').submit();
            }
        });


    });



    </script>
@endsection
