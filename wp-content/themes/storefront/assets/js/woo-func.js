jQuery('.metabox_submit').click(function(e) {
    e.preventDefault();
    jQuery('#publish').click();
});
jQuery('#clear_button').click(function(e) {
    const result = confirm('Clear fields?');
    if (result) {
        jQuery('#_product_type').prop('selectedIndex', 0);
        } else {
            alert("Changes not saved");
        }
});

