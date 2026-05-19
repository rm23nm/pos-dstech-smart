<!DOCTYPE html>
<!--
Template Name: Kundol Admin - Bootstrap 4 HTML Admin Dashboard Theme
Author: Themes-coder
Website: https://themes-coder.com/
Contact: sales@themes-coder.com
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">
<!--begin::Head-->

<head>
	
	<meta charset="utf-8" />
	<title>Admin | Penjualan FnB</title>
	<meta name="description" content="Updates and statistics" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<!--begin::Fonts-->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
	<!--end::Fonts-->

	<!--begin::Global Theme Styles(used by all pages)-->
	<link href="{{ asset('css/style.css?v=1.0')}}" rel="stylesheet" type="text/css" />
	<!--end::Global Theme Styles-->

	<link href="{{ asset('api/pace/pace-theme-flat-top.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('api/mcustomscrollbar/jquery.mCustomScrollbar.css')}}" rel="stylesheet" type="text/css" />
	
	{{-- <link href="http://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" /> --}}
	<link href="{{asset('api/datatable/jquery.dataTables.min.css')}}" rel="stylesheet" type="text/css" />
	<link href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	<link href="{{asset('api/select2/select2.min.css')}}" rel="stylesheet" />

	<link rel="shortcut icon" href="{{ asset('media/logos/favicon.ico')}}" />
	{{-- <script src="https://www.youtube.com/iframe_api"></script> --}}
    {{-- Datatable --}}
    <link href="{{asset('api/datatable/jquery.dataTables.min.css')}}" rel="stylesheet" type="text/css" />
	<style>
	html, body {
		height: 100%;
		margin: 0;
		padding: 0;
		overflow: hidden;
	}
	.contentPOS {
		height: calc(100vh - 70px); /* Adjust 70px based on header height */
		display: flex;
		flex-direction: column;
	}
	.contentPOS .container-fluid, .contentPOS .row {
		flex-grow: 1;
		display: flex;
		flex-direction: column;
	}
	.contentPOS .col-xl-12 {
		display: flex;
		flex-direction: column;
		flex-grow: 1;
	}
	.card-custom {
		flex-grow: 1;
		display: flex;
		flex-direction: column;
	}
	.card-body {
		flex-grow: 1;
		overflow-y: auto; /* Allow scrolling within card body if needed */
	}
	.slider, .slides, .slide {
		height: 100%;
	}
	.slide img {
		object-fit: contain;
	}
	
	/* Premium Global Typography Override */
	* {
		font-family: 'Outfit', sans-serif !important;
	}
	h1, h2, h3, h4, h5, h6, th, .TotalText, #hours, #min, #sec {
		font-family: 'Plus Jakarta Sans', sans-serif !important;
	}

	/* Premium Cyberpunk Brand Gradient Background Overrides */
	body#tc_body {
		background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #4c0519 100%) !important; /* Premium Dark Navy to Crimson Gradient */
		background-image: none !important;
	}
	
	/* Glassmorphism Header */
	.pos-header {
		background: rgba(30, 58, 138, 0.45) !important; /* Royal Blue Translucent */
		backdrop-filter: blur(16px) !important;
		-webkit-backdrop-filter: blur(16px) !important;
		color: #ffffff !important;
		border-bottom: 1px solid rgba(255, 255, 255, 0.15) !important;
		box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3) !important;
	}
	.pos-header .greeting-text h3 {
		color: #ffffff !important;
		font-weight: 600 !important;
		text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
	}
	.pos-header .greeting-text h3.text-primary {
		color: #60a5fa !important; /* Light neon blue for Hallo sapaan */
	}
	.pos-header .clock .datetime-content ul li {
		color: #ffffff !important;
		font-weight: 700 !important;
		text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
	}
	.pos-header .clock .datetime-content #Date {
		color: rgba(255, 255, 255, 0.8) !important;
		font-weight: 500 !important;
	}

	/* Glassmorphism Sidebar & Card Styles */
	.card-custom {
		background: rgba(15, 23, 42, 0.55) !important; /* Deep Navy Translucent */
		backdrop-filter: blur(16px) !important;
		-webkit-backdrop-filter: blur(16px) !important;
		border: 1px solid rgba(255, 255, 255, 0.1) !important;
		border-radius: 20px !important;
		box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4) !important;
		overflow: hidden;
	}
	.card-custom h4 {
		color: #ffffff !important;
		font-weight: 700 !important;
		letter-spacing: 0.5px;
		border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
		padding-bottom: 15px !important;
		margin-bottom: 15px !important;
	}
	
	/* Modern Non-scrolling Receipt Table */
	#orderTable {
		background: transparent !important;
	}
	#orderTable th {
		color: rgba(255, 255, 255, 0.6) !important;
		font-weight: 600 !important;
		text-transform: uppercase;
		font-size: 0.75rem !important;
		letter-spacing: 1px;
		border-bottom: 1px solid rgba(255, 255, 255, 0.1) !important;
		background: transparent !important;
		background-color: transparent !important;
	}
	#orderTable td {
		color: #ffffff !important;
		font-size: 0.95rem !important;
		border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
		padding: 12px 8px !important;
		background: transparent !important;
		background-color: transparent !important;
	}
	
	/* Receipt Animations */
	#tableBody tr {
		animation: slideInRow 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
	}
	
	@keyframes slideInRow {
		from {
			opacity: 0;
			transform: translateX(-15px);
		}
		to {
			opacity: 1;
			transform: translateX(0);
		}
	}

	/* Neon Calculations Panel */
	.resulttable-pos {
		background: rgba(15, 23, 42, 0.6) !important;
		border-radius: 12px;
		padding: 8px 12px !important;
		border: 1px solid rgba(255, 255, 255, 0.08) !important;
		margin-top: 8px;
	}
	.resulttable-pos .table {
		background: transparent !important;
		margin-bottom: 0 !important;
	}
	.resulttable-pos tr {
		padding: 2px 0 !important;
	}
	.resulttable-pos th {
		color: rgba(255, 255, 255, 0.7) !important;
		font-weight: 500 !important;
		font-size: 0.8rem !important;
		border: none !important;
		background: transparent !important;
		background-color: transparent !important;
		padding: 4px 0 !important;
	}
	.resulttable-pos td {
		border: none !important;
		background: transparent !important;
		background-color: transparent !important;
		padding: 4px 0 !important;
	}
	.resulttable-pos th.text-dark {
		color: rgba(255, 255, 255, 0.75) !important;
		background: transparent !important;
		background-color: transparent !important;
	}
	.resulttable-pos td input.TotalText.form-control {
		background: transparent !important;
		border: none !important;
		color: #ffffff !important;
		font-weight: 600 !important;
		font-size: 0.9rem !important;
		padding: 0 !important;
		height: auto !important;
		box-shadow: none !important;
		text-align: right !important;
	}
	
	/* Voucher Row - Premium Animated */
	#rowVoucherDiscount {
		background: rgba(239, 68, 68, 0.12) !important;
		border-radius: 8px;
		border: 1px solid rgba(239, 68, 68, 0.3) !important;
		padding: 4px 8px !important;
		animation: voucherPulse 1.8s infinite ease-in-out;
	}
	#rowVoucherDiscount th, #rowVoucherDiscount td input.TotalText.form-control {
		color: #fca5a5 !important;
		font-weight: 800 !important;
	}
	@keyframes voucherPulse {
		0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.25); }
		50% { box-shadow: 0 0 12px 4px rgba(239, 68, 68, 0.15); }
		100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.25); }
	}
	/* Idle Welcome Screen */
	#idleWelcome {
		display: none;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		height: 100%;
		text-align: center;
		gap: 18px;
		animation: fadeIn 0.6s ease-out forwards;
	}
	#idleWelcome .idle-brand {
		font-size: 2.5rem;
		font-weight: 800;
		color: #ffffff;
		letter-spacing: 2px;
		text-shadow: 0 0 30px rgba(255,255,255,0.3);
	}
	#idleWelcome .idle-sub {
		font-size: 1.1rem;
		color: rgba(255,255,255,0.6);
		font-weight: 400;
		letter-spacing: 1px;
	}
	
	/* Grand Total Pulse Animation & Neon Light Effect */
	.item-price {
		border-top: 1px solid rgba(255, 255, 255, 0.15) !important;
		padding-top: 10px !important;
		margin-top: 6px !important;
	}
	.item-price th.text-primary {
		color: #00f0ff !important; /* Neon Electric Cyan */
		font-weight: 800 !important;
		font-size: 1.15rem !important;
		text-shadow: 0 0 8px rgba(0, 240, 255, 0.6);
		text-transform: uppercase;
		letter-spacing: 1px;
	}
	.item-price td input.TotalText.form-control {
		color: #00f0ff !important; /* Neon Electric Cyan */
		background: rgba(0, 240, 255, 0.08) !important;
		border: 1.5px solid rgba(0, 240, 255, 0.4) !important;
		border-radius: 10px !important;
		font-weight: 900 !important;
		font-size: 1.5rem !important;
		padding: 6px 12px !important;
		height: auto !important;
		text-align: right !important;
		box-shadow: 0 0 15px rgba(0, 240, 255, 0.3), inset 0 0 8px rgba(0, 240, 255, 0.15) !important;
		text-shadow: 0 0 10px rgba(0, 240, 255, 0.8), 0 0 5px rgba(0, 240, 255, 0.4);
		animation: pulseTotalNeon 2s infinite ease-in-out;
		transition: all 0.3s ease;
	}
	
	@keyframes pulseTotalNeon {
		0% {
			box-shadow: 0 0 12px rgba(0, 240, 255, 0.3), inset 0 0 8px rgba(0, 240, 255, 0.15) !important;
			text-shadow: 0 0 8px rgba(0, 240, 255, 0.8);
		}
		50% {
			box-shadow: 0 0 25px rgba(0, 240, 255, 0.7), inset 0 0 12px rgba(0, 240, 255, 0.3) !important;
			text-shadow: 0 0 15px rgba(0, 240, 255, 1), 0 0 8px rgba(255, 255, 255, 0.5);
			border-color: rgba(0, 240, 255, 0.7) !important;
		}
		100% {
			box-shadow: 0 0 12px rgba(0, 240, 255, 0.3), inset 0 0 8px rgba(0, 240, 255, 0.15) !important;
			text-shadow: 0 0 8px rgba(0, 240, 255, 0.8);
		}
	}

	/* Dynamic QRIS Checkout Screen Overlay */
	.qris-board {
		display: none;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		height: 100%;
		padding: 20px;
		text-align: center;
		animation: fadeIn 0.5s ease-out forwards;
	}
	.qris-card {
		background: rgba(255, 255, 255, 0.95);
		padding: 24px;
		border-radius: 24px;
		box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5), 0 0 40px rgba(59, 130, 246, 0.3);
		position: relative;
		overflow: hidden;
		margin-bottom: 20px;
	}
	.qris-barcode-wrapper {
		position: relative;
		width: 200px;
		height: 200px;
	}
	.qris-laser {
		position: absolute;
		left: 0;
		width: 100%;
		height: 3px;
		background: linear-gradient(to right, transparent, #10b981, transparent);
		box-shadow: 0 0 8px #10b981;
		animation: qrisLaser 2.5s infinite ease-in-out;
		z-index: 10;
	}
	.qris-logo {
		width: 120px;
		margin-bottom: 12px;
	}
	.qris-status {
		color: #60a5fa !important;
		font-size: 0.95rem;
		font-weight: 600;
		letter-spacing: 0.5px;
		animation: blinkStatus 1.5s infinite ease-in-out;
	}
	
	@keyframes qrisLaser {
		0% { top: 0%; }
		50% { top: 100%; }
		100% { top: 0%; }
	}
	@keyframes blinkStatus {
		0% { opacity: 0.4; }
		50% { opacity: 1; }
		100% { opacity: 0.4; }
	}
	@keyframes fadeIn {
		from { opacity: 0; transform: scale(0.95); }
		to { opacity: 1; transform: scale(1); }
	}
</style>
<style type="text/css">
		.TotalText{
			text-align: right;
			pointer-events: none;
		}
		.CenterText{
			text-align: center;
		}

		.scroll-container {
		    width: 100%;
		    overflow-x: auto;
		    padding: 10px;
		}

		.horizontal-list {
		    display: grid; /* Uses CSS Grid */
		    grid-template-columns: repeat(2, 1fr); /* Each row will have 4 items */
		    gap: 10px; /* Adds space between items */
		    list-style: none; /* Removes default list styling */
		    padding: 0; /* Removes default padding */
		    margin: 0; /* Removes default margin */
		}

		.horizontal-list li {
		    background-color: #f0f0f0;
		    padding: 10px;
		    border: 1px solid #ccc;
		    text-align: center;
		}
		.horizontal-list li.active {
		    background-color: #ffcc00; /* Change to the desired color when clicked */
		}


		.horizontal-list-meja {
		    display: grid; /* Uses CSS Grid */
		    grid-template-columns: repeat(4, 1fr); /* Each row will have 4 items */
		    gap: 10px; /* Adds space between items */
		    list-style: none; /* Removes default list styling */
		    padding: 0; /* Removes default padding */
		    margin: 0; /* Removes default margin */
		}

		.horizontal-list-meja li {
		    background-color: #f0f0f0;
		    padding: 10px;
		    border: 1px solid #ccc;
		    text-align: center;
		}
		.horizontal-list-meja li.active {
		    background-color: #ffcc00; /* Change to the desired color when clicked */
		}

		.horizontal-list-meja {
		    display: grid; /* Uses CSS Grid */
		    grid-template-columns: repeat(4, 1fr); /* Each row will have 4 items */
		    gap: 10px; /* Adds space between items */
		    list-style: none; /* Removes default list styling */
		    padding: 0; /* Removes default padding */
		    margin: 0; /* Removes default margin */
		}

		.horizontal-list-meja li {
		    background-color: #f0f0f0;
		    padding: 10px;
		    border: 1px solid #ccc;
		    text-align: center;
		}
		.horizontal-list-meja li.active {
		    background-color: #ffcc00; /* Change to the desired color when clicked */
		}

		/*  */
		.horizontal-list-variant {
		    display: grid; /* Uses CSS Grid */
		    grid-template-columns: repeat(4, 1fr); /* Each row will have 4 items */
		    gap: 10px; /* Adds space between items */
		    list-style: none; /* Removes default list styling */
		    padding: 0; /* Removes default padding */
		    margin: 0; /* Removes default margin */
		}

		.horizontal-list-variant li {
		    background-color: #f0f0f0;
		    padding: 10px;
		    border: 1px solid #ccc;
		    text-align: center;
		}
		.horizontal-list-variant li.active {
		    background-color: #ffcc00; /* Change to the desired color when clicked */
		}
		/*  */
		/* Style for text alignment */
		.aligned-textbox {
		    text-align: right; /* Change 'center' to 'left' or 'right' for different alignments */
		}
		.dx-dropdowneditor-overlay {
		    z-index: 10000!important ; /* Adjust the z-index value as needed */
		}
		.dx-dropdowneditor-input-wrapper{
		    height: 50% !important;
		}

		.productCard:hover{
			cursor: pointer;
		}

		.slider {
            position: relative;
            width: 100%;
            margin: auto;
            overflow: hidden;
            border: 2px solid #ddd;
            border-radius: 10px;
        }
        .slides {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .slide {
            min-width: 100%;
            box-sizing: border-box;
			display: flex;
			justify-content: center;
        }
        .slide img {
            max-width: 100%; /* Makes the image scale down if it's too large */
			height: auto;    /* Maintains the aspect ratio */
			display: block;  /* Ensures no unwanted gaps below the image */
			margin: auto;    /* Centers the image horizontally if needed */
			border-radius: 10px;
        }
        .dots {
            text-align: center;
            margin-top: 10px;
        }
        .dot {
            height: 10px;
            width: 10px;
            margin: 0 5px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            cursor: pointer;
        }
        .dot.active {
            background-color: #717171;
        }

		/* Running Text */

		.marquee-container {
            position: relative;
            width: 100%;
			height: 50px;
            overflow: hidden;
            background-color: #222;
            padding: 10px 0;
        }
        .marquee {
            display: inline-block;
            position: absolute;
            white-space: nowrap;
            animation: scrollText 40s linear infinite, blink 1s step-start infinite;
            font-size: 24px;
            color: #fff;
        }
        @keyframes scrollText {
            from {
                transform: translateX(100%);
            }
            to {
                transform: translateX(-100%);
            }
        }
        @keyframes blink {
            0%, 50% {
                color: yellow;
            }
            51%, 100% {
                color: red;
            }
		}

		table {
            width: 50%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: right;
        }
        th {
            background-color: #f4f4f4;
        }
	</style>
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="tc_body" class="header-fixed header-mobile-fixed subheader-enabled aside-enabled aside-fixed" style="
    @if(isset($company) && $company->TypeBackgraund == 'Color')
        background-color: {{ $company->Backgraund }};
    @elseif(isset($company) && $company->TypeBackgraund == 'Image')
        background-image: url('{{ $company->Backgraund }}');
        background-size: auto;
        background-repeat: repeat;
        background-position: center;
    @endif
">
    <header class="pos-header bg-white">
		<div class="container-fluid">
			<div class="row align-items-center">
				<div class="col-xl-4 col-lg-4 col-md-6 d-flex align-items-center" style="gap:14px;">
					@if(isset($company) && $company->icon)
					<img src="{{ asset('storage/'.$company->icon) }}" onerror="this.src='{{ asset('media/logos/favicon.ico') }}'" alt="Logo" style="height:42px;width:42px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,0.4);box-shadow:0 0 12px rgba(0,0,0,0.3);">
					@endif
					<div class="greeting-text">
						<h3 class="card-label mb-0 font-weight-bold text-primary" style="font-size:0.8rem;opacity:0.8;">SELAMAT DATANG DI</h3>
						<h3 class="card-label mb-0" style="font-size:1rem;font-weight:700;">
							{{ isset($company) ? $company->NamaPartner : Auth::user()->name }}
						</h3>
					</div>
				</div>
				<div class="col-xl-4 col-lg-5 col-md-6 clock-main">
					<div class="clock">
						<div class="datetime-content">
							<ul>
								<li id="hours"></li>
								<li id="point1">:</li>
								<li id="min"></li>
								<li id="point">:</li>
								<li id="sec"></li>
							</ul>
						</div>
						<div class="datetime-content">
							<div id="Date" class=""></div>
						</div>
					</div>
				</div>
				<div class="col-xl-4 col-lg-3 col-md-12 d-none d-lg-flex justify-content-end align-items-center" style="gap:8px;">
					<span style="background:rgba(255,255,255,0.15);border-radius:20px;padding:4px 14px;font-size:0.78rem;color:#fff;font-weight:600;letter-spacing:1px;">✨ CUSTOMER DISPLAY</span>
				</div>
			</div>
		</div>
	</header>
	<div class="contentPOS">
		<div class="container-fluid h-100">
			<div class="row h-100">
				<div class="col-md-7 h-100">
					<div class="card card-custom gutter-b bg-white border-0 h-100">
						@if ($company->RunningText != null && $company->RunningText != "")
							<div class="marquee-container">
								<div class="marquee">{{ $company->RunningText }}</div>
							</div>
						@endif
						<div class="card-body d-flex flex-column" style="position: relative; overflow: hidden; height: 100%;">
							<!-- Idle Welcome Screen -->
							<div id="idleWelcome" class="flex-grow-1">
								<div style="font-size:4rem;">🛍️</div>
								<div class="idle-brand">{{ isset($company) ? strtoupper($company->NamaPartner) : 'SELAMAT DATANG' }}</div>
								<div class="idle-sub">Silakan lakukan pemesanan Anda</div>
								<div style="margin-top:8px;padding:8px 24px;border-radius:30px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);color:rgba(255,255,255,0.5);font-size:0.85rem;">Menunggu transaksi...</div>
							</div>
							<div class="slider flex-grow-1" id="promoSlider" style="display:none;">
								<div class="slides h-100" id="slidesContainer"></div>
								<div class="dots" id="dotsContainer"></div>
							</div>
							<div class="qris-board flex-grow-1" id="qrisBoard">
								<img src="{{ asset('media/logos/qris.png') }}" class="qris-logo" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg'" alt="QRIS Logo">
								<div class="qris-card">
									<div class="qris-barcode-wrapper">
										<div class="qris-laser"></div>
										<img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=https://dstechsmart.com" alt="QRIS QR Code" style="width: 100%; height: 100%; border-radius: 12px;">
									</div>
								</div>
								<div class="qris-status">
									<span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background-color: #3b82f6; margin-right: 8px;"></span>
									MENUNGGU PEMBAYARAN PELANGGAN...
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-5 h-100">
					<div class="card card-custom gutter-b bg-white border-0 h-100">
						<div class="card-body d-flex flex-column">
							<h4 class="text-center">Detail Transaksi</h4>
							<div class="table-responsive" id="printableTable" style="overflow-y: auto; flex: 1 1 auto; margin-bottom: 8px;">
								<table id="orderTable" class="display" style="width:100%">
									<thead>
										<tr>
											<th>Nama Item</th>
											<th>Jumlah</th>
											<th>Harga</th>
										</tr>
									</thead>
									<tbody id="tableBody">
									</tbody>
								</table>
							</div>
							<div class="resulttable-pos">
								<table class="table right-table">
									<tbody>
										<tr class="d-flex align-items-center justify-content-between">
											<th class="border-0 font-size-h5 mb-0 font-size-bold text-dark">Total Items</th>
											<td class="border-0 justify-content-end d-flex text-dark font-size-base">
												<input type="text" name="_TotalItem" id="_TotalItem" value="0" class="form-control TotalText">
											</td>
										</tr>
										<tr class="d-flex align-items-center justify-content-between">
											<th class="border-0 font-size-h5 mb-0 font-size-bold text-dark">Subtotal</th>
											<td class="border-0 justify-content-end d-flex text-dark font-size-base">
												<input type="text" name="_SubTotal" id="_SubTotal" value="0" class="form-control TotalText">
											</td>
										</tr>
										<tr class="d-flex align-items-center justify-content-between">
											<th class="border-0 font-size-h5 mb-0 font-size-bold text-dark">DISCOUNT</th>
											<td class="border-0 justify-content-end d-flex text-dark font-size-base">
												<input type="text" name="_TotalDiskon" id="_TotalDiskon" value="0" class="form-control TotalText">
											</td>
										</tr>
										<tr class="d-flex align-items-center justify-content-between" id="rowVoucherDiscount" style="display:none !important;">
											<th class="border-0 mb-0" style="font-size:0.85rem;">🎟️ VOUCHER HEMAT</th>
											<td class="border-0 justify-content-end d-flex">
												<input type="text" name="_VoucherDiscount" id="_VoucherDiscount" value="0" class="form-control TotalText font-weight-bold" style="color: #fca5a5 !important; background:transparent !important; border:none !important; box-shadow:none !important; text-align:right;">
											</td>
										</tr>
										<tr class="d-flex align-items-center justify-content-between">
											<th class="border-0 font-size-h5 mb-0 font-size-bold text-dark">Tax (P. Hiburan, PPN, P. Makanan)</th>
											<td class="border-0 justify-content-end d-flex text-dark font-size-base">
												<input type="text" name="_TotalTax" id="_TotalTax" value="0" class="form-control TotalText">
											</td>
										</tr>
										<tr class="d-flex align-items-center justify-content-between item-price">
											<th class="border-0 font-size-h5 mb-0 font-size-bold text-primary">TOTAL</th>
											<td class="border-0 justify-content-end d-flex text-primary font-size-base">
												<input type="text" name="_GrandTotal" id="_GrandTotal" value="0" class="form-control TotalText">
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('api/mcustomscrollbar/jquery.mCustomScrollbar.concat.min.js')}}"></script>
<script src="{{ asset('api/datatable/jquery.dataTables.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/script.bundle.js')}}"></script>

