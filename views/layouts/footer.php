</main> <footer class="footer mt-auto py-4 bg-white border-top">
    <div class="container text-center">
        <span class="text-muted">
            &copy; <?= date('Y'); ?> <strong>TECNOSIA</strong> - Proyecto de Ingeniería UNAD.
            <br>
            <small>Desarrollado con <i class="fas fa-heart text-danger"></i> en Risaralda, Colombia.</small>
        </span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert-success');
        alerts.forEach(function(alert) {
            let bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>

</body>
</html>