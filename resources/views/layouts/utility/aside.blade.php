<div class="left-side-bar">
	<div class="brand-logo">
		<a href="{{url('home')}}">
			<img src="{{asset('FICblk.png')}}" alt="" class="dark-logo" width="20%"><h5 class="dark-logo"><strong>Tagih</strong>Boss</h5>
			<img src="{{asset('ficwhite.png')}}" alt="" class="light-logo" width="20%"><h5 class="light-logo text-light" ><strong>Tagih</strong>Boss</h5>
		</a>
		<div class="close-sidebar" data-toggle="left-sidebar-close">
			<i class="ion-close-round"></i>
		</div>
	</div>
	<div class="menu-block customscroll">
		<div class="sidebar-menu">
			<ul id="accordion-menu">
                <li>
                    <a href="{{url('home')}}" class="dropdown-toggle no-arrow">
                        <span class="micon dw dw-house-1"></span><span class="mtext">Dashboard</span>
                    </a>
                </li>
                @foreach ($getMenuMain as $k => $value)
                @can($value->authorization)
                <li class="dropdown">
                    <a href="{{($value->link !=='javascript:void(0)')? $value->link:'javascript:void(0);'}}" class="dropdown-toggle">
                        <span class="{{ $value->icon }}"></span><span class="mtext">{{ $value->name }}</span>
                    </a>
                    <ul class="submenu">
                        @foreach ($getMenuSub as $key => $sub)
                        @if($value->id == $sub->masters_id)
                        @can($sub->authorization)
                            <li><a href="{{ route($sub->link) }}" class="change-state-url">{{ $sub->name }}</a></li>
                        @endcan
                        @endif
                        @endforeach
                    </ul>
                </li>
                @endcan
                @endforeach
			</ul>
		</div>
	</div>
</div>
<div class="mobile-menu-overlay"></div>