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
						@foreach ($documentation as $k => $v)
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
		<h4 class="h4 text-blue mb-10">ALL Crews System Created</h4>

		<div class="row clearfix">
			<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
				<div class="da-card">
					<div class="da-card-photo">
						<img src="{{asset('img/help/pakdarwin.png')}}" alt="">
					</div>
					<div class="da-card-content">
						<h5 class="h5 mb-10">Darhwin Sinarta</h5>
						<p class="mb-0">IT & RISK Direksi</p>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
				<div class="da-card">
					<div class="da-card-photo">
						<img src="{{asset('img/help/pakham.png')}}" alt="">
					</div>
					<div class="da-card-content">
						<h5 class="h5 mb-10">Hamsudi</h5>
						<p class="mb-0">IT Dept Head</p>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
				<div class="da-card">
					<div class="da-card-photo">
						<img src="{{asset('img/help/pakindara.jpg')}}" alt="">
						<div class="da-overlay">
							<div class="da-social">
								<ul class="clearfix">
									<li><i class="icon-copy dw dw-antenna"></i> Core Banking Integrate</li>
									<li><i class="icon-copy dw dw-server"></i> Database Architecture TagihBoss</li>
									<li><i class="icon-copy dw dw-calculator"></i> Bussines Analyst TagihBoss</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="da-card-content">
						<h5 class="h5 mb-10">Indra Maulana</h5>
						<p class="mb-0">IT Supervisor</p>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
				<div class="da-card">
					<div class="da-card-photo">
						<img src="{{asset('img/help/alan.jpeg')}}" alt="">
						<div class="da-overlay">
							<div class="da-social">
								<ul class="clearfix">
									<li><i class="icon-copy dw dw-diamond"></i> Core Engine Program Integrate Development TagihBoss</li>
									<li><i class="icon-copy dw dw-server"></i> Database Architecture TagihBoss</li>
									<li><i class="icon-copy dw dw-analytics-14"></i></i> System Analyst TagihBoss</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="da-card-content">
						<h5 class="h5 mb-10">Alan Gentina</h5>
						<p class="mb-0">IT Programmer</p>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
				<div class="da-card">
					<div class="da-card-photo">
						<img src="{{asset('img/help/ibutri.jpeg')}}" alt="">
						<div class="da-overlay">
							<div class="da-social">
								<ul class="clearfix">
									<li><i class="icon-copy dw dw-calculator"></i> Bussines Analyst TagihBoss</li>
									<li><i class="icon-copy dw dw-workflow"></i> Bussines Procedure TagihBoss</li>
									<li><i class="icon-copy dw dw-analytics-14"></i></i> System Analyst TagihBoss</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="da-card-content">
						<h5 class="h5 mb-10">Tri</h5>
						<p class="mb-0">Collection SPV</p>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
				<div class="da-card">
					<div class="da-card-photo">
						<img src="vendors/images/photo2.jpg" alt="">
						<div class="da-overlay da-slide-bottom">
							<div class="da-social">
								<ul class="clearfix">
									<li><i class="icon-copy dw dw-calculator"></i> Bussines Analyst TagihBoss</li>
									<li><i class="icon-copy dw dw-workflow"></i> Bussines Procedure TagihBoss</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="da-card-content">
						<h5 class="h5 mb-10">Rahmat</h5>
						<p class="mb-0">UH Collection</p>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
				<div class="da-card">
					<div class="da-card-photo">
						<img src="{{asset('img/help/pakedwin.jpg')}}" alt="">
						<div class="da-overlay da-slide-bottom">
							<div class="da-social">
								<ul class="clearfix">
									<li><i class="icon-copy dw dw-diamond"></i> Core Sefin Integrate Development</li>
									<li><i class="icon-copy dw dw-server"></i> Database Architecture TagihBoss Sefin Integrate</li>
									<li><i class="icon-copy dw dw-analytics-14"></i></i> System Analyst TagihBoss Sefin Integrate</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="da-card-content">
						<h5 class="h5 mb-10">Edwin</h5>
						<p class="mb-0">IT Programmer</p>
					</div>
				</div>
			</div>
			<div class="col-lg-3 col-md-6 col-sm-12 mb-30">
				<div class="da-card">
					<div class="da-card-photo">
						<img src="{{asset('img/help/bonar.jpg')}}" alt="">
						<div class="da-overlay da-slide-bottom">
							<div class="da-social">
								<ul class="clearfix">
									<li><i class="icon-copy dw dw-antenna"></i> IT Network Infrastructure</li>
									<li><i class="icon-copy dw dw-personal-computer"></i> Network Engine Infratructure Programing Release</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="da-card-content">
						<h5 class="h5 mb-10">Bonar Purba</h5>
						<p class="mb-0">IT Network Infrastructure</p>
					</div>
				</div>
			</div>
		</div>
							
	</div>

	<div class="pd-20 card-box mb-30">
		<h4 class="h4 text-blue mb-10">Introduction</h4>
		<p>TagihBoss adalah WebApp untuk dasbor admin dan panel kontrol. TagihBoss juga adalah Webapp yang sepenuhnya responsif, yang didasarkan pada kerangka kerja CSS Bootstrap 4. Ini menggunakan semua komponen Bootstrap dalam desainnya dan menata ulang banyak plugin yang umum digunakan untuk membuat desain yang konsisten yang dapat digunakan sebagai antarmuka pengguna untuk backend aplikasi. TagihBoss didasarkan pada desain modular, yang memungkinkannya untuk disesuaikan dan dibangun dengan mudah. Dokumentasi ini akan memandu Anda dalam sistem dan menjelajahi berbagai fitur yang disertakan dengan sistem.</p>

		<p>Kami menaruh banyak cinta dan upaya untuk menjadikan TagihBoss sebagai Webapp yang berguna untuk semua orang khususnya team collections, IT, dan team yang bersangkutan. Kami ingin merilis pembaruan jangka panjang yang berkelanjutan dan banyak fitur baru akan segera hadir di rilis mendatang</p>
	</div>
	<h4 class="h4 text-blue mb-10">Note</h4>
	<div class="pd-20 card-box mb-30" data-bgcolor="#ff4747" data-color="#fdfdfd" style="color: rgb(253, 253, 253); background-color: rgb(255, 71, 71);">
		<ul>
			<li class="d-flex pb-20"><i class="dw dw-edit-file font-20 mr-2"></i> Kami telah mengintegrasikan sistem TagihBoss dengan core Micro BPR Online dan juga SEFIN, namun tetap menggunakan kerangka kerja data user dengan terpisah.</li>
			<li class="d-flex pb-20"><i class="dw dw-edit-file font-20 mr-2"></i> Sistem dilengkapi dengan log activity untuk merekam seluruh aktivitas user didalam sistem.</li>
			<li class="d-flex pb-20"><i class="dw dw-edit-file font-20 mr-2"></i> Jika menemukan kesulitan dalam penggunaan sistem, anda bisa menanyakan pada pihak yang ikut serta dalam perancangan sistem.</li>
			<li class="d-flex pb-20"><i class="dw dw-edit-file font-20 mr-2"></i> Sesuai dengan semboyan MRX pada film "Who I'm I" yakni "Tidak ada sistem yang aman, kami menyadari akan banyaknya kekurangan didalam sistem, untuk itu kami sangat mengapresiasi setiap user yang turut serta dalam pengembangan sistem dengan melaporkan berbagai kekurangan yang ada melalui Tools yang akan kami siapkan (coming soon).</li>
		</ul>
	</div>
</div>
@endsection
@section('js')
@endsection