    for (let row = 0; row < 8; row++) {
        trs+='<tr>'
            for (let col = 0; col < 100; col++) {
                let rw = '<td><div id="cell'+row+'-'+col+'" class="trend-item bg-disabled"></div></td>'
                trs += rw;
            }
        trs+='</tr>'
    }
    $('.trend-table').append(trs);

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
                }else if(prev != res[s].result && (res[s].result != 'draw' || res[s].result != 'cancelled')){
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
        for (let row = 0; row < 6; row++) {
            for (let col = 0; col < 45; col++) {
                $('#cell'+row+col).removeClass('meron').text('')
                $('#cell'+row+col).removeClass('wala').text('')
                $('#cell'+row+col).removeClass('draw').text('')
                $('#cell'+row+col).removeClass('cancelled').text('')
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


    function updateTeams(team1,team2){
        $('#lblTeamOne').text(team1);
        $('#lblTeamTwo').text(team2);
    }

    function declareWinner(winner,fightNum){
        console.log(winner)
        var win = 'TEAM 1';
        if(winner == 'WALA'){
            var win = 'TEAM 2';
        }

        if(winner == 'CANCELLED'){
            var win = 'CANCELLED';
         }
        $('#fight-status').removeClass('blink')
        $('#fight-status span').text('FINISHED')
        $('.winner').show();
        $('.fight-result').text(win);

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

    function updateBets(betMeron,betWala,percentMeron,percentWala){
        //console.log(isNaN((Math.floor(betMeron*100)/100)) == true ? '0' : (Math.floor(betMeron*100)/100));
        // betMeron = parseFloat(betMeron)+5000;
        // betWala = parseFloat(betWala)+5000;
        let btMrn = parseFloat(betMeron*100)/100;
        let btWla = parseFloat(betWala*100)/100;

        $('#betMeron').text(numberWithCommas(btMrn.toFixed(2)));
        $('#betWala').text(numberWithCommas(btWla.toFixed(2)));
        $('#percentMeron').text((parseFloat(percentMeron*100)/100).toFixed(2));
        $('#percentWala').text((parseFloat(percentWala*100)/100).toFixed(2));
    }



    function updateWalletBalance(bal){
        $('.wallet-balance').val(bal);
    }
    $('.btn-bet-amt').on('click',function(){
        let amt = $(this).data('value');
        $('#input-bet-amount').val(amt);
    })

    $('.btn-lock-bet').on('click',function(){
        let betAmount =  $('#input-bet-amount').val();
        let betMeronWala = $(this).data('bet');
        var data = {
            event_id : eventID,
            fight_id : fightID,
            user_id : userID,
            amount : betAmount,
            bet : betMeronWala,
            _token : token
        }

        if(betAmount >= 5 && betAmount <= 500000000){
            Swal.fire({
                title: 'Place PHP'+betAmount+' bet to '+betMeronWala+'?',
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
              }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type : "POST",
                        url : placeBetURL,
                        data : data,
                        success : function(res){
                            console.log(res);
                            $('.wallet-balance').val(res.balance);
                            Swal.fire('Bet has been placed!','','info');
                            $('#input-bet-amount').val(null);

                            if(betMeronWala == 'meron'){
                                myBetMeron = res.myBetMeron
                                updateBetAmount(betMeronWala,myBetMeron);
                            }

                            if(betMeronWala == 'wala'){
                                myBetWala = res.myBetWala
                                updateBetAmount(betMeronWala,myBetWala);
                            }

                            if(betMeronWala == 'draw'){
                                myBetDraw = res.myBetDraw
                                updateBetAmount(betMeronWala,myBetDraw);
                            }

                            displayBets();


                        },
                        error : function(err){
                            console.log(err)
                            Swal.fire(err.responseJSON.msg,'','info');
                        }
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
    });

    function displayPossibleWinnings(){
        let percentMeron = $('#percentMeron').text();
        let percentWala = $('#percentWala').text();

        let meronBet = myBetMeron;
        let totalMeron = parseFloat(percentMeron)*parseFloat(meronBet);
        $('.meron-wins').text(parseFloat(meronBet).toFixed(2) +'='+ (totalMeron/100).toFixed(2));

        let walaBet = myBetWala;
        let totalWala = parseFloat(percentWala)*parseFloat(walaBet);
        $('.wala-wins').text(parseFloat(walaBet).toFixed(2) +'='+ (totalWala/100).toFixed(2));

        let drawBet = myBetDraw;
        let totalDraw = parseFloat(8)*parseFloat(drawBet);
        $('.draw-winnings').text(parseFloat(drawBet).toFixed(2) +'='+ parseFloat(totalDraw).toFixed(2));

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
        localStorage.setItem('bet-meron',0);
        localStorage.setItem('bet-wala',0);
        localStorage.setItem('bet-draw',0);
    }

    function numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    if(!localStorage.getItem('bet-meron')){
        resetBet();
    }

    $('#fight-number').text(fightNumber);

    updateBets(betMeron,betWala,percentMeron,percentWala);

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
        displayPossibleWinnings();

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

    displayPossibleWinnings();






