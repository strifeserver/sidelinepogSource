Echo.channel('all-bets-'+eventIDString)
.listen('AllBets', function(data) {
    fillBetsTable(data.allbets);
});


Echo.channel('place-bet-'+eventIDString)
.listen('PlaceBet', function(data) {
    updateBets(data.betData.betMeron,data.betData.betWala);
});
