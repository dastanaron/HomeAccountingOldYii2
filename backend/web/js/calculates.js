$(document).ready(function () {

    $( ".date_control" ).datepicker({
        dateFormat: "dd.mm.yy",
    });

    $('button#calc_selected').click(function () {
        var keys = $('#arrival_or_expense').yiiGridView('getSelectedRows');

        if(keys == '' || keys == []) {
            $('#check_selected').html('');
            alert('ничего не выбрано');
            return ;
        }
        console.log(keys);

        var sum = 0;

        $.each(keys, function (index, value) {
            sum += parseInt($('#arrival_or_expense').find('table').find('tr[data-key='+value+']').children('td[data-col-seq=4]').html());
        });

        console.log(sum);
        $('#check_selected').html('Сумма выбранных: <span class="calc_value">' + sum + '</span>');
    });

});
