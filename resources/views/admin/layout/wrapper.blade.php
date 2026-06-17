@include('admin.layout.head')
@include('admin.layout.header')
@include('admin.layout.sidebar')
@include('sweetalert::alert')
@yield('content')
<!-- Konten Dinamis -->
@if(isset($content))
    @include($content)
@endif

@include('admin.layout.footer')
