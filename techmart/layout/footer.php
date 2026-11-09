</main>

        <footer>
            <p>&copy; <?= date('Y') ?> TECHMART. All Rights Reserved.</p>
            <p>Your Needs, Our Technology.</p>
        </footer>

    </div>
</div>

<!-- Lapisan gelap saat menu HP dibuka -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Script untuk membuka dan menutup sidebar -->
<script>
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (menuToggle && sidebar && overlay) {

        menuToggle.addEventListener('click', function () {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });

        overlay.addEventListener('click', function () {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });

    }
</script>

</body>
</html>