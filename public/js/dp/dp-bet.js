for (let row = 0; row < 4; row++) {
    trs+='<tr>'
        for (let col = 0; col < 500; col++) {
            let rw = '<td><div id="cell'+row+'-'+col+'" class="trend-item bg-disabled"></div></td>'
            trs += rw;
        }
    trs+='</tr>'
}
$('.trend-table').append(trs);

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
                $('#cell'+x+'-'+s).addClass(convertoString(convertResult(colors[c]))).text(convertResult(colors[c]));
                c++;
            }

        }
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

function convertoString(num){
    if(num == '10'){
        return 'ten-w';
    }else if(num == '9'){
        return 'nine-w';
    }else{
        return num;
    }
}

function clearTrends(){
    for (let row = 0; row < 4; row++) {
        for (let col = 0; col < 500; col++) {
            $('#cell'+row+col).removeClass('blue').text('')
            $('#cell'+row+col).removeClass('grey').text('')
            $('#cell'+row+col).removeClass('red').text('')
            $('#cell'+row+col).removeClass('yellow').text('')
            $('#cell'+row+col).removeClass('white').text('')
            $('#cell'+row+col).removeClass('pink').text('')
        }
    }
}


function countOccurence(arraySet,stringToFind){
    var count = arraySet.reduce(function(n, val) {
        return n + (val === stringToFind);
    }, 0);

    return count;
}

function openBet(){
    $('#fight-status').removeClass('text-warning')
    $('#fight-status').addClass('text-white')
    $('#fight-status span').text('OPEN')
    $('.bet-btn').show();
    $('#input-bet-amount').show();
    $('.winner').hide();
    $('.status-fight-closed').hide();
    $('.status-fight-last-call').hide();
    $('.status-fight-not-open').hide();
    $('.status-fight-open').show();
}

function lastCall(){
    $('#fight-status').removeClass('text-success')
    $('#fight-status').addClass('text-white')
    $('#fight-status span').text('LAST CALL')
    $('.status-fight-closed').hide();
    $('.status-fight-not-open').hide();
    $('.status-fight-last-call').show();
    $('.status-fight-open').hide();
}

function closeBet(){
    $('#fight-status').removeClass('text-warning')
    $('#fight-status').addClass('text-white')
    $('#fight-status span').text('CLOSED')
    $('.bet-btn').hide();
    $('#input-bet-amount').hide();
    $('.status-fight-closed').show();
    $('.status-fight-not-open').hide();
    $('.status-fight-last-call').hide();
    $('.status-fight-open').hide();

}

function declareWinner(winner,fightNum){
    $('#fight-status').removeClass('blink')
    $('#fight-status span').text('FINISHED')
    $('.winner').show();
    $('.fight-result').text(winner);
    resetBet();
    if(winner == 'MERON'){
        $('.fight-result').css('color','#dc3545')
    }
    if(winner == 'WALA'){
        $('.fight-result').css('color','#007bff')
    }

    if(winner == 'DRAW'){
        $('.fight-result').css('color','#ffc107')
    }

    if(winner == 'CANCELLED'){
        $('.fight-result').css('color','#ffffff')
    }

    setTimeout(() => {
        $('#fight-status').removeClass('text-danger')
        $('#fight-status').addClass('text-warning')
        $('#fight-status span').text('NOT OPEN')
        $('.winner').hide();
        $('#input-bet-amount').val(null);
        $('#fight-number').text(fightNum)
        betMeron = 0.00;
        betWala = 0.00;
        percentWala = 0.00;
        percentMeron = 0.00;
        updateBets(betMeron,betWala,percentMeron,percentWala);
        $('.winnings').text('')
        $('.status-fight-not-open').show();
        $('.status-fight-closed').hide();
    }, 5000);
}

function numberWithCommas(n) {
    var parts=n.toString().split(".");
    return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (parts[1] ? "." + parts[1] : "");
}

function updateBets(betBlue,betGrey,betRed,betYellow,betWhite,betPink){
    $('.bets-blue').text(numberWithCommas(betBlue.toFixed(2)));
    $('.bets-grey').text(numberWithCommas(betGrey.toFixed(2)));
    $('.bets-red').text(numberWithCommas(betRed.toFixed(2)));
    $('.bets-yellow').text(numberWithCommas(betYellow.toFixed(2)));
    $('.bets-white').text(numberWithCommas(betWhite.toFixed(2)));
    $('.bets-pink').text(numberWithCommas(betPink.toFixed(2)));
}



function updateWalletBalance(bal){
    $('.wallet-balance').val(bal);
}

$('.btn-clear').on('click',function(){
    $('#input-bet-amount').val(0);
})

$('.btn-bet-amt').on('click',function(){
    let amt = parseFloat($(this).data('value'));
    $('#input-bet-amount').val(amt);
})

$('.btn-lock-bet').on('click',function(){
    let amount = $('#input-bet-amount').val();
    let bet = $(this).data('bet');

    placeBet(betUrl,eventID,fightID,amount,bet)
})

