<?php $this->load->view('header'); ?>
<div id="container">
	<h1>Accurate</h1>

	<div id="body">
		<a href="<?php echo site_url('products'); ?>"><button>Products</button></a>
		<a href="<?php echo site_url('clients'); ?>"><button>Clients</button></a>
		<a href="<?php echo site_url('orders'); ?>"><button>Orders</button></a>
	</div>

</div>
<?php $this->load->view('footer'); ?>