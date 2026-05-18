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
	<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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

    {{-- Datatable --}}
    <link href="{{asset('api/datatable/jquery.dataTables.min.css')}}" rel="stylesheet" type="text/css" />
	<style type="text/css">
		:root {
			--primary: #0b57d0; /* PT. DSTECH Royal Blue */
			--primary-hover: #0842a0;
			--secondary: #dc2626; /* PT. DSTECH Action Crimson Red */
			--secondary-hover: #b91c1c;
			--accent: #10b981; /* Neon Emerald */
			--accent-hover: #059669;
			--dark: #0f172a;
			--light: #f8fafc;
			--border-color: rgba(11, 87, 208, 0.08);
			--glass-bg: rgba(255, 255, 255, 0.9);
			--glass-border: rgba(255, 255, 255, 0.5);
			--shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.03);
			--shadow-md: 0 15px 35px -10px rgba(11, 87, 208, 0.12);
			--shadow-lg: 0 30px 60px -15px rgba(11, 87, 208, 0.22);
			--premium-gradient: linear-gradient(135deg, #094cb4 0%, #00bcff 100%); /* PT. DSTECH Royal to Cyan Gradient */
		}

		body {
			font-family: 'Outfit', sans-serif !important;
			background: radial-gradient(at 0% 0%, rgba(11, 87, 208, 0.12) 0px, transparent 50%),
			            radial-gradient(at 50% 0%, rgba(220, 38, 38, 0.08) 0px, transparent 50%),
			            radial-gradient(at 100% 0%, rgba(16, 185, 129, 0.08) 0px, transparent 50%),
			            linear-gradient(rgba(255, 255, 255, 0.82), rgba(255, 255, 255, 0.82)),
			            url('{{ asset("images/misc/bg-login3.jpg") }}') !important;
			background-size: cover, cover, cover, cover, 100% 100% !important; /* Image stretches 100% 100% to fit screen paper completely as a single piece */
			background-position: top center !important;
			background-repeat: no-repeat !important;
			background-attachment: fixed !important;
			color: #1e293b !important;
			height: 100vh !important;
			position: relative;
		}

		/* Premium Grid Overlay */
		body::before {
			content: '';
			position: absolute;
			top: 0; left: 0; right: 0; bottom: 0;
			background-image: linear-gradient(rgba(11, 87, 208, 0.015) 1px, transparent 1px),
			                  linear-gradient(90deg, rgba(11, 87, 208, 0.015) 1px, transparent 1px);
			background-size: 40px 40px;
			pointer-events: none;
			z-index: -1;
		}

		.TotalText{
			text-align: right;
			pointer-events: none;
			font-weight: 800;
			font-size: 1.2rem;
			color: var(--primary);
			border: none;
			background: transparent;
		}
		.CenterText{
			text-align: center;
		}

		/* Premium Header */
		.pos-header {
			background: rgba(255, 255, 255, 0.95) !important;
			backdrop-filter: blur(12px) !important;
			-webkit-backdrop-filter: blur(12px) !important;
			border-bottom: 1.5px solid rgba(11, 87, 208, 0.25) !important;
			padding: 0.5rem 1.5rem !important;
			box-shadow: 0 4px 18px rgba(11, 87, 208, 0.08) !important;
			position: sticky;
			top: 0;
			z-index: 1000;
		}

		.greeting-text {
			background: rgba(255, 255, 255, 0.92) !important;
			padding: 6px 14px !important;
			border-radius: 12px !important;
			border: 1px solid rgba(11, 87, 208, 0.25) !important;
			box-shadow: 0 4px 12px rgba(11, 87, 208, 0.1);
			backdrop-filter: blur(8px);
			-webkit-backdrop-filter: blur(8px);
			display: inline-flex;
			align-items: center;
			gap: 6px;
			height: 38px;
			transition: all 0.2s ease;
		}

		.clock-main {
			background: rgba(255, 255, 255, 0.92) !important;
			border: 1.5px solid rgba(11, 87, 208, 0.3) !important;
			border-radius: 50px !important;
			padding: 0.3rem 1.5rem !important;
			box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
			backdrop-filter: blur(8px);
			-webkit-backdrop-filter: blur(8px);
			height: 38px;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		/* Premium Cards */
		.card {
			background: rgba(255, 255, 255, 0.85) !important;
			backdrop-filter: blur(20px);
			-webkit-backdrop-filter: blur(20px);
			border-radius: 24px !important;
			border: 1px solid rgba(255, 255, 255, 0.6) !important;
			box-shadow: var(--shadow-md) !important;
			transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1), border-color 0.3s ease;
			overflow: hidden;
		}

		.card:hover {
			transform: translateY(-2px);
			box-shadow: var(--shadow-lg) !important;
			border-color: rgba(11, 87, 208, 0.25) !important;
		}

		.premium-total-card {
			background: var(--premium-gradient);
			color: white;
			border-radius: 24px !important;
			padding: 2rem !important;
			position: relative;
			overflow: hidden;
			box-shadow: var(--shadow-lg);
		}

		.premium-total-card::after {
			content: '';
			position: absolute;
			top: -50%; left: -50%;
			width: 200%; height: 200%;
			background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 60%);
			pointer-events: none;
		}

		/* Inputs */
		.form-control {
			border-radius: 12px !important;
			border: 1.5px solid rgba(11, 87, 208, 0.15) !important;
			padding: 0.75rem 1rem !important;
			transition: all 0.3s ease;
		}

		.form-control:focus {
			border-color: var(--primary) !important;
			box-shadow: 0 0 0 4px rgba(11, 87, 208, 0.1) !important;
		}

		/* Buttons */
		.btn {
			border-radius: 12px !important;
			font-weight: 600 !important;
			padding: 0.8rem 1.5rem !important;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
		}

		.btn-primary {
			background: var(--premium-gradient) !important;
			border: none !important;
			box-shadow: 0 8px 20px rgba(11, 87, 208, 0.2) !important;
		}

		.btn-primary:hover {
			transform: translateY(-2px);
			box-shadow: 0 12px 25px rgba(11, 87, 208, 0.3) !important;
		}

		#_GrandTotal {
			font-size: 2.5rem !important;
			height: auto !important;
			color: white !important;
			text-shadow: 0 2px 4px rgba(0,0,0,0.1);
		}

		.productCard {
			border-radius: 16px !important;
			transition: all 0.3s ease !important;
			border: 1px solid rgba(11, 87, 208, 0.08) !important;
			background: white;
		}

		.productCard:hover {
			transform: scale(1.05);
			box-shadow: 0 15px 30px rgba(11, 87, 208, 0.12) !important;
			cursor: pointer;
		}

		/* Premium Addons Dialog styling */
		.horizontal-list-variant, .horizontal-list-meja {
			display: grid;
			grid-template-columns: repeat(4, 1fr);
			gap: 12px;
			padding: 0;
			margin: 0;
			list-style: none;
		}

		.horizontal-list-variant li, .horizontal-list-meja li {
			background-color: rgba(11, 87, 208, 0.04);
			padding: 12px;
			border: 1.5px solid rgba(11, 87, 208, 0.08);
			border-radius: 12px;
			text-align: center;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.2s ease;
		}

		.horizontal-list-variant li:hover, .horizontal-list-meja li:hover {
			background-color: rgba(11, 87, 208, 0.08);
			transform: translateY(-1px);
		}

		.horizontal-list-variant li.active, .horizontal-list-meja li.active {
			background-color: var(--primary) !important;
			color: white !important;
			border-color: var(--primary) !important;
			box-shadow: 0 4px 12px rgba(11, 87, 208, 0.2);
		}

		/* Compact Doc Badge */
		.doc-badge-container {
			background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
			border-radius: 14px;
			padding: 0.5rem 1rem;
			display: inline-flex;
			align-items: center;
			gap: 8px;
			border: 1px solid rgba(0,0,0,0.04);
		}

		#_NoTransaksi {
			font-weight: 800 !important;
			font-size: 1.15rem !important;
			color: var(--primary) !important;
			letter-spacing: 0.5px;
		}

		/* Inputs Redesign */
		.form-control, select.form-control {
			border-radius: 14px !important;
			border: 1.5px solid #cbd5e1 !important;
			padding: 0.75rem 1rem !important;
			font-size: 0.95rem !important;
			color: #1e293b !important;
			background-color: #ffffff !important;
			box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.01) !important;
			transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
		}

		.form-control:focus {
			border-color: var(--primary) !important;
			box-shadow: 0 0 0 4px rgba(11, 87, 208, 0.12) !important;
			background-color: #ffffff !important;
		}

		/* Advanced Touch NumPad Styles */
		.numpad-grid {
			display: grid;
			grid-template-columns: repeat(4, 1fr);
			gap: 8px;
		}
		.btn-numpad {
			background: rgba(255, 255, 255, 0.8) !important;
			backdrop-filter: blur(5px);
			border: 1.5px solid #cbd5e1 !important;
			color: #1e293b !important;
			height: 38px !important;
			border-radius: 14px !important;
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02), inset 0 2px 0 rgba(255,255,255,0.2) !important;
			transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1) !important;
			display: flex !important;
			flex-direction: column !important;
			align-items: center !important;
			justify-content: center !important;
			padding: 2px !important;
		}
		.btn-numpad:hover {
			background: #f1f5f9 !important;
			border-color: #94a3b8 !important;
			transform: translateY(-1px);
			box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05) !important;
		}
		.btn-numpad:active {
			transform: translateY(1px);
			box-shadow: none !important;
		}
		.numpad-num {
			font-size: 1.3rem !important;
			font-weight: 950 !important;
			line-height: 1;
		}
		.numpad-sub {
			font-size: 0.65rem !important;
			color: #64748b !important;
			font-weight: 800 !important;
			letter-spacing: 0.5px;
			margin-top: 1px;
		}

		/* Compact QWERTY Touch Keypad Styles */
		.btn-qwerty {
			background: rgba(255, 255, 255, 0.95) !important;
			border: 1.5px solid #cbd5e1 !important;
			color: #0f172a !important;
			font-weight: 950 !important;
			font-size: 1.25rem !important;
			height: 38px !important;
			min-width: 36px;
			padding: 0 !important;
			border-radius: 12px !important;
			box-shadow: 0 2px 4px rgba(0,0,0,0.04), inset 0 2px 0 rgba(255,255,255,0.2) !important;
			display: flex;
			align-items: center;
			justify-content: center;
			transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1) !important;
			flex: 1;
		}
		.btn-qwerty:hover {
			background: #f8fafc !important;
			border-color: #94a3b8 !important;
			transform: translateY(-1px);
		}
		.btn-qwerty:active {
			background: #e2e8f0 !important;
			transform: translateY(1px);
		}
		.btn-qwerty-action {
			background: #f1f5f9 !important;
			border-color: #cbd5e1 !important;
		}
		.btn-numpad-action {
			background: rgba(11, 87, 208, 0.06) !important;
			border: 1.5px solid rgba(11, 87, 208, 0.18) !important;
			color: var(--primary) !important;
			font-weight: 950 !important;
			font-size: 1.1rem !important;
			height: 38px !important;
			border-radius: 14px !important;
			transition: all 0.15s ease !important;
		}
		.btn-numpad-action:hover, .btn-numpad-action.active {
			background: var(--primary) !important;
			color: white !important;
			border-color: var(--primary) !important;
			box-shadow: 0 4px 12px rgba(11, 87, 208, 0.2) !important;
		}
		.btn-numpad-clear {
			background: rgba(220, 38, 38, 0.06) !important;
			border: 1.5px solid rgba(220, 38, 38, 0.18) !important;
			color: var(--secondary) !important;
			font-weight: 950 !important;
			font-size: 1.2rem !important;
			height: 38px !important;
			border-radius: 14px !important;
			transition: all 0.15s ease !important;
		}
		.btn-numpad-clear:hover {
			background: var(--secondary) !important;
			color: white !important;
			border-color: var(--secondary) !important;
			box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2) !important;
		}
		.btn-numpad-enter {
			background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
			border: none !important;
			color: white !important;
			font-weight: 950 !important;
			font-size: 1.2rem !important;
			height: 38px !important;
			border-radius: 14px !important;
			box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35) !important;
			transition: all 0.15s ease !important;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.btn-numpad-enter:hover {
			box-shadow: 0 6px 18px rgba(16, 185, 129, 0.45) !important;
			transform: translateY(-1px) scale(1.02);
		}

		/* Compact Product Card Grid Catalog Styles */
		.catalog-product-card {
			background: rgba(255, 255, 255, 0.85) !important;
			backdrop-filter: blur(10px);
			border-radius: 12px !important;
			border: 1.5px solid rgba(255, 255, 255, 0.6) !important;
			padding: 0.5rem !important;
			cursor: pointer;
			transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
			box-shadow: 0 2px 4px rgba(0, 0, 0, 0.01) !important;
			position: relative;
			overflow: hidden;
			height: 165px;
			display: flex;
			flex-direction: column;
			justify-content: flex-start;
			gap: 4px;
		}
		.catalog-product-card:hover {
			transform: translateY(-2px);
			box-shadow: 0 8px 16px rgba(11, 87, 208, 0.1) !important;
			border-color: rgba(11, 87, 208, 0.3) !important;
			background: #ffffff !important;
		}
		.catalog-product-card:hover img {
			transform: scale(1.06);
		}
		.catalog-product-title {
			font-size: 0.72rem;
			font-weight: 750;
			color: #1e293b;
			line-height: 1.25;
			margin: 0;
			display: -webkit-box;
			-webkit-line-clamp: 2;
			-webkit-box-orient: vertical;
			overflow: hidden;
			height: 2.5em;
			flex-grow: 1;
		}
		.catalog-product-footer {
			display: flex;
			align-items: center;
			justify-content: space-between;
			margin-top: auto;
			padding-top: 4px;
		}
		.catalog-product-price {
			font-size: 0.78rem;
			font-weight: 850;
			color: #059669;
		}

		/* shortcuts (Sleek and Compact) */
		.alfamart-shortcut-grid {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
			gap: 8px;
			margin-top: 0.5rem;
		}
		.btn-alfamart-tile {
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			padding: 0.35rem 0.5rem !important;
			border-radius: 12px !important;
			color: white !important;
			font-weight: 800 !important;
			font-size: 0.75rem !important;
			cursor: pointer;
			transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
			border: none !important;
			box-shadow: 0 2px 6px rgba(0,0,0,0.05);
			height: 55px;
		}
		.btn-alfamart-tile:hover {
			transform: translateY(-2px);
			box-shadow: 0 6px 15px rgba(0,0,0,0.12) !important;
			filter: brightness(1.08);
		}
		.btn-alfamart-tile:active {
			transform: translateY(1px);
			box-shadow: none !important;
		}
		.btn-alfamart-tile kbd {
			background: rgba(255, 255, 255, 0.25);
			border-radius: 4px;
			padding: 1px 6px;
			font-size: 0.68rem;
			font-weight: 900;
			margin-bottom: 2px;
			color: white;
			box-shadow: 0 1px 2px rgba(0,0,0,0.1);
		}
		.tile-f2 { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important; }
		.tile-f3 { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important; }
		.tile-f4 { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%) !important; }
		.tile-f5 { background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; }
		.tile-f6 { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important; }
		.tile-f7 { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%) !important; }
		.tile-del { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important; }

		/* Barcode Input Scan line */
		.barcode-wrapper {
			position: relative;
		}
		.barcode-wrapper input {
			padding-left: 45px !important;
			font-weight: 600;
			letter-spacing: 0.5px;
		}
		.barcode-icon {
			position: absolute;
			left: 16px;
			top: 50%;
			transform: translateY(-50%);
			color: #94a3b8;
			z-index: 10;
			pointer-events: none;
			transition: color 0.2s ease;
		}
		.barcode-wrapper input:focus + .barcode-icon {
			color: var(--primary);
			animation: pulseScan 1.5s infinite;
		}
		@keyframes pulseScan {
			0% { opacity: 0.5; }
			50% { opacity: 1; }
			100% { opacity: 0.5; }
		}

		/* Right Column: Premium Digital Receipt Tape Container */
		.receipt-tape {
			background: rgba(255, 255, 255, 0.95) !important;
			backdrop-filter: blur(20px);
			-webkit-backdrop-filter: blur(20px);
			border-radius: 28px !important;
			border: 2px dashed rgba(11, 87, 208, 0.25) !important;
			padding: 1.25rem !important;
			position: relative;
			box-shadow: var(--shadow-lg) !important;
		}
		.receipt-tape::before {
			content: '';
			position: absolute;
			top: -4px; left: 10px; right: 10px;
			height: 8px;
			background-image: radial-gradient(circle, rgba(11, 87, 208, 0.2) 4px, transparent 5px);
			background-size: 12px 8px;
		}
		.receipt-tape::after {
			content: '';
			position: absolute;
			bottom: -4px; left: 10px; right: 10px;
			height: 8px;
			background-image: radial-gradient(circle, rgba(11, 87, 208, 0.2) 4px, transparent 5px);
			background-size: 12px 8px;
		}

		.shop-profile {
			border-bottom: 1.5px dashed rgba(11, 87, 208, 0.15);
			padding-bottom: 1rem;
			margin-bottom: 1rem;
		}
		.shop-profile .media .bg-primary {
			border-radius: 16px !important;
			font-weight: 900;
			box-shadow: 0 8px 24px rgba(11, 87, 208, 0.25);
			background: var(--premium-gradient) !important;
		}
		.shop-profile .title {
			font-size: 1.15rem;
			color: #0f172a;
			margin-bottom: 4px;
			font-weight: 850;
		}
		.shop-profile p {
			margin-bottom: 2px;
			font-size: 0.8rem;
			color: #475569;
		}
		.right-table th {
			font-size: 0.9rem;
			color: #475569 !important;
			font-weight: 700 !important;
		}

		/* Glowing LED Total Tagihan Board */
		.premium-total-card {
			background: linear-gradient(135deg, #0f172a 0%, #030712 100%) !important;
			border: 2.5px solid rgba(0, 255, 196, 0.65) !important;
			color: #ffffff;
			border-radius: 24px !important;
			padding: 1.15rem 1.5rem !important;
			position: relative;
			overflow: hidden;
			box-shadow: 0 25px 50px rgba(0, 255, 196, 0.25), inset 0 2px 4px rgba(255, 255, 255, 0.05);
		}
		.premium-total-card::after {
			content: '';
			position: absolute;
			top: -40%; left: -40%;
			width: 180%; height: 180%;
			background: radial-gradient(circle, rgba(0, 255, 196, 0.15) 0%, transparent 60%);
			pointer-events: none;
		}
		#_GrandTotal {
			font-family: 'Courier New', Courier, monospace !important;
			font-size: 3.35rem !important;
			font-weight: 950 !important;
			height: auto !important;
			color: #00ffc4 !important; /* Premium Cyan Neon Glow */
			text-shadow: 0 0 20px rgba(0, 255, 196, 0.95), 0 0 3px rgba(0, 255, 196, 1) !important;
			text-align: right !important;
			border: none !important;
			background: transparent !important;
			padding: 0 !important;
			box-shadow: none !important;
		}

		/* Action Grid Buttons */
		.buttons-cash {
			margin-top: 1.5rem;
			width: 100%;
		}
		.buttons-cash > div {
			display: grid;
			grid-template-columns: 1fr;
			gap: 10px;
			width: 100%;
		}
		.btn-primary {
			background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; /* Green for Bayar */
			border: none !important;
			box-shadow: 0 8px 25px rgba(16, 185, 129, 0.35) !important;
			padding: 0.9rem !important;
			font-size: 1.05rem !important;
		}
		.btn-primary:hover {
			transform: translateY(-2px) scale(1.01);
			box-shadow: 0 12px 30px rgba(16, 185, 129, 0.45) !important;
		}
		.btn-danger {
			background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%) !important; /* Crimson red for cancel */
			border: none !important;
			box-shadow: 0 6px 20px rgba(244, 63, 94, 0.25) !important;
			padding: 0.9rem !important;
		}
		.btn-danger:hover {
			transform: translateY(-2px);
			box-shadow: 0 10px 25px rgba(244, 63, 94, 0.35) !important;
		}
		.btn-secondary {
			background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important; /* Gold/orange for pending draft */
			border: none !important;
			color: white !important;
			box-shadow: 0 6px 20px rgba(245, 158, 11, 0.2) !important;
			padding: 0.9rem !important;
		}
		.btn-secondary:hover {
			transform: translateY(-2px);
			box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3) !important;
		}
		
		/* Highlight selected row in F&B receipt table */
		#AppendArea tr.selected-row {
			background-color: rgba(11, 87, 208, 0.08) !important;
			border-left: 4px solid var(--primary) !important;
		}
		#AppendArea tr.selected-row td input {
			background-color: rgba(255, 255, 255, 0.95) !important;
			font-weight: 850 !important;
			border-color: var(--primary) !important;
		}
		.btn-alfamart-tile.active {
			transform: scale(0.95);
			box-shadow: none !important;
			filter: brightness(0.85);
		}
	</style>
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="tc_body" class="header-fixed header-mobile-fixed subheader-enabled aside-enabled aside-fixed">
   <!-- Paste this code after body tag -->
   <!-- s -->
   <!-- pos header -->

   <header class="pos-header bg-white">
	   <div class="container-fluid">
		   <div class="row align-items-center">
			   <div class="col-xl-4 col-lg-4 col-md-6">
				   <div class="greeting-text">
					<h3 class="card-label mb-0 font-weight-bold text-primary">WELCOME
					</h3>
					<h3 class="card-label mb-0 ">
						{{ Auth::user()->name }}
					</h3>
				   </div>
			
			   </div>
			   <div class="col-xl-4 col-lg-5 col-md-6  clock-main">
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
					<div id="Date"  class=""></div>
				 </div>
				
				</div>
				
			   </div>
			   <div class="col-xl-4 col-lg-3 col-md-12  order-lg-last order-second">

				<div class="topbar justify-content-end">
					<div class="topbar-item folder-data">
					 <div class="btn btn-icon  w-auto h-auto btn-clean d-flex align-items-center py-0 me-3"  data-bs-toggle="modal" data-bs-target="#folderpop"
					 >
						 <span class="badge badge-pill badge-primary" id="_draftCount">0</span>
						 <span class="symbol symbol-35  symbol-light-success">
							 <span class="symbol-label bg-warning font-size-h5 ">
								 <svg width="20px" height="20px" xmlns="http://www.w3.org/2000/svg" fill="#ffff"  viewBox="0 0 16 16">
									 <path d="M9.828 3h3.982a2 2 0 0 1 1.992 2.181l-.637 7A2 2 0 0 1 13.174 14H2.826a2 2 0 0 1-1.991-1.819l-.637-7a1.99 1.99 0 0 1 .342-1.31L.5 3a2 2 0 0 1 2-2h3.672a2 2 0 0 1 1.414.586l.828.828A2 2 0 0 0 9.828 3zm-8.322.12C1.72 3.042 1.95 3 2.19 3h5.396l-.707-.707A1 1 0 0 0 6.172 2H2.5a1 1 0 0 0-1 .981l.006.139z"></path>
								   </svg>
							 </span>
						 </span>
					 </div>
				 
					</div>
			 
				 <div class="dropdown">
					 <div class="topbar-item" data-bs-toggle="dropdown" data-display="static">
						 <div class="btn btn-icon w-auto h-auto btn-clean d-flex align-items-center py-0">
						 
							 <span class="symbol symbol-35 symbol-light-success">
								 <span class="symbol-label font-size-h5 ">
									 <svg width="20px" height="20px" viewBox="0 0 16 16" class="bi bi-person-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
										 <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"></path>
									 </svg>
								 </span>
							 </span>
						 </div>
					 </div>
 
					 <div class="dropdown-menu dropdown-menu-right" style="min-width: 150px;">
 
 
						 <a href="{{ route('logout') }}" class="dropdown-item">
							 <span class="svg-icon svg-icon-xl svg-icon-primary me-2">
								 <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-power">
									 <path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path>
									 <line x1="12" y1="2" x2="12" y2="12"></line>
								 </svg>
							 </span>
							 Logout
						 </a>
					 </div>
 
				 </div>
				</div>
		 
				</div>
		   </div>
	   </div>
   </header>
	<form id="PoSSubmit">
		<div class="contentPOS">
			<div class="container-fluid">
				<div class="row">
					<!-- Column 1: col-xl-6 col-lg-6 col-md-12 d-flex flex-column -->
					<div class="col-xl-6 col-lg-6 col-md-12 d-flex flex-column">
						<div class="card card-custom gutter-b bg-white border-0 flex-grow-1 d-flex flex-column" style="min-height: 600px;">
							<div class="card-body d-flex flex-column p-4">
								<div class="d-flex justify-content-between align-items-center mb-3">
									<div class="selectmain">
										<select class="arabic-select w-200px bag-primary" id="cboJenisItem">
											<option value="">Semua Kelompok Item</option>
											@foreach ($jenisitem as $item)
												<option value="{{ $item->KodeJenis }}">{{ $item->NamaJenis }}</option>
											@endforeach
										</select>
									</div>
									<div class="d-flex gap-2">
										<div class="badge bg-primary text-white font-weight-bold px-3 py-2 rounded">FnB CATALOG</div>
									</div>
								</div>
								<hr class="my-2">
								<div class="product-items flex-grow-1" style="overflow-y: auto; max-height: 480px; padding-right: 4px;">
									<div class="row g-2" id="lsvProductList">
										@if (count($itemmenu) > 0)
											@foreach ($itemmenu as $item)
											<div class="col-xl-4 col-lg-3 col-md-4 col-sm-6 col-6" >
												<div class="catalog-product-card ProductSelected" data-KodeItem= "{{ $item->KodeItem }}" >
													<img class="img-fluid rounded mb-1" src="{{ $item->Gambar }}" alt="ix" style="height: 80px; width: 100%; object-fit: cover;">
													<h5 class="catalog-product-title">{{ $item->NamaItem }}</h5>
													<div class="catalog-product-footer">
														<span class="catalog-product-price">Rp. {{ number_format($item->HargaJual) }}</span>
													</div>
												</div>
											</div>
											@endforeach
										@endif
									</div>
								</div>
								
								<!-- Action Shortcut Tiles -->
								<div class="alfamart-shortcut-grid mt-4">
									<div class="btn-alfamart-tile tile-f2" id="tileF2">
										<kbd>F2</kbd>
										<span>Edit Qty</span>
									</div>
									<div class="btn-alfamart-tile tile-f3" id="tileF3">
										<kbd>F3</kbd>
										<span>Disc %</span>
									</div>
									<div class="btn-alfamart-tile tile-f4" id="tileF4">
										<kbd>F4</kbd>
										<span>Disc Rp</span>
									</div>
									<div class="btn-alfamart-tile tile-f5" id="tileF5">
										<kbd>F5</kbd>
										<span>Bayar</span>
									</div>
									<div class="btn-alfamart-tile tile-f6" id="tileF6">
										<kbd>F6</kbd>
										<span>Draft</span>
									</div>
									<div class="btn-alfamart-tile tile-f7" id="tileF7">
										<kbd>F7</kbd>
										<span>Biaya Jasa</span>
									</div>
									<div class="btn-alfamart-tile tile-del" id="tileDEL">
										<kbd>DEL</kbd>
										<span>Batal</span>
									</div>
								</div>

							</div>	
						</div>
					</div>

					<!-- Column 2: col-xl-3 col-lg-3 col-md-12 d-flex flex-column -->
					<div class="col-xl-3 col-lg-3 col-md-12 d-flex flex-column">
						<!-- Barcode Scanner Card -->
						<div class="card card-custom gutter-b bg-white border-0 mb-3">
							<div class="card-body p-3">
								<div class="barcode-wrapper">
									<i class="fas fa-barcode barcode-icon font-size-h3"></i>
									<input type="text" class="form-control" id="_Barcode" placeholder="Scan Barcode / Tekan Enter..." autofocus autocomplete="off" style="font-weight: 800; font-size: 1.1rem; color: var(--primary);">
								</div>
								<div id="_activeInputIndicator" class="mt-2 text-center font-weight-bold" style="font-size: 0.8rem; letter-spacing: 0.5px; color: #dc2626;">
									STATUS INPUT: <span class="badge bg-danger text-white font-weight-extrabold px-2 py-1">BARCODE SCANNER</span>
								</div>
							</div>
						</div>

						<!-- F&B Order Info Card -->
						<div class="card card-custom gutter-b bg-white border-0 mb-3">
							<div class="card-body p-3">
								<div class="text-center mb-2">
									<label class="text-muted font-size-sm mb-1 font-weight-bold">Nomor Dokumen</label>
									<div class="doc-badge-container d-block">
										<span id="_NoTransaksi" class="d-block text-center font-weight-bold">&lt;OTOMATIS&gt;</span>
									</div>
								</div>
								
								<div class="shop-profile mb-2">
									<div class="media align-items-center">
										<div class="bg-primary w-40px h-40px rounded d-flex justify-content-center align-items-center me-2">
											<span class="mb-0 white font-weight-bold" style="font-size: 1rem;">{{ substr($company[0]['NamaPartner'], 0, 1) }}</span>
										</div>
										<div class="media-body">
											<h6 class="title font-weight-bold mb-0" style="font-size: 0.85rem;">
												{{ $company[0]['NamaPartner'] }}
											</h6>
											<p class="phoonenumber mb-0" style="font-size: 0.7rem; color: #64748b;">
												{{ $company[0]['NoTlp'] }}
											</p>
										</div>
									</div>
								</div>

								<!-- Tipe Order & Nomor Meja -->
								<div class="row g-2 mt-2">
									<div class="col-6">
										<button type="button" class="btn btn-secondary w-100 py-2 d-flex flex-column align-items-center justify-content-center" id="btTipeOrder" style="height: 55px; border-radius: 12px !important;">
											<span class="font-size-xs text-uppercase text-white" style="opacity: 0.8; font-size: 0.65rem;">Tipe Order</span>
											<div id="txtTipeOrder" class="w-100">
												<h6 class="mb-0 text-white font-weight-bold" style="font-size: 0.85rem;">Pilih</h6>
											</div>
										</button>
									</div>
									<div class="col-6">
										<button type="button" class="btn btn-success w-100 py-2 d-flex flex-column align-items-center justify-content-center" id="btNomorMeja" style="height: 55px; border-radius: 12px !important; background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; border: none !important;">
											<span class="font-size-xs text-uppercase text-white" style="opacity: 0.8; font-size: 0.65rem;">Nomor Meja</span>
											<div id="txtNomorMeja" class="w-100">
												<h6 class="mb-0 text-white font-weight-bold" style="font-size: 0.85rem;">Pilih</h6>
											</div>
										</button>
									</div>
								</div>
							</div>
						</div>

						<!-- Business Partner Selector Card -->
						<div class="card card-custom gutter-b bg-white border-0 mb-3">
							<div class="card-body p-3">
								<div class="form-group mb-2">
									<label class="text-dark font-weight-bold font-size-sm mb-1">Business Partner</label>
									<div class="d-flex">
										<select class="form-control" id="KodePelanggan" name="KodePelanggan" style="width: 100%;">
											<option value="">Pilih Pelanggan</option>
											@foreach($pelanggan as $ko)
												<option value="{{ $ko->KodePelanggan }}">
													{{ $ko->NamaPelanggan }}
												</option>
											@endforeach
										</select>
										<button type="button" class="btn btn-success ms-1 p-2 d-flex align-items-center justify-content-center" id="btSearchCustomer" style="width: 38px; height: 38px; border-radius: 12px !important;"><i class="fas fa-search"></i></button>
										<button type="button" class="btn btn-primary ms-1 p-2 d-flex align-items-center justify-content-center" id="btAddCustomer" style="width: 38px; height: 38px; border-radius: 12px !important;"><i class="fas fa-plus"></i></button>
									</div>
								</div>
								
								<div class="form-group mb-0">
									<label class="text-dark font-weight-bold font-size-sm mb-1">Referensi</label>
									<input type="text" class="form-control" id="NoReff" name="NoReff" placeholder="Nomor Reff..." style="height: 38px !important; padding: 0.5rem 0.75rem !important;">
								</div>
							</div>
						</div>

						<!-- Touch NumPad Card -->
						<div class="card card-custom gutter-b bg-white border-0 mb-0 flex-grow-1">
							<div class="card-body p-3 d-flex flex-column justify-content-between">
								<div class="d-flex justify-content-between gap-1 mb-2">
									<button type="button" class="btn btn-numpad-action active" id="btnNumpadQty" onclick="switchActiveInput('QTY')">QTY (F2)</button>
									<button type="button" class="btn btn-numpad-action" id="btnNumpadDiscP" onclick="switchActiveInput('DISC_P')">DISC % (F3)</button>
									<button type="button" class="btn btn-numpad-action" id="btnNumpadDiscR" onclick="switchActiveInput('DISC_R')">DISC Rp (F4)</button>
								</div>
								<div class="numpad-grid flex-grow-1">
									<button type="button" class="btn btn-numpad" onclick="pressNumpad('7')"><span class="numpad-num">7</span><span class="numpad-sub">PRS</span></button>
									<button type="button" class="btn btn-numpad" onclick="pressNumpad('8')"><span class="numpad-num">8</span><span class="numpad-sub">TUV</span></button>
									<button type="button" class="btn btn-numpad" onclick="pressNumpad('9')"><span class="numpad-num">9</span><span class="numpad-sub">WXY</span></button>
									<button type="button" class="btn btn-numpad-clear" onclick="pressNumpad('CLEAR')">C</button>

									<button type="button" class="btn btn-numpad" onclick="pressNumpad('4')"><span class="numpad-num">4</span><span class="numpad-sub">GHI</span></button>
									<button type="button" class="btn btn-numpad" onclick="pressNumpad('5')"><span class="numpad-num">5</span><span class="numpad-sub">JKL</span></button>
									<button type="button" class="btn btn-numpad" onclick="pressNumpad('6')"><span class="numpad-num">6</span><span class="numpad-sub">MNO</span></button>
									<button type="button" class="btn btn-numpad" onclick="pressNumpad('BACKSPACE')"><i class="fas fa-backspace"></i></button>

									<button type="button" class="btn btn-numpad" onclick="pressNumpad('1')"><span class="numpad-num">1</span><span class="numpad-sub">.,#</span></button>
									<button type="button" class="btn btn-numpad" onclick="pressNumpad('2')"><span class="numpad-num">2</span><span class="numpad-sub">ABC</span></button>
									<button type="button" class="btn btn-numpad" onclick="pressNumpad('3')"><span class="numpad-num">3</span><span class="numpad-sub">DEF</span></button>
									<button type="button" class="btn btn-numpad-enter" style="grid-row: span 2; height: 100% !important;" onclick="pressNumpad('ENTER')"><i class="fas fa-check"></i></button>

									<button type="button" class="btn btn-numpad" onclick="pressNumpad('0')" style="grid-column: span 2;"><span class="numpad-num">0</span><span class="numpad-sub">SPACE</span></button>
									<button type="button" class="btn btn-numpad" onclick="pressNumpad('.')"><span class="numpad-num">.</span><span class="numpad-sub">DOT</span></button>
								</div>
							</div>
						</div>
					</div>

					<!-- Column 3: col-xl-3 col-lg-3 col-md-12 d-flex flex-column -->
					<div class="col-xl-3 col-lg-3 col-md-12 d-flex flex-column">
						<div class="receipt-tape flex-grow-1 d-flex flex-column justify-content-between" style="min-height: 600px;">
							<div class="d-flex flex-column flex-grow-1">
								<div class="d-flex justify-content-between align-items-center mb-3">
									<h5 class="font-weight-extrabold mb-0" style="color: var(--primary); letter-spacing: 0.5px;"><i class="fas fa-receipt me-2"></i>STRUK BELANJA</h5>
									<span class="badge bg-success text-white font-weight-bold px-2 py-1">LIVE TAPE</span>
								</div>
								
								<!-- Digital Cart Items Table -->
								<div class="table-responsive flex-grow-1 scrollbar-1 mb-3" style="max-height: 250px; overflow-y: auto; border: 1.5px solid #cbd5e1; border-radius: 16px; background: rgba(0,0,0,0.01);">
									<table id="PosDetail" class="display table table-striped" style="width:100%; margin-bottom: 0;">
										<thead>
											<tr style="background: rgba(11, 87, 208, 0.04);">
												<th width="5%" class="p-2 text-center" style="font-weight: 800; font-size: 0.75rem;">#</th>
												<th width="50%" class="p-2" style="font-weight: 800; font-size: 0.75rem;">Item</th>
												<th width="15%" class="p-2 text-center" style="font-weight: 800; font-size: 0.75rem;">Qty</th>
												<th width="30%" class="p-2 text-end" style="font-weight: 800; font-size: 0.75rem;">Total</th>
											</tr>
										</thead>
										<tbody id="AppendArea">
											<!-- Dynamic lines are outputted here -->
										</tbody>
									</table>
								</div>

								<!-- Calculations Dashboard -->
								<div class="resulttable-pos mt-auto">
									<table class="table right-table mb-2">
										<tbody>
											<tr class="d-flex align-items-center justify-content-between py-1" style="border-bottom: 1.2px dashed rgba(0,0,0,0.08);">
												<th class="border-0 font-size-h6 mb-0 font-weight-bold text-dark">Total Items</th>
												<td class="border-0 justify-content-end d-flex text-dark font-weight-bolder">
													<input type="text" name="_TotalItem" id="_TotalItem" value="0" class="TotalText" style="font-size: 1rem !important; height: 25px !important; width: 80px;" readonly>
												</td>
											</tr>
											<tr class="d-flex align-items-center justify-content-between py-1" style="border-bottom: 1.2px dashed rgba(0,0,0,0.08);">
												<th class="border-0 font-size-h6 mb-0 font-weight-bold text-dark">Subtotal</th>
												<td class="border-0 justify-content-end d-flex text-dark font-weight-bolder">
													<input type="text" name="_SubTotal" id="_SubTotal" value="0" class="TotalText" style="font-size: 1rem !important; height: 25px !important; width: 120px;" readonly>
												</td>
											</tr>
											<tr class="d-flex align-items-center justify-content-between py-1" style="border-bottom: 1.2px dashed rgba(0,0,0,0.08);">
												<th class="border-0 font-size-h6 mb-0 font-weight-bold text-dark">DISKON</th>
												<td class="border-0 justify-content-end d-flex text-dark font-weight-bolder">
													<input type="text" name="_TotalDiskon" id="_TotalDiskon" value="0" class="TotalText" style="font-size: 1rem !important; height: 25px !important; width: 120px;" readonly>
												</td>
											</tr>
											<tr class="d-flex align-items-center justify-content-between py-1" style="border-bottom: 1.2px dashed rgba(0,0,0,0.08);">
												<th class="border-0 font-size-h6 mb-0 font-weight-bold text-dark">Voucher</th>
												<td class="border-0 justify-content-end d-flex align-items-center text-dark font-size-base">
													<div class="input-group" style="width: 140px; height: 28px;">
														<input type="text" name="_VoucherCode" id="_VoucherCode" placeholder="KODE" class="form-control text-uppercase" style="font-size: 0.72rem !important; height: 28px !important; padding: 2px 6px !important; border: 1.5px solid rgba(11, 87, 208, 0.3) !important; border-radius: 8px 0 0 8px !important; font-weight: 700 !important; background: rgba(11, 87, 208, 0.02) !important;">
														<button class="btn btn-primary d-flex align-items-center justify-content-center px-2" type="button" id="btnApplyVoucher" style="height: 28px !important; border-radius: 0 8px 8px 0 !important; background: var(--primary) !important; font-size: 0.7rem; font-weight: 800; border: none; padding: 0 10px !important;">
															Cek
														</button>
													</div>
												</td>
											</tr>
											<tr class="d-flex align-items-center justify-content-between py-1 text-danger" id="rowVoucherDiscount" style="border-bottom: 1.2px dashed rgba(0,0,0,0.08);">
												<th class="border-0 font-size-h6 mb-0 font-weight-bold text-danger">Voucher Discount</th>
												<td class="border-0 justify-content-end d-flex text-danger font-weight-bolder">
													<input type="text" name="_VoucherDiscount" id="_VoucherDiscount" value="0" class="TotalText text-danger font-weight-bold" style="color: #dc2626 !important; font-size: 1rem !important; height: 25px !important; width: 120px;" readonly>
												</td>
											</tr>
											<tr class="d-flex align-items-center justify-content-between py-1" style="border-bottom: 1.2px dashed rgba(0,0,0,0.08);">
												<th class="border-0 font-size-h6 mb-0 font-weight-bold text-dark">Tax</th>
												<td class="border-0 justify-content-end d-flex text-dark font-weight-bolder">
													<input type="text" name="_TotalTax" id="_TotalTax" value="0" class="TotalText" style="font-size: 1rem !important; height: 25px !important; width: 120px;" readonly>
												</td>
											</tr>
											<tr class="d-flex align-items-center justify-content-between py-1" style="border-bottom: 1.2px dashed rgba(0,0,0,0.08);">
												<th class="border-0 font-size-h6 mb-0 font-weight-bold text-dark">
													<div class="d-flex align-items-center">
														Services
														<span class="badge badge-secondary white rounded-circle ms-1 p-1 d-flex align-items-center justify-content-center" id="btshippingcost" style="cursor: pointer; width: 18px; height: 18px;">
															<i class="fas fa-plus" style="font-size: 0.6rem;"></i>
														</span>
													</div>
												</th>
												<td class="border-0 justify-content-end d-flex text-dark font-size-base">
													<input type="text" name="_TotalServices" id="_TotalServices" value="0" class="TotalText" style="font-size: 1rem !important; height: 25px !important; width: 80px;" readonly>
													<a href="#" id="btResetServices" class="ms-1 font-size-xs text-danger font-weight-bold" style="font-size: 0.72rem;">Reset</a>
												</td>
											</tr>
										</tbody>
									</table>

									<!-- Massive Cyan Neon glowing grand total -->
									<div class="premium-total-card my-3">
										<div class="d-flex justify-content-between align-items-center mb-1" style="opacity: 0.8; font-size: 0.75rem; font-weight: 800; letter-spacing: 0.5px;">
											<span>TOTAL TAGIHAN</span>
											<span><i class="fas fa-shield-alt text-success"></i> SECURE</span>
										</div>
										<input type="text" name="_GrandTotal" id="_GrandTotal" value="0" readonly>
										<div class="mt-2 d-flex justify-content-between align-items-center">
											<span class="badge bg-success text-white px-2 py-1 rounded-pill" style="font-size: 0.65rem; font-weight: 800;">READY TO PRINT</span>
											<span class="text-white-50 font-size-xs" style="font-size: 0.65rem;">PT. DSTech Gateway</span>
										</div>
									</div>
								</div>
							</div>

							<!-- Action Buttons -->
							<div class="buttons-cash">
								<div class="d-flex flex-column gap-2">
									<button type="button" class="btn btn-primary white w-100 py-3 font-weight-extrabold d-flex align-items-center justify-content-center" id="btBayar" style="border-radius: 16px !important; font-size: 1.1rem; height: 50px;">
										<i class="fas fa-money-bill-wave me-2 font-size-lg"></i>
										BAYAR SEKARANG (F5)
									</button>
									<div class="row g-2">
										<div class="col-6">
											<button type="button" class="btn btn-secondary white w-100 py-2 font-weight-bold d-flex align-items-center justify-content-center" id="btDraft" style="border-radius: 14px !important; font-size: 0.85rem; height: 40px;">
												<i class="fas fa-save me-1"></i> Draft (F6)
											</button>
										</div>
										<div class="col-6">
											<button type="button" class="btn btn-danger white w-100 py-2 font-weight-bold d-flex align-items-center justify-content-center" id="btBatal" style="border-radius: 14px !important; font-size: 0.85rem; height: 40px;">
												<i class="fas fa-trash-alt me-1"></i> Batal (DEL)
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
   <div class="modal fade text-left" id="payment-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel11" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h3 class="modal-title" id="myModalLabel11">Payment</h3>
			<button type="button" class="close rounded-pill btn btn-sm btn-icon btn-light btn-hover-primary m-0" data-bs-dismiss="modal" aria-label="Close">
			  <svg width="20px" height="20px" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				  <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
			  </svg>
			</button>
		  </div>
		  <div class="modal-body">
			<table class="table right-table">
				<tbody>
				  <tr class="d-flex align-items-center justify-content-between">
					<th class="border-0 px-0 font-size-lg mb-0 font-size-bold text-primary">
						<h1>Total Transaksi</h1>
					</th>
					<td class="border-0 justify-content-end d-flex text-primary font-size-lg font-size-bold px-0 font-size-lg mb-0 font-size-bold text-primary">
						<input type="hidden" name="_TotalTagihan" id="_TotalTagihan">
						<h1 id="_TotalTagihanFormated">Rp. </h1>
					</td>
				  </tr>

				  <tr class="d-flex align-items-center justify-content-between">
					<th class="border-0 px-0 font-size-lg mb-0 font-size-bold text-primary">
						<h1>Pembulatan</h1>
					</th>
					<td class="border-0 justify-content-end d-flex text-primary font-size-lg font-size-bold px-0 font-size-lg mb-0 font-size-bold text-primary">
						<input type="hidden" name="_Pembulatan" id="_Pembulatan" originalvalue="0">
						<h1 id="_PembulatanFormated">Rp. </h1>
					</td>
				  </tr>

				  <tr class="d-flex align-items-center justify-content-between">
					<th class="border-0 px-0 font-size-lg mb-0 font-size-bold text-primary">
						<h1>Total Bayar</h1>
					</th>
					<td class="border-0 justify-content-end d-flex text-primary font-size-lg font-size-bold px-0 font-size-lg mb-0 font-size-bold text-primary">
						<input type="hidden" name="_TotalNetBayar" id="_TotalNetBayar" value="0">
						<h1 id="_TotalNetBayarFormated">Rp. </h1>
					</td>
				  </tr>
				</tbody>
			</table>	  
				<div class="form-group row">
					<div class="col-md-12">
						<div class="col-lg-12">
							<div class="card card-custom gutter-b bg-white border-0">
								<div class="card-header align-items-center  border-0">
									<div class="card-title mb-0">
										<h3 class="card-label text-body font-weight-bold mb-0">Pilih Metode Pembayaran
										</h3>
									</div>
								</div>

								<div class="card-body px-0">
									<div class="scroll-container list-group scrollbar-1">
										<ul class="horizontal-list payment">

											@foreach($metodepembayaran as $ko)
												<li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between py-2" StsPyment={{$ko->Active}} id={{ $ko->id }} CaraVerifikasi={{$ko->MetodeVerifikasi}} TipePembayaran={{$ko->TipePembayaran}}>
													<div class="list-left d-flex align-items-center">
														<span class="d-flex align-items-center justify-content-center rounded svg-icon w-45px h-45px bg-light-dark text-white me-2">
															<img src="{{ $ko->Image }}" class="bi bi-lightning-fill" width="80%">
														</span>
													  <div class="list-content">
														<span class="list-title text-body">{{ $ko->NamaMetodePembayaran}}</span>
													  </div>
													</div>
												</li>
											@endforeach
								        </ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group row">
					<div class="col-md-12">
						<label  class="text-body">Jumlah Bayar</label>
						<fieldset class="form-label-group ">
							<input type="text" name="JumlahBayar" id="JumlahBayar" class="form-control CenterText" size="300" style="height: : 50px; font-size: 50px">
						</fieldset>
					</div>
					<div class="col-md-12">
						<label  class="text-body">Nomor Refrensi</label>
						<fieldset class="form-label-group ">
							<input type="text" name="NomorRefrensiPembayaran" id="NomorRefrensiPembayaran" class="form-control CenterText">
						</fieldset>
					</div>
				</div>
				<div class="form-group row justify-content-end mb-0">
					<div class="col-md-6  text-end">
						<button class="btn btn-primary" id="btSimpanPembayaran">Submit</button>
					</div>
				</div>
		  </div>
		</div>
	</div>	  	  
	</div>
   <div class="modal fade text-left" id="folderpop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel14" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg " role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h3 class="modal-title" id="myModalLabel14">Draft Orders</h3>
			<button type="button" class="close rounded-pill btn btn-sm btn-icon btn-light btn-hover-primary m-0" data-bs-dismiss="modal" aria-label="Close">
			  <svg width="20px" height="20px" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				  <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
			  </svg>
			</button>
		  </div>
		  <div class="modal-body pos-ordermain">
				<div id="_draftOrderList">
			  		
				</div>
		  </div>
		</div>
	</div>	  	  
