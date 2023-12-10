for (let row = 0; row < 4; row++) {
    trs+='<tr>'
        for (let col = 0; col < 500; col++) {
            let rw = '<td><div id="cell'+row+'-'+col+'" class="trend-item bg-disabled"></div></td>'
            trs += rw;
        }
    trs+='</tr>'
}


function fillTrends(res){
    clearTrends();
    for(let s = 0; s < res.length; s++){
        let str = res[s].result;
        let colors = str.split(',');
        let c = 0;
        for(let x = 0; x < colors.length+1; x++){
            if(x == 0){
                $('#cell'+x+'-'+s).text(res[s].fight_number);
            }else{
                $('#cell'+x+'-'+s).addClass(colors[c]).text(convertResult(colors[c]));
                c++;
            }
        }
    }
}


$('.trend-table').append(trs);

$('.btn-jump-fight').on('click',function(){
    var data ={
        id : currentFightId,
        fight_number : $('#fightNumber').val(),
        _token : token
    }

    $.ajax({
        type : "POST",
        url : jumpFight,
        data : data,
        success:function(res){
            $('.fight-number').text(res.fight_number);
            $('#fightNumber').val(res.fight_number);
        },
        error: function(err){
            console.log(err);
        }
    })
})

$('.btn-choose3').on('click',function(){
    let color = $(this).data('color')
    $('#third-color').val(color);
    $('#selected-third').addClass(color).text(convertResult(color));
});

$('.btn-choose2').on('click',function(){
    let color = $(this).data('color')
    $('#second-color').val(color);
    $('#selected-second').addClass(color).text(convertResult(color));
});

$('.btn-choose1').on('click',function(){
    let color = $(this).data('color')
    $('#first-color').val(color);
    $('#selected-first').addClass(color).text(convertResult(color))
});

$('.btn-ghost-bet').on('click',function(){
    let bet = $(this).data('bet');
    var data ={
        fight_id : currentFightId,
        event_id : event_id,
        bet : bet,
        amount : $('#ghost-bet-amount').val(),
        _token : token
    }

    $.ajax({
        type : "POST",
        url : ghost,
        data : data,
        success:function(res){
            console.log(res)
        },
        error: function(err){
            console.log(err);
        }
    })
})

function fillBetsTable(betsList){
    $('.live-bets tr').remove();
    trd = '';
    betsList.forEach((e,i)=> {
        trd+='<tr>'
        let rw = '<td>'+parseInt(i+1)+'</td>'
        rw+= '<td>'+e.username+'</td>'
        rw+= '<td><span class="badge uppercase-bet '+e.bet+'">'+e.bet+'</span></td>'
        rw+= '<td>'+e.amount+'</td>'
        trd += rw;
        trd+='</tr>'
    });
    $('.live-bets').append(trd);
}

function checkFightStatus(stat){
    if(stat == 'not_open'){
        $('.btn-jump-fight').prop('disabled',false);
    }

    if(stat == 'open'){
        $('.btn-open').hide();
        $('.btn-last-call').show();
        $('.btn-closed').hide();
    }

    if(stat == 'last call'){
        $('.btn-open').hide();
        $('.btn-last-call').hide();
        $('.btn-closed').show();
    }

    if(stat == 'closed'){
        $('.btn-open').show();
        $('.btn-last-call').hide();
        $('.btn-closed').hide();
        $('.btn-open').prop('disabled',true);
        $('#btn-declarator button').prop('disabled',false);
    }
}

function convertResult(res){
    if(res == 'jack'){
        return 'J';
    }

    if(res == 'queen'){
        return 'Q';
    }

    if(res == 'king'){
        return 'K';
    }

    if(res == 'nine'){
        return '9';
    }

    if(res == 'ten'){
        return '10';
    }

    if(res == 'ace'){
        return 'A';
    }
}

function clearTrends(){
    for (let row = 0; row < 6; row++) {
        for (let col = 0; col < 45; col++) {
            $('#cell'+row+col).removeClass('blue').text('')
            $('#cell'+row+col).removeClass('grey').text('')
            $('#cell'+row+col).removeClass('red').text('')
            $('#cell'+row+col).removeClass('yellow').text('')
            $('#cell'+row+col).removeClass('white').text('')
            $('#cell'+row+col).removeClass('pink').text('')
        }
    }
}

