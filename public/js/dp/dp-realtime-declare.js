Echo.channel('all-bets-'+eventIdString)
.listen('AllBets', function(data) {
    fillBetsTable(data.allbets);
});


Echo.channel('place-bet-'+eventIdString)
.listen('PlaceBet', function(data) {
    console.log(data);
    updateBets(data.betData.betBlue,data.betData.betGrey,data.betData.betRed,data.betData.betYellow,data.betData.betWhite,data.betData.betPink);
});
