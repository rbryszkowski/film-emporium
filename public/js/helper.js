

//***************POPULATE TABLE WITH DATA***************

  const populateTable = (data) => {

    //clear previous results
    $('#results_table > tbody').empty();

    console.log('data for table populate: ', data);

    if (!data.length) {

      $('#results_table > tbody').append('<tr><td colspan="6">No results to display</td></tr>');

    } else {

      let tableBody = '';

      data.forEach((film) => {
        tableBody += '<tr>';
        tableBody += '<td>' + film.id + '</td>';
        tableBody += '<td>' + film.title + '</td>';
        tableBody += '<td>' + film.genre + '</td>';
        tableBody += '<td>' + film.description + '</td>';
        tableBody += '</tr>';
      });

      $('#results_table > tbody').append(tableBody);

      //add update and delete buttons to end of rows
      $('#results_table > tbody > tr').append('<td><button class="edit_btn">Edit</button></td><td><button class="delete_btn">Delete</button></td>');

    }

    $('#results_table').show();

    //clear feedback
    $('#edit_table_feedback').html('');

  }

  //***************DISPLAY RESULTS FROM SEARCH FORM***************

  const displaySearchResults = () => {

    let searchParam = $('#select_search_param').val();
    let paramVal = $('#film_search_input').val();
    let endpoint = '/films/' + searchParam + '/' + paramVal;

    if (paramVal === '') {
      console.log('type something in the field before submitting');
      return;
    }

    $.getJSON(endpoint, (data) => {

      populateTable(data);

    });

  }

  //***************DISPLAY ALL FILMS***************

  const displayAll = () => {

    $.getJSON('/films/all', (data) => {

      populateTable(data);

    });

  }

  //***************ADD A FILM TO DB***************

  const addFilm = () => {

    if ( ($('#film_title').val()) === '' ) {
      $('#add_film_feedback').html('You must enter a film title!');
      return;
    }

    $.ajax({

      url: '/films/add',

      type: 'POST',

      dataType: 'json',

      data: {
        title: $('#film_title').val(),
        director: $('#film_director').val(),
        description: $('#film_desc').val()
      },

      success: function(data) {
        console.log('film added to db:', data);
        //add feedback
        $('#add_film_feedback').html("'" + $('#film_title').val() + "'" + ' has been added to your collection!');
      },

      error: function(jqXHR, textStatus, errorThrown) {
        // your error code
        console.log("api response failed!" + " error:" + errorThrown);
        //add feedback
        $('#add_film_feedback').html('film not added, something went wrong!');
      }
    });

  }

  //***************EDIT ROW IN TABLE (NOT DB)***************

  const enterEditMode = (rowToEdit, targetEl) => {

    let classNames = ['table_title_input', 'table_genre_input', 'table_desc_input'];

    $(rowToEdit).children().each( (index, cell) => {
      //select columns 2 to 4 (title, genre, description)
      if (index > 0 && index < 4) {
        //grab current data (is stored in the name attribute of the input so we get the data back if we cancel the edit)
        let currData = $(cell).html();
        $(cell).html(`<input class="${classNames[index-1]}" name="${currData}" value="${currData}">`);
      }
    });

    //render update/delete buttons in edit column
    $(targetEl).parent().html('<button class="update_btn">Update</button>&nbsp;<button class="cancel_edit_btn">X</button>');

    }

    const exitEditMode = (selectedRow, targetEl) => {

      $(selectedRow).children().each( (index, cell) => {
        //select columns 2 to 4 (title, genre, description)
        if (index > 0 && index < 4) {
          //the original data is stored in the name attribute of the input
          let originalVal = $(cell).children().first().attr('name');
          $(cell).html(originalVal);
        }
      });

      //restore edit button
      $(targetEl).parent().html('<button class="edit_btn">Edit</button>');

      //clear feedback
      $('#edit_table_feedback').html('');

      }

      //***************UPDATE ENTRY IN DB***************

    const updateEntry = (selectedRow, targetEl) => {

      let selectedID = $(selectedRow).children().first().html();
      let newData = [];
      let endpoint = '/films/update/' + selectedID;

      $(selectedRow).children().each( (index, cell) => {
        //select columns 2 to 4 (title, genre, description)
        if (index > 0 && index < 4) {
          newData.push($(cell).children().first().val());
        }
      });

      console.log(newData);

      //update the database with the new entry database
      $.ajax({

        url: endpoint,

        type: 'POST',

        dataType: 'json',

        data: {
          newTitle: newData[0],
          newGenre: newData[1],
          newDescription: newData[2]
        },

        success: function(data) {
          console.log('updated film:', data);
          //populate row with new data
          $(selectedRow).children().each( (index, cell) => {
            //select columns 2 to 4 (title, genre, description) (index 1-3)
            if (index > 0 && index < 4) {
              $(cell).html(newData[index-1]);
            }
          });
          //restore edit button
          $(targetEl).parent().html('<button class="edit_btn">Edit</button>');
          //give feedback
          $('#edit_table_feedback').html('update successful!');
        },

        error: function(jqXHR, textStatus, errorThrown) {
          // your error code
          console.log("api response failed!" + " error:" + errorThrown);
          //give feedback
          $('#edit_table_feedback').html('Could not update film, something went wrong!');
        }
      });

    }

    //***************DELETE ENTRY FROM DB***************

    const deleteEntry = (selectedRow, targetEl) => {

      let selectionID = $(targetEl).parent().siblings().first().html();

      let endpoint = '/films/delete/' + selectionID;

      $.ajax({

        url: endpoint,

        type: 'POST',

        dataType: 'json',

        success: function(data) {
          console.log('deleted film:', data);
          //remove table row:
          $(selectedRow).remove();

          if ( !($('#results_table > tbody').html()) ) {
            $('#results_table > tbody').append('<tr><td colspan="6">No results to display</td></tr>');
          }

          //give feedback
          $('#edit_table_feedback').html('delete successful!');

        },

        error: function(jqXHR, textStatus, errorThrown) {
          // your error code
          console.log("api response failed!" + " error:" + errorThrown);
          //give feedback
          $('#edit_table_feedback').html('failed to delete item, something went wrong!');
        }
      });

    }
