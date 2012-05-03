var theSelection = false;
    function openAllSmiles(){
        smiles = window.open('modules.php?name=Forums&file=posting&mode=smilies&popup=1', '_phpbbsmilies', 'HEIGHT=300,resizable=yes,scrollbars=yes,WIDTH=500');
        smiles.focus();
        return false;
    }
function quoteSelection()
{
        if (document.getSelection) txt = document.getSelection();
    else if (document.selection) txt = document.selection.createRange().text;
    else return;

    theSelection = txt.replace(new RegExp('([\\f\\n\\r\\t\\v ])+', 'g')," ");
        if (theSelection) {
            // Add tags around selection
            emoticon( '[quote]\n' + theSelection + '\n[/quote]\n');
            document.post.message.focus();
            theSelection = '';
            return;
        }else{
            alert('You must specify a subject when posting a new topic.');
        }
}

    function storeCaret(textEl) {
        if (textEl.createTextRange) textEl.caretPos = document.selection.createRange().duplicate();
    }

    function emoticon(text) {
        text = ' ' + text + ' ';
        if (document.post.message.createTextRange && document.post.message.caretPos) {
            var caretPos = document.post.message.caretPos;
            caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
            document.post.message.focus();
        } else {
            document.post.message.value  += text;
            document.post.message.focus();
        }
    }

    function checkForm() {
        formErrors = false;
        if (document.post.message.value.length < 2) {
            formErrors = 'You must enter a message when posting.';
        }
        if (!document.post.subject.value) {
                   formErrors = 'You must specify a subject when posting a new topic.';
                }
                if (formErrors) {
            alert(formErrors);
            return false;
        } else {
            if (document.post.quick_quote.checked) {
                document.post.message.value = document.post.last_msg.value + document.post.message.value;
            } 
            document.post.quick_quote.checked = false;
            return true;
        }
    }