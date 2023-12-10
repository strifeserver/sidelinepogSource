var pusher = new Pusher('c4e5937c6f9df63b7de4', {
    cluster: 'ap1'
});



var channelStatus = pusher.subscribe('betting-status-'+eventIDString);

channelStatus.bind('betting-status-'+eventIDString,function(data) {
    console.log(data);
    if(data.status == "OPEN"){
        openBet();
    }

    if(data.status == "LAST CALL"){
        lastCall();
    }

    if(data.status == "CLOSED"){
        closeBet();
        displayPossibleWinnings();
    }
});

var channelBet = pusher.subscribe('place-bet-'+eventIDString);

channelBet.bind('place-bet-'+eventIDString, function(data) {
    console.log(data);
    updateBets(data.betData.betMeron,data.betData.betWala,data.betData.betDraw,data.betData.percentMeron,data.betData.percentWala);
});


var channelEvent = pusher.subscribe('event-status-'+eventIDString);
channelEvent.bind('event-status-'+eventIDString, function(data) {
    if(data.status == "open"){
        location.reload();
    }
});


var channelDeclare = pusher.subscribe('declare-winner-'+eventIDString);

channelDeclare.bind('declare-winner-'+eventIDString, function(data) {
    console.log(data.declareData);
    fightID = data.declareData.fight_id;
    fightNumber = data.declareData.fight_number;
    declareWinner(data.declareData.result,fightNumber);
    fillTrends(data.declareData.wins)
    results = data.declareData.wins;
    localStorage.setItem('bet-meron',0);
    localStorage.setItem('bet-wala',0);
    localStorage.setItem('bet-draw',0);
    myBetMeron = 0;
    myBetWala = 0;
    myBetDraw = 0;

    getLastWinningResult();
    countCancelWins();
    countDrawWins();
    countWalaWins();
    countMeronWins();
});


var channelNotif = pusher.subscribe('send-notification-'+eventIDString);

channelNotif.bind('send-notification-'+eventIDString, function(data) {
    console.log(data);
    $.each(data.notifData,function(i,v){
        console.log(v);
        if(userID == v.user_id){
            updateWalletBalance(v.balance);
            resetBet();
        }
    })

});


var channelJump = pusher.subscribe('jump-number-'+eventIDString);
channelJump.bind('jump-number-'+eventIDString , function(data) {
    console.log(data);
    $('#fight-number').text(data.fightNumber);
});

