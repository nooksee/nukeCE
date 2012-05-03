function select_mm(status) {
    for (i = 0; i < document.mmo_list.length; i++) {
        document.mmo_list.elements[i].checked = status;
    }
}