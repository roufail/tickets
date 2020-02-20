@extends('layouts.admin.template')
@section('title',__('roles.roles_title'))

@push('css-stack')
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
        <div class="clearfix">
            <h5 class="card-title">
                {{ __('roles.your_roles') }}
            </h5>
            <div class="float-right">
            <a href="{{ route('admin.roles.create') }}">
                    {{ __('roles.create_new_role') }}
                </a>
            </div>
        </div>

        @include('components.feedback')
        @if($roles->count() > 0)
            <div class="table-responsive">
            <table id="roles" class="table table-bordered table-hover ">
                <thead>
                <tr>
                    <th>{{ __('roles.table.name') }}</th>
                    <th>{{ __('roles.table.actions') }}</th>
                </tr>
                </thead>
                <tbody>

                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>
                            <div class="form-group">
                                <a href="{{ route('admin.roles.edit',$role->id)}}" class="float-left btn btn-primary mr-2 white-text">
                                    <i class="fas fa-edit"></i>
                                    {{  __('roles.table.edit') }}
                                </a>

                                <form method="POST" action="{{ route('admin.roles.destroy',$role->id)}}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="delete_role float-left btn btn-danger mr-2">
                                        <i class="fas fa-trash-alt"></i>
                                        {{  __('roles.table.delete') }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>{{ __('roles.table.name') }}</th>
                    <th>{{ __('roles.table.actions') }}</th>
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
    $('#roles').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "pageLength": 10,
    });

    $('#roles').on('click','.delete_role',function(e){
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
