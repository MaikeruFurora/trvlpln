<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand bold " href="#">Beat Plan</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item">
            <a class="nav-link" href="{{ auth()->user()->type=="admin"?route('authenticate.admin') : route('authenticate.supervisor') }}"> Activity Callendar</a>
        </li>
        @if (auth()->user()->type=="admin")
        <li class="nav-item">
            <a class="nav-link" href="{{ route('authenticate.dashboard') }}">Dashboard</a>
        </li>
        @endif
        @if (auth()->user()->type=="admin" && auth()->user()->wrhs==NULL)
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Config
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('authenticate.user') }}"> Users</a>
                    <a class="dropdown-item" href="{{ route('authenticate.actvtylist') }}">Activity</a>
                    <a class="dropdown-item" href="{{ route('authenticate.product') }}">Product</a>
                    <a class="dropdown-item" href="{{ route('authenticate.group') }}">Group</a>
                    <a class="dropdown-item" href="{{ route('authenticate.warehouse') }}">Warehouse/Cluster</a>
                    <a class="dropdown-item" href="{{ route('authenticate.audit') }}">Audit Trail</a>
                </div>
            </li>
        @endif
        <li class="nav-item">
          <a  class="nav-link text-danger" style="cursor: pointer"
          onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log out</a>
          <form id="logout-form" action="{{ route('authenticate.signout') }}" method="POST" class="d-none">@csrf</form>
        </li>
      </ul>
      <span class="navbar-text">
        {{ strtoupper(auth()->user()->name) }}
      </span>
    </div>
  </nav>