function updateBets(betBlue,betGrey,betRed,betYellow,betWhite,betPink){
    $('.bets-blue').text(numberWithCommas(parseFloat(betBlue).toFixed(2)));
    $('.bets-grey').text(numberWithCommas(parseFloat(betGrey).toFixed(2)));
    $('.bets-red').text(numberWithCommas(parseFloat(betRed).toFixed(2)));
    $('.bets-yellow').text(numberWithCommas(parseFloat(betYellow).toFixed(2)));
    $('.bets-white').text(numberWithCommas(parseFloat(betWhite).toFixed(2)));
    $('.bets-pink').text(numberWithCommas(parseFloat(betPink).toFixed(2)));
}


$('.btn-event-status').on('click',function(){
    let url = $(this).data('url');
    window.location.href = url;
})

$('.btn-fight-status').on('click',function(){
    let status = $(this).data('value');
    var dis = $(this);
    var data ={
        id : currentFightId,
        status : status,
        _token : token
    }
    $.ajax({
        type : "POST",
        url : fightStat,
        data : data,
        success:function(res){
            dis.hide();
            if(res.status == "OPEN"){
                $('.btn-last-call').show();
                $('#btn-declarator button').prop('disabled',true);
                $('.btn-jump-fight').prop('disabled',true);
            }

            if(res.status == "LAST CALL"){
                $('.btn-closed').show();
                $('.btn-jump-fight').prop('disabled',true);
            }

            if(res.status == "CLOSED"){
                $('.btn-open').show();
                $('.btn-open').prop('disabled',true);
                $('#btn-declarator button').prop('disabled',false);
                $('.btn-jump-fight').prop('disabled',true);
            }

        },
        error: function(err){
            console.log(err);
        }
    })
})

$('.btn-declare').on('click',function(){

    let f = $('#first-color').val();
    let sec = $('#second-color').val();
    let t = $('#third-color').val();

    let result = $('#first-color').val()+','+$('#second-color').val() +','+$('#third-color').val();
    var dis = $(this);
    var data ={
        id : currentFightId,
        result : result,
        event_id : event_id,
        _token : token
    }

    if(f == "" || sec == "" || t == ""){
        Swal.fire({
            title: 'Please select three colors to declare!',
            text: "",
            icon: 'info',
        });
    }else{
        Swal.fire({
            title: 'Declare '+result+'?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
          }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type : "POST",
                    url : declareWin,
                    data : data,
                    success:function(res){
                        currentFightId = res.fight_id;
                        $('.fight-number').text(res.fight_number);
                        $('#fightNumber').val(res.fight_number);
                        fillTrends(res.wins);
                        updateBets(0,0);
                        $('.btn-open').prop('disabled',false);
                        $('#btn-declarator button').prop('disabled',true);
                        $('.btn-jump-fight').prop('disabled',false);
                        $('.live-bets tr').remove();
                    },
                    error: function(err){
                        console.log(err);
                    }
                })
            }
        })
    }


})

$('.btn-redeclare').on('click',function(){
    let id = $('#select-fight-id').val();
    let result = $('#select-result').val();

    if(id == "" || result == ""){
        Swal.fire('Please select fight number and result','','warning');
    }else{
        var data ={
            fight_id : id,
            result : result,
            event_id : event_id,
            _token : token
        }

        $.ajax({
            type : "POST",
            url : redeclareWin,
            data : data,
            success:function(res){
                fillTrends(res.wins);
                $('#select-fight-id').val("").trigger("change");
                $('#select-result').val("");
                $('#redeclareFightModal').modal('hide');
                Swal.fire(
                    'Fight successfully redeclared!',
                    '',
                    'success'
                );
            },
            error: function(err){
                console.log(err);
            }
        })
    }

})

$('.btn-bet-amt').on('click',function(){
    let amt = $(this).data('value');
    $('#ghost-bet-amount').val(amt);
})

function numberWithCommas(n) {
    var parts=n.toString().split(".");
    return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (parts[1] ? "." + parts[1] : "");
}


fillTrends(results);
checkFightStatus(fightStatus);
