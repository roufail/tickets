@extends('layouts.admin.template')

@section('title',$user->id != null ?  __('users.edit_user').' '.$user->name : __('users.new_user'))

@push('css-stack')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2-bootstrap4.min.css') }}">
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
        <div class="clearfix">
            <h5 class="card-title">
                {{ $user->id != null ?  __('users.edit_user').' '.$user->name : __('users.new_user') }}
            </h5>
            <div class="float-right">
            <a href="{{ route('admin.tickets.index') }}">
                    {{ __('users.all_users') }}
                </a>
            </div>
        </div>
        @include('components.feedback')
        <div class="form">
            <form action="{{ $user->id != null ? route('admin.users.update',$user->id)  : route('admin.users.store') }}" method="POST">
                @csrf

                @if($user->id)
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label for="name">{{ __('users.form.user_name') }}</label>
                    <input value="{{ old('name') ? old('name') :  $user->name  }}" id="name" type="text" name="name" class="form-control" placeholder="Enter  {{ __('users.form.user_name')   }}...">
                </div>


                <div class="form-group">
                    <label for="email">{{ __('users.form.user_email') }}</label>
                    <input value="{{ old('email') ? old('email') :  $user->email  }}" id="email" type="text" name="email" class="form-control" placeholder="Enter {{ __('users.form.user_email') }}..." />
                </div>


                <div class="form-group">
                    <label for="password">{{ __('users.form.user_password') }}</label>
                    <input  id="password" type="password" name="password" class="form-control" placeholder="Enter {{ __('users.form.user_password') }}..." />
                </div>

                <div class="form-group">
                    <label for="confirm_password">{{ __('users.form.user_confirm_password') }}</label>
                    <input id="confirm_password" type="password" name="confirm_password" class="form-control" placeholder="Enter {{ __('users.form.user_confirm_password') }}..." />
                </div>

                @if($user->id != 1)
                <!-- /.form-group -->
                <div class="form-group">
                    <label for="role">{{ __('users.form.user_group') }}</label>
                    <select id="role" name="role" class="form-control select2" style="width: 100%;">
                        @foreach ($roles as $key => $role)
                          <option value="{{ $key }}" @if($user->hasRole($key)) selected="selected" @endif>{{  $role   }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- /.form-group -->
                @endif



                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            {{ $user->id != null ?  __('users.form.user_update') : __('users.form.user_save') }}
                        </button>
                    </div>


            </form>
        </div>

    </div>
    </div>
@endsection

@push('js-stack')
<script src="{{ asset('js/select2.full.min.js') }}"></script>
@endpush

@section('extra-scripts')
    <script>
        $('.select2').select2()
    </script>
@endsection
