<?php $this->load->view('header'); ?>
<div class="popup" id="new_giveback_popup">
    <form id="new_giveback_form" action="giveback/new_giveback" method="post">
        Ընտրել կլիենտ:<br>
        <select id="client_gives" name="client_to_pick"><br>
            <?php
            foreach($clients as $client)
            {
                echo '<option value="'.$client->id.'">'.$client->name.'</option>';
            }
            ?>
        </select><br>
        Ընտրել ապրանք:<br>
        <select id="product_given" name="product_to_pick"><br>
            <?php
            foreach($products as $product)
            {
                echo '<option value="'.$product->id.'">'.$product->name.'</option>';
            }
            ?>
        </select><br>
        Ապրանքի քանակ:<br>
        <input type="number" step="0.01" name="product_quantity" /><br>
        Ամսաթիվ:
        <div class="input-group date" data-provide="datepicker">
            <input type="text" class="form-control datepicker" name="date">
            <div class="input-group-addon">
                <span class="glyphicon glyphicon-th"></span>
            </div>
        </div>

        <input type="submit" value="Հաստատել"  class="btn btn-default"/>
        <button id="close_new_giveback" class="btn btn-default">Փակել</button>
    </form>
</div>
<div class="giveback">
    <button id="new_giveback" class="btn btn-default">Ապրանքի վերադարձ</button>
    <a href="welcome"><button class="btn btn-default">Հետ</button></a>
</div>

<?php $this->load->view('footer'); ?>
