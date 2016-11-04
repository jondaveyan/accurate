<?php $this->load->view('header'); ?>
	<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Modal Header</h4>
				</div>
				<div class="modal-body">
					<p>Some text in the modal.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>

		</div>
	</div>
<div id="container">
	<a href="welcome"><button class="btn btn-default">back</button></a><br><br>
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
					echo '<th data-client_id="'.$client_ids[$key].'" data-toggle="modal" data-target="#myModal" class="clickable client_info">'.$client.'</th>';
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
								echo '<td data-client_id="'.$client_ids[$k].'" data-product_id="'.$product->id.'"data-toggle="modal" data-target="#myModal" class="clickable product_client_info">'.$res[$product->name][$client].'</td>';
							}
							else
							{
								echo '<td data-client_id="'.$client_ids[$k].'" data-product_id="'.$product->id.'"data-toggle="modal" data-target="#myModal" class="clickable product_client_info"></td>';
							}
						}
					}
					else
					{
						foreach($clients as $k => $client)
						{
							echo '<td data-client_id="'.$client_ids[$k].'" data-product_id="'.$product->id.'"data-toggle="modal" data-target="#myModal" class="clickable product_client_info"></td>';
						}
					}
					echo '</tr>';
				}
			?>
		</table>
	</div>

</div>
<?php $this->load->view('footer'); ?>