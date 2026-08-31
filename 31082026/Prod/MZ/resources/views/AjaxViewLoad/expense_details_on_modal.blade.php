<div class="table-responsive">
  <table class="table table-bordered table-success">
    <colgroup>
      <col width="40%">
      <col width="35%">
      <col width="25%">
    </colgroup>
    <thead>
        <tr>
          <th class="vcenter text-center">Expense Type</th>
          <th class="vcenter text-center">Name</th>
          <th class="vcenter text-center">Amount</th>
      
        </tr>
    </thead>
    <tbody style="word-break: break-all;">
      @IF(!empty($allExpenseData))
        @FOREACH($allExpenseData as $key=>$data)
          <tr class="{{($key % 2 == 0) ? 'warning' : ''}}">
            <td class="text-center vcenter"> 
              @IF($data['expense_type'] == 'f')
                {{'Fixed Cost'}}
              @ELSEIF($data['expense_type'] == 'e')
                {{'Employee Salary'}}
              @ELSEIF($data['expense_type'] == 'o')
                {{'Other'}}
              @ENDIF  
            </td>
            <td class="text-center vcenter"> 
              @IF($data['expense_type'] == 'f')
                {!! $data['fixed_expense']['name'] !!}
              @ELSEIF($data['expense_type'] == 'e')
                {!! $data['employee']['name'].'-'.$data['employee']['designation'] !!}
              @ELSEIF($data['expense_type'] == 'o')
                {!! $data['other_expense'] !!}
              @ENDIF  
            </td>
            <td class="text-right vcenter">{!! number_format($data['amount'], 2) !!}</td>
          </tr>
        @ENDFOREACH
          <tr>
            <td class="text-right vcenter" colspan="2"><strong>Total:</strong></td>
            <td class="text-right vcenter"><strong>{!! number_format($total_amount, 2) !!}</strong></td>
          </tr>
      @ELSE
        <tr><tr class="text-center error" colspan="3">No Data Found !!!</tr></tr>
      @ENDIF
    </tbody>
  </table>
</div><!-- Product Details -->
