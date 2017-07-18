<!DOCTYPE html>
<html lang="en">
<head>
    @include('includes.head')
</head>
<body>
	<div class="container-fluid" id="wrapper">
		<div class="row">
			<nav class="sidebar col-xs-12 col-sm-4 col-lg-3 col-xl-2 bg-faded sidebar-style-1">
                            @include('includes.sidebar')
                        </nav>
			
			<main class="col-xs-12 col-sm-8 offset-sm-4 col-lg-9 offset-lg-3 col-xl-10 offset-xl-2 pt-3 pl-4">
				<header class="page-header row justify-center">
					@include('includes.header')
				</header>
				
				<section class="row">
                                    @yield('content')
				</section>
			</main>
		</div>
	</div>

	

     @include('includes.footer')    
 </body>
</html>
