$(document).ready(function () {
  // Initialize table with the first dataset
  //   updateTable();
  $(".dateSelector").flatpickr(optional_config);
});

function updateTable() {
  //   let selectElement = document.getElementById("grid");
  //   let selectedElement = selectElement.value;
  //   $.ajax({
  //     url: `utils/entry.php?tbl=` + selectedElement,
  //     type: "GET",
  //     success: function (response) {
  //       response = JSON.parse(response);
  //       const tableBody = document.getElementById("tableBody");
  //       tableBody.innerHTML = "";
  //       response.data.forEach((row) => {
  //         console.log(row);
  //         const tr = document.createElement("tr");
  //         $(tr).append(
  //           `<td>${row.drawid}</td><td>${row.lottery_name}</td><td>${row.draw_date}</td><td>${row.draw_time}</td><td>${row.draw_number}</td><td>${row.draw_count}</td><td>${row.date_created}</td><td><a href='${row.client}' target='_blank'>Visit Client Site</a></td><td>${row.get_time}</td>`
  //         );
  //         tableBody.appendChild(tr);
  //       });
  //     },
  //     error: function (error) {
  //       console.log("Error: ", error);
  //     },
  //   });
}
