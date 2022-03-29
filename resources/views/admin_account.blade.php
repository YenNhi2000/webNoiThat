<!DOCTYPE HTML>
<html>
<head>
    <title>Tài khoản Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="Glance Design Dashboard Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
    SmartPhone Compatible web template, free WebDesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>

    <!-- Bootstrap Core CSS -->
    <link href="{{asset('public/backend/css/bootstrap.css')}}" rel='stylesheet' type='text/css' />

    <!-- Custom CSS -->
    <link href="{{asset('public/backend/css/style.css')}}" rel='stylesheet' type='text/css' />

    <!-- font-awesome icons CSS-->
    <link href="{{asset('public/backend/css/font-awesome.css')}}" rel="stylesheet"> 
    <!-- //font-awesome icons CSS-->

    <!-- side nav css file -->
    <link href="{{asset('public/backend/css/SidebarNav.min.css')}}" media='all' rel='stylesheet' type='text/css'/>
    <!-- side nav css file -->
    
    <!-- js-->
    <script src="{{asset('public/backend/js/jquery-1.11.1.min.js')}}"></script>
    <script src="{{asset('public/backend/js/modernizr.custom.js')}}"></script>

    <!--webfonts-->
    <link href="//fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext,latin-ext" rel="stylesheet">
    <!--//webfonts-->
    
    <!-- Metis Menu -->
    <script src="{{asset('public/backend/js/metisMenu.min.js')}}"></script>
    <script src="{{asset('public/backend/js/custom.js')}}"></script>
    <link href="{{asset('public/backend/css/custom.css')}}" rel="stylesheet">
    <!--//Metis Menu -->

    <!-- Toast -->
    <link rel="stylesheet" href="http://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <!-- //Toast -->

</head>
<body class="cbp-sp menu-push">
    <div class="main-content">
        <div id="page-wrapper">
            
            @yield('account_layout')

        </div>
    </div>
		
    <!-- Toast -->
    <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
    {!! Toastr::message() !!}

    <!-- Alert -->
    <script>
        $('div.alert').delay(3000).slideUp();
    </script>
	
	<!-- side nav js -->
	<script src="{{asset('public/backend/js/SidebarNav.min.js')}}" type='text/javascript'></script>
	<script>
      $('.sidebar-menu').SidebarNav()
    </script>
	<!-- //side nav js -->
	
	<!-- Classie --><!-- for toggle left push menu script -->
		<script src="{{asset('public/backend/js/classie.js')}}"></script>
		<script>
			var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
				showLeftPush = document.getElementById( 'showLeftPush' ),
				body = document.body;
				
			showLeftPush.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( body, 'cbp-spmenu-push-toright' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
				disableOther( 'showLeftPush' );
			};
			
			function disableOther( button ) {
				if( button !== 'showLeftPush' ) {
					classie.toggle( showLeftPush, 'disabled' );
				}
			}
		</script>
	<!-- //Classie --><!-- //for toggle left push menu script -->
		
	<!--scrolling js-->
	<script src="{{asset('public/backend/js/jquery.nicescroll.js')}}"></script>
	<script src="{{asset('public/backend/js/scripts.js')}}"></script>
	<!--//scrolling js-->
	
	<!-- Bootstrap Core JavaScript -->
   <script src="{{asset('public/backend/js/bootstrap.js')}}"> </script>
	<!-- //Bootstrap Core JavaScript -->
   
</body>
</html> 