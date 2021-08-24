
$(document).ready(() => {

  //FILM SEARCH BUTTON EVENT

  $('#film_search_btn').click(displaySearchResults);

  //SHOW ALL FILMs BUTTON EVENT

  $('#show_all_btn').click(displayAll);

  //ADD FILM BUTTON EVENT

  $('#add_film_btn').click(addFilm);

  //DELETE BUTTON EVENT

  $(document).on('click', '.delete_btn', (e) => {

    console.log('delete');
    let targetEl = e.target;
    let selectedRow = $(targetEl).closest('tr');
    deleteEntry(selectedRow, targetEl);

  });

  //EDIT BUTTON EVENT

  $(document).on('click', '.edit_btn', (e) => {

    console.log('edit');
    let targetEl = e.target;
    let selectedRow = $(targetEl).closest('tr');
    enterEditMode(selectedRow, targetEl);

  });

  //CANCEL EDIT BUTTON EVENT

  $(document).on('click', '.cancel_edit_btn', (e) => {

    console.log('edit cancelled');
    let targetEl = e.target;
    let selectedRow = $(targetEl).closest('tr');
    exitEditMode(selectedRow, targetEl);

  });

  //UPDATE BUTTON EVENT

  $(document).on('click', '.update_btn', (e) => {

    console.log('update');
    let targetEl = e.target;
    let selectedRow = $(targetEl).closest('tr');
    updateEntry(selectedRow, targetEl);

  });

});

//CLEAR BUTTON EVENT

$('#clear_btn').click( () => {
  $('#film_title').val('');
  $('#film_genre').val('');
  $('#film_desc').val('');
})
