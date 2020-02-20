@extends('layouts.admin.template')
@section('title', __('tickets.your_tickets'))

@push('css-stack')
    <link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
        <div class="clearfix">
            <h5 class="card-title">
                {{ __('tickets.your_tickets') }}
            </h5>
            @if ( auth()->user()->can('Create Ticket') )
                <div class="float-right">
                    <a href="{{ route('admin.tickets.create') }}">
                            {{ __('tickets.create_new_ticket') }}
                    </a>
                </div>
            @endif


        </div>

        @include('components.feedback')



        <div class="filters">
            <form method="GET" action="{{ route('admin.tickets.index') }}">
                <!-- /.form-group -->
                <div class="form-group col-md-2 float-left">
                    <label for="from_date">{{ __('tickets.filters.from_date') }}</label>
                    <input value="{{ request()->from_date }}" autocomplete="off" type="text" id="from_date" name="from_date" class="form-control">
                </div>
                <!-- /.form-group -->

                <!-- /.form-group -->
                <div class="form-group col-md-2 float-left">
                    <label for="to_date">{{ __('tickets.filters.to_date') }}</label>
                    <input value="{{ request()->to_date }}" autocomplete="off" type="text" id="to_date" name="to_date" class="form-control">
                </div>
                <!-- /.form-group -->

                <!-- /.form-group -->
                <div class="form-group col-md-2 float-left">
                    <label for="user">{{ __('tickets.filters.user') }}</label>
                    <select id="user" name="user" class="form-control" style="width: 100%;">
                        @foreach ($users as $key => $user)
                            <option value="{{ $key }}" @if( request()->user == $key) selected="selected" @endif>{{ $user }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- /.form-group -->


                <!-- /.form-group -->
                <div class="form-group col-md-2 float-left">
                    <label for="from_date">{{ __('tickets.filters.assign') }}</label>

                    <select id="assign" name="assign" class="form-control" style="width: 100%;">
                        @foreach ($users as $key => $user)
                            <option value="{{ $key }}" @if( request()->assign == $key) selected="selected" @endif>{{ $user }}</option>
                        @endforeach
                    </select>

                </div>
                <!-- /.form-group -->


                <!-- /.form-group -->
                <div class="form-group col-md-2 mt-2 float-left">
                    <br />
                    <button class="btn btn-primary">{{  __('tickets.filters.search')}}</button>
                </div>
                <!-- /.form-group -->


            </form>
        </div>



        @if($tickets->count() > 0)



            <div class="table-responsive">
            <table id="tickets" class="table table-bordered table-hover ">
                <thead>
                <tr>
                <th>{{ __('tickets.table.title') }}</th>
                <th>{{ __('tickets.table.description') }}</th>
                <th>{{ __('tickets.table.deadline') }}</th>
                <th>{{ __('tickets.table.status') }}</th>
                <th>{{ __('tickets.table.user') }}</th>
                <th>{{ __('tickets.table.assign') }}</th>

                @if ( auth()->user()->can('Open Ticket') || auth()->user()->can('Close Ticket') )
                    <th width="5%">{{ __('tickets.table.changestatus') }}</th>
                @endif

                @if ( auth()->user()->can('Edit Ticket') || auth()->user()->can('Delete Ticket') )
                    <th>{{ __('tickets.table.actions') }}</th>
                @endif

                </tr>
                </thead>
                <tbody>

                @foreach ($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->title }}</td>
                        <td>{{ $ticket->description }}</td>
                        <td>{{ $ticket->deadline }}</td>
                        <td>{{ $ticket->status ? __('tickets.messages.closed') : __('tickets.messages.opened') }}</td>
                        <td>{{ $ticket->user->name }}</td>
                        <td>{{ $ticket->assign->name }}</td>

                        @canany(['Open Ticket', 'Close Ticket'])

                            <td title="@if(!$ticket->status) {{ __('tickets.messages.opened') }} @else {{ __('tickets.messages.closed') }} @endif">

                                <a href="@if(($ticket->status && auth()->user()->can('Open Ticket')) ||
                                            (!$ticket->status && auth()->user()->can('Close Ticket')))

                                                {{ route('admin.tickets.changestatus',$ticket->id)   }}  @else # @endif"
                                    class="white-text btn @if(!$ticket->status)btn-success @else btn-danger @endif mr-2 white-text">
                                    <i class="fas fa-exchange-alt"></i>
                                </a>

                            </td>

                        @endcanany



                        @canany(['Edit Ticket', 'Delete Ticket'])

                        <td>
                            <div class="form-group">
                                        @can('Edit Ticket')
                                        <a href="{{ route('admin.tickets.edit',$ticket->id)}}" class="float-left btn btn-primary mr-2 white-text">
                                            <i class="fas fa-edit"></i>
                                            {{  __('tickets.table.edit') }}
                                        </a>
                                        @endcan

                                        @can('Delete Ticket')
                                        <form method="POST" action="{{ route('admin.tickets.destroy',$ticket->id)}}">
                                                @csrf
                                                @method('DELETE')
                                                <button class="delete_ticket float-left btn btn-danger mr-2">
                                                    <i class="fas fa-trash-alt"></i>
                                                    {{  __('tickets.table.delete') }}
                                                </button>
                                        </form>
                                        @endcan

                            </div>
                        </td>
                        @endcanany
                    </tr>
                @endforeach

                </tbody>
                <tfoot>
                <tr>
                    <th>{{ __('tickets.table.title') }}</th>
                    <th>{{ __('tickets.table.description') }}</th>
                    <th>{{ __('tickets.table.deadline') }}</th>
                    <th>{{ __('tickets.table.status') }}</th>
                    <th>{{ __('tickets.table.user') }}</th>
                    <th>{{ __('tickets.table.assign') }}</th>
                    @canany(['Open Ticket', 'Close Ticket'])
                        <th width="5%">{{ __('tickets.table.changestatus') }}</th>
                    @endcanany
                    @canany(['Edit Ticket', 'Delete Ticket'])
                        <th>{{ __('tickets.table.actions') }}</th>
                    @endcanany

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
    <script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('js/select2.full.min.js') }}"></script>
@endpush

@section('extra-scripts')
    <script>

    $('#from_date,#to_date').datepicker({
            autoclose: true,
            // startDate: "now()",
            format: 'yyyy-mm-d'
    });



    $('#tickets').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "pageLength": 10,
    });

    $('#tickets').on('click','.delete_ticket',function(e){
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
