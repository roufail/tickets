@extends('layouts.admin.template')

@section('title',$role->id != null ?  __('roles.edit_role').' '.$role->title : __('roles.new_role'))

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
                {{ $role->id != null ?  __('roles.edit_role').' '.$role->title : __('roles.new_role') }}
            </h5>
            <div class="float-right">
            <a href="{{ route('admin.roles.index') }}">
                    {{ __('roles.all_roles') }}
                </a>
            </div>
        </div>
        @include('components.feedback')
        <div class="form">
            <form action="{{ $role->id != null ? route('admin.roles.update',$role->id)  : route('admin.roles.store') }}" method="POST">
                @csrf

                @if($role->id)
                    @method('PUT')
                @endif

                <div class="form-group">
                    <label for="name">{{ __('roles.form.role_name') }}</label>
                    <input value="{{ old('name') ? old('name') :  $role->name  }}" id="name" type="text" name="name" class="form-control" placeholder="Enter  {{ __('roles.form.role_name')   }}...">
                </div>

                {{-- <div class="form-group new-permission">
                    <a href="#">{{ __('roles.create_new_permission')   }}</a>
                </div>

                <div class="form-group d-none new-permission-form">
                    <label for="name">{{ __('roles.form.permission_name') }}</label>
                    <input class="permission-name-input form-control " id="name" type="text"  placeholder="Enter  {{ __('roles.form.permission_name')   }}...">
                    <button type="button" class="new-permission-btn btn btn-primary mt-2 float-right">{{  __('roles.form.save_permission') }}</button>
                    <div class="permission-form-error invalid-feedback">

                    </div>
                </div> --}}

                <div class="permission-holder">
                    @if($permissions->count())
                        <ul class="row list-inline">
                            @foreach ($permissions as $key => $permission)
                                <li class="col-md-3">
                                   <label><input type="checkbox" name="permissions[]" value="{{ $key }}" @if($role->hasPermissionTo($permission) || (old('permissions') && in_array($key,old('permissions')))  ) checked="checked" @endif/> {{  __('roles.roles.'.$permission)   }} </label>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>



                <div class="form-group mt-2 clearfix">
                    <button type="submit" class="btn btn-primary">
                        {{ $role->id != null ?  __('roles.form.role_update') : __('roles.form.role_save') }}
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
        $('.select2').select2();
        $('.new-permission').on('click','a',function(){
            $('.new-permission-form').toggleClass('d-none');
        });

        $('.new-permission-form').on('click','.new-permission-btn',function(e){
            e.preventDefault();
            $('.permission-form-error').removeClass('d-block');
            $permisson_input = $('.permission-name-input');
            $permisson = $permisson_input.val();

            if($permisson.trim() == '')
            {
                $permisson_input.addClass('is-invalid');
                return false;
            } else {
                $permisson_input.removeClass('is-invalid');
            }

            $.ajax({
                url: "{{ route('admin.permission.create') }}",
                type: "POST",
                data: {
                    _token    : "{{ csrf_token() }}",
                    name : $permisson
                },
                dataType: "json",
                success: function(permission) {

                    $(".permission-holder ul").append(`
                        <li class="col-md-3">
                            <label><input type="checkbox" name="permissions[]" value="${permission.data.id}"/>${permission.data.name}</label>
                        </li>
                    `);
                    $permisson_input.val('');
                },
                error: function(xhr , error){
                    $('.permission-form-error').text(xhr.responseJSON.message).addClass('d-block');
                }
            });
        })
    </script>
@endsection
