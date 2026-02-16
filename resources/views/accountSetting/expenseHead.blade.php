@extends('layout')

@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ __('Expense Head') }}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href=""><i class="fas fa-home"></i>{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item">{{ __('Expense Head') }}</li>
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
                        <h3 class="card-title mt-1">{{ __('Add Expense Head') }}</h3>
                        <div class="card-tools">
                            <a href="" class="btn btn-primary btn-sm">
                                <i class="fas fa-angle-double-left"></i> {{ __('Back') }}
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form id="expenseHeadForm" class="form-horizontal" action="{{ route('expenseHead.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="expenseHead_id" name="expenseHead_id" value="">
                            <div class="form-group row">
                                <label for="title" class="col-sm-2 control-label">{{ __('Expense Head Name') }}<span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="title" id="title" placeholder="{{ __('Expense Head Name') }}" value="{{ old('title') }}">
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
                        <h3 class="card-title">{{ __('Expense Head List:') }}</h3>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-striped data_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($methods as $id=>$expenseHead)
                                    <tr>
                                        <td>
                                            {{ $loop->index+1 }}
                                        </td>
                                        <td>{{$expenseHead->title}}</td>
                                        <td>
                                            <form action="{{ route('expenseHead.status', $expenseHead->id) }}"
                                                method="post">
                                                @csrf
                                                @if ($expenseHead->status == "1")
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
                                                onclick="editexpenseHead('{{ $expenseHead->id }}', '{{ $expenseHead->title }}', '{{ $expenseHead->update }}')">
                                                <i class="fas fa-pencil-alt"></i> Edit
                                            </button>




                                            <form id="deleteform" class="d-inline-block" action="{{ route('expenseHead.delete', $expenseHead->id ) }}" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $expenseHead->id }}">
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
   

    function editexpenseHead(id, title, status) {
        let form = document.getElementById('expenseHeadForm');
        form.action = '/accountsetting/expensehead/edit/' + id;

        document.getElementById('expenseHead_id').value = id;
        document.getElementById('title').value = title;

        document.getElementById('formSubmitBtn').textContent = 'Update';
    }
</script>
@endsection