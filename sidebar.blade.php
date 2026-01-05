<div class="nav-wrapper">
    <nav class="top-navbar">
        <div class="navbar-container">
            <ul class="navbar-nav">

                {{-- القسم الرئيسي --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-home me-1"></i> الرئيسية
                    </a>
                    <ul class="dropdown-menu shadow">
                        <li><a class="dropdown-item" href="">الرئيسية</a></li>
                        <li><a class="dropdown-item" href="">التحليلات</a></li>
                    </ul>
                </li>
                {{-- المشاريع --}}
                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-newspaper me-1"></i> إدارة المشاريع
                    </a>
                    <ul class="dropdown-menu shadow text-end">
                        <li><a class="dropdown-item" href="{{ route('categories.index') }}">الاقسام</a></li>
                        <li><a class="dropdown-item" href="{{ route('projects.index') }}">المشاريع</a></li>
                        <li><a class="dropdown-item" href="#">العملاء</a></li>
                    </ul>
                </li>


                {{-- المستخدمين --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-users me-1"></i> ادارة المستخدمين
                    </a>
                    <ul class="dropdown-menu shadow text-end">
                        <li><a class="dropdown-item" href="{{ route('investors.index') }}">إدارة المستثمرين</a>
                        </li>
                        <li><a class="dropdown-item" href="">إدارة المقترضين</a>
                        </li>
                    </ul>
                </li>
                {{-- الاستثمارات والأرباح --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-users me-1"></i> الاستثمارات والأرباح
                    </a>
                    <ul class="dropdown-menu shadow text-end">
                        <li><a class="dropdown-item" href="">إدارة الاستثمارات</a>
                        </li>
                        <li><a class="dropdown-item" href="">الارباح </a>
                        </li>
                    </ul>
                </li>
                {{-- المعاملات المالية --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-users me-1"></i> المعاملات المالية
                    </a>
                    <ul class="dropdown-menu shadow text-end">
                        <li><a class="dropdown-item" href=""> التحويلات المالية</a>
                        </li>
                        <li><a class="dropdown-item" href=""> إدارة السداد</a>
                        </li>
                    </ul>
                </li>
                {{--  الرسائل --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-envelope me-1"></i> الدعم والمراسلات
                    </a>
                    <ul class="dropdown-menu shadow text-end">
                        <li><a class="dropdown-item" href="">رسائل التواصل</a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('reviews.index') }}">اراء المستثمرين</a></li>
                    </ul>
                </li>

                {{-- الإعدادات --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-cogs me-1"></i> الإعدادات والتقارير
                    </a>
                    <ul class="dropdown-menu shadow text-end">
                        <li><a class="dropdown-item" href="#">الإعدادات المتقدمة </a></li>
                        <li><a class="dropdown-item" href="#"> التقارير والتحليلات</a></li>
                        <li><a class="dropdown-item" href="#"> سجل الأنشطة</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>
</div>
