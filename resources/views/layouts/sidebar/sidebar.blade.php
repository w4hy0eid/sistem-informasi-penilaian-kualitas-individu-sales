@php
$segment = Request::segment(1) . '/' . Request::segment(2);

function activeSidebar($uri, $realUri) {
    if ($uri == $realUri) {
        return 'active';
    }
    return '';
}

$menu = [
[
'name' => 'Dasboard',
'icon' => 'fa fa-tachometer-alt me-2',
'single' => false,
'uri' => 'view/dashboard',
'sub' => [],
'role' => 'all',
],
[
'name' => 'Users',
'icon' => 'fa fa-user me-2',
'single' => false,
'uri' => 'view/user',
'sub' => [],
'role' => 'admin',
],
[
'name' => 'Realisasi',
'icon' => 'fa fa-laptop me-2',
'single' => false,
'uri' => 'view/dashboard',
'uri' => '#',
'role' => 'all',
'sub' => [
    [
        'name' => 'Scaling',
        'uri' => 'view/realisasi-scaling',
    ],
    [
        'name' => 'Ngtma',
        'uri' => 'view/realisasi-ngtma',
    ],
    [
        'name' => 'Sustain',
        'uri' => 'view/realisasi-sustain',
    ],
],
],
[
'name' => 'Target',
'icon' => 'fa fa-clipboard me-2',
'single' => false,
'uri' => '#',
'role' => 'all',
'sub' => [
    [
        'name' => 'Ngtma',
        'uri' => 'view/target-ngtma',
    ],
    [
        'name' => 'Scaling',
        'uri' => 'view/target-scaling',
    ],
    [
        'name' => 'Sustain',
        'uri' => 'view/target-sustain',
    ]
],
],
[
'name' => 'Sales Funnel',
'icon' => 'fa fa-cash-register me-2',
'single' => false,
'uri' => 'view/sales',
'sub' => [],
'role' => 'admin',
],
[
'name' => 'Performansi AM',
'icon' => 'fa fa-file-excel me-2',
'single' => false,
'uri' => 'view/generate-report',
'sub' => [],
'role' => 'all',
]
];

@endphp
<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">
        <a href="index.html" class="navbar-brand mx-4 mb-3">
            <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>DASHMIN</h3>
        </a>
        <div class="d-flex align-items-center ms-4 mb-4">
            <div class="position-relative">
                <img class="rounded-circle" src="https://www.clipartmax.com/png/middle/434-4349876_profile-icon-vector-png.png" alt="" style="width: 40px; height: 40px;">
                <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>
            <div class="ms-3">
                <h6 class="mb-0">{{ session()->get('name') }}</h6>
                <span>{{ session()->get('role') }}</span>
            </div>
        </div>
        <div class="navbar-nav w-100">
            @foreach($menu as $me)
            @if (count($me['sub']) === 0 && $me['role'] === session()->get('role'))
            <a href="{{ url($me['uri']) }}" class="nav-item nav-link {{ activeSidebar($me['uri'], $segment); }}"><i class="{{ $me['icon'] }}"></i>{{ $me['name'] }}</a>
            @endif
            @if (count($me['sub']) === 0 && $me['role'] === 'all')
            <a href="{{ url($me['uri']) }}" class="nav-item nav-link {{ activeSidebar($me['uri'], $segment); }}"><i class="{{ $me['icon'] }}"></i>{{ $me['name'] }}</a>
            @endif
            @if (count($me['sub']) > 0 && $me['role'] === 'all')
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle {{ activeSidebar($me['uri'], $segment); }}" data-bs-toggle="dropdown"><i class="{{ $me['icon'] }}"></i>{{ $me['name'] }}</a>
                <div class="dropdown-menu bg-transparent border-0">
                @foreach($me['sub'] as $meSub)
                    <a href="{{ url($meSub['uri']) }}" class="dropdown-item {{ activeSidebar($meSub['uri'], $segment); }}">{{ $meSub['name'] }}</a>
                @endforeach
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </nav>
</div>
