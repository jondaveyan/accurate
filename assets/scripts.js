/**
 * Created by Jon on 10/28/2016.
 */
function Save(){ var par = $(this).parent().parent();
    //var tdProduct = par.children("td:nth-child(1)");
    var tdQuantity = par.children("td:nth-child(2)");
    var tdPrice = par.children("td:nth-child(3)");
    var tdDate = par.children("td:nth-child(4)");
    var tdType = par.children("td:nth-child(5)");
    var tdButtons = par.children("td:nth-child(6)");
    var id = par.data('id');
    var product_id = par.data('product_id');

    //tdProduct.html(tdProduct.children("input[type=text]").val());
    tdQuantity.html(tdQuantity.children("input[type=text]").val());
    tdPrice.html(tdPrice.children("input[type=number]").val());
    tdDate.html(tdDate.children("input[type=text]").val());
    tdType.html(tdType.children("select").val());
    tdButtons.html('<button class="btnEdit btn-default btn-xs">Փոփոխել</button><button class="btnDelete btn-danger btn-xs">Ջնջել</button>');
    $(".btnEdit").bind("click", Edit);
    $(".btnDelete").bind("click", Delete);

    var result = {'id': id, 'product_id': product_id, 'quantity': tdQuantity.html(), 'price': tdPrice.html(), 'date': tdDate.html(), 'type': tdType.html()};

    $.ajax({
        method: "post",
        dataType: 'json',
        data: result,
        url: 'orders/update_order',
        success: function(data) {
        }
    });
};

function Edit(){ var par = $(this).parent().parent();
    //var tdProduct = par.children("td:nth-child(1)");
    var tdQuantity = par.children("td:nth-child(2)");
    var tdPrice = par.children("td:nth-child(3)");
    var tdDate = par.children("td:nth-child(4)");
    var tdType = par.children("td:nth-child(5)");
    var tdButtons = par.children("td:nth-child(6)");

    //tdProduct.html("<input type='text' style='width: 100%;' id='txtProduct' value='"+tdProduct.html()+"'/>");
    tdQuantity.html("<input type='text' style='width: 100%;' id='txtQuantity' value='"+tdQuantity.html()+"'/>");
    tdPrice.html("<input type='number' style='width: 100%;' step='0.01' id='txtPrice' value='"+tdPrice.html()+"'/>");
    tdDate.html("<input type='text' style='width: 100%;' id='txtDate' value='"+tdDate.html()+"'/>");
    tdType.html("<select style='width: 100%;' id='txtType'><option value='Օրավարձ'>Օրավարձ</option><option value='Վաճառք'>Վաճառք</option>");
    tdButtons.html('<button class="btnSave btn-info btn-xs">Պահպանել</button>');

    $(".btnSave").bind("click", Save);
    $(".btnEdit").bind("click", Edit);
    $(".btnDelete").bind("click", Delete);
};

function Delete(){
    var par = $(this).parent().parent();
    var id = par.data('id');
    var product_id = par.data('product_id');
    var result = {'id': id, 'product_id': product_id};
    par.remove();
    $.ajax({
        method: "post",
        dataType: 'json',
        data: result,
        url: 'orders/delete_order',
        success: function(data) {
        }
    });
};


