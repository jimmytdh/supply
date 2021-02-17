<!DOCTYPE html>
<html>
<head>
    <title>Printer Report: {{ $title }}</title>
    <style>
        h1,h2,h3,h4,h5 {
            padding: 0;
            margin: 0;
        }
        .header { text-align: center; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .mt { margin-top:10px;}
        .mb { margin-bottom:10px;}
        .mt-lg { margin-top: 30px; }
        .mb-lg { margin-top: 30px; }
        .table td,.table th {
            border: 1px solid #000;
            padding: 2px 3px;
        }
        table th {
            text-align: center;
        }
        .code {
            border: 1px solid;
            text-align: center;
            width: 180px;
            padding: 10px;
            float: right;
        }
    </style>
</head>
<body>
<div class="header">
    <h2>TALISAY DISTRICT HOSPITAL</h2>
    <h5>San Isidro, Talisay City, Cebu</h5>
    <br>
    <h3>PO & IAR COPIES FOR COA</h3>
</div>

<div class="text-right mt mb">
    Report No.: ________
</div>
<table class="table" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <th width="4%">#</th>
        <th width="19%">PO NO.</th>
        <th width="19%">PO DATE</th>
        <th width="29%">SUPPLIER</th>
        <th width="29%">TOTAL P.O. AMOUNT</th>
    </tr>
    <?php
        $c =1;
        $sum = 0;
    ?>
    @foreach($data as $row)
    <?php
        $supplier = null;
        $sup = \App\Models\Supplier::find($row->supplier_id);
        if($sup)
            $supplier = $sup->company;

        $sum += $row->total_amount;
    ?>
    <tr>
        <td class="text-center">{{ $c++ }}</td>
        <td>{{ $row->po_no }}</td>
        <td>{{ date('M d, Y') }}</td>
        <td>{{ $supplier }}</td>
        <td class="text-right">{{ number_format($row->total_amount,2)}}</td>
    </tr>
    @endforeach

    @if(count($data)==0)
        <tr>
            <td colspan="5" class="text-center" style="padding:30px 0">--</td>
        </tr>
    @endif
    <tr>
        <th colspan="4" class="text-right">TOTAL</th>
        <th class="text-right">{{ number_format($sum,2)}}</th>
    </tr>
</table>
<div class="mt" style="margin-left: 30px;">
    No. of PO's: <span style="text-decoration: underline; font-weight:bold;">{{ count($data) }}</span>
    <div class="mb">&nbsp;</div>
    Prepared By:
    <div class="mb-lg">&nbsp;</div>
    <table width="60%">
        <tr>
            <td style="border-bottom: 2px solid #000;"></td>
        </tr>
        <tr>
            <td class="text-center">(Signature over printed name/ Date)</td>
        </tr>
    </table>
    <div class="mb">&nbsp;</div>
    <div class="mb">&nbsp;</div>
    <div class="mt">
        Received By:
        <div class="mb-lg">&nbsp;</div>
        <table width="60%">
            <tr>
                <td style="border-bottom: 2px solid #000;"></td>
            </tr>
            <tr>
                <td class="text-center">(Signature over printed name/ Date)</td>
            </tr>
        </table>
    </div>
</div>
<div class="code mt-lg">
    HOPSS-PSS-FM-03 Rev. 0<br>
    01-APRIL-2018
</div>
</body>
</html>