</div>

<div class="modal fade text-left" id="shippingcost" tabindex="1" role="dialog" aria-labelledby="shippingcost" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h3 class="modal-title" id="myModalLabel1444">Tambah Biaya Tambhan</h3>
			<button type="button" class="close rounded-pill btn btn-sm btn-icon btn-light btn-hover-primary m-0" data-bs-dismiss="modal" aria-label="Close">
			  <svg width="20px" height="20px" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				  <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
			  </svg>
			</button>
		  </div>
		  <div class="modal-body">
			<div class="form-group row">
				<div class="col-md-12">
					<label  class="text-body">Item Jasa</label>
					<fieldset class="form-group mb-12">
						<select class="arabic-select select-down Select2-Selector" id="KodeItemJasa" name="KodeItemJasa" tabindex="-1">
							<option value="">Pilih Jasa</option>
							@foreach($itemServices as $ko)
								<option value="{{ $ko->KodeItem }}">
	                                {{ $ko->NamaItem }}
	                            </option>
							@endforeach
						</select>
					</fieldset>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-12">
					<label  class="text-body">Jumlah</label>
					<fieldset class="form-group mb-3">
						<input type="text" class="form-control" name="JumlahJasa" id="JumlahJasa" value="0">
					</fieldset>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-md-12">
					<label  class="text-body">Keterangan</label>
					<fieldset class="form-group mb-3">
						<input type="text" class="form-control" name="KeteranganJasa" id="KeteranganJasa">
					</fieldset>
				</div>
			</div>
			<div class="form-group row justify-content-end mb-0">
				<div class="col-md-6  text-end">
					<button id="btLookupBiaya" class="btn btn-primary">Update Order</button>
				</div>
			</div>
		  </div>
		</div>
	</div>	  	  
