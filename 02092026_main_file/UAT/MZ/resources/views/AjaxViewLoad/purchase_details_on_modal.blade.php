<div class="table-responsive">
  <h4 class="h4"> Product Details</h4>
  <div class="ln_solid">&nbsp;</div>
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Sl</th>
        <th>Product Name</th>
        <th>Batch No</th>
        <th>Quantity</th>
        <th>Price</th>
        <th>Vat</th>
        <th>Amount</th>
      </tr>
    </thead>
    <tbody style="word-break: break-all;">
      @IF(!empty($chldData))
        @FOREACH($chldData as $key=>$data)
          <tr class="{{($key % 2 == 0) ? 'warning' : ''}}">
            <td class="text-center vcenter">{!! $key+1 !!}</td>

            <td class="text-center vcenter">{!! (!empty($allProductData[$data['product_id']])) ? $allProductData[$data['product_id']] : '-' !!}</td>
            <td class="text-center vcenter">{!! $data['batch_no'] !!}</td>
            <td class="text-center vcenter">{!! $data['quantity'] !!}</td>
            <td class="text-right vcenter">{!! number_format($data['price'], 2) !!}</td>
            <td class="text-center vcenter">{!! number_format($data['vat'], 2) !!} %</td>
            <td class="text-right vcenter">{!! number_format($data['amount'], 2) !!}</td>
          </tr>
        @ENDFOREACH
          <tr>
            <td class="text-right vcenter" colspan="6"><strong>Total:</strong></td>
            <td class="text-right vcenter"><strong>{!! number_format($total_amount, 2) !!}</strong></td>
          </tr>
      @ELSE
        <tr><th class="text-center error" colspan="7">No Data Found !!!</th></tr>
      @ENDIF
    </tbody>
  </table>
</div><!-- Product Details -->
<div class="table-responsive">
  <h4 class="h4"> Payment Details</h4>
  <div class="ln_solid">&nbsp;</div>
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Sl</th>
        <th>Paid Date</th>        
        <th>Paid Amount</th>
      </tr>
    </thead>
    <tbody style="word-break: break-all;">
      @IF(!empty($paymentData))
        <?php $totalPaid = 0; ?>
        @FOREACH($paymentData as $key=>$data)
          <?php
          $totalPaid += $data['amount'];
          ?>
          <tr class="{{($key % 2 == 0) ? 'success' : ''}}">
            <td class="text-center vcenter">{!! $key+1 !!}</td>
            <td class="vcenter text-center"> {{ Carbon\Carbon::parse($data['paid_date'])->format('d-M-Y') }} </td>
            <td class="text-right vcenter">{!! number_format($data['amount'],2) !!}</td>
          </tr>
        @ENDFOREACH
          <tr>
            <td class="text-right vcenter" colspan="2"><strong>Total Paid:<br/>Total Amount:</strong></td>
            <td class="text-right vcenter"><strong>{!! number_format($totalPaid, 2) !!}<br/>{!! number_format($total_amount, 2) !!}</strong></td>
          </tr>
      @ELSE
        <tr><th class="text-center error" colspan="7">No Data Found !!!</th></tr>
      @ENDIF
    </tbody>
  </table>
</div><!-- Payment Details -->
