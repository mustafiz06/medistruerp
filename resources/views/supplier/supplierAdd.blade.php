@extends('layout')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('supplier') }}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href=""><i class="fas fa-home"></i>{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item">{{ __('supplier') }}</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title mt-1">{{ __('Add supplier') }}</h3>
                        <div class="card-tools">
                            <a href="" class="btn btn-primary btn-sm">
                                <i class="fas fa-angle-double-left"></i> {{ __('Back') }}
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form id="supplierForm" class="form-horizontal" action="{{ route('supplier.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 control-label">{{ __('supplier Name') }}<span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="{{ __('supplier Name') }}" value="{{ old('name') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="address" class="col-sm-2 control-label">{{ __('Address') }}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="address" id="address" placeholder="{{ __('Address') }}" value="{{ old('address') }}">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="responsible_person" class="col-sm-2 control-label">{{ __('Responsible Person') }}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="responsible_person" id="responsible_person" placeholder="{{ __('Responsible Person') }}" value="{{ old('responsible_person') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="responsible_person_contact" class="col-sm-2 control-label">{{ __('Responsible Person Contact') }}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="responsible_person_contact" id="responsible_person_contact" placeholder="{{ __('Responsible Person Contact') }}" value="{{ old('responsible_person_contact') }}">
                                </div>
                            </div>

                            

                            <div class="form-group row">
                                <div class="offset-sm-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary" id="formSubmitBtn">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
</section>

@endsection