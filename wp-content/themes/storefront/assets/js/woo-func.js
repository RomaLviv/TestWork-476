jQuery('.metabox_submit').click(function(e) {
    e.preventDefault();
    jQuery('#publish').click();
});
jQuery('#clear_button').click(function(e) {
    const result = confirm('Clear fields?');
    if (result) {
        jQuery('#_product_type').prop('selectedIndex', 0);
        jQuery('#image_prew').val('');
        jQuery('#preview_prod_img img').attr("src","");

        } else {
            alert("Changes not saved");
        }
});
function imagePreview(fileInput) {
    if (fileInput.files && fileInput.files[0]) {
        var fileReader = new FileReader();
        fileReader.onload = function (event) {
            jQuery('#preview_prod_img').html('<img src="'+event.target.result+'" width="300" height="auto"/>');
        };
        fileReader.readAsDataURL(fileInput.files[0]);
    }
}
jQuery("#image_prew").change(function () {
    imagePreview(this);
});
