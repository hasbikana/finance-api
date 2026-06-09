<div class="sidebar" id="sidebar">
    <div class="d-flex align-items-center px-3 py-3 mb-3">
        <i class="bi bi-wallet2 text-white fs-4 me-2"></i>
        <span class="text-white fs-5 fw-bold">FinanceApp</span>
    </div>
    <nav class="nav flex-column">
        <a href="{{ route('web.dashboard') }}" class="nav-link {{ request()->routeIs('web.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('web.transactions.index') }}" class="nav-link {{ request()->routeIs('web.transactions.*') ? 'active' : '' }}">
            <i class="bi bi-arrow-left-right"></i> Transaksi
        </a>
        <a href="{{ route('web.categories.index') }}" class="nav-link {{ request()->routeIs('web.categories.*') ? 'active' : '' }}">
            <i class="bi bi-folder"></i> Kategori
        </a>
        <a href="{{ route('web.wallets.index') }}" class="nav-link {{ request()->routeIs('web.wallets.*') ? 'active' : '' }}">
            <i class="bi bi-wallet"></i> Wallet
        </a>
        <a href="{{ route('web.budgets.index') }}" class="nav-link {{ request()->routeIs('web.budgets.*') ? 'active' : '' }}">
            <i class="bi bi-pie-chart"></i> Budget
        </a>
        <a href="{{ route('web.goals.index') }}" class="nav-link {{ request()->routeIs('web.goals.*') ? 'active' : '' }}">
            <i class="bi bi-bullseye"></i> Target
        </a>
        <div class="mx-3 my-2 border-top border-white border-opacity-25"></div>
        <a href="{{ route('web.reports.monthly') }}" class="nav-link {{ request()->routeIs('web.reports.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart"></i> Laporan
        </a>
        <a href="{{ route('web.insights.index') }}" class="nav-link {{ request()->routeIs('web.insights.*') ? 'active' : '' }}">
            <i class="bi bi-lightbulb"></i> Insight
        </a>
        <div class="mx-3 my-2 border-top border-white border-opacity-25"></div>
        <a href="{{ route('web.notifications.index') }}" class="nav-link {{ request()->routeIs('web.notifications.*') ? 'active' : '' }}">
            <i class="bi bi-bell"></i> Notifikasi
        </a>
        <a href="{{ route('web.profile') }}" class="nav-link {{ request()->routeIs('web.profile') ? 'active' : '' }}">
            <i class="bi bi-person-gear"></i> Profil
        </a>
    </nav>
</div>
