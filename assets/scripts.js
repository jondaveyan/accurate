/**
 * Created by Jon on 10/28/2016.
 */
$(document).ready(function(){
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
                $('#edit_name').val(product.name);
                $('#edit_type').val(product.type);
                $('#edit_quantity').val(product.quantity);
                $('#edit_new_quantity').val(product.new_quantity);
                $('#edit_state').val(product.state);
                $('#edit_form').attr('action', 'products/edit_product/'+product.id);
            }
        });
    })
})