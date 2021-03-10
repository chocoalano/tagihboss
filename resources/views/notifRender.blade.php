<div class="dropdown-menu dropdown-menu-right show" id="notification-right-data-rendered">
	<div class="d-flex justify-content-end">
		<a href="#" onclick="closedNotif();">x</a>
	</div>
	<hr/>
	<div class="notification-list mx-h-350 customscroll">
		<ul>
			@foreach ($x as $k => $v)
			<li>
				<a href="#" onclick="ShowNotificationDetailData('{{ url($v->notifFrom->link_show) }}','{{$v->id}}');">
					@if($v->notifFrom->event == 'activity')
					<img src="{{ asset('business.png') }}" alt="">
					@elseif($v->notifFrom->event == 'visit')
					<img src="{{ asset('building.png') }}" alt="">
					@elseif($v->notifFrom->event == 'payment')
					<img src="{{ asset('wallet.png') }}" alt="">
					@else
					<img src="{{ asset('customer-service.png') }}" alt="">
					@endif
					<h3>{{$v->notifFrom->event}}</h3>
					<?php echo ($v->show == 'n') ? '<i class="icon-copy dw dw-newspaper-1 text-primary"></i> <strong class="text-primary">Info baru</strong>' : '<i class="icon-copy dw dw-open-book text-success"></i> <strong class="text-info">Info sudah dilihat</strong>'; ?>
					<p>{{$v->notifFrom->desc}}</p>
				</a>
			</li>
			@endforeach
		</ul>
	</div>
	<?php 
		$last=$x->lastPage();
		$current=$x->currentPage();
	 ?>
	@if($last > $current)
	<div class="d-flex justify-content-around mt-3">
		<button type="button" class="btn btn-light" onclick="neXt('{{ $x->nextPageUrl() }}');">Lainya...</button>
		@if($current !== 1)
		<button type="button" class="btn btn-light" onclick="neXt('{{ $x->previousPageUrl() }}');">Kembali...</button>
		@endif
	</div>
	@else
	<div class="d-flex justify-content-around mt-3">
		<button type="button" class="btn btn-light" onclick="neXt('{{ $x->previousPageUrl() }}');">Kembali...</button>
	</div>
	@endif
</div>