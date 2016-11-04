<?php $this->load->view('header'); ?>
<div class="popup" id="new_payment_popup">
    <form id="new_payment_form" action="payment/new_payment" method="post">
        Select Client:<br>
        <select id="client_pays" name="client_to_pick"><br>
            <?php
            foreach($clients as $client)
            {
                echo '<option value="'.$client->id.'">'.$client->name.'</option>';
            }
            ?>
        </select><br>
        Amount:<br>
        <input type="number" step="1" name="amount" /><br>

        <input type="submit" value="submit" class="btn btn-default" />
        <button id="close_new_payment" class="btn btn-default">close</button>
    </form>
</div>
<div class="payment">
    <button id="new_payment" class="btn btn-default">new payment</button>
    <a href="welcome"><button class="btn btn-default">back</button></a>
</div>

<?php $this->load->view('footer'); ?>
