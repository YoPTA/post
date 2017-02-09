/*
 Позволяте проверить длину значения в поле.
 Поле не может быть пустым.

 var item string - id поля
 var input_type  string - размер поля (указывается в классе)
 var lenght_value int - количество символов
 var id_item_correct string - id, где будет отображено сообщение об ошибке
 var lenght_exceed_message string - сообщение, что длина превышает допустимую
 var length_empty_message string - сообщение, что поле пустое
 var correct_message string - сообщение, если всё правильно
 */
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


/*
Проверяет длину значения в поле.
Поле может быть пустым.

var item string - id поля
var input_type  string - размер поля (указывается в классе)
var lenght_value int - количество символов
var id_item_correct string - id, где будет отображено сообщение об ошибке
var acorrect_message string - сообщение об ошибке
var correct_message string - сообщение, если всё правильно
 */
function InputCountCanEmpty(item, input_type, lenght_value, id_item_correct, acorrect_message, correct_message) {
    // определяем переменную для показа сообщения об ошибке
    var item_correct = id_item_correct;

    if (document.getElementById(item).value.length > lenght_value) {
        document.getElementById(item_correct).innerHTML = acorrect_message;
        document.getElementById(item_correct).className = 'acorrect';
        InputError(item, input_type);
    }
    else if (document.getElementById(item).value.length >= 0) {
        document.getElementById(item_correct).innerHTML = correct_message;
        document.getElementById(item_correct).className = 'correct';
        InputNormal(item);
    }
}

/*
Проверяет пароль на совпадение
Поле не может быть пустым

var item1 string - id поля, с которым сравнивают
var item2 string - id поля, которое сравнивают
var input_type  string - размер поля (указывается в классе)
var id_item_correct string - id, где будет отображено сообщение об ошибке
*/
function CompareFields(item1, item2, input_type, id_item_correct) {
    // длина поля, с которым сравнивают
    var item1_lenght = document.getElementById(item1).value.length;

    // проверяем совпадают ли значения введеных паролей
    if (document.getElementById(item2).value == document.getElementById(item1).value) {
        // если совпадают, сообщаем об этом
        document.getElementById(id_item_correct).innerHTML = 'Совпадают';
        document.getElementById(id_item_correct).className = 'correct';
        InputNormal(item2);
    }
    else if (document.getElementById(item1).value.length > 0) {
        document.getElementById(id_item_correct).innerHTML = 'Не совпадают';
        document.getElementById(id_item_correct).className = 'acorrect';
        InputError(item2, input_type);
    }

}