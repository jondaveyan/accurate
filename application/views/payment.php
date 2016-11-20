<?php $this->load->view('header'); ?>
<div class="popup" id="new_payment_popup">
    <form id="new_payment_form" action="payment/new_payment" method="post">
        Ընտրել կլիենտ:<br>
        <select id="client_pays" name="client_to_pick"><br>
            <?php
            foreach($clients as $client)
            {
                echo '<option value="'.$client->id.'">'.$client->name.'</option>';
            }
            ?>
        </select><br>
        Գին:<br>
        <input type="number" step="1" name="amount" /><br>

        Ամսաթիվ:
        <div class="input-group date" data-provide="datepicker">
            <input type="text" class="form-control datepicker" name="date">
            <div class="input-group-addon">
                <span class="glyphicon glyphicon-th"></span>
            </div>
        </div>

        <input type="submit" value="Հաստատել" class="btn btn-default" />
        <button id="close_new_payment" class="btn btn-default">Փակել</button>
    </form>
</div>
<div class="payment">
    <button id="new_payment" class="btn btn-default">Նոր վճարում</button>
    <a href="welcome"><button class="btn btn-default">Հետ</button></a>
</div>

<?php $this->load->view('footer'); ?>
