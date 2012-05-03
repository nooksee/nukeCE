function select_attach(status) {
    for (i = 0; i < document.attach_list.length; i++) {
        document.attach_list.elements[i].checked = status;
    }
}