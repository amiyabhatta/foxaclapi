<!DOCTYPE html>
<html lang="en">
<head>
    @include('includes.head')
</head>
<body ng-app="authApp">
    
    <div class="container">
            <div ui-view></div>
    </div> 
    
    <?php /*
    <div class="container-fluid" id="wrapper_login">
            @yield('content')
    </div>
    */?>
    
     @include('includes.footer')    
 </body>
</html>