</div>

<div class="modal fade text-left" id="LookupCustomer" tabindex="-1" role="dialog" aria-labelledby="LookupCustomer" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h3 class="modal-title" id="myModalLabel1444">Cari Member / Pelanggan</h3>
			<button type="button" class="close rounded-pill btn btn-sm btn-icon btn-light btn-hover-primary m-0" data-bs-dismiss="modal" aria-label="Close">
			  <svg width="20px" height="20px" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				  <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
			  </svg>
			</button>
		  </div>
		  <div class="modal-body">
			<div class="col-md-12">
				<div class="dx-viewport demo-container">
                	<div id="data-grid-demo">
                  		<div id="gridLookupCustomer"></div>
                	</div>
              	</div>
			</div>
			<hr>
			<div class="form-group row justify-content-end mb-0">
				<div class="col-md-6  text-end">
					<button type="button" class="btn btn-primary" id="btPilihCustomer">Pilih Data</button>
				</div>
			</div>
		  </div>
		</div>
	</div>	  	  
</div>


<div class="modal fade text-left" id="LookupAddCustomer" tabindex="-1" role="dialog" aria-labelledby="LookupAddCustomer" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h3 class="modal-title" id="myModalLabel1444">Tambah Member / Pelanggan</h3>
			<button type="button" class="close rounded-pill btn btn-sm btn-icon btn-light btn-hover-primary m-0" data-bs-dismiss="modal" aria-label="Close">
			  <svg width="20px" height="20px" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				  <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
			  </svg>
			</button>
		  </div>
		  <div class="modal-body">
			<div class="col-md-12">
				<div class="form-group row">
					<div class="col-md-12">
            			<label  class="text-body">Kode Pelanggan</label>
            			<fieldset class="form-group mb-3">
            				<input type="text" class="form-control" id="ModalKodePelanggan" name="ModalKodePelanggan" placeholder="<AUTO>" readonly="" >
            			</fieldset>
            			
            		</div>
            		
            		<div class="col-md-12">
            			<label  class="text-body">Nama Pelanggan</label>
            			<fieldset class="form-group mb-3">
            				<input type="text" class="form-control" id="ModalNamaPelanggan" name="ModalNamaPelanggan" placeholder="Masukan Nama Pelanggan" required="">
            			</fieldset>
            			
            		</div>

            		<div class="col-md-6">
            			<label  class="text-body">Grup Pelanggan</label>
            			<fieldset class="form-group mb-3">
            				<select name="ModalKodeGrupPelanggan" id="ModalKodeGrupPelanggan" class="js-example-basic-single js-states form-control bg-transparent" name="state" required="">
								<option value="">Pilih Kelompok Pelanggan</option>
								@foreach($gruppelanggan as $ko)
									<option value="{{ $ko->KodeGrup }}">
                                        {{ $ko->NamaGrup }}
                                    </option>
								@endforeach
								
							</select>
            			</fieldset>
            			
            		</div>

            		<div class="col-md-6">
            			<label  class="text-body">Limit Piutang</label>
            			<fieldset class="form-group mb-3">
            				<input type="number" class="form-control" id="ModalLimitPiutang" name="ModalLimitPiutang" placeholder="Masukan Limit Piutang" value="0">
            			</fieldset>
            			
            		</div>

            		<div class="col-md-3">
            			<label  class="text-body">Provinsi</label>
            			<fieldset class="form-group mb-3">
            				<select name="ModalProvID" id="ModalProvID" class="js-example-basic-single js-states form-control bg-transparent" name="state" >
								<option value="-1">Pilih Provinsi</option>
								@foreach($provinsi as $ko)
									<option value="{{ $ko->prov_id }}">
                                        {{ $ko->prov_name }}
                                    </option>
								@endforeach
								
							</select>
            			</fieldset>
            		</div>

            		<div class="col-md-3">
            			<label  class="text-body">Kota</label>
            			<fieldset class="form-group mb-3">
            				<select name="ModalKotaID" id="ModalKotaID" class="js-example-basic-single js-states form-control bg-transparent" name="state" >
								<option value="-1">Pilih Kota</option>
							</select>
            			</fieldset>
            		</div>

            		<div class="col-md-3">
            			<label  class="text-body">Kecamatan</label>
            			<fieldset class="form-group mb-3">
            				<select name="ModalKecID" id="ModalKecID" class="js-example-basic-single js-states form-control bg-transparent" name="state" >
								<option value="-1">Pilih Kecamatan</option>
							</select>
            			</fieldset>
            		</div>

            		<div class="col-md-3">
            			<label  class="text-body">Kelurahan</label>
            			<fieldset class="form-group mb-3">
            				<select name="ModalKelID" id="ModalKelID" class="js-example-basic-single js-states form-control bg-transparent" name="state" >
								<option value="-1">Pilih Kelurahan</option>
							</select>
            			</fieldset>
            		</div>

            		<div class="col-md-12">
            			<label  class="text-body">Alamat</label>
            			<fieldset class="form-group mb-12">
            				<textarea class="form-control" id="ModalAlamat" name="ModalAlamat" rows="3" placeholder="Masukan Alamat"></textarea>
            			</fieldset>
            		</div>

            		<div class="col-md-6">
            			<label  class="text-body">Email</label>
            			<fieldset class="form-group mb-3">
            				<input type="mail" class="form-control" id="ModalEmail" name="ModalEmail" placeholder="Masukan Email" >
            			</fieldset>
            		</div>

            		<div class="col-md-3">
            			<label  class="text-body">NoTlp</label>
            			<fieldset class="form-group mb-3">
            				<input type="number" class="form-control" id="ModalNoTlp1" name="ModalNoTlp1" placeholder="621325058258" required="">
            			</fieldset>
            		</div>

            		<div class="col-md-3">
            			<label  class="text-body">Kontak Lain</label>
            			<fieldset class="form-group mb-3">
            				<input type="number" class="form-control" id="ModalNoTlp2" name="ModalNoTlp2" placeholder="621325058258" >
            			</fieldset>
            		</div>

            		<div class="col-md-12">
            			<label  class="text-body">Keterangan Lain</label>
            			<fieldset class="form-group mb-12">
            				<textarea class="form-control" id="ModalKeterangan" name="ModalKeterangan" rows="3" placeholder="Masukan Keterangan"></textarea>
            			</fieldset>
            		</div>

            		<div class="col-md-12">
            			<label  class="text-body">Status</label>
            			<fieldset class="form-group mb-3">
            				<select name="ModalStatus" id="ModalStatus" class="js-example-basic-single js-states form-control bg-transparent" name="state" >
								<option value="1">Active</option>
								<option value="0">Inactive</option>
							</select>
            			</fieldset>
            			
            		</div>
				</div>
			</div>
			<hr>
			<div class="form-group row justify-content-end mb-0">
				<div class="col-md-6  text-end">
					<button type="button" class="btn btn-primary" id="btSaveAddCustomer">Simpan Data</button>
				</div>
			</div>
		  </div>
		</div>
	</div>	  	  
</div>

<div class="modal fade text-left" id="LookupTipeOrder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel11" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h3 class="modal-title" id="myModalLabel11">Tipe Order</h3>
			<button type="button" class="close rounded-pill btn btn-sm btn-icon btn-light btn-hover-primary m-0" data-bs-dismiss="modal" aria-label="Close">
			  <svg width="20px" height="20px" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				  <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
			  </svg>
			</button>
		  </div>
		  <div class="modal-body">
				<div class="form-group row">
					<div class="col-md-12">
						<div class="col-lg-12">
							<div class="card card-custom gutter-b bg-white border-0">
								<div class="card-header align-items-center  border-0">
									<div class="card-title mb-0">
										<h3 class="card-label text-body font-weight-bold mb-0">Pilih Metode Pembayaran
										</h3>
									</div>
								</div>

								<div class="card-body px-0">
									<div class="scroll-container list-group scrollbar-1">
										<ul class="horizontal-list tipeorder">

											@foreach($tipeorder as $ko)
												<li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between py-2" id={{ $ko->id }} NamaJenisOrder="{{ $ko->NamaJenisOrder }}" DineIn= "{{ $ko->DineIn }}">
													<div class="list-left d-flex align-items-center">
														<span class="d-flex align-items-center justify-content-center rounded svg-icon w-45px h-45px bg-light-dark text-white me-2">
															<img src="{{ $ko->Icon }}" class="bi bi-lightning-fill" width="80%">
														</span>
													  <div class="list-content">
														<span class="list-title text-body">{{ $ko->NamaJenisOrder}}</span>
													  </div>
													</div>
												</li>
											@endforeach
								        </ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group row justify-content-end mb-0">
					<div class="col-md-6  text-end">
						<button class="btn btn-primary" id="btPilihTipeOrder">Submit</button>
					</div>
				</div>
		  	</div>
		</div>
	</div>	  	  
</div>

