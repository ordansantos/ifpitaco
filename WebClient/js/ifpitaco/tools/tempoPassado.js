    
function tempoPassado (t) {

    var d = new Date();
    var n = d.getTimezoneOffset();
    var timezone = n * 60 * 1000;

    var dt = new Date(t);
    var now = new Date();

    var mes = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

    var month = dt.getMonth();
    var day = dt.getDate();
    var year = dt.getFullYear();
    var hour = dt.getHours();
    var minute = dt.getMinutes();

    var time = now.getTime() - dt.getTime() + timezone;

    var dias = parseInt(time / 86400000);

    time = time % 86400000;

    var horas = parseInt(time / 3600000);

    time = time % 3600000;

    var minutos = parseInt(time / 60000);

    time = time % 60000;

    var segundos = parseInt(time / 1000);



    if (dias > 28)
        //return day + ' de ' + mes[month] + ' de ' + year + ' às ' + hour + ':' + minute;
        return day + ' de ' + mes[month] + ' de ' + year;
    if (dias)
        return dias + ' dia' + (dias > 1 ? 's' : '');
    if (horas)
        return horas + ' hora' + (horas > 1 ? 's' : '');
    if (minutos)
        return minutos + ' minuto' + (minutos > 1 ? 's' : '');
    if (segundos)
        //return segundos + ' segundo' + (segundos > 1? 's' : '');
        return 'agora mesmo';

    return 'agora mesmo';
};