function placeBet(url,eventId,fightId,amount,bet){
    let postData = {
        event_id : eventId,
        fight_id : fightId,
        amount : amount,
        bet : bet,
        _token : token
    }
    let option = {
        url : url,
        type : 'post',
        data : postData,
    }
    if(amount >= 5 && amount <= 500000000){
        Swal.fire({
            title: 'Place PHP'+amount+' bet to '+bet+'?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
          }).then((result) => {
            if (result.isConfirmed) {
                $.ajax(option)
                .done(function(res){
                    Swal.fire(
                        res.msg,
                        '',
                        'info'
                    );
                    $('.wallet-balance').val(res.balance);
                    $('.my-bet-blue').text(parseFloat(res.myBetBlue).toFixed(2));
                    $('.my-bet-grey').text(parseFloat(res.myBetGrey).toFixed(2));
                    $('.my-bet-red').text(parseFloat(res.myBetRed).toFixed(2));
                    $('.my-bet-yellow').text(parseFloat(res.myBetYellow).toFixed(2));
                    $('.my-bet-white').text(parseFloat(res.myBetWhite).toFixed(2));
                    $('.my-bet-pink').text(parseFloat(res.myBetPink).toFixed(2));

                    $('#betBlue').text(parseFloat(res.betBlue).toFixed(2));
                    $('#betGrey').text(parseFloat(res.betGrey).toFixed(2));
                    $('#betRed').text(parseFloat(res.betRed).toFixed(2));
                    $('#betYellow').text(parseFloat(res.betYellow).toFixed(2));
                    $('#betWhite').text(parseFloat(res.betWhite).toFixed(2));
                    $('#betPink').text(parseFloat(res.betPink).toFixed(2));
                })
                .fail(function(err){
                    console.log(err)
                })
            }
        })
    }else{
        Swal.fire(
            'Bet amount out of range!',
            'Minimum: PHP5',
            'info'
        );
    }
}

function updateBetAmount(bet,amount){
    if(bet == 'meron'){
        let currentBet = myBetMeron;
        let totalBet = currentBet+parseFloat(amount);
        localStorage.setItem('bet-meron',totalBet);
    }

    if(bet == 'wala'){
        let currentBet = myBetWala;
        let totalBet = currentBet+parseFloat(amount);
        localStorage.setItem('bet-wala',totalBet);
    }

    if(bet == 'draw'){
        let currentBet = myBetDraw;
        let totalBet = currentBet+parseFloat(amount);
        localStorage.setItem('bet-draw',totalBet);
    }
}

function resetBet(){
    myBetBlue = 0;
    myBetGrey = 0;
    myBetRed = 0;
    myBetYellow = 0;
    myBetWhite = 0;
    myBetPink = 0;
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

if(!localStorage.getItem('bet-meron')){
    resetBet();
}

function clearBets(){
    $('.my-bet-blue').text(parseFloat(myBetBlue).toFixed(2));
    $('.my-bet-grey').text(parseFloat(myBetGrey).toFixed(2));
    $('.my-bet-red').text(parseFloat(myBetRed).toFixed(2));
    $('.my-bet-yellow').text(parseFloat(myBetYellow).toFixed(2));
    $('.my-bet-white').text(parseFloat(myBetWhite).toFixed(2));
    $('.my-bet-pink').text(parseFloat(myBetPink).toFixed(2));

    $('#betBlue').text(parseFloat(0).toFixed(2));
    $('#betGrey').text(parseFloat(0).toFixed(2));
    $('#betRed').text(parseFloat(0).toFixed(2));
    $('#betYellow').text(parseFloat(0).toFixed(2));
    $('#betWhite').text(parseFloat(0).toFixed(2));
    $('#betPink').text(parseFloat(0).toFixed(2));
}
$('#fight-number').text(fightNumber);


fillTrends(results);

function displayBets(){
    let betM = myBetMeron;
    let betW = myBetWala;
    let betD = myBetDraw;

    $('.meron-wins').text(parseFloat(betM).toFixed(2));
    $('.wala-wins').text(parseFloat(betW).toFixed(2));
    $('.draw-winnings').text(parseFloat(betD).toFixed(2));
}

if(fightStatus == 'open' || fightStatus == 'last call'){
    $('.bet-btn').show();

}

if(fightStatus == 'closed'){
    displayBets();
    $('.status-fight-closed').show();
    $('.status-fight-open').hide();
    $('.status-fight-last-call').hide();
    $('.status-fight-not-open').hide();
    //displayPossibleWinnings();

}

if(fightStatus == 'open'){
    $('.status-fight-closed').hide();
    $('.status-fight-open').show();
    $('.status-fight-last-call').hide();
    $('.status-fight-not-open').hide();
}

if(fightStatus == 'last call'){
    $('.status-fight-closed').hide();
    $('.status-fight-open').hide();
    $('.status-fight-last-call').show();
    $('.status-fight-not-open').hide();
}

if(fightStatus == 'not_open'){
    $('.status-fight-closed').hide();
    $('.status-fight-open').hide();
    $('.status-fight-last-call').hide();
    $('.status-fight-not-open').show();
}

resetBet();

// displayPossibleWinnings();
