<div class="header">
    <div class="container d-flex justify-content-between align-items-center py-3">
        <div class="name mx-3 d-flex align-items-center gap-3">
            <span class="menu-icon open"><i class="uil uil-bars"></i></span>
            <h1 class="h4 mb-0">@yield('page_title')</h1>
        </div>
        <div class="dropdown user mx-3">
            <div class="dropdown-toggle d-flex align-items-center justify-content-between w-100"
                id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" role="button"
                style="min-width: 180px;">
                <div class="d-flex gap-2 align-items-center">
                    <div class="image me-2">
                        <img src="{{ asset('admin/assets/admin.jpeg') }}" alt="admin" class="rounded-circle" width="32" height="32" />
                    </div>
                    <span>Admin</span>
                </div>
            </div>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li>
                    <form method="POST" action="logoute>
                        @csrf
                        <button type="submit" class="dropdown-item">تسجيل الخروج</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>