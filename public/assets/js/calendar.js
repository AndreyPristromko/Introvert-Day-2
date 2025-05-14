$(document).ready(function() {
    var today = new Date();
    today.setHours(0, 0, 0, 0);
    
    var maxDate = new Date(today);
    maxDate.setDate(today.getDate() + 30);
    maxDate.setHours(23, 59, 59, 999);
    var activeDates = {};

    function checkDateAvailability() {
        var currentTimestamp = Math.floor(Date.now() / 1000);
        
        $.ajax({
            url: '/api/check',
            method: 'GET',
            data: { date: currentTimestamp },
            success: function(response) {
                if (response.success) {
                    console.log('Получены даты:', response.data);
                    activeDates = response.data;
                    var dates = [];
                    for (var timestamp in activeDates) {
                        dates.push(new Date(timestamp * 1000));
                    }
                    
                    $('#booking-date').glDatePicker({
                        showAlways: true,
                        cssName: 'flatwhite',
                        dowOffset: 1,
                        onClick: function(target, cell, date) {
                            var timestamp = Math.floor(date.getTime() / 1000);
                            if (activeDates[timestamp]) {
                                target.val(date.toISOString().split('T')[0]);
                            }
                        },
                        specialDates: dates.map(function(date) {
                            return {
                                date: date,
                                cssClass: 'special'
                            };
                        })
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Ошибка запроса:', error);
            }
        });
    }

    checkDateAvailability();
});