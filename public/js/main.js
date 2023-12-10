$(document).ready(function () {

    $('.data-table').DataTable({
        order: [[2, 'desc']],
    });

    $('form').on('submit', function() {
        $(this).find(":submit").prop('disabled', true);
    });

    $('.table').on('click','.btn-deposit',function(){
        let dis = $(this);
        let selectedUserID = dis.data('id')
        $('#wallet_user_id').val(selectedUserID);
        $('#depositAmountModal').modal('show');
    })

    $('table').on('click','.btn-commission',function(){
        let dis = $(this);
        let selectedUserID = dis.data('id');
        $('#commission_user_id').val(selectedUserID);
        $('#commissionPercentModal').modal('show');
    })

    $('.table').on('click','.btn-return',function(){
        let user_id = $(this).data('id');
        $('#userIdRet').val(user_id);
        $('#returnCreditsModal').modal('show');
    })

    $('.table').on('click','.btn-comm',function(){
        let user_id = $(this).data('id');
        $('#userIdComm').val(user_id);
        $('#withdrawCommModal').modal('show');
    })

    $('.table').on('click','.btn-change-status',function(){
        let dis = $(this);
        let id = $(this).data('id');
        let status = $(this).data('status');

        let formData = {
            id : id,
            status : status,
            _token : token
        }
        Swal.fire({
            title: "Are you sure you want to do this?",
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type : 'POST',
                    url : statusUpdateURL,
                    data : formData,
                    success : function(res){
                        Swal.fire(
                            res.msg,
                            '',
                            'success'
                        );
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    },
                    error : function(err){
                        console.log(err)
                    }
                })
            }
        })
    })


    $('.table').on('click','.btn-convert',function(){
        let id = $(this).data('id');
        let formData = {
            id : id,
            _token : token
        }

        Swal.fire({
            title: "Are you sure you want to convert this player to agent?",
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type : 'POST',
                    url : convertURL,
                    data : formData,
                    success : function(res){
                        Swal.fire(
                            res.msg,
                            '',
                            'success'
                        );
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    },
                    error : function(err){
                        console.log(err)
                    }
                })
            }
        })
    })

    $('table').on('click','.btn-delete',function(){
        let id = $(this).data('id');
        let deleteURL = $(this).data('url');
        console.log(deleteURL);
        let formData = {
            id : id,
            _token : token
        }

        Swal.fire({
            title: "Are you sure you want to delete this user?",
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type : 'POST',
                    url : deleteURL,
                    data : formData,
                    success : function(res){
                        Swal.fire(
                            res.msg,
                            '',
                            'success'
                        );
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    },
                    error : function(err){
                        console.log(err)
                    }
                })
            }
        })
    })

    $('#btn-search').click(function(){
        let url = $(this).data('url');
        let keyword = $('#username-search').val();

        window.location.href = url+'/'+keyword;
    })

    $('.table').on('click','.btn-accsumm',function(){
        let url = $(this).data('url');
        $.ajax({
            type : 'GET',
            url : url,
            success : function(res){
               $('#asCurrentPoints').text(res.currentPoints)
               $('#asCurrentCommi').text(res.currentCommi)
               $('#asTotalCashin').text(res.totalCashin)
               $('#asTotalCashout').text(res.totalCashOut)
               $('#asDownlinePoints').text(res.downLinesPoints)
               $('#asPlayerWithdraw').text(res.playerWithdraw)
               $('#asPlayerDeposit').text(res.playerDeposit)
               $('#asDownlineCommi').text(res.downLinesCommi)
               $('#asCommiCashout').text(res.totalCommiOut)
               $('#asTotalCommi').text(res.totalCommi)
               $('#asTotalCTW').text(res.totalCTW)
               $('#accountSummaryModal').modal('show');
            },
            error : function(err){
                console.log(err)
            }
        })
    })
});