<div class="modal fade text-left" id="LookupNomorMeja" tabindex="-1" role="dialog" aria-labelledby="myModalLabel11" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h3 class="modal-title" id="myModalLabel11">Nomor Meja</h3>
			<button type="button" class="close rounded-pill btn btn-sm btn-icon btn-light btn-hover-primary m-0" data-bs-dismiss="modal" aria-label="Close">
			  <svg width="20px" height="20px" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				  <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
			  </svg>
			</button>
		  </div>
		  <div class="modal-body">
				<div class="form-group row">
					<div class="col-md-12">
						<div class="col-lg-12">
							<div class="card card-custom gutter-b bg-white border-0">
								<div class="card-header align-items-center  border-0">
									<div class="card-title mb-0">
										<h3 class="card-label text-body font-weight-bold mb-0">Pilih Nomor Meja										</h3>
									</div>
								</div>

								<div class="card-body px-0">
									<div class="scroll-container list-group scrollbar-1">
										<ul class="horizontal-list-meja nomormeja">

											@foreach($meja as $ko)
												<li class="list-group-item list-group-item-action border-0 d-flex align-items-center justify-content-between py-2" id={{ $ko->KodeMeja }} NamaMeja="{{ $ko->NamaMeja }}" >
													<div class="list-left d-flex align-items-center">
														<span class="d-flex align-items-center justify-content-center rounded svg-icon w-45px h-45px bg-light-dark text-white me-2">
															<img src="{{ url('images/default/dining-table.png') }}" class="bi bi-lightning-fill" width="80%">
														</span>
														<div class="list-content">
															<span class="list-title text-body">{{ $ko->NamaMeja}}</span>
														</div>
													</div>
												</li>
											@endforeach
								        </ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group row justify-content-end mb-0">
					<div class="col-md-6  text-end">
						<button class="btn btn-primary" id="btPilihNomorMeja">Submit</button>
					</div>
				</div>
		  	</div>
		</div>
	</div>	  	  
</div>


