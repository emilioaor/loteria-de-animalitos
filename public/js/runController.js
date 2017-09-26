

function addHorse() {

    var newHorse = $('#newHorse').val();
    var text = $('#newHorse option:selected').data('label');

    if (newHorse > 0) {

        $('#newHorse').val(0);
        $('#newHorse #option' + newHorse).hide();

        var html = '';

        html += '<tr id="row' + newHorse + '" >';
        html +=     '<td>';
        html +=         text;
        html +=         '<input type="hidden" name="horses[]" value="' + newHorse + '">';
        html +=     '</td>';
        html +=     '<td>';
        html +=         '<input type="number" class="form-control" value="0" name="staticTable[' + newHorse + ']" placeholder="Precio de tabla fija" required>';
        html +=     '</td>';

        html +=     '<td>';
        html +=         '<button type="button" onclick="removeHorse(' + newHorse + ')" class="btn btn-danger"><i class="fa fa-fw fa-remove"></i></button>';
        html +=     '</td>';
        html += '</tr>';

        $('#spaceHorses').append(html);
    }
}

function removeHorse(id) {
    $('#row' + id).html('');
    $('#newHorse #option' + id).show();
}