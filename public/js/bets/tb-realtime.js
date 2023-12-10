Echo.channel('betting-status-'+eventIDString).listen('Bet', function(data) {

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


Echo.channel('place-bet-'+eventIDString).listen('PlaceBet', function(data) {
    updateBets(data.betData.betMeron,data.betData.betWala,data.betData.percentMeron,data.betData.percentWala);
});


Echo.channel('event-status-'+eventIDString).listen('EventStatus', function(data) {
    if(data.status == "open"){
        location.reload();
    }
});


Echo.channel('declare-winner-'+eventIDString).listen('DeclareWinner', function(data) {
    console.log(data.declareData);
    fightID = data.declareData.fight_id;
    fightNumber = data.declareData.fight_number;
    declareWinner(data.declareData.result,fightNumber);
    fillTrends(data.declareData.wins)
    updateTeams(data.declareData.team_1,data.declareData.team_2);
    localStorage.setItem('bet-meron',0);
    localStorage.setItem('bet-wala',0);
    localStorage.setItem('bet-draw',0);
    myBetMeron = 0;
    myBetWala = 0;
    myBetDraw = 0;
});

Echo.channel('send-notification-'+eventIDString).listen('SendNotification', function(data) {
    console.log(data);
    if(userID == data.notifData.user_id){
        updateWalletBalance(data.notifData.balance);
        resetBet();
    }
});


Echo.channel('jump-number-'+eventIDString).listen('JumpFight', function(data) {
    $('#fight-number').text(data.fightNumber);
});

Echo.channel('change-team-'+eventIDString).listen('ChangeTeam', function(data) {
    updateTeams(data.teamData.team_1,data.teamData.team_2);
});


