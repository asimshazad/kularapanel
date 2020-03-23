<li class="{{ preg_match('/admin.dashboard.*/', request()->route()->getName())? 'active':'' }}">
    <a href="{{ route('admin.dashboard') }}"><i class="fal fa-fw fa-tachometer mr-3"></i>Dashboard</a>
</li>
{{-- menu inject start --}}
{{-- menu inject end --}}
@include('kulara::layouts.menu.admin_menu')
