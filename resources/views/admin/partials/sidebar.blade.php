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
            <a href="{{ route('admin.categories.index') }}" 
                class="link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }} d-block w-100 px-3 py-2">
                <i class="uil uil-list-ul me-2"></i> الفئات
            </a>

        </li>
        <li>
            <a href="{{ route('admin.products.index') }}" 
            class="link {{ request()->routeIs('admin.products.*') ? 'active' : '' }} d-block w-100 px-3 py-2">
            <i class="uil uil-shopping-bag me-2"></i> المنتجات
            </a>
        </li>
        <li>
            <a href="{{ route('admin.warehouses.index') }}" 
            class="link {{ request()->routeIs('admin.warehouses.*') ? 'active' : '' }} d-block w-100 px-3 py-2">
            <i class="uil uil-shopping-bag me-2"></i> المستودعات
            </a>
        </li>
        <li>
            <a href="{{ route('admin.deliveries.index') }}" 
            class="link {{ request()->routeIs('admin.deliveries.*') ? 'active' : '' }} d-block w-100 px-3 py-2">
            <i class="uil uil-shopping-bag me-2"></i> عملاء التوصيل
            </a>
        </li>
        <li>
            <a href="{{ route('admin.coupons.index') }}" 
            class="link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }} d-block w-100 px-3 py-2">
            <i class="uil uil-shopping-bag me-2"></i> كوبونات الخصم 
            </a>
        </li>
        <li>
            <a href="sales" class="link d-block w-100 px-3 py-2">اداره المبيعات</a>
        </li>
        <!-- بقية عناصر القائمة -->
    </ul>
</div>