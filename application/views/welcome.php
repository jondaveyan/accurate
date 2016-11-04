<?php $this->load->view('header'); ?>
<div id="container">
    <h1>Accurate</h1>

    <div id="body">
        <a href="<?php echo site_url('products'); ?>"><button class="btn btn-default">Products</button></a>
        <a href="<?php echo site_url('clients'); ?>"><button class="btn btn-default">Clients</button></a>
        <a href="<?php echo site_url('orders'); ?>"><button class="btn btn-default">Orders</button></a>
        <a href="<?php echo site_url('payment'); ?>"><button class="btn btn-default">Payment</button></a>
        <a href="<?php echo site_url('giveback'); ?>"><button class="btn btn-default">Give back</button></a>
        <a href="<?php echo site_url('dashboard'); ?>"><button class="btn btn-info">Dashboard</button></a>
    </div>
</div>
<?php $this->load->view('footer'); ?>