<?php
/*
$currentShowing = $this->Paginator->params()['current'];
$totalRecord = $this->Paginator->params()['count'];

<div class="paginator">
    <ul class="pagination">
    	
    	<li class="active"><a><b>Show <?= $currentShowing ?> Items out of <?= $totalRecord ?></b></a></li>
        <?= $this->Paginator->first('◀◀ ' . __('First')) ?>
        <?= $this->Paginator->prev('◀ ' . __('Previous')) ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next(__('Next') . ' ▶') ?>
        <?= $this->Paginator->last(__('Last') . ' ▶▶') ?>
    	
    	<li class="active"><a><b>Page <?= $this->Paginator->counter() ?></b></a></li>
    </ul>
</div>

*/
?>
<div class="paginator">
    <ul class="pagination">
    	
    	<li class="active"><a><b>Show {{ $currentShowing }}  Items out of {{ $totalRecord }} </b></a></li>
                
                <li class="prev disabled"><a href="" onclick="return false;">◀ Previous</a></li>

                <li class="active"><a href="">1</a></li>
                <li><a href="/zpaims/products?page=2&amp;sort=Products.id&amp;direction=desc">2</a></li>
                <li><a href="/zpaims/products?page=3&amp;sort=Products.id&amp;direction=desc">3</a></li>
                <li><a href="/zpaims/products?page=4&amp;sort=Products.id&amp;direction=desc">4</a></li>
                <li><a href="/zpaims/products?page=5&amp;sort=Products.id&amp;direction=desc">5</a></li>
                <li><a href="/zpaims/products?page=6&amp;sort=Products.id&amp;direction=desc">6</a></li>
                <li><a href="/zpaims/products?page=7&amp;sort=Products.id&amp;direction=desc">7</a></li>
                <li><a href="/zpaims/products?page=8&amp;sort=Products.id&amp;direction=desc">8</a></li>
                <li><a href="/zpaims/products?page=9&amp;sort=Products.id&amp;direction=desc">9</a></li>        
                <li class="next">
                	<a rel="next" href="/zpaims/products?page=2&amp;sort=Products.id&amp;direction=desc">Next ▶</a>
                </li>        
                <li class="last">
                	<a href="/zpaims/products?page=25&amp;sort=Products.id&amp;direction=desc">Last ▶▶</a>
                </li>    	
                <li class="active"><a><b>Page 1 of 25</b></a></li>
    </ul>
</div>