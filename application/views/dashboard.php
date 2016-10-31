<?php $this->load->view('header'); ?>
<div class="popup" id="product_client_popup">

</div>
<div id="container">
	<h1>Accurate</h1>

	<div id="body">
		<a href="<?php echo site_url('products'); ?>"><button>Products</button></a>
		<a href="<?php echo site_url('clients'); ?>"><button>Clients</button></a>
		<a href="<?php echo site_url('orders'); ?>"><button>Orders</button></a>
	</div>
	<br>
	<div id="products_table">
		Products:
		<table class="table">
			<tr>
				<th>Product name</th>
				<th>Quantity</th>
				<th>For use</th>
				<th>Daily ordered</th>
				<?php
				foreach($clients as $key => $client)
				{
					echo '<th data-client_id="'.$client_ids[$key].'" class="clickable">'.$client.'</th>';
				}
				echo '</tr>';
				foreach($products as $key => $product)
				{
					echo '<tr><td>'.$product->name.'</td>';
					echo '<td>'.$product->quantity.'</td>';
					echo '<td>'.($product->quantity-$product->daily_order).'</td>';
					echo '<td class="daily_order" data-id="'.$product->id.'" class="clickable">'.$product->daily_order.'</td>';
					if(isset($res[$product->name]))
					{
						foreach($clients as $k => $client)
						{
							if(isset($res[$product->name][$client]))
							{
								echo '<td data-client_id="'.$client_ids[$k].'" data-product_id="'.$product->id.'" class="clickable">'.$res[$product->name][$client].'</td>';
							}
							else
							{
								echo '<td data-client_id="'.$client_ids[$k].'" data-product_id="'.$product->id.'" class="clickable"></td>';
							}
						}
					}
					else
					{
						foreach($clients as $k => $client)
						{
							echo '<td data-client_id="'.$client_ids[$k].'" data-product_id="'.$product->id.'" class="clickable"></td>';
						}
					}
					echo '</tr>';
				}
			?>
		</table>
	</div>

</div>
<?php $this->load->view('footer'); ?>