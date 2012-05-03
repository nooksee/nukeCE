function select_shadow(status) {
    for (i = 0; i < document.shadow_list.length; i++) {
        document.shadow_list.elements[i].checked = status;
    }
}