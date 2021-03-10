<div class="header">
    <div class="header-left">
      <div class="menu-icon dw dw-menu ml-2"></div>
    </div>
    <div class="header-right">
      <div class="dashboard-setting user-notification">
        <div class="dropdown">
          <a class="dropdown-toggle no-arrow button-helper-support-itman" href="javascript:;" data-toggle="right-sidebar">
            <i class="dw fa fa-user-secret"></i>
          </a>
        </div>
      </div>
      <div class="user-notification">
        <div class="dropdown-drop">
          <a class="dropdown-toggle no-arrow" href="#" id="dropdown-notification" urlCount="{{ url('count-notification') }}">
            <i class="icon-copy dw dw-notification"></i>
            <span class="badge" id="activated-notification"></span>
          </a>
          <div class="rendered-notif-badge-data" urlNotifRendered="{{ url('rendered-notification') }}"></div>
        </div>
      </div>
      <div class="user-info-dropdown">
        <div class="dropdown">
          <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
            <span class="user-icon">
              @if(Auth::user()->gender == 'pria')
              <img src="{{asset('man.png')}}" alt="">
              @elseif(Auth::user()->gender == 'wanita')
              <img src="{{asset('woman.png')}}" alt="">
              @elseif(Auth::user()->gender == 'other')
              <img src="{{asset('transgender.png')}}" alt="">
              @else
              <img src="{{asset('user.png')}}" alt="">
              @endif
            </span>
            <span class="user-name">{{ isset(Auth::user()->name) ? ucfirst(strtolower(Auth::user()->name)) : ucfirst(strtolower(Auth::user()->email)) }}</span>
          </a>
          <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
            <a href="{{url('/profile')}}" class="dropdown-item" type="button" actions="profile"><i class="dw dw-user1"></i> Profile</a>
            <a href="{{url('/help')}}" class="dropdown-item" type="button" actions="documentation"><i class="dw dw-help"></i> Help</a>
            <!-- <button class="dropdown-item" type="button" actions="logout" url="{{ route('logout') }}"><i class="dw dw-logout"></i> Log Out</button> -->
            <a href="{{url('/sign-out')}}" class="dropdown-item" type="button" actions="documentation"><i class="dw dw-logout"></i> Log Out</a>
          </div>
        </div>
      </div>
    </div>
  </div>