<?php $this->load->view('header'); ?>
<div class="popup" id="new_giveback_popup">
    <form id="new_giveback_form" action="giveback/new_giveback" method="post">
        Select Client:<br>
        <select id="client_gives" name="client_to_pick"><br>
            <?php
            foreach($clients as $client)
            {
                echo '<option value="'.$client->id.'">'.$client->name.'</option>';
            }
            ?>
        </select><br>
        Select Product:<br>
        <select id="product_given" name="product_to_pick"><br>
            <?php
            foreach($products as $product)
            {
                echo '<option value="'.$product->id.'">'.$product->name.'</option>';
            }
            ?>
        </select><br>
        Product Quantity:<br>
        <input type="number" step="0.01" name="product_quantity" /><br>

        <input type="submit" value="submit" />
        <button id="close_new_giveback">close</button>
    </form>
</div>
<div class="giveback">
    <button id="new_giveback">return products</button>
    <a href="dashboard"><button>back</button></a>
</div>

<?php $this->load->view('footer'); ?>
