<div class="sidebar flex-column flex-shrink-0">
    <a href="/" class="logo">
        <img class="img-fluid" src="../img/logo1.png" alt="" srcset="">
    </a>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="side-nav-title">Home</li>
        <li class="nav-item">
            <a href="../admin/dashboard" id="dashboard-link" class="nav-link">
                <i class="bi bi-speedometer2"></i>
                <span class="fs-6 ms-2">Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-cart"></i>
                <span class="fs-6 ms-2">Orders</span>
            </a>
        </li>
        <li class="side-nav-title">Reports</li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-graph-up"></i>
                <span class="fs-6 ms-2">Sales</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="bi bi-file-earmark-bar-graph"></i>
                <span class="fs-6 ms-2">Reports</span>
            </a>
        </li>
        <li class="side-nav-title">Settings</li>
        <li class="nav-item">
            <a href="../admin/products" id="products-link" class="nav-link">
                <i class="bi bi-box"></i>
                <span class="fs-6 ms-2">Product</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="../admin/accounts" id="accounts-link" class="nav-link">
                <i class="bi bi-people"></i>
                <span class="fs-6 ms-2">Accounts</span>
            </a>
        </li>
    </ul>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all nav links in the sidebar
    const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');
    
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Only handle links that have an href (not "#")
            if (this.getAttribute('href') !== '#') {
                e.preventDefault(); // Prevent default link behavior
                const url = this.getAttribute('href');
                // Force page reload to the new URL
                window.location.href = url;
            }
        });
    });
});
</script>
