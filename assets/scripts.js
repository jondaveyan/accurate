/**
 * Created by Jon on 10/28/2016.
 */
$(document).ready(function(){

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
                $('#product_quantity').attr('max' ,product.quantity);
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