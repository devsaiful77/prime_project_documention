<tr id="uniqMaterialId<?php echo $appendCount;?>">
  {!! Form::hidden('purchase_children['.$appendCount.'][product_id]',$productData['id'] ); !!}
	<td class="text-center vcenter">{{$productData['product_code']}}</td>
  <td class="text-center vcenter">{{$productData['name']}} ({{(!empty($productData['product_category']['name'])) ? $productData['product_category']['name'] : '-'}})</td>
	<td class="text-center vcenter">
      {!!
          Form::text('purchase_children['.$appendCount.'][batch_no]',(!empty($batch_no)) ? $batch_no : ""  ,[
            'class' => 'form-control',
            'label'=>false,
            'autocomplete'=>'off',
            'type'=>'text',
            'placeholder'=>'Batch No'
          ]);
      !!} 
  </td>
  <td class="text-center vcenter">     
      {!!
          Form::text('purchase_children['.$appendCount.'][price]',(!empty($productData["purchase_price"])) ? $productData["purchase_price"] : ""  ,[
            'class' => 'form-control text-right purchaseCommonClass',
            'id'=>'uniqProdPrice'.$appendCount,
            'uniqAttr'=>$appendCount,
            'label'=>false,
            'autocomplete'=>'off',
            'type'=>'text',
            'placeholder'=>'Price',
           
          ]);
      !!}
  </td>
	<td class="text-center vcenter">
      {!!
          Form::text('purchase_children['.$appendCount.'][quantity]',1,[
            'class' => 'form-control purchaseCommonClass',
            'id'=>'uniqProdQty'.$appendCount,
            'uniqAttr'=>$appendCount,
            'label'=>false,
            'autocomplete'=>'off',
            'type'=>'text',
            'placeholder'=>'Quantity'
          ]);
      !!}
  </td>
  <td class="text-center vcenter">
      {!!
          Form::text('purchase_children['.$appendCount.'][vat]',(!empty($dataForView["vat"])) ? $dataForView["vat"] : 0  ,[
            'class' => 'form-control purchaseCommonClass',
            'id'=>'uniqProdVat'.$appendCount,
            'uniqAttr'=>$appendCount,
            'label'=>false,
            'autocomplete'=>'off',
            'type'=>'text',
            'placeholder'=>'Vat',
           
          ]);
      !!}
  </td>
  <td class="text-center vcenter">
      {!!
          Form::text('purchase_children['.$appendCount.'][amount]',(!empty($productData["purchase_price"])) ? number_format($productData["purchase_price"],2, '.', '') : ""  ,[
            'class' => 'form-control text-right productAmountClass',
            'id'=>'uniqProdAmount'.$appendCount,
            'uniqAttr'=>$appendCount,
            'readonly'=>'true',
            'label'=>false,
            'autocomplete'=>'off',
            'type'=>'text',
            'placeholder'=>'Amount',
           
          ]);
      !!}
  </td>
  <td class="text-center vcenter"><button type="button" class="btn btn-danger gradient removeAddMoreProduct"><i class="fa fa-minus"></i></button></td> 
</tr>   
