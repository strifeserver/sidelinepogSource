var pusher = new Pusher('c4e5937c6f9df63b7de4', {
    cluster: 'ap1'
});

var channelBets = pusher.subscribe('all-bets-'+eventIDString);
channelBets.bind('all-bets-'+eventIDString, function(data) {
    fillBetsTable(data.allbets);
});



var channelPlaceBet = pusher.subscribe('place-bet-'+eventIDString);
channelPlaceBet.bind('place-bet-'+eventIDString, function(data) {
    console.log(data)
    updateBets(data.betData.betMeron,data.betData.betWala,data.betData.percentMeron,data.betData.percentWala);
});


var channelStatus = pusher.subscribe('betting-status-'+eventIDString);

channelStatus.bind('betting-status-'+eventIDString, function(data) {
    checkFightStatus(data.status.toLowerCase(),data.oddsMeron,data.oddsWala)
});
