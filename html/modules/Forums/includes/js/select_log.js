function select_log(status) {
    for (i = 0; i < document.log_list.length; i++) {
        document.log_list.elements[i].checked = status;
    }
}