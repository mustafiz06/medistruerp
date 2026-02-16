@extends('layout')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Brand') }}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href=""><i class="fas fa-home"></i>{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item">{{ __('Brand') }}</li>
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
                        <h3 class="card-title mt-1">{{ __('Add Brand') }}</h3>
                        <div class="card-tools">
                            <a href="" class="btn btn-primary btn-sm">
                                <i class="fas fa-angle-double-left"></i> {{ __('Back') }}
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form id="brandForm" class="form-horizontal" action="{{ route('brand.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="brand_id" name="brand_id" value="">
                            <div class="form-group row">
                                <label for="title" class="col-sm-2 control-label">{{ __('Title') }}<span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="title" id="title" placeholder="{{ __('Title') }}" value="{{ old('title') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="slug" class="col-sm-2 control-label">Slug <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="slug" id="slug" placeholder="{{ __('Slug') }}" value="{{ old('slug') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="status" class="col-sm-2 control-label">{{ __('Status') }}<span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select class="form-control" name="status" id="status">
                                        <option value="0">Unpublish</option>
                                        <option value="1">Publish</option>
                                    </select>
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
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('brand List:') }}</h3>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-striped data_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Slug') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($brands as $id=>$brand)
                                    <tr>
                                        <td>
                                            {{ $loop->index+1 }}
                                        </td>
                                        <td>{{$brand->title}}</td>
                                        <td>{{$brand->slug}}</td>
                                        <td>
                                            <form action="{{ route('brand.status', $brand->id) }}"
                                                method="post">
                                                @csrf
                                                @if ($brand->status == "1")
                                                <button type="submit"
                                                    class="btn badge badge-success">Published</button>
                                                @else
                                                <button type="submit"
                                                    class="btn badge badge-warning">Unpublished</button>
                                                @endif
                                            </form>
                                        </td>
                                        <td>



                                            <button type="button" class="btn btn-info btn-sm"
                                                onclick="editbrand('{{ $brand->id }}', '{{ $brand->title }}', '{{ $brand->slug }}', '{{ $brand->update }}')">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </button>




                                            <form id="deleteform" class="d-inline-block" action="{{ route('brand.delete', $brand->id ) }}" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $brand->id }}">
                                                <button type="submit" class="btn btn-danger btn-sm" id="delete">
                                                    <i class="fas fa-trash"></i>{{ __('Delete') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->

</section>



<script>
    document.getElementById('title').addEventListener('input', function() {
        let title = this.value;

        let slug = title
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');

        document.getElementById('slug').value = slug;
    });

    function editbrand(id, title, slug, status) {
        let form = document.getElementById('brandForm');
        form.action = '/productsetting/brand/edit/' + id;

        document.getElementById('brand_id').value = id;
        document.getElementById('title').value = title;
        document.getElementById('slug').value = slug;
        document.getElementById('status').value = status;

        document.getElementById('formSubmitBtn').textContent = 'Update';
    }
</script>
@endsection