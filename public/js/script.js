$(document).ready( () => {

    $('.accordion-button').click(() => {
        $('.accordion-collapse').toggleClass('show');
    });

    //film actions
    $('.delete-film').on('click',  () => {
        let endPoint = '/films/delete/' + $('.delete-film').attr('id');
        $.ajax({
            url: endPoint,
            type: 'DELETE',
            success: function(response) {
                $('body').html(response);
            }
        })
    });

    $('.delete-all-films').on('click',  () => {
        let endPoint = '/films/clear';
        $.ajax({
            url: endPoint,
            type: 'DELETE',
            success: function(response) {
                $('body').html(response);
            }
        })
    });

    //genre actions
    $('.delete-genre').on('click',  () => {
        let endPoint = '/genres/delete/' + $('.delete-genre').attr('id');
        $.ajax({
            url: endPoint,
            type: 'DELETE',
            success: function(response) {
              $('body').html(response);
            }
        })
    });

    $('.delete-all-genres').on('click',  () => {
        let endPoint = '/genres/clear';
        $.ajax({
            url: endPoint,
            type: 'DELETE',
            success: function(response) {
                $('body').html(response);
            }
        })
    });

    //director actions
    $('.delete-director').on('click',  () => {
        let endPoint = '/directors/delete/' + $('.delete-director').attr('id');
        $.ajax({
            url: endPoint,
            type: 'DELETE',
            success: function(response) {
                $('body').html(response);
            }
        })
    });

    $('.delete-all-directors').on('click',  () => {
        let endPoint = '/directors/clear';
        $.ajax({
            url: endPoint,
            type: 'DELETE',
            success: function(response) {
                $('body').html(response);
            }
        })
    });

});