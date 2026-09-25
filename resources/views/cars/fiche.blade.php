<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fiche Technique — {{ $car->carModel->brand->name }} {{ $car->carModel->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Montserrat', sans-serif;
            background: #fff;
            color: #1a1a1a;
            padding: 25mm 20mm;
            font-size: 11pt;
            line-height: 1.5;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid #B8860B;
            padding-bottom: 18px;
            margin-bottom: 25px;
        }
        .logo { font-size: 26pt; font-weight: 900; color: #111; }
        .logo span { color: #B8860B; }
        .header-right { text-align: right; font-size: 10pt; color: #666; }
        .header-right .ref { font-weight: 700; color: #111; font-size: 11pt; }

        .brand-tag {
            display: inline-block;
            background: #B8860B;
            color: #fff;
            font-size: 9pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 3px;
            padding: 4px 14px;
            border-radius: 4px;
            margin-bottom: 6px;
        }

        h1 { font-size: 22pt; font-weight: 900; margin-bottom: 4px; color: #111; }
        .subtitle { font-size: 11pt; color: #888; margin-bottom: 12px; }

        .price-box {
            display: inline-block;
            background: #FFF8E7;
            border: 2px solid #B8860B;
            border-radius: 8px;
            padding: 8px 20px;
            margin-bottom: 25px;
        }
        .price-box .amount { font-size: 20pt; font-weight: 900; color: #B8860B; }
        .price-box .label { font-size: 8pt; color: #888; text-transform: uppercase; letter-spacing: 2px; }

        .car-image {
            width: 100%;
            max-height: 280px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #eee;
            margin-bottom: 25px;
        }

        h2 {
            font-size: 13pt;
            font-weight: 800;
            color: #111;
            border-bottom: 2px solid #B8860B;
            padding-bottom: 6px;
            margin: 25px 0 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table th, table td {
            padding: 9px 14px;
            border: 1px solid #e0e0e0;
            font-size: 10.5pt;
        }
        table th {
            background: #f9f9f9;
            font-weight: 700;
            width: 38%;
            color: #333;
        }
        table td { color: #111; }

        .description {
            background: #fafafa;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 14px 18px;
            font-size: 10pt;
            line-height: 1.7;
            color: #444;
            margin-bottom: 15px;
        }

        .options-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .option-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 12px;
            border: 1px solid #eee;
            border-radius: 6px;
            font-size: 10pt;
        }
        .option-item .name { font-weight: 600; }
        .option-item .opt-price { color: #B8860B; font-weight: 700; }

        .footer {
            margin-top: 35px;
            border-top: 2px solid #eee;
            padding-top: 15px;
            display: flex;
            justify-content: space-between;
            font-size: 8.5pt;
            color: #999;
        }
        .footer-left { font-weight: 600; }
        .footer-right { text-align: right; }

        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 9pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .status-available { background: #dcfce7; color: #166534; }
        .status-reserved { background: #fef9c3; color: #854d0e; }
        .status-sold { background: #fee2e2; color: #991b1b; }

        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 0 30px; }

        @media print {
            body { padding: 15mm; }
            .no-print { display: none !important; }
        }

        .print-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #B8860B;
            color: #fff;
            border: none;
            padding: 14px 28px;
            border-radius: 12px;
            font-size: 12pt;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(184,134,11,0.4);
            display: flex;
            align-items: center;
            gap: 8px;
            z-index: 100;
            transition: all 0.2s;
            font-family: 'Montserrat', sans-serif;
        }
        .print-btn:hover { background: #D4AF37; transform: translateY(-2px); }
        .print-btn svg { width: 20px; height: 20px; }
    </style>
</head>
<body>

    <div class="header">
        <div>
            <div class="logo">APEX<span> CAR</span></div>
            <div style="font-size:9pt; color:#999; margin-top:4px;">www.apexcar.ma &bull; +212 766 153 755 &bull; Oujda, Maroc</div>
        </div>
        <div class="header-right">
            <div class="ref">REF: AXC-{{ str_pad($car->id, 5, '0', STR_PAD_LEFT) }}</div>
            <div>{{ __('car_detail.date') ?? 'Date' }}: {{ now()->format('d/m/Y') }}</div>
        </div>
    </div>

    @if($car->images && count($car->images) > 0)
        <img src="{{ asset('storage/' . $car->images[0]) }}" alt="{{ $car->carModel->name }}" class="car-image">
    @endif

    <div class="brand-tag">{{ $car->carModel->brand->name }}</div>
    <h1>{{ $car->carModel->name }} {{ $car->year }}</h1>
    <div class="subtitle">{{ $car->carModel->type ?? '' }} &bull; {{ $car->year }}</div>

    <div class="price-box">
        <div class="label">{{ __('cars.price') }}</div>
        <div class="amount">{{ number_format($car->price) }} DH</div>
    </div>

    <div style="margin-bottom:5px;">
        <span class="status-badge status-{{ $car->status }}">
            {{ $car->status === 'available' ? __('cars.available') : ($car->status === 'reserved' ? __('cars.reserved') : __('cars.sold')) }}
        </span>
    </div>

    <h2>{{ __('car_detail.specs') }}</h2>
    <div class="two-col">
        <table>
            <tr><th>{{ __('car_detail.brand') }}</th><td>{{ $car->carModel->brand->name }}</td></tr>
            <tr><th>{{ __('car_detail.model') }}</th><td>{{ $car->carModel->name }}</td></tr>
            <tr><th>{{ __('car_detail.year') }}</th><td>{{ $car->year }}</td></tr>
            <tr><th>{{ __('car_detail.condition') }}</th><td>{{ $car->condition === 'neuf' ? __('cars.new') : __('cars.used') }}</td></tr>
        </table>
        <table>
            <tr><th>{{ __('cars.fuel') }}</th><td>{{ $car->fuel_type }}</td></tr>
            <tr><th>{{ __('cars.transmission') }}</th><td>{{ $car->transmission }}</td></tr>
            <tr><th>{{ __('cars.mileage') }}</th><td>{{ number_format($car->mileage) }} km</td></tr>
            <tr><th>{{ __('car_detail.color') }}</th><td>{{ $car->color ?? '—' }}</td></tr>
        </table>
    </div>

    @if($car->description)
        <h2>{{ __('car_detail.description') }}</h2>
        <div class="description">{{ $car->description }}</div>
    @endif

    @if($car->options && $car->options->count())
        <h2>{{ __('car_detail.specs') }} — Extras</h2>
        <div class="options-grid">
            @foreach($car->options as $option)
                <div class="option-item">
                    <span class="name">{{ $option->name }}</span>
                    <span class="opt-price">+{{ number_format($option->price) }} DH</span>
                </div>
            @endforeach
        </div>
    @endif

    <div class="footer">
        <div class="footer-left">
            <strong>APEX CAR</strong> — {{ __('footer.address_value') }}<br>
            {{ __('footer.phone') }}: +212 766 153 755 &bull; {{ __('footer.email') }}: contact@apexcar.ma
        </div>
        <div class="footer-right">
            {{ __('car_detail.date') }}: {{ now()->format('d/m/Y H:i') }}<br>
            {{ __('car_detail.ref') }}: AXC-{{ str_pad($car->id, 5, '0', STR_PAD_LEFT) }}
        </div>
    </div>

    <button class="print-btn no-print" onclick="window.print()">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        {{ __('car_detail.print_sheet') }}
    </button>

</body>
</html>
