@extends('layout')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Edit Customer</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">

                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title mt-1">Edit Customer</h3>
                        <div class="card-tools">
                            <a href="{{ route('customer.index') }}" class="btn btn-primary btn-sm">
                                Back
                            </a>
                        </div>
                    </div>

                    <div class="card-body">

                        <form action="{{ route('customer.update', $customer->id) }}" method="POST">
                            @csrf

                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Customer Name</label>
                                <div class="col-sm-10">
                                    <input type="text"
                                        name="name"
                                        class="form-control"
                                        value="{{ old('name', $customer->name) }}"
                                        required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Designation</label>
                                <div class="col-sm-10">
                                    <input type="text"
                                        name="designation"
                                        class="form-control"
                                        value="{{ old('designation', $customer->designation) }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Address</label>
                                <div class="col-sm-10">
                                    <input type="text"
                                        name="address"
                                        class="form-control"
                                        value="{{ old('address', $customer->address) }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Phone Number</label>
                                <div class="col-sm-10">
                                    <input type="text"
                                        name="contact"
                                        class="form-control"
                                        value="{{ old('contact', $customer->contact) }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Responsible Person</label>
                                <div class="col-sm-10">
                                    <input type="text"
                                        name="responsible_person"
                                        class="form-control"
                                        value="{{ old('responsible_person', $customer->responsible_person) }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Responsible Person Contact</label>
                                <div class="col-sm-10">
                                    <input type="text"
                                        name="responsible_person_contact"
                                        class="form-control"
                                        value="{{ old('responsible_person_contact', $customer->responsible_person_contact) }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="offset-sm-2 col-sm-10">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection