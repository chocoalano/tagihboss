@extends('layouts.app')
@section('css')
@endsection
@section('content')
<div class="min-height-200px">
	<div class="page-header">
		<div class="row">
			<div class="col-md-6 col-sm-12">
				<div class="title">
					<h4>Introduction</h4>
				</div>
				<nav aria-label="breadcrumb" role="navigation">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="index.html">Home</a></li>
						<li class="breadcrumb-item"><a href="javascript:;">Documentation</a></li>
						<li class="breadcrumb-item active" aria-current="page">introduction</li>
					</ol>
				</nav>
			</div>
			<div class="col-md-6 col-sm-12 text-right">
				<div class="dropdown">
					<a class="btn btn-primary dropdown-toggle" href="#" role="button" data-toggle="dropdown">
						Select Documentation
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						@foreach ($doc as $k => $v)
						@can($v->authorization)
							<a class="dropdown-item" href="{{ url('/documentation', $v->authorization) }}">{{ucfirst(strtolower(str_replace("-", " Fungsi ", $v->authorization)))}}</a>
						@endcan
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="pd-20 card-box mb-30">
		<h4 class="h4 text-blue mb-10">{{ucfirst(strtolower($documentation->title))}}</h4>
		<?php echo $documentation->information; ?>
	</div>
	<h4 class="h4 text-blue mb-10">Note</h4>
	<div class="pd-20 card-box mb-30" data-bgcolor="#ff4747" data-color="#fdfdfd" style="color: rgb(253, 253, 253); background-color: rgb(255, 71, 71);">
		<?php echo $documentation->attention; ?>
	</div>
</div>
@endsection
@section('js')
@endsection