$(document).ready(function(){

    $('.datepicker').datepicker({
        "todayHighlight": true,
        "setDate": new Date(),
        "autoclose": true
    });
    //dashboard
    $(document).on('click', '.daily_order', function(){
        var el = $(this);
        $.ajax({
            method: "get",
            dataType: 'json',
            url: 'dashboard/get_product_client_table/'+el.data('id'),
            success: function(data) {
                $('#product_client_popup').html(data.data);
                $('#product_client_popup').show();
            }
        });
    })

    $(document).on('click', '.product_client_info', function(){
        var el = $(this);
        $.ajax({
            method: "get",
            dataType: 'json',
            url: 'dashboard/get_product_client_info/'+el.data('client_id')+'/'+el.data('product_id'),
            success: function(data) {
                $('#myModal .modal-content').html(data.html);
            }
        });
    })

    $(document).on('click', '.client_info', function(){
        var el = $(this);
        $.ajax({
            method: "get",
            dataType: 'json',
            url: 'dashboard/get_client_info/'+el.data('client_id'),
            success: function(data) {
                $('#myModal .modal-content').html(data.html);
                $(".btnEdit").bind("click", Edit);
                $(".btnDelete").bind("click", Delete);
            }
        });
    })

    $(document).on('click', '.product_info', function(){
        var el = $(this);
        $.ajax({
            method: "get",
            dataType: 'json',
            url: 'dashboard/get_product_info/'+el.data('product_id'),
            success: function(data) {
                $('#myModal .modal-content').html(data.html);
            }
        });
    })

    //giveback
    $(document).on('click', '#new_giveback', function(){
        $('#new_giveback_popup').show();
    });
    $(document).on('click', '#close_new_giveback', function(event){
        event.preventDefault();
        $('#new_giveback_popup').hide();
    });

    //payment
    $(document).on('click', '#new_payment', function(){
        $('#new_payment_popup').show();
    });
    $(document).on('click', '#close_new_payment', function(event){
        event.preventDefault();
        $('#new_payment_popup').hide();
    });


    //products
    $(document).on('click', '#new_product', function(){
        $('#new_product_popup').show();
        $('#edit_product_popup').hide();
    });
    $(document).on('click', '#close_new_product', function(event){
        event.preventDefault();
        $('#new_product_popup').hide();
    });
    $(document).on('click', '#edit_product', function(){
        $('#edit_product_popup').show();
        $('#new_product_popup').hide();
    });
    $(document).on('click', '#close_edit_product', function(event){
        event.preventDefault();
        $('#edit_product_popup').hide();
    });
    $(document).on('change', '#product_to_edit', function(){
        $.ajax({
            method: "get",
            dataType: 'json',
            url: 'products/get_product_details/'+$('#product_to_edit').val(),
            success: function(data) {
                var product = data.data[0];
                $('#edit_product_name').val(product.name);
                $('#edit_type').val(product.type);
                $('#edit_quantity').val(product.quantity);
                $('#edit_new_quantity').val(product.new_quantity);
                $('#edit_bad_quantity').val(product.bad_quantity);
                $('#edit_useless_quantity').val(product.useless_quantity);
                $('#edit_product_form').attr('action', 'products/edit_product/'+product.id);
            }
        });
    })

    //clients
    $(document).on('click', '#new_client', function(){
        $('#new_client_popup').show();
        $('#edit_client_popup').hide();
    });
    $(document).on('click', '#close_new_client', function(event){
        event.preventDefault();
        $('#new_client_popup').hide();
    });
    $(document).on('click', '#edit_client', function(){
        $('#edit_client_popup').show();
        $('#new_client_popup').hide();
    });
    $(document).on('click', '#close_edit_client', function(event){
        event.preventDefault();
        $('#edit_client_popup').hide();
    });
    $(document).on('change', '#client_to_edit', function(){
        $.ajax({
            method: "get",
            dataType: 'json',
            url: 'clients/get_client_details/'+$('#client_to_edit').val(),
            success: function(data) {
                var client = data.data[0];
                $('#edit_client_name').val(client.name);
                if(client.own == 'yes')
                {
                    $('#edit_own').prop('checked', true);
                }
                else
                {
                    $('#edit_own').prop('checked', false);
                }
                $('#edit_debt').val(client.debt);
                $('#edit_client_form').attr('action', 'clients/edit_client/'+client.id);
            }
        });
    })

    //orders
    $(document).on('click', '#new_order', function(){
        $('#new_order_popup').show();
    });
    $(document).on('click', '#close_new_order', function(event){
        event.preventDefault();
        $('#new_order_popup').hide();
    });
    $(document).on('change', '#new_client_for_order', function(){
        $('#new_client_name').toggle();
        if($("#new_client_for_order").is(":checked"))
        {
            $('#client_to_pick').prop('disabled', 'disabled');
            $('#product_price').show();
        }
        else
        {
            $('#client_to_pick').prop('disabled', false);
            if($('#product_price').attr('data-show') == 'false')
            {
                $('#product_price').hide();
            }
        }
    })
    $(document).on('change', '#own_client', function(){
        if($("#own_client").is(":checked"))
        {
            $('#product_price').hide();
        }
        else
        {
            $('#product_price').show();
        }
    })
    $(document).on('change', '#sale', function(){
        if($("#sale").is(":checked"))
        {
            $('#daily').prop('checked', false);
        }
        else
        {
            $('#daily').prop('checked', true);
        }
    })
    $(document).on('change', '#daily', function(){
        if($("#daily").is(":checked"))
        {
            $('#sale').prop('checked', false);
        }
        else
        {
            $('#sale').prop('checked', true);
        }
    })
    $(document).on('change', '#product_to_pick', function(){
        $.ajax({
            method: "get",
            dataType: 'json',
            url: 'products/get_product_details/'+$('#product_to_pick').val(),
            success: function(data) {
                var product = data.data[0];
                $('#product_type').text(product.type);
                $('#product_quantity').attr('max' ,(product.quantity-product.daily_order));
                if(parseInt($('#product_quantity').val()) > product.quantity)
                {
                    $('#product_quantity').val(product.quantity);
                }
            }
        });
    })
    $(document).on('change', '#client_to_pick', function(){
        $.ajax({
            method: "get",
            dataType: 'json',
            url: 'clients/get_client_details/'+$('#client_to_pick').val(),
            success: function(data) {
                var client = data.data[0];
                if(client.own == 'yes')
                {
                    $('#edit_own').prop('checked', true);
                    $('#product_price').hide();
                    $('#product_price').attr('data-show', 'false');
                }
                else
                {
                    $('#product_price').show();
                    $('#product_price').attr('data-show', 'true');
                }
            }
        });
    })
    $(document).on('change', '#product_quantity', function(){
        var max = $('#product_quantity').attr('max');
        if(parseInt($('#product_quantity').val()) > max)
        {
            $('#product_quantity').val(max);
        }
    })
})