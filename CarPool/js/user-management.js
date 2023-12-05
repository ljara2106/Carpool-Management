$(document).ready(function () {
  // call fetchData function
  fetchData();

  //initialize datatables
  let table = new DataTable("#myTable");

  // function to fetch data from database
  function fetchData() {
    $.ajax({
      url: "/../user-management-actions.php?action=fetchData",
      type: "POST",
      dataType: "json",
      success: function (response) {
        var data = response.data;
        table.clear().draw();
        $.each(data, function (index, value) {
          table.row
            .add([
              value.id,
              value.teacher_id,
              value.name,
              value.email,
              '<Button type="button" class="btn editBtn" value="' +
                value.id +
                '"><i class="fa-solid fa-pen-to-square fa-xl"></i></Button>' +
                '<Button type="button" class="btn deleteBtn" value="' +
                value.id +
                '"><i class="fa-solid fa-trash fa-xl"></i></Button>',
            ])
            .draw(false);
        });
      },
    });
  }

  // function to insert data to database
  $("#insertForm").on("submit", function (e) {
    $("#insertBtn").attr("disabled", "disabled");
    e.preventDefault();
    $.ajax({
      url: "/../user-management-actions.php?action=insertData",
      type: "POST",
      data: new FormData(this),
      contentType: false,
      cache: false,
      processData: false,
      success: function (response) {
        var response = JSON.parse(response);
        if (response.statusCode == 200) {
          $("#offcanvasAddUser").offcanvas("hide");
          $("#insertBtn").removeAttr("disabled");
          $("#insertForm")[0].reset();
          //$(".preview_img").attr("src", "images/default_profile.jpg");
          $("#successToast").toast("show");
          $("#successMsg").html(response.message);
          fetchData();

          // Delay the page reload by 1 second (adjust as needed)
          setTimeout(function () {
            location.reload();
          }, 1000);
        } else if (response.statusCode == 500) {
          $("#offcanvasAddUser").offcanvas("hide");
          $("#insertBtn").removeAttr("disabled");
          $("#insertForm")[0].reset();
          //$(".preview_img").attr("src", "images/default_profile.jpg");
          $("#errorToast").toast("show");
          $("#errorMsg").html(response.message);
        } else if (response.statusCode == 400) {
          $("#insertBtn").removeAttr("disabled");
          $("#errorToast").toast("show");
          $("#errorMsg").html(response.message);
        }
      },
    });
  });

  // function to edit data
  $("#myTable").on("click", ".editBtn", function () {
    var id = $(this).val();
    $.ajax({
      url: "/../user-management-actions.php?action=fetchSingle",
      type: "POST",
      dataType: "json",
      data: {
        id: id,
      },
      success: function (response) {
        //console.log(response);
        var data = response.data;
        $("#editForm #id").val(data.id);
        $("#editForm input[name='teacher_id']").val(data.teacher_id);
        $("#editForm input[name='name']").val(data.name);
        $("#editForm input[name='email']").val(data.email);
        $("#editForm input[name='password']").val(data.password);
        // show the edit user offcanvas
        $("#offcanvasEditUser").offcanvas("show");
      },
    });
  });

  // function to update data in database
  $("#editForm").on("submit", function (e) {
    $("#editBtn").attr("disabled", "disabled");
    e.preventDefault();
    $.ajax({
      url: "/../user-management-actions.php?action=updateData",
      type: "POST",
      data: new FormData(this),
      contentType: false,
      cache: false,
      processData: false,
      success: function (response) {
        var response = JSON.parse(response);
        if (response.statusCode == 200) {
          $("#offcanvasEditUser").offcanvas("hide");
          $("#editBtn").removeAttr("disabled");
          $("#editForm")[0].reset();
          $("#successToast").toast("show");
          $("#successMsg").html(response.message);
          fetchData();
        } else if (response.statusCode == 500) {
          $("#offcanvasEditUser").offcanvas("hide");
          $("#editBtn").removeAttr("disabled");
          $("#editForm")[0].reset();
          $("#errorToast").toast("show");
          $("#errorMsg").html(response.message);
        } else if (response.statusCode == 400) {
          $("#editBtn").removeAttr("disabled");
          $("#errorToast").toast("show");
          $("#errorMsg").html(response.message);
        }
      },
    });
  });

  // function to delete data
  $("#myTable").on("click", ".deleteBtn", function () {
    if (confirm("Are you sure you want to delete this user?")) {
      var id = $(this).val();
      $.ajax({
        url: "/../user-management-actions.php?action=deleteData",
        type: "POST",
        dataType: "json",
        data: {
          id,
        },
        success: function (response) {
          if (response.statusCode == 200) {
            fetchData();
            $("#successToast").toast("show");
            $("#successMsg").html(response.message);
          } else if (response.statusCode == 500) {
            $("#errorToast").toast("show");
            $("#errorMsg").html(response.message);
          }
        },
      });
    }
  });
});
