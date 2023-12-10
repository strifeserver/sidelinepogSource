var bettingAutoId;

for (let row = 0; row < maxRow; row++) {
    trs+='<tr>'
        for (let col = 0; col < 200; col++) {
            let rw = '<td><div id="cell'+row+'-'+col+'" class="trend-item bg-disabled"></div></td>'
            trs += rw;
        }
    trs+='</tr>'
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
            updateBets(res.betMeron,res.betWala);
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

function checkFightStatus(stat,meronOdds,walaOdds){
    if(stat == 'not_open'){
        $('.btn-jump-fight').prop('disabled',false);
    }

    if(stat == 'open'){
        $('.btn-open').hide();
        $('.btn-last-call').show();
        $('.btn-closed').hide();

        // betAmt = randomNumber(1000,5000);
        // let finalbetAmt = parseInt(betAmt)/10;
        // bettingAutoId = setInterval(function(){
        //     autoBet(finalbetAmt)
        // }, 5000);
    }

    if(stat == 'last call'){
        $('.btn-open').hide();
        $('.btn-last-call').hide();
        $('.btn-closed').show();

        // betAmt = randomNumber(1000,5000);
        // let finalbetAmt = parseInt(betAmt)/10;
        // bettingAutoId = setInterval(function(){
        //     autoBet(finalbetAmt)
        // }, 5000);
    }

    if(stat == 'closed'){
        $('.btn-open').show();
        $('.btn-last-call').hide();
        $('.btn-closed').hide();
        $('.btn-open').prop('disabled',true);
        $('#btn-declarator button').prop('disabled',false);

        if(meronOdds <=120 || walaOdds<=120){
            $('.btn-declare').prop('disabled',true);
            $('.btn-declare-cancel').prop('disabled',false);
        }

        if(meronOdds == 0 || walaOdds == 0){
            $('.btn-declare').prop('disabled',false);
            $('.btn-declare-cancel').prop('disabled',false);
        }

        console.log('test');
    }

    if(stat == 'not open'){
        $('.btn-open').show();
        $('.btn-last-call').hide();
        $('.btn-closed').hide();
        $('#btn-declarator button').prop('disabled',false);
        $('.btn-declare').prop('disabled',false);
    }
}


function fillTrends(res){
    clearTrends();
    var currentColumn = 0;
    var currentRow = 0;
    var prev;
    for(let s = 0; s < res.length; s++){
        if(s > 1){
            prev = res[s-1].result;
        }

        if(s==0){ //initial array index
            prev = res[s].result;
            $('#cell'+currentRow+'-'+currentColumn).addClass(prev).text(res[s].fight_number);
        }else{
            if(res[s].result == 'draw' || res[s].result == 'cancelled'){
                currentColumn = currentColumn
                currentRow = currentRow+1;

                if(currentRow > (maxRow-1)){
                    currentColumn = currentColumn+1;
                    currentRow = 0;
                }

            }else if(prev != res[s].result && (res[s].result != 'draw' || res[s].result != 'cancelled')){
                if(currentRow > maxRow){
                    currentColumn = currentColumn+1;
                }
                currentColumn = currentColumn + 1;
                currentRow = 0;
            }else{
                currentRow = currentRow+1;
            }
            $('#cell'+currentRow+'-'+currentColumn).addClass(res[s].result).text(res[s].fight_number);
        }
    }
}

function clearTrends(){
    for (let row = 0; row < maxRow; row++) {
        for (let col = 0; col < 200; col++) {
            $('#cell'+row+'-'+col).removeClass('meron').text('')
            $('#cell'+row+'-'+col).removeClass('wala').text('')
            $('#cell'+row+'-'+col).removeClass('draw').text('')
            $('#cell'+row+'-'+col).removeClass('cancelled').text('')
        }
    }
}
function updateBets(betMeron,betWala,percentMeron,percentWala){
    $('.bets-meron').text(betMeron);
    $('.bets-wala').text(betWala);
    $('.odds-meron').text(percentMeron)
    $('.odds-wala').text(percentWala)
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
                $('.btn-jump-fight').prop('disabled',false);
                // betAmt = randomNumber(1000,5000);
                // let finalbetAmt = parseInt(betAmt)/10;
                // bettingAutoId = setInterval(function(){
                //     autoBet(finalbetAmt)
                // }, 5000);

            }

            if(res.status == "LAST CALL"){
                $('.btn-closed').show();
                $('.btn-jump-fight').prop('disabled',false);
            }

            if(res.status == "CLOSED"){
                clearInterval(bettingAutoId);
                $('.btn-open').show();
                $('.btn-open').prop('disabled',true);
                $('#btn-declarator button').prop('disabled',false);
                $('.btn-jump-fight').prop('disabled',false);

                if(parseFloat(res.percentMeron) <=120 || parseFloat(res.percentWala)<=120){
                    $('.btn-declare').prop('disabled',true);
                    $('.btn-declare-cancel').prop('disabled',false);
                }
            }

        },
        error: function(err){
            console.log(err);
        }
    })
})

$('.btn-declare').on('click',function(){
    let result = $(this).data('value');
    var dis = $(this);
    var data ={
        id : currentFightId,
        result : result,
        event_id : event_id,
        _token : token
    }
    Swal.fire({
        title: 'Declare '+result+' for this fight?',
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
                    updateBets(0,0,0,0);
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


var autoBet = function(betAmount){

    var data = {
        event_id : event_id,
        fight_id : currentFightId ,
        user_id : 12,
        amount : betAmount,
        bet : 'wala',
        _token : token
    }
    var data2 = {
        event_id : event_id,
        fight_id : currentFightId ,
        user_id : 12,
        amount : betAmount,
        bet : 'meron',
        _token : token
    }

    $.ajax({
        type : "POST",
        url : placeBetURL,
        data : data,
        success : function(res){
            console.log(res);
        },
        error : function(err){
            console.log(err);
        }
    })

    $.ajax({
        type : "POST",
        url : placeBetURL,
        data : data2,
        success : function(res){
            console.log(res);
        },
        error : function(err){
            console.log(err);
        }
    })
}

function randomNumber(min, max) {
    return Math.floor(Math.random() * (max - min) + min);
}


fillTrends(results);
checkFightStatus(fightStatus,oddsMeron,oddsWala);
