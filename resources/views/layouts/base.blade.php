<footer class="footer">
    <div class="footer-content">
        <div class="user-info">
            <p>
                {{ Auth::user()->name }}
                <span style="color: green; margin-left: 8px;">
                    <i class="fas fa-circle"></i> En línea
                </span>
            </p>
            <p>&copy; {{ date('Y') }} Graficador 3D y 2D</p>
            <p>Fecha y hora de inicio de sesión: {{ now()->format('d/m/Y H:i:s') }}</p>
        </div>

        <div class="info-section">
            <h3>¿Necesitas ayuda?</h3>
            <ul>
                <li><a href="/contacto"><i class="fas fa-envelope"></i> Contáctanos</a></li>
                <li><a href="/ayuda"><i class="fas fa-question-circle"></i> Ayuda</a></li>
                <li><a href="/terminos"><i class="fas fa-file-alt"></i> Términos y Condiciones</a></li>
            </ul>
        </div>
    </div>
</footer>