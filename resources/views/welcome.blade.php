@extends('dashboard.layout')

@section('content')
<!-- Content Header (Page header) -->

<div class="content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">

        <h1 class="m-0 text-dark">Welcome rahim !</h1>
      </div>
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box bg-gradient-info">
          <span class="info-box-icon"><i class="fas fa-user"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Total Users</span>
            <h4 class="info-box-number font-weight-normal">5</h4>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box bg-gradient-success">
          <span class="info-box-icon"><i class="fas fa-box-open"></i></span>
          <a href="" class="info-box-content text-dark">
            <span class="info-box-text">Total Category</span>
            <h4 class="info-box-number font-weight-normal">50</h4>
          </a>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box bg-gradient-warning">
          <span class="info-box-icon"><i class="fas fa-box-open"></i></span>
          <a href="" class="info-box-content text-light">
            <span class="info-box-text">Total Brand</span>
            <h4 class="info-box-number font-weight-normal">50</h4>
          </a>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box bg-gradient-danger">
          <span class="info-box-icon"><i class="fas fa-star"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Total Review</span>
            <h4 class="info-box-number font-weight-normal">5</h4>
          </div>
          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
      <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box bg-gradient-success">
          <span class="info-box-icon"><i class="fas fa-shopping-cart"></i></span>
          <a href="" class="info-box-content text-light">
            <span class="info-box-text">Total Product</span>
            <h4 class="info-box-number font-weight-normal">50</h4>
          </a>

          <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
      </div>
    </div>
  </div>
  <!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection