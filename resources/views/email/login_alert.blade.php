<!-- resources/views/emails/property/registered.blade.php -->


@section('title', 'Login Alert - Aura Properties')

@section('content')
    <h3>Dear {{ $name }},</h3>

    <p>We've detected a successful login to your Aura Properties account:</p>

    <ul>
        <li><strong>Login Time:</strong> {{ $login_at }}</li>
        <li><strong>System:</strong> Aura Properties & Building Rent Platform</li>
    </ul>

    <p>Our platform features:</p>
    <ul>
        <li>PG/Building Rent Management</li>
        <li>Property Buy/Sell Services</li>
        <li>Secure Transaction System</li>
    </ul>

    <p>If you didn't initiate this login, please contact our support team immediately.</p>

    <p>Best regards,<br>
        <strong>Aura Properties Team</strong></p>

    <p style="font-size: 12px; color: #777;">
        This is an automated message. Please do not reply directly to this email.
    </p>
@endsection
