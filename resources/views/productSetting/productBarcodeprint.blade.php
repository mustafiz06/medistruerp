<!DOCTYPE html>
<html>

<head>
    <title>Print Barcode</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: #fff;
        }

        @page {
            size: 2in 1.5in;
            margin: 0;
        }

        #label {
            width: 2in;
            height: 1.5in;
            padding: 6px;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        .company {
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .barcode {
            text-align: center;
            margin-top: 2px;
        }

        .barcode svg {
            width: 100%;
            height: 45px;
        }

        .sku {
            width: 100%;
            text-align: center;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 3px;
            margin-top: 2px;
        }

        .name {
            text-align: center;
            font-size: 9px;
            font-weight: 600;
            margin-top: 2px;
            line-height: 1.1;
            height: 22px;
            overflow: hidden;
        }

        .price {
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            margin-top: 3px;
        }
    </style>
</head>

<body onload="printAndClose()">

    <div id="label">

        <div class="barcode">
            {!! $barcodeSVG !!}
        </div>

        <div class="sku">
            {{ $formattedSku }}
        </div>

        <div class="name">
            {{ strtoupper($product->name) }}
        </div>

    </div>

    <script>
        function printAndClose() {
            window.print();
            setTimeout(function() {
                window.close();
            }, 500);
        }
    </script>

</body>

</html>