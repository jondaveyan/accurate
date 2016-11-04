<?php $this->load->view('header'); ?>
<div class="popup" id="new_client_popup">
    <form id="new_client_form" action="clients/new_client" method="post">
        Name:<br>
        <input type="text" name="name" /><br>
        Own:<br>
        <input type="checkbox" name="own" /><br>
        Debt:<br>
        <input type="number" step="0.001" name="debt" /><br>
        <input type="submit" value="submit" class="btn btn-default"/>
        <button id="close_new_client" class="btn btn-default">Close</button>
    </form>
</div>
<div class="popup" id="edit_client_popup">
    <form id="edit_client_form" action="clients/edit_client/<?php echo $clients[0]->id; ?>" method="post">
        Select the client:<br>
        <select id="client_to_edit"><br>
            <?php
            foreach($clients as $client)
            {
                echo '<option value="'.$client->id.'">'.$client->name.'</option>';
            }
            ?>
        </select><br>
        Name:<br>
        <input id="edit_client_name" type="text" name="name" value="<?php echo $clients[0]->name; ?>"/><br>
        Own:<br>
        <input id="edit_own" <?php if($clients[0]->own == 'yes')echo "checked"; ?> type="checkbox" name="own"/><br>
        Debt:<br>
        <input id="edit_debt" type="number" step="0.001" name="debt" value="<?php echo $clients[0]->debt; ?>"/><br>
        <input type="submit" value="submit" />
        <button id="close_edit_client" class="btn btn-default">close</button>
    </form>
</div>
<div class="clients">
    <button id="new_client" class="btn btn-default">new client</button>
    <button id="edit_client" class="btn btn-default">edit client</button>
    <a href="welcome"><button class="btn btn-default">back</button></a>
</div>

<?php $this->load->view('footer'); ?>
