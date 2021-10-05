$(document).ready( () => {

    $('.accordion-button').click(() => {
        $('.accordion-collapse').toggleClass('show');
    });

    //film actions
    $('.delete-film').on('click',  (e) => {
        let endPoint = '/films/delete/' + $(e.target).attr('id');
        console.log(endPoint);
        $.ajax({
            url: endPoint,
            type: 'DELETE',
            success: function(response) {
                location.reload();
            }
        })
    });

    $('.delete-all-films').on('click',  () => {
        let endPoint = '/films/clear';
        $.ajax({
            url: endPoint,
            type: 'DELETE',
            success: function(response) {
                location.reload();
            }
        })
    });

    //genre actions
    $('.delete-genre').on('click',  (e) => {
        let endPoint = '/genres/delete/' + $(e.target).attr('id');
        $.ajax({
            url: endPoint,
            type: 'DELETE',
            success: function(response) {
                location.reload();
            }
        })
    });

    $('.delete-all-genres').on('click',  () => {
        let endPoint = '/genres/clear';
        $.ajax({
            url: endPoint,
            type: 'DELETE',
            success: function(response) {
                location.reload();
            }
        })
    });

    //director actions
    $('.delete-director').on('click',  (e) => {
        let endPoint = '/directors/delete/' + $(e.target).attr('id');
        $.ajax({
            url: endPoint,
            type: 'DELETE',
            success: function(response, textStatus, jqXHR) {
                location.reload();
            },
            failure: function(error) {
                console.log(error);
            }
        })
    });

    $('.delete-all-directors').on('click',  () => {
        let endPoint = '/directors/clear';
        $.ajax({
            url: endPoint,
            type: 'DELETE',
            success: function(response) {
                location.reload();
            },
            failure: function(error) {
                console.log(error);
            }
        })
    });

});