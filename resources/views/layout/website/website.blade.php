<!DOCTYPE html>
<html lang="en">
{{-- head --}}

@include('layout.website.include.head')


<body>

    <!-- ======= Header ======= -->
    
    @if((Route::currentRouteName()!="website.subject.mcqstart") && (Route::currentRouteName()!="website.subject.mcqresult"))
    @include('layout.website.include.header')
    
   
    @include('layout.website.include.navbar')
    @endif
    <!-- End Header -->


    <main>

        @yield('content')
        
    </main>
    @if((Route::currentRouteName()!="website.subject.mcqstart") && (Route::currentRouteName()!="website.subject.mcqresult"))
    @include('layout.website.include.footer')
     @endif

    {{-- script --}}
    @include('layout.website.include.script')


    
    @yield('scripts')

</body>

</html>