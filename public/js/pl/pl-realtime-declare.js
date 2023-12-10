Echo.channel('all-bets-'+eventIdString)
.listen('AllBets', function(data) {
    fillBetsTable(data.allbets);
});


Echo.channel('place-bet-'+eventIdString)
.listen('PlaceBet', function(data) {
    console.log(data.betData);
    updateBets(data.betData.betRed,data.betData.betBlue,data.betData.betYellow,data.betData.betWhite);
});
