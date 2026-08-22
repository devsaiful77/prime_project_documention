<tr id="uniqMaterialId<?php echo $appendCount;?>">
  {!! Form::hidden('sale_children['.$appendCount.'][product_id]',$productData['id'] ); !!}
	<td class="text-center vcenter">{{$productData['product_code']}}</td>
  <td class="text-center vcenter">{{$productData['name']}} ({{(!empty($productData['product_attribute']['name'])) ? $productData['product_attribute']['name'] : '-'}})</td>
	<td class="text-center vcenter">
      {!!
          Form::text('sale_children['.$appendCount.'][batch_no]',(!empty($batch_no)) ? $batch_no : ""  ,[
            'class' => 'form-control',
            'label'=>false,
            'readonly'=>true,
            'autocomplete'=>'off',
            'readonly'=>'true',
            'type'=>'text',
            'placeholder'=>'Batch No'
          ]);
      !!} 
  </td>
  <td class="text-center vcenter">
      {!!
          Form::text('sale_children['.$appendCount.'][price]',(!empty($productData["sale_price"])) ? $productData["sale_price"] : ""  ,[
            'class' => 'form-control saleCommonClass',
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
          Form::text('sale_children['.$appendCount.'][quantity]',(!empty($dataForView["quantity"])) ? $dataForView["quantity"] : 0,[
            'class' => 'form-control saleCommonClass',
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
          Form::text('sale_children['.$appendCount.'][vat]',(!empty($dataForView["vat"])) ? $dataForView["vat"] : 0  ,[
            'class' => 'form-control saleCommonClass',
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
      <?php 
        $totalAmountCalculation = 0;
        $curProductPrice = (!empty($productData["sale_price"])) ? $productData["sale_price"] : 0;

        // $curProductPrice = (!empty($dataForView["price"])) ? $dataForView["price"] : 0;
        $curProdVat = (!empty($dataForView["vat"])) ? $dataForView["vat"] : 0;
        $curProdQty = (!empty($dataForView["quantity"])) ? $dataForView["quantity"] : 0;
        $vatOnCurrentProduct = (($curProdVat * $curProductPrice) / 100) * $curProdQty; // Calculating Vat with quantity

        $vatOnCurrentProduct  = (float)$vatOnCurrentProduct;
        $totalProductAmount   = $curProductPrice * $curProdQty;
        $totalProductAmount   = (float)$totalProductAmount;
        $netAmountWithVat     = number_format($totalProductAmount + $vatOnCurrentProduct,2,'.','');


      ?>
      {!!
          Form::text('sale_children['.$appendCount.'][amount]',(!empty($dataForView["amount"])) ? $dataForView["amount"] : $netAmountWithVat  ,[
            'class' => 'form-control productAmountClass',
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
  <td><button type="button" class="btn btn-danger gradient removeAddMoreProduct" data-product-id="{{$productData['id']}}" data-batch-no="{{$batch_no}}"><i class="fa fa-minus"></i></button></td> 
</tr>   
