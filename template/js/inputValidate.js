function InputCount(item, input_type, lenght_value, id_item_correct, lenght_exceed_message, length_empty_message, correct_message)
{
    // определяем переменную для показа сообщения об ошибке
    var item_correct = id_item_correct;

    if (document.getElementById(item).value.length > lenght_value) {
        document.getElementById(item_correct).innerHTML = lenght_exceed_message;
        document.getElementById(item_correct).className = 'acorrect';
        InputError(item, input_type);
    }
    else if (document.getElementById(item).value.length >= 1) {
        document.getElementById(item_correct).innerHTML = correct_message;
        document.getElementById(item_correct).className = 'correct';
        InputNormal(item);
    }
    else if (document.getElementById(item).value.length < 1) {
        document.getElementById(item_correct).innerHTML = length_empty_message;
        document.getElementById(item_correct).className = 'acorrect';
        InputError(item, input_type);
    }
}

function InputError(input_id, input_type){
    document.getElementById(input_id).className = input_type + ' error';
}

function InputNormal(input_id) {
    var classStr = document.getElementById(input_id).className;
    document.getElementById(input_id).className = classStr.replace(" error", "");
}

function CountMiddlen(item, input_type) {
    // определяем переменную для показа сообщения об ошибке
    var item_correct = 'middlename_correct';

    if (document.getElementById(item).value.length > 128) {
        document.getElementById(item_correct).innerHTML = 'Отчество не может быть такой длины';
        document.getElementById(item_correct).className = 'acorrect';
        InputError(item, input_type);
    }
    else if (document.getElementById(item).value.length >= 0) {
        document.getElementById(item_correct).innerHTML = '';
        document.getElementById(item_correct).className = 'correct';
        InputNormal(item);
    }
}