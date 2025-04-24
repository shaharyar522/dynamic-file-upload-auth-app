<?php
include("conn.php");

// Pagination setup
$limit = 1; // Records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get limited records
$sql = "SELECT * FROM tickts WHERE status = '1' AND pdf_path IS NOT NULL LIMIT $limit OFFSET $offset";

$result = $conn->query($sql);

// Get total records count for pagination
$total_result = $conn->query("SELECT COUNT(*) as total FROM tickts WHERE status = '1'");
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Complete Tickets</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/complete_tiks.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/navbar.css">
 
    <style>
        .ticket-img {
            max-width: 100px;
            height: auto;
        }
        .pagination .btn {
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>


<!-- navabar -->
<nav class="navbar">
        <div class="navbar-brand">
            <h1>Ticketing Dashboard</h1>
            <div class="nav-item">
                <a href="#" class="nav-link">Tickets Detail</a>
                <div class="dropdown-menu">
                    <a href="complete_tickets.php" class="dropdown-item">Complete Tickets</a>
                    <a href="pending_tickets.php" class="dropdown-item">Pending Tickets</a>
                </div>
            </div>
        </div>
        </div>
        <div class="btn-group">
            <button class="btn btn-outline" onclick="openPasswordModal()">Update Password</button>
            <button class="btn btn-danger" onclick="window.location.href='logout.php'">Logout</button>
        </div>
    </nav>

    <script>
        function openPasswordModal() {
            // Modal logic here
        }
    </script>
    <!-- uay navbar main pasword update ka modal ki js hian  -->
     
<!-- End with navabar -->
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
                <?php while ($row = $result->fetch_assoc()): ?>
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
                            <a href="delete_ticket.php?id=<?= $row['id'] ?>&image=<?= urlencode($row['image_path']) ?>&pdf=<?= urlencode($row['pdf_path']) ?>" class="delete btn btn-sm btn-danger">
                                <i class="fas fa-trash-alt me-1"></i> Delete
                            </a>
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

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <div class="pagination text-center mt-4">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>" class="btn <?= ($i == $page) ? 'btn-primary' : 'btn-outline-primary' ?> mx-1">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Back to Dashboard Button -->
<div class="back-btn text-center mt-5">
    <a href="dashboard.php" style="
        display: inline-block;
        background-color: #007bff;
        color: #fff;
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: bold;
        text-decoration: none;
        transition: background-color 0.3s, transform 0.2s;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);"
        onmouseover="this.style.backgroundColor='#0056b3'; this.style.transform='scale(1.05)';"
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
<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

<!-- Disable DataTables Pagination -->
<script>
    $(document).ready(function () {
        new DataTable('#myTable', {
            paging: false,
            responsive: true,
            language: {
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
            dom: '<"top"l>rt<"bottom"ip>' // removed "f"
        });
    });
</script>


<!-- Delete Confirmation  message agr wo delete hnva kya to us ko uay message show-->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const deleteButtons = document.querySelectorAll(".delete");

        deleteButtons.forEach(button => {
            button.addEventListener("click", function (e) {
                e.preventDefault();
                const href = this.getAttribute("href");

                Swal.fire({
                    title: 'Are you sure you want to delete this record?',
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
                text: 'The record has been successfully deleted.',
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
