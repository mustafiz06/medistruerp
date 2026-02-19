@extends('layout')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Edit supplier</h1>
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
                        <h3 class="card-title mt-1">Edit supplier</h3>
                        <div class="card-tools">
                            <a href="{{ route('supplier.index') }}" class="btn btn-primary btn-sm">
                                Back
                            </a>
                        </div>
                    </div>

                    <div class="card-body">

                        <form action="{{ route('supplier.update', $supplier->id) }}" method="POST">
                            @csrf

                            <div class="form-group row">
                                <label class="col-sm-2 control-label">supplier Name</label>
                                <div class="col-sm-10">
                                    <input type="text"
                                        name="name"
                                        class="form-control"
                                        value="{{ old('name', $supplier->name) }}"
                                        required>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Address</label>
                                <div class="col-sm-10">
                                    <input type="text"
                                        name="address"
                                        class="form-control"
                                        value="{{ old('address', $supplier->address) }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Responsible Person</label>
                                <div class="col-sm-10">
                                    <input type="text"
                                        name="responsible_person"
                                        class="form-control"
                                        value="{{ old('responsible_person', $supplier->responsible_person) }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 control-label">Responsible Person Contact</label>
                                <div class="col-sm-10">
                                    <input type="text"
                                        name="responsible_person_contact"
                                        class="form-control"
                                        value="{{ old('responsible_person_contact', $supplier->responsible_person_contact) }}">
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