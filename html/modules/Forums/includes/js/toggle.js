function toggle_check_all() {
    var archive_text = "archive_id";
    for (var i=0; i < document.msgrow_values.elements.length; i++) {
        var checkbox_element = document.msgrow_values.elements[i];
        if ((checkbox_element.name != 'check_all_del_box') && (checkbox_element.name != 'check_all_arch_box') && (checkbox_element.type == 'checkbox')) {
            if (checkbox_element.name.search("archive_id") != -1) {        
                checkbox_element.checked = document.msgrow_values.check_all_arch_box.checked;
            } else {            
                checkbox_element.checked = document.msgrow_values.check_all_del_box.checked;            
            }
        }
    }
}