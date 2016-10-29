<?php $this->load->view('header'); ?>
<div class="popup" id="new_order_popup">
    <form id="new_order_form" action="orders/new_order" method="post">
        Select Client:<br>
        <select id="client_to_pick" name="client_to_pick"><br>
            <?php
            foreach($clients as $client)
            {
                echo '<option value="'.$client->id.'">'.$client->name.'</option>';
            }
            ?>
        </select><br>
        New Client:
        <input id="new_client_for_order" type="checkbox" name="new_client" /><br>
        <div id="new_client_name" style="display: none;">
            Type Client Name:<br>
            <input type="text" name="new_client_name"/><br>
            Own:
            <input id="own_client" type="checkbox" name="own_client" /><br>
        </div>
        Select product:<br>
        <select id="product_to_pick" name="product_to_pick"><br>
            <?php
            foreach($products as $product)
            {
                echo '<option value="'.$product->id.'">'.$product->name.'</option>';
            }
            ?>
        </select><br>
        Product Quantity:<br>
        <input id="product_quantity" type="number" step="0.001" max="<?php echo $products[0]->quantity; ?>" name="product_quantity" />
        <span id="product_type"><?php echo $products[0]->type; ?></span><br>
        Daily:
        <input id="daily" type="checkbox" name="daily" checked/>
        Sale:
        <input id="sale" type="checkbox" name="sale" /><br>
        <div id="product_price" <?php if($clients[0]->own == 'yes') echo 'style="display: none;" data-show="false"'; ?>>
            Product Price:<br>
            <input type="number" step="0.001" name="product_price" /><br>
        </div>

        <input type="submit" value="submit" />
        <button id="close_new_order">Close</button>
    </form>
</div>
<div class="orders">
    <button id="new_order">new order</button>
    <a href="dashboard"><button>back</button></a>
</div>

<?php $this->load->view('footer'); ?>