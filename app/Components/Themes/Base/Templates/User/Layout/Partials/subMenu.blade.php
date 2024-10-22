@inject('menuFactory','App\Blueprint\Services\MenuServices')

<li class="nav-item{{ $isCurrent ? ' active' : '' }}" data-id="{{ $menu['id'] }}"
    @if ($bookmarked) data-bookmarkId="{{ $bookmarked->id }}" @endif>
    @php

    $user = loggedUser();
    @endphp
    @if (($user->package->slug == 'affiliate' || $user->package->slug == 'client') && ($slug == 'network' || $slug == 'e-mail' || $label == 'XOOM' || $label == 'SoHo'))
    <a href="javascript:;" class="nav-link nav-toggle nav-affiliate">
        @if ($menu['icon_image'])
            <img src="{{ asset($menu['icon_image']) }}">
        @else
            <i class="{{ $menu['icon_font'] }}"></i>
        @endif
        {{--<span class="favourite @if ($bookmarked) bookmarked @endif">
            <i class="@if ($bookmarked) fa fa-star @else fal fa-star @endif"></i>
        </span>--}}
        <span class="title">{{ $label }}</span>
        <span class="selected"></span>
        @if(isset($menu['child']) && $menu['child']) <span class="arrow"></span> @endif
    </a>
    @if(isset($menu['child']) && $menu['child'])
        <ul class="sub-menu" style="display: none;">
            {!! $menuFactory->renderLeftMenu($menu['child']) !!}
        </ul>
    @endif
    @else
        <a href="{{ $slug == 'network' ? route('user.tree.genealogyTree1') : $href }}" class="nav-link nav-toggle">
            @if ($menu['icon_image'])
                <img src="{{ asset($menu['icon_image']) }}">
            @else
                <i class="{{ $menu['icon_font'] }}"></i>
            @endif
            <span class="title">{{ $label }}</span>
            <span class="selected"></span>
            @if(isset($menu['child']) && $menu['child']) <span class="arrow"></span> @endif
        </a>
        @if(isset($menu['child']) && $menu['child'])
            <ul class="sub-menu" style="display: none;">
                {!! $menuFactory->renderLeftMenu($menu['child']) !!}
            </ul>
        @endif
    @endif
</li>
