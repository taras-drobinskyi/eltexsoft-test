<div class="sidebar" data-color="orange" data-background-color="white"
     data-image="{{ asset('material') }}/img/sidebar-1.jpg">
    <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
    <div class="logo">
        <a href="https://eltexsoft.com/" class="simple-text logo-normal">
            {{ __('ELTEXSOFT') }}
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="nav-item{{ $activePage == 'user-management' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('user-management') }}">
                    <i class="material-icons">content_paste</i>
                    <p>{{ __('User Management') }}</p>
                </a>
            </li>
        </ul>
    </div>
</div>