<link href="{{ asset('devexpress/dx.light.css')}}" rel="stylesheet" type="text/css" />
<script src="{{asset('devexpress/dx.all.js')}}"></script>
<script src="{{asset('api/select2/select2.min.js')}}"></script>
@if (env('MIDTRANS_IS_PRODUCTION') == false)
<script src="{{ env('MIDTRANS_DEV_URL') }}" data-client-key="{{ $midtransclientkey }}"></script>
@else
<script src="{{ env('MIDTRANS_PROD_URL') }}" data-client-key="{{ $midtransclientkey }}"></script>
@endif
<script>
	window.$ = window.jQuery;
	const itemsDiv = document.getElementById("items");
	const totalAmountSpan = document.getElementById("totalAmount");

	const slides = document.querySelector('.slides');
	const dots = document.querySelectorAll('.dot');
	let currentIndex = 0;
	const slideCount = document.querySelectorAll('.slide').length;

	// window.onload = function () {
    //     if (window.opener && !window.opener.closed) {
    //         window.opener.postMessage({ type: 'say-hello' }, '*');
    //     }
    // };
	let autoPlayTimeout = null;
	let mediaData = <?php echo json_encode($oImageData) ?>;
	let ytPlayers = {}
	let videoStarted = {};
	

	window.addEventListener('beforeunload', function (e) {
		// Lakukan sesuatu sebelum halaman ditutup
		if (window.opener && !window.opener.closed) {
            try {
                window.opener.postMessage('customer-display-closed', '*');
            } catch (err) {
                console.error("Failed to postMessage:", err);
            }
        }
	});
	window.addEventListener('message', function(event) {
		// console.log(event);
		if (event.data === 'paymentgateway') {
			// document.getElementById('status').innerHTML = 'Status: <b>CLOSED</b>';
			// _custdisplayopened = false;
			const oData = JSON.parse(localStorage.getItem("paymentgatewaydata"));
			PaymentGateWay(oData);
		}
	});
	function updateDisplay() {
		if (window.opener && !window.opener.closed) {
            try {
                window.opener.postMessage({ type: 'say-hello' }, '*');
            } catch (err) {
                console.error("Failed to postMessage:", err);
            }
        }

		const cart = JSON.parse(localStorage.getItem("PoSData"));

		const hasItems = cart && Array.isArray(cart.data) && cart.data.length > 0;

		// --- Screen State Manager ---
		if (cart && cart.isCheckout === true) {
			// QRIS / Payment screen
			$('#idleWelcome').hide();
			$('#promoSlider').hide();
			$('#qrisBoard').css('display', 'flex');
		} else if (hasItems) {
			// Active transaction - show promo slider
			$('#idleWelcome').hide();
			$('#qrisBoard').hide();
			$('#promoSlider').show();
		} else {
			// No items - show idle welcome screen
			$('#qrisBoard').hide();
			$('#promoSlider').hide();
			$('#idleWelcome').css('display', 'flex');
		}

		// Reset totals
		const tableBody = document.getElementById("tableBody");
		tableBody.innerHTML = '';
		formatCurrency($('#_TotalItem'), 0);
	    formatCurrency($('#_SubTotal'), 0);
	    formatCurrency($('#_TotalDiskon'), 0);
	    formatCurrency($('#_VoucherDiscount'), 0);
	    formatCurrency($('#_GrandTotal'), 0);
		formatCurrency($('#_TotalTax'), 0);
		// Hide voucher row by default
		$('#rowVoucherDiscount').hide();

		if (hasItems) {
			for (let index = 0; index < cart["data"].length; index++) {
				const newRow = document.createElement("tr");
				const cell1 = document.createElement("td");
				cell1.textContent = cart["data"][index]['NamaItem'];
				const cell2 = document.createElement("td");
				cell2.textContent = cart["data"][index]['Qty'];
				const cell3 = document.createElement("td");
				let formattedAmount = parseFloat(cart["data"][index]['Harga']).toLocaleString('id-ID', {
					style: 'decimal',
					minimumFractionDigits: 0,
					maximumFractionDigits: 0
				});
				cell3.textContent = formattedAmount;
				newRow.appendChild(cell1);
				newRow.appendChild(cell2);
				newRow.appendChild(cell3);
				// Item baru selalu di bawah (append)
				tableBody.appendChild(newRow);
			}
			CalculateTotal(cart);
			// Auto-scroll ke item terbaru (paling bawah)
			const tableWrapper = document.getElementById('printableTable');
			if (tableWrapper) tableWrapper.scrollTop = tableWrapper.scrollHeight;
		}
	}

	 // Auto-slide function
	function autoSlide() {
		currentIndex = (currentIndex + 1) % slideCount;
		updateSlidePosition();
	}

	// Update slide position
	function updateSlidePosition() {
		slides.style.transform = `translateX(-${currentIndex * 100}%)`;
		updateDots();
	}

	// Update active dot
	function updateDots() {
		dots.forEach((dot, index) => {
			dot.classList.toggle('active', index === currentIndex);
		});
	}

	dots.forEach(dot => {
		dot.addEventListener('click', () => {
			currentIndex = parseInt(dot.getAttribute('data-slide'));
			updateSlidePosition();
		});
	});

	function CalculateTotal(data) {
		var _tempTotalItem = data["data"].length;
	    formatCurrency($('#_TotalItem'), _tempTotalItem);
	    formatCurrency($('#_SubTotal'), data["Total"]);
	    formatCurrency($('#_TotalDiskon'), data["Discount"]);
	    formatCurrency($('#_GrandTotal'), data["Net"]);
		formatCurrency($('#_TotalTax'), data["Tax"]);

		// Voucher: tampil hanya jika nilai > 0
		var voucherVal = parseFloat(data["VoucherDiscount"] || 0);
		if (voucherVal > 0) {
			formatCurrency($('#_VoucherDiscount'), voucherVal);
			$('#rowVoucherDiscount').show();
		} else {
			$('#rowVoucherDiscount').hide();
		}
	}

	function formatCurrency(input, amount) {
		input.attr("originalvalue", amount);
        let formattedAmount = parseFloat(amount).toLocaleString('id-ID', {
            style: 'decimal',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });

        // Set the formatted value to the input field
        input.val(formattedAmount);
    }

	function PaymentGateWay(oData){
		console.log(oData);
		fetch( "{{route('pembayaranpenjualan-createpayment')}}", {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-TOKEN': '{{ csrf_token() }}'
			},
			body: JSON.stringify(oData)
		})
		.then(response => response.json())
		.then(data => {
			console.log(data);
			if (data.snap_token) {
				snap.pay(data.snap_token, {
					onSuccess: function(result){
						// console.log(result);
						if(result.transaction_status == "cancel"){
							// Swal.fire({
							// 	icon: "error",
							// 	title: "Opps...",
							// 	text: "Pembayaran Dibatalkan",
							// });
							window.opener.postMessage('payment-cancel', '*');
						}
						else{
							window.opener.postMessage('payment-success', '*');
							// order_id
							// jQuery('#txtRefrensi_Detail').val(result.order_id)
							// SaveData(Status, ButonObject, ButtonDefaultText)
						}
						// Proses pembayaran sukses
					},
					onPending: function(result){
						// console.log(result);
						// Pembayaran tertunda
					},
					onError: function(result){
						// console.log(result);
						// Swal.fire({
						// 	icon: "error",
						// 	title: "Opps...",
						// 	text: result,
						// })
						window.opener.postMessage('payment-error', '*');
						localStorage.setItem('errorresult', result);
						// Pembayaran gagal
					},
					onClose: function(){
						console.log('customer closed the popup without finishing the payment');
						window.opener.postMessage('no-pay', '*');
					}
				});
			} else {
				// alert('Error: ' + data.error);
				// Swal.fire({
				// 	icon: "error",
				// 	title: "Opps...",
				// 	text: data.error,
				// })
				window.opener.postMessage('data-error', data.error);
				localStorage.setItem('errorresult', data.error);
			}
		})
		.catch(error => console.error('Error:', error));
	}

	function extractYouTubeID(url) {
		const regExp = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=)([^#&?]*).*/;
		const match = url.match(regExp);
		return (match && match[2].length === 11) ? match[2] : null;
	}

	function renderSlides() {
		console.log("3. render Slide")
		const slidesContainer = document.getElementById('slidesContainer');
		const dotsContainer = document.getElementById('dotsContainer');

		slidesContainer.innerHTML = '';
		dotsContainer.innerHTML = '';
		ytPlayers = {}; // Reset player cache

		mediaData.forEach((item, index) => {
			const slide = document.createElement('div');
			slide.className = 'slide';

			if (item.type === 'image') {
				const img = document.createElement('img');
				img.src = item.url;
				slide.appendChild(img);
			} else if (item.type === 'video') {
				const videoId = extractYouTubeID(item.url);
				const iframeContainer = document.createElement('div');
				iframeContainer.id = `ytplayer-${index}`;
				slide.appendChild(iframeContainer);
				// Tunda sedikit agar DOM ter-render dulu
				setTimeout(() => createYouTubePlayer(videoId, index), 0);
			}

				slidesContainer.appendChild(slide);

				const dot = document.createElement('span');
				dot.className = 'dot';
				dot.dataset.index = index;
				dot.addEventListener('click', () => {
				currentIndex = index;
				playMedia(index);
			});
			dotsContainer.appendChild(dot);
		});

		// Style container
		slidesContainer.style.display = 'flex';
		slidesContainer.style.transition = 'transform 0.7s ease-in-out';
		document.querySelectorAll('.slide').forEach(slide => {
			slide.style.flex = '0 0 100%';
		});
	}


	function playMedia(index) {
		clearTimeout(autoPlayTimeout);

		const slidesContainer = document.getElementById('slidesContainer');
		const slides = document.querySelectorAll('.slide');
		const dots = document.querySelectorAll('.dot');

		slidesContainer.style.transform = `translateX(-${index * 100}%)`;

		dots.forEach((dot, i) => {
			dot.classList.toggle('active', i === index);
		});

		// Stop semua video lain
		Object.entries(ytPlayers).forEach(([i, player]) => {
			i = parseInt(i);
			if (player && player.stopVideo && i !== index) {
			player.stopVideo();
			}
		});

		const media = mediaData[index];

		if (media.type === 'image') {
			autoPlayTimeout = setTimeout(nextMedia, 5000);
		} else if (media.type === 'video') {
			const player = ytPlayers[index];
			if (player && player.seekTo && player.playVideo) {
			// Pastikan video mulai dari awal
			player.seekTo(0);
			player.playVideo();
			}
		}
	}


	function createYouTubePlayer(videoId, index) {
		const slidesContainer = document.getElementById('slidesContainer');
		const containerWidth = slidesContainer.clientWidth;
		const containerHeight = slidesContainer.clientHeight;
		ytPlayers[index] = new YT.Player(`ytplayer-${index}`, {
			height: containerHeight,
			width: containerWidth,
			videoId: videoId,
			playerVars: {
				autoplay: 0,
				mute: 1,
				controls: 0,
				modestbranding: 1,
				rel: 0,
				enablejsapi: 1
			},
			events: {
				onReady: (event) => {
					if (index === currentIndex) {
						event.target.playVideo();
					}
				},
				onStateChange: (event) => {
					const player = event.target;

					const currentTime = player.getCurrentTime();
					const duration = player.getDuration();

					console.log(event.data + " >> " + currentTime + " >> " + duration)

					if (event.data === YT.PlayerState.PLAYING) {
						// Tandai bahwa video benar-benar mulai diputar
						videoStarted[index] = true;
					}

					if (event.data === YT.PlayerState.ENDED) {
						// Pastikan video benar-benar sudah mulai sebelumnya
						if (videoStarted[index]) {
							
							if (Math.abs(currentTime - duration) <= 1) {
								event.target.stopVideo();
								nextMedia();
							}
						}
					}
				}
			}
		});
	}


	function nextMedia() {
		currentIndex = (currentIndex + 1) % mediaData.length;
		playMedia(currentIndex);
	}

	function loadYouTubeAPI() {
		console.log("1. Load Youtube API")
		if (!window.YT || !YT.Player) {
			const tag = document.createElement('script');
			tag.src = "https://www.youtube.com/iframe_api";
			document.body.appendChild(tag);
		}
	}

	window.onYouTubeIframeAPIReady = function () {
		console.log("2. Youtube API Ready")
		renderSlides();
		playMedia(0);
	};
	
	// Update display whenever localStorage changes
	window.addEventListener("storage", updateDisplay);

	// Initial load
	updateDisplay();

	// Only load YouTube + slides if there is media
	if (mediaData && mediaData.length > 0) {
		loadYouTubeAPI();
	} else {
		// No media - just keep idle or active state via updateDisplay
		// promoSlider stays hidden, idleWelcome will show if no cart items
	}

	updateSlidePosition();
</script>
</html>