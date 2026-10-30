<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>

<body>
    @dd($result);
    <div class="invoice-container">
        <div class="header">
            <div class="logo">LOGO HERE</div>
            <div class="invoice-title">INVOICE</div>
        </div>
        <div class="invoice-details">
            <div>
                <p><strong>Invoice #:</strong> 000xxx</p>
                <p><strong>Date:</strong> 01/02/2025</p>
            </div>
        </div>
        <div class="address-container">
            <div class="address-box">
                <p><strong>Billing Address:</strong></p>
                <p>Name Here</p>
                <p>25 Dummy Street Area, Location, Country</p>
            </div>
            <div class="address-box">
                <p><strong>Shipping Address:</strong></p>
                <p>Name Here</p>
                <p>30 Example Street, Another City, Country</p>
            </div>
        </div>
        <table class="table">
            <tr>
                <th>SL.</th>
                <th>Item Description</th>
                <th>Price</th>
                <th>Qty.</th>
                <th>Total</th>
            </tr>
            <tr>
                <td>1</td>
                <td>Lorem Ipsum is simply dummy text</td>
                <td>$00.00</td>
                <td>1</td>
                <td>$00.00</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Lorem Ipsum is simply dummy text</td>
                <td>$00.00</td>
                <td>2</td>
                <td>$00.00</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Lorem Ipsum is simply dummy text</td>
                <td>$00.00</td>
                <td>1</td>
                <td>$00.00</td>
            </tr>
        </table>
        <div class="totals">
            <p><strong>Sub Total:</strong> $00.00</p>
            <p><strong>Tax:</strong> 0.00%</p>
            <p><strong>Total:</strong> <span style="color: #f60; font-size: 18px;">$00.00</span></p>
        </div>
        <div class="footer">Thank You For Your Business!</div>
    </div>
</body>

</html>
