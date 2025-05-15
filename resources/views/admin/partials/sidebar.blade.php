<div class="sidebar">
    <div class="head">
        <div class="logo">
            <img src="{{ asset('admin/assets/logo.png') }}" alt="Logo" />
        </div>
        <span class="menu-icon close"><i class="uil uil-times"></i></span>
    </div>
    <ul class="links list-unstyled p-0 m-0">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} d-block w-100 px-3 py-2">الصفحة الرئيسية</a>
        </li>
        <li>
            <a href="customers" class="link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }} d-block w-100 px-3 py-2">العملاء</a>
        </li>
        <li>
            <a href="sales" class="link d-block w-100 px-3 py-2">اداره المبيعات</a>
        </li>
        <!-- بقية عناصر القائمة -->
    </ul>
</div>