<div class="modal fade text-left" id="LookupMenuVariant" tabindex="-1" role="dialog" aria-labelledby="myModalLabel11" style="display: none;" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable  modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h3 class="modal-title" id="myModalLabel11">Variant Menu</h3>
			<button type="button" class="close rounded-pill btn btn-sm btn-icon btn-light btn-hover-primary m-0" data-bs-dismiss="modal" aria-label="Close">
			  <svg width="20px" height="20px" viewBox="0 0 16 16" class="bi bi-x" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
				  <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"></path>
			  </svg>
			</button>
		  </div>
		  <div class="modal-body">
				<div class="form-group row">
					<div class="col-md-12">
						<div id="lsvVariantMenu"></div>
						{{-- <div id="lsvMenuAddon"></div> --}}
						<div class="row">
							<div class="col-md-12 col-12">
								<div class="card card-custom gutter-b bg-white border-0">
									<div class="card-header align-items-center  border-0">
										<div class="card-title mb-0">
											<h3 class="card-label mb-0 font-weight-bold text-body ">Addon Menu</h3>
										</div>
									</div>
									<div class="card-body">
										<div class="col-md-12">
											<div id="gridLookupAddon"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

		  	</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary ms-1" id="btPilihVariant" data-bs-dismiss="modal">
					<span class="">Pilih Variant</span>
				</button>
			</div> 	
		</div>
	</div>	  	  
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/plugin.bundle.min.js')}}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js')}}"></script>
<!-- <script src="http://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script> -->
<script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>
<!-- <script src="{{ asset('js/sweetalert.js')}}"></script> -->
<!-- <script src="{{ asset('js/sweetalert1.js')}}"></script> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('api/jqueryvalidate/jquery.validate.min.js')}}"></script>
<script src="{{asset('api/mcustomscrollbar/jquery.mCustomScrollbar.concat.min.js')}}"></script>
<script src="{{ asset('js/script.bundle.js')}}"></script>
<link href="{{ asset('devexpress/dx.light.css')}}" rel="stylesheet" type="text/css" />
<script src="{{asset('devexpress/dx.all.js')}}"></script>
<script src="{{asset('api/select2/select2.min.js')}}"></script>
@if (env('MIDTRANS_IS_PRODUCTION') == 'false')
<script src="{{ env('MIDTRANS_DEV_URL') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
@else
<script src="{{ env('MIDTRANS_PROD_URL') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endif
<script src="{{asset('api/datatable/jquery.dataTables.min.js')}}"></script>
</body>
<!--end::Body-->
</html>
@extends('parts.generaljs')
<script type="text/javascript">
	var _LastInputed = '';
	var _VoucherDiscountPercent = 0;
	var _VoucherMaximalDiscount = 0;
	var _VoucherAppliedCode = "";
	var _TipeDiskon = '';
	var _ServicesData = [];
	var _DiskonGrupCustomer = 0;
	var _TerminPelanggan = '';

	var _Tanggal = '';
	var _Jam = '';
	var _Company = [];
	var _Printer = [];
	var _Pelanggan = [];
	var _KodeMetodePembayaran = -1;
	var _MetodeVerifikasiPembayaran = '';
	var _TipePembayaran = '';

	var _JenisOrder = '';
	var _idJenisOrder = -1;
	var _DineIn = 'N';

	var _KodeMeja = '';
	var _NamaMeja = '';

	var _oItemMenu = [];
	var _gridKodeItem = [];

	var _oTxtKodeItem = [];

	var _oVariantMenu = [];

	var _oSelectedVariant = [];
	var _oDaftarAddon  = [];

	document.addEventListener('DOMContentLoaded', () => {
	    const listItems = document.querySelectorAll('.horizontal-list.payment li');
		const listTipeOrder = document.querySelectorAll('.horizontal-list.tipeorder li');
		const listNomorMeja = document.querySelectorAll('.horizontal-list-meja.nomormeja li');

	    // console.log(listVariant);

		if (listItems.length > 0) {
			listItems.forEach(item => {
				item.addEventListener('click', () => {
					// Remove active class from all items
					listItems.forEach(i => i.classList.remove('active'));

					// Add active class to the clicked item
					var Sts = $('#'+item.id).attr('stspyment');
					_MetodeVerifikasiPembayaran = $('#'+item.id).attr('CaraVerifikasi');
					_TipePembayaran = $('#'+item.id).attr('TipePembayaran');
					console.log(_TipePembayaran);
					if (Sts =='Y') {
						item.classList.add('active');
						_KodeMetodePembayaran = item.id;
						if (_TipePembayaran == "NON") {
							$('#JumlahBayar').val(jQuery('#_TotalNetBayar').attr("originalvalue"));	
						}
						else{
							$('#JumlahBayar').val(0);
						}
						$('#JumlahBayar').focus();
					}
					SetEnableCommand();
				});
			});	
		}

		if (listTipeOrder.length > 0) {
			listTipeOrder.forEach(item => {
				item.addEventListener('click', () => {
					// Remove active class from all items
					listTipeOrder.forEach(i => i.classList.remove('active'));
					item.classList.add('active');
					_idJenisOrder = item.id;
					_JenisOrder = $('#'+item.id).attr('NamaJenisOrder');
					_DineIn = $('#'+item.id).attr('DineIn');
					SetEnableCommand();
				});
			});	
		}

		if (listNomorMeja.length > 0) {
			listNomorMeja.forEach(item => {
				item.addEventListener('click', () => {
					// Remove active class from all items
					listNomorMeja.forEach(i => i.classList.remove('active'));
					item.classList.add('active');
					_KodeMeja = item.id;
					_NamaMeja = $('#'+item.id).attr('NamaMeja');
					SetEnableCommand();
				});
			});	
		}
	});
	jQuery(function () {
		jQuery(document).ready(function() {
            jQuery('#PosDetail').DataTable();
			$('#_Barcode').focus();
			jQuery('.arabic-select').multipleSelect({filter: true,filterAcceptOnEnter: true});
			$("#btNomorMeja").css("pointer-events", "none");

			_oItemMenu = <?php echo $itemmenu ?>;
			var xdata = <?php echo $itemServices ?>;

			jQuery('.Select2-Selector').select2({
				dropdownParent: $('#shippingcost')
			});

			jQuery('.js-example-basic-single').select2({
				dropdownParent: $('#LookupAddCustomer')
			});

			jQuery('#KodePelanggan').select2();
			jQuery('#KodeSales').select2();

			var now = new Date();
	    	var day = ("0" + now.getDate()).slice(-2);
	    	var month = ("0" + (now.getMonth() + 1)).slice(-2);
	    	var hours = now.getHours().toString().padStart(2, '0');
			var minutes = now.getMinutes().toString().padStart(2, '0');
			var seconds = now.getSeconds().toString().padStart(2, '0');

	    	var firstDay = now.getFullYear()+"-"+month+"-01";
	    	var NowDay = now.getFullYear()+"-"+month+"-"+day;

	    	_Tanggal = NowDay;
	    	_Jam = hours+":"+minutes+":"+seconds;

	    	_Company = <?php echo $company ?>;
	    	_Pelanggan = <?php echo $pelanggan ?>;
			_Printer = <?php echo $printer ?>;
			_oVariantMenu= <?php echo $variantmenu ?>;
			_oDaftarAddon = <?php echo $menuaddon; ?>;

			// console.log(_oDaftarAddon);

	    	LoadDraftOrderList();
	    	bindGridLookupCustomer(_Pelanggan);

	    	jQuery('#_NoTransaksi').text("<OTOMATIS>");
			SetEnableCommand();

			// Voucher Verification Handler
			$('#btnApplyVoucher').click(function() {
				var code = $('#_VoucherCode').val().trim().toUpperCase();
				if (!code) {
					Swal.fire('Peringatan', 'Silakan masukkan kode voucher terlebih dahulu.', 'warning');
					return;
				}
				
				var encodedId = btoa(code);
				var checkUrl = "{{ url('/booking') }}/" + encodeURIComponent(encodedId) + "/get-DiscountVoucher";
				
				$.ajax({
					url: checkUrl,
					type: 'GET',
					dataType: 'json',
					success: function(response) {
						if (response.success && response.data) {
							var v = response.data;
							_VoucherDiscountPercent = parseFloat(v.JumlahPotongan) || 0;
							_VoucherMaximalDiscount = parseFloat(v.MaksimalPotongan) || 0;
							_VoucherAppliedCode = code;
							
							Swal.fire({
								icon: 'success',
								title: 'Voucher Berhasil!',
								text: 'Potongan ' + _VoucherDiscountPercent + '% berhasil diterapkan.',
								timer: 2000,
								showConfirmButton: false
							});
							CalculateTotal();
						} else {
							Swal.fire('Gagal', response.message || 'Kode voucher tidak valid atau sudah tidak aktif.', 'error');
							_VoucherDiscountPercent = 0;
							_VoucherMaximalDiscount = 0;
							_VoucherAppliedCode = "";
							CalculateTotal();
						}
					},
					error: function() {
						Swal.fire('Error', 'Gagal memverifikasi voucher. Silakan coba lagi.', 'error');
						_VoucherDiscountPercent = 0;
						_VoucherMaximalDiscount = 0;
						_VoucherAppliedCode = "";
						CalculateTotal();
					}
				});
			});

			// Checkout state listener for Customer Display QRIS activation
			$('#payment-popup').on('shown.bs.modal', function () {
				var current = JSON.parse(localStorage.getItem('PoSData') || '{}');
				current.isCheckout = true;
				localStorage.setItem('PoSData', JSON.stringify(current));
			});

			$('#payment-popup').on('hidden.bs.modal', function () {
				var current = JSON.parse(localStorage.getItem('PoSData') || '{}');
				current.isCheckout = false;
				localStorage.setItem('PoSData', JSON.stringify(current));
			});

			// Touch NumPad and Active Input Target Logic
			window._ActiveInputType = 'QTY'; // default numpad mode

			window.switchActiveInput = function(type) {
				window._ActiveInputType = type;
				
				// Update active visual state class on numpad mode buttons
				$('#btnNumpadQty, #btnNumpadDiscP, #btnNumpadDiscR').removeClass('active');
				
				var badgeHtml = "";
				if (type === 'QTY') {
					$('#btnNumpadQty').addClass('active');
					badgeHtml = '<span class="badge bg-warning text-white font-weight-extrabold px-2 py-1">KUANTITAS (QTY)</span>';
				} else if (type === 'DISC_P') {
					$('#btnNumpadDiscP').addClass('active');
					badgeHtml = '<span class="badge bg-info text-white font-weight-extrabold px-2 py-1">DISKON (%)</span>';
				} else if (type === 'DISC_R') {
					$('#btnNumpadDiscR').addClass('active');
					badgeHtml = '<span class="badge bg-primary text-white font-weight-extrabold px-2 py-1">DISKON (Rp)</span>';
				} else if (type === 'BARCODE') {
					badgeHtml = '<span class="badge bg-danger text-white font-weight-extrabold px-2 py-1">BARCODE SCANNER</span>';
				}
				
				$('#_activeInputIndicator').html('STATUS INPUT: ' + badgeHtml);
				
				// Focus barcode scanner input field if active
				if (type === 'BARCODE') {
					$('#_Barcode').focus();
				}
			};

			window.pressNumpad = function(key) {
				if (window._ActiveInputType === 'BARCODE') {
					var barcodeInput = $('#_Barcode');
					var currentVal = barcodeInput.val() || '';
					
					if (key === 'CLEAR') {
						barcodeInput.val('');
					} else if (key === 'BACKSPACE') {
						barcodeInput.val(currentVal.substring(0, currentVal.length - 1));
					} else if (key === 'ENTER') {
						var e = $.Event('keypress');
						e.keyCode = 13;
						barcodeInput.trigger(e);
					} else {
						barcodeInput.val(currentVal + key);
					}
					barcodeInput.focus();
					return;
				}

				// Find selected row or fallback to last row in list
				var targetRow = $('#AppendArea tr.selected-row');
				if (targetRow.length === 0) {
					targetRow = $('#AppendArea tr#InputSectionData').last();
				}

				if (targetRow.length === 0) {
					Swal.fire({
						icon: 'warning',
						title: 'Peringatan',
						text: 'Silakan pilih atau tambahkan item terlebih dahulu.',
						timer: 1500,
						showConfirmButton: false
					});
					return;
				}

				// Ensure selected-row class is present on active row
				$('#AppendArea tr#InputSectionData').removeClass('selected-row');
				targetRow.addClass('selected-row');

				var qtyInput = targetRow.find('input[id="txtQty"]');
				var diskonInput = targetRow.find('input[id="txtDiskon"]');
				
				if (window._ActiveInputType === 'QTY') {
					var currentVal = qtyInput.val() || '0';
					if (key === 'CLEAR') {
						qtyInput.val('0');
					} else if (key === 'BACKSPACE') {
						var newVal = currentVal.substring(0, currentVal.length - 1);
						qtyInput.val(newVal === '' ? '0' : newVal);
					} else if (key === 'ENTER') {
						window.switchActiveInput('BARCODE');
					} else {
						if (currentVal === '0' && key !== '.') {
							qtyInput.val(key);
						} else {
							qtyInput.val(currentVal + key);
						}
					}
					// Trigger standard input events to update totals and sync customer display
					if (qtyInput[0]) {
						qtyInput[0].dispatchEvent(new Event('input', { bubbles: true }));
					}
					qtyInput.trigger('input');
				} else if (window._ActiveInputType === 'DISC_P') {
					var currentVal = diskonInput.val() || '0';
					if (key === 'CLEAR') {
						diskonInput.val('0');
					} else if (key === 'BACKSPACE') {
						var newVal = currentVal.substring(0, currentVal.length - 1);
						diskonInput.val(newVal === '' ? '0' : newVal);
					} else if (key === 'ENTER') {
						window.switchActiveInput('BARCODE');
					} else {
						if (currentVal === '0' && key !== '.') {
							diskonInput.val(key);
						} else {
							diskonInput.val(currentVal + key);
						}
					}
					if (diskonInput[0]) {
						diskonInput[0].dispatchEvent(new Event('input', { bubbles: true }));
					}
					diskonInput.trigger('input');
				} else if (window._ActiveInputType === 'DISC_R') {
					// Convert Rupiah discount to percentage based on active row's base total price
					var price = parseFloat(targetRow.find('input[id="txtHarga"]').val()) || 0;
					var qty = parseFloat(qtyInput.val()) || 1;
					var baseTotal = qty * price;

					if (baseTotal === 0) return;

					var currentRpVal = diskonInput.attr('data-discount-rp') || '0';
					
					if (key === 'CLEAR') {
						currentRpVal = '0';
					} else if (key === 'BACKSPACE') {
						currentRpVal = currentRpVal.substring(0, currentRpVal.length - 1);
						if (currentRpVal === '') currentRpVal = '0';
					} else if (key === 'ENTER') {
						window.switchActiveInput('BARCODE');
						return;
					} else {
						if (currentRpVal === '0' && key !== '.') {
							currentRpVal = key;
						} else {
							currentRpVal = currentRpVal + key;
						}
					}

					diskonInput.attr('data-discount-rp', currentRpVal);

					var discountRpVal = parseFloat(currentRpVal) || 0;
					var calculatedPercent = (discountRpVal / baseTotal) * 100;
					
					calculatedPercent = Math.round(calculatedPercent * 100) / 100;
					if (calculatedPercent > 100) calculatedPercent = 100;

					diskonInput.val(calculatedPercent);
					if (diskonInput[0]) {
						diskonInput[0].dispatchEvent(new Event('input', { bubbles: true }));
					}
					diskonInput.trigger('input');
				}
			};

			// Click handler on F&B receipt table rows to set focus/highlight
			$(document).on('click', '#AppendArea tr#InputSectionData', function() {
				$('#AppendArea tr#InputSectionData').removeClass('selected-row');
				$(this).addClass('selected-row');
			});

			// Shortcut event listeners (F2, F3, F4, F5, F6, F7, Delete)
			$(document).keydown(function(e) {
				// Prevent triggering shortcuts when modals are open
				if ($('.modal.show').length > 0) {
					return;
				}
				
				var activeEl = document.activeElement;
				if (activeEl && activeEl.tagName === 'INPUT' && activeEl.id !== '_Barcode' && activeEl.id !== '_VoucherCode') {
					return;
				}

				if (e.key === 'F2') {
					e.preventDefault();
					window.switchActiveInput('QTY');
					$('#tileF2').addClass('active');
					setTimeout(function() { $('#tileF2').removeClass('active'); }, 150);
				} else if (e.key === 'F3') {
					e.preventDefault();
					window.switchActiveInput('DISC_P');
					$('#tileF3').addClass('active');
					setTimeout(function() { $('#tileF3').removeClass('active'); }, 150);
				} else if (e.key === 'F4') {
					e.preventDefault();
					window.switchActiveInput('DISC_R');
					$('#tileF4').addClass('active');
					setTimeout(function() { $('#tileF4').removeClass('active'); }, 150);
				} else if (e.key === 'F5') {
					e.preventDefault();
					$('#tileF5').addClass('active');
					setTimeout(function() { $('#tileF5').removeClass('active'); }, 150);
					$('#btBayar').click();
				} else if (e.key === 'F6') {
					e.preventDefault();
					$('#tileF6').addClass('active');
					setTimeout(function() { $('#tileF6').removeClass('active'); }, 150);
					$('#btDraft').click();
				} else if (e.key === 'F7') {
					e.preventDefault();
					$('#tileF7').addClass('active');
					setTimeout(function() { $('#tileF7').removeClass('active'); }, 150);
					$('#btshippingcost').click();
				} else if (e.key === 'Delete') {
					if (activeEl && activeEl.tagName === 'INPUT') {
						return;
					}
					e.preventDefault();
					$('#tileDEL').addClass('active');
					setTimeout(function() { $('#tileDEL').removeClass('active'); }, 150);
					$('#btBatal').click();
				}
			});

			// Setup click handlers for alfamart-style action shortcut tiles
			$('#tileF2').click(function() { window.switchActiveInput('QTY'); });
			$('#tileF3').click(function() { window.switchActiveInput('DISC_P'); });
			$('#tileF4').click(function() { window.switchActiveInput('DISC_R'); });
			$('#tileF5').click(function() { $('#btBayar').click(); });
			$('#tileF6').click(function() { $('#btDraft').click(); });
			$('#tileF7').click(function() { $('#btshippingcost').click(); });
			$('#tileDEL').click(function() { $('#btBatal').click(); });
		});

		function UpdateCurrentTime() {
			var now = new Date();
			var day = ("0" + now.getDate()).slice(-2);
			var month = ("0" + (now.getMonth() + 1)).slice(-2);
			var hours = now.getHours().toString().padStart(2, '0');
			var minutes = now.getMinutes().toString().padStart(2, '0');
			var seconds = now.getSeconds().toString().padStart(2, '0');

			var NowDay = now.getFullYear() + "-" + month + "-" + day;
			_Tanggal = NowDay;
			_Jam = hours + ":" + minutes + ":" + seconds;
		}

		jQuery('#cboJenisItem').change(function () {
			// console.log(jQuery('#cboJenisItem').val());
			// lsvProductList
			$.ajax({
	            async:false,
	            type: 'post',
	            url: "{{route('menu-ViewJson')}}",
	            headers: {
	                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
	            },
	            data: {
	                'KodeJenis' : jQuery('#cboJenisItem').val(),
	            },
	            dataType: 'json',
	            success: function(response) {
	            	// console.log(response);
					var xHTML = "";
					$.each(response.data,function (k,v) {
						xHTML += '<div class="col-xl-4 col-lg-2 col-md-3 col-sm-4 col-6">';
						xHTML += '	<div class="productCard">';
						xHTML += '		<div class="productThumb">';
						xHTML += '			<img class="img-fluid" src="'+v['Gambar']+'" alt="ix">';
						xHTML += '		</div>';
						xHTML += '		<div class="productContent">';
						xHTML += '			<a href="#">'+v['NamaItem']+'</a>';
						xHTML += '		</div>';
						xHTML += '	</div>';
						xHTML += '</div>';
					});

					jQuery('#lsvProductList').html(xHTML);
	            }
	        });
		});

		jQuery('#btTipeOrder').click(function () {
			jQuery('#LookupTipeOrder').modal({backdrop: 'static', keyboard: false})
			jQuery('#LookupTipeOrder').modal('show');
		});

		jQuery('#btNomorMeja').click(function () {
			jQuery('#LookupNomorMeja').modal({backdrop: 'static', keyboard: false})
			jQuery('#LookupNomorMeja').modal('show');
		});

		jQuery('#btPilihTipeOrder').click(function () {
			jQuery('#LookupTipeOrder').modal('hide');

			var xHTML = "";

			if (_idJenisOrder > -1) {
				xHTML += '<center>';
				xHTML += '	<p class="white">Tipe Order</p>';
				xHTML += '	<h4 class="mb-0 white">'+ _JenisOrder +'</h4>';
				xHTML += '</center>';
			}

			if (_DineIn == "Y") {
				$("#btNomorMeja").css("pointer-events", "auto");
			}
			else{
				$("#btNomorMeja").css("pointer-events", "none");
			}
			jQuery('#txtTipeOrder').html(xHTML);
			SetEnableCommand();
		});

		jQuery('#btPilihNomorMeja').click(function () {
			jQuery('#LookupNomorMeja').modal('hide');

			var xHTML = "";

			if (_idJenisOrder > -1) {
				xHTML += '<center>';
				xHTML += '	<p class="white">Nomor Meja</p>';
				xHTML += '	<h4 class="mb-0 white">'+ _NamaMeja +'</h4>';
				xHTML += '</center>';
			}
			jQuery('#txtNomorMeja').html(xHTML);
			SetEnableCommand();
		});

		jQuery('#btPilihVariant').click(function () {
			var KodeItem = jQuery('#VariantFatherItemCode').val();
			jQuery('#LookupMenuVariant').modal('hide');
			AddNewRow(KodeItem);
			AsignRowNumber();
			AddAddonVariantMenu(KodeItem)
			// Tambah Addon
			var dataGridInstance = jQuery('#gridLookupAddon').dxDataGrid('instance');
			var dataGridDetailInstance = jQuery('#gridLookupAddon').dxDataGrid('instance');
			var selectedRows = dataGridInstance.getSelectedRowsData();

			if (selectedRows.length > 0) {
				for (let index = 0; index < selectedRows.length; index++) {
					// console.log("Add Row : " + index)
					// console.log(CheckifExist(selectedRows[index]["KodeItem"]));
					// addNewLineAddon(selectedRows[index], index +1); 
					AddAddonMenu(KodeItem, selectedRows[index])
				}
			}
			dataGridInstance.deselectAll();
			CalculateTotal();
			// console.log(_oSelectedVariant);
		})

		$('.ProductSelected').on('click', function() {
			var kodeItem = $(this).data('kodeitem'); // Retrieve the custom attribute
			// Tambah Variant
			let filteredVariantDetail = _oVariantMenu.filter(function(variant) {
				return variant.Father == kodeItem;
			});

			if (filteredVariantDetail.length > 0) {
				console.log('Masuk Tambah Variant + addon')
				console.log(filteredVariantDetail)
				var _Header = [];

				for (let index = 0; index < filteredVariantDetail.length; index++) {
					var oData = {
						'id' : filteredVariantDetail[index]['VariantGrupID'],
						'NamaGrup' : filteredVariantDetail[index]['NamaGrup']
					}

					if (_Header.length > 0) {
						let headerExist = _Header.filter(function(temp) {
							return temp.id === filteredVariantDetail[index]['VariantGrupID'];
						});
						// console.log(_Header);
						if (headerExist.length == 0) {
							_Header.push(oData);	
						}
					}
					else{
						_Header.push(oData);
					}
					
					
				}
				
				var _xHTML = "<input type='hidden' value ='"+kodeItem+"' id='VariantFatherItemCode'>";
				for (let i = 0; i < _Header.length; i++) {
					// const element = array[i];
					_xHTML += '<div class="row">';
						_xHTML += '<div class="col-md-12 col-12">';
							_xHTML += '<div class="card card-custom gutter-b bg-white border-0">';
								_xHTML += '<div class="card-header align-items-center  border-0">';
									_xHTML += '<div class="card-title mb-0">';
										_xHTML += '<h3 class="card-label mb-0 font-weight-bold text-body ">'+_Header[i]['NamaGrup']+'</h3>'
									_xHTML += '</div>';
								_xHTML += '</div>';

								_xHTML += '<div class="card-body">';
									_xHTML += '<div class="col-md-12">';
										// _xHTML += "MASUK DATA DISINI";
										let ItemDetail = filteredVariantDetail.filter(function(variant) {
											return variant.Father === kodeItem && variant.VariantGrupID === _Header[i]['id'];
										});
										_xHTML += '<div class="scroll-container list-group scrollbar-1">';
											_xHTML += '<ul class="horizontal-list-variant VariantData">';
												// _xHTML += '';
												for (let iDetail = 0; iDetail < ItemDetail.length; iDetail++) {
													_xHTML += '<li class="list-group-item list-group-item-action border-0 d-flex align-items-center  py-2" id='+'variant'+ItemDetail[iDetail]['VariantID']+' namavariant='+ItemDetail[iDetail]['NamaVariant']+' extraprice ='+ItemDetail[iDetail]['ExtraPrice']+' VariantGrupID = '+ItemDetail[iDetail]['VariantGrupID']+'>';
														_xHTML += '<span class="d-flex align-items-center justify-content-center rounded svg-icon w-45px h-45px bg-light-dark text-white me-2">';
															_xHTML += '<img src="https://cdn-icons-png.freepik.com/256/6178/6178920.png?semt=ais_hybrid" class="bi bi-lightning-fill" width="80%">';
														_xHTML += '</span>';
														_xHTML += '<div class="list-content">';
															_xHTML += '<span class="list-title text-body">'+ItemDetail[iDetail]['NamaVariant']+'</span><br>';
															_xHTML += '<span class="list-title text-body"> <font color ="red"> + Rp. '+ parseFloat(ItemDetail[iDetail]['ExtraPrice']).toLocaleString('en-US')+'</font></span>';
														_xHTML += '</div>';
													_xHTML += '</li>';
												}
											_xHTML += '</ul>';
										_xHTML += '</div>';
										// console.log(ItemDetail);
									_xHTML += '</div>';
								_xHTML += '</div>';

							_xHTML += '</div>';
						_xHTML += '</div>';
					_xHTML += '</div>';
				}
				
				jQuery('#lsvVariantMenu').empty();
				jQuery("#lsvVariantMenu").append(_xHTML);

				var ColumnData = [
					{
						dataField: "NamaAddon",
						caption: "Nama Addon",
						allowSorting: true,
						allowEditing : false
					},
					{
						dataField: "HargaAddon",
						caption: "Extra Cost",
						allowSorting: true,
						allowEditing : false
					},
				];
				BindLookupServices("gridLookupAddon", "id", _oDaftarAddon, ColumnData,"multiple");

				jQuery('#LookupMenuVariant').modal({backdrop: 'static', keyboard: false})
        		jQuery('#LookupMenuVariant').modal('show');

				const listVariant = document.querySelectorAll('.horizontal-list-variant.VariantData li');

				if (listVariant.length > 0) {
					listVariant.forEach(item => {
						item.addEventListener('click', () => {
							// Remove active class from all items
							listVariant.forEach(i => i.classList.remove('active'));
							item.classList.add('active');


							// _oSelectedVariant

							// _KodeMeja = item.id;
							// _NamaMeja = $('#'+item.id).attr('namavariant');
							console.log(jQuery('#'+item.id))
							var oTempSelectedVariant = {
								'id' : item.id,
								'NamaVariant' : jQuery('#'+item.id).attr('namavariant'),
								'ExtraPrice' : jQuery('#'+item.id).attr('extraprice'),
								'VariantGrupID' : jQuery('#'+item.id).attr('VariantGrupID')
							}
							// _Header.push(oData);
							_oSelectedVariant = oTempSelectedVariant;
							SetEnableCommand();
						});
					});	
				}
			}
			else{
				AddNewRow(kodeItem)
			}
			AsignRowNumber();
			FirstRowHandling();
			CalculateTotal();
		});

		$('#btPilihLookupData').click(function () {
			var dataGridInstance = jQuery('#gridLookupItem').dxDataGrid('instance');
			var selectedRows = dataGridInstance.getSelectedRowsData();

			if (selectedRows.length > 0) {
				jQuery('#LookupItem').modal('hide');
				$('#_Barcode').val(selectedRows[0]['KodeItem']);
				$('#_Barcode').focus();

				var e = $.Event('keypress');
				e.keyCode = 13;
				$('#_Barcode').trigger(e);
			}

		});

		$('#btBatal').click(function () {
			location.reload()
		});

		$('#btshippingcost').click(function () {
			// bindGridLookupServices(_ServicesData);
			$('#KodeItemJasa').val('').trigger('change');
			$('#JumlahJasa').val('0');
			$('#KeteranganJasa').val('');
			jQuery('#shippingcost').modal({backdrop: 'static', keyboard: false})
		    jQuery('#shippingcost').modal('show');
		})

		$('#btLookupBiaya').click(function () {
			jQuery('#shippingcost').modal('hide');

			var item ={
				'KodeItem'  : $('#KodeItemJasa').val(),
				'Jumlah'	: $('#JumlahJasa').attr("originalvalue"),
				'Keterangan': $('#KeteranganJasa').val()
			}

			_ServicesData.push(item);

			CalculateTotal();
		});

		$('#JumlahJasa').focusout(function(){
			formatCurrency($("#JumlahJasa"), $("#JumlahJasa").val());
		});

		$('#JumlahBayar').focusout(function(){
			formatCurrency($("#JumlahBayar"), $("#JumlahBayar").val());
			SetEnableCommand();
		});

		// jQuery('#KodePelanggan').change(function () {
		// 	$.ajax({
	    //         async:false,
	    //         type: 'post',
	    //         url: "{{route('pelanggan-viewJson')}}",
	    //         headers: {
	    //             'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
	    //         },
	    //         data: {
	    //             'KodePelanggan' : $('#KodePelanggan').val(),
	    //             'GrupPelanggan' : ''
	    //         },
	    //         dataType: 'json',
	    //         success: function(response) {
	    //         	var dataGridInstance = jQuery('#gridContainerDetail').dxDataGrid('instance');
      	// 			var allRowsData  = dataGridInstance.getDataSource().items();

	    //         	if (response.data.length > 0) {
	    //         		_DiskonGrupCustomer = response.data[0]['DiskonPersen'];
	    //         		_TerminPelanggan = response.data[0]['DiskonPersen'];
	    //         		// console.log(response.data[0]);

	    //         		if (allRowsData.length > 0) {
	    //         			for (var i = 0; i < allRowsData.length; i++) {
	    //         				allRowsData[i]["DiskonPersen"] = _DiskonGrupCustomer;
	    //         			}

	    //         			bindGrid(allRowsData);
	    //         			CalculateTotal();
	    //         		}
	    //         	}
	    //         }
	    //     });
		// });

		$('#btDraft').click(function () {
			SaveData('T',$('#btDraft'),'Simpan Sementara');
		});

		$('#btBayar').click(function () {
			// payment-popup
			jQuery('#payment-popup').modal({backdrop: 'static', keyboard: false})
		    jQuery('#payment-popup').modal('show');

		    $('#_TotalTagihan').val($('#_GrandTotal').attr('originalvalue'));
		    $('#_TotalTagihanFormated').text($('#_GrandTotal').val())

			// Pembulatan
			var TotalPenjualan = $('#_GrandTotal').attr('originalvalue');
			var TotalPembulatan = Math.ceil(TotalPenjualan);
			var NilaiPembulatan = TotalPembulatan - TotalPenjualan;
			console.log(NilaiPembulatan)
			// formatCurrency($('#_TotalServices'), _tempTotalServices);
			// $('#_Pembulatan').val();
			formatCurrency($('#_Pembulatan'), NilaiPembulatan)
		    $('#_PembulatanFormated').text($('#_Pembulatan').val())

			// Total Penjualan
			// $('#_TotalNetBayar').val();
			formatCurrency($('#_TotalNetBayar'), TotalPembulatan)
		    $('#_TotalNetBayarFormated').text($('#_TotalNetBayar').val())
		});

		$('#btSimpanPembayaran').click(function () {
			// PaymentGateWay();

			if (_MetodeVerifikasiPembayaran == "AUTO") {
				PaymentGateWay('C',$('#btSimpanPembayaran'),'Submit');
			}
			else{
				SaveData('C',$('#btSimpanPembayaran'),'Submit');
			}
		});

		$('#btSearchCustomer').click(function () {
			jQuery('#LookupCustomer').modal({backdrop: 'static', keyboard: false})
			jQuery('#LookupCustomer').modal('show');
		});

		$('#btPilihCustomer').click(function () {
			var dataGridInstance = jQuery('#gridLookupCustomer').dxDataGrid('instance');
			var selectedRows = dataGridInstance.getSelectedRowsData();

			console.log(selectedRows);
			if (selectedRows.length > 0) {
				jQuery('#LookupCustomer').modal('hide');
				jQuery('#KodePelanggan').val(selectedRows[0]['KodePelanggan']).trigger('change');
			}
		});

		jQuery('#btAddCustomer').click(function () {
			jQuery('#LookupAddCustomer').modal({backdrop: 'static', keyboard: false})
			jQuery('#LookupAddCustomer').modal('show');
		})

		jQuery('#ModalProvID').change(function () {
			$.ajax({
                async   : false,
                type    : "post",
                url     : "{{route('demografipelanggan')}}",
                data    : {
                            'Table' : 'dem_kota',
                            'Field' : 'prov_id',
                            'Value' : jQuery('#ModalProvID').val(),
                            '_token': '{{ csrf_token() }}',
                        },
                dataType: "json",
                success: function (response) {
                    // console.log(response);
                    if (response.data.length > 0) {
                    	$('#ModalKotaID').empty();
                    	var newOption = $('<option>', {
			            	value: -1,
			            	text: "Pilih Kota"
			          	});
			          	$('#ModalKotaID').append(newOption); 
			          	$.each(response.data,function (k,v) {
				            var newOption = $('<option>', {
				            	value: v.city_id,
				            	text: v.city_name
				        	});

				        	$('#ModalKotaID').append(newOption);
				        });
                    }
                }
            });
		});


		jQuery('#ModalKotaID').change(function () {
			console.log('Test masuk')
			$.ajax({
                async   : false,
                type    : "post",
                url     : "{{route('demografipelanggan')}}",
                data    : {
                            'Table' : 'dem_kecamatan',
                            'Field' : 'kota_id',
                            'Value' : $('#ModalKotaID').val(),
                            '_token': '{{ csrf_token() }}',
                        },
                dataType: "json",
                success: function (response) {
                    // console.log(response);
                    if (response.data.length > 0) {
                    	$('#ModalKecID').empty();
                    	var newOption = $('<option>', {
			            	value: -1,
			            	text: "Pilih Kecamatan"
			          	});
			          	$('#ModalKecID').append(newOption); 
			          	$.each(response.data,function (k,v) {
				            var newOption = $('<option>', {
				            	value: v.dis_id,
				            	text: v.dis_name
				        	});

				        	$('#ModalKecID').append(newOption);
				        });
                    }
                }
            });
		});


		jQuery('#ModalKecID').change(function () {
			console.log('Test masuk')
			$.ajax({
                async   : false,
                type    : "post",
                url     : "{{route('demografipelanggan')}}",
                data    : {
                            'Table' : 'dem_kelurahan',
                            'Field' : 'kec_id',
                            'Value' : $('#ModalKecID').val(),
                            '_token': '{{ csrf_token() }}',
                        },
                dataType: "json",
                success: function (response) {
                    // console.log(response);
                    if (response.data.length > 0) {
                    	$('#ModalKelID').empty();
                    	var newOption = $('<option>', {
			            	value: -1,
			            	text: "Pilih Kelurahan"
			          	});
			          	$('#ModalKelID').append(newOption); 
			          	$.each(response.data,function (k,v) {
				            var newOption = $('<option>', {
				            	value: v.subdis_id,
				            	text: v.subdis_name
				        	});

				        	$('#ModalKelID').append(newOption);
				        });
                    }
                }
            });
		});

		jQuery('#btSaveAddCustomer').click(function () {
			$.ajax({
                async   : false,
                type    : "post",
                url     : "{{route('pelanggan-storeJson')}}",
                headers: {
	                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
	            },
                data    : {
                	'NamaPelanggan' : jQuery('#ModalNamaPelanggan').val(),
                	'KodeGrupPelanggan' : jQuery('#ModalKodeGrupPelanggan').val(),
                	'LimitPiutang' : jQuery('#ModalLimitPiutang').val(),
                	'ProvID' : jQuery('#ModalProvID').val(),
                	'KotaID' : jQuery('#ModalKotaID').val(),
                	'KelID' : jQuery('#ModalKelID').val(),
                	'KecID' : jQuery('#ModalKecID').val(),
                	'Email' : jQuery('#ModalEmail').val(),
                	'NoTlp1' : jQuery('#ModalNoTlp1').val(),
                	'NoTlp2' : jQuery('#ModalNoTlp2').val(),
                	'Alamat' : jQuery('#ModalAlamat').val(),
                	'Keterangan' : jQuery('#ModalKeterangan').val(),
                	'Status' : jQuery('#ModalStatus').val()
                },
                dataType: "json",
                success: function (response) {
                    // console.log(response);
                    if (response.success == true) {
                    	jQuery('#LookupAddCustomer').modal('hide');
                    	var newOption = $('<option>', {
			            	value: response.LastTRX,
			            	text: jQuery('#ModalNamaPelanggan').val()
			          	});
			          	jQuery('#KodePelanggan').append(newOption);
			          	jQuery('#KodePelanggan').val(response.LastTRX).trigger('change');
                    }
                    else{
                    	Swal.fire({
	                      icon: "error",
	                      title: "Opps...",
	                      text: response.message,
	                    });
                    }
                }
            });
		});

	});

	function AddNewRow(KodeItem) {
		var RandomID = generateRandomText(10);
        var newRow = document.createElement('tr');
        newRow.className = RandomID;
        newRow.id = "InputSectionData"

		var tbody = document.querySelectorAll('#InputSectionData');
		// console.log(tbody);
        var index = 0;

		if (tbody.length > 0) {
			index = tbody.length + 1;
		}

		// Check if Exists
		var existingRow = Array.from(document.querySelectorAll('input[id="txtKodeItem"]')).find(function(input) {
			return input.value === KodeItem;
		});

		if (existingRow) {
        // If the row exists, update QtyText
			var row = existingRow.closest('tr');
			var qtyText = row.querySelector('input[id="txtQty"]');
			console.log(qtyText)
			qtyText.value = parseInt(qtyText.value) + 1; // Or set to any value you want
			updateTotal(row); // Update Total based on new Qty
			return;
		}

		// Filter Item
		let filteredItem = _oItemMenu.filter(function(item) {
            return item.KodeItem == KodeItem;
        });

		var nomorCol = document.createElement('td');
        var ItemCol = document.createElement('td');
        var QtyCol = document.createElement('td');
        var HargaCol = document.createElement('td');
		var TotalCol = document.createElement('td');
		var DiskonCol = document.createElement('td');
        var RemoveCol = document.createElement('td');

		var NamaItemText = document.createElement('input');
		var KodeItemText = document.createElement('input');
		var QtyText = document.createElement('input');
		var HargaText = document.createElement('input');
		var TotalText = document.createElement('input');
		var DiskonText = document.createElement('input');
		
		// Item
		NamaItemText.type  = 'text';
		NamaItemText.id = "txtNamaItem";
        NamaItemText.name = 'DetailParameter['+index+'][NamaItem]';
        NamaItemText.placeholder = "Tambah Nama Item";
        NamaItemText.className = 'form-control';
        NamaItemText.required = true;
        NamaItemText.value = filteredItem[0]["NamaItem"];
        NamaItemText.readOnly = true;
		NamaItemText.title = filteredItem[0]["NamaItem"];
        ItemCol.appendChild(NamaItemText);

        KodeItemText.type = "hidden";
		KodeItemText.id = "txtKodeItem";
        KodeItemText.name = 'DetailParameter['+index+'][KodeItem]';
        KodeItemText.value = KodeItem;
        ItemCol.appendChild(KodeItemText);
		// End Item

		// Jumlah
        QtyText.type  = 'number';
		QtyText.id = "txtQty";
        QtyText.name = 'DetailParameter['+index+'][Qty]';
        QtyText.placeholder = "Quantity";
        QtyText.className = 'form-control';
        QtyText.value = 1;
        QtyText.required = true;
        QtyText.addEventListener('input', function() {
			updateTotal(newRow);
			CalculateTotal();
            // let value = QtyText.value;
            // console.log('Current Value: ' + value);
            // PemakaianText.value = value;
            // QtyText.setAttribute('Qty', value);
			// TotalText.value = value * HargaText.value;
        });
        QtyCol.appendChild(QtyText);
		// End Jumlah

		// Harga
        HargaText.type  = 'number';
		HargaText.id = "txtHarga";
        HargaText.name = 'DetailParameter['+index+'][HargaJual]';
        HargaText.placeholder = "Harga";
        HargaText.className = 'form-control';
        HargaText.value = filteredItem[0]["HargaJual"];
        HargaText.required = true;
		HargaText.readOnly = true;
        HargaCol.appendChild(HargaText);
		// End Harga

		// Diskon
		DiskonText.type  = 'number';
		DiskonText.id = "txtDiskon";
        DiskonText.name = 'DetailParameter['+index+'][Diskon]';
        DiskonText.placeholder = "Diskon (%)";
        DiskonText.className = 'form-control';
        DiskonText.value = 0;
        DiskonText.required = true;
		DiskonText.addEventListener('input', function() {
			updateTotal(newRow);
			CalculateTotal();
            // let value = DiskonText.value;
            // console.log('Current Value: ' + value);
            // PemakaianText.value = value;
            // DiskonText.setAttribute('HargaJual', value);
        });
        DiskonCol.appendChild(DiskonText);
		// End Diskon

		// Total
		TotalText.type  = 'number';
		TotalText.id = "txtTotal";
        TotalText.name = 'DetailParameter['+index+'][Total]';
        TotalText.placeholder = "Total";
        TotalText.className = 'form-control';
        TotalText.value = (QtyText.value * HargaText.value) - ((DiskonText.value / 100) * (QtyText.value * HargaText.value));
        TotalText.required = true;
		TotalText.readOnly = true;
        TotalCol.appendChild(TotalText);
		// End Total
		console.log(QtyText.value + " * " + HargaText.value)

		var RemoveText = document.createElement('button');
        RemoveText.innerText   = 'Delete Data';
        RemoveText.type   = 'button';
        // RemoveText.style.color = "red";
        // RemoveText.href = "#"+RandomID;
        RemoveText.className = "btn btn-danger RemoveLineItem";
        RemoveText.id = "RemoveLineItem";
        RemoveText.onclick = function() {
            // alert('Button in row  clicked! ' + RandomID);
            var elements = document.querySelectorAll('.'+RandomID);
			var elementsVariant = document.querySelectorAll('.'+KodeItem);
            // elements.remove();
            elements.forEach(function(element) {
                element.remove();
            });

			elementsVariant.forEach(function(element) {
                element.remove();
            });
            AsignRowNumber();
			FirstRowHandling();
			CalculateTotal();
            // console.log(elements)
        };
        RemoveCol.appendChild(RemoveText);
		newRow.appendChild(nomorCol);
        newRow.appendChild(ItemCol);
        newRow.appendChild(QtyCol);
        newRow.appendChild(HargaCol);
		newRow.appendChild(DiskonCol);
		newRow.appendChild(TotalCol);
		newRow.appendChild(RemoveCol);
        document.getElementById('AppendArea').appendChild(newRow);

		// Auto select newly added row
		$('#AppendArea tr#InputSectionData').removeClass('selected-row');
		newRow.classList.add('selected-row');

		_oTxtKodeItem = document.querySelectorAll('#txtKodeItem');

		// Remove Blank Item

		var emptyCells = document.querySelectorAll('#printableTable td.dataTables_empty');
		emptyCells.forEach(function(cell) {
			var row = cell.closest('tr');
			row.parentNode.removeChild(row);
		});
	}
	
	function AddAddonVariantMenu(KodeItem, oDraftData = []) {
		console.log(oDraftData);
		if (oDraftData.length > 0) {
			for (let index = 0; index < oDraftData.length; index++) {
				var RandomID = generateRandomText(10);
				var newRow = document.createElement('tr');
				newRow.className = KodeItem;
				newRow.id = "VariantSectionData"

				var tbody = document.querySelectorAll('#VariantSectionData');
				// console.log(tbody);
				var indexInternal = 0;

				if (tbody.length > 0) {
					indexInternal = tbody.length + 1;
				}

				var nomorCol = document.createElement('td');
				var NamaVariant = document.createElement('td');
				NamaVariant.setAttribute("colspan",3)
				var ExtraQty = document.createElement('td');
				var ExtraPrice = document.createElement('td');

				var GrupVariantID = document.createElement('input');
				var VariantID = document.createElement('input');
				var txtNamaVariant = document.createElement("input");
				var txtExtraPrice = document.createElement("input");
				var txtExtraQty = document.createElement("input");
				var txtKodeItem = document.createElement("input");

				GrupVariantID.type  = 'hidden';
				GrupVariantID.id = "txtGrupVariantID";
				GrupVariantID.name = 'VariantParameter['+indexInternal+'][GrupVariantID]';
				GrupVariantID.placeholder = "Tambah Nama Item";
				GrupVariantID.className = 'form-control';
				GrupVariantID.required = true;
				GrupVariantID.value = oDraftData[index]["VariantGrupID"]
				GrupVariantID.readOnly = true;
				NamaVariant.appendChild(GrupVariantID);

				VariantID.type  = 'hidden';
				VariantID.id = "txtVariantID";
				VariantID.name = 'VariantParameter['+indexInternal+'][VariantID]';
				VariantID.placeholder = "Tambah Nama Item";
				VariantID.className = 'form-control';
				VariantID.required = true;
				VariantID.value = oDraftData[index]["VariantID"];
				VariantID.readOnly = true;
				NamaVariant.appendChild(VariantID);

				txtKodeItem.type  = 'hidden';
				txtKodeItem.id = "txtKodeItem";
				txtKodeItem.name = 'VariantParameter['+indexInternal+'][KodeItem]';
				txtKodeItem.placeholder = "Tambah Nama Item";
				txtKodeItem.className = 'form-control';
				txtKodeItem.required = true;
				txtKodeItem.value = KodeItem
				txtKodeItem.readOnly = true;
				NamaVariant.appendChild(txtKodeItem);

				txtNamaVariant.type  = 'text';
				txtNamaVariant.id = "txtNamaItem";
				txtNamaVariant.name = 'VariantParameter['+indexInternal+'][NamaVariant]';
				txtNamaVariant.placeholder = "Tambah Nama Item";
				txtNamaVariant.className = 'form-control';
				txtNamaVariant.required = true;
				txtNamaVariant.value = oDraftData[index]["NamaVariant"]
				txtNamaVariant.readOnly = true;
				NamaVariant.appendChild(txtNamaVariant);

				txtExtraPrice.type  = 'text';
				txtExtraPrice.id = "txtExtraPrice";
				txtExtraPrice.name = 'VariantParameter['+indexInternal+'][ExtraPrice]';
				txtExtraPrice.placeholder = "Tambah Nama Item";
				txtExtraPrice.className = 'form-control';
				txtExtraPrice.required = true;
				txtExtraPrice.value = oDraftData[index]["ExtraPrice"]
				txtExtraPrice.readOnly = true;
				ExtraPrice.appendChild(txtExtraPrice);

				txtExtraQty.type  = 'text';
				txtExtraQty.id = "txtExtraQty";
				txtExtraQty.name = 'VariantParameter['+indexInternal+'][ExtraQty]';
				txtExtraQty.placeholder = "Tambah Nama Item";
				txtExtraQty.className = 'form-control';
				txtExtraQty.required = true;
				txtExtraQty.value = 1
				txtExtraQty.readOnly = true;
				ExtraQty.appendChild(txtExtraQty);

				newRow.appendChild(nomorCol);
				newRow.appendChild(NamaVariant);
				newRow.appendChild(ExtraQty);
				newRow.appendChild(ExtraPrice);
				document.getElementById('AppendArea').appendChild(newRow);	
			}
		}
		else{
			var RandomID = generateRandomText(10);
			var newRow = document.createElement('tr');
			newRow.className = KodeItem;
			newRow.id = "VariantSectionData"

			var tbody = document.querySelectorAll('#VariantSectionData');
			// console.log(tbody);
			var index = 0;

			if (tbody.length > 0) {
				index = tbody.length + 1;
			}

			var nomorCol = document.createElement('td');
			var NamaVariant = document.createElement('td');
			NamaVariant.setAttribute("colspan",3)
			var ExtraQty = document.createElement('td');
			var ExtraPrice = document.createElement('td');

			var GrupVariantID = document.createElement('input');
			var VariantID = document.createElement('input');
			var txtNamaVariant = document.createElement("input");
			var txtExtraPrice = document.createElement("input");
			var txtExtraQty = document.createElement("input");
			var txtKodeItem = document.createElement("input");

			GrupVariantID.type  = 'hidden';
			GrupVariantID.id = "txtGrupVariantID";
			GrupVariantID.name = 'VariantParameter['+index+'][GrupVariantID]';
			GrupVariantID.placeholder = "Tambah Nama Item";
			GrupVariantID.className = 'form-control';
			GrupVariantID.required = true;
			GrupVariantID.value = _oSelectedVariant["VariantGrupID"]
			GrupVariantID.readOnly = true;
			NamaVariant.appendChild(GrupVariantID);

			VariantID.type  = 'hidden';
			VariantID.id = "txtVariantID";
			VariantID.name = 'VariantParameter['+index+'][VariantID]';
			VariantID.placeholder = "Tambah Nama Item";
			VariantID.className = 'form-control';
			VariantID.required = true;
			VariantID.value = _oSelectedVariant["id"].replace("variant","")
			VariantID.readOnly = true;
			NamaVariant.appendChild(VariantID);

			txtKodeItem.type  = 'text';
			txtKodeItem.id = "txtKodeItem";
			txtKodeItem.name = 'VariantParameter['+index+'][KodeItem]';
			txtKodeItem.placeholder = "Tambah Nama Item";
			txtKodeItem.className = 'form-control';
			txtKodeItem.required = true;
			txtKodeItem.value = KodeItem
			txtKodeItem.readOnly = true;
			NamaVariant.appendChild(txtKodeItem);

			txtNamaVariant.type  = 'text';
			txtNamaVariant.id = "txtNamaItem";
			txtNamaVariant.name = 'VariantParameter['+index+'][NamaVariant]';
			txtNamaVariant.placeholder = "Tambah Nama Item";
			txtNamaVariant.className = 'form-control';
			txtNamaVariant.required = true;
			txtNamaVariant.value = _oSelectedVariant["NamaVariant"]
			txtNamaVariant.readOnly = true;
			NamaVariant.appendChild(txtNamaVariant);

			txtExtraPrice.type  = 'text';
			txtExtraPrice.id = "txtExtraPrice";
			txtExtraPrice.name = 'VariantParameter['+index+'][ExtraPrice]';
			txtExtraPrice.placeholder = "Tambah Nama Item";
			txtExtraPrice.className = 'form-control';
			txtExtraPrice.required = true;
			txtExtraPrice.value = _oSelectedVariant["ExtraPrice"]
			txtExtraPrice.readOnly = true;
			ExtraPrice.appendChild(txtExtraPrice);

			txtExtraQty.type  = 'text';
			txtExtraQty.id = "txtExtraQty";
			txtExtraQty.name = 'VariantParameter['+index+'][ExtraQty]';
			txtExtraQty.placeholder = "Tambah Nama Item";
			txtExtraQty.className = 'form-control';
			txtExtraQty.required = true;
			txtExtraQty.value = 1
			txtExtraQty.readOnly = true;
			ExtraQty.appendChild(txtExtraQty);

			newRow.appendChild(nomorCol);
			newRow.appendChild(NamaVariant);
			newRow.appendChild(ExtraQty);
			newRow.appendChild(ExtraPrice);
			document.getElementById('AppendArea').appendChild(newRow);
		}
	}

	function AddAddonMenu(KodeItem, oData, oDraftData = []) {
		if (oDraftData.length > 0) {
			for (let index = 0; index < oDraftData.length; index++) {
				console.log(oData);
				var RandomID = generateRandomText(10);
				var newRow = document.createElement('tr');
				newRow.className = KodeItem;
				newRow.id = "AddonSectionData"

				var tbody = document.querySelectorAll('#AddonSectionData');
				// console.log(tbody);
				var AddAddonMenu = 0;

				if (tbody.length > 0) {
					AddAddonMenu = tbody.length + 1;
				}

				var nomorCol = document.createElement('td');
				var NamaVariant = document.createElement('td');
				NamaVariant.setAttribute("colspan",3)
				var ExtraQty = document.createElement('td');
				var ExtraPrice = document.createElement('td');

				var MenuAddonID = document.createElement('input');
				var txtNamaVariant = document.createElement("input");
				var txtExtraPrice = document.createElement("input");
				var txtExtraQty = document.createElement("input");
				var txtKodeItem = document.createElement("input");

				MenuAddonID.type  = 'hidden';
				MenuAddonID.id = "txtMenuAddonID";
				MenuAddonID.name = 'AddonParameter['+AddAddonMenu+'][MenuAddonID]';
				MenuAddonID.placeholder = "Tambah Nama Item";
				MenuAddonID.className = 'form-control';
				MenuAddonID.required = true;
				MenuAddonID.value = oDraftData[index]["AddonMenuID"]
				MenuAddonID.readOnly = true;
				NamaVariant.appendChild(MenuAddonID);

				txtKodeItem.type  = 'hidden';
				txtKodeItem.id = "txtKodeItem";
				txtKodeItem.name = 'AddonParameter['+AddAddonMenu+'][KodeItem]';
				txtKodeItem.placeholder = "Tambah Nama Item";
				txtKodeItem.className = 'form-control';
				txtKodeItem.required = true;
				txtKodeItem.value = KodeItem;
				txtKodeItem.readOnly = true;
				NamaVariant.appendChild(txtKodeItem);

				txtNamaVariant.type  = 'text';
				txtNamaVariant.id = "txtNamaItem";
				txtNamaVariant.name = 'AddonParameter['+AddAddonMenu+'][NamaAddon]';
				txtNamaVariant.placeholder = "Tambah Nama Item";
				txtNamaVariant.className = 'form-control';
				txtNamaVariant.required = true;
				txtNamaVariant.value = oDraftData[index]["NamaAddon"]
				txtNamaVariant.readOnly = true;
				NamaVariant.appendChild(txtNamaVariant);

				txtExtraPrice.type  = 'text';
				txtExtraPrice.id = "txtExtraPrice";
				txtExtraPrice.name = 'AddonParameter['+AddAddonMenu+'][HargaAddon]';
				txtExtraPrice.placeholder = "Tambah Nama Item";
				txtExtraPrice.className = 'form-control';
				txtExtraPrice.required = true;
				txtExtraPrice.value = oDraftData[index]["HargaAddon"]
				txtExtraPrice.readOnly = true;
				ExtraPrice.appendChild(txtExtraPrice);

				txtExtraQty.type  = 'text';
				txtExtraQty.id = "txtExtraQty";
				txtExtraQty.name = 'AddonParameter['+AddAddonMenu+'][Qty]';
				txtExtraQty.placeholder = "Tambah Nama Item";
				txtExtraQty.className = 'form-control';
				txtExtraQty.required = true;
				txtExtraQty.value = oDraftData[index]["ExtraQty"]
				txtExtraQty.readOnly = true;
				ExtraQty.appendChild(txtExtraQty);

				newRow.appendChild(nomorCol);
				newRow.appendChild(NamaVariant);
				newRow.appendChild(ExtraQty);
				newRow.appendChild(ExtraPrice);
				document.getElementById('AppendArea').appendChild(newRow);	
			}
		}
		else{
			console.log(oData);
			var RandomID = generateRandomText(10);
			var newRow = document.createElement('tr');
			newRow.className = KodeItem;
			newRow.id = "AddonSectionData"

			var tbody = document.querySelectorAll('#AddonSectionData');
			// console.log(tbody);
			var index = 0;

			if (tbody.length > 0) {
				index = tbody.length + 1;
			}

			var nomorCol = document.createElement('td');
			var NamaVariant = document.createElement('td');
			NamaVariant.setAttribute("colspan",3)
			var ExtraQty = document.createElement('td');
			var ExtraPrice = document.createElement('td');

			var MenuAddonID = document.createElement('input');
			var txtNamaVariant = document.createElement("input");
			var txtExtraPrice = document.createElement("input");
			var txtExtraQty = document.createElement("input");
			var txtKodeItem = document.createElement("input");

			MenuAddonID.type  = 'hidden';
			MenuAddonID.id = "txtMenuAddonID";
			MenuAddonID.name = 'AddonParameter['+index+'][MenuAddonID]';
			MenuAddonID.placeholder = "Tambah Nama Item";
			MenuAddonID.className = 'form-control';
			MenuAddonID.required = true;
			MenuAddonID.value = oData["AddonMenuID"]
			MenuAddonID.readOnly = true;
			NamaVariant.appendChild(MenuAddonID);

			txtKodeItem.type  = 'text';
			txtKodeItem.id = "txtKodeItem";
			txtKodeItem.name = 'AddonParameter['+index+'][KodeItem]';
			txtKodeItem.placeholder = "Tambah Nama Item";
			txtKodeItem.className = 'form-control';
			txtKodeItem.required = true;
			txtKodeItem.value = KodeItem;
			txtKodeItem.readOnly = true;
			NamaVariant.appendChild(txtKodeItem);

			txtNamaVariant.type  = 'text';
			txtNamaVariant.id = "txtNamaItem";
			txtNamaVariant.name = 'AddonParameter['+index+'][NamaAddon]';
			txtNamaVariant.placeholder = "Tambah Nama Item";
			txtNamaVariant.className = 'form-control';
			txtNamaVariant.required = true;
			txtNamaVariant.value = oData["NamaAddon"]
			txtNamaVariant.readOnly = true;
			NamaVariant.appendChild(txtNamaVariant);

			txtExtraPrice.type  = 'text';
			txtExtraPrice.id = "txtExtraPrice";
			txtExtraPrice.name = 'AddonParameter['+index+'][HargaAddon]';
			txtExtraPrice.placeholder = "Tambah Nama Item";
			txtExtraPrice.className = 'form-control';
			txtExtraPrice.required = true;
			txtExtraPrice.value = oData["HargaAddon"]
			txtExtraPrice.readOnly = true;
			ExtraPrice.appendChild(txtExtraPrice);

			txtExtraQty.type  = 'text';
			txtExtraQty.id = "txtExtraQty";
			txtExtraQty.name = 'AddonParameter['+index+'][Qty]';
			txtExtraQty.placeholder = "Tambah Nama Item";
			txtExtraQty.className = 'form-control';
			txtExtraQty.required = true;
			txtExtraQty.value = 1
			txtExtraQty.readOnly = true;
			ExtraQty.appendChild(txtExtraQty);

			newRow.appendChild(nomorCol);
			newRow.appendChild(NamaVariant);
			newRow.appendChild(ExtraQty);
			newRow.appendChild(ExtraPrice);
			document.getElementById('AppendArea').appendChild(newRow);
		}
	}

	function CalculateRowTotal(qty, harga, diskon) {
		return (qty * harga) - ((diskon / 100) * (qty * harga));
		CalculateTotal();
	}

	function updateTotal(row) {
		var qty = row.querySelector('input[id="txtQty"]').value;
		var harga = row.querySelector('input[id="txtHarga"]').value;
		var diskon = row.querySelector('input[id="txtDiskon"]').value;
		var totalText = row.querySelector('input[id="txtTotal"]');
		totalText.value = CalculateRowTotal(qty, harga, diskon);
	}

	function AsignRowNumber() {
        var tbody = document.querySelectorAll('#InputSectionData');
        tbody.forEach(function(row, index) {
            var firstCell = row.querySelector('td:first-child');
            if (firstCell) {
                firstCell.textContent = index + 1;
            }
        });
    }

	function generateRandomText(length) {
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let randomText = '';
        for (let i = 0; i < length; i++) {
            const randomIndex = Math.floor(Math.random() * characters.length);
            randomText += characters[randomIndex];
        }
        return randomText;
    }

	function FirstRowHandling() {
		var tbody = document.querySelectorAll('#InputSectionData');
		if (tbody.length == 1) {
			// Find and remove the empty message element if it exists
			// $('td.dataTables_empty').remove();
			var emptyMessage = document.querySelector('td.dataTables_empty');
			console.log(emptyMessage)
			if (emptyMessage) {
				emptyMessage.remove();
			}
		}
		else if (tbody.length == 0) {
			var newEmptyMessage = document.createElement('tr');
			var emptyCell = document.createElement('td');
			emptyCell.colSpan = 7; // Adjust colspan as needed
			emptyCell.className = 'dataTables_empty';
			emptyCell.textContent = 'No data available in table';
			newEmptyMessage.appendChild(emptyCell);

			document.getElementById('AppendArea').appendChild(newEmptyMessage);
		}
		
	}

	


	function LoadDraftOrderList() {
		$.ajax({
			async:false,
			url: "{{route('fpenjualan-readheader')}}",
			type: 'POST',
			headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
            },
            data: {
            	'TglAwal':'1999-01-01',
            	'TglAkhir' : _Tanggal,
            	'KodePelanggan' : '',
            	'Status' : 'T'
           	},
            success: function(response) {
            	// console.log(response);
            	jQuery('#_draftCount').text(response.data.length);

            	if (response.data.length > 0) {
            		jQuery('#_draftOrderList').empty();
            		var xHTML = '<div class="row">';
            		$.each(response.data,function (k,v) {
            			var xNoTransaksi = "'"+v.NoTransaksi+"'";
            			xHTML += '<div class="col-lg-4">';
            			xHTML += '	<div class="pos-order">';
            			xHTML += '		<center><h4 class="pos-order-title" >'+v.NoTransaksi+'</h4></center>';
            			xHTML += '			<div class="orderdetail-pos">';
            			xHTML += '				<p><strong>Customer Name</strong> '+v.NamaPelanggan+'</p>';
            			xHTML += '				<p><strong>Payment Status</strong> Pending</p>';
            			xHTML += '				<p><strong>Total Item</strong> '+v.TotalItems+' Items</p>';
            			xHTML += '				<p><strong>Total Transaksi</strong> '+v.TotalHutang.toLocaleString('en-US')+'</p>';
            			xHTML += '			</div>';
            			xHTML += '			<div class="d-flex justify-content-end">';
            			xHTML += '				<a class="confirm-delete ms-3" title="Edit" onClick = "editDraft('+xNoTransaksi+')"><i class="fas fa-edit"></i></a>';
            			xHTML += '				<a class="confirm-delete ms-3" title="Delete" onClick = "deleteDraft('+xNoTransaksi+')"><i class="fas fa-trash-alt"></i></a>';
            			xHTML += '			</div>';
            			xHTML += '	</div>';
            			xHTML += '</div>';
            			
            		});

            		xHTML += '</div>';

            		console.log(xHTML);

            			jQuery('#_draftOrderList').html(xHTML);
            	}
            }
		});
	}

	function PrintStruk(NoTransaksi) {

		if(_Company[0]["NamaPosPrinter"] == ""){
			Swal.fire({
				icon: "error",
				title: "Opps...",
				text: "Printer Belum ditentukan, Silahkan melakukan setting di menu Master -> Pengaturan Toko -> Perusahaan",
			}).then((result) => {
				return;
			});
		}

		if(_Company[0]["LebarKertas"] == ""){
			Swal.fire({
				icon: "error",
				title: "Opps...",
				text: "Lebar Kertas Belum ditentukan, Silahkan melakukan setting di menu Master -> Pengaturan Toko -> Perusahaan",
			}).then((result) => {
				return;
			});
		}

		if(_Printer["PrinterInterface"] == "Bluetooth"){
			$.ajax({
				async:false,
				url: "{{route('print-retail')}}",
				type: 'POST',
				headers: {
					'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
				},
				data: {'NoTransaksi':NoTransaksi},
				success: function(response) {
					if (response.success == true) {
						Swal.fire({
						icon: "success",
						title: "Sukses",
						text: "Data Penjualan Berhasil Disimpan",
						}).then((result) => {
							location.reload();
						});
					}
					else{
						Swal.fire({
							icon: "error",
							title: "Opps...",
							text: response.message,
						});
					}
				}
			});
		}
		else if(_Printer["PrinterInterface"] == "USB"){
			// var link = "fpenjualan/printthermal/"+cellInfo.data.NoTransaksi;
			let url = "{{ url('') }}";
            // url.searchParams.append('NoTransaksi', NoTransaksi);
			url += "/fpenjualan/printthermal/"+NoTransaksi;
			console.log(url);
			// // window.location.href = url.toString();
			window.open(url, "_blank");
			location.reload();
		}
		else{
			Swal.fire({
				icon: "error",
				title: "Opps...",
				text: "Interface belum tersedia",
			});
		}
	}

	function GetItemInfo(KodeItem) {
		var oReturnData = {};

		$.ajax({
            async:false,
            type: 'post',
            url: "{{route('itemmaster-ViewJson')}}",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
            },
            data: {
                'KodeJenis' : '',
			    'Merk' 		: '',
			    'TipeItem' 	: '',
				'Active' 	: 'Y',
				'Scan'		: KodeItem,
				'TipeItemIN' : '1,3,5'
            },
            dataType: 'json',
            success: function(response) {
            	if (response.data.length > 0) {
            		oReturnData = response.data;
            	}
            }
        });

        return oReturnData;
	}

	function CalculateDiskon(KodeItem, Qty) {
		var DiskReturn = {};

		$.ajax({
            async:false,
            type: 'post',
            url: "{{route('fpenjualan-getDiskon')}}",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
            },
            data: {
                'KodeItem' : KodeItem,
                'Qty' 		: Qty
            },
            dataType: 'json',
            success: function(response) {
            	DiskReturn ={
            		Diskon : response.Diskon,
            		DiskonType : response.TipeDiskon
            	}
            }
        });

		return DiskReturn;
	}

	

	function bindGridLookupServices(data) {
		var dataGridInstance = jQuery("#gridLookupServices").dxDataGrid({
			allowColumnResizing: true,
			dataSource: data,
			keyExpr: "NoUrut",
			showBorders: true,
            allowColumnResizing: true,
            columnAutoWidth: true,
            showBorders: true,
            paging: {
                enabled: true,
                pageSize: 30
            },
            editing: {
                mode: "row",
                allowUpdating: true,
                allowDeleting: true,
                texts: {
                    confirmDeleteMessage: ''  
                }
            },
            columns: [
            	{
                    dataField: "NoUrut",
                    caption: "#",
                    allowEditing:false,
                    allowSorting: false 
                },
                {
                    dataField: "KodeItem",
                    caption: "Jasa",
                    lookup: {
					    dataSource: <?php echo $itemServices ?>,
					    valueExpr: 'KodeItem',
					    displayExpr: 'NamaItem',
				    },
				    allowSorting: false,
				    allowEditing:true
                },
                {
                    dataField: "Jumlah",
                    caption: "Jumlah",
                    allowEditing:true,
                    format: { type: 'fixedPoint', precision: 2 },
                    allowSorting: false 
                },
                {
                    dataField: "Keterangan",
                    caption: "Keterangan",
                    allowEditing:true,
                    allowSorting: false 
                },
            ],
            onContentReady: function(e) {
	            var rowData = dataGridInstance.option("dataSource");
	            if (rowData.length == 1) {
	            	dataGridInstance.editRow(0)	
	            }
	        },
	        onCellClick:function (e) {
	            var rowData = dataGridInstance.option("dataSource");
	            var columnIndex = e.columnIndex;
	            console.log(e)
	        	if (columnIndex >= 1 && columnIndex <= 5) {
	                dataGridInstance.editRow(e.rowIndex)	
	            }
	            dataGridInstance.option("focusedColumnIndex", columnIndex);	
	        },
		}).dxDataGrid('instance');

		var allRowsData  = dataGridInstance.option("dataSource");
    	var newData = { NoUrut: allRowsData.length + 1,KodeItem:"", Jumlah: 0, Keterangan:'' }
    	dataGridInstance.option("dataSource", [...dataGridInstance.option("dataSource"), newData]);
    	dataGridInstance.refresh();

    	dataGridInstance.on('rowUpdated', function(e) {
    		// console.log(e)
    		CalculateTotal();
    	});

    	dataGridInstance.on('editorPreparing',function (e) {
    		if (e.parentType === "dataRow" && e.dataField === "KodeItem") {
    			e.editorOptions.onFocusOut = (x) => {
    				var rowIndex = dataGridInstance.getRowIndexByKey(e.row.key);

    				dataGridInstance.cellValue(rowIndex, "Jumlah", 0);
		            dataGridInstance.cellValue(rowIndex, "Keterangan", '');
		            // dataGridInstance.cellValue(rowIndex, "Qty", 1);

		            dataGridInstance.refresh();

		            dataGridInstance.saveEditData();

		            var allRowsData  = dataGridInstance.option("dataSource");
                    var newData = { NoUrut: allRowsData.length + 1,KodeItem:"", Jumlah: 0, Keterangan:'' }
    				dataGridInstance.option("dataSource", [...dataGridInstance.option("dataSource"), newData]);
    				dataGridInstance.refresh();
    			}
    		}
    		else if (e.parentType === "dataRow" && e.dataField === "Jumlah") {
		    	e.editorOptions.onFocusOut = (x) => {
		    		dataGridInstance.saveEditData();
		    		CalculateTotal();
		    	}
		    }
		    else if (e.parentType === "dataRow" && e.dataField === "Keterangan") {
		    	e.editorOptions.onFocusOut = (x) => {
		    		dataGridInstance.saveEditData();
		    	}
		    }
    	})
	}

	function bindGridLookup(data) {
		// gridLookupItem
		var dataGridInstance = jQuery("#gridLookupItem").dxDataGrid({
			allowColumnResizing: true,
			dataSource: data,
			keyExpr: "KodeItem",
			showBorders: true,
            allowColumnResizing: true,
            columnAutoWidth: true,
            showBorders: true,
            paging: {
                enabled: true,
                pageSize: 30
            },
            editing: {
                mode: "row",
                texts: {
                    confirmDeleteMessage: ''  
                }
            },
            selection: {
                mode: "single" // Enable single selection mode
            },
            searchPanel: {
	            visible: true,
	            width: 240,
	            placeholder: "Search..."
	        },
            columns: [
            	{
                    dataField: "KodeItem",
                    caption: "Kode Item",
                    allowSorting: true,
                    allowEditing : false
                },
                {
                    dataField: "Barcode",
                    caption: "Barcode",
                    allowSorting: true,
                    allowEditing : false
                },
                {
                    dataField: "NamaItem",
                    caption: "Nama Item",
                    allowSorting: true,
                    allowEditing : false
                },
                {
                    dataField: "Stock",
                    caption: "Stock",
                    allowSorting: true,
                    allowEditing : false,
                    format: { type: 'fixedPoint', precision: 2 },
                },
                {
                    dataField: "Satuan",
                    caption: "Sat",
                    allowSorting: true,
                    allowEditing : false
                },
            ]
		}).dxDataGrid('instance');
	}

	function bindGridLookupCustomer(data) {
		// gridLookupItem
		var dataGridInstance = jQuery("#gridLookupCustomer").dxDataGrid({
			allowColumnResizing: true,
			dataSource: data,
			keyExpr: "KodePelanggan",
			showBorders: true,
            allowColumnResizing: true,
            columnAutoWidth: true,
            showBorders: true,
            paging: {
                enabled: true,
                pageSize: 30
            },
            editing: {
                mode: "row",
                texts: {
                    confirmDeleteMessage: ''  
                }
            },
            selection: {
                mode: "single" // Enable single selection mode
            },
            searchPanel: {
	            visible: true,
	            width: 240,
	            placeholder: "Search..."
	        },
            columns: [
            	{
                    dataField: "KodePelanggan",
                    caption: "Kode Pelanggan",
                    allowSorting: true,
                    allowEditing : false
                },
                {
                    dataField: "NamaPelanggan",
                    caption: "Nama Pelanggan",
                    allowSorting: true,
                    allowEditing : false
                },
                {
                    dataField: "NoTlpConcat",
                    caption: "No. HP",
                    allowSorting: true,
                    allowEditing : false
                },
            ]
		}).dxDataGrid('instance');
	}
	
	function SaveData(Status, ButonObject, ButtonDefaultText) {
		UpdateCurrentTime();
		ButonObject.text('Tunggu Sebentar.....');
  		ButonObject.attr('disabled',true);

  		var NoTransaksi = "";
  		if (jQuery('#_NoTransaksi').text() != "<OTOMATIS>") {
  			NoTransaksi = jQuery('#_NoTransaksi').text();
  		}
  		// console.log(allRowsData)
  		var oDetail = [];
		var oVariant = [];

		var rows = document.querySelectorAll('#AppendArea tr#InputSectionData');
		var rowsVariant = document.querySelectorAll('#AppendArea tr#VariantSectionData');
		var rowsAddon = document.querySelectorAll('#AppendArea tr#AddonSectionData');

		var NoUrut = 0;
		var NoUrutVariant = 0;
		rows.forEach(function(row) {
			var totalInput = row.querySelector('input[id="txtTotal"]');
			var RowKodeItem = row.querySelector('input[id="txtKodeItem"]');
			var RowQty = row.querySelector('input[id="txtQty"]');
			var RowHarga = row.querySelector('input[id="txtHarga"]');
			var RowDiskon = row.querySelector('input[id="txtDiskon"]');

			if (totalInput) {
				var oDisk = parseFloat(RowQty.value) * parseFloat(RowHarga.value) * (parseFloat(RowDiskon.value)/ 100);
				
				var oItem = {
  					'NoUrut' : NoUrut,
					'KodeItem' : RowKodeItem.value,
					'Qty' : RowQty.value,
					'QtyKonversi' : RowQty.value,
					'Satuan' : '',
					'Harga' : RowHarga.value,
					'Discount' : oDisk,
					'HargaNet' : (RowQty.value * RowHarga.value) - oDisk,
					'BaseReff' : 'POS',
					'BaseLine' : -1,
					'KodeGudang' : _Company[0]['GudangPoS'],
					'LineStatus': Status,
					'VatPercent' : 0,
					'HargaPokokPenjualan' : 0,
  				}
  				
  				oDetail.push(oItem)

				NoUrut+=1
			}
		});

		// Variant

		var VariantUrut = 0;
		var AddonUrut = 0;

		rowsVariant.forEach(function(row) {
			var RowQty = row.querySelector('input[id="txtExtraQty"]');
			var RowExtraPrice = row.querySelector('input[id="txtExtraPrice"]');
			var RowVariantGrupID = row.querySelector('input[id="txtGrupVariantID"]');
			var RowVariantID = row.querySelector('input[id="txtVariantID"]');
			var RowNamaItem = row.querySelector('input[id="txtNamaItem"]');
			var RowKodeItem = row.querySelector('input[id="txtKodeItem"]');

			var oItem = {
				'NoUrut'			: VariantUrut,
				'VariantGrupID'		: RowVariantGrupID.value,
				'VariantID'			: RowVariantID.value,
				'AddonMenuID'		: -1,
				'NamaGroupVariant'	: '',
				'NamaVariant'		: RowNamaItem.value,
				'ExtraQty'			: RowQty.value,
				'ExtraPrice'		: RowExtraPrice.value,
				'KodeItem'			: RowKodeItem.value
			}

			oVariant.push(oItem);
			
			VariantUrut += 1;
		});

		rowsAddon.forEach(function(row) {
			var RowQty = row.querySelector('input[id="txtExtraQty"]');
			var RowExtraPrice = row.querySelector('input[id="txtExtraPrice"]');
			var RowMenuAddonID = row.querySelector('input[id="txtMenuAddonID"]');
			var RowNamaItem = row.querySelector('input[id="txtNamaItem"]');
			var RowKodeItem = row.querySelector('input[id="txtKodeItem"]');

			var oItem = {
				'NoUrut'			: AddonUrut,
				'VariantGrupID'		: -1,
				'VariantID'			: -1,
				'AddonMenuID'		: RowMenuAddonID.value,
				'NamaGroupVariant'	: '',
				'NamaVariant'		: RowNamaItem.value,
				'ExtraQty'			: RowQty.value,
				'ExtraPrice'		: RowExtraPrice.value,
				'KodeItem'			: RowKodeItem.value
			}

			oVariant.push(oItem);
			
			VariantUrut += 1;
		});

  		if (_ServicesData.length > 0) {
  			for (var i = 0; i < _ServicesData.length; i++) {
  				var oItem = {
  					'NoUrut' : oDetail.length + 1,
					'KodeItem' : _ServicesData[i]['KodeItem'],
					'Qty' : 1,
					'Satuan' : '',
					'Harga' : _ServicesData[i]['Jumlah'],
					'Discount' : 0,
					'HargaNet' : _ServicesData[i]['Jumlah'],
					'BaseReff' : '',
					'BaseLine' : -1,
					'KodeGudang' : 'UMM',
					'LineStatus': Status,
  				}
  				
  				oDetail.push(oItem)
  			}
  		}

  		// jQuery('#_NoTransaksi').text()
  		var oData = {
			'NoTransaksi' : NoTransaksi,
			'TglTransaksi' : _Tanggal + " " + _Jam,
			'TglJatuhTempo' : _Tanggal,
			'NoReff' : 'POS',
			'KodeSales' : '',
			'KodePelanggan' : jQuery('#KodePelanggan').val(),
			'KodeTermin' : _Company[0]['TerminBayarPoS'],
			'Termin' : 0,
			'TotalTransaksi' : jQuery('#_SubTotal').attr("originalvalue"),
			'Potongan' : parseFloat(jQuery('#_TotalDiskon').attr("originalvalue") || 0) + parseFloat(jQuery('#_VoucherDiscount').attr("originalvalue") || 0),
			'Pajak' : 0,
			'Pembulatan' : jQuery('#_Pembulatan').attr("originalvalue"),
			'TotalPembelian' : jQuery('#_GrandTotal').attr("originalvalue"),
			'TotalRetur' : 0,
			'TotalPembayaran' : (Status) == 'T' ? 0 : jQuery('#JumlahBayar').attr("originalvalue"),
			'Status' : Status,
			'Keterangan' : _VoucherAppliedCode ? 'Voucher: ' + _VoucherAppliedCode : '',
			'MetodeBayar' : _KodeMetodePembayaran,
			'ReffPembayaran' : $('#NomorRefrensiPembayaran').val(),
			'JenisOrder' : _idJenisOrder,
			'KodeMeja' : _KodeMeja,
			'Detail' : oDetail,
			'Variant' : oVariant
		}

		console.log(oVariant);

		// Save Data

		$.ajax({
			async:false,
			url: (NoTransaksi) == "" ? "{{route('fpenjualan-retailPosFnB')}}" : "{{route('fpenjualan-editJsonPosFnB')}}",
			type: 'POST',
			contentType: 'application/json',
			headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
            },
            data: JSON.stringify(oData),
            success: function(response) {
            	if (response.success == true) {
            		if(Status == 'T'){
            			Swal.fire({
	                      icon: "success",
	                      title: "Sukses",
	                      text: "Data Berhasil disimpan",
	                    }).then((result) => {
						  location.reload();
						});
            		}else{
            			let formattedAmount = parseFloat(response.Kembalian).toLocaleString('en-US', {
				            style: 'decimal',
				            minimumFractionDigits: 2,
				            maximumFractionDigits: 2
				        });
	            		Swal.fire({
						  title: "KEMBALIAN "+formattedAmount,
						  text: "Cetak Struk ?",
						  icon: "warning",
						  showCancelButton: true,
						  confirmButtonColor: "#3085d6",
						  cancelButtonColor: "#d33",
						  confirmButtonText: "Cetak",
						  cancelButtonText: "Tidak Cetak"
						}).then((result) => {
						  if (result.isConfirmed) {
						    PrintStruk(response.LastTRX);
						  }
						  else{
						  	location.reload();
						  }
						});
            		}
            	}
            	else{
            		Swal.fire({
                      icon: "error",
                      title: "Opps...",
                      text: response.message,
                    })
                    ButonObject.text(ButtonDefaultText);
  					ButonObject.attr('disabled',false);
            	}
            }
		});

		ButonObject.text(ButtonDefaultText);
  		ButonObject.attr('disabled',false);
	}

	function PaymentGateWay(Status, ButonObject, ButtonDefaultText) {
		UpdateCurrentTime();
		var dataGridInstance = jQuery('#gridContainerDetail').dxDataGrid('instance');
  		var allRowsData  = dataGridInstance.getDataSource().items();

  		var NoTransaksi = "";
  		if (jQuery('#_NoTransaksi').text() != "<OTOMATIS>") {
  			NoTransaksi = jQuery('#_NoTransaksi').text();
  		}
  		// console.log(allRowsData)
  		var oDetail = [];

  		for (var i = 0; i < allRowsData.length; i++) {
  			// Things[i]
  			if (allRowsData[i]['KodeItem'] != "") {
  				// var oItemMaster = GetItemInfo(allRowsData[i]['KodeItem']);
  				var oDisk = 0;

  				if (allRowsData[i]['DiskonPersen'] > 0) {
  					oDisk += (allRowsData[i]['Qty'] * allRowsData[i]['Harga']) * allRowsData[i]['DiskonPersen'] / 100;
  				}

  				if (allRowsData[i]['DiskonRp'] > 0) {
  					oDisk += allRowsData[i]['DiskonRp'];
  				}

  				// console.log(oItemMaster[0].Satuan);

  				var oItem = {
  					'NoUrut' : allRowsData[i]['LineNumber'],
					'KodeItem' : allRowsData[i]['KodeItem'],
					'Qty' : allRowsData[i]['Qty'] * allRowsData[i]['QtyKonversi'],
					'QtyKonversi' : allRowsData[i]['QtyKonversi'],
					'Satuan' : allRowsData[i]['Satuan'],
					'Harga' : allRowsData[i]['Harga'],
					'Discount' : oDisk,
					'HargaNet' : (allRowsData[i]['Qty'] * allRowsData[i]['Total']) - oDisk,
					'BaseReff' : 'POS',
					'BaseLine' : -1,
					'KodeGudang' : _Company[0]['GudangPoS'],
					'LineStatus': Status,
					'VatPercent' : allRowsData[i]['VatPercent'],
					'HargaPokokPenjualan' : allRowsData[i]['HargaPokokPenjualan'],
  				}
  				
  				oDetail.push(oItem)
  			}
  		}

  		if (_ServicesData.length > 0) {
  			for (var i = 0; i < _ServicesData.length; i++) {
  				var oItem = {
  					'NoUrut' : oDetail.length + 1,
					'KodeItem' : _ServicesData[i]['KodeItem'],
					'Qty' : 1,
					'Satuan' : '',
					'Harga' : _ServicesData[i]['Jumlah'],
					'Discount' : 0,
					'HargaNet' : _ServicesData[i]['Jumlah'],
					'BaseReff' : '',
					'BaseLine' : -1,
					'KodeGudang' : 'UMM',
					'LineStatus': Status,
  				}
  				
  				oDetail.push(oItem)
  			}
  		}

  		// jQuery('#_NoTransaksi').text()
  		var oData = {
			'NoTransaksi' : NoTransaksi,
			'TglTransaksi' : _Tanggal + " " + _Jam,
			'TglJatuhTempo' : _Tanggal,
			'NoReff' : 'POS',
			'KodeSales' : jQuery('#KodeSales').val(),
			'KodePelanggan' : jQuery('#KodePelanggan').val(),
			'KodeTermin' : _Company[0]['TerminBayarPoS'],
			'Termin' : 0,
			'TotalTransaksi' : jQuery('#_SubTotal').attr("originalvalue"),
			'Potongan' : jQuery('#_TotalDiskon').attr("originalvalue"),
			'Pajak' : 0,
			'Pembulatan' : jQuery('#_Pembulatan').attr("originalvalue"),
			'TotalPembelian' : jQuery('#_TotalNetBayar').attr("originalvalue"),
			'TotalRetur' : 0,
			'TotalPembayaran' : (Status) == 'T' ? 0 : jQuery('#JumlahBayar').attr("originalvalue"),
			'Status' : Status,
			'Keterangan' : '',
			'MetodeBayar' : _KodeMetodePembayaran,
			'ReffPembayaran' : $('#NomorRefrensiPembayaran').val(),
			'Detail' : oDetail
		}
		
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
			if (data.snap_token) {
				snap.pay(data.snap_token, {
					onSuccess: function(result){
						console.log(result);
						if(result.transaction_status == "cancel"){
							Swal.fire({
								icon: "error",
								title: "Opps...",
								text: "Pembayaran Dibatalkan",
							})
						}
						else{
							// order_id
							$('#NomorRefrensiPembayaran').val(result.order_id)
							SaveData(Status, ButonObject, ButtonDefaultText)
						}
						// Proses pembayaran sukses
					},
					onPending: function(result){
						console.log(result);
						// Pembayaran tertunda
					},
					onError: function(result){
						// console.log(result);
						Swal.fire({
							icon: "error",
							title: "Opps...",
							text: result,
						})
						// Pembayaran gagal
					},
					onClose: function(){
						console.log('customer closed the popup without finishing the payment');
					}
				});
			} else {
				// alert('Error: ' + data.error);
				Swal.fire({
					icon: "error",
					title: "Opps...",
					text: data.error,
				})
			}
		})
		.catch(error => console.error('Error:', error));
	}

	function formatCurrency(input, amount) {
		input.attr("originalvalue", amount);
        let formattedAmount = parseFloat(amount).toLocaleString('en-US', {
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        // Set the formatted value to the input field
        input.val(formattedAmount);
    }

	function CalculateTotal() {
		var rows = document.querySelectorAll('#AppendArea tr'); // Select all rows within the AppendArea
		var grandTotal = 0;

		var rowsItem = document.querySelectorAll('#AppendArea tr#InputSectionData');
		var rowsVariant = document.querySelectorAll('#AppendArea tr#VariantSectionData');
		var rowsAddon = document.querySelectorAll('#AppendArea tr#AddonSectionData');

		var _tempTotalItem = 0;
		var _tempSubTotal = 0;
		var _tempTotalDiskon = 0;
		var _tempTotalTax = 0;
		var _tempTotalServices = 0;
		var _tempGrandTotal = 0;

		rowsItem.forEach(function(row) {
			var totalInput = row.querySelector('input[id="txtTotal"]');
			var RowQty = row.querySelector('input[id="txtQty"]');
			var RowHarga = row.querySelector('input[id="txtHarga"]');
			var RowDiskon = row.querySelector('input[id="txtDiskon"]');

			if (totalInput) {
				var qtyVal = parseFloat(RowQty.value) || 0;
				var hargaVal = parseFloat(RowHarga.value) || 0;
				var diskonPercent = parseFloat(RowDiskon.value) || 0;

				_tempTotalItem += qtyVal;
				_tempSubTotal += parseFloat(totalInput.value) || 0;
				_tempTotalDiskon += qtyVal * hargaVal * (diskonPercent / 100);
			}
		});

		rowsVariant.forEach(function(row) {
			var RowQty = row.querySelector('input[id="txtExtraQty"]');
			var RowExtraPrice = row.querySelector('input[id="txtExtraPrice"]');
			_tempSubTotal += (RowQty.value * RowExtraPrice.value);
		});

		rowsAddon.forEach(function(row) {
			var RowQty = row.querySelector('input[id="txtExtraQty"]');
			var RowExtraPrice = row.querySelector('input[id="txtExtraPrice"]');
			_tempSubTotal += (RowQty.value * RowExtraPrice.value);
		});

	    // Jasa
	    for (var i = 0; i < _ServicesData.length; i++) {
	    	_tempTotalServices += parseFloat(_ServicesData[i]['Jumlah']);
	    }

	    // Calculate Voucher Discount
	    var netSubtotal = _tempSubTotal - _tempTotalDiskon;
	    var _tempVoucherDiscount = 0;
	    if (_VoucherDiscountPercent > 0) {
	    	_tempVoucherDiscount = netSubtotal * (_VoucherDiscountPercent / 100);
	    	if (_VoucherMaximalDiscount > 0 && _tempVoucherDiscount > _VoucherMaximalDiscount) {
	    		_tempVoucherDiscount = _VoucherMaximalDiscount;
	    	}
	    }

	    // Update UI elements
	    formatCurrency($('#_TotalItem'), _tempTotalItem);
	    formatCurrency($('#_SubTotal'), _tempSubTotal);
	    formatCurrency($('#_TotalDiskon'), _tempTotalDiskon);
	    formatCurrency($('#_VoucherDiscount'), _tempVoucherDiscount);
	    formatCurrency($('#_TotalServices'), _tempTotalServices);
		formatCurrency($('#_TotalTax'), _tempTotalTax);

		var grandTotalVal = _tempSubTotal + _tempTotalServices - _tempTotalDiskon - _tempVoucherDiscount + _tempTotalTax;
	    formatCurrency($('#_GrandTotal'), grandTotalVal);

		SetEnableCommand();

		// Real-time synchronization to Customer Display (localStorage 'PoSData')
		var allRowsData = [];
		rowsItem.forEach(function(row) {
			var RowKodeItem = row.querySelector('input[id="txtKodeItem"]').value;
			var RowNamaItem = row.querySelector('input[id="txtNamaItem"]').value;
			var RowQty = parseFloat(row.querySelector('input[id="txtQty"]').value) || 0;
			var RowHarga = parseFloat(row.querySelector('input[id="txtHarga"]').value) || 0;
			var RowDiskon = parseFloat(row.querySelector('input[id="txtDiskon"]').value) || 0;
			var RowTotal = parseFloat(row.querySelector('input[id="txtTotal"]').value) || 0;

			// Get all variants and addons for this specific item code
			var namaTambahan = [];
			var rowsVariantThis = document.querySelectorAll('#AppendArea tr#VariantSectionData.' + RowKodeItem);
			var rowsAddonThis = document.querySelectorAll('#AppendArea tr#AddonSectionData.' + RowKodeItem);
			
			rowsVariantThis.forEach(function(r) {
				var vName = r.querySelector('input[id="txtNamaItem"]').value;
				namaTambahan.push(vName);
			});
			rowsAddonThis.forEach(function(r) {
				var aName = r.querySelector('input[id="txtNamaItem"]').value;
				namaTambahan.push(aName);
			});

			var fullNamaItem = RowNamaItem;
			if (namaTambahan.length > 0) {
				fullNamaItem += ' (' + namaTambahan.join(', ') + ')';
			}

			allRowsData.push({
				KodeItem: RowKodeItem,
				NamaItem: fullNamaItem,
				Qty: RowQty,
				Harga: RowHarga,
				Discount: RowQty * RowHarga * (RowDiskon / 100),
				Total: RowTotal
			});
		});

		var displayObject = {
			data: allRowsData,
			Total: _tempSubTotal + _tempTotalServices,
			Discount: _tempTotalDiskon,
			VoucherDiscount: _tempVoucherDiscount,
			Net: grandTotalVal,
			Tax: _tempTotalTax
		};
		localStorage.setItem('PoSData', JSON.stringify(displayObject));
	}


	function SetEnableCommand() {
    	var ErrorCount = 0;

		// Set Tipe Order
		var xTipeOrderHTML = "";
		// Set Tipe Order
    	

    	// if ($('#JumlahBayar').attr('originalvalue') < $('#_TotalTagihan').val()) {
    	// 	ErrorCount +=1;
    	// }

		// console.log(_idJenisOrder +" > " + _DineIn + " > " + _KodeMeja);
		if (_idJenisOrder == -1) {
			ErrorCount +=1;
		}

		if (_DineIn == 'Y' && _KodeMeja == "" ) {
			ErrorCount +1;
		}

		if ($('#KodePelanggan').val() == "") {
			ErrorCount +1;
		}

    	if (ErrorCount >0) {
			var xBayarError = 0;
			if ($('#JumlahBayar').attr('originalvalue') == 0) {
				// $('#btSimpanPembayaran').attr('disabled',true);
				xBayarError +=1 ;
			}

			if (_KodeMetodePembayaran == -1) {
				// $('#btSimpanPembayaran').attr('disabled',true);
				xBayarError +=1
			}

			if (xBayarError > 0) {
				$('#btSimpanPembayaran').attr('disabled',true);
			}
			else{
				$('#btSimpanPembayaran').attr('disabled',false);
			}
    		
			// $('#btBayar').attr('disabled',true);
			$('#btDraft').attr('disabled',true);
    	}
    	else{
    		$('#btSimpanPembayaran').attr('disabled',false);
			$('#btDraft').attr('disabled',false);
			// $('#btBayar').attr('disabled',false);
    	}

    }
    function editDraft(NoTransaksi) {
    	jQuery('#_NoTransaksi').text(NoTransaksi)
    	// var dataGridInstance = jQuery('#gridContainerDetail').dxDataGrid('instance');
        // var dataSource = dataGridInstance.getDataSource();
        // dataGridInstance.option("dataSource", []);
    	// Load Header
    	$.ajax({
			async:false,
			url: "{{route('fpenjualan-findheader')}}",
			type: 'POST',
			headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
            },
            data: {
            	'NoTransaksi':NoTransaksi
           	},
            success: function(response) {
            	if (response.data.length > 0) {
            		jQuery('#KodePelanggan').val(response.data[0]['KodePelanggan']).trigger('change');
            		jQuery('#KodeSales').val(response.data[0]['KodeSales']).trigger('change');
					// Jenis Order
					_idJenisOrder = response.data[0]['TipeOrder']
					_JenisOrder = response.data[0]['NamaJenisOrder']
					_DineIn = response.data[0]['DineIn']
					
					// _KodeMeja = response.data[0]['DineIn']
					_NamaMeja = response.data[0]['NomorMeja']

					var xHTML = "";

					if (_idJenisOrder > -1) {
						xHTML += '<center>';
						xHTML += '	<p class="white">Tipe Order</p>';
						xHTML += '	<h4 class="mb-0 white">'+ _JenisOrder +'</h4>';
						xHTML += '</center>';
					}

					if (_DineIn == "Y") {
						$("#btNomorMeja").css("pointer-events", "auto");
					}
					else{
						$("#btNomorMeja").css("pointer-events", "none");
					}

					// console.log(xHTML);

					jQuery('#txtTipeOrder').html(xHTML);

					xHTML = "";
					// Meja
					if (_idJenisOrder > -1) {
						xHTML += '<center>';
						xHTML += '	<p class="white">Nomor Meja</p>';
						xHTML += '	<h4 class="mb-0 white">'+ _NamaMeja +'</h4>';
						xHTML += '</center>';
					}
					jQuery('#txtNomorMeja').html(xHTML);
					// SetEnableCommand();
            	}
            }
		});

		// Load Detail
		$.ajax({
			async:false,
			url: "{{route('fpenjualan-readdetail')}}",
			type: 'POST',
			headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
            },
            data: {
            	'NoTransaksi':NoTransaksi
           	},
            success: function(response) {
            	// console.log(response)
            	

            	var xLine = 0;
            	$.each(response.data,function (k,v) {
					AddNewRow(v.KodeItem);
					if (v.Variant.length > 0) {
						AddAddonVariantMenu(v.KodeItem, v.Variant);	
					}
					if (v.Addon.length > 0) {
						AddAddonMenu(v.KodeItem, [], v.Addon)
					}
					
            		// var item = {
	        		// 	'LineNumber' 	: xLine,
	        		// 	'KodeItem' 	 	: v.KodeItem,
	        		// 	'NamaItem'	 	: v.NamaItem,
	        		// 	'Qty'	 	 	: v.Qty,
	        		// 	'QtyKonversi'	: v.QtyKonversi,
	        		// 	'Satuan'		: v.Satuan,
	        		// 	'Harga' 	 	: v.Harga,
	        		// 	'DiskonPersen' 	: 0,
	        		// 	'DiskonRp' 	 	: 0,
	        		// 	'Total' 	 	: 0
	        		// }

	        		// dataSource.store().insert(item).then(function() {
				    //     dataSource.reload();
				    // })
				    // xLine +=1;
            	});
            	AsignRowNumber();
				FirstRowHandling();
				CalculateTotal();
				SetEnableCommand();

            	jQuery('#folderpop').modal('hide');
            }
		});
    }

    function editDataTransaksi(NoTransaksi, Status) {
    	$.ajax({
			async:false,
			url: "{{route('fpenjualan-editStatus')}}",
			type: 'POST',
			headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include the CSRF token in the headers
            },
            data: {
            	'NoTransaksi':NoTransaksi,
            	'Status' : Status
           	},
            success: function(response) {
            	if (response.success == true) {
            		Swal.fire({
                      icon: "success",
                      title: "Horray..",
                      text: "Data Berhasil Dihapus",
                    }).then((result) => {
					  location.reload();
					});
            	}
            	else{
            		Swal.fire({
                      icon: "error",
                      title: "wooopss..",
                      text: response.message,
                    });
            	}
            }
		});
    }

    function deleteDraft(NoTransaksi) {
    	jQuery('#_NoTransaksi').text(NoTransaksi)
    	// editDraft(NoTransaksi);
    	Swal.fire({
		  title: "Hapus Data Draff Penjualan",
		  text: "Hapus Draft penjualan ini ?",
		  icon: "warning",
		  showCancelButton: true,
		  confirmButtonColor: "#3085d6",
		  cancelButtonColor: "#d33",
		  confirmButtonText: "Hapus",
		  cancelButtonText: "Jangan Hapus"
		}).then((result) => {
		  if (result.isConfirmed) {
		    editDataTransaksi(NoTransaksi, 'D')
		  }
		  else{
		  	location.reload();
		  }
		});
    }


    
</script>