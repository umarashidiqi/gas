<html lang="en">
	<head>
        @include('partial.head')
        @include('partial.css')
        @laravelPWA
	</head>
	<body id="kt_body" class="app-blank" style="background-color: #ffffff">
        <script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-theme-mode")) { themeMode = document.documentElement.getAttribute("data-theme-mode"); } else { if ( localStorage.getItem("data-theme") !== null ) { themeMode = localStorage.getItem("data-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-theme", themeMode); }</script>
		<div class="d-flex flex-column flex-root">
			<div class="d-flex flex-column flex-lg-row flex-column-fluid">
				<div class="d-flex flex-column flex-lg-row-fluid w-lg-40 p-10 order-2 order-lg-1">
					<div class="d-flex flex-center flex-column flex-lg-row-fluid">
						<div class="w-lg-500px p-10">
							<form class="form w-100" novalidate="novalidate" id="formLogin" action="#">
								<div class="text-left mb-11">
									<img alt="Logo" src="{{ asset('images/icons/logoNew_2.png') }}" class="h-60px h-lg-75px">
								</div>

							</form>
						</div>
					</div>
				</div>
                @php
                    $image = ["banner_1.jpg", "banner_2.png","banner_3.jpg","banner_4.jpg", "banner_5.jpg"];
                    $k = array_rand($image);
                @endphp
				<div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-100 bgi-no-repeat bgi-position-center order-1 order-lg-2" style="background-image: url(images/banner/{{ $image[$k] }});">
					<div class="d-flex flex-column flex-center min-h-300px min-h-md-800px py-20 py-lg-15 px-15 px-md-15 w-100">
						<a href="#" class="mb-0">

						</a>
					</div>
				</div>
			</div>
		</div>
        @include('partial.js')
	</body>
</html>
