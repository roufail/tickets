@extends('layouts.admin.template')

@section('title',$ticket->id != null ?  __('tickets.edit_ticket').' '.$ticket->title : __('tickets.new_ticket'))

@push('css-stack')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
        <div class="clearfix">
            <h5 class="card-title">
                {{ $ticket->id != null ?  __('tickets.edit_ticket').' '.$ticket->title : __('tickets.new_ticket') }}
            </h5>
            <div class="float-right">
            <a href="{{ route('admin.tickets.index') }}">
                    {{ __('tickets.all_tickets') }}
                </a>
            </div>
        </div>
        @include('components.feedback')
        <div class="form">
            <form action="{{ $ticket->id != null ? route('admin.tickets.update',$ticket->id)  : route('admin.tickets.store') }}" method="POST">
                @csrf

                @if($ticket->id)
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label for="title">{{ __('tickets.form.ticket_title') }}</label>
                    <input value="{{ old('title') ? old('title') :  $ticket->title  }}" id="title" type="text" name="title" class="form-control" placeholder="Enter  {{ __('tickets.form.ticket_title')   }}...">
                </div>

                <div class="form-group">
                    <label for="description">{{ __('tickets.form.ticket_description') }}</label>
                    <textarea id="description" type="text" name="description" class="form-control" placeholder="Enter {{ __('tickets.form.ticket_description') }}..."> {{ old('description') ? old('description') :  $ticket->description  }} </textarea>
                </div>



                <div class="form-group">
                    <label for="deadline">{{ __('tickets.form.ticket_deadline') }}</label>

                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">
                          <i class="far fa-calendar-alt"></i>
                        </span>
                      </div>
                    <input autocomplete="off" value="{{ old('deadline') ? old('deadline') :  $ticket->deadline  }}" id="deadline" type="text" name="deadline" class="form-control" placeholder="Enter  {{ __('tickets.form.ticket_deadline')   }}...">
                    </div>
                    <!-- /.input group -->
                  </div>


                <!-- /.form-group -->
                <div class="form-group">
                    <label for="assign_to">{{ __('tickets.form.ticket_assign_to') }}</label>
                    <select id="assign_to" name="assign_id" class="form-control select2" style="width: 100%;">
                        @foreach ($admins as $key => $admin)
                            <option value="{{ $key }}">{{ $admin }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- /.form-group -->


                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            {{ $ticket->id != null ?  __('tickets.form.ticket_update') : __('tickets.form.ticket_save') }}
                        </button>
                    </div>


            </form>
        </div>

    </div>
    </div>
@endsection

@push('js-stack')
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('js/select2.full.min.js') }}"></script>
@endpush

@section('extra-scripts')
    <script>
        $('#deadline').datepicker({
            autoclose: true,
            startDate: "now()",
            format: 'yyyy-mm-d'
        });

        $('.select2').select2()

    </script>
@endsection
