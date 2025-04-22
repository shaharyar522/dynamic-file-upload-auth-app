<?php
include("conn.php");
$sql = "SELECT * FROM tickts WHERE status = '1'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Tickets</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/complete_tiks.css">
    <!-- sweet alert message -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>

    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2>Complete Tickets Details</h2>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <table id="myTable" class="display">
                <thead>
                    <tr>
                        <th>IMAGES</th>
                        <th>PDF</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $id = 1;
                    while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <img src="<?= htmlspecialchars($row['image_path']) ?>" alt="Ticket Image" class="ticket-img">
                            </td>
                            <td>
                                <a href="upload/pdf/<?= htmlspecialchars($row['pdf_path']) ?>" class="btn btn-download" download>
                                    <i class="fas fa-file-pdf me-1"></i> Download PDF
                                </a>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <!-- <a href="edit_ticket.php?id=<?= $row['id'] ?>" class="btn btn-edit">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a> -->
                                    <a href="delete_ticket.php?id=<?= $row['id'] ?>&image=<?= urlencode($row['image_path']) ?>&pdf=<?= urlencode($row['pdf_path']) ?>" class="delete btn btn-sm btn-danger">
                                        <i class="fas fa-trash-alt me-1"></i> Delete
                                    </a>

                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No Completed Tickets Found</h3>
                <p>There are currently no tickets marked as completed.</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="back-btn" style="text-align:center; margin-top: 30px;">
        <a href="dashboard.php" style="
    display: inline-block;
    background-color: #007bff;
    color: #fff;
    padding: 12px 25px;
    border-radius: 8px;
    font-weight: bold;
    text-decoration: none;
    transition: background-color 0.3s, transform 0.2s;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
  " onmouseover="this.style.backgroundColor='#0056b3'; this.style.transform='scale(1.05)';"
            onmouseout="this.style.backgroundColor='#007bff'; this.style.transform='scale(1)';">
            ← Back to Dashboard
        </a>
    </div>






    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
    <!-- Font Awesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <script>
        $(document).ready(function() {
            let table = new DataTable('#myTable', {
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search tickets...",
                    lengthMenu: "Show _MENU_ tickets per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ tickets",
                    infoEmpty: "No tickets available",
                    infoFiltered: "(filtered from _MAX_ total tickets)",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                dom: '<"top"lf>rt<"bottom"ip>',
                pageLength: 10
            });

            // Add animation to table rows
            $('#myTable tbody tr').each(function(i) {
                $(this).css('opacity', '0').delay(i * 100).animate({
                    'opacity': '1'
                }, 200);
            });
        });
    </script>


    <!-- uay hamray pass jab recod delet hn jain ga  -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
    const deleteButtons = document.querySelectorAll(".delete");

    deleteButtons.forEach(button => {
        button.addEventListener("click", function (e) {
            e.preventDefault();
            const href = this.getAttribute("href");

            Swal.fire({
                title: 'Are you sure you want to  delete this record?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href + "&confirmed=1";
                }
            });
        });
    });

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('deleted') === '1') {
        Swal.fire({
            icon: 'success',
            title: 'Deleted!',
            text: 'The record has been successfully Deleted.',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            const url = new URL(window.location);
            url.searchParams.delete('deleted');
            window.history.replaceState(null, null, url);
        });
    }
});

    </script>




</body>

</html>