<?php $this->load->view('header'); ?>
<div class="popup" id="new_product_popup">
    <form id="new_form" action="products/new_product" method="post">
        Name:<br>
        <input type="text" name="name" /><br>
        Type:<br>
        <input type="text" name="type" /><br>
        Quantity:<br>
        <input type="number" step="0.001" name="quantity" /><br>
        New products quantity:<br>
        <input type="number" step="0.001" name="new_quantity" /><br>
        State:<br>
        <select name="state">
            <option value="normal">
                normal
            </option>
            <option value="bad">
                bad
            </option>
            <option value="useless">
                useless
            </option>
        </select><br>
        <input type="submit" value="submit" />
        <button id="close_new_product">Close</button>
    </form>
</div>
<div class="popup" id="edit_product_popup">
    <form id="edit_form" action="products/edit_product/<?php echo $products[0]->id; ?>" method="post">
        Select product:<br>
        <select id="product_to_edit"><br>
            <?php
            foreach($products as $product)
            {
                echo '<option value="'.$product->id.'">'.$product->name.'</option>';
            }
            ?>
        </select><br>
        Name:<br>
        <input id="edit_name" type="text" name="name" value="<?php echo $products[0]->name; ?>"/><br>
        Type:<br>
        <input id="edit_type" type="text" name="type" value="<?php echo $products[0]->type; ?>"/><br>
        Quantity:<br>
        <input id="edit_quantity" type="number" step="0.001" name="quantity" value="<?php echo $products[0]->quantity; ?>"/><br>
        New products quantity:<br>
        <input id="edit_new_quantity" type="number" step="0.001" name="new_quantity" value="<?php echo $products[0]->new_quantity; ?>"/><br>
        State:<br>
        <select id="edit_state" name="state" value="<?php echo $products[0]->state; ?>"><br>
            <option value="normal">
                normal
            </option>
            <option value="bad">
                bad
            </option>
            <option value="useless">
                useless
            </option>
        </select><br>
        <input type="submit" value="submit" />
        <button id="close_edit_product">Close</button>
    </form>
</div>
<div class="products">
    <button id="new_product">new product</button>
    <button id="edit_product">edit product</button>
    <a href="dashboard"><button>back</button></a>
</div>

<?php $this->load->view('footer'); ?>
