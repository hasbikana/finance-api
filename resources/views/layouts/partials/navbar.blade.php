<nav class="navbar navbar-top navbar-expand px-3 py-2">
    <button class="btn btn-link text-dark d-md-none me-2" onclick="document.getElementById('sidebar').classList.toggle('show')">
        <i class="bi bi-list fs-4"></i>
    </button>
    <span class="navbar-brand d-md-none fs-5 fw-bold" style="color: #0F766E;">FinanceApp</span>
    <div class="ms-auto d-flex align-items-center gap-2">
        <a href="{{ route('web.notifications.index') }}" class="btn btn-link text-dark position-relative">
            <i class="bi bi-bell fs-5"></i>
            @php
                $unread = \App\Models\Notification::where('user_id', Auth::id())->whereNull('read_at')->count();
            @endphp
            @if($unread > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.6rem;">
                    {{ $unread > 99 ? '99+' : $unread }}
                </span>
            @endif
        </a>
        <div class="dropdown">
            <button class="btn btn-link text-dark dropdown-toggle text-decoration-none d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white" style="width:32px;height:32px;font-size:0.85rem;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <span class="d-none d-sm-inline">{{ Auth::user()->name }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('web.profile') }}"><i class="bi bi-person me-2"></i>Profil</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('